<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

/**
 * Gerenciador de Permissões
 * Sistema completo para verificação e gestão de permissões granulares
 */
class PermissionManager {
    private $db;
    private static $instance = null;
    private $userPermissions = [];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Verificar se usuário tem permissão específica
     */
    public function hasPermission($userId, $permission) {
        try {
            // Verificar se é admin primeiro (admins têm acesso total)
            $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $userRole = $stmt->fetchColumn();
            
            if ($userRole === 'admin') {
                return true; // Admins têm todas as permissões
            }
            
            // Cache de permissões do usuário
            if (!isset($this->userPermissions[$userId])) {
                $this->loadUserPermissions($userId);
            }
            
            return in_array($permission, $this->userPermissions[$userId]);
        } catch (Exception $e) {
            error_log("Erro ao verificar permissão: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verificar múltiplas permissões (AND)
     */
    public function hasAllPermissions($userId, $permissions) {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($userId, $permission)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Verificar se tem alguma das permissões (OR)
     */
    public function hasAnyPermission($userId, $permissions) {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($userId, $permission)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Carregar todas as permissões do usuário (role + específicas)
     */
    private function loadUserPermissions($userId) {
        $permissions = [];
        
        // 1. Buscar permissões do role do usuário
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.name
            FROM users u
            JOIN user_roles ur ON u.role = ur.name
            JOIN role_permissions rp ON ur.id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE u.id = ? AND u.deleted_at IS NULL
        ");
        $stmt->execute([$userId]);
        $rolePermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // 2. Buscar permissões específicas do usuário
        $stmt = $this->db->prepare("
            SELECT p.name, up.granted
            FROM user_permissions_detailed up
            JOIN permissions p ON up.permission_id = p.id
            WHERE up.user_id = ? 
            AND (up.expires_at IS NULL OR up.expires_at > NOW())
        ");
        $stmt->execute([$userId]);
        $userSpecificPermissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. Combinar permissões (específicas sobrescrevem role)
        $permissions = $rolePermissions;
        
        foreach ($userSpecificPermissions as $perm) {
            if ($perm['granted']) {
                // Adicionar permissão concedida
                if (!in_array($perm['name'], $permissions)) {
                    $permissions[] = $perm['name'];
                }
            } else {
                // Remover permissão revogada
                $permissions = array_diff($permissions, [$perm['name']]);
            }
        }
        
        $this->userPermissions[$userId] = $permissions;
    }
    
    /**
     * Buscar todas as permissões de um usuário
     */
    public function getUserPermissions($userId) {
        if (!isset($this->userPermissions[$userId])) {
            $this->loadUserPermissions($userId);
        }
        
        return $this->userPermissions[$userId];
    }
    
    /**
     * Conceder permissão específica a um usuário
     */
    public function grantPermission($userId, $permissionName, $grantedBy = null, $expiresAt = null) {
        try {
            // Buscar ID da permissão
            $stmt = $this->db->prepare("SELECT id FROM permissions WHERE name = ?");
            $stmt->execute([$permissionName]);
            $permissionId = $stmt->fetchColumn();
            
            if (!$permissionId) {
                throw new Exception("Permissão '{$permissionName}' não encontrada");
            }
            
            // Inserir ou atualizar permissão específica
            $stmt = $this->db->prepare("
                INSERT INTO user_permissions_detailed (user_id, permission_id, granted, granted_by, expires_at)
                VALUES (?, ?, TRUE, ?, ?)
                ON DUPLICATE KEY UPDATE 
                granted = TRUE, granted_by = VALUES(granted_by), expires_at = VALUES(expires_at), granted_at = NOW()
            ");
            $stmt->execute([$userId, $permissionId, $grantedBy, $expiresAt]);
            
            // Limpar cache
            unset($this->userPermissions[$userId]);
            
            return true;
        } catch (Exception $e) {
            error_log("Erro ao conceder permissão: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Revogar permissão específica de um usuário
     */
    public function revokePermission($userId, $permissionName, $revokedBy = null) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM permissions WHERE name = ?");
            $stmt->execute([$permissionName]);
            $permissionId = $stmt->fetchColumn();
            
            if (!$permissionId) {
                return false;
            }
            
            $stmt = $this->db->prepare("
                INSERT INTO user_permissions_detailed (user_id, permission_id, granted, granted_by)
                VALUES (?, ?, FALSE, ?)
                ON DUPLICATE KEY UPDATE 
                granted = FALSE, granted_by = VALUES(granted_by), granted_at = NOW()
            ");
            $stmt->execute([$userId, $permissionId, $revokedBy]);
            
            // Limpar cache
            unset($this->userPermissions[$userId]);
            
            return true;
        } catch (Exception $e) {
            error_log("Erro ao revogar permissão: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Listar todas as permissões disponíveis
     */
    public function getAllPermissions() {
        $stmt = $this->db->query("
            SELECT name, display_name, description, module, action 
            FROM permissions 
            ORDER BY module, action, name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Listar todos os roles
     */
    public function getAllRoles() {
        $stmt = $this->db->query("
            SELECT id, name, display_name, description, is_system 
            FROM user_roles 
            ORDER BY name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Buscar permissões de um role
     */
    public function getRolePermissions($roleId) {
        $stmt = $this->db->prepare("
            SELECT p.name, p.display_name, p.module, p.action
            FROM role_permissions rp
            JOIN permissions p ON rp.permission_id = p.id
            WHERE rp.role_id = ?
            ORDER BY p.module, p.action
        ");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar se usuário é super admin (bypass de todas as verificações)
     */
    public function isSuperAdmin($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT ur.name 
                FROM users u 
                JOIN user_roles ur ON u.role = ur.name 
                WHERE u.id = ? AND ur.name = 'super_admin'
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchColumn() !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Middleware para verificação de permissões
     */
    public function requirePermission($userId, $permission, $throwException = true) {
        // Super admin sempre tem acesso
        if ($this->isSuperAdmin($userId)) {
            return true;
        }
        
        if (!$this->hasPermission($userId, $permission)) {
            if ($throwException) {
                throw new Exception("Acesso negado. Permissão necessária: {$permission}");
            }
            return false;
        }
        
        return true;
    }
    
    /**
     * Verificar se pode acessar módulo
     */
    public function canAccessModule($userId, $module) {
        // Super admin pode acessar tudo
        if ($this->isSuperAdmin($userId)) {
            return true;
        }
        
        // Verificar se tem alguma permissão do módulo
        $permissions = $this->getUserPermissions($userId);
        foreach ($permissions as $permission) {
            if (strpos($permission, $module . '.') === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Log de verificação de permissão (para auditoria)
     */
    public function logPermissionCheck($userId, $permission, $granted, $context = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO user_access_logs (user_id, action_type, action_details, success)
                VALUES (?, 'permission_change', ?, ?)
            ");
            
            $details = json_encode([
                'permission' => $permission,
                'context' => $context,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
            $stmt->execute([$userId, $details, $granted]);
        } catch (Exception $e) {
            error_log("Erro ao logar verificação de permissão: " . $e->getMessage());
        }
    }
}
?>