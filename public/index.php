<?php
// Servir arquivos estáticos quando usando servidor PHP dev
if (php_sapi_name() === 'cli-server') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $uri;
    
    if (is_file($file)) {
        // Definir content-type correto
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon'
        ];
        
        if (isset($mimeTypes[$ext])) {
            header('Content-Type: ' . $mimeTypes[$ext]);
        }
        
        return false; // Servir o arquivo
    }
}

if (!defined('PUBLIC_ACCESS')) {
    define('PUBLIC_ACCESS', true);
}

// Carregar configuração de ambiente
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('SRC_PATH', ROOT_PATH . '/src');
define('PUBLIC_PATH', __DIR__);

// Carregar configurações de ambiente
$env = require CONFIG_PATH . '/environment.php';

// Definir constantes baseadas no ambiente
define('IS_PRODUCTION', $env['is_production']);
define('BASE_URL', $env['base_url']);
define('BASE_PATH', $env['base_path']);

// Configurar PHP baseado no ambiente
if ($env['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Configurar sessão ANTES de iniciar
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', $env['session_httponly'] ? 1 : 0);
    if ($env['session_secure']) {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}

// Verificar se o sistema foi instalado
if (!file_exists(CONFIG_PATH . '/installed.lock')) {
    header('Location: ../install.php');
    exit;
}

// Verificar se os arquivos existem antes de incluir
if (!file_exists(CONFIG_PATH . '/config.php')) {
    die('Erro: Arquivo de configuração não encontrado.');
}

require_once CONFIG_PATH . '/config.php';
require_once CONFIG_PATH . '/database.php';
require_once SRC_PATH . '/models/DatabaseInitializer.php';
require_once SRC_PATH . '/controllers/Router.php';

// Inicializar banco de dados e executar migrations automaticamente
$dbInit = new DatabaseInitializer();

$router = new Router();
$router->handleRequest();