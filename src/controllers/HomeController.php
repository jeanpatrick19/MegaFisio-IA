<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class HomeController extends BaseController {
    
    public function index() {
        // Redirecionar para welcome
        $this->redirect('/welcome');
    }
    
    public function welcome() {
        if ($this->user) {
            // Redirecionar usuários logados para dashboard
            $this->redirect('/dashboard');
        }
        
        // Garantir que temos um token CSRF válido
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Mostrar página de boas-vindas para visitantes
        require SRC_PATH . '/views/home/index.php';
    }
}