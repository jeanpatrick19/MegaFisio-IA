<?php
/**
 * Auto-instalação do Mega Fisio IA
 * Este arquivo se auto-deleta após execução bem-sucedida
 */

define('SETUP_MODE', true);
define('ROOT_PATH', dirname(__DIR__));

class AutoInstaller {
    private $errors = [];
    private $success = [];
    private $dbConfig = null;
    
    public function run() {
        header('Content-Type: text/html; charset=UTF-8');
        
        // Verificar se já foi instalado
        if ($this->isInstalled()) {
            $this->selfDestruct();
            die('Sistema já instalado. Arquivo de instalação removido por segurança.');
        }
        
        echo $this->getHeader();
        
        // Executar instalação
        $this->checkRequirements();
        $this->detectDatabaseConfig();
        
        if (empty($this->errors)) {
            $this->createDatabase();
            $this->runMigrations();
            $this->createAdminUser();
            $this->finalizeInstallation();
        }
        
        echo $this->getFooter();
    }
    
    private function isInstalled() {
        return file_exists(ROOT_PATH . '/.installed');
    }
    
    private function checkRequirements() {
        // PHP Version
        if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
            $this->success[] = "PHP " . PHP_VERSION . " ✓";
        } else {
            $this->errors[] = "PHP 8.0+ necessário (atual: " . PHP_VERSION . ")";
        }
        
        // Extensões
        $required = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
        foreach ($required as $ext) {
            if (extension_loaded($ext)) {
                $this->success[] = "Extensão $ext ✓";
            } else {
                $this->errors[] = "Extensão $ext ausente";
            }
        }
        
