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
}