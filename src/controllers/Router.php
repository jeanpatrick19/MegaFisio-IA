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
        $this->loadDynamicRobotRoutes();
    }
    
    private function defineRoutes() {
        $this->routes = [
            // Páginas públicas
            '' => ['controller' => 'HomeController', 'method' => 'welcome'],
            'welcome' => ['controller' => 'HomeController', 'method' => 'welcome'],
            'login' => ['controller' => 'AuthController', 'method' => 'login'],
            'logout' => ['controller' => 'AuthController', 'method' => 'logout'],
            'register' => ['controller' => 'AuthController', 'method' => 'register'],
            'verify-2fa' => ['controller' => 'AuthController', 'method' => 'verify2FA'],
            'cancel-2fa' => ['controller' => 'AuthController', 'method' => 'cancel2FA'],
            
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
            'profile/get-preferences' => ['controller' => 'ProfileController', 'method' => 'getPreferences'],
            'profile/upload-avatar' => ['controller' => 'ProfileController', 'method' => 'uploadAvatar'],
            'profile/select-default-avatar' => ['controller' => 'ProfileController', 'method' => 'selectDefaultAvatar'],
            'profile/privacy' => ['controller' => 'ProfileController', 'method' => 'privacy'],
            'profile/request-deletion' => ['controller' => 'ProfileController', 'method' => 'requestDeletion'],
            'profile/enable2FA' => ['controller' => 'ProfileController', 'method' => 'enable2FA'],
            'profile/confirm2FA' => ['controller' => 'ProfileController', 'method' => 'confirm2FA'],
            'profile/disable2FA' => ['controller' => 'ProfileController', 'method' => 'disable2FA'],
            'profile/get2FAStatus' => ['controller' => 'ProfileController', 'method' => 'get2FAStatus'],
            'profile/regenerateBackupCodes' => ['controller' => 'ProfileController', 'method' => 'regenerateBackupCodes'],
            'profile/getSessions' => ['controller' => 'ProfileController', 'method' => 'getSessions'],
            'profile/revokeSession' => ['controller' => 'ProfileController', 'method' => 'revokeSession'],
            'profile/revokeAllSessions' => ['controller' => 'ProfileController', 'method' => 'revokeAllSessions'],
            
            // Atividades da conta
            'profile/activities' => ['controller' => 'ActivityController', 'method' => 'index'],
            'profile/export-data' => ['controller' => 'ActivityController', 'method' => 'exportData'],
            'profile/request-account-deletion' => ['controller' => 'ActivityController', 'method' => 'requestAccountDeletion'],
            'profile/confirm-account-deletion' => ['controller' => 'ActivityController', 'method' => 'confirmAccountDeletion'],
            
            // Admin - Usuários
            'admin/users' => ['controller' => 'UserController', 'method' => 'index'],
            'admin/users/create' => ['controller' => 'UserController', 'method' => 'create'],
            'admin/users/edit' => ['controller' => 'UserController', 'method' => 'edit'],
            'admin/users/delete' => ['controller' => 'UserController', 'method' => 'delete'],
            'admin/users/change-password' => ['controller' => 'UserController', 'method' => 'changePassword'],
            'admin/users/unlock' => ['controller' => 'UserController', 'method' => 'unlock'],
            'admin/users/toggle-status' => ['controller' => 'UserController', 'method' => 'toggleStatus'],
            'admin/users/permissions' => ['controller' => 'UserController', 'method' => 'permissions'],
            'admin/users/logs' => ['controller' => 'UserController', 'method' => 'logs'],
            'admin/users/privacy' => ['controller' => 'UserController', 'method' => 'privacy'],
            'admin/users/export' => ['controller' => 'UserController', 'method' => 'export'],
            'admin/users/get-user-data' => ['controller' => 'UserController', 'method' => 'getUserData'],
            'admin/users/update' => ['controller' => 'UserController', 'method' => 'updateUser'],
            
            // Admin - Permissões
            'admin/permissions' => ['controller' => 'PermissionController', 'method' => 'index'],
            'admin/permissions/assignUserPermission' => ['controller' => 'PermissionController', 'method' => 'assignUserPermission'],
            'admin/permissions/bulkAssignPermissions' => ['controller' => 'PermissionController', 'method' => 'bulkAssignPermissions'],
            'admin/permissions/bulk-assign' => ['controller' => 'PermissionController', 'method' => 'bulkAssignPermissions'],
            'admin/permissions/getUserPermissions' => ['controller' => 'PermissionController', 'method' => 'getUserPermissions'],
            'admin/permissions/user-permissions' => ['controller' => 'PermissionController', 'method' => 'getUserPermissions'],
            'admin/permissions/getUsersWithPermissions' => ['controller' => 'PermissionController', 'method' => 'getUsersWithPermissionsAPI'],
            'admin/permissions/getModulesWithPermissions' => ['controller' => 'PermissionController', 'method' => 'getModulesWithPermissionsAPI'],
            'admin/permissions/getPermissionReport' => ['controller' => 'PermissionController', 'method' => 'getPermissionReport'],
            'admin/permissions/users-api' => ['controller' => 'PermissionController', 'method' => 'getUsersAPI'],
            'admin/permissions/get-user-permissions' => ['controller' => 'PermissionController', 'method' => 'getUserPermissionsNew'],
            'admin/permissions/save-user-permissions' => ['controller' => 'PermissionController', 'method' => 'saveUserPermissions'],
            
            // Admin - Dashboard
            'admin/dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
            
            // Assistente IA
            'ai' => ['controller' => 'AIController', 'method' => 'index'],
            'ai/configuracoes' => ['controller' => 'AIController', 'method' => 'configuracoes'],
            'ai/save-config' => ['controller' => 'AIController', 'method' => 'saveApiConfig'],
            'ai/save-robot-config' => ['controller' => 'AIController', 'method' => 'saveRobotConfig'],
            'ai/check-status' => ['controller' => 'AIController', 'method' => 'checkApiStatus'],
            'ai/gerar-conteudo' => ['controller' => 'AIController', 'method' => 'gerarConteudo'],
            'ai/registrar-uso' => ['controller' => 'AIController', 'method' => 'registrarUso'],
            'ai/update-robot-status' => ['controller' => 'AIController', 'method' => 'updateRobotStatus'],
            
            // Admin - Assistente IA
            'admin/ai' => ['controller' => 'AIController', 'method' => 'index'],
            
            // Admin - Perfil
            'admin/profile' => ['controller' => 'ProfileController', 'method' => 'index'],
            'admin/profile/edit' => ['controller' => 'ProfileController', 'method' => 'edit'],
            'admin/profile/save-personal-data' => ['controller' => 'ProfileController', 'method' => 'savePersonalData'],
            'admin/profile/save-professional-data' => ['controller' => 'ProfileController', 'method' => 'saveProfessionalData'],
            'admin/profile/change-password' => ['controller' => 'ProfileController', 'method' => 'changePassword'],
            'admin/profile/save-preferences' => ['controller' => 'ProfileController', 'method' => 'savePreferences'],
            'admin/profile/get-preferences' => ['controller' => 'ProfileController', 'method' => 'getPreferences'],
            'admin/profile/upload-avatar' => ['controller' => 'ProfileController', 'method' => 'uploadAvatar'],
            'admin/profile/select-default-avatar' => ['controller' => 'ProfileController', 'method' => 'selectDefaultAvatar'],
            'admin/profile/privacy' => ['controller' => 'ProfileController', 'method' => 'privacy'],
            'admin/profile/request-deletion' => ['controller' => 'ProfileController', 'method' => 'requestDeletion'],
            'admin/profile/enable2FA' => ['controller' => 'ProfileController', 'method' => 'enable2FA'],
            'admin/profile/confirm2FA' => ['controller' => 'ProfileController', 'method' => 'confirm2FA'],
            'admin/profile/disable2FA' => ['controller' => 'ProfileController', 'method' => 'disable2FA'],
            'admin/profile/get2FAStatus' => ['controller' => 'ProfileController', 'method' => 'get2FAStatus'],
            'admin/profile/regenerateBackupCodes' => ['controller' => 'ProfileController', 'method' => 'regenerateBackupCodes'],
            'admin/profile/getSessions' => ['controller' => 'ProfileController', 'method' => 'getSessions'],
            'admin/profile/revokeSession' => ['controller' => 'ProfileController', 'method' => 'revokeSession'],
            'admin/profile/revokeAllSessions' => ['controller' => 'ProfileController', 'method' => 'revokeAllSessions'],
            
            // Admin - Atividades da conta
            'admin/profile/activities' => ['controller' => 'ActivityController', 'method' => 'index'],
            'admin/profile/export-data' => ['controller' => 'ActivityController', 'method' => 'exportData'],
            
            // Admin - Configurações
            'admin/settings' => ['controller' => 'SettingsController', 'method' => 'index'],
            'admin/settings/save-logo-identity' => ['controller' => 'SettingsController', 'method' => 'saveLogoIdentity'],
            'admin/settings/save-system-config' => ['controller' => 'SettingsController', 'method' => 'saveSystemConfig'],
            'admin/settings/save-integration-email' => ['controller' => 'SettingsController', 'method' => 'saveIntegrationEmail'],
            
            // Admin - Logs
            'admin/logs' => ['controller' => 'LogsController', 'method' => 'index'],
            'admin/logs/export' => ['controller' => 'LogsController', 'method' => 'export'],
            'admin/logs/clear' => ['controller' => 'LogsController', 'method' => 'clear'],
            
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
    
    /**
     * Carrega rotas dinâmicas dos robôs Dr. IA do banco de dados
     */
    private function loadDynamicRobotRoutes() {
        try {
            // Verificar se a tabela dr_ai_robots existe
            $stmt = $this->db->prepare("SHOW TABLES LIKE 'dr_ai_robots'");
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                return; // Tabela não existe ainda
            }
            
            // Buscar robôs que têm páginas criadas
            $stmt = $this->db->prepare("
                SELECT robot_slug, controller_method, route_path 
                FROM dr_ai_robots 
                WHERE has_page = TRUE 
                AND is_active = TRUE 
                AND controller_method IS NOT NULL
                ORDER BY sort_order, robot_name
            ");
            $stmt->execute();
            $robots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Adicionar rotas dinamicamente
            foreach ($robots as $robot) {
                $routePath = $robot['route_path'] ?: 'ai/' . $robot['robot_slug'];
                $this->routes[$routePath] = [
                    'controller' => 'AIController',
                    'method' => $robot['controller_method']
                ];
            }
            
        } catch (Exception $e) {
            // Silenciosamente ignora erros de banco - sistema deve funcionar mesmo sem tabela de robôs
            error_log("Erro ao carregar rotas dinâmicas de robôs: " . $e->getMessage());
        }
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