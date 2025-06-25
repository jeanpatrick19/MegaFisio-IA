<?php
define('APP_NAME', 'Mega Fisio IA');
define('APP_VERSION', '1.0.0');
// Usar configuração do environment.php para consistência
$environmentConfig = require __DIR__ . '/environment.php';
define('APP_ENV', $environmentConfig['is_production'] ? 'production' : 'development');

define('DEBUG_MODE', !$environmentConfig['is_production']);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

date_default_timezone_set('America/Sao_Paulo');

define('HASH_SALT', bin2hex(random_bytes(32)));
define('SESSION_LIFETIME', 3600);

define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);
define('ALLOWED_UPLOAD_TYPES', ['jpg', 'jpeg', 'png', 'pdf']);

define('RATE_LIMIT_REQUESTS', 100);
define('RATE_LIMIT_WINDOW', 3600);