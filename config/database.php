<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private $host;
    private $database;
    private $username;
    private $password;
    private $charset;
    
    private function __construct() {
        // Carregar configuração do arquivo
        $config = require __DIR__ . '/db_config.php';
        $this->host = $config['host'];
        $this->database = $config['database'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->charset = $config['charset'];
        
        try {
            // Primeiro tentar conectar sem banco para criá-lo se necessário
            $dsnNoDb = "mysql:host={$this->host};charset={$this->charset}";
            $tempConnection = new PDO($dsnNoDb, $this->username, $this->password);
            $tempConnection->exec("CREATE DATABASE IF NOT EXISTS {$this->database}");
            
            // Agora conectar ao banco específico
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
            // Executar migração automática
            $this->runAutoMigration();
        } catch (PDOException $e) {
            die("Erro ao verificar/criar banco de dados: " . $e->getMessage() . 
                "<br>Tentando conectar com: {$this->username}/{$this->password}");
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
    
    public function exec($sql) {
        return $this->connection->exec($sql);
    }
    
    private function runAutoMigration() {
        try {
            // Verificar se as tabelas essenciais existem
            if (!$this->needsMigration()) {
                return; // Tudo OK, não precisa migrar
            }
            
            // Verificar lock para evitar execução simultânea
            $lockFile = sys_get_temp_dir() . '/megafisio_migration.lock';
            if (file_exists($lockFile) && (time() - filemtime($lockFile)) < 300) {
                return;
            }
            
            // Criar lock
            file_put_contents($lockFile, date('Y-m-d H:i:s'));
            
            // Incluir o SmartMigrationManager
            require_once __DIR__ . '/../src/models/SmartMigrationManager.php';
            
            // Executar migração
            $migrationManager = new SmartMigrationManager($this);
            $migrationManager->createAllTables();
            
            // Remover lock
            if (file_exists($lockFile)) {
                unlink($lockFile);
            }
            
        } catch (Exception $e) {
            // Em caso de erro, remover lock e continuar
            if (file_exists($lockFile)) {
                unlink($lockFile);
            }
            error_log("Erro na migração automática: " . $e->getMessage());
        }
    }
    
    private function needsMigration() {
        try {
            // Verificação otimizada: apenas tabelas críticas para funcionamento básico
            $criticalTables = ['users', 'user_profiles_extended', 'settings'];
            
            foreach ($criticalTables as $table) {
                $stmt = $this->connection->prepare("
                    SELECT COUNT(*) 
                    FROM INFORMATION_SCHEMA.TABLES 
                    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?
                ");
                $stmt->execute([$table]);
                
                if ($stmt->fetchColumn() == 0) {
                    return true; // Tabela crítica não existe, precisa migrar
                }
            }
            
            // Verificação rápida de campos essenciais apenas na user_profiles_extended
            $criticalColumns = ['phone', 'theme', 'language'];
            
            foreach ($criticalColumns as $column) {
                $stmt = $this->connection->prepare("
                    SELECT COUNT(*) 
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'user_profiles_extended' 
                    AND COLUMN_NAME = ?
                ");
                $stmt->execute([$column]);
                
                if ($stmt->fetchColumn() == 0) {
                    return true; // Campo crítico não existe, precisa migrar
                }
            }
            
            return false; // Estrutura básica OK
            
        } catch (Exception $e) {
            // Em caso de erro, assumir que precisa migrar
            return true;
        }
    }
}