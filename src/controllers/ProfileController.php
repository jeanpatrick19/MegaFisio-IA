<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class ProfileController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Buscar dados completos do usuário
        $stmt = $this->db->prepare("
            SELECT id, email, name, role, status, created_at, last_login, updated_at
            FROM users 
            WHERE id = ? AND deleted_at IS NULL
        ");
        $stmt->execute([$this->user['id']]);
        $userProfile = $stmt->fetch();
        
        if (!$userProfile) {
            $this->logout();
        }
        
        // Buscar últimos acessos
        $recentLogins = $this->getRecentLogins();
        
        // Buscar estatísticas de uso
        $usageStats = $this->getUserStats();
        
        $this->render('profile/meu-perfil', [
            'title' => 'Meu Perfil',
            'currentPage' => 'profile',
            'user' => $this->user,
            'userProfile' => $userProfile,
            'recentLogins' => $recentLogins,
            'usageStats' => $usageStats
        ], 'fisioterapia-premium');
    }
    
    public function edit() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit();
            return;
        }
        
        $this->render('profile/edit');
    }
    
    private function processEdit() {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];
        
        $errors = $this->validate([
            'name' => ['required' => true, 'min' => 3, 'max' => 255],
            'email' => ['required' => true, 'email' => true]
        ]);
        
        if (!empty($errors)) {
            $this->flash('error', 'Por favor, corrija os erros no formulário');
            return;
        }
        
        // Verificar se email já existe para outro usuário
        if ($data['email'] !== $this->user['email']) {
            $stmt = $this->db->prepare("
                SELECT id FROM users 
                WHERE email = ? AND id != ? AND deleted_at IS NULL
            ");
            $stmt->execute([$data['email'], $this->user['id']]);
            
            if ($stmt->fetch()) {
                $this->flash('error', 'Este email já está em uso por outro usuário');
                return;
            }
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET name = ?, email = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([$data['name'], $data['email'], $this->user['id']]);
            
            // Atualizar dados da sessão
            $_SESSION['user_name'] = $data['name'];
            $_SESSION['user_email'] = $data['email'];
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'profile_updated', 
                'Usuário atualizou próprio perfil', true);
            
            $this->flash('success', 'Perfil atualizado com sucesso!');
            $this->redirect('/profile');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao atualizar perfil. Tente novamente.');
            error_log("Erro ao atualizar perfil: " . $e->getMessage());
        }
    }
    
    public function privacy() {
        $this->requireAuth();
        
        // Buscar dados pessoais do usuário para LGPD
        $personalData = $this->getPersonalData();
        
        $this->render('profile/privacy', [
            'personalData' => $personalData
        ]);
    }
    
    public function exportData() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile/privacy');
        }
        
        try {
            $userData = $this->getPersonalData();
            $logs = $this->getUserLogs();
            
            $exportData = [
                'user_info' => $userData,
                'access_logs' => $logs,
                'export_date' => date('Y-m-d H:i:s'),
                'export_requested_by' => $this->user['email']
            ];
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'data_export', 
                'Usuário solicitou exportação de dados', true);
            
            // Headers para download
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="meus_dados_megafisio_' . date('Y-m-d') . '.json"');
            
            echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao exportar dados. Tente novamente.');
            error_log("Erro na exportação de dados: " . $e->getMessage());
            $this->redirect('/profile/privacy');
        }
    }
    
    public function requestDeletion() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile/privacy');
        }
        
        $reason = trim($_POST['reason'] ?? '');
        
        try {
            // Log da solicitação
            $this->logUserAction($this->user['id'], 'deletion_requested', 
                "Usuário solicitou exclusão de dados. Motivo: $reason", true);
            
            // Criar notificação para admin
            $stmt = $this->db->prepare("
                INSERT INTO notifications (user_id, type, title, message, data)
                SELECT id, 'admin_alert', 'Solicitação de Exclusão de Dados', 
                       CONCAT('Usuário ', ?, ' solicitou exclusão de seus dados'), 
                       JSON_OBJECT('user_id', ?, 'reason', ?)
                FROM users WHERE role = 'admin'
            ");
            $stmt->execute([$this->user['name'], $this->user['id'], $reason]);
            
            $this->flash('success', 'Solicitação de exclusão enviada. Entraremos em contato em até 48 horas.');
            $this->redirect('/profile/privacy');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao processar solicitação. Tente novamente.');
            error_log("Erro na solicitação de exclusão: " . $e->getMessage());
            $this->redirect('/profile/privacy');
        }
    }
    
    private function getRecentLogins($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT acao, ip_address, user_agent, data_hora, sucesso
            FROM user_logs 
            WHERE user_id = ? AND acao IN ('login_success', 'login_failed')
            ORDER BY data_hora DESC
            LIMIT ?
        ");
        $stmt->execute([$this->user['id'], $limit]);
        return $stmt->fetchAll();
    }
    
    private function getUserStats() {
        // Estatísticas de uso do usuário
        $stats = [];
        
        // Total de requisições IA
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total_requests
            FROM ai_requests 
            WHERE user_id = ?
        ");
        $stmt->execute([$this->user['id']]);
        $stats['ai_requests'] = $stmt->fetchColumn();
        
        // Uso por mês
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(data_hora, '%Y-%m') as month,
                COUNT(*) as actions
            FROM user_logs 
            WHERE user_id = ? AND data_hora > DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month DESC
        ");
        $stmt->execute([$this->user['id']]);
        $stats['monthly_activity'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    private function getPersonalData() {
        $stmt = $this->db->prepare("
            SELECT id, email, name, role, status, created_at, last_login, updated_at
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$this->user['id']]);
        
        return $stmt->fetch();
    }
    
    private function getUserLogs($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT acao, detalhes, ip_address, data_hora, sucesso
            FROM user_logs 
            WHERE user_id = ?
            ORDER BY data_hora DESC
            LIMIT ?
        ");
        $stmt->execute([$this->user['id'], $limit]);
        return $stmt->fetchAll();
    }
    
}