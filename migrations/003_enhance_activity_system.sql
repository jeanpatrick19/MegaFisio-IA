-- Migration: 003_enhance_activity_system
-- Date: 2025-06-26
-- Description: Melhorar sistema de atividades com mais tipos e dados para exportação

-- Expandir tipos de ação na tabela user_logs
ALTER TABLE user_logs 
MODIFY COLUMN acao VARCHAR(100) NOT NULL COMMENT 'Ação realizada',
ADD COLUMN categoria VARCHAR(50) NULL COMMENT 'Categoria da ação (login, profile, security, etc)',
ADD COLUMN metadados JSON NULL COMMENT 'Dados adicionais em formato JSON',
ADD COLUMN sessao_id VARCHAR(128) NULL COMMENT 'ID da sessão quando aplicável';

-- Criar tabela para dados de usuário (para exportação)
CREATE TABLE IF NOT EXISTS user_data_exports (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipo_export VARCHAR(50) NOT NULL COMMENT 'Tipo de exportação (full, logs, profile)',
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    arquivo_path VARCHAR(500) NULL COMMENT 'Caminho do arquivo gerado',
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_processamento TIMESTAMP NULL,
    data_expiracao TIMESTAMP NULL COMMENT 'Quando o arquivo expira',
    tamanho_arquivo BIGINT NULL COMMENT 'Tamanho do arquivo em bytes',
    hash_arquivo VARCHAR(64) NULL COMMENT 'Hash SHA256 do arquivo',
    ip_solicitante VARCHAR(45) NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_data_expiracao (data_expiracao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Criar tabela para solicitações de exclusão de conta
CREATE TABLE IF NOT EXISTS account_deletion_requests (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    motivo TEXT NULL COMMENT 'Motivo da exclusão',
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_processamento TIMESTAMP NULL,
    processado_por INT NULL COMMENT 'ID do admin que processou',
    observacoes_admin TEXT NULL,
    codigo_confirmacao VARCHAR(32) NOT NULL COMMENT 'Código para confirmar exclusão',
    ip_solicitante VARCHAR(45) NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (processado_por) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_codigo_confirmacao (codigo_confirmacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir configurações adicionais
INSERT INTO settings (`key`, `value`, `type`, description, is_public) VALUES
('export_file_retention_days', '7', 'integer', 'Dias para manter arquivos de exportação', FALSE),
('max_export_requests_per_day', '3', 'integer', 'Máximo de solicitações de exportação por dia', FALSE),
('account_deletion_confirmation_hours', '48', 'integer', 'Horas para confirmar exclusão de conta', FALSE),
('activity_log_retention_days', '365', 'integer', 'Dias para manter logs de atividade', FALSE)
ON DUPLICATE KEY UPDATE `key` = `key`;

