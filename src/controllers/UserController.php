<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class UserController extends BaseController {
    
    public function index() {
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
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
        
        // Buscar estatísticas reais
        $stats = $this->getUserStats();
        
        // Buscar todos os usuários com dados completos para filtros dinâmicos
        $allUsers = $this->getAllUsersForFilters();
        
        $this->render('admin/gestao-usuarios-completa', [
            'title' => 'Gestão de Usuários',
            'currentPage' => 'users',
            'user' => $this->user,
            'users' => $users,
            'stats' => $stats,
            'allUsers' => $allUsers,
            'total' => $total,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'role' => $role,
            'status' => $status
        ], 'fisioterapia-premium');
    }
    
    public function create() {
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
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
        $validRoles = ['admin', 'usuario'];
        if (!in_array($data['role'], $validRoles)) {
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
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
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
        $validRoles = ['admin', 'usuario'];
        if (!in_array($data['role'], $validRoles)) {
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
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
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
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
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
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
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
        error_log("DEBUG findUser: Procurando usuário com ID = " . $userId);
        
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE id = ? AND deleted_at IS NULL
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        error_log("DEBUG findUser: " . ($user ? "Usuário encontrado: " . $user['name'] : "Usuário NÃO encontrado"));
        
        return $user;
    }
    
    private function getUserStats() {
        try {
            $stats = [];
            
            // Contar administradores
            $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'admin' AND deleted_at IS NULL");
            $stats['admins'] = $stmt->fetchColumn();
            
            // Contar fisioterapeutas
            $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'usuario' AND deleted_at IS NULL");
            $stats['fisioterapeutas'] = $stmt->fetchColumn();
            
            // Contar usuários online (últimos 15 minutos)
            $stmt = $this->db->query("
                SELECT COUNT(DISTINCT user_id) FROM user_sessions 
                WHERE last_activity > UNIX_TIMESTAMP() - 900
            ");
            $stats['online'] = $stmt->fetchColumn();
            
            // Contar usuários bloqueados
            $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE status = 'inactive' AND deleted_at IS NULL");
            $stats['blocked'] = $stmt->fetchColumn();
            
            return $stats;
        } catch (Exception $e) {
            return [
                'admins' => 0,
                'professionals' => 0,
                'online' => 0,
                'blocked' => 0
            ];
        }
    }
    
    public function toggleStatus() {
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $userId = intval($_POST['id'] ?? 0);
        $newStatus = $_POST['status'] ?? '';
        
        if (!$userId || !in_array($newStatus, ['active', 'inactive'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
            return;
        }
        
        // Não permitir alterar próprio status
        if ($user['id'] == $this->user['id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Você não pode alterar seu próprio status']);
            return;
        }
        
        try {
            $stmt = $this->db->prepare("UPDATE users SET status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$newStatus, $userId]);
            
            $action = $newStatus === 'active' ? 'desbloqueado' : 'bloqueado';
            $this->logUserAction($userId, 'status_changed', 
                "Usuário $action pelo admin {$this->user['name']}", true, $user['email']);
            
            echo json_encode(['success' => true, 'message' => "Usuário $action com sucesso!"]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
            error_log("Erro ao alterar status: " . $e->getMessage());
        }
    }
    
    public function permissions() {
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
        $userId = intval($_GET['id'] ?? 0);
        if (!$userId) {
            $this->notFound();
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            $this->notFound();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processPermissions($user);
            return;
        }
        
        // Buscar permissões atuais do usuário
        $userPermissions = $this->getUserPermissions($userId);
        
        $this->render('admin/users/permissions', [
            'user' => $user,
            'userPermissions' => $userPermissions
        ]);
    }
    
    private function processPermissions($user) {
        $permissions = $_POST['permissions'] ?? [];
        
        try {
            $this->db->beginTransaction();
            
            // Remover permissões antigas
            $stmt = $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            
            // Adicionar novas permissões
            if (!empty($permissions)) {
                $stmt = $this->db->prepare("
                    INSERT INTO user_permissions (user_id, permission_key, granted_at, is_active) 
                    VALUES (?, ?, NOW(), 1)
                ");
                
                foreach ($permissions as $permission) {
                    $stmt->execute([$user['id'], $permission]);
                }
            }
            
            $this->db->commit();
            
            // Log da ação
            $this->logUserAction($user['id'], 'permissions_updated', 
                "Permissões atualizadas pelo admin {$this->user['name']}", true, $user['email']);
            
            $this->flash('success', 'Permissões atualizadas com sucesso!');
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->flash('error', 'Erro ao atualizar permissões. Tente novamente.');
            error_log("Erro ao atualizar permissões: " . $e->getMessage());
        }
        
        $this->redirect("/admin/users/permissions?id={$user['id']}");
    }
    
    private function getUserPermissions($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT permission_key 
                FROM user_permissions 
                WHERE user_id = ? AND is_active = 1
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function logs() {
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
        $userId = intval($_GET['id'] ?? 0);
        if (!$userId) {
            $this->notFound();
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            $this->notFound();
        }
        
        // Buscar logs do usuário
        $stmt = $this->db->prepare("
            SELECT * FROM user_logs 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 100
        ");
        $stmt->execute([$userId]);
        $logs = $stmt->fetchAll();
        
        $this->render('admin/users/logs', ['user' => $user, 'logs' => $logs]);
    }
    
    public function privacy() {
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
        $userId = intval($_GET['id'] ?? 0);
        if (!$userId) {
            $this->notFound();
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            $this->notFound();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processPrivacyUpdate($user);
            return;
        }
        
        $this->render('admin/users/privacy', ['user' => $user]);
    }
    
    private function processPrivacyUpdate($user) {
        $action = $_POST['action'] ?? '';
        
        try {
            switch ($action) {
                case 'anonymize':
                    $this->anonymizeUser($user['id']);
                    $this->flash('success', 'Dados do usuário anonimizados conforme LGPD');
                    break;
                    
                case 'export':
                    $this->exportUserData($user['id']);
                    return;
                    
                case 'delete_logs':
                    $this->deleteUserLogs($user['id']);
                    $this->flash('success', 'Logs do usuário removidos');
                    break;
                    
                default:
                    $this->flash('error', 'Ação inválida');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao processar solicitação LGPD');
            error_log("Erro LGPD: " . $e->getMessage());
        }
        
        $this->redirect("/admin/users/privacy?id={$user['id']}");
    }
    
    private function anonymizeUser($userId) {
        $stmt = $this->db->prepare("
            UPDATE users SET 
                name = 'Usuário Anonimizado',
                email = CONCAT('anonimo_', id, '@removido.local'),
                phone = NULL,
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        
        $this->logUserAction($userId, 'user_anonymized', 
            "Usuário anonimizado pelo admin {$this->user['name']} (LGPD)", true);
    }
    
    private function exportUserData($userId) {
        $user = $this->findUser($userId);
        
        // Buscar todos os dados do usuário
        $stmt = $this->db->prepare("SELECT * FROM user_logs WHERE user_id = ?");
        $stmt->execute([$userId]);
        $logs = $stmt->fetchAll();
        
        $data = [
            'usuario' => $user,
            'logs' => $logs,
            'export_date' => date('Y-m-d H:i:s'),
            'exported_by' => $this->user['name']
        ];
        
        header('Content-Type: application/json');
        header("Content-Disposition: attachment; filename=usuario_{$userId}_dados.json");
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    private function deleteUserLogs($userId) {
        $stmt = $this->db->prepare("DELETE FROM user_logs WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        $this->logUserAction($userId, 'logs_deleted', 
            "Logs removidos pelo admin {$this->user['name']} (LGPD)", true);
    }
    
    private function getAllUsersForFilters() {
        try {
            $stmt = $this->db->query("
                SELECT DISTINCT role, status, especialidade
                FROM users 
                WHERE deleted_at IS NULL
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function export() {
        // Verificar se usuário está logado e é admin
        if (!$this->user || $this->user['role'] !== 'admin') {
            $this->forbidden();
        }
        
        $format = $_GET['format'] ?? 'excel';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        
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
        
        // Buscar usuários com dados completos
        $sql = "
            SELECT 
                u.id,
                u.name,
                u.email,
                u.phone,
                u.role,
                u.status,
                u.department,
                u.position,
                u.last_login,
                u.created_at,
                up.cpf,
                up.birth_date,
                up.gender,
                up.crefito,
                up.main_specialty,
                up.cep,
                up.address,
                up.number,
                up.complement,
                up.neighborhood,
                up.city,
                up.state
            FROM users u
            LEFT JOIN user_profiles_extended up ON u.id = up.user_id
            WHERE $whereClause
            ORDER BY u.name ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();
        
        if ($format === 'pdf') {
            $this->exportPDF($users);
        } else {
            $this->exportExcel($users);
        }
    }
    
    private function exportExcel($users) {
        // Define headers para download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="usuarios_' . date('Y-m-d_H-i-s') . '.csv"');
        header('Cache-Control: max-age=0');
        
        // Abre output stream
        $output = fopen('php://output', 'w');
        
        // Adiciona BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers da planilha
        fputcsv($output, [
            'ID',
            'Nome',
            'Email',
            'Telefone',
            'CPF',
            'Data Nascimento',
            'Gênero',
            'Perfil',
            'Status',
            'Departamento',
            'Cargo',
            'CREFITO',
            'Especialidade',
            'CEP',
            'Endereço',
            'Número',
            'Complemento',
            'Bairro',
            'Cidade',
            'Estado',
            'Último Login',
            'Cadastrado em'
        ], ';');
        
        // Dados dos usuários
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['phone'],
                $user['cpf'],
                $user['birth_date'] ? date('d/m/Y', strtotime($user['birth_date'])) : '',
                $user['gender'],
                $user['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta',
                $user['status'] === 'active' ? 'Ativo' : 'Inativo',
                $user['department'],
                $user['position'],
                $user['crefito'],
                $user['main_specialty'],
                $user['cep'],
                $user['address'],
                $user['number'],
                $user['complement'],
                $user['neighborhood'],
                $user['city'],
                $user['state'],
                $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Nunca',
                date('d/m/Y H:i', strtotime($user['created_at']))
            ], ';');
        }
        
        fclose($output);
        exit;
    }
    
    private function exportPDF($users) {
        // Para implementar PDF, seria necessário uma biblioteca como TCPDF ou DomPDF
        // Por enquanto, vamos criar um HTML que pode ser impresso como PDF
        
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Lista de Usuários - ' . date('d/m/Y') . '</title>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { color: #4D9AE6; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #4D9AE6; color: white; }
                tr:nth-child(even) { background-color: #f2f2f2; }
                .header { margin-bottom: 30px; }
                .footer { margin-top: 30px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Lista de Usuários</h1>
                <p>Exportado em: ' . date('d/m/Y às H:i') . '</p>
                <p>Total de usuários: ' . count($users) . '</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>CREFITO</th>
                        <th>Último Login</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($users as $user) {
            $html .= '<tr>
                <td>' . htmlspecialchars($user['name']) . '</td>
                <td>' . htmlspecialchars($user['email']) . '</td>
                <td>' . htmlspecialchars($user['phone'] ?? '-') . '</td>
                <td>' . ($user['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta') . '</td>
                <td>' . ($user['status'] === 'active' ? 'Ativo' : 'Inativo') . '</td>
                <td>' . htmlspecialchars($user['crefito'] ?? '-') . '</td>
                <td>' . ($user['last_login'] ? date('d/m/Y', strtotime($user['last_login'])) : 'Nunca') . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
            </table>
            
            <div class="footer">
                <p>MegaFisio IA - Sistema de Gestão de Fisioterapia</p>
            </div>
            
            <script>window.print();</script>
        </body>
        </html>';
        
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }
    
    public function getUserData() {
        // Forçar sempre JSON response
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');
        
        // Verificar se usuário está logado
        if (!$this->user) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
            die();
        }
        
        // Verificar se é admin - permitir qualquer usuário com role 'admin'
        if ($this->user['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Acesso negado - apenas administradores']);
            die();
        }
        
        $userId = intval($_GET['id'] ?? 0);
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido']);
            die();
        }
        
        try {
            // Buscar dados básicos do usuário
            $sql = "SELECT id, name, email, role, status, created_at, last_login, must_change_password FROM users WHERE id = ? AND deleted_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
                die();
            }
            
            // Buscar dados do perfil estendido se existir
            $profileSql = "SELECT * FROM user_profiles_extended WHERE user_id = ?";
            $profileStmt = $this->db->prepare($profileSql);
            $profileStmt->execute([$userId]);
            $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);
            
            // Combinar dados se perfil existe
            if ($profile) {
                // Remover campos que podem duplicar
                unset($profile['user_id'], $profile['created_at'], $profile['updated_at']);
                $user = array_merge($user, $profile);
            }
            
            // Retornar dados
            echo json_encode(['success' => true, 'user' => $user]);
            die();
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
            error_log("Erro getUserData: " . $e->getMessage());
            die();
        }
    }
    
    public function updateUser() {
        header('Content-Type: application/json');
        
        // Verificar se usuário está logado
        if (!$this->user) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }
        
        // Verificar se é admin - permitir qualquer usuário com role 'admin'
        if ($this->user['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Acesso negado - apenas administradores']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $userId = intval($_POST['user_id'] ?? 0);
        error_log("DEBUG updateUser: user_id recebido = " . ($_POST['user_id'] ?? 'VAZIO') . ", convertido para int = " . $userId);
        
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido']);
            return;
        }
        
        $user = $this->findUser($userId);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
            return;
        }
        
        // Dados básicos do usuário
        $userData = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? $user['role'],
            'status' => $_POST['status'] ?? $user['status'],
            'department' => trim($_POST['department'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'must_change_password' => isset($_POST['must_change_password']) ? 1 : 0
        ];
        
        // Dados do perfil estendido (incluindo campos que não cabem na tabela users)
        $profileData = [
            'phone' => trim($_POST['phone'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'position' => trim($_POST['position'] ?? ''),
            'must_change_password' => isset($_POST['must_change_password']) ? 1 : 0,
            'cpf' => trim($_POST['cpf'] ?? ''),
            'birth_date' => $_POST['birth_date'] ?: null,
            'gender' => $_POST['gender'] ?: null,
            'crefito' => trim($_POST['crefito'] ?? ''),
            'main_specialty' => $_POST['main_specialty'] ?: null,
            'cep' => trim($_POST['cep'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'number' => trim($_POST['number'] ?? ''),
            'complement' => trim($_POST['complement'] ?? ''),
            'neighborhood' => trim($_POST['neighborhood'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => $_POST['state'] ?: null
        ];
        
        // Validações básicas
        if (empty($userData['name']) || strlen($userData['name']) < 2) {
            echo json_encode(['success' => false, 'message' => 'Nome deve ter pelo menos 2 caracteres']);
            return;
        }
        
        // Validar role e status
        $validRoles = ['admin', 'usuario'];
        if (!in_array($userData['role'], $validRoles)) {
            $userData['role'] = $user['role'];
        }
        
        if (!in_array($userData['status'], ['active', 'inactive'])) {
            $userData['status'] = $user['status'];
        }
        
        try {
            // Garantir que os campos existem na tabela user_profiles_extended
            $this->ensureProfileFields();
            
            $this->db->beginTransaction();
            
            // Atualizar apenas dados básicos do usuário (campos que existem na tabela)
            $stmt = $this->db->prepare("
                UPDATE users 
                SET name = ?, role = ?, status = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([
                $userData['name'],
                $userData['role'],
                $userData['status'],
                $userId
            ]);
            
            // Verificar se já existe perfil estendido
            $stmt = $this->db->prepare("SELECT user_id FROM user_profiles_extended WHERE user_id = ?");
            $stmt->execute([$userId]);
            $profileExists = $stmt->fetch();
            
            if ($profileExists) {
                // Atualizar perfil existente
                $stmt = $this->db->prepare("
                    UPDATE user_profiles_extended 
                    SET phone = ?, department = ?, position = ?, must_change_password = ?,
                        cpf = ?, birth_date = ?, gender = ?, crefito = ?, main_specialty = ?,
                        cep = ?, address = ?, number = ?, complement = ?, neighborhood = ?,
                        city = ?, state = ?, updated_at = NOW()
                    WHERE user_id = ?
                ");
                
                $stmt->execute([
                    $profileData['phone'],
                    $profileData['department'],
                    $profileData['position'],
                    $profileData['must_change_password'],
                    $profileData['cpf'],
                    $profileData['birth_date'],
                    $profileData['gender'],
                    $profileData['crefito'],
                    $profileData['main_specialty'],
                    $profileData['cep'],
                    $profileData['address'],
                    $profileData['number'],
                    $profileData['complement'],
                    $profileData['neighborhood'],
                    $profileData['city'],
                    $profileData['state'],
                    $userId
                ]);
            } else {
                // Criar novo perfil
                $stmt = $this->db->prepare("
                    INSERT INTO user_profiles_extended 
                    (user_id, phone, department, position, must_change_password,
                     cpf, birth_date, gender, crefito, main_specialty, cep, address, 
                     number, complement, neighborhood, city, state, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                
                $stmt->execute([
                    $userId,
                    $profileData['phone'],
                    $profileData['department'],
                    $profileData['position'],
                    $profileData['must_change_password'],
                    $profileData['cpf'],
                    $profileData['birth_date'],
                    $profileData['gender'],
                    $profileData['crefito'],
                    $profileData['main_specialty'],
                    $profileData['cep'],
                    $profileData['address'],
                    $profileData['number'],
                    $profileData['complement'],
                    $profileData['neighborhood'],
                    $profileData['city'],
                    $profileData['state']
                ]);
            }
            
            $this->db->commit();
            
            // Log da ação
            $this->logUserAction($userId, 'user_updated', 
                "Usuário atualizado pelo admin {$this->user['name']}", true, $user['email']);
            
            echo json_encode(['success' => true, 'message' => 'Usuário atualizado com sucesso!']);
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Garantir que todos os campos necessários existem na tabela user_profiles_extended
     */
    private function ensureProfileFields() {
        // Carregar SmartMigrationManager
        require_once SRC_PATH . '/models/SmartMigrationManager.php';
        
        // Garantir que os campos existem na tabela user_profiles_extended
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'phone', 'VARCHAR(20) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'department', 'VARCHAR(100) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'position', 'VARCHAR(100) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'must_change_password', 'TINYINT(1) DEFAULT 0');
        
        // Campos que já devem existir mas vamos garantir
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'cpf', 'VARCHAR(14) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'birth_date', 'DATE NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'gender', 'ENUM("M", "F", "O") NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'crefito', 'VARCHAR(20) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'main_specialty', 'VARCHAR(100) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'cep', 'VARCHAR(10) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'address', 'VARCHAR(255) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'number', 'VARCHAR(10) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'complement', 'VARCHAR(100) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'neighborhood', 'VARCHAR(100) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'city', 'VARCHAR(100) NULL');
        SmartMigrationManager::ensureColumn('user_profiles_extended', 'state', 'VARCHAR(2) NULL');
    }
}