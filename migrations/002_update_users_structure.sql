-- Migration: 002_update_users_structure
-- Date: 2025-06-25
-- Description: Atualizar estrutura da tabela users conforme especificações

-- Atualizar colunas existentes da tabela users
ALTER TABLE users 
MODIFY COLUMN name VARCHAR(255) NOT NULL COMMENT 'Nome completo do usuário',
MODIFY COLUMN role ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario' COMMENT 'Perfil do usuário';

-- Verificar e adicionar colunas apenas se não existirem
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'first_login';
SET @sql = IF(@col_exists = 0, 'ALTER TABLE users ADD COLUMN first_login BOOLEAN DEFAULT TRUE COMMENT \'Indica se é o primeiro login\'', 'SELECT "Coluna first_login já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'last_login';
SET @sql = IF(@col_exists = 0, 'ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL COMMENT \'Último login realizado\'', 'SELECT "Coluna last_login já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'password_reset_token';
SET @sql = IF(@col_exists = 0, 'ALTER TABLE users ADD COLUMN password_reset_token VARCHAR(255) NULL COMMENT \'Token para reset de senha\'', 'SELECT "Coluna password_reset_token já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'password_reset_expires';
SET @sql = IF(@col_exists = 0, 'ALTER TABLE users ADD COLUMN password_reset_expires TIMESTAMP NULL COMMENT \'Expiração do token de reset\'', 'SELECT "Coluna password_reset_expires já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'login_attempts';
SET @sql = IF(@col_exists = 0, 'ALTER TABLE users ADD COLUMN login_attempts INT DEFAULT 0 COMMENT \'Tentativas de login falhadas\'', 'SELECT "Coluna login_attempts já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND COLUMN_NAME = 'locked_until';
SET @sql = IF(@col_exists = 0, 'ALTER TABLE users ADD COLUMN locked_until TIMESTAMP NULL COMMENT \'Bloqueio temporário até\'', 'SELECT "Coluna locked_until já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verificar e criar índices apenas se não existirem
SET @index_exists = 0;
SELECT COUNT(*) INTO @index_exists FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND INDEX_NAME = 'idx_email_status';
SET @sql = IF(@index_exists = 0, 'ALTER TABLE users ADD INDEX idx_email_status (email, status)', 'SELECT "Índice idx_email_status já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @index_exists = 0;
SELECT COUNT(*) INTO @index_exists FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND INDEX_NAME = 'idx_password_reset_token';
SET @sql = IF(@index_exists = 0, 'ALTER TABLE users ADD INDEX idx_password_reset_token (password_reset_token)', 'SELECT "Índice idx_password_reset_token já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @index_exists = 0;
SELECT COUNT(*) INTO @index_exists FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = 'megafisio_ia' AND TABLE_NAME = 'users' AND INDEX_NAME = 'idx_locked_until';
SET @sql = IF(@index_exists = 0, 'ALTER TABLE users ADD INDEX idx_locked_until (locked_until)', 'SELECT "Índice idx_locked_until já existe"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Tabela de logs de usuário (user_logs)
CREATE TABLE IF NOT EXISTS user_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    email VARCHAR(255) NULL COMMENT 'Email usado na tentativa (mesmo se usuário não existir)',
    acao VARCHAR(50) NOT NULL COMMENT 'Ação realizada',
    detalhes TEXT NULL COMMENT 'Detalhes adicionais da ação',
    ip_address VARCHAR(45) NULL COMMENT 'Endereço IP',
    user_agent TEXT NULL COMMENT 'User agent do navegador',
    sucesso BOOLEAN DEFAULT TRUE COMMENT 'Se a ação foi bem-sucedida',
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_acao (acao),
    INDEX idx_data_hora (data_hora),
    INDEX idx_ip_address (ip_address),
    INDEX idx_email_acao (email, acao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de sessões ativas
CREATE TABLE IF NOT EXISTS user_sessions_active (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_expires_at (expires_at),
    INDEX idx_last_activity (last_activity),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Configurações de sistema
INSERT INTO settings (`key`, `value`, `type`, description, is_public) VALUES
('max_login_attempts', '5', 'integer', 'Máximo de tentativas de login antes do bloqueio', FALSE),
('lockout_duration', '900', 'integer', 'Duração do bloqueio em segundos (15 minutos)', FALSE),
('session_lifetime', '3600', 'integer', 'Duração da sessão em segundos (1 hora)', FALSE),
('password_min_length', '8', 'integer', 'Tamanho mínimo da senha', FALSE),
('require_password_change', 'true', 'boolean', 'Forçar troca de senha no primeiro login', FALSE),
('password_reset_token_lifetime', '3600', 'integer', 'Validade do token de reset em segundos (1 hora)', FALSE)
ON DUPLICATE KEY UPDATE `key` = `key`;

-- Criar usuário admin padrão se não existir
INSERT INTO users (email, password, name, role, status, first_login) 
VALUES (
    'admin@megafisio.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- senha: admin123
    'Administrador do Sistema',
    'admin',
    'active',
    TRUE
) ON DUPLICATE KEY UPDATE email = email;