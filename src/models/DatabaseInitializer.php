<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

class DatabaseInitializer {
    private $db;
    
    public function __construct() {
        $this->checkAndCreateDatabase();
        $this->db = Database::getInstance();
        $this->runMigrations();
    }
    
    private function checkAndCreateDatabase() {
        try {
            $config = require __DIR__ . '/../../config/db_config.php';
            $host = $config['host'];
            $username = $config['username'];
            $password = $config['password'];
            $charset = $config['charset'];
            $database = $config['database'];
            
            // Conectar sem especificar banco
            $dsn = "mysql:host={$host};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $pdo = new PDO($dsn, $username, $password, $options);
            
            // Criar banco se não existir
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` 
                       DEFAULT CHARACTER SET utf8mb4 
                       DEFAULT COLLATE utf8mb4_unicode_ci");
            
            
        } catch (PDOException $e) {
            die("Erro ao verificar/criar banco de dados: " . $e->getMessage());
        }
    }
    
    private function runMigrations() {
        require_once SRC_PATH . '/models/SmartMigrationManager.php';
        
        $smartMigrationManager = new SmartMigrationManager($this->db);
        $smartMigrationManager->createAllTables();
        
        // Registrar que todas as migrations foram executadas
        try {
            $this->db->query("INSERT INTO migrations (version, description) VALUES ('001_create_initial_tables', 'Criação automática das tabelas iniciais') ON DUPLICATE KEY UPDATE version = version");
            $this->db->query("INSERT INTO migrations (version, description) VALUES ('002_update_users_structure', 'Atualização automática da estrutura users') ON DUPLICATE KEY UPDATE version = version");
        } catch (Exception $e) {
            // Ignorar erros de duplicação
        }
    }
    
    public function getDatabase() {
        return $this->db;
    }
}