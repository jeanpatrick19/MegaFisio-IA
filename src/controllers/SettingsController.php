<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once 'BaseController.php';

class SettingsController extends BaseController {
    
    public function index() {
        // Verificar se usuário está logado e é admin
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
            return;
        }
        
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
            return;
        }
        
        $data = [
            'title' => 'Configurações do Sistema',
            'pageTitle' => 'Configurações',
            'currentPage' => 'settings',
            'user' => $this->user
        ];
        
        $this->render('admin/configuracoes-sistema', $data, 'fisioterapia-premium');
    }
    
    public function update() {
        if (!$this->isLoggedIn() || !$this->isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Acesso negado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        try {
            // Aqui você processaria as configurações
            // Por enquanto apenas retorna sucesso
            echo json_encode(['success' => true, 'message' => 'Configurações atualizadas com sucesso']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function saveLogoIdentity() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        if (!$this->isAdmin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acesso negado']);
            exit;
        }
        
        // Garantir que a tabela system_settings existe
        require_once SRC_PATH . '/models/SmartMigrationManager.php';
        SmartMigrationManager::ensureTable('system_settings');
        
        try {
            $data = [
                'nome_sistema' => trim($_POST['nome_sistema'] ?? ''),
                'slogan_sistema' => trim($_POST['slogan_sistema'] ?? '')
            ];
            
            // Validações básicas
            if (empty($data['nome_sistema'])) {
                throw new Exception('Nome do sistema é obrigatório');
            }
            
            // Salvar configurações na tabela system_settings
            $this->saveSystemSetting('system', 'name', $data['nome_sistema']);
            $this->saveSystemSetting('system', 'slogan', $data['slogan_sistema']);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'logo_identity_updated', 'Configurações de logo e identidade atualizadas', true);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Configurações de logo e identidade salvas com sucesso!']);
            
        } catch (Exception $e) {
            error_log("Erro ao salvar logo e identidade: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function saveSystemConfig() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        if (!$this->isAdmin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acesso negado']);
            exit;
        }
        
        try {
            $data = [
                'fuso_horario' => $_POST['fuso_horario'] ?? 'America/Sao_Paulo',
                'idioma_sistema' => $_POST['idioma_sistema'] ?? 'pt-BR',
                'timeout_sessao' => (int)($_POST['timeout_sessao'] ?? 60),
                'max_tentativas_login' => (int)($_POST['max_tentativas_login'] ?? 5),
                'logs_detalhados' => isset($_POST['logs_detalhados']) ? 1 : 0,
                'backup_automatico' => isset($_POST['backup_automatico']) ? 1 : 0,
                'modo_manutencao' => isset($_POST['modo_manutencao']) ? 1 : 0
            ];
            
            // Salvar cada configuração
            foreach ($data as $key => $value) {
                $this->saveSystemSetting('system', $key, $value);
            }
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'system_config_updated', 'Configurações do sistema atualizadas', true);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Configurações do sistema salvas com sucesso!']);
            
        } catch (Exception $e) {
            error_log("Erro ao salvar configurações do sistema: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function saveIntegrationEmail() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        if (!$this->isAdmin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acesso negado']);
            exit;
        }
        
        try {
            $data = [
                'smtp_servidor' => trim($_POST['smtp_servidor'] ?? ''),
                'smtp_porta' => (int)($_POST['smtp_porta'] ?? 587),
                'smtp_email' => trim($_POST['smtp_email'] ?? ''),
                'smtp_senha' => trim($_POST['smtp_senha'] ?? '')
            ];
            
            // Validações básicas
            if (empty($data['smtp_servidor']) || empty($data['smtp_email'])) {
                throw new Exception('Servidor SMTP e email são obrigatórios');
            }
            
            if (!filter_var($data['smtp_email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }
            
            // Salvar configurações SMTP
            foreach ($data as $key => $value) {
                $this->saveSystemSetting('email', $key, $value);
            }
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'email_integration_updated', 'Configurações SMTP atualizadas', true);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Configurações SMTP salvas com sucesso!']);
            
        } catch (Exception $e) {
            error_log("Erro ao salvar configurações SMTP: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    private function saveSystemSetting($category, $key, $value) {
        $stmt = $this->db->prepare("
            INSERT INTO system_settings (category, `key`, value, type, updated_at)
            VALUES (?, ?, ?, 'string', NOW())
            ON DUPLICATE KEY UPDATE
            value = VALUES(value), updated_at = NOW()
        ");
        
        $stmt->execute([$category, $key, $value]);
    }
}