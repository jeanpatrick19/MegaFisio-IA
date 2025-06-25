<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class PasswordController extends BaseController {
    
    public function change() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processChange();
            return;
        }
        
        $forceChange = isset($_SESSION['force_password_change']);
        
        $this->render('auth/change-password', [
            'forceChange' => $forceChange
        ]);
    }
    
    private function processChange() {
        $data = [
            'current_password' => $_POST['current_password'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? ''
        ];
        
        $forceChange = isset($_SESSION['force_password_change']);
        
        $rules = [
            'password' => ['required' => true, 'min' => 8],
            'password_confirm' => ['required' => true, 'match' => 'password']
        ];
        
        // Se não for mudança forçada, exigir senha atual
        if (!$forceChange) {
            $rules['current_password'] = ['required' => true];
        }
        
        $errors = $this->validate($rules);
        
        if (!empty($errors)) {
            $this->flash('error', 'Por favor, corrija os erros no formulário');
            return;
        }
        
        // Verificar senha atual (se não for mudança forçada)
        if (!$forceChange) {
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$this->user['id']]);
            $currentHash = $stmt->fetchColumn();
            
            if (!password_verify($data['current_password'], $currentHash)) {
                $this->flash('error', 'Senha atual incorreta');
                return;
            }
        }
        
        // Verificar se a nova senha é diferente da atual
        if (!$forceChange) {
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$this->user['id']]);
            $currentHash = $stmt->fetchColumn();
            
            if (password_verify($data['password'], $currentHash)) {
                $this->flash('error', 'A nova senha deve ser diferente da senha atual');
                return;
            }
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password = ?, first_login = FALSE, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                password_hash($data['password'], PASSWORD_DEFAULT),
                $this->user['id']
            ]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'password_changed', 
                $forceChange ? 'Senha alterada no primeiro login' : 'Senha alterada pelo usuário', true);
            
            // Remover flag de mudança forçada
            unset($_SESSION['force_password_change']);
            
            $this->flash('success', 'Senha alterada com sucesso!');
            $this->redirect('/dashboard');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao alterar senha. Tente novamente.');
            error_log("Erro ao alterar senha: " . $e->getMessage());
        }
    }
    
    public function forgotPassword() {
        if ($this->user) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processForgotPassword();
            return;
        }
        
        $this->render('auth/forgot-password');
    }
    
    private function processForgotPassword() {
        $email = trim($_POST['email'] ?? '');
        
        $errors = $this->validate([
            'email' => ['required' => true, 'email' => true]
        ]);
        
        if (!empty($errors)) {
            $this->flash('error', 'Por favor, insira um email válido');
            return;
        }
        
        // Buscar usuário
        $stmt = $this->db->prepare("
            SELECT id, email, name FROM users 
            WHERE email = ? AND status = 'active' AND deleted_at IS NULL
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // Sempre mostrar mensagem de sucesso (segurança)
        $this->flash('success', 'Se o email existir em nosso sistema, você receberá instruções para redefinir sua senha.');
        
        if ($user) {
            try {
                // Gerar token de reset
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora
                
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET password_reset_token = ?, password_reset_expires = ?
                    WHERE id = ?
                ");
                $stmt->execute([$token, $expires, $user['id']]);
                
                // Enviar email (implementar depois)
                $this->sendPasswordResetEmail($user, $token);
                
                // Log
                $this->logUserAction($user['id'], 'password_reset_requested', 
                    'Token de reset de senha gerado', true);
                
            } catch (Exception $e) {
                error_log("Erro ao gerar token de reset: " . $e->getMessage());
            }
        }
        
        $this->redirect('/login');
    }
    
    public function resetPassword() {
        if ($this->user) {
            $this->redirect('/dashboard');
        }
        
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->flash('error', 'Token inválido');
            $this->redirect('/login');
        }
        
        // Verificar token
        $stmt = $this->db->prepare("
            SELECT id, email, name FROM users 
            WHERE password_reset_token = ? 
            AND password_reset_expires > NOW() 
            AND status = 'active' 
            AND deleted_at IS NULL
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $this->flash('error', 'Token inválido ou expirado');
            $this->redirect('/forgot-password');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processResetPassword($user, $token);
            return;
        }
        
        $this->render('auth/reset-password', [
            'token' => $token,
            'user' => $user
        ]);
    }
    
    private function processResetPassword($user, $token) {
        $data = [
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? ''
        ];
        
        $errors = $this->validate([
            'password' => ['required' => true, 'min' => 8],
            'password_confirm' => ['required' => true, 'match' => 'password']
        ]);
        
        if (!empty($errors)) {
            $this->flash('error', 'Por favor, corrija os erros no formulário');
            return;
        }
        
        try {
            $this->db->beginTransaction();
            
            // Atualizar senha
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password = ?, 
                    password_reset_token = NULL, 
                    password_reset_expires = NULL,
                    login_attempts = 0,
                    locked_until = NULL,
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                password_hash($data['password'], PASSWORD_DEFAULT),
                $user['id']
            ]);
            
            $this->db->commit();
            
            // Log
            $this->logUserAction($user['id'], 'password_reset_completed', 
                'Senha redefinida via token de reset', true);
            
            $this->flash('success', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
            $this->redirect('/login');
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->flash('error', 'Erro ao redefinir senha. Tente novamente.');
            error_log("Erro ao redefinir senha: " . $e->getMessage());
        }
    }
    
    private function sendPasswordResetEmail($user, $token) {
        // Placeholder para implementação de email
        // Por enquanto, apenas log
        error_log("Password reset token para {$user['email']}: $token");
        
        // TODO: Implementar envio de email
        // $resetUrl = BASE_URL . "/reset-password?token=$token";
        // $subject = "Redefinição de senha - Mega Fisio IA";
        // $message = "Clique no link para redefinir sua senha: $resetUrl";
        // mail($user['email'], $subject, $message);
    }
    
}