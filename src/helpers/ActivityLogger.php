<?php

/**
 * Sistema de Log de Atividades - MegaFisio IA
 * Rastreia todas as ações dos usuários no sistema
 */

class ActivityLogger {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Registrar uma atividade do usuário
     */
    public function log($acao, $categoria = null, $detalhes = null, $userId = null, $email = null, $sucesso = true, $metadados = null) {
        try {
            // Obter informações da sessão atual
            $userId = $userId ?? ($_SESSION['user_id'] ?? null);
            $email = $email ?? ($_SESSION['user_email'] ?? null);
            $sessaoId = session_id();
            $ipAddress = $this->getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            // Preparar metadados
            $metadadosJson = null;
            if ($metadados && is_array($metadados)) {
                $metadadosJson = json_encode($metadados, JSON_UNESCAPED_UNICODE);
            }
            
            $sql = "INSERT INTO user_logs 
                    (user_id, email, acao, categoria, detalhes, ip_address, user_agent, sucesso, sessao_id, metadados) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $userId,
                $email,
                $acao,
                $categoria,
                $detalhes,
                $ipAddress,
                $userAgent,
                $sucesso ? 1 : 0,
                $sessaoId,
                $metadadosJson
            ]);
            
            return true;
        } catch (Exception $e) {
            error_log("Erro ao registrar atividade: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Logs específicos para diferentes tipos de atividade
     */
    public function logLogin($email, $sucesso = true, $detalhes = null) {
        $categoria = 'authentication';
        $acao = $sucesso ? 'login_success' : 'login_failed';
        $detalhesDefault = $sucesso ? 'Login realizado com sucesso' : 'Tentativa de login falhada';
        
        return $this->log($acao, $categoria, $detalhes ?? $detalhesDefault, null, $email, $sucesso);
    }
    
    public function logLogout($userId = null) {
        return $this->log('logout', 'authentication', 'Logout realizado', $userId);
    }
    
    public function logProfileUpdate($campos = [], $userId = null) {
        $metadados = ['campos_alterados' => $campos];
        return $this->log('profile_update', 'profile', 'Perfil atualizado', $userId, null, true, $metadados);
    }
    
    public function logPasswordChange($userId = null) {
        return $this->log('password_change', 'security', 'Senha alterada', $userId);
    }
    
    public function log2FAEnabled($userId = null) {
        return $this->log('2fa_enabled', 'security', 'Autenticação de dois fatores ativada', $userId);
    }
    
    public function log2FADisabled($userId = null) {
        return $this->log('2fa_disabled', 'security', 'Autenticação de dois fatores desativada', $userId);
    }
    
    public function logSessionRevoked($sessionId, $userId = null) {
        $metadados = ['session_revoked' => $sessionId];
        return $this->log('session_revoked', 'security', 'Sessão revogada', $userId, null, true, $metadados);
    }
    
    public function logDataExport($tipo, $userId = null) {
        $metadados = ['export_type' => $tipo];
        return $this->log('data_export_request', 'data', "Solicitação de exportação de dados ($tipo)", $userId, null, true, $metadados);
    }
    
    public function logAccountDeletionRequest($motivo = null, $userId = null) {
        $metadados = ['motivo' => $motivo];
        return $this->log('account_deletion_request', 'account', 'Solicitação de exclusão de conta', $userId, null, true, $metadados);
    }
    
    public function logAdminAction($acao, $detalhes, $targetUserId = null, $userId = null) {
        $metadados = ['target_user_id' => $targetUserId];
        return $this->log($acao, 'admin', $detalhes, $userId, null, true, $metadados);
    }
    
    public function logPreferencesUpdate($preferencias = [], $userId = null) {
        $metadados = ['preferencias' => $preferencias];
        return $this->log('preferences_update', 'profile', 'Preferências atualizadas', $userId, null, true, $metadados);
    }
    
    /**
     * Obter atividades de um usuário
     */
    public function getUserActivities($userId, $limit = 50, $offset = 0, $categoria = null) {
        try {
            $whereClause = "WHERE user_id = ?";
            $params = [$userId];
            
            if ($categoria) {
                $whereClause .= " AND categoria = ?";
                $params[] = $categoria;
            }
            
            $sql = "SELECT * FROM user_logs 
                    $whereClause 
                    ORDER BY data_hora DESC 
                    LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar atividades: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Contar total de atividades de um usuário
     */
    public function countUserActivities($userId, $categoria = null) {
        try {
            $whereClause = "WHERE user_id = ?";
            $params = [$userId];
            
            if ($categoria) {
                $whereClause .= " AND categoria = ?";
                $params[] = $categoria;
            }
            
            $sql = "SELECT COUNT(*) as total FROM user_logs $whereClause";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Erro ao contar atividades: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obter estatísticas de atividades
     */
    public function getActivityStats($userId, $dias = 30) {
        try {
            $sql = "SELECT 
                        categoria,
                        COUNT(*) as total,
                        DATE(data_hora) as data
                    FROM user_logs 
                    WHERE user_id = ? 
                    AND data_hora >= DATE_SUB(NOW(), INTERVAL ? DAY)
                    GROUP BY categoria, DATE(data_hora)
                    ORDER BY data DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $dias]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar estatísticas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Limpar logs antigos
     */
    public function cleanOldLogs($days = 365) {
        try {
            $sql = "DELETE FROM user_logs WHERE data_hora < DATE_SUB(NOW(), INTERVAL ? DAY)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$days]);
            
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log("Erro ao limpar logs antigos: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obter IP do cliente
     */
    private function getClientIP() {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Formatar ação para exibição
     */
    public static function formatAction($acao) {
        $acoes = [
            'login_success' => 'Login realizado',
            'login_failed' => 'Tentativa de login falhada',
            'logout' => 'Logout',
            'profile_update' => 'Perfil atualizado',
            'password_change' => 'Senha alterada',
            '2fa_enabled' => '2FA ativado',
            '2fa_disabled' => '2FA desativado',
            'session_revoked' => 'Sessão revogada',
            'data_export_request' => 'Exportação de dados solicitada',
            'account_deletion_request' => 'Exclusão de conta solicitada',
            'preferences_update' => 'Preferências atualizadas'
        ];
        
        return $acoes[$acao] ?? ucfirst(str_replace('_', ' ', $acao));
    }
    
    /**
     * Formatar categoria para exibição
     */
    public static function formatCategory($categoria) {
        $categorias = [
            'authentication' => 'Autenticação',
            'profile' => 'Perfil',
            'security' => 'Segurança',
            'admin' => 'Administração',
            'data' => 'Dados',
            'account' => 'Conta'
        ];
        
        return $categorias[$categoria] ?? ucfirst($categoria);
    }
}
?>