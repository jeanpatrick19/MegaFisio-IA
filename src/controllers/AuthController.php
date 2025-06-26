<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class AuthController extends BaseController {
    
    public function login() {
        if ($this->user) {
            $this->redirect($this->getDashboardPath());
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $errors = $this->validate([
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true]
            ]);
            
            if (empty($errors)) {
                $user = $this->authenticateUser($email, $password);
                
                if ($user) {
                    if ($user['status'] !== 'active') {
                        $errors['general'] = 'Usuário inativo. Entre em contato com o administrador.';
                    } else {
                        // Verificar se o usuário tem 2FA ativado
                        $twoFAEnabled = false;
                        try {
                            $stmt = $this->db->prepare("
                                SELECT two_factor_enabled 
                                FROM user_profiles_extended 
                                WHERE user_id = ?
                            ");
                            $stmt->execute([$user['id']]);
                            $result = $stmt->fetch();
                            $twoFAEnabled = ($result && $result['two_factor_enabled']) ? true : false;
                        } catch (Exception $e) {
                            // Se der erro, continua com 2FA desativado
                            error_log("Erro ao verificar 2FA: " . $e->getMessage());
                        }
                        
                        if ($twoFAEnabled) {
                            // Armazenar dados do usuário temporariamente para verificação 2FA
                            $_SESSION['pending_2fa_user_id'] = $user['id'];
                            $_SESSION['pending_2fa_user_data'] = [
                                'id' => $user['id'],
                                'name' => $user['name'],
                                'email' => $user['email'],
                                'role' => $user['role']
                            ];
                            
                            $this->logUserAction($user['id'], 'login_pending_2fa', 'Login pendente - aguardando código 2FA');
                            
                            // Redirecionar para página de verificação 2FA
                            $this->redirect('/verify-2fa');
                        } else {
                            // Login normal (sem 2FA)
                            $_SESSION['user_id'] = $user['id'];
                            
                            $this->updateLastLogin($user['id']);
                            
                            // SEMPRE criar sessão no login - OBRIGATÓRIO
                            $this->forceCreateUserSession($user['id']);
                            
                            $this->logUserAction($user['id'], 'login', 'Login realizado com sucesso');
                            
                            $this->flash('success', 'Bem-vindo(a), ' . $user['name'] . '!');
                            $this->redirect($this->getDashboardPath($user['role']));
                        }
                    }
                } else {
                    $errors['general'] = 'Email ou senha incorretos.';
                    
                    $this->logUserAction(null, 'login_failed', 'Tentativa de login com email: ' . $email, false);
                }
            }
        }
        
        // Renderizar página de login sem layout
        require SRC_PATH . '/views/auth/login.php';
    }
    
    public function logout() {
        try {
            if ($this->user) {
                // Revogar sessão atual
                $this->revokeUserSession();
                
                // Log da ação
                $this->logUserAction($this->user['id'], 'logout', 'Logout realizado');
            }
        } catch (Exception $e) {
            error_log("Erro no logout: " . $e->getMessage());
        }
        
        // Limpar sessão e redirecionar
        session_unset();
        session_destroy();
        
        // Iniciar nova sessão para o flash message
        session_start();
        $this->flash('success', 'Logout realizado com sucesso!');
        $this->redirect('/login');
    }
    
    private function authenticateUser($email, $password) {
        $stmt = $this->db->prepare("
            SELECT id, email, password, name, role, status, login_attempts, locked_until
            FROM users 
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return false;
        }
        
        // Verificar se está bloqueado
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $this->flash('error', 'Usuário temporariamente bloqueado. Tente novamente mais tarde.');
            return false;
        }
        
        // Verificar senha
        if (password_verify($password, $user['password'])) {
            // Limpar tentativas de login em caso de sucesso
            $this->resetLoginAttempts($user['id']);
            return $user;
        } else {
            // Incrementar tentativas de login
            $this->incrementLoginAttempts($user['id']);
            return false;
        }
    }
    
    private function incrementLoginAttempts($userId) {
        // Buscar configuração de máximo de tentativas
        $stmt = $this->db->query("SELECT `value` FROM settings WHERE `key` = 'max_login_attempts'");
        $maxAttempts = (int)($stmt->fetchColumn() ?: 5);
        
        $stmt = $this->db->query("SELECT `value` FROM settings WHERE `key` = 'lockout_duration'");
        $lockoutDuration = (int)($stmt->fetchColumn() ?: 900); // 15 minutos
        
        // Incrementar tentativas
        $stmt = $this->db->prepare("
            UPDATE users 
            SET login_attempts = login_attempts + 1 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        
        // Verificar se atingiu o limite
        $stmt = $this->db->prepare("SELECT login_attempts FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $attempts = (int)$stmt->fetchColumn();
        
        if ($attempts >= $maxAttempts) {
            // Bloquear usuário temporariamente
            $lockUntil = date('Y-m-d H:i:s', time() + $lockoutDuration);
            $stmt = $this->db->prepare("
                UPDATE users 
                SET locked_until = ? 
                WHERE id = ?
            ");
            $stmt->execute([$lockUntil, $userId]);
            
            $this->flash('error', "Muitas tentativas de login. Usuário bloqueado por " . ($lockoutDuration / 60) . " minutos.");
        }
    }
    
    private function resetLoginAttempts($userId) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET login_attempts = 0, locked_until = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
    }
    
    private function updateLastLogin($userId) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET last_login = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
    }
    
    private function getDashboardPath($role = null) {
        $role = $role ?: $this->user['role'];
        
        switch ($role) {
            case 'admin':
                return '/admin/dashboard'; // Admin administra o sistema
            case 'professional':
                return '/dashboard'; // Professional usa o sistema
            case 'patient':
                return '/dashboard'; // Patient usa o sistema
            default:
                return '/dashboard';
        }
    }
    
    // =================== MÉTODOS DE 2FA ===================
    
    public function verify2FA() {
        // Verificar se há uma sessão pendente de 2FA
        if (!isset($_SESSION['pending_2fa_user_id'])) {
            $this->redirect('/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $code = trim($_POST['code'] ?? '');
            $isBackupCode = isset($_POST['backup_code']) && $_POST['backup_code'] === '1';
            
            if (empty($code)) {
                $errors['code'] = 'Digite o código de verificação.';
            } else {
                $userId = $_SESSION['pending_2fa_user_id'];
                
                // Buscar dados 2FA do usuário
                $stmt = $this->db->prepare("
                    SELECT two_factor_secret, backup_codes
                    FROM user_profiles_extended 
                    WHERE user_id = ? AND two_factor_enabled = TRUE
                ");
                $stmt->execute([$userId]);
                $twoFAData = $stmt->fetch();
                
                if (!$twoFAData) {
                    $errors['general'] = 'Configuração 2FA não encontrada.';
                } else {
                    require_once SRC_PATH . '/services/TwoFactorService.php';
                    
                    $validCode = false;
                    
                    if ($isBackupCode || preg_match('/^\d{4}-\d{4}$/', $code)) {
                        // Verificar código de backup
                        $backupCodes = json_decode($twoFAData['backup_codes'], true) ?: [];
                        $usedCode = TwoFactorService::verifyBackupCode($code, $backupCodes);
                        
                        if ($usedCode) {
                            $validCode = true;
                            
                            // Remover código usado
                            $backupCodes = TwoFactorService::removeUsedBackupCode($backupCodes, $usedCode);
                            $stmt = $this->db->prepare("
                                UPDATE user_profiles_extended 
                                SET backup_codes = ? 
                                WHERE user_id = ?
                            ");
                            $stmt->execute([json_encode($backupCodes), $userId]);
                            
                            $this->logUserAction($userId, 'backup_code_used', "Código de backup usado: $usedCode");
                        }
                    } else {
                        // Verificar código do app (6 dígitos)
                        if (preg_match('/^\d{6}$/', $code)) {
                            $validCode = TwoFactorService::verifyCode($twoFAData['two_factor_secret'], $code);
                        }
                    }
                    
                    if ($validCode) {
                        // Código válido - completar login
                        $userData = $_SESSION['pending_2fa_user_data'];
                        
                        $_SESSION['user_id'] = $userData['id'];
                        
                        // Limpar dados temporários
                        unset($_SESSION['pending_2fa_user_id']);
                        unset($_SESSION['pending_2fa_user_data']);
                        
                        $this->updateLastLogin($userData['id']);
                        
                        // SEMPRE criar sessão no login com 2FA - OBRIGATÓRIO
                        $this->forceCreateUserSession($userData['id']);
                        
                        $this->logUserAction($userData['id'], 'login_2fa_success', 'Login com 2FA realizado com sucesso');
                        
                        $this->flash('success', 'Bem-vindo(a), ' . $userData['name'] . '!');
                        $this->redirect($this->getDashboardPath($userData['role']));
                    } else {
                        $errors['code'] = 'Código incorreto. Verifique e tente novamente.';
                        $this->logUserAction($userId, 'login_2fa_failed', 'Falha na verificação 2FA - código: ' . $code, false);
                    }
                }
            }
        }
        
        // Renderizar página de verificação 2FA
        $userData = $_SESSION['pending_2fa_user_data'] ?? null;
        require SRC_PATH . '/views/auth/verify-2fa.php';
    }
    
    public function cancel2FA() {
        // Limpar sessão pendente
        unset($_SESSION['pending_2fa_user_id']);
        unset($_SESSION['pending_2fa_user_data']);
        
        $this->flash('info', 'Verificação 2FA cancelada.');
        $this->redirect('/login');
    }
    
    // =================== MÉTODOS DE GESTÃO DE SESSÕES ===================
    
    private function forceCreateUserSession($userId) {
        // Garantir que SEMPRE seja criada uma sessão no login
        try {
            // Garantir estrutura correta da tabela user_sessions
            require_once SRC_PATH . '/models/SmartMigrationManager.php';
            $migrationManager = new SmartMigrationManager($this->db);
            $migrationManager->createUserSessionsTable();
            
            // Criar sessão diretamente no banco
            $sessionId = session_id();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Sistema';
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            
            // Detectar dispositivo básico
            $device = 'Desktop';
            $browser = 'Navegador';
            $os = 'Sistema';
            
            if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
                $device = preg_match('/iPad/', $userAgent) ? 'Tablet' : 'Mobile';
            }
            
            if (preg_match('/Chrome/', $userAgent)) $browser = 'Chrome';
            elseif (preg_match('/Firefox/', $userAgent)) $browser = 'Firefox';
            elseif (preg_match('/Safari/', $userAgent)) $browser = 'Safari';
            elseif (preg_match('/Edge/', $userAgent)) $browser = 'Edge';
            
            if (preg_match('/Windows/', $userAgent)) $os = 'Windows';
            elseif (preg_match('/Mac/', $userAgent)) $os = 'macOS';
            elseif (preg_match('/Linux/', $userAgent)) $os = 'Linux';
            elseif (preg_match('/Android/', $userAgent)) $os = 'Android';
            elseif (preg_match('/iOS/', $userAgent)) $os = 'iOS';
            
            // Desativar outras sessões como atual
            $stmt = $this->db->prepare("UPDATE user_sessions SET is_current = FALSE WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Inserir/atualizar sessão atual
            $stmt = $this->db->prepare("
                INSERT INTO user_sessions (
                    id, user_id, user_agent, ip_address, location, 
                    device_type, browser, os, is_current, is_active, expires_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE, TRUE, DATE_ADD(NOW(), INTERVAL 30 DAY))
                ON DUPLICATE KEY UPDATE
                    user_agent = VALUES(user_agent),
                    ip_address = VALUES(ip_address),
                    location = VALUES(location),
                    device_type = VALUES(device_type),
                    browser = VALUES(browser),
                    os = VALUES(os),
                    is_current = TRUE,
                    is_active = TRUE,
                    last_activity = CURRENT_TIMESTAMP,
                    expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY)
            ");
            
            $result = $stmt->execute([
                $sessionId,
                $userId,
                $userAgent,
                $ipAddress,
                'Rede Local',
                $device,
                $browser,
                $os
            ]);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("ERRO CRÍTICO - falha ao criar sessão obrigatória: " . $e->getMessage());
            // Mesmo com erro, não quebrar o login
            return false;
        }
    }
    
    private function createUserSession($userId) {
        try {
            error_log("Iniciando criação de sessão para usuário: $userId");
            
            // Garantir que a tabela existe usando o sistema de migrações
            require_once SRC_PATH . '/models/SmartMigrationManager.php';
            $migrationManager = new SmartMigrationManager($this->db);
            $migrationManager->createUserSessionsTable();
            
            error_log("Tabela user_sessions garantida, criando sessão...");
            
            require_once SRC_PATH . '/services/SessionManager.php';
            $sessionManager = new SessionManager($this->db);
            $result = $sessionManager->createSession($userId);
            
            error_log("Resultado da criação de sessão: " . ($result ? 'SUCESSO' : 'FALHA'));
            
            return $result;
        } catch (Exception $e) {
            error_log("ERRO CRÍTICO ao criar sessão do usuário: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Tentar criar sessão manualmente como fallback
            try {
                $sessionId = session_id();
                $stmt = $this->db->prepare("
                    INSERT INTO user_sessions (
                        id, user_id, user_agent, ip_address, location, 
                        device_type, browser, os, is_current, is_active, expires_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE, TRUE, DATE_ADD(NOW(), INTERVAL 30 DAY))
                    ON DUPLICATE KEY UPDATE
                        last_activity = CURRENT_TIMESTAMP,
                        is_current = TRUE,
                        is_active = TRUE
                ");
                
                $result = $stmt->execute([
                    $sessionId,
                    $userId,
                    $_SERVER['HTTP_USER_AGENT'] ?? 'Sistema',
                    $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                    'Login Manual',
                    'Desktop',
                    'Sistema',
                    'Sistema'
                ]);
                
                error_log("Criação manual de sessão: " . ($result ? 'SUCESSO' : 'FALHA'));
                return $result;
                
            } catch (Exception $e2) {
                error_log("FALLBACK também falhou: " . $e2->getMessage());
                return true; // Não quebrar o login
            }
        }
    }
    
    private function revokeUserSession($sessionId = null) {
        try {
            if (!$this->user) return false;
            
            require_once SRC_PATH . '/services/SessionManager.php';
            $sessionManager = new SessionManager($this->db);
            
            if (!$sessionId) {
                $sessionId = session_id();
            }
            
            return $sessionManager->revokeSession($sessionId, $this->user['id']);
        } catch (Exception $e) {
            error_log("Erro ao revogar sessão do usuário: " . $e->getMessage());
            return true; // Não quebrar o logout
        }
    }
    
}