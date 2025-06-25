<?php
/**
 * Configuração de banco de dados para produção
 * Substitua /config/database.php por este arquivo em produção
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // IMPORTANTE: Configurar com as credenciais do seu servidor
    private $host = 'localhost';
    private $database = 'seu_banco_producao';
    private $username = 'seu_usuario_producao';
    private $password = 'sua_senha_segura';
    private $charset = 'utf8mb4';
    
    // Configurações adicionais para produção
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        // Otimizações para produção
        PDO::ATTR_PERSISTENT => true, // Conexões persistentes
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    ];
    
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $this->options);
            
            // Configurações adicionais de produção
            $this->connection->exec("SET time_zone = '-03:00'"); // Brasília
            $this->connection->exec("SET sql_mode = 'TRADITIONAL'"); // Modo strict
            
        } catch (PDOException $e) {
            // Em produção, logar erro sem expor detalhes
            error_log("Database connection error: " . $e->getMessage());
            
            // Página de erro genérica
            http_response_code(500);
            if (file_exists(dirname(__DIR__) . '/public/500.html')) {
                include dirname(__DIR__) . '/public/500.html';
            } else {
                echo "Erro no servidor. Por favor, tente novamente mais tarde.";
            }
            exit;
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollBack() {
        return $this->connection->rollBack();
    }
    
    public function inTransaction() {
        return $this->connection->inTransaction();
    }
    
    /**
     * Executar query com retry em caso de deadlock
     */
    public function executeWithRetry($callback, $maxRetries = 3) {
        $attempts = 0;
        
        while ($attempts < $maxRetries) {
            try {
                return $callback($this);
            } catch (PDOException $e) {
                $attempts++;
                
                // Verificar se é deadlock
                if ($e->getCode() == '40001' && $attempts < $maxRetries) {
                    usleep(100000 * $attempts); // Delay progressivo
                    continue;
                }
                
                throw $e;
            }
        }
    }
    
    /**
     * Verificar saúde da conexão
     */
    public function healthCheck() {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}