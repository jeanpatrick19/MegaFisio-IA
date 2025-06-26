-- Migration 005: Sistema de Permissões
-- Criação das tabelas para controle granular de permissões

-- Tabela de módulos do sistema
CREATE TABLE IF NOT EXISTS permission_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    display_name VARCHAR(200) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de permissões específicas
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    display_name VARCHAR(200) NOT NULL,
    description TEXT,
    permission_type ENUM('view', 'edit', 'create', 'delete', 'execute', 'manage') NOT NULL,
    is_critical BOOLEAN DEFAULT FALSE COMMENT 'Indica se é uma permissão crítica do sistema',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES permission_modules(id) ON DELETE CASCADE,
    UNIQUE KEY unique_permission (module_id, name, permission_type)
);

-- Tabela de associação usuário-permissão
CREATE TABLE IF NOT EXISTS user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    permission_id INT NOT NULL,
    granted_by INT NOT NULL COMMENT 'ID do usuário que concedeu a permissão',
    granted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL COMMENT 'NULL para permissões permanentes',
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    FOREIGN KEY (granted_by) REFERENCES users(id) ON DELETE RESTRICT,
    UNIQUE KEY unique_user_permission (user_id, permission_id)
);

-- Inserir módulos do sistema
INSERT INTO permission_modules (name, display_name, description, icon, sort_order) VALUES
('auth', 'Autenticação e Segurança', 'Controle de login, 2FA e segurança', 'fas fa-shield-alt', 1),
('dashboard', 'Dashboard', 'Visualização de painéis e relatórios', 'fas fa-tachometer-alt', 2),
('users', 'Gestão de Usuários', 'Gerenciamento de usuários do sistema', 'fas fa-users', 3),
('profile', 'Perfil do Usuário', 'Gerenciamento do próprio perfil', 'fas fa-user-circle', 4),
('ai', 'Inteligência Artificial', 'Funcionalidades de IA para fisioterapia', 'fas fa-brain', 5),
('settings', 'Configurações', 'Configurações gerais do sistema', 'fas fa-cogs', 6),
('logs', 'Logs e Auditoria', 'Visualização e gestão de logs', 'fas fa-list-alt', 7),
('activities', 'Atividades', 'Gestão de atividades e relatórios', 'fas fa-chart-line', 8),
('permissions', 'Permissões', 'Gestão do sistema de permissões', 'fas fa-key', 9),
('maintenance', 'Manutenção', 'Limpeza e manutenção do sistema', 'fas fa-tools', 10);

-- Inserir permissões por módulo

-- MÓDULO: Autenticação e Segurança
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'auth'), 'login', 'Fazer Login', 'Permitir login no sistema', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'auth'), '2fa_view', 'Ver 2FA', 'Visualizar configurações de 2FA', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'auth'), '2fa_manage', 'Gerenciar 2FA', 'Configurar autenticação de dois fatores', 'manage', FALSE),
((SELECT id FROM permission_modules WHERE name = 'auth'), 'password_reset', 'Recuperar Senha', 'Solicitar recuperação de senha', 'execute', FALSE);

-- MÓDULO: Dashboard
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'dashboard'), 'admin_view', 'Dashboard Admin', 'Visualizar dashboard administrativo', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'dashboard'), 'professional_view', 'Dashboard Profissional', 'Visualizar dashboard profissional', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'dashboard'), 'patient_view', 'Dashboard Paciente', 'Visualizar dashboard do paciente', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'dashboard'), 'stats_view', 'Ver Estatísticas', 'Visualizar estatísticas do sistema', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'dashboard'), 'reports_view', 'Ver Relatórios', 'Visualizar relatórios de uso', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'dashboard'), 'reports_generate', 'Gerar Relatórios', 'Gerar novos relatórios', 'create', FALSE);

-- MÓDULO: Gestão de Usuários
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'users'), 'view', 'Listar Usuários', 'Visualizar lista de usuários', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'users'), 'create', 'Criar Usuário', 'Criar novos usuários', 'create', TRUE),
((SELECT id FROM permission_modules WHERE name = 'users'), 'edit', 'Editar Usuário', 'Editar dados de usuários', 'edit', TRUE),
((SELECT id FROM permission_modules WHERE name = 'users'), 'delete', 'Excluir Usuário', 'Excluir usuários do sistema', 'delete', TRUE),
((SELECT id FROM permission_modules WHERE name = 'users'), 'password_change', 'Alterar Senha', 'Alterar senha de outros usuários', 'edit', TRUE),
((SELECT id FROM permission_modules WHERE name = 'users'), 'unlock', 'Desbloquear Usuário', 'Desbloquear usuários bloqueados', 'edit', FALSE),
((SELECT id FROM permission_modules WHERE name = 'users'), 'stats_view', 'Ver Estatísticas', 'Visualizar estatísticas de usuários', 'view', FALSE);

