<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class PermissionController extends BaseController {
    
    public function index() {
        // Comentando temporariamente para teste
        // $this->requirePermission('system.admin');
        
        // Buscar usuários com suas permissões
        $users = $this->getUsersWithPermissions();
        
        // Buscar todos os módulos com suas permissões
        $modules = $this->getModulesWithPermissions();
        
        $this->render('admin/permissions/index', [
            'title' => 'Gerenciar Permissões',
            'currentPage' => 'permissions',
            'users' => $users,
            'modules' => $modules
        ], 'fisioterapia-premium');
    }
    
    public function assignUserPermission() {
        $this->requirePermission('system.admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/permissions');
        }
        
        $userId = intval($_POST['user_id'] ?? 0);
        $permissionId = intval($_POST['permission_id'] ?? 0);
        $granted = isset($_POST['granted']) && $_POST['granted'] === '1';
        $expiresAt = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
        
        if (!$userId || !$permissionId) {
            $this->json(['success' => false, 'message' => 'Dados inválidos'], 400);
        }
        
        // Verificar se o usuário não é admin (admins têm acesso total automático)
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $userRole = $stmt->fetchColumn();
        
        if ($userRole === 'admin') {
            $this->json(['success' => false, 'message' => 'Administradores têm acesso total automático'], 400);
        }
        
        try {
            $this->db->beginTransaction();
            
            if ($granted) {
                // Conceder permissão
                $stmt = $this->db->prepare("
                    INSERT INTO user_permissions (user_id, permission_id, granted_by, expires_at)
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                    granted_by = VALUES(granted_by),
                    expires_at = VALUES(expires_at),
                    granted_at = CURRENT_TIMESTAMP,
                    is_active = TRUE
                ");
                $success = $stmt->execute([$userId, $permissionId, $this->user['id'], $expiresAt]);
            } else {
                // Revogar permissão
                $stmt = $this->db->prepare("
                    UPDATE user_permissions 
                    SET is_active = FALSE 
                    WHERE user_id = ? AND permission_id = ?
                ");
                $success = $stmt->execute([$userId, $permissionId]);
            }
            
            $this->db->commit();
            
            // Buscar nome da permissão para log
            $stmt = $this->db->prepare("SELECT display_name FROM permissions WHERE id = ?");
            $stmt->execute([$permissionId]);
            $permissionName = $stmt->fetchColumn();
            
            // Log da ação
            $action = $granted ? 'concedida' : 'revogada';
            $this->logUserAction(
                $userId, 
                'permission_' . ($granted ? 'grant' : 'revoke'), 
                "Permissão '{$permissionName}' {$action} pelo admin {$this->user['name']}", 
                $success
            );
            
            $this->json([
                'success' => $success,
                'message' => $success ? 'Permissão atualizada com sucesso' : 'Erro ao atualizar permissão'
            ]);
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function bulkAssignPermissions() {
        $this->requirePermission('system.admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/permissions');
        }
        
        $userId = intval($_POST['user_id'] ?? 0);
        $permissions = $_POST['permissions'] ?? [];
        
        if (!$userId) {
            $this->json(['success' => false, 'message' => 'Usuário inválido'], 400);
        }
        
        // Verificar se o usuário não é admin (admins têm acesso total automático)
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $userRole = $stmt->fetchColumn();
        
        if ($userRole === 'admin') {
            $this->json(['success' => false, 'message' => 'Administradores têm acesso total automático'], 400);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Desativar todas as permissões atuais do usuário
            $stmt = $this->db->prepare("UPDATE user_permissions SET is_active = FALSE WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Adicionar novas permissões
            if (!empty($permissions)) {
                $stmt = $this->db->prepare("
                    INSERT INTO user_permissions (user_id, permission_id, granted_by) 
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                    is_active = TRUE,
                    granted_by = VALUES(granted_by),
                    granted_at = CURRENT_TIMESTAMP
                ");
                
                foreach ($permissions as $permissionId) {
                    $stmt->execute([$userId, $permissionId, $this->user['id']]);
                }
            }
            
            $this->db->commit();
            
            // Log da ação
            $userName = $this->getUserName($userId);
            $this->logUserAction(
                $userId, 
                'bulk_permissions_updated', 
                "Permissões do usuário {$userName} atualizadas em lote pelo admin {$this->user['name']}", 
                true
            );
            
            $this->json(['success' => true, 'message' => 'Permissões atualizadas com sucesso']);
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getUserPermissions() {
        $this->requirePermission('system.admin');
        
        $userId = intval($_GET['user_id'] ?? 0);
        if (!$userId) {
            $this->json(['success' => false, 'message' => 'Usuário inválido'], 400);
        }
        
        try {
            // Buscar permissões específicas do usuário agrupadas por módulo
            $stmt = $this->db->prepare("
                SELECT 
                    pm.id as module_id, pm.name as module_name, pm.display_name as module_display,
                    p.id as permission_id, p.name as permission_name, p.display_name as permission_display,
                    p.permission_type, p.is_critical,
                    CASE WHEN up.id IS NOT NULL AND up.is_active = 1 
                         AND (up.expires_at IS NULL OR up.expires_at > NOW()) 
                         THEN 1 ELSE 0 END as has_permission,
                    up.expires_at
                FROM permission_modules pm
                JOIN permissions p ON pm.id = p.module_id
                LEFT JOIN user_permissions up ON p.id = up.permission_id AND up.user_id = ?
                WHERE pm.is_active = 1 AND p.is_active = 1
                ORDER BY pm.sort_order, pm.display_name, p.permission_type, p.display_name
            ");
            $stmt->execute([$userId]);
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Agrupar por módulo
            $grouped = [];
            foreach ($permissions as $perm) {
                $moduleKey = $perm['module_name'];
                if (!isset($grouped[$moduleKey])) {
                    $grouped[$moduleKey] = [
                        'id' => $perm['module_id'],
                        'name' => $perm['module_name'],
                        'display_name' => $perm['module_display'],
                        'permissions' => []
                    ];
                }
                
                $grouped[$moduleKey]['permissions'][] = [
                    'id' => $perm['permission_id'],
                    'name' => $perm['permission_name'],
                    'display_name' => $perm['permission_display'],
                    'type' => $perm['permission_type'],
                    'is_critical' => $perm['is_critical'],
                    'has_permission' => $perm['has_permission'],
                    'expires_at' => $perm['expires_at']
                ];
            }
            
            $this->json([
                'success' => true,
                'modules' => $grouped
            ]);
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getPermissionReport() {
        $this->requirePermission('system.admin');
        
        try {
            // Relatório de permissões por usuário
            $stmt = $this->db->query("
                SELECT 
                    u.id, u.name, u.email, u.role,
                    COUNT(DISTINCT up.permission_id) as total_permissions,
                    COUNT(DISTINCT CASE WHEN p.is_critical = 1 THEN up.permission_id END) as critical_permissions,
                    COUNT(DISTINCT CASE WHEN up.expires_at IS NOT NULL AND up.expires_at > NOW() THEN up.permission_id END) as expiring_permissions
                FROM users u
                LEFT JOIN user_permissions up ON u.id = up.user_id AND up.is_active = 1
                LEFT JOIN permissions p ON up.permission_id = p.id
                WHERE u.deleted_at IS NULL
                GROUP BY u.id
                ORDER BY u.name
            ");
            $userReport = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Relatório de permissões por módulo
            $stmt = $this->db->query("
                SELECT 
                    pm.name as module_name, pm.display_name as module_display,
                    COUNT(p.id) as total_permissions,
                    COUNT(CASE WHEN p.is_critical = 1 THEN p.id END) as critical_permissions,
                    COUNT(DISTINCT up.user_id) as users_with_permissions
                FROM permission_modules pm
                LEFT JOIN permissions p ON pm.id = p.module_id AND p.is_active = 1
                LEFT JOIN user_permissions up ON p.id = up.permission_id AND up.is_active = 1
                WHERE pm.is_active = 1
                GROUP BY pm.id
                ORDER BY pm.sort_order
            ");
            $moduleReport = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->json([
                'success' => true,
                'user_report' => $userReport,
                'module_report' => $moduleReport
            ]);
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    private function getUsersWithPermissions() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    u.id, u.name, u.email, u.role, u.status, u.created_at,
                    COUNT(DISTINCT CASE WHEN up.is_active = 1 
                          AND (up.expires_at IS NULL OR up.expires_at > NOW()) 
                          THEN up.permission_id END) as active_permissions_count
                FROM users u
                LEFT JOIN user_permissions up ON u.id = up.user_id
                WHERE u.deleted_at IS NULL AND u.role != 'admin'
                GROUP BY u.id
                ORDER BY u.name
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getUserName($userId) {
        try {
            $stmt = $this->db->prepare("SELECT name FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchColumn() ?: 'Usuário #' . $userId;
        } catch (Exception $e) {
            return 'Usuário #' . $userId;
        }
    }
    
    private function getModulesWithPermissions() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    pm.id, pm.name, pm.display_name, pm.description, pm.icon, pm.sort_order,
                    COUNT(p.id) as permissions_count
                FROM permission_modules pm
                LEFT JOIN permissions p ON pm.id = p.module_id AND p.is_active = 1
                WHERE pm.is_active = 1
                GROUP BY pm.id
                ORDER BY pm.sort_order, pm.display_name
            ");
            $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Buscar permissões para cada módulo
            foreach ($modules as &$module) {
                $stmt = $this->db->prepare("
                    SELECT id, name, display_name, description, permission_type, is_critical
                    FROM permissions
                    WHERE module_id = ? AND is_active = 1
                    ORDER BY permission_type, display_name
                ");
                $stmt->execute([$module['id']]);
                $module['permissions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $modules;
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getUsersWithPermissionsAPI() {
        header('Content-Type: application/json');
        
        try {
            $users = $this->getUsersWithPermissions();
            $this->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getModulesWithPermissionsAPI() {
        header('Content-Type: application/json');
        
        try {
            $modules = $this->getModulesWithPermissions();
            $this->json([
                'success' => true,
                'modules' => $modules
            ]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
}
?>