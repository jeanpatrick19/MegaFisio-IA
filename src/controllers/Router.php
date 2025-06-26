<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

class Router {
    private $routes = [];
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->defineRoutes();
    }
    
    private function defineRoutes() {
        $this->routes = [
            // Páginas públicas
            '' => ['controller' => 'HomeController', 'method' => 'welcome'],
            'welcome' => ['controller' => 'HomeController', 'method' => 'welcome'],
            'login' => ['controller' => 'AuthController', 'method' => 'login'],
            'logout' => ['controller' => 'AuthController', 'method' => 'logout'],
            'register' => ['controller' => 'AuthController', 'method' => 'register'],
            
            // Dashboard
            'dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
            
            // Gerenciamento de senhas
            'change-password' => ['controller' => 'PasswordController', 'method' => 'change'],
            'forgot-password' => ['controller' => 'PasswordController', 'method' => 'forgotPassword'],
            'reset-password' => ['controller' => 'PasswordController', 'method' => 'resetPassword'],
            
            // Perfil do usuário
            'profile' => ['controller' => 'ProfileController', 'method' => 'index'],
            'profile/edit' => ['controller' => 'ProfileController', 'method' => 'edit'],
            'profile/save-personal-data' => ['controller' => 'ProfileController', 'method' => 'savePersonalData'],
            'profile/save-professional-data' => ['controller' => 'ProfileController', 'method' => 'saveProfessionalData'],
            'profile/change-password' => ['controller' => 'ProfileController', 'method' => 'changePassword'],
            'profile/save-preferences' => ['controller' => 'ProfileController', 'method' => 'savePreferences'],
            'profile/upload-avatar' => ['controller' => 'ProfileController', 'method' => 'uploadAvatar'],
            'profile/select-default-avatar' => ['controller' => 'ProfileController', 'method' => 'selectDefaultAvatar'],
            'profile/privacy' => ['controller' => 'ProfileController', 'method' => 'privacy'],
            'profile/export-data' => ['controller' => 'ProfileController', 'method' => 'exportData'],
            'profile/request-deletion' => ['controller' => 'ProfileController', 'method' => 'requestDeletion'],
            
            // Admin - Usuários
            'admin/users' => ['controller' => 'UserController', 'method' => 'index'],
            'admin/users/create' => ['controller' => 'UserController', 'method' => 'create'],
            'admin/users/edit' => ['controller' => 'UserController', 'method' => 'edit'],
            'admin/users/delete' => ['controller' => 'UserController', 'method' => 'delete'],
            'admin/users/change-password' => ['controller' => 'UserController', 'method' => 'changePassword'],
            'admin/users/unlock' => ['controller' => 'UserController', 'method' => 'unlock'],
            
            // Admin - Dashboard
            'admin/dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
            
            // Assistente IA
            'ai' => ['controller' => 'AIController', 'method' => 'index'],
            
            // Admin - Assistente IA
            'admin/ai' => ['controller' => 'AIController', 'method' => 'index'],
            
            // Admin - Perfil
            'admin/profile' => ['controller' => 'ProfileController', 'method' => 'index'],
            'admin/profile/edit' => ['controller' => 'ProfileController', 'method' => 'edit'],
            'admin/profile/save-personal-data' => ['controller' => 'ProfileController', 'method' => 'savePersonalData'],
            'admin/profile/save-professional-data' => ['controller' => 'ProfileController', 'method' => 'saveProfessionalData'],
            'admin/profile/change-password' => ['controller' => 'ProfileController', 'method' => 'changePassword'],
            'admin/profile/save-preferences' => ['controller' => 'ProfileController', 'method' => 'savePreferences'],
            'admin/profile/upload-avatar' => ['controller' => 'ProfileController', 'method' => 'uploadAvatar'],
            'admin/profile/select-default-avatar' => ['controller' => 'ProfileController', 'method' => 'selectDefaultAvatar'],
            'admin/profile/privacy' => ['controller' => 'ProfileController', 'method' => 'privacy'],
            'admin/profile/export-data' => ['controller' => 'ProfileController', 'method' => 'exportData'],
            'admin/profile/request-deletion' => ['controller' => 'ProfileController', 'method' => 'requestDeletion'],
            
            // Admin - Configurações
            'admin/settings' => ['controller' => 'SettingsController', 'method' => 'index'],
            'admin/settings/save-logo-identity' => ['controller' => 'SettingsController', 'method' => 'saveLogoIdentity'],
            'admin/settings/save-system-config' => ['controller' => 'SettingsController', 'method' => 'saveSystemConfig'],
            'admin/settings/save-integration-email' => ['controller' => 'SettingsController', 'method' => 'saveIntegrationEmail'],
            
            // Admin - Logs
            'admin/logs' => ['controller' => 'LogsController', 'method' => 'index'],
            
            // Admin - Prompts IA
            'admin/prompts' => ['controller' => 'PromptController', 'method' => 'index'],
            'admin/prompts/create' => ['controller' => 'PromptController', 'method' => 'create'],
            'admin/prompts/edit' => ['controller' => 'PromptController', 'method' => 'edit'],
            'admin/prompts/delete' => ['controller' => 'PromptController', 'method' => 'delete'],
            'admin/prompts/history' => ['controller' => 'PromptController', 'method' => 'history'],
            'admin/prompts/toggle-status' => ['controller' => 'PromptController', 'method' => 'toggleStatus'],
            
            // API endpoints
            'api/auth/login' => ['controller' => 'AuthController', 'method' => 'apiLogin'],
            'api/auth/logout' => ['controller' => 'AuthController', 'method' => 'apiLogout'],
            'api/auth/register' => ['controller' => 'AuthController', 'method' => 'apiRegister'],
        ];
    }
    
    public function handleRequest() {
        // Gerar token CSRF se não existir
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';
        
        if (array_key_exists($route, $this->routes)) {
            $controllerName = $this->routes[$route]['controller'];
            $methodName = $this->routes[$route]['method'];
            
            $controllerFile = SRC_PATH . '/controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName($this->db);
                    
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName();
                    } else {
                        $this->show404();
                    }
                } else {
                    $this->show404();
                }
            } else {
                $this->show404();
            }
        } else {
            $this->show404();
        }
    }
    
    private function show404() {
        http_response_code(404);
        require_once SRC_PATH . '/views/404.php';
        exit;
    }
}