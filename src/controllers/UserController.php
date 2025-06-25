<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class UserController extends BaseController {
    
    public function index() {
        $this->requireRole('admin');
        
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $search = trim($_GET['search'] ?? '');
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        
        // Construir query com filtros
        $whereConditions = ['deleted_at IS NULL'];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = '(name LIKE ? OR email LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($role)) {
            $whereConditions[] = 'role = ?';
            $params[] = $role;
        }
        
        if (!empty($status)) {
            $whereConditions[] = 'status = ?';
            $params[] = $status;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        // Contar total
        $countSql = "SELECT COUNT(*) FROM users WHERE $whereClause";
        $stmt = $this->db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();
        
        // Buscar usuários
        $sql = "
            SELECT id, name, email, role, status, last_login, created_at
            FROM users 
            WHERE $whereClause
            ORDER BY created_at DESC
            LIMIT $limit OFFSET $offset
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();
        
        // Calcular paginação
        $totalPages = ceil($total / $limit);
        
        $this->render('admin/gestao-usuarios-completa', [
            'title' => 'Gestão de Usuários',
            'currentPage' => 'users',
            'user' => $this->user,
            'users' => $users,
            'total' => $total,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'role' => $role,
            'status' => $status
        ], 'fisioterapia-premium');
    }
    
    public function create() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
            return;
        }
        
        $this->render('admin/users/create');
    }
    
    private function processCreate() {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'role' => $_POST['role'] ?? 'usuario',
            'status' => $_POST['status'] ?? 'active',
            'force_password_change' => isset($_POST['force_password_change'])
        ];
        
        $errors = $this->validate([
            'name' => ['required' => true, 'min' => 3, 'max' => 255],
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true, 'min' => 8],
            'password_confirm' => ['required' => true, 'match' => 'password']
        ]);
        
        if (!empty($errors)) {
            $this->flash('error', 'Por favor, corrija os erros no formulário');
            return;
        }
        
        // Verificar email único
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND deleted_at IS NULL");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            $this->flash('error', 'Este email já está cadastrado');
            return;
        }
        
        // Validar role e status
        if (!in_array($data['role'], ['admin', 'usuario'])) {
            $data['role'] = 'usuario';
        }
        
        if (!in_array($data['status'], ['active', 'inactive'])) {
            $data['status'] = 'active';
        }
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, status, first_login)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role'],
                $data['status'],
                $data['force_password_change'] ? 1 : 0
            ]);
            
            $userId = $this->db->lastInsertId();
            
            // Log da ação
            $this->logUserAction($userId, 'user_created', 
                "Usuário criado pelo admin {$this->user['name']}", true, $data['email']);
            
            $this->flash('success', 'Usuário criado com sucesso!');
            $this->redirect('/admin/users');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao criar usuário. Tente novamente.');
            error_log("Erro ao criar usuário: " . $e->getMessage());
        }
    }
    
    public function edit() {
        $this->requireRole('admin');
        
        $userId = intval($_GET['id'] ?? 0);
        if (!$userId) {
            $this->notFound();
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            $this->notFound();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($user);
            return;
        }
        
        $this->render('admin/users/edit', ['user' => $user]);
    }
    
    private function processEdit($user) {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'role' => $_POST['role'] ?? $user['role'],
            'status' => $_POST['status'] ?? $user['status']
        ];
        
        $errors = $this->validate([
            'name' => ['required' => true, 'min' => 3, 'max' => 255]
        ]);
        
        if (!empty($errors)) {
            $this->flash('error', 'Por favor, corrija os erros no formulário');
            return;
        }
        
        // Validar role e status
        if (!in_array($data['role'], ['admin', 'usuario'])) {
            $data['role'] = $user['role'];
        }
        
        if (!in_array($data['status'], ['active', 'inactive'])) {
            $data['status'] = $user['status'];
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET name = ?, role = ?, status = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([$data['name'], $data['role'], $data['status'], $user['id']]);
            
            // Log da ação
            $this->logUserAction($user['id'], 'user_updated', 
                "Usuário atualizado pelo admin {$this->user['name']}", true, $user['email']);
            
            $this->flash('success', 'Usuário atualizado com sucesso!');
            $this->redirect('/admin/users');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao atualizar usuário. Tente novamente.');
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
        }
    }
    
    public function changePassword() {
        $this->requireRole('admin');
        
        $userId = intval($_GET['id'] ?? 0);
        if (!$userId) {
            $this->notFound();
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            $this->notFound();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processChangePassword($user);
            return;
        }
        
        $this->render('admin/users/change-password', ['user' => $user]);
    }
    
    private function processChangePassword($user) {
        $data = [
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'force_change' => isset($_POST['force_change'])
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
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password = ?, first_login = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['force_change'] ? 1 : 0,
                $user['id']
            ]);
            
            // Log da ação
            $this->logUserAction($user['id'], 'password_changed_admin', 
                "Senha alterada pelo admin {$this->user['name']}", true, $user['email']);
            
            $this->flash('success', 'Senha alterada com sucesso!');
            $this->redirect('/admin/users');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao alterar senha. Tente novamente.');
            error_log("Erro ao alterar senha: " . $e->getMessage());
        }
    }
    
    public function delete() {
        $this->requireRole('admin');
        
        $userId = intval($_GET['id'] ?? $_POST['id'] ?? 0);
        if (!$userId) {
            $this->flash('error', 'Usuário não encontrado');
            $this->redirect('/admin/users');
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            $this->flash('error', 'Usuário não encontrado');
            $this->redirect('/admin/users');
        }
        
        // Não permitir excluir a si mesmo
        if ($user['id'] == $this->user['id']) {
            $this->flash('error', 'Você não pode excluir sua própria conta');
            $this->redirect('/admin/users');
        }
        
        // Se não veio confirmação, mostrar tela de confirmação
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['confirm_delete'])) {
            $this->renderDashboard('admin/users/confirm-delete', [
                'title' => 'Confirmar Exclusão Permanente',
                'currentPage' => 'admin-users',
                'user' => $user
            ]);
            return;
        }
        
        // Verificar dupla confirmação
        if ($_POST['confirm_delete'] !== 'EXCLUIR' || $_POST['user_email'] !== $user['email']) {
            $this->flash('error', 'Confirmação inválida. Digite exatamente "EXCLUIR" e o email correto.');
            $this->renderDashboard('admin/users/confirm-delete', [
                'title' => 'Confirmar Exclusão Permanente',
                'currentPage' => 'admin-users',
                'user' => $user,
                'error' => true
            ]);
            return;
        }
        
        try {
            $this->db->beginTransaction();
            
            // EXCLUSÃO REAL - Remover dados relacionados primeiro
            $stmt = $this->db->prepare("DELETE FROM user_logs WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            $stmt = $this->db->prepare("DELETE FROM ai_requests WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Excluir usuário permanentemente
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            
            $this->db->commit();
            
            // Log da ação (sem user_id pois foi excluído)
            $this->logUserAction(null, 'user_permanently_deleted', 
                "Usuário {$user['name']} ({$user['email']}) excluído permanentemente pelo admin {$this->user['name']}", true, $user['email']);
            
            $this->flash('success', 'Usuário excluído permanentemente do sistema!');
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->flash('error', 'Erro ao excluir usuário. Tente novamente.');
            error_log("Erro ao excluir usuário: " . $e->getMessage());
        }
        
        $this->redirect('/admin/users');
    }
    
    public function unlock() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
        }
        
        $userId = intval($_POST['id'] ?? 0);
        if (!$userId) {
            $this->flash('error', 'Usuário não encontrado');
            $this->redirect('/admin/users');
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET login_attempts = 0, locked_until = NULL
                WHERE id = ?
            ");
            $stmt->execute([$userId]);
            
            $user = $this->findUser($userId);
            $this->logUserAction($userId, 'user_unlocked', 
                "Usuário desbloqueado pelo admin {$this->user['name']}", true, $user['email']);
            
            $this->flash('success', 'Usuário desbloqueado com sucesso!');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao desbloquear usuário. Tente novamente.');
            error_log("Erro ao desbloquear usuário: " . $e->getMessage());
        }
        
        $this->redirect('/admin/users');
    }
    
    private function findUser($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE id = ? AND deleted_at IS NULL
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
}