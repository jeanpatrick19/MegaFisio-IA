<?php

require_once __DIR__ . '/../helpers/ActivityLogger.php';

class ActivityController {
    private $activityLogger;
    private $db;
    
    public function __construct() {
        $this->activityLogger = new ActivityLogger();
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Exibir aba de atividades
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $categoria = $_GET['categoria'] ?? null;
        
        // Buscar atividades
        $atividades = $this->activityLogger->getUserActivities($userId, $limit, $offset, $categoria);
        $totalAtividades = $this->activityLogger->countUserActivities($userId, $categoria);
        $totalPaginas = ceil($totalAtividades / $limit);
        
        // Buscar estatísticas
        $stats = $this->activityLogger->getActivityStats($userId, 30);
        
        // Categorias disponíveis
        $categorias = $this->getCategorias($userId);
        
        $data = [
            'atividades' => $atividades,
            'stats' => $stats,
            'categorias' => $categorias,
            'paginacao' => [
                'pagina_atual' => $page,
                'total_paginas' => $totalPaginas,
                'total_registros' => $totalAtividades,
                'categoria_filtro' => $categoria
            ]
        ];
        
        require_once __DIR__ . '/../views/profile/atividades.php';
    }
    
    /**
     * Exportar dados do usuário
     */
    public function exportData() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $tipo = $_POST['tipo'] ?? 'full';
        
        // Verificar limite diário
        if (!$this->checkExportLimit($userId)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Limite diário de exportações atingido'
            ]);
            return;
        }
        
        try {
            // Criar solicitação de exportação
            $exportId = $this->createExportRequest($userId, $tipo);
            
            // Log da atividade
            $this->activityLogger->logDataExport($tipo, $userId);
            
            // Processar exportação em background (ou imediatamente para desenvolvimento)
            $this->processExport($exportId);
            
            echo json_encode([
                'success' => true,
                'message' => 'Exportação iniciada. Você receberá um email quando estiver pronta.',
                'export_id' => $exportId
            ]);
            
        } catch (Exception $e) {
            error_log("Erro na exportação: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ]);
        }
    }
    
    /**
     * Solicitar exclusão de conta
     */
    public function requestAccountDeletion() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $motivo = $_POST['motivo'] ?? '';
        
        // Verificar se é admin
        if ($_SESSION['user_role'] === 'admin') {
            echo json_encode([
                'success' => false,
                'message' => 'Contas de administrador não podem ser excluídas'
            ]);
            return;
        }
        
        // Verificar se já existe uma solicitação pendente
        if ($this->hasPendingDeletionRequest($userId)) {
            echo json_encode([
                'success' => false,
                'message' => 'Já existe uma solicitação de exclusão pendente'
            ]);
            return;
        }
        
        try {
            $codigoConfirmacao = bin2hex(random_bytes(16));
            
            $sql = "INSERT INTO account_deletion_requests 
                    (user_id, motivo, codigo_confirmacao, ip_solicitante) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $userId,
                $motivo,
                $codigoConfirmacao,
                $this->getClientIP()
            ]);
            
            // Log da atividade
            $this->activityLogger->logAccountDeletionRequest($motivo, $userId);
            
            // TODO: Enviar email de confirmação
            
            echo json_encode([
                'success' => true,
                'message' => 'Solicitação registrada. Verifique seu email para confirmar a exclusão.',
                'codigo' => $codigoConfirmacao // Para desenvolvimento, remover em produção
            ]);
            
        } catch (Exception $e) {
            error_log("Erro na solicitação de exclusão: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ]);
        }
    }
    
    /**
     * Confirmar exclusão de conta
     */
    public function confirmAccountDeletion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $codigo = $_POST['codigo'] ?? '';
        
        if (empty($codigo)) {
            echo json_encode(['success' => false, 'message' => 'Código inválido']);
            return;
        }
        
        try {
            // Buscar solicitação
            $sql = "SELECT * FROM account_deletion_requests 
                    WHERE codigo_confirmacao = ? 
                    AND status = 'pending' 
                    AND data_solicitacao > DATE_SUB(NOW(), INTERVAL 48 HOUR)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$codigo]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Código inválido ou expirado']);
                return;
            }
            
            // Verificar se é admin
            $userSql = "SELECT role FROM users WHERE id = ?";
            $userStmt = $this->db->prepare($userSql);
            $userStmt->execute([$request['user_id']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user['role'] === 'admin') {
                echo json_encode(['success' => false, 'message' => 'Contas de administrador não podem ser excluídas']);
                return;
            }
            
            // Atualizar status da solicitação
            $updateSql = "UPDATE account_deletion_requests 
                         SET status = 'approved', data_processamento = NOW() 
                         WHERE id = ?";
            $updateStmt = $this->db->prepare($updateSql);
            $updateStmt->execute([$request['id']]);
            
            // Log final
            $this->activityLogger->log('account_deletion_confirmed', 'account', 'Exclusão de conta confirmada', $request['user_id']);
            
            // TODO: Processar exclusão real dos dados em background
            
            echo json_encode([
                'success' => true,
                'message' => 'Conta será excluída em até 24 horas. Você receberá um email de confirmação.'
            ]);
            
        } catch (Exception $e) {
            error_log("Erro na confirmação de exclusão: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno. Tente novamente.'
            ]);
        }
    }
    
    /**
     * Métodos auxiliares
     */
    private function getCategorias($userId) {
        try {
            $sql = "SELECT DISTINCT categoria FROM user_logs WHERE user_id = ? AND categoria IS NOT NULL ORDER BY categoria";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function checkExportLimit($userId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM user_data_exports 
                    WHERE user_id = ? AND data_solicitacao >= CURDATE()";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $limit = 3; // TODO: Buscar de settings
            return $result['count'] < $limit;
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function createExportRequest($userId, $tipo) {
        $sql = "INSERT INTO user_data_exports (user_id, tipo_export, ip_solicitante) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $tipo, $this->getClientIP()]);
        return $this->db->lastInsertId();
    }
    
    private function hasPendingDeletionRequest($userId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM account_deletion_requests 
                    WHERE user_id = ? AND status IN ('pending', 'approved')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function processExport($exportId) {
        // TODO: Implementar processamento real da exportação
        // Por enquanto, apenas marcar como completo para desenvolvimento
        try {
            $sql = "UPDATE user_data_exports 
                    SET status = 'completed', 
                        data_processamento = NOW(),
                        arquivo_path = CONCAT('exports/user_', user_id, '_', id, '.json')
                    WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$exportId]);
        } catch (Exception $e) {
            error_log("Erro ao processar exportação: " . $e->getMessage());
        }
    }
    
    private function getClientIP() {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}
?>