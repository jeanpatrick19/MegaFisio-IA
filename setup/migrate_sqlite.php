<?php
// Script para adaptar migrations MySQL para SQLite
if (!defined('PUBLIC_ACCESS')) {
    define('PUBLIC_ACCESS', true);
}

require_once __DIR__ . '/../config/database.php';

function convertMysqlToSqlite($sql) {
    // Remover ENGINE e CHARSET
    $sql = preg_replace('/ENGINE=\w+/', '', $sql);
    $sql = preg_replace('/DEFAULT CHARSET=\w+/', '', $sql);
    $sql = preg_replace('/COLLATE=\w+/', '', $sql);
    
    // Converter AUTO_INCREMENT para AUTOINCREMENT
    $sql = str_replace('AUTO_INCREMENT', 'AUTOINCREMENT', $sql);
    
    // Converter BIGINT AUTO_INCREMENT para INTEGER (SQLite)
    $sql = preg_replace('/BIGINT\s+AUTOINCREMENT/', 'INTEGER', $sql);
    $sql = preg_replace('/INT\s+AUTOINCREMENT/', 'INTEGER', $sql);
    
    // Converter ENUM para TEXT com CHECK
    $sql = preg_replace("/ENUM\('([^']+)'(?:,\s*'([^']*)')*\)/", 'TEXT', $sql);
    
    // Converter TIMESTAMP para DATETIME
    $sql = str_replace('TIMESTAMP', 'DATETIME', $sql);
    $sql = str_replace('CURRENT_TIMESTAMP', "datetime('now')", $sql);
    $sql = str_replace('ON UPDATE CURRENT_TIMESTAMP', '', $sql);
    
    // Converter VARCHAR para TEXT
    $sql = preg_replace('/VARCHAR\(\d+\)/', 'TEXT', $sql);
    
    // Remover INDEX separados (SQLite cria automaticamente)
    $sql = preg_replace('/,\s*INDEX\s+\w+\s*\([^)]+\)/', '', $sql);
    
    return $sql;
}

try {
    $db = Database::getInstance();
    
    // Ler migration original
    $migrationFile = __DIR__ . '/../migrations/001_create_initial_tables.sql';
    $sql = file_get_contents($migrationFile);
    
    // Converter para SQLite
    $sqliteSql = convertMysqlToSqlite($sql);
    
    // Executar comandos SQL
    $statements = explode(';', $sqliteSql);
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        try {
            $db->query($statement);
            echo "✓ Executado: " . substr($statement, 0, 50) . "...\n";
        } catch (Exception $e) {
            echo "✗ Erro: " . $e->getMessage() . "\n";
            echo "SQL: " . $statement . "\n";
        }
    }
    
    // Criar usuário admin padrão
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $stmt->execute();
    $adminCount = $stmt->fetchColumn();
    
    if ($adminCount == 0) {
        $stmt = $db->prepare("
            INSERT INTO users (email, password, name, role, status)
            VALUES (?, ?, ?, 'admin', 'active')
        ");
        $stmt->execute([
            'admin@megafisio.com',
            password_hash('admin123', PASSWORD_DEFAULT),
            'Administrador'
        ]);
        echo "✓ Usuário admin criado (admin@megafisio.com / admin123)\n";
    }
    
    echo "\n✅ Migration concluída com sucesso!\n";
    
} catch (Exception $e) {
    echo "❌ Erro na migration: " . $e->getMessage() . "\n";
}
?>