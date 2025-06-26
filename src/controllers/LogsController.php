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
    
    public function export() {
        $this->requireRole('admin');
        
        $format = $_GET['format'] ?? 'json';
        
        try {
            $stmt = $this->db->query("
                SELECT 
                    l.*,
                    u.name as user_name,
                    u.email as user_email
                FROM activity_logs l
                LEFT JOIN users u ON l.user_id = u.id
                ORDER BY l.created_at DESC
                LIMIT 5000
            ");
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($format === 'csv') {
                $this->exportCSV($logs);
            } else {
                $this->exportJSON($logs);
            }
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function clear() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método não permitido'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $confirmacao = $input['confirmacao'] ?? '';
        
        if ($confirmacao !== 'CONFIRMAR') {
            $this->json(['success' => false, 'message' => 'Confirmação inválida'], 400);
        }
        
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM activity_logs");
            $totalLogs = $stmt->fetchColumn();
            
            $this->db->exec("DELETE FROM activity_logs");
            
            $this->json([
                'success' => true, 
                'message' => "Todos os logs foram removidos. Total: {$totalLogs} registros."
            ]);
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    private function exportCSV($logs) {
        $filename = 'logs_sistema_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['ID', 'Data/Hora', 'Usuário', 'Email', 'Ação', 'Detalhes'], ';');
        
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['user_name'] ?? 'Sistema',
                $log['user_email'] ?? '',
                $log['action'] ?? '',
                $log['details'] ?? ''
            ], ';');
        }
        
        fclose($output);
        exit;
    }
    
    private function exportJSON($logs) {
        $filename = 'logs_sistema_' . date('Y-m-d_H-i-s') . '.json';
        
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo json_encode([
            'exported_at' => date('Y-m-d H:i:s'),
            'exported_by' => $this->user['name'],
            'total_logs' => count($logs),
            'logs' => $logs
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}