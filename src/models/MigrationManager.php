<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

class MigrationManager {
    private $db;
    private $migrationsPath;
    
    public function __construct($db) {
        $this->db = $db;
        $this->migrationsPath = ROOT_PATH . '/migrations';
    }
    
    public function run() {
        try {
            $this->createMigrationsTableIfNotExists();
            $executedMigrations = $this->getExecutedMigrations();
            $migrationFiles = $this->getMigrationFiles();
            
            foreach ($migrationFiles as $file) {
                $version = $this->getVersionFromFilename($file);
                
                if (!in_array($version, $executedMigrations)) {
                    $this->executeMigration($file, $version);
                }
            }
        } catch (Exception $e) {
            die("Erro crítico no sistema de migrations: " . $e->getMessage());
        }
    }
    
    private function createMigrationsTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            version VARCHAR(50) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            description TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
    }
    
    private function getExecutedMigrations() {
        try {
            $stmt = $this->db->query("SELECT version FROM migrations ORDER BY id");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function getMigrationFiles() {
        $files = glob($this->migrationsPath . '/*.sql');
        sort($files);
        return $files;
    }
    
    private function getVersionFromFilename($filename) {
        $basename = basename($filename, '.sql');
        return $basename;
    }
    
    private function executeMigration($file, $version) {
        $sql = file_get_contents($file);
        
        if (empty($sql)) {
            throw new Exception("Migration $version está vazia");
        }
        
        // Dividir em statements individuais
        $statements = $this->parseStatements($sql);
        
        try {
            $this->db->beginTransaction();
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement) || $this->isComment($statement)) {
                    continue;
                }
                
                // Skip INSERT INTO migrations - será feito manualmente
                if (stripos($statement, 'INSERT INTO migrations') !== false) {
                    continue;
                }
                
                $this->executeStatement($statement);
            }
            
            // Registrar migração executada
            $stmt = $this->db->prepare("INSERT INTO migrations (version, description) VALUES (?, ?)");
            $description = $this->extractDescription($sql);
            $stmt->execute([$version, $description]);
            
            $this->db->commit();
            
            if (DEBUG_MODE) {
                echo "Migration executada: $version\n";
            }
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Erro ao executar migration $version: " . $e->getMessage());
        }
    }
    
    private function parseStatements($sql) {
        // Remove comentários de linha
        $sql = preg_replace('/--.*$/m', '', $sql);
        
        // Divide por ; mas preserva strings
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            
            if (!$inString && ($char === '"' || $char === "'")) {
                $inString = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar && $sql[$i-1] !== '\\') {
                $inString = false;
            }
            
            if (!$inString && $char === ';') {
                $statements[] = $current;
                $current = '';
                continue;
            }
            
            $current .= $char;
        }
        
        if (trim($current)) {
            $statements[] = $current;
        }
        
        return $statements;
    }
    
    private function executeStatement($statement) {
        try {
            $this->db->query($statement);
        } catch (PDOException $e) {
            // Ignorar erros específicos que não impedem o funcionamento
            $ignorableErrors = [
                '1061', // Duplicate key name
                '1050', // Table already exists
                '1060', // Duplicate column name
            ];
            
            if (in_array($e->getCode(), $ignorableErrors)) {
                if (DEBUG_MODE) {
                    echo "Aviso ignorado: " . $e->getMessage() . "\n";
                }
                return;
            }
            
            throw $e;
        }
    }
    
    private function isComment($line) {
        $line = trim($line);
        return empty($line) || 
               substr($line, 0, 2) === '--' || 
               substr($line, 0, 2) === '/*' ||
               substr($line, 0, 1) === '#';
    }
    
    private function extractDescription($sql) {
        if (preg_match('/-- Description:\s*(.+)/i', $sql, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }
    
    public function reset() {
        // Método para resetar migrations (usar com cuidado)
        try {
            $this->db->query("DROP TABLE IF EXISTS migrations");
            echo "Tabela de migrations resetada\n";
        } catch (Exception $e) {
            throw new Exception("Erro ao resetar migrations: " . $e->getMessage());
        }
    }
}