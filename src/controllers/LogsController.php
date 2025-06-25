<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once 'BaseController.php';

class LogsController extends BaseController {
    
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
        
        // Buscar logs de atividade recentes
        $stmt = $this->db->prepare("
            SELECT 
                l.*,
                u.name as user_name,
                u.email as user_email
            FROM activity_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC
            LIMIT 100
        ");
        $stmt->execute();
        $logs = $stmt->fetchAll();
        
        // Estatísticas dos logs
        $stats = [
            'total_logs' => $this->getTotalLogs(),
            'logs_today' => $this->getLogsToday(),
            'unique_users_today' => $this->getUniqueUsersToday(),
            'most_active_user' => $this->getMostActiveUser()
        ];
        
        $data = [
            'title' => 'Logs do Sistema',
            'pageTitle' => 'Logs de Atividade',
            'currentPage' => 'logs',
            'user' => $this->user,
            'logs' => $logs,
            'stats' => $stats
        ];
        
        $this->render('admin/logs-sistema', $data, 'fisioterapia-premium');
    }
    
    private function getTotalLogs() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM activity_logs");
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getLogsToday() {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM activity_logs 
                WHERE DATE(created_at) = CURDATE()
            ");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getUniqueUsersToday() {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT user_id) FROM activity_logs 
                WHERE DATE(created_at) = CURDATE() AND user_id IS NOT NULL
            ");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getMostActiveUser() {
        try {
            $stmt = $this->db->prepare("
                SELECT u.name, COUNT(*) as total
                FROM activity_logs l
                JOIN users u ON l.user_id = u.id
                WHERE DATE(l.created_at) = CURDATE()
                GROUP BY u.id, u.name
                ORDER BY total DESC
                LIMIT 1
            ");
            $stmt->execute();
            $result = $stmt->fetch();
            return $result ? $result['name'] : 'Nenhum';
        } catch (Exception $e) {
            return 'Nenhum';
        }
    }
}