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
                        $_SESSION['user_id'] = $user['id'];
                        
                        $this->updateLastLogin($user['id']);
                        
                        $this->logUserAction($user['id'], 'login', 'Login realizado com sucesso');
                        
                        $this->flash('success', 'Bem-vindo(a), ' . $user['name'] . '!');
                        $this->redirect($this->getDashboardPath($user['role']));
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
        if ($this->user) {
            // Log da ação
            $this->logUserAction($this->user['id'], 'logout', 'Logout realizado');
        }
        
        session_destroy();
        $this->flash('success', 'Logout realizado com sucesso!');
        $this->redirect('/');
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
    
}