<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}


abstract class BaseController {
    protected $db;
    protected $user = null;
    protected $activityLogger = null;
    protected $permissionManager = null;
    
    public function __construct($db) {
        $this->db = $db;
        $this->loadHelpers();
        $this->activityLogger = new ActivityLogger();
        $this->permissionManager = PermissionManager::getInstance();
        $this->checkAuth();
    }
    
    protected function loadHelpers() {
        // Carregar helpers
        require_once SRC_PATH . '/helpers/DateTimeHelper.php';
        require_once SRC_PATH . '/helpers/ActivityLogger.php';
        require_once SRC_PATH . '/helpers/PermissionManager.php';
    }
    
    protected function checkAuth() {
        if (isset($_SESSION['user_id'])) {
            // Garantir que as tabelas essenciais existem com estrutura atualizada
            require_once SRC_PATH . '/models/SmartMigrationManager.php';
            SmartMigrationManager::ensureTable('users');
            SmartMigrationManager::ensureTable('user_profiles_extended');
            
            $stmt = $this->db->prepare("
                SELECT u.id, u.email, u.name, u.role, u.status, 
                       p.avatar_type, p.avatar_path, p.avatar_default, p.two_factor_enabled
                FROM users u 
                LEFT JOIN user_profiles_extended p ON u.id = p.user_id
                WHERE u.id = ? AND u.status = 'active'
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $this->user = $stmt->fetch();
            
            if (!$this->user) {
                $this->logout();
            } else {
                // Carregar preferências de data/hora do usuário
                DateTimeHelper::loadUserPreferences($this->user['id'], $this->db);
            }
        }
    }
    
    protected function requireAuth() {
        if (!$this->user) {
            $this->redirect('/login');
        }
    }
    
    protected function requireRole($roles) {
        $this->requireAuth();
        
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        if (!in_array($this->user['role'], $roles)) {
            $this->forbidden();
        }
    }
    
    /**
     * Verificar se usuário tem permissão específica
     */
    protected function requirePermission($permission) {
        $this->requireAuth();
        
        // Admins têm acesso total, não verificar permissões
        if ($this->user['role'] === 'admin') {
            return;
        }
        
        if (!$this->permissionManager->hasPermission($this->user['id'], $permission)) {
            $this->logUserAction($this->user['id'], 'access_denied', "Tentativa de acesso sem permissão: {$permission}", false);
            $this->forbidden();
        }
    }
    
    /**
     * Verificar se usuário pode acessar módulo
     */
    protected function requireModuleAccess($module) {
        $this->requireAuth();
        
        if (!$this->permissionManager->canAccessModule($this->user['id'], $module)) {
            $this->logUserAction($this->user['id'], 'access_denied', "Tentativa de acesso ao módulo: {$module}", false);
            $this->forbidden();
        }
    }
    
    /**
     * Verificar permissão sem lançar exceção
     */
    protected function hasPermission($permission) {
        if (!$this->user) {
            return false;
        }
        
        return $this->permissionManager->hasPermission($this->user['id'], $permission);
    }
    
    /**
     * Verificar se é super admin
     */
    protected function isSuperAdmin() {
        if (!$this->user) {
            return false;
        }
        
        return $this->permissionManager->isSuperAdmin($this->user['id']);
    }
    
    protected function getUserAvatarHtml($size = 'medium') {
        if (!$this->user) {
            return '';
        }
        
        $sizes = [
            'small' => '32px',
            'medium' => '40px', 
            'large' => '48px'
        ];
        
        $avatarSize = $sizes[$size] ?? $sizes['medium'];
        
        $style = "width: {$avatarSize}; height: {$avatarSize}; border-radius: 50%; object-fit: cover; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: " . (intval($avatarSize) * 0.4) . "px;";
        
        if (!empty($this->user['avatar_path']) && $this->user['avatar_type'] === 'upload') {
            return "<img src=\"" . htmlspecialchars($this->user['avatar_path']) . "\" alt=\"Avatar\" style=\"{$style}\">";
        } elseif (!empty($this->user['avatar_default']) && $this->user['avatar_type'] === 'default') {
            return "<div style=\"{$style} background: var(--gradiente-principal); color: white;\">" . htmlspecialchars($this->user['avatar_default']) . "</div>";
        } else {
            $initials = strtoupper(substr($this->user['name'] ?? 'U', 0, 2));
            return "<div style=\"{$style} background: var(--gradiente-principal); color: white;\">{$initials}</div>";
        }
    }

    protected function render($view, $data = [], $layout = null) {
        extract($data);
        $user = $this->user;
        
        if ($layout) {
            // Usar layout específico
            $currentPage = $data['currentPage'] ?? '';
            
            // Capturar o conteúdo da view
            ob_start();
            require SRC_PATH . '/views/' . $view . '.php';
            $content = ob_get_clean();
            
            // Renderizar com layout específico
            require SRC_PATH . '/views/layouts/' . $layout . '.php';
        } else {
            // Layout padrão
            require SRC_PATH . '/views/layout/header.php';
            require SRC_PATH . '/views/' . $view . '.php';
            require SRC_PATH . '/views/layout/footer.php';
        }
    }
    
    protected function renderDashboard($view, $data = []) {
        // Carregar dados básicos do dashboard
        $user = $this->user;
        $aiPrompts = $this->getActiveAIPrompts();
        $currentPage = $data['currentPage'] ?? '';
        
        // Capturar o conteúdo da view
        ob_start();
        extract($data);
        require SRC_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();
        
        // Renderizar com layout fisioterapia premium
        require SRC_PATH . '/views/layouts/fisioterapia-premium.php';
    }
    
    private function getActiveAIPrompts() {
        // Garantir que a tabela ai_prompts tenha todos os campos necessários
        $this->loadSmartMigrationManager();
        SmartMigrationManager::ensureColumn('ai_prompts', 'name', 'VARCHAR(120) NULL');
        SmartMigrationManager::ensureColumn('ai_prompts', 'slug', 'VARCHAR(150) NULL');
        SmartMigrationManager::ensureColumn('ai_prompts', 'description', 'TEXT NULL');
        
        try {
            // Sincronizar dados se necessário
            $this->db->query("
                UPDATE ai_prompts 
                SET name = COALESCE(name, nome),
                    description = COALESCE(description, descricao),
                    slug = COALESCE(slug, LOWER(REPLACE(REPLACE(REPLACE(nome, ' ', '-'), 'ã', 'a'), 'ç', 'c')))
                WHERE (name IS NULL OR name = '') AND nome IS NOT NULL
            ");
            
            // Buscar dados
            $stmt = $this->db->query("
                SELECT id, 
                       COALESCE(name, nome) as name, 
                       COALESCE(slug, LOWER(REPLACE(REPLACE(nome, ' ', '-'), 'ã', 'a'))) as slug, 
                       COALESCE(description, descricao) as description 
                FROM ai_prompts 
                WHERE status IN ('ativo', 'active')
                ORDER BY COALESCE(name, nome) ASC
            ");
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Erro ao buscar prompts IA: " . $e->getMessage());
            return [];
        }
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
    
    protected function isLoggedIn() {
        return !empty($this->user);
    }
    
    protected function isAdmin() {
        return $this->isLoggedIn() && $this->user['role'] === 'admin';
    }
    
    protected function redirect($path) {
        header('Location: ' . BASE_URL . $path);
        exit;
    }
    
    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        header('Location: ' . $referer);
        exit;
    }
    
    protected function forbidden() {
        http_response_code(403);
        $this->render('errors/403');
        exit;
    }
    
    protected function notFound() {
        http_response_code(404);
        $this->render('errors/404');
        exit;
    }
    
    protected function validate($rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $_POST[$field] ?? '';
            
            if (isset($rule['required']) && $rule['required'] && empty($value)) {
                $errors[$field] = "O campo {$field} é obrigatório";
                continue;
            }
            
            if (!empty($value)) {
                if (isset($rule['min']) && strlen($value) < $rule['min']) {
                    $errors[$field] = "O campo {$field} deve ter no mínimo {$rule['min']} caracteres";
                }
                
                if (isset($rule['max']) && strlen($value) > $rule['max']) {
                    $errors[$field] = "O campo {$field} deve ter no máximo {$rule['max']} caracteres";
                }
                
                if (isset($rule['email']) && $rule['email'] && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Email inválido";
                }
                
                if (isset($rule['match']) && $value !== ($_POST[$rule['match']] ?? '')) {
                    $errors[$field] = "Os campos não coincidem";
                }
            }
        }
        
        return $errors;
    }
    
    protected function flash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    protected function getFlash() {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
    
    protected function logout() {
        session_destroy();
        $this->redirect('/login');
    }
    
    protected function log($action, $entityType = null, $entityId = null, $details = null) {
        $stmt = $this->db->prepare("
            INSERT INTO system_logs (user_id, action, entity_type, entity_id, ip_address, user_agent, details)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $this->user['id'] ?? null,
            $action,
            $entityType,
            $entityId,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null,
            $details ? json_encode($details) : null
        ]);
    }
    
    protected function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'agora';
        if ($time < 3600) return floor($time/60) . ' min atrás';
        if ($time < 86400) return floor($time/3600) . ' h atrás';
        if ($time < 2592000) return floor($time/86400) . ' dias atrás';
        if ($time < 31536000) return floor($time/2592000) . ' meses atrás';
        return floor($time/31536000) . ' anos atrás';
    }
    
    protected function validateCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';
            
            if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
                // Regenerar token e redirecionar
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $this->flash('error', 'Sessão expirou. Tente novamente.');
                $this->redirect('/welcome');
                exit;
            }
        }
    }
    
    protected function csrfToken() {
        return $_SESSION['csrf_token'] ?? '';
    }
    
    protected function validateCSRFAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';
            
            if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Log de ações do usuário usando ActivityLogger moderno
     */
    protected function logUserAction($userId, $acao, $detalhes = null, $sucesso = true, $email = null, $categoria = null, $metadados = null) {
        try {
            // Mapear categorias baseadas na ação
            if (!$categoria) {
                $categoria = $this->mapActionToCategory($acao);
            }
            
            // Se email não foi fornecido e temos userId, buscar email
            if (!$email && $userId) {
                $stmt = $this->db->prepare("SELECT email FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $email = $stmt->fetchColumn();
            }
            
            // Usar o ActivityLogger
            $this->activityLogger->log(
                $acao,
                $categoria,
                $detalhes,
                $userId,
                $email,
                $sucesso,
                $metadados
            );
            
        } catch (Exception $e) {
            error_log("Erro ao registrar log de usuário: " . $e->getMessage());
        }
    }
    
    /**
     * Mapear ações para categorias
     */
    private function mapActionToCategory($acao) {
        $categorias = [
            'login' => 'authentication',
            'logout' => 'authentication',
            'login_failed' => 'authentication',
            'login_pending_2fa' => 'authentication',
            '2fa_verified' => 'authentication',
            '2fa_failed' => 'authentication',
            'register' => 'authentication',
            'password_changed' => 'security',
            'password_reset' => 'security',
            '2fa_enabled' => 'security',
            '2fa_disabled' => 'security',
            'profile_updated' => 'profile',
            'profile_view' => 'profile',
            'preferences_updated' => 'profile',
            'avatar_changed' => 'profile',
            'session_created' => 'security',
            'session_revoked' => 'security',
            'data_export' => 'data',
            'account_deletion_request' => 'account',
            'system_access' => 'admin',
            'user_created' => 'admin',
            'user_updated' => 'admin',
            'user_deleted' => 'admin',
            'settings_updated' => 'admin'
        ];
        
        return $categorias[$acao] ?? 'other';
    }
    
    protected function csrfField() {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($this->csrfToken()) . '">';
    }
    
    /**
     * Carrega SmartMigrationManager apenas quando necessário
     */
    private function loadSmartMigrationManager() {
        if (!class_exists('SmartMigrationManager')) {
            require_once __DIR__ . '/../models/SmartMigrationManager.php';
        }
    }
}