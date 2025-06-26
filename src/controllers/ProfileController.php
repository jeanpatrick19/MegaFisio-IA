<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class ProfileController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Log da visualização do perfil
        $this->logUserAction($this->user['id'], 'profile_view', 'Visualizou próprio perfil');
        
        // Garantir que a tabela user_profiles_extended existe
        require_once SRC_PATH . '/models/SmartMigrationManager.php';
        SmartMigrationManager::ensureTable('user_profiles_extended');
        
        // Buscar dados completos do usuário com JOIN na tabela estendida
        $stmt = $this->db->prepare("
            SELECT u.id, u.email, u.name, u.role, u.status, u.created_at, u.last_login, u.updated_at,
                   p.phone, p.birth_date, p.gender, p.marital_status, p.cep, p.address, p.number, 
                   p.complement, p.neighborhood, p.city, p.state, p.crefito, p.main_specialty, 
                   p.education, p.graduation_year, p.experience_time, p.workplace, 
                   p.secondary_specialties, p.professional_bio, p.language, p.timezone, 
                   p.date_format, p.theme, p.compact_interface, p.reduced_animations,
                   p.email_notifications, p.system_notifications, p.ai_updates, p.newsletter,
                   p.two_factor_enabled, p.two_factor_secret, p.avatar_type, p.avatar_path, p.avatar_default
            FROM users u
            LEFT JOIN user_profiles_extended p ON u.id = p.user_id
            WHERE u.id = ? AND u.deleted_at IS NULL
        ");
        $stmt->execute([$this->user['id']]);
        $userProfile = $stmt->fetch();
        
        if (!$userProfile) {
            $this->logout();
        }
        
        // Processar especialidades secundárias (JSON)
        if (!empty($userProfile['secondary_specialties'])) {
            $userProfile['secondary_specialties_array'] = json_decode($userProfile['secondary_specialties'], true) ?: [];
        } else {
            $userProfile['secondary_specialties_array'] = [];
        }
        
        // Buscar últimos acessos
        $recentLogins = $this->getRecentLogins();
        
        // Buscar estatísticas de uso
        $usageStats = $this->getUserStats();
        
        $this->render('profile/meu-perfil', [
            'title' => 'Meu Perfil',
            'currentPage' => 'profile',
            'user' => $this->user,
            'userProfile' => $userProfile,
            'recentLogins' => $recentLogins,
            'usageStats' => $usageStats
        ], 'fisioterapia-premium');
    }
    
    public function edit() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit();
            return;
        }
        
        $this->render('profile/edit');
    }
    
    private function processEdit() {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];
        
        $errors = $this->validate([
            'name' => ['required' => true, 'min' => 3, 'max' => 255],
            'email' => ['required' => true, 'email' => true]
        ]);
        
        if (!empty($errors)) {
            $this->flash('error', 'Por favor, corrija os erros no formulário');
            return;
        }
        
        // Verificar se email já existe para outro usuário
        if ($data['email'] !== $this->user['email']) {
            $stmt = $this->db->prepare("
                SELECT id FROM users 
                WHERE email = ? AND id != ? AND deleted_at IS NULL
            ");
            $stmt->execute([$data['email'], $this->user['id']]);
            
            if ($stmt->fetch()) {
                $this->flash('error', 'Este email já está em uso por outro usuário');
                return;
            }
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET name = ?, email = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $stmt->execute([$data['name'], $data['email'], $this->user['id']]);
            
            // Atualizar dados da sessão
            $_SESSION['user_name'] = $data['name'];
            $_SESSION['user_email'] = $data['email'];
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'profile_updated', 
                'Usuário atualizou próprio perfil', true);
            
            $this->flash('success', 'Perfil atualizado com sucesso!');
            $this->redirect('/profile');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao atualizar perfil. Tente novamente.');
            error_log("Erro ao atualizar perfil: " . $e->getMessage());
        }
    }
    
    public function savePersonalData() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        // Garantir que a tabela user_profiles_extended existe
        require_once SRC_PATH . '/models/SmartMigrationManager.php';
        SmartMigrationManager::ensureTable('user_profiles_extended');
        
        try {
            $data = [
                'nome' => trim($_POST['nome'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'telefone' => trim($_POST['telefone'] ?? ''),
                'data_nascimento' => $_POST['data_nascimento'] ?? null,
                'genero' => $_POST['genero'] ?? null,
                'estado_civil' => $_POST['estado_civil'] ?? null,
                'cep' => trim($_POST['cep'] ?? ''),
                'endereco' => trim($_POST['endereco'] ?? ''),
                'numero' => trim($_POST['numero'] ?? ''),
                'complemento' => trim($_POST['complemento'] ?? ''),
                'bairro' => trim($_POST['bairro'] ?? ''),
                'cidade' => trim($_POST['cidade'] ?? ''),
                'estado' => $_POST['estado'] ?? null
            ];
            
            // Validações básicas
            if (empty($data['nome'])) {
                throw new Exception('Nome é obrigatório');
            }
            
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email válido é obrigatório');
            }
            
            // Verificar se email já existe para outro usuário
            if ($data['email'] !== $this->user['email']) {
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ? AND deleted_at IS NULL");
                $stmt->execute([$data['email'], $this->user['id']]);
                if ($stmt->fetch()) {
                    throw new Exception('Este email já está em uso por outro usuário');
                }
            }
            
            // Atualizar dados básicos na tabela users
            $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$data['nome'], $data['email'], $this->user['id']]);
            
            // Inserir/atualizar dados estendidos
            $stmt = $this->db->prepare("
                INSERT INTO user_profiles_extended 
                (user_id, phone, birth_date, gender, marital_status, cep, address, number, complement, neighborhood, city, state) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                phone = VALUES(phone), birth_date = VALUES(birth_date), gender = VALUES(gender), 
                marital_status = VALUES(marital_status), cep = VALUES(cep), address = VALUES(address),
                number = VALUES(number), complement = VALUES(complement), neighborhood = VALUES(neighborhood), 
                city = VALUES(city), state = VALUES(state), updated_at = NOW()
            ");
            
            $stmt->execute([
                $this->user['id'],
                $data['telefone'] ?: null,
                $data['data_nascimento'] ?: null,
                $data['genero'] ?: null,
                $data['estado_civil'] ?: null,
                $data['cep'] ?: null,
                $data['endereco'] ?: null,
                $data['numero'] ?: null,
                $data['complemento'] ?: null,
                $data['bairro'] ?: null,
                $data['cidade'] ?: null,
                $data['estado'] ?: null
            ]);
            
            // Atualizar dados da sessão
            $_SESSION['user_name'] = $data['nome'];
            $_SESSION['user_email'] = $data['email'];
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'personal_data_updated', 'Dados pessoais atualizados', true);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Dados pessoais salvos com sucesso!']);
            
        } catch (Exception $e) {
            error_log("Erro ao salvar dados pessoais: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function saveProfessionalData() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            $data = [
                'crefito' => trim($_POST['crefito'] ?? ''),
                'especialidade' => $_POST['especialidade'] ?? null,
                'formacao' => trim($_POST['formacao'] ?? ''),
                'ano_formacao' => $_POST['ano_formacao'] ?? null,
                'tempo_experiencia' => $_POST['tempo_experiencia'] ?? null,
                'local_trabalho' => trim($_POST['local_trabalho'] ?? ''),
                'especialidades' => $_POST['especialidades'] ?? [],
                'bio_profissional' => trim($_POST['bio_profissional'] ?? '')
            ];
            
            // Processar especialidades secundárias
            $especialidades_json = !empty($data['especialidades']) ? json_encode($data['especialidades']) : null;
            
            // Inserir/atualizar dados profissionais
            $stmt = $this->db->prepare("
                INSERT INTO user_profiles_extended 
                (user_id, crefito, main_specialty, education, graduation_year, experience_time, workplace, secondary_specialties, professional_bio) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                crefito = VALUES(crefito), main_specialty = VALUES(main_specialty), education = VALUES(education),
                graduation_year = VALUES(graduation_year), experience_time = VALUES(experience_time), 
                workplace = VALUES(workplace), secondary_specialties = VALUES(secondary_specialties),
                professional_bio = VALUES(professional_bio), updated_at = NOW()
            ");
            
            $stmt->execute([
                $this->user['id'],
                $data['crefito'] ?: null,
                $data['especialidade'] ?: null,
                $data['formacao'] ?: null,
                $data['ano_formacao'] ?: null,
                $data['tempo_experiencia'] ?: null,
                $data['local_trabalho'] ?: null,
                $especialidades_json,
                $data['bio_profissional'] ?: null
            ]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'professional_data_updated', 'Dados profissionais atualizados', true);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Dados profissionais salvos com sucesso!']);
            
        } catch (Exception $e) {
            error_log("Erro ao salvar dados profissionais: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function changePassword() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            $senhaAtual = $_POST['senha_atual'] ?? '';
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';
            
            // Validações
            if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
                throw new Exception('Todos os campos são obrigatórios');
            }
            
            if ($novaSenha !== $confirmarSenha) {
                throw new Exception('As senhas não coincidem');
            }
            
            if (strlen($novaSenha) < 8) {
                throw new Exception('A nova senha deve ter pelo menos 8 caracteres');
            }
            
            // Verificar senha atual
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$this->user['id']]);
            $currentPassword = $stmt->fetchColumn();
            
            if (!password_verify($senhaAtual, $currentPassword)) {
                throw new Exception('Senha atual incorreta');
            }
            
            // Atualizar senha
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$novaSenhaHash, $this->user['id']]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'password_changed', 'Senha alterada pelo usuário', true);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Senha alterada com sucesso!']);
            
        } catch (Exception $e) {
            error_log("Erro ao alterar senha: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function savePreferences() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            // Garantir que a tabela user_profiles_extended existe
            require_once SRC_PATH . '/models/SmartMigrationManager.php';
            SmartMigrationManager::ensureTable('user_profiles_extended');
            
            $data = [
                'language' => $_POST['language'] ?? 'pt-BR',
                'timezone' => $_POST['timezone'] ?? 'America/Sao_Paulo',
                'date_format' => $_POST['date_format'] ?? 'dd/mm/yyyy',
                'theme' => $_POST['theme'] ?? 'claro',
                'compact_interface' => isset($_POST['compact_interface']) && $_POST['compact_interface'] === 'true' ? 1 : 0,
                'reduced_animations' => isset($_POST['reduced_animations']) && $_POST['reduced_animations'] === 'true' ? 1 : 0,
                'email_notifications' => isset($_POST['email_notifications']) && $_POST['email_notifications'] === 'true' ? 1 : 0,
                'system_notifications' => isset($_POST['system_notifications']) && $_POST['system_notifications'] === 'true' ? 1 : 0,
                'ai_updates' => isset($_POST['ai_updates']) && $_POST['ai_updates'] === 'true' ? 1 : 0,
                'newsletter' => isset($_POST['newsletter']) && $_POST['newsletter'] === 'true' ? 1 : 0
            ];
            
            // Inserir/atualizar preferências
            $stmt = $this->db->prepare("
                INSERT INTO user_profiles_extended 
                (user_id, language, timezone, date_format, theme, compact_interface, reduced_animations,
                 email_notifications, system_notifications, ai_updates, newsletter) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                language = VALUES(language), timezone = VALUES(timezone), date_format = VALUES(date_format),
                theme = VALUES(theme), compact_interface = VALUES(compact_interface), 
                reduced_animations = VALUES(reduced_animations), email_notifications = VALUES(email_notifications),
                system_notifications = VALUES(system_notifications), ai_updates = VALUES(ai_updates),
                newsletter = VALUES(newsletter), updated_at = NOW()
            ");
            
            $stmt->execute([
                $this->user['id'],
                $data['language'],
                $data['timezone'],
                $data['date_format'],
                $data['theme'],
                $data['compact_interface'],
                $data['reduced_animations'],
                $data['email_notifications'],
                $data['system_notifications'],
                $data['ai_updates'],
                $data['newsletter']
            ]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'preferences_updated', 'Preferências atualizadas', true);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Preferências salvas com sucesso!']);
            
        } catch (Exception $e) {
            error_log("Erro ao salvar preferências: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function getPreferences() {
        $this->requireAuth();
        
        try {
            // Buscar preferências do usuário
            $stmt = $this->db->prepare("
                SELECT language, timezone, date_format, theme, compact_interface, 
                       reduced_animations, email_notifications, system_notifications, 
                       ai_updates, newsletter
                FROM user_profiles_extended 
                WHERE user_id = ?
            ");
            $stmt->execute([$this->user['id']]);
            $preferences = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Se não existir, retornar padrões
            if (!$preferences) {
                $preferences = [
                    'language' => 'pt-BR',
                    'timezone' => 'America/Sao_Paulo',
                    'date_format' => 'dd/mm/yyyy',
                    'theme' => 'claro',
                    'compact_interface' => false,
                    'reduced_animations' => false,
                    'email_notifications' => true,
                    'system_notifications' => true,
                    'ai_updates' => false,
                    'newsletter' => false
                ];
            } else {
                // Converter valores booleanos
                $preferences['compact_interface'] = (bool)$preferences['compact_interface'];
                $preferences['reduced_animations'] = (bool)$preferences['reduced_animations'];
                $preferences['email_notifications'] = (bool)$preferences['email_notifications'];
                $preferences['system_notifications'] = (bool)$preferences['system_notifications'];
                $preferences['ai_updates'] = (bool)$preferences['ai_updates'];
                $preferences['newsletter'] = (bool)$preferences['newsletter'];
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'preferences' => $preferences
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar preferências: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ]);
        }
        exit;
    }
    
    public function privacy() {
        $this->requireAuth();
        
        // Buscar dados pessoais do usuário para LGPD
        $personalData = $this->getPersonalData();
        
        $this->render('profile/privacy', [
            'personalData' => $personalData
        ]);
    }
    
    public function exportData() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile/privacy');
        }
        
        try {
            $userData = $this->getPersonalData();
            $logs = $this->getUserLogs();
            
            $exportData = [
                'user_info' => $userData,
                'access_logs' => $logs,
                'export_date' => date('Y-m-d H:i:s'),
                'export_requested_by' => $this->user['email']
            ];
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'data_export', 
                'Usuário solicitou exportação de dados', true);
            
            // Headers para download
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="meus_dados_megafisio_' . date('Y-m-d') . '.json"');
            
            echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            exit;
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao exportar dados. Tente novamente.');
            error_log("Erro na exportação de dados: " . $e->getMessage());
            $this->redirect('/profile/privacy');
        }
    }
    
    public function requestDeletion() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile/privacy');
        }
        
        $reason = trim($_POST['reason'] ?? '');
        
        try {
            // Log da solicitação
            $this->logUserAction($this->user['id'], 'deletion_requested', 
                "Usuário solicitou exclusão de dados. Motivo: $reason", true);
            
            // Criar notificação para admin
            $stmt = $this->db->prepare("
                INSERT INTO notifications (user_id, type, title, message, data)
                SELECT id, 'admin_alert', 'Solicitação de Exclusão de Dados', 
                       CONCAT('Usuário ', ?, ' solicitou exclusão de seus dados'), 
                       JSON_OBJECT('user_id', ?, 'reason', ?)
                FROM users WHERE role = 'admin'
            ");
            $stmt->execute([$this->user['name'], $this->user['id'], $reason]);
            
            $this->flash('success', 'Solicitação de exclusão enviada. Entraremos em contato em até 48 horas.');
            $this->redirect('/profile/privacy');
            
        } catch (Exception $e) {
            $this->flash('error', 'Erro ao processar solicitação. Tente novamente.');
            error_log("Erro na solicitação de exclusão: " . $e->getMessage());
            $this->redirect('/profile/privacy');
        }
    }
    
    private function getRecentLogins($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT acao, ip_address, user_agent, data_hora, sucesso
            FROM user_logs 
            WHERE user_id = ? AND acao IN ('login_success', 'login_failed')
            ORDER BY data_hora DESC
            LIMIT ?
        ");
        $stmt->execute([$this->user['id'], $limit]);
        return $stmt->fetchAll();
    }
    
    private function getUserStats() {
        // Estatísticas de uso do usuário
        $stats = [];
        
        // Total de requisições IA
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total_requests
            FROM ai_requests 
            WHERE user_id = ?
        ");
        $stmt->execute([$this->user['id']]);
        $stats['ai_requests'] = $stmt->fetchColumn();
        
        // Uso por mês
        $stmt = $this->db->prepare("
            SELECT 
                DATE_FORMAT(data_hora, '%Y-%m') as month,
                COUNT(*) as actions
            FROM user_logs 
            WHERE user_id = ? AND data_hora > DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month DESC
        ");
        $stmt->execute([$this->user['id']]);
        $stats['monthly_activity'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    private function getPersonalData() {
        $stmt = $this->db->prepare("
            SELECT id, email, name, role, status, created_at, last_login, updated_at
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$this->user['id']]);
        
        return $stmt->fetch();
    }
    
    private function getUserLogs($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT acao, detalhes, ip_address, data_hora, sucesso
            FROM user_logs 
            WHERE user_id = ?
            ORDER BY data_hora DESC
            LIMIT ?
        ");
        $stmt->execute([$this->user['id'], $limit]);
        return $stmt->fetchAll();
    }
    
    public function uploadAvatar() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        // Garantir que a tabela user_profiles_extended existe
        require_once SRC_PATH . '/models/SmartMigrationManager.php';
        SmartMigrationManager::ensureTable('user_profiles_extended');
        
        try {
            if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Erro no upload do arquivo');
            }
            
            $file = $_FILES['avatar'];
            
            // Validações
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP.');
            }
            
            if ($file['size'] > 2 * 1024 * 1024) { // 2MB
                throw new Exception('Arquivo muito grande. Máximo 2MB.');
            }
            
            // Criar diretório se não existir
            $uploadDir = ROOT_PATH . '/public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Gerar nome único
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = 'avatar_' . $this->user['id'] . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $fileName;
            
            // Mover arquivo
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new Exception('Erro ao salvar arquivo');
            }
            
            // Salvar no banco
            $relativePath = '/public/uploads/avatars/' . $fileName;
            $stmt = $this->db->prepare("
                INSERT INTO user_profiles_extended 
                (user_id, avatar_type, avatar_path, avatar_default) 
                VALUES (?, 'upload', ?, NULL)
                ON DUPLICATE KEY UPDATE
                avatar_type = 'upload', avatar_path = VALUES(avatar_path), 
                avatar_default = NULL, updated_at = NOW()
            ");
            $stmt->execute([$this->user['id'], $relativePath]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'avatar_uploaded', 'Avatar atualizado via upload', true);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Avatar atualizado com sucesso!',
                'avatar_url' => $relativePath
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao fazer upload de avatar: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function selectDefaultAvatar() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            $avatarLetter = trim($_POST['avatar_letter'] ?? '');
            
            // Validações
            if (empty($avatarLetter) || strlen($avatarLetter) !== 1) {
                throw new Exception('Letra do avatar inválida');
            }
            
            if (!ctype_alpha($avatarLetter)) {
                throw new Exception('Avatar deve ser uma letra');
            }
            
            $avatarLetter = strtoupper($avatarLetter);
            
            // Salvar no banco
            $stmt = $this->db->prepare("
                INSERT INTO user_profiles_extended 
                (user_id, avatar_type, avatar_path, avatar_default) 
                VALUES (?, 'default', NULL, ?)
                ON DUPLICATE KEY UPDATE
                avatar_type = 'default', avatar_path = NULL, 
                avatar_default = VALUES(avatar_default), updated_at = NOW()
            ");
            $stmt->execute([$this->user['id'], $avatarLetter]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'avatar_selected', "Avatar padrão selecionado: $avatarLetter", true);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Avatar selecionado com sucesso!',
                'avatar_letter' => $avatarLetter
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao selecionar avatar: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    // =================== MÉTODOS DE 2FA ===================
    
    public function enable2FA() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            require_once SRC_PATH . '/services/TwoFactorService.php';
            
            // Gerar novo segredo
            $secret = TwoFactorService::generateSecret();
            
            // Salvar temporariamente (não ativar ainda)
            $stmt = $this->db->prepare("
                INSERT INTO user_profiles_extended 
                (user_id, two_factor_secret, two_factor_enabled) 
                VALUES (?, ?, FALSE)
                ON DUPLICATE KEY UPDATE
                two_factor_secret = VALUES(two_factor_secret), updated_at = NOW()
            ");
            $stmt->execute([$this->user['id'], $secret]);
            
            // Gerar QR Code URL
            $qrCodeURL = TwoFactorService::getQRCodeURL($secret, $this->user['email']);
            
            // Log da ação
            $this->logUserAction($this->user['id'], '2fa_setup_started', 'Usuário iniciou configuração de 2FA', true);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'secret' => $secret,
                'qrCodeURL' => $qrCodeURL,
                'message' => 'QR Code gerado. Configure seu app e insira o código para ativar.'
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao iniciar 2FA: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erro interno. Tente novamente.']);
        }
        exit;
    }
    
    public function confirm2FA() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            require_once SRC_PATH . '/services/TwoFactorService.php';
            
            $code = trim($_POST['code'] ?? '');
            
            if (empty($code) || !preg_match('/^\d{6}$/', $code)) {
                throw new Exception('Código inválido. Digite um código de 6 dígitos.');
            }
            
            // Buscar segredo temporário
            $stmt = $this->db->prepare("
                SELECT two_factor_secret 
                FROM user_profiles_extended 
                WHERE user_id = ? AND two_factor_secret IS NOT NULL
            ");
            $stmt->execute([$this->user['id']]);
            $secret = $stmt->fetchColumn();
            
            if (!$secret) {
                throw new Exception('Configuração não iniciada. Gere um novo QR Code.');
            }
            
            // Verificar código
            if (!TwoFactorService::verifyCode($secret, $code)) {
                throw new Exception('Código incorreto. Verifique seu app e tente novamente.');
            }
            
            // Gerar códigos de backup
            $backupCodes = TwoFactorService::generateBackupCodes();
            
            // Ativar 2FA
            $stmt = $this->db->prepare("
                UPDATE user_profiles_extended 
                SET two_factor_enabled = TRUE, 
                    backup_codes = ?, 
                    updated_at = NOW()
                WHERE user_id = ?
            ");
            $stmt->execute([json_encode($backupCodes), $this->user['id']]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], '2fa_enabled', 'Autenticação de dois fatores ativada', true);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => '2FA ativado com sucesso!',
                'backupCodes' => $backupCodes
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao confirmar 2FA: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function disable2FA() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            $this->validateCSRF();
            
            require_once SRC_PATH . '/services/TwoFactorService.php';
            
            // Verificar se 2FA está ativado
            $stmt = $this->db->prepare("
                SELECT two_factor_enabled
                FROM user_profiles_extended 
                WHERE user_id = ?
            ");
            $stmt->execute([$this->user['id']]);
            $twoFAEnabled = $stmt->fetchColumn();
            
            if (!$twoFAEnabled) {
                throw new Exception('2FA não está ativado.');
            }
            
            // Desativar 2FA
            $stmt = $this->db->prepare("
                UPDATE user_profiles_extended 
                SET two_factor_enabled = FALSE, 
                    two_factor_secret = NULL, 
                    backup_codes = NULL, 
                    updated_at = NOW()
                WHERE user_id = ?
            ");
            $stmt->execute([$this->user['id']]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], '2fa_disabled', 'Autenticação de dois fatores desativada', true);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => '2FA desativado com sucesso!'
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao desativar 2FA: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function get2FAStatus() {
        $this->requireAuth();
        
        try {
            $stmt = $this->db->prepare("
                SELECT two_factor_enabled, 
                       CASE WHEN backup_codes IS NOT NULL 
                            THEN JSON_LENGTH(backup_codes) 
                            ELSE 0 
                       END as backup_codes_count
                FROM user_profiles_extended 
                WHERE user_id = ?
            ");
            $stmt->execute([$this->user['id']]);
            $status = $stmt->fetch();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'enabled' => (bool)($status['two_factor_enabled'] ?? false),
                'backupCodesCount' => (int)($status['backup_codes_count'] ?? 0)
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar status 2FA: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar status.']);
        }
        exit;
    }
    
    public function regenerateBackupCodes() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        try {
            require_once SRC_PATH . '/services/TwoFactorService.php';
            
            $password = $_POST['password'] ?? '';
            
            // Verificar senha atual
            $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$this->user['id']]);
            $currentPassword = $stmt->fetchColumn();
            
            if (!password_verify($password, $currentPassword)) {
                throw new Exception('Senha incorreta.');
            }
            
            // Verificar se 2FA está ativo
            $stmt = $this->db->prepare("
                SELECT two_factor_enabled 
                FROM user_profiles_extended 
                WHERE user_id = ?
            ");
            $stmt->execute([$this->user['id']]);
            $enabled = $stmt->fetchColumn();
            
            if (!$enabled) {
                throw new Exception('2FA não está ativado.');
            }
            
            // Gerar novos códigos
            $backupCodes = TwoFactorService::generateBackupCodes();
            
            // Salvar códigos
            $stmt = $this->db->prepare("
                UPDATE user_profiles_extended 
                SET backup_codes = ?, updated_at = NOW()
                WHERE user_id = ?
            ");
            $stmt->execute([json_encode($backupCodes), $this->user['id']]);
            
            // Log da ação
            $this->logUserAction($this->user['id'], 'backup_codes_regenerated', 'Códigos de backup regenerados', true);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Novos códigos de backup gerados!',
                'backupCodes' => $backupCodes
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao regenerar códigos: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    // =================== MÉTODOS AUXILIARES ===================
    
    // Tabela user_sessions é criada automaticamente pelo SmartMigrationManager
    
    private function forceCreateCurrentSession($userId) {
        try {
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
            
            // Marcar outras como não-atual
            $stmt = $this->db->prepare("UPDATE user_sessions SET is_current = FALSE WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Inserir sessão atual obrigatoriamente
            $stmt = $this->db->prepare("
                INSERT INTO user_sessions (
                    id, user_id, user_agent, ip_address, location, 
                    device_type, browser, os, is_current, is_active, expires_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE, TRUE, DATE_ADD(NOW(), INTERVAL 30 DAY))
                ON DUPLICATE KEY UPDATE
                    is_current = TRUE,
                    is_active = TRUE,
                    last_activity = CURRENT_TIMESTAMP,
                    expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY)
            ");
            
            return $stmt->execute([
                $sessionId,
                $userId,
                $userAgent,
                $ipAddress,
                'Sessão Atual',
                $device,
                $browser,
                $os
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao forçar criação de sessão atual: " . $e->getMessage());
            return false;
        }
    }
    
    private function guaranteeCurrentSession($userId) {
        try {
            $sessionId = session_id();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Sistema';
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            
            // Detectar dispositivo
            $device = 'Desktop';
            $browser = 'Navegador';
            $os = 'Sistema';
            
            if (preg_match('/Mobile|Android|iPhone/', $userAgent)) {
                $device = 'Mobile';
            } elseif (preg_match('/iPad/', $userAgent)) {
                $device = 'Tablet';
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
            
            // Marcar outras sessões como não-atuais
            $stmt = $this->db->prepare("UPDATE user_sessions SET is_current = FALSE WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Inserir ou atualizar sessão atual
            $stmt = $this->db->prepare("
                INSERT INTO user_sessions (
                    id, user_id, user_agent, ip_address, location, 
                    device_type, browser, os, is_current, is_active, expires_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE, TRUE, DATE_ADD(NOW(), INTERVAL 30 DAY))
                ON DUPLICATE KEY UPDATE
                    user_agent = VALUES(user_agent),
                    ip_address = VALUES(ip_address),
                    is_current = TRUE,
                    is_active = TRUE,
                    last_activity = NOW(),
                    expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY)
            ");
            
            return $stmt->execute([
                $sessionId, $userId, $userAgent, $ipAddress, 
                'Sessão Atual', $device, $browser, $os
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao garantir sessão atual: " . $e->getMessage());
            return false;
        }
    }
    
    private function getDirectUserSessions($userId) {
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
            $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formatar cada sessão
            $formattedSessions = [];
            foreach ($sessions as $session) {
                $formattedSessions[] = [
                    'id' => $session['id'],
                    'device_icon' => $this->getDeviceIcon($session['device_type']),
                    'browser_icon' => $this->getBrowserIcon($session['browser']),
                    'device_name' => $session['device_type'],
                    'browser_name' => $session['browser'],
                    'os_name' => $session['os'],
                    'ip_address' => $session['ip_address'],
                    'location' => $session['location'],
                    'status' => $session['status'],
                    'status_class' => 'status-' . $session['status'],
                    'is_current' => $session['is_current'],
                    'created_at' => $session['created_at'],
                    'last_activity' => $session['last_activity'],
                    'formatted_created' => DateTimeHelper::formatDateTime($session['created_at']),
                    'formatted_activity' => $this->formatTimeAgo($session['last_activity'])
                ];
            }
            
            return $formattedSessions;
            
        } catch (Exception $e) {
            error_log("Erro ao buscar sessões diretamente: " . $e->getMessage());
            return [];
        }
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
            default: return 'fas fa-globe';
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
    
    // =================== MÉTODOS DE GESTÃO DE SESSÕES ===================
    
    public function getSessions() {
        $this->requireAuth();
        
        try {
            // Garantir estrutura correta da tabela user_sessions
            require_once SRC_PATH . '/models/SmartMigrationManager.php';
            $migrationManager = new SmartMigrationManager($this->db);
            $migrationManager->createUserSessionsTable();
            
            // SEMPRE criar/garantir sessão atual do usuário logado
            $this->guaranteeCurrentSession($this->user['id']);
            
            // Buscar sessões do usuário
            $sessions = $this->getDirectUserSessions($this->user['id']);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'sessions' => $sessions
            ]);
        } catch (Exception $e) {
            error_log("Erro ao buscar sessões: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ]);
        }
        exit;
    }
    
    public function revokeSession() {
        $this->requireAuth();
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Método não permitido']);
                exit;
            }
            
            $this->validateCSRF();
            
            $sessionId = $_POST['session_id'] ?? '';
            
            if (empty($sessionId)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID da sessão é obrigatório']);
                exit;
            }
            
            require_once SRC_PATH . '/services/SessionManager.php';
            $sessionManager = new SessionManager($this->db);
            
            $result = $sessionManager->revokeSession($sessionId, $this->user['id']);
            
            if ($result) {
                $this->logUserAction($this->user['id'], 'session_revoked', "Sessão revogada: $sessionId");
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Sessão revogada com sucesso'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao revogar sessão'
                ]);
            }
        } catch (Exception $e) {
            error_log("Erro ao revogar sessão: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ]);
        }
        exit;
    }
    
    public function revokeAllSessions() {
        $this->requireAuth();
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Método não permitido']);
                exit;
            }
            
            $this->validateCSRF();
            
            // Revogar todas as outras sessões diretamente
            $currentSessionId = session_id();
            $stmt = $this->db->prepare("
                UPDATE user_sessions 
                SET is_active = FALSE 
                WHERE user_id = ? AND id != ?
            ");
            
            $result = $stmt->execute([$this->user['id'], $currentSessionId]);
            
            if ($result) {
                $this->logUserAction($this->user['id'], 'all_sessions_revoked', 'Todas as outras sessões foram revogadas');
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Todas as outras sessões foram revogadas'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao revogar sessões'
                ]);
            }
        } catch (Exception $e) {
            error_log("Erro ao revogar todas as sessões: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ]);
        }
        exit;
    }
    
}