-- MÓDULO: Perfil do Usuário
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'profile'), 'view', 'Ver Perfil', 'Visualizar próprio perfil', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'personal_edit', 'Editar Dados Pessoais', 'Editar informações pessoais', 'edit', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'professional_edit', 'Editar Dados Profissionais', 'Editar informações profissionais', 'edit', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'password_change', 'Alterar Própria Senha', 'Alterar própria senha', 'edit', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), '2fa_manage', 'Gerenciar 2FA', 'Configurar 2FA no perfil', 'manage', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'preferences_edit', 'Editar Preferências', 'Alterar preferências do sistema', 'edit', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'avatar_edit', 'Alterar Avatar', 'Upload e alteração de avatar', 'edit', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'sessions_manage', 'Gerenciar Sessões', 'Gerenciar sessões ativas', 'manage', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'privacy_manage', 'Gerenciar Privacidade', 'Configurações de privacidade LGPD', 'manage', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'export_data', 'Exportar Dados', 'Exportar dados pessoais', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'profile'), 'request_deletion', 'Solicitar Exclusão', 'Solicitar exclusão de conta', 'execute', FALSE);

-- MÓDULO: Inteligência Artificial
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'ai'), 'interface_view', 'Interface IA', 'Visualizar interface da IA', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'interface_use', 'Usar IA Básica', 'Utilizar funcionalidades básicas da IA', 'execute', FALSE),

-- Dr. IA Marketing e Vendas
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_autoritas', 'Dr. Autoritas', 'Conteúdo para Instagram', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_acolhe', 'Dr. Acolhe', 'Atendimento via WhatsApp/Direct', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_fechador', 'Dr. Fechador', 'Vendas de Planos Fisioterapêuticos', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_local', 'Dr. Local', 'Autoridade de Bairro', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_recall', 'Dr. Recall', 'Fidelização e Retorno de Pacientes', 'execute', FALSE),

-- Dr. IA Clínico e Terapêutico
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_reab', 'Dr. Reab', 'Prescrição de Exercícios Personalizados', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dra_protoc', 'Dra. Protoc', 'Protocolos Terapêuticos Estruturados', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_injetaveis', 'Dr. Injetáveis', 'Protocolos Terapêuticos com Injetáveis', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_evolucio', 'Dr. Evolucio', 'Acompanhamento Clínico do Paciente', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dra_contrology', 'Dra. Contrology', 'Especialista em prescrição de Pilates clássico terapêutico', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_posturalis', 'Dr. Posturalis', 'Especialista em RPG de Souchard e análise postural', 'execute', FALSE),

-- Dr. IA Educativo e Científico
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dra_edu', 'Dra. Edu', 'Materiais Educativos para Pacientes', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_cientifico', 'Dr. Científico', 'Resumos de Artigos e Evidências', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_pop', 'Dr. POP', 'Protocolos Operacionais Padrão', 'execute', FALSE),

-- Dr. IA Diagnóstico e Análise
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_imaginario', 'Dr. Imaginário', 'Análise de Exames de Imagem (RX, USG, RNM)', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_diagnostik', 'Dr. Diagnostik', 'Mapeamento de Marcadores para Fisioterapia', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_integralis', 'Dr. Integralis', 'Análise Funcional de Exames Laboratoriais', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_peritus', 'Dr. Peritus', 'Mestre das Perícias', 'execute', FALSE),

-- Dr. IA Jurídico e Regulatório
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dra_legal', 'Dra. Legal', 'Termos de Consentimento Personalizados', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_contratus', 'Dr. Contratus', 'Contratos de Prestação de Serviço', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_imago', 'Dr. Imago', 'Autorização de Uso de Imagem', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_vigilantis', 'Dr. Vigilantis', 'Documentação e Exigências da Vigilância Sanitária', 'execute', FALSE),

-- Dr. IA Farmacológico
((SELECT id FROM permission_modules WHERE name = 'ai'), 'dr_formula_oral', 'Dr. Fórmula Oral', 'Propostas Farmacológicas Via Oral para Dor', 'execute', FALSE),

