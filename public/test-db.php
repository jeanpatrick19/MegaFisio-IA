<?php
session_start();
define('PUBLIC_ACCESS', true);
require_once '../config/config.php';
require_once '../src/models/Database.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance();
    
    // Testar conexão
    $stmt = $db->query("SELECT 1");
    
    // Verificar tabela user_sessions
    $stmt = $db->query("SHOW TABLES LIKE 'user_sessions'");
    $tableExists = $stmt->fetch() ? true : false;
    
    // Se não existir, tentar criar
    if (!$tableExists) {
        $sql = "CREATE TABLE IF NOT EXISTS user_sessions (
            id VARCHAR(128) PRIMARY KEY,
            user_id INT NOT NULL,
            user_agent TEXT,
            ip_address VARCHAR(45),
            location VARCHAR(255),
            device_type VARCHAR(50),
            browser VARCHAR(100),
            os VARCHAR(100),
            is_current BOOLEAN DEFAULT FALSE,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_is_active (is_active),
            INDEX idx_expires_at (expires_at),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->exec($sql);
        $tableExists = true;
    }
    
    // Verificar estrutura
    $columns = [];
    if ($tableExists) {
        $stmt = $db->query("DESCRIBE user_sessions");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $row['Field'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'database_connected' => true,
        'table_exists' => $tableExists,
        'columns' => $columns,
        'user_id' => $_SESSION['user_id'] ?? null
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}

// Auto-remover após execução
unlink(__FILE__);