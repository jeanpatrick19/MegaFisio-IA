<?php
/**
 * Configurações para ambiente de produção
 * Substitua o arquivo /config/config.php por este em produção
 */

define('APP_NAME', 'Mega Fisio IA');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'production');

// IMPORTANTE: Desabilitar debug em produção
define('DEBUG_MODE', false);

// Configurações de erro para produção
error_reporting(0);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', dirname(__DIR__) . '/logs/error.log');

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Segurança - IMPORTANTE: Gerar nova chave em produção
define('HASH_SALT', 'GERAR_NOVA_CHAVE_ALEATORIA_AQUI');
define('SESSION_LIFETIME', 3600); // 1 hora
define('SESSION_SECURE', true); // Cookies apenas HTTPS
define('SESSION_HTTPONLY', true); // Prevenir acesso JS

// Upload
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_UPLOAD_TYPES', ['jpg', 'jpeg', 'png', 'pdf']);
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads');

// Rate limiting
define('RATE_LIMIT_REQUESTS', 100);
define('RATE_LIMIT_WINDOW', 3600);

// API de IA (configurar conforme seu provedor)
define('AI_API_ENDPOINT', '');
define('AI_API_KEY', '');
define('AI_MODEL', 'gpt-3.5-turbo');
define('AI_MAX_TOKENS', 2000);
define('AI_TEMPERATURE', 0.7);

// Email (configurar SMTP)
define('MAIL_DRIVER', 'smtp');
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_ADDRESS', 'noreply@megafisio.com');
define('MAIL_FROM_NAME', APP_NAME);

// URLs do sistema
define('APP_URL', 'https://seu-dominio.com');
define('ASSET_URL', APP_URL . '/assets');

// Cache
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1 hora

// Backup
define('BACKUP_ENABLED', true);
define('BACKUP_PATH', dirname(__DIR__) . '/backups');
define('BACKUP_RETENTION_DAYS', 30);

// Logs
define('LOG_LEVEL', 'error'); // emergency, alert, critical, error, warning, notice, info, debug
define('LOG_PATH', dirname(__DIR__) . '/logs');
define('LOG_RETENTION_DAYS', 90);

// Monitoramento
define('MONITORING_ENABLED', true);
define('ALERT_EMAIL', 'admin@megafisio.com');

// Manutenção
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'Sistema em manutenção. Voltaremos em breve.');
define('MAINTENANCE_ALLOWED_IPS', []); // IPs permitidos durante manutenção

// Recursos
define('ENABLE_REGISTRATION', true);
define('REQUIRE_EMAIL_VERIFICATION', true);
define('ENABLE_PASSWORD_RESET', true);
define('ENABLE_TWO_FACTOR_AUTH', false);

// Limites
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutos
define('PASSWORD_MIN_LENGTH', 8);
define('SESSION_TIMEOUT', 3600); // 1 hora

// LGPD
define('PRIVACY_POLICY_VERSION', '1.0');
define('TERMS_VERSION', '1.0');
define('DATA_RETENTION_DAYS', 365);
define('ENABLE_DATA_EXPORT', true);
define('ENABLE_DATA_DELETION', true);