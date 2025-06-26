-- Migration: 004_complete_user_management
-- Date: 2025-06-26
-- Description: Sistema completo de gestão de usuários com permissões, LGPD e auditoria

-- =============================================
-- SISTEMA DE PERMISSÕES
-- =============================================

-- Tabela de roles (papéis) do sistema
CREATE TABLE IF NOT EXISTS user_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    is_system BOOLEAN DEFAULT FALSE COMMENT 'Role do sistema não pode ser alterada',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de permissões disponíveis
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    display_name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    module VARCHAR(50) NOT NULL COMMENT 'Módulo do sistema (users, dashboard, ai, etc)',
    action VARCHAR(50) NOT NULL COMMENT 'Ação (create, read, update, delete, manage)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de permissões por role
CREATE TABLE IF NOT EXISTS role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    granted_by INT NULL COMMENT 'ID do usuário que concedeu a permissão',
    granted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES user_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    FOREIGN KEY (granted_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_role_permission (role_id, permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de permissões específicas por usuário (sobrescreve role)
CREATE TABLE IF NOT EXISTS user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    permission_id INT NOT NULL,
    granted BOOLEAN DEFAULT TRUE COMMENT 'TRUE = concedida, FALSE = revogada',
    granted_by INT NULL,
    granted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL COMMENT 'Permissão temporária',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    FOREIGN KEY (granted_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_permission (user_id, permission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- AUDITORIA E LOGS AVANÇADOS
-- =============================================

-- Tabela de logs detalhados de usuários
CREATE TABLE IF NOT EXISTS user_access_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    email VARCHAR(255) NULL COMMENT 'Email mesmo se usuário for deletado',
    action_type ENUM('login', 'logout', 'access', 'permission_change', 'data_access', 'failed_login') NOT NULL,
    action_details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    session_id VARCHAR(128) NULL,
    location_info JSON NULL COMMENT 'Geolocalização aproximada',
    device_info JSON NULL COMMENT 'Informações do dispositivo',
    risk_score TINYINT DEFAULT 0 COMMENT '0-100, score de risco da ação',
    success BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action_type (action_type),
    INDEX idx_created_at (created_at),
    INDEX idx_ip_address (ip_address),
    INDEX idx_risk_score (risk_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de auditoria de mudanças em usuários
CREATE TABLE IF NOT EXISTS user_audit_trail (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Usuário que foi modificado',
    changed_by INT NULL COMMENT 'Usuário que fez a mudança',
    action ENUM('created', 'updated', 'deleted', 'activated', 'deactivated', 'password_changed', 'role_changed') NOT NULL,
    field_name VARCHAR(100) NULL COMMENT 'Campo alterado',
    old_value TEXT NULL COMMENT 'Valor anterior',
    new_value TEXT NULL COMMENT 'Novo valor',
    change_reason VARCHAR(500) NULL COMMENT 'Motivo da mudança',
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_changed_by (changed_by),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- CONFORMIDADE LGPD
-- =============================================

-- Tabela de consentimentos LGPD
CREATE TABLE IF NOT EXISTS user_lgpd_consents (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    consent_type ENUM('data_processing', 'marketing', 'analytics', 'cookies', 'third_party_sharing') NOT NULL,
    consent_version VARCHAR(10) NOT NULL COMMENT 'Versão dos termos',
    consent_given BOOLEAN NOT NULL,
    consent_text TEXT NOT NULL COMMENT 'Texto do consentimento apresentado',
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    consent_method ENUM('registration', 'explicit_form', 'settings_page', 'popup') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    revoked_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_consent_type (consent_type),
    INDEX idx_consent_given (consent_given)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de solicitações de dados pessoais
CREATE TABLE IF NOT EXISTS user_data_requests (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    request_type ENUM('access', 'portability', 'correction', 'deletion', 'processing_restriction') NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'rejected') DEFAULT 'pending',
    request_details TEXT NULL,
    requested_data JSON NULL COMMENT 'Dados específicos solicitados',
    response_data JSON NULL COMMENT 'Dados fornecidos na resposta',
    processed_by INT NULL COMMENT 'Admin que processou',
    rejection_reason TEXT NULL,
    due_date DATE NOT NULL COMMENT 'Prazo legal para resposta',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_request_type (request_type),
    INDEX idx_status (status),
    INDEX idx_due_date (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- EXTENSÃO DA TABELA USERS
-- =============================================

-- Adicionar colunas de controle à tabela users
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS last_password_change TIMESTAMP NULL COMMENT 'Última mudança de senha',
ADD COLUMN IF NOT EXISTS password_expires_at TIMESTAMP NULL COMMENT 'Expiração da senha',
ADD COLUMN IF NOT EXISTS failed_login_attempts INT DEFAULT 0 COMMENT 'Tentativas de login falhadas',
ADD COLUMN IF NOT EXISTS locked_until TIMESTAMP NULL COMMENT 'Bloqueio temporário até',
ADD COLUMN IF NOT EXISTS must_change_password BOOLEAN DEFAULT FALSE COMMENT 'Forçar mudança de senha',
ADD COLUMN IF NOT EXISTS email_verified_at TIMESTAMP NULL COMMENT 'Email verificado em',
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) NULL COMMENT 'Telefone do usuário',
ADD COLUMN IF NOT EXISTS department VARCHAR(100) NULL COMMENT 'Departamento/Setor',
ADD COLUMN IF NOT EXISTS position VARCHAR(100) NULL COMMENT 'Cargo/Função',
ADD COLUMN IF NOT EXISTS manager_id INT NULL COMMENT 'ID do supervisor',
ADD COLUMN IF NOT EXISTS notes TEXT NULL COMMENT 'Observações administrativas',
ADD COLUMN IF NOT EXISTS last_login_ip VARCHAR(45) NULL COMMENT 'IP do último login',
ADD COLUMN IF NOT EXISTS timezone VARCHAR(50) DEFAULT 'America/Sao_Paulo' COMMENT 'Fuso horário do usuário',
ADD FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL;

-- =============================================
-- DADOS INICIAIS
-- =============================================

-- Inserir roles padrão do sistema
INSERT IGNORE INTO user_roles (name, display_name, description, is_system) VALUES
('super_admin', 'Super Administrador', 'Acesso total ao sistema, não pode ser limitado', TRUE),
('admin', 'Administrador', 'Administrador geral do sistema', TRUE),
('manager', 'Gerente', 'Gerenciamento de usuários e relatórios', FALSE),
('professional', 'Profissional', 'Fisioterapeuta/Profissional de saúde', TRUE),
('assistant', 'Assistente', 'Assistente/Auxiliar', FALSE),
('viewer', 'Visualizador', 'Apenas visualização de dados', FALSE);

-- Inserir permissões básicas do sistema
INSERT IGNORE INTO permissions (name, display_name, description, module, action) VALUES
-- Gestão de usuários
('users.view', 'Visualizar Usuários', 'Ver lista e detalhes de usuários', 'users', 'read'),
('users.create', 'Criar Usuários', 'Cadastrar novos usuários', 'users', 'create'),
('users.edit', 'Editar Usuários', 'Modificar dados de usuários', 'users', 'update'),
('users.delete', 'Excluir Usuários', 'Remover usuários do sistema', 'users', 'delete'),
('users.manage_permissions', 'Gerenciar Permissões', 'Alterar permissões de usuários', 'users', 'manage'),
('users.view_logs', 'Ver Logs de Usuários', 'Acessar logs de atividades', 'users', 'read'),

-- Dashboard e relatórios
('dashboard.admin', 'Dashboard Admin', 'Acessar dashboard administrativo', 'dashboard', 'read'),
('dashboard.reports', 'Relatórios', 'Gerar e visualizar relatórios', 'dashboard', 'read'),
('dashboard.analytics', 'Analytics', 'Ver métricas e análises', 'dashboard', 'read'),

-- Sistema de IA
('ai.use', 'Usar IA', 'Utilizar sistema de inteligência artificial', 'ai', 'read'),
('ai.manage', 'Gerenciar IA', 'Configurar e administrar IA', 'ai', 'manage'),
('ai.view_history', 'Histórico IA', 'Ver histórico de uso da IA', 'ai', 'read'),

-- Configurações
('settings.view', 'Ver Configurações', 'Visualizar configurações do sistema', 'settings', 'read'),
('settings.edit', 'Editar Configurações', 'Modificar configurações do sistema', 'settings', 'update'),

-- LGPD e Privacidade
('lgpd.view', 'Ver LGPD', 'Acessar dados de conformidade LGPD', 'lgpd', 'read'),
('lgpd.manage', 'Gerenciar LGPD', 'Processar solicitações LGPD', 'lgpd', 'manage'),

-- Perfil próprio
('profile.view', 'Ver Próprio Perfil', 'Visualizar próprio perfil', 'profile', 'read'),
('profile.edit', 'Editar Próprio Perfil', 'Modificar próprio perfil', 'profile', 'update');

-- Atribuir permissões aos roles padrão
-- Super Admin (todas as permissões)
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM user_roles r, permissions p WHERE r.name = 'super_admin';

-- Admin (quase todas, exceto algumas sensíveis)
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM user_roles r, permissions p 
WHERE r.name = 'admin' AND p.name NOT LIKE '%super%';

-- Professional (básicas + IA)
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM user_roles r, permissions p 
WHERE r.name = 'professional' AND p.name IN (
    'profile.view', 'profile.edit', 'ai.use', 'ai.view_history', 'dashboard.reports'
);

-- Atualizar usuários existentes para usar o novo sistema de roles
UPDATE users SET role = 'admin' WHERE role = 'admin';
UPDATE users SET role = 'professional' WHERE role = 'professional';

-- Registrar migração
INSERT IGNORE INTO migrations (version, executed_at) VALUES ('004_complete_user_management', NOW());