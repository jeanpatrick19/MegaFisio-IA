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
        // Estatísticas do sistema
        $stats = [
            'total_users' => $this->getTotalUsers(),
            'active_sessions' => $this->getActiveSessions(),
            'ai_requests_today' => $this->getAIRequestsToday(),
            'system_health' => $this->getSystemHealth(),
            'storage_percent' => $this->getStoragePercent(),
            'users_growth' => $this->getUsersGrowth(),
            'ai_growth' => $this->getAIGrowth(),
            // Dados específicos dos módulos IA
            'ai_ortopedica' => $this->getAIUsageByCategory('ortopedica'),
            'ai_neurologica' => $this->getAIUsageByCategory('neurologica'),
            'ai_respiratoria' => $this->getAIUsageByCategory('respiratoria'),
            'ai_geriatrica' => $this->getAIUsageByCategory('geriatrica'),
            'ai_pediatrica' => $this->getAIUsageByCategory('pediatrica')
        ];
        
        // Últimas atividades
        $recentActivities = $this->getRecentActivities();
        
        // Usar o mesmo padrão das outras páginas
        $this->render('dashboard/fisio-admin', [
            'title' => 'Dashboard Clínico Administrativo',
            'currentPage' => 'dashboard',
            'user' => $this->user,
            'stats' => $stats,
            'activities' => $recentActivities
        ], 'fisioterapia-premium');
    }
    
    private function professionalDashboard() {
        // Estatísticas do profissional
        $stats = [
            'patients_count' => 0, // Implementar depois
            'documents_created' => $this->getDocumentsCreated($this->user['id']),
            'ai_requests' => $this->getUserAIRequests($this->user['id']),
            'monthly_usage' => $this->getMonthlyUsage($this->user['id'])
        ];
        
        $this->render('dashboard/professional', [
            'title' => 'Dashboard Profissional',
            'currentPage' => 'dashboard',
            'user' => $this->user,
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
}