<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Redirecionar baseado no role
        switch ($this->user['role']) {
            case 'admin':
                $this->adminDashboard();
                break;
            case 'professional':
            case 'usuario': // Compatibilidade com role antigo
                $this->professionalDashboard();
                break;
            case 'patient':
                $this->patientDashboard();
                break;
            default:
                $this->notFound();
        }
    }
    
    private function adminDashboard() {
        // Estatísticas reais do sistema
        $stats = [
            // Usuários
            'total_users' => $this->getTotalUsers(),
            'admins' => $this->getUsersByRole('admin'),
            'fisioterapeutas' => $this->getUsersByRole('professional'),
            'active_sessions' => $this->getActiveSessions(),
            'users_growth' => $this->getUsersGrowth(),
            
            // IA e Robôs Dr. IA
            'total_robots' => $this->getTotalRobots(),
            'active_robots' => $this->getActiveRobots(),
            'ai_requests_today' => $this->getAIRequestsToday(),
            'ai_requests_total' => $this->getAIRequestsTotal(),
            'ai_success_rate' => $this->getAISuccessRate(),
            'ai_growth' => $this->getAIGrowth(),
            
            // Sistema
            'system_health' => $this->getSystemHealth(),
            'storage_percent' => $this->getStoragePercent(),
            'database_size' => $this->getDatabaseSize(),
            'api_status' => $this->getAPIStatus(),
            
            // Top Robôs Dr. IA mais usados
            'top_robots' => $this->getTopRobots(),
            
            // Estatísticas por categoria
            'robots_by_category' => $this->getRobotsByCategory()
        ];
        
        // Atividades recentes
        $recentActivities = $this->getRecentActivities();
        
        // Logs recentes de API
        $recentApiLogs = $this->getRecentApiLogs();
        
        // Usar o mesmo padrão das outras páginas
        $this->render('dashboard/fisio-admin', [
            'title' => 'Dashboard Clínico Administrativo',
            'currentPage' => 'dashboard',
            'user' => $this->user,
            'stats' => $stats,
            'activities' => $recentActivities,
            'apiLogs' => $recentApiLogs
        ], 'fisioterapia-premium');
    }
    
    private function professionalDashboard() {
        // Buscar dados completos do usuário incluindo created_at
        $userData = $this->getCompleteUserData($this->user['id']);
        
        // Buscar robôs reais disponíveis para este usuário
        $userRobots = $this->getUserAvailableRobots($this->user['id']);
        
        // Estatísticas do profissional
        $stats = [
            'patients_count' => 0, // Implementar depois
            'documents_created' => $this->getDocumentsCreated($this->user['id']),
            'ai_requests' => $this->getUserAIRequests($this->user['id']),
            'monthly_usage' => $this->getMonthlyUsage($this->user['id']),
            'available_robots' => count($userRobots['visible']),
            'active_robots' => count($userRobots['usable']),
            'user_robots' => $userRobots
        ];
        
        $this->render('dashboard/professional', [
            'title' => 'Dashboard Profissional - MegaFisio IA',
            'currentPage' => 'dashboard',
            'user' => $userData ?: $this->user, // Usar dados completos ou fallback
            'stats' => $stats
        ], 'fisioterapia-premium');
    }
    
    private function patientDashboard() {
        // Informações do paciente
        $data = [
            'appointments' => [], // Implementar depois
            'documents' => [], // Implementar depois
            'notifications' => $this->getUnreadNotifications()
        ];
        
        $this->render('dashboard/patient', [
            'title' => 'Dashboard Paciente',
            'currentPage' => 'dashboard',
            'user' => $this->user,
            'appointments' => $data['appointments'],
            'documents' => $data['documents'],
            'notifications' => $data['notifications']
        ], 'fisioterapia-premium');
    }
    
    private function getTotalUsers() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL");
        return $stmt->fetchColumn();
    }
    
    private function getActiveSessions() {
        $stmt = $this->db->query("
            SELECT COUNT(*) FROM user_sessions 
            WHERE last_activity > UNIX_TIMESTAMP() - " . SESSION_LIFETIME
        );
        return $stmt->fetchColumn();
    }
    
    private function getAIRequestsToday() {
        $stmt = $this->db->query("
            SELECT COUNT(*) FROM ai_requests 
            WHERE DATE(created_at) = CURDATE()
        ");
        return $stmt->fetchColumn();
    }
    
    private function getSystemHealth() {
        // Verificar saúde do sistema
        $health = [
            'database' => $this->checkDatabaseHealth(),
            'disk_space' => disk_free_space('/') > 1073741824, // 1GB livre
            'error_rate' => $this->getErrorRate() < 0.05 // menos de 5% de erro
        ];
        
        return array_sum($health) === count($health) ? 'healthy' : 'warning';
    }
    
    private function checkDatabaseHealth() {
        try {
            $stmt = $this->db->query("SELECT 1");
            return $stmt !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function getErrorRate() {
        $stmt = $this->db->query("
            SELECT 
                COUNT(CASE WHEN status = 'failed' THEN 1 END) / COUNT(*) as error_rate
            FROM ai_requests 
            WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        return $stmt->fetchColumn() ?: 0;
    }
    
    private function getRecentActivities($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT 
                l.*,
                u.name as user_name,
                u.email as user_email
            FROM system_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    private function getDocumentsCreated($userId) {
        // Placeholder - implementar quando tiver tabela de documentos
        return 0;
    }
    
    private function getUserAIRequests($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM ai_requests 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
    
    private function getMonthlyUsage($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as requests,
                SUM(tokens_used) as tokens
            FROM ai_requests 
            WHERE user_id = ? 
                AND created_at > DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    private function getUnreadNotifications() {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? AND read_at IS NULL
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $stmt->execute([$this->user['id']]);
        return $stmt->fetchAll();
    }
    
    private function getStoragePercent() {
        // Calcular porcentagem de armazenamento baseado no tamanho do banco
        try {
            $stmt = $this->db->query("
                SELECT ROUND(((data_length + index_length) / 1024 / 1024), 2) AS db_size_mb
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
            ");
            $sizeResult = $stmt->fetchAll();
            $totalSize = array_sum(array_column($sizeResult, 'db_size_mb'));
            
            // Assumir limite de 1GB = 1024MB
            $maxSize = 1024;
            return min(100, round(($totalSize / $maxSize) * 100));
        } catch (Exception $e) {
            return 25; // Valor padrão seguro
        }
    }
    
    private function getUsersGrowth() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    (SELECT COUNT(*) FROM users WHERE created_at >= CURDATE() - INTERVAL 30 DAY) as this_month,
                    (SELECT COUNT(*) FROM users WHERE created_at >= CURDATE() - INTERVAL 60 DAY AND created_at < CURDATE() - INTERVAL 30 DAY) as last_month
            ");
            $result = $stmt->fetch();
            
            if ($result['last_month'] == 0) {
                return $result['this_month'] > 0 ? 100 : 0;
            }
            
            return round((($result['this_month'] - $result['last_month']) / $result['last_month']) * 100);
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getAIGrowth() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    (SELECT COUNT(*) FROM user_logs WHERE acao LIKE '%ia%' AND data_hora >= CURDATE()) as today,
                    (SELECT COUNT(*) FROM user_logs WHERE acao LIKE '%ia%' AND data_hora >= CURDATE() - INTERVAL 1 DAY AND data_hora < CURDATE()) as yesterday
            ");
            $result = $stmt->fetch();
            
            return $result['today'] - $result['yesterday'];
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getAIUsageByCategory($categoria) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM user_logs 
                WHERE (detalhes LIKE ? OR acao LIKE ?) 
                AND data_hora >= CURDATE()
            ");
            $stmt->execute(["%{$categoria}%", "%{$categoria}%"]);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    // Novos métodos para dados reais dos robôs Dr. IA
    private function getUsersByRole($role) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = ? AND deleted_at IS NULL");
            $stmt->execute([$role]);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getTotalRobots() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM dr_ai_robots");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getActiveRobots() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM dr_ai_robots WHERE is_active = 1");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getAIRequestsTotal() {
        try {
            $stmt = $this->db->query("
                SELECT COUNT(*) FROM api_usage_logs 
                WHERE created_at IS NOT NULL
            ");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getAISuccessRate() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    ROUND(
                        SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1
                    ) as success_rate
                FROM api_usage_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ");
            return $stmt->fetchColumn() ?: 100;
        } catch (Exception $e) {
            return 100;
        }
    }
    
    private function getDatabaseSize() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'db_size_mb'
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
            ");
            return $stmt->fetchColumn() . ' MB';
        } catch (Exception $e) {
            return 'N/A';
        }
    }
    
    private function getAPIStatus() {
        try {
            $stmt = $this->db->query("
                SELECT COUNT(*) FROM api_usage_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            ");
            return $stmt->fetchColumn() > 0 ? 'online' : 'idle';
        } catch (Exception $e) {
            return 'offline';
        }
    }
    
    private function getTopRobots() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    r.robot_name,
                    r.robot_category,
                    r.robot_icon,
                    COALESCE(COUNT(l.id), 0) as usage_count,
                    COALESCE(ROUND(AVG(CASE WHEN l.success = 1 THEN 100 ELSE 0 END), 1), 100) as success_rate
                FROM dr_ai_robots r
                LEFT JOIN api_usage_logs l ON r.robot_slug = l.robot_name 
                    AND l.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                WHERE r.is_active = 1
                GROUP BY r.id, r.robot_name, r.robot_category, r.robot_icon
                ORDER BY usage_count DESC
                LIMIT 5
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getRobotsByCategory() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    robot_category as category,
                    COUNT(*) as total,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active
                FROM dr_ai_robots
                GROUP BY robot_category
                ORDER BY total DESC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getRecentApiLogs() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    l.*,
                    r.robot_name,
                    r.robot_icon
                FROM api_usage_logs l
                LEFT JOIN dr_ai_robots r ON l.robot_name = r.robot_slug
                ORDER BY l.created_at DESC
                LIMIT 10
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getAvailableRobotsCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM dr_ai_robots");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 23; // Valor padrão
        }
    }
    
    private function getActiveRobotsCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM dr_ai_robots WHERE is_active = 1");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 23; // Valor padrão
        }
    }
    
    private function getCompleteUserData($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, name, email, role, created_at, updated_at, last_login
                FROM users 
                WHERE id = ? AND deleted_at IS NULL
            ");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
    
    private function getUserAvailableRobots($userId) {
        try {
            // Buscar todos os robôs ativos
            $stmt = $this->db->query("
                SELECT id, robot_name, robot_slug, robot_icon, robot_category 
                FROM dr_ai_robots 
                WHERE is_active = TRUE 
                ORDER BY sort_order, robot_name
            ");
            $allRobots = $stmt->fetchAll();
            
            // Incluir PermissionManager
            if (!class_exists('PermissionManager')) {
                require_once __DIR__ . '/../helpers/PermissionManager.php';
            }
            
            $permissionManager = new PermissionManager($this->db);
            
            $visibleRobots = [];
            $usableRobots = [];
            
            foreach ($allRobots as $robot) {
                $canView = $permissionManager->hasPermission($userId, $robot['robot_slug'] . '_view') ||
                          $permissionManager->hasPermission($userId, $robot['robot_slug'] . '_use');
                
                $canUse = $permissionManager->hasPermission($userId, $robot['robot_slug'] . '_use');
                
                if ($canView) {
                    $robot['can_use'] = $canUse;
                    $visibleRobots[] = $robot;
                    
                    if ($canUse) {
                        $usableRobots[] = $robot;
                    }
                }
            }
            
            return [
                'visible' => $visibleRobots,
                'usable' => $usableRobots,
                'all' => $allRobots
            ];
        } catch (Exception $e) {
            return [
                'visible' => [],
                'usable' => [],
                'all' => []
            ];
        }
    }
}