        // Diretórios com permissão de escrita
        $dirs = ['/', '/migrations', '/config'];
        foreach ($dirs as $dir) {
            if (is_writable(ROOT_PATH . $dir)) {
                $this->success[] = "Permissão escrita em $dir ✓";
            } else {
                $this->errors[] = "Sem permissão escrita em $dir";
            }
        }
    }
    
    private function detectDatabaseConfig() {
        $configs = [
            ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
            ['host' => 'localhost', 'user' => 'root', 'pass' => '123'],
            ['host' => 'localhost', 'user' => 'megafisio_user', 'pass' => 'megafisio123'],
        ];
        
        foreach ($configs as $config) {
            try {
                $pdo = new PDO(
                    "mysql:host={$config['host']};charset=utf8mb4",
                    $config['user'],
                    $config['pass'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                $this->dbConfig = $config;
                $this->success[] = "Conexão MySQL estabelecida ✓";
                return;
                
            } catch (PDOException $e) {
                continue;
            }
        }
        
        if (!$this->dbConfig) {
            $this->errors[] = "Não foi possível conectar ao MySQL. Configure manualmente.";
        }
    }
    
    private function createDatabase() {
        if (!$this->dbConfig) return;
        
        try {
            $pdo = new PDO(
                "mysql:host={$this->dbConfig['host']};charset=utf8mb4",
                $this->dbConfig['user'],
                $this->dbConfig['pass']
            );
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS megafisio_ia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->success[] = "Banco de dados criado ✓";
            
            // Salvar configuração
            $this->saveDbConfig();
            
        } catch (PDOException $e) {
            $this->errors[] = "Erro ao criar banco: " . $e->getMessage();
        }
    }
    
    private function saveDbConfig() {
        $config = "<?php
class Database {
    private static \$instance = null;
    private \$connection;
    
    private \$host = '{$this->dbConfig['host']}';
    private \$database = 'megafisio_ia';
    private \$username = '{$this->dbConfig['user']}';
    private \$password = '{$this->dbConfig['pass']}';
    private \$charset = 'utf8mb4';
    
    private function __construct() {
        try {
            \$dsn = \"mysql:host={\$this->host};dbname={\$this->database};charset={\$this->charset}\";
            \$options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => \"SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci\"
            ];
            
            \$this->connection = new PDO(\$dsn, \$this->username, \$this->password, \$options);
        } catch (PDOException \$e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                die(\"Erro de conexão: \" . \$e->getMessage());
            } else {
                die(\"Erro ao conectar com o banco de dados\");
            }
        }
    }
    
    public static function getInstance() {
        if (self::\$instance === null) {
            self::\$instance = new self();
        }
        return self::\$instance;
    }
    
    public function getConnection() {
        return \$this->connection;
    }
    
    public function prepare(\$sql) {
        return \$this->connection->prepare(\$sql);
    }
    
    public function query(\$sql) {
        return \$this->connection->query(\$sql);
    }
    
    public function lastInsertId() {
        return \$this->connection->lastInsertId();
    }
    
    public function beginTransaction() {
        return \$this->connection->beginTransaction();
    }
    
    public function commit() {
        return \$this->connection->commit();
    }
    
    public function rollBack() {
        return \$this->connection->rollBack();
    }
}";
        
        file_put_contents(ROOT_PATH . '/config/database.php', $config);
    }
    
    private function runMigrations() {
        if (!$this->dbConfig) return;
        
        try {
            $pdo = new PDO(
                "mysql:host={$this->dbConfig['host']};dbname=megafisio_ia;charset=utf8mb4",
                $this->dbConfig['user'],
                $this->dbConfig['pass']
            );
            
            // Executar migrations
            $migrationFiles = glob(ROOT_PATH . '/migrations/*.sql');
            sort($migrationFiles);
            
            foreach ($migrationFiles as $file) {
                $sql = file_get_contents($file);
                $queries = $this->splitSqlFile($sql);
                
                foreach ($queries as $query) {
                    if (trim($query) && !$this->isComment($query)) {
                        $pdo->exec($query);
                    }
                }
            }
            
            $this->success[] = "Migrations executadas ✓";
            
        } catch (PDOException $e) {
            $this->errors[] = "Erro nas migrations: " . $e->getMessage();
        }
    }
    
    private function createAdminUser() {
        if (!$this->dbConfig) return;
        
        try {
            $pdo = new PDO(
                "mysql:host={$this->dbConfig['host']};dbname=megafisio_ia;charset=utf8mb4",
                $this->dbConfig['user'],
                $this->dbConfig['pass']
            );
            
            // Criar usuário admin padrão
            $email = 'admin@megafisio.com';
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("
                INSERT INTO users (email, password, name, role, status) 
                VALUES (?, ?, 'Administrador', 'admin', 'active')
                ON DUPLICATE KEY UPDATE id=id
            ");
            $stmt->execute([$email, $password]);
            
            $this->success[] = "Usuário admin criado ✓";
            $this->success[] = "Login: admin@megafisio.com / admin123";
            
        } catch (PDOException $e) {
            $this->errors[] = "Erro ao criar admin: " . $e->getMessage();
        }
    }
    
    private function finalizeInstallation() {
        if (!empty($this->errors)) return;
        
        // Criar arquivo de controle
        file_put_contents(ROOT_PATH . '/.installed', date('Y-m-d H:i:s'));
        
        // Auto-deletar este arquivo e diretório
        $this->selfDestruct();
        
        $this->success[] = "Instalação concluída! ✓";
        $this->success[] = "Arquivos de instalação removidos ✓";
    }
    
    private function selfDestruct() {
        // Deletar este arquivo
        @unlink(__FILE__);
        
        // Deletar outros arquivos de setup
        @unlink(ROOT_PATH . '/test_connection.php');
        @unlink(ROOT_PATH . '/create_database.sql');
        @unlink(ROOT_PATH . '/setup_mysql.sql');
        @unlink(ROOT_PATH . '/INSTRUCOES_MYSQL.md');
        @unlink(ROOT_PATH . '/db_config_detected.php');
        @unlink(ROOT_PATH . '/install.php');
        
        // Tentar remover diretório setup
        @rmdir(dirname(__FILE__));
    }
    
    private function splitSqlFile($sql) {
        $queries = [];
        $currentQuery = '';
        $inString = false;
        
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            
            if (!$inString && ($char == "'" || $char == '"')) {
                $inString = true;
            } elseif ($inString && $char == "'" && $sql[$i - 1] != '\\') {
                $inString = false;
            }
            
            if (!$inString && $char == ';') {
                $queries[] = $currentQuery;
                $currentQuery = '';
                continue;
            }
            
            $currentQuery .= $char;
        }
        
        if (trim($currentQuery)) {
            $queries[] = $currentQuery;
        }
        
        return $queries;
    }
    
    private function isComment($line) {
        $line = trim($line);
        return empty($line) || substr($line, 0, 2) == '--';
    }
    
    private function getHeader() {
        return '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto-Instalação - Mega Fisio IA</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #1a1a1a;
            color: #fff;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: #2a2a2a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        h1 {
            color: #ffd700;
            margin-bottom: 30px;
            text-align: center;
        }
        .status {
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            font-size: 14px;
        }
        .success {
            background: #1e4620;
            color: #4ade80;
            border: 1px solid #22c55e;
        }
        .error {
            background: #4a1a1a;
            color: #f87171;
            border: 1px solid #ef4444;
        }
        .info {
            background: #1a3a4a;
            color: #60a5fa;
            border: 1px solid #3b82f6;
        }
        ul {
            margin: 0;
            padding-left: 20px;
        }
        .loading {
            text-align: center;
            margin: 30px 0;
        }
        .spinner {
            border: 3px solid #333;
            border-top: 3px solid #ffd700;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Auto-Instalação Mega Fisio IA</h1>';
    }
    
    private function getFooter() {
        $html = '';
        
        if (!empty($this->errors)) {
            $html .= '<div class="status error"><strong>❌ Erros encontrados:</strong><ul>';
            foreach ($this->errors as $error) {
                $html .= "<li>$error</li>";
            }
            $html .= '</ul></div>';
        }
        
        if (!empty($this->success)) {
            $html .= '<div class="status success"><strong>✅ Concluído:</strong><ul>';
            foreach ($this->success as $item) {
                $html .= "<li>$item</li>";
            }
            $html .= '</ul></div>';
        }
        
        if (empty($this->errors) && !empty($this->success)) {
            $html .= '<div class="status info">
                <strong>🎉 Sistema instalado com sucesso!</strong><br><br>
                Acesse <a href="/public/" style="color: #60a5fa;">aqui</a> para começar a usar.<br><br>
                <small>Este arquivo foi removido automaticamente por segurança.</small>
            </div>';
        }
        
        $html .= '</div></body></html>';
        return $html;
    }
}

// Executar instalador
$installer = new AutoInstaller();
$installer->run();