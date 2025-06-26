<?php
session_start();
define('PUBLIC_ACCESS', true);

// Mostrar todos os erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/config.php';
require_once '../src/models/Database.php';

echo "<h3>Teste de Login - Debug</h3>";

try {
    $db = Database::getInstance();
    echo "✓ Conexão com banco OK<br>";
    
    // Verificar tabela users
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "✓ Tabela users existe - Total: $userCount usuários<br>";
    
    // Verificar tabela user_profiles_extended
    $stmt = $db->query("SHOW TABLES LIKE 'user_profiles_extended'");
    if ($stmt->fetch()) {
        echo "✓ Tabela user_profiles_extended existe<br>";
        
        // Verificar estrutura
        $stmt = $db->query("DESCRIBE user_profiles_extended");
        echo "<br>Colunas da tabela user_profiles_extended:<br>";
        while ($row = $stmt->fetch()) {
            if ($row['Field'] == 'two_factor_enabled') {
                echo "✓ Campo two_factor_enabled: " . $row['Type'] . "<br>";
            }
        }
    } else {
        echo "✗ Tabela user_profiles_extended NÃO existe<br>";
    }
    
    // Verificar sessão
    echo "<br>Session ID: " . session_id() . "<br>";
    echo "Session Status: " . session_status() . "<br>";
    
    // Verificar se pode acessar AuthController
    $controllerFile = '../src/controllers/AuthController.php';
    if (file_exists($controllerFile)) {
        echo "✓ AuthController encontrado<br>";
    } else {
        echo "✗ AuthController não encontrado em: $controllerFile<br>";
    }
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "<br>";
    echo "Stack trace:<pre>" . $e->getTraceAsString() . "</pre>";
}

// Remover após teste
unlink(__FILE__);
?>