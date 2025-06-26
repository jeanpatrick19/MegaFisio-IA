<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

class SessionManager {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Registra uma nova sessão no login
     */
    public function createSession($userId, $sessionId = null) {
        try {
            if (!$sessionId) {
                $sessionId = session_id();
            }
            
            // Detectar informações do dispositivo/browser
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Navegador Desconhecido';
            $ipAddress = $this->getRealIpAddress();
            $deviceInfo = $this->parseUserAgent($userAgent);
            $location = $this->getLocationFromIP($ipAddress);
            
            // Marcar outras sessões como não-atual
            $stmt = $this->db->prepare("
                UPDATE user_sessions 
                SET is_current = FALSE 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            
            // Inserir nova sessão
            $stmt = $this->db->prepare("
                INSERT INTO user_sessions (
                    id, user_id, user_agent, ip_address, location, 
                    device_type, browser, os, is_current, is_active, expires_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE, TRUE, DATE_ADD(NOW(), INTERVAL 30 DAY))
                ON DUPLICATE KEY UPDATE
                    last_activity = CURRENT_TIMESTAMP,
                    is_current = TRUE,
                    is_active = TRUE,
                    expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY)
            ");
            
            return $stmt->execute([
                $sessionId,
                $userId,
                $userAgent,
                $ipAddress,
                $location,
                $deviceInfo['device'],
                $deviceInfo['browser'],
                $deviceInfo['os']
            ]);
        } catch (Exception $e) {
            error_log("Erro ao criar sessão: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return true; // Não quebrar o login por causa da sessão
        }
    }
    
    /**
     * Atualiza a atividade da sessão atual
     */
    public function updateActivity($sessionId = null) {
        if (!$sessionId) {
            $sessionId = session_id();
        }
        
        $stmt = $this->db->prepare("
            UPDATE user_sessions 
            SET last_activity = CURRENT_TIMESTAMP 
            WHERE id = ? AND is_active = TRUE
        ");
        
        return $stmt->execute([$sessionId]);
    }
    
    /**
     * Lista todas as sessões ativas de um usuário
     */
    public function getUserSessions($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id,
                    user_agent,
                    ip_address,
                    location,
                    device_type,
                    browser,
                    os,
                    is_current,
                    created_at,
                    last_activity,
                    CASE 
                        WHEN last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 'online'
                        WHEN last_activity > DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN 'recent'
                        ELSE 'offline'
                    END as status
                FROM user_sessions 
                WHERE user_id = ? AND is_active = TRUE AND expires_at > NOW()
                ORDER BY is_current DESC, last_activity DESC
            ");
            
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar sessões do usuário: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Revoga uma sessão específica
     */
    public function revokeSession($sessionId, $userId) {
        try {
            // Verificar se a tabela existe
            $stmt = $this->db->query("SHOW TABLES LIKE 'user_sessions'");
            if (!$stmt->fetch()) {
                return true; // Tabela não existe, não há problema
            }
            
            // Verificar se a sessão pertence ao usuário
            $stmt = $this->db->prepare("
                UPDATE user_sessions 
                SET is_active = FALSE 
                WHERE id = ? AND user_id = ?
            ");
            
            $result = $stmt->execute([$sessionId, $userId]);
            
            // Não destruir a sessão aqui - será feito no AuthController
            
            return $result;
        } catch (Exception $e) {
            error_log("Erro ao revogar sessão: " . $e->getMessage());
            return true; // Não quebrar o logout por causa da sessão
        }
    }
    
    /**
     * Revoga todas as outras sessões (exceto a atual)
     */
    public function revokeAllOtherSessions($userId, $currentSessionId = null) {
        if (!$currentSessionId) {
            $currentSessionId = session_id();
        }
        
        $stmt = $this->db->prepare("
            UPDATE user_sessions 
            SET is_active = FALSE 
            WHERE user_id = ? AND id != ?
        ");
        
        return $stmt->execute([$userId, $currentSessionId]);
    }
    
    /**
     * Limpa sessões expiradas
     */
    public function cleanExpiredSessions() {
        $stmt = $this->db->prepare("
            UPDATE user_sessions 
            SET is_active = FALSE 
            WHERE expires_at < NOW() OR expires_at IS NULL
        ");
        
        return $stmt->execute();
    }
    
    /**
     * Verifica se uma sessão é válida
     */
    public function isValidSession($sessionId, $userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM user_sessions 
            WHERE id = ? AND user_id = ? AND is_active = TRUE AND expires_at > NOW()
        ");
        
        $stmt->execute([$sessionId, $userId]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtém o IP real do usuário
     */
    private function getRealIpAddress() {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                // Se há múltiplos IPs, pega o primeiro
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                // Validar IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Faz parsing do User Agent para extrair informações
     */
    private function parseUserAgent($userAgent) {
        $device = 'Desktop';
        $browser = 'Desconhecido';
        $os = 'Desconhecido';
        
        // Detectar dispositivo móvel
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            if (preg_match('/iPad/', $userAgent)) {
                $device = 'Tablet';
            } else {
                $device = 'Mobile';
            }
        }
        
        // Detectar browser
        if (preg_match('/Chrome\/[\d.]+/', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox\/[\d.]+/', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari\/[\d.]+/', $userAgent) && !preg_match('/Chrome/', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge\/[\d.]+/', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/Opera\/[\d.]+/', $userAgent)) {
            $browser = 'Opera';
        }
        
        // Detectar OS
        if (preg_match('/Windows NT ([\d.]+)/', $userAgent, $matches)) {
            $version = $matches[1];
            if ($version >= 10) $os = 'Windows 10+';
            elseif ($version >= 6.1) $os = 'Windows 7/8';
            else $os = 'Windows';
        } elseif (preg_match('/Mac OS X ([\d_]+)/', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/Android ([\d.]+)/', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iPhone OS ([\d_]+)/', $userAgent)) {
            $os = 'iOS';
        }
        
        return [
            'device' => $device,
            'browser' => $browser,
            'os' => $os
        ];
    }
    
    /**
     * Obtém localização aproximada do IP (versão simplificada)
     */
    private function getLocationFromIP($ip) {
        // Por enquanto, retorna apenas informação básica
        // Em produção, pode integrar com serviços como ipinfo.io ou geoip
        
        if ($ip === '127.0.0.1' || $ip === '0.0.0.0' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
            return 'Local/Rede Privada';
        }
        
        // Aqui você pode integrar com um serviço de geolocalização
        // Por exemplo: ipinfo.io, geoip2, etc.
        
        return 'Localização não identificada';
    }
    
    /**
     * Formatar informações da sessão para exibição
     */
    public function formatSessionInfo($session) {
        $deviceIcon = $this->getDeviceIcon($session['device_type']);
        $browserIcon = $this->getBrowserIcon($session['browser']);
        $statusClass = $this->getStatusClass($session['status']);
        
        return [
            'id' => $session['id'],
            'device_icon' => $deviceIcon,
            'browser_icon' => $browserIcon,
            'device_name' => $session['device_type'],
            'browser_name' => $session['browser'],
            'os_name' => $session['os'],
            'ip_address' => $session['ip_address'],
            'location' => $session['location'],
            'status' => $session['status'],
            'status_class' => $statusClass,
            'is_current' => $session['is_current'],
            'created_at' => $session['created_at'],
            'last_activity' => $session['last_activity'],
            'formatted_created' => date('d/m/Y H:i', strtotime($session['created_at'])),
            'formatted_activity' => $this->formatTimeAgo($session['last_activity'])
        ];
    }
    
    private function getDeviceIcon($device) {
        switch ($device) {
            case 'Mobile': return 'fas fa-mobile-alt';
            case 'Tablet': return 'fas fa-tablet-alt';
            default: return 'fas fa-desktop';
        }
    }
    
    private function getBrowserIcon($browser) {
        switch ($browser) {
            case 'Chrome': return 'fab fa-chrome';
            case 'Firefox': return 'fab fa-firefox';
            case 'Safari': return 'fab fa-safari';
            case 'Edge': return 'fab fa-edge';
            case 'Opera': return 'fab fa-opera';
            default: return 'fas fa-globe';
        }
    }
    
    private function getStatusClass($status) {
        switch ($status) {
            case 'online': return 'status-online';
            case 'recent': return 'status-recent';
            default: return 'status-offline';
        }
    }
    
    private function formatTimeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'Agora mesmo';
        if ($time < 3600) return floor($time/60) . ' min atrás';
        if ($time < 86400) return floor($time/3600) . ' h atrás';
        if ($time < 2592000) return floor($time/86400) . ' dias atrás';
        
        return date('d/m/Y', strtotime($datetime));
    }
}