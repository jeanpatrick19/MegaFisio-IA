-- Migration: Criar tabela de permissões de usuários
-- Data: 2024-12-26
-- Descrição: Sistema de permissões granulares para usuários fisioterapeutas

CREATE TABLE IF NOT EXISTS user_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    permission_key VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices para performance
    INDEX idx_user_permissions_user_id (user_id),
    INDEX idx_user_permissions_permission (permission_key),
    UNIQUE KEY unique_user_permission (user_id, permission_key),
    
    -- Chave estrangeira
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir permissões padrão para usuários existentes do tipo 'usuario'
INSERT IGNORE INTO user_permissions (user_id, permission_key)
SELECT 
    id,
    'ai_basic_access'
FROM users 
WHERE role = 'usuario' 
AND deleted_at IS NULL;

INSERT IGNORE INTO user_permissions (user_id, permission_key)
SELECT 
    id,
    'patients_view'
FROM users 
WHERE role = 'usuario' 
AND deleted_at IS NULL;

INSERT IGNORE INTO user_permissions (user_id, permission_key)
SELECT 
    id,
    'reports_view'
FROM users 
WHERE role = 'usuario' 
AND deleted_at IS NULL;

-- Comentários sobre as permissões disponíveis:
/*
MÓDULO ROBÔS DE IA:
- ai_basic_access: Acesso básico aos robôs de IA
- ai_advanced_features: Recursos avançados de IA
- ai_export_reports: Exportar relatórios de IA

MÓDULO PACIENTES:
- patients_view: Visualizar pacientes
- patients_create: Criar novos pacientes
- patients_edit: Editar pacientes existentes

MÓDULO RELATÓRIOS:
- reports_view: Visualizar relatórios básicos
- reports_advanced: Relatórios avançados e estatísticas

MÓDULO SISTEMA:
- system_preferences: Configurar preferências pessoais
- backup_access: Realizar backup de dados próprios
*/