-- Gestão de IA
((SELECT id FROM permission_modules WHERE name = 'ai'), 'prompts_manage', 'Gerenciar Prompts', 'Gerenciar prompts da IA', 'manage', TRUE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'history_view', 'Ver Histórico', 'Visualizar histórico de análises', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'ai'), 'history_manage', 'Gerenciar Histórico', 'Gerenciar histórico de análises', 'manage', FALSE);

-- MÓDULO: Configurações
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'settings'), 'general_view', 'Ver Configurações', 'Visualizar configurações gerais', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'general_edit', 'Editar Configurações', 'Editar configurações gerais', 'edit', TRUE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'email_view', 'Ver Config. Email', 'Visualizar configurações de email', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'email_edit', 'Editar Config. Email', 'Editar configurações de email', 'edit', TRUE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'branding_view', 'Ver Identidade', 'Visualizar logo e identidade', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'branding_edit', 'Editar Identidade', 'Editar logo e identidade', 'edit', FALSE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'security_view', 'Ver Segurança', 'Visualizar configurações de segurança', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'security_edit', 'Editar Segurança', 'Editar configurações de segurança', 'edit', TRUE),
((SELECT id FROM permission_modules WHERE name = 'settings'), 'maintenance_manage', 'Gerenciar Manutenção', 'Backup e manutenção do sistema', 'manage', TRUE);

-- MÓDULO: Logs e Auditoria
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'logs'), 'system_view', 'Ver Logs Sistema', 'Visualizar logs do sistema', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'logs'), 'users_view', 'Ver Logs Usuários', 'Visualizar logs de usuários', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'logs'), 'activity_view', 'Ver Logs Atividade', 'Visualizar logs de atividade', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'logs'), 'stats_view', 'Ver Estatísticas', 'Visualizar estatísticas de logs', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'logs'), 'cleanup_execute', 'Limpeza de Logs', 'Executar limpeza de logs', 'execute', TRUE);

-- MÓDULO: Atividades
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'activities'), 'user_view', 'Ver Atividades', 'Visualizar atividades do usuário', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'activities'), 'reports_view', 'Ver Relatórios', 'Visualizar relatórios de atividade', 'view', FALSE),
((SELECT id FROM permission_modules WHERE name = 'activities'), 'reports_generate', 'Gerar Relatórios', 'Gerar relatórios de atividade', 'create', FALSE),
((SELECT id FROM permission_modules WHERE name = 'activities'), 'export_data', 'Exportar Dados', 'Exportar dados de atividades', 'execute', FALSE),
((SELECT id FROM permission_modules WHERE name = 'activities'), 'deletion_manage', 'Gerenciar Exclusões', 'Gerenciar exclusão de dados', 'manage', TRUE);

-- MÓDULO: Permissões
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'permissions'), 'view', 'Ver Permissões', 'Visualizar sistema de permissões', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'permissions'), 'manage', 'Gerenciar Permissões', 'Gerenciar permissões do sistema', 'manage', TRUE),
((SELECT id FROM permission_modules WHERE name = 'permissions'), 'users_assign', 'Atribuir a Usuários', 'Atribuir permissões a usuários', 'edit', TRUE),
((SELECT id FROM permission_modules WHERE name = 'permissions'), 'modules_manage', 'Gerenciar Módulos', 'Gerenciar módulos de permissão', 'manage', TRUE);

-- MÓDULO: Manutenção
INSERT INTO permissions (module_id, name, display_name, description, permission_type, is_critical) VALUES
((SELECT id FROM permission_modules WHERE name = 'maintenance'), 'cleanup_view', 'Ver Limpeza', 'Visualizar opções de limpeza', 'view', TRUE),
((SELECT id FROM permission_modules WHERE name = 'maintenance'), 'cleanup_execute', 'Executar Limpeza', 'Executar limpeza de dados', 'execute', TRUE),
((SELECT id FROM permission_modules WHERE name = 'maintenance'), 'system_manage', 'Manutenção Sistema', 'Executar manutenção do sistema', 'manage', TRUE);

-- Criar índices para performance
CREATE INDEX idx_permissions_module_type ON permissions(module_id, permission_type);
CREATE INDEX idx_user_permissions_user ON user_permissions(user_id, is_active);
CREATE INDEX idx_user_permissions_expires ON user_permissions(expires_at);

-- Comentários para documentação
ALTER TABLE permission_modules COMMENT = 'Módulos do sistema para organização de permissões';
ALTER TABLE permissions COMMENT = 'Permissões específicas do sistema organizadas por módulo';
ALTER TABLE user_permissions COMMENT = 'Associação entre usuários e suas permissões concedidas';