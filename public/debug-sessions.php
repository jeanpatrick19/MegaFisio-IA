<?php
session_start();
define('PUBLIC_ACCESS', true);
require_once '../config/config.php';
require_once '../config/database.php';

header('Content-Type: text/plain');

try {
    $db = Database::getInstance();
    
    echo "=== DEBUG SESSÕES ===\n";
    echo "Session ID atual: " . session_id() . "\n";
    echo "User ID na sessão: " . ($_SESSION['user_id'] ?? 'NÃO DEFINIDO') . "\n\n";
    
    // Verificar se tabela existe
    $stmt = $db->query("SHOW TABLES LIKE 'user_sessions'");
    if ($stmt->fetch()) {
        echo "✓ Tabela user_sessions existe\n";
        
        // Mostrar estrutura da tabela
        echo "\nEstrutura da tabela:\n";
        $stmt = $db->query("DESCRIBE user_sessions");
        while ($row = $stmt->fetch()) {
            echo "- {$row['Field']} ({$row['Type']})\n";
        }
        
        // Contar registros totais
        $stmt = $db->query("SELECT COUNT(*) as total FROM user_sessions");
        $total = $stmt->fetch()['total'];
        echo "\nTotal de registros: $total\n";
        
        if ($total > 0) {
            // Mostrar últimos 5 registros
            echo "\nÚltimos registros:\n";
            $stmt = $db->query("SELECT id, user_id, is_active, is_current, created_at FROM user_sessions ORDER BY created_at DESC LIMIT 5");
            while ($row = $stmt->fetch()) {
                echo "- ID: {$row['id']}, User: {$row['user_id']}, Ativo: {$row['is_active']}, Atual: {$row['is_current']}, Criado: {$row['created_at']}\n";
            }
        }
        
        // Se há user_id na sessão, verificar sessões do usuário
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            echo "\nSessões do usuário $userId:\n";
            $stmt = $db->prepare("SELECT * FROM user_sessions WHERE user_id = ?");
            $stmt->execute([$userId]);
            $sessions = $stmt->fetchAll();
            
            if (empty($sessions)) {
                echo "NENHUMA sessão encontrada para este usuário!\n";
                
                // Tentar criar uma sessão manualmente
                echo "\nTentando criar sessão...\n";
                $sessionId = session_id();
                $stmt = $db->prepare("
                    INSERT INTO user_sessions (
                        id, user_id, user_agent, ip_address, location, 
                        device_type, browser, os, is_current, is_active, expires_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE, TRUE, DATE_ADD(NOW(), INTERVAL 30 DAY))
                ");
                
                $result = $stmt->execute([
                    $sessionId,
                    $userId,
                    $_SERVER['HTTP_USER_AGENT'] ?? 'Debug',
                    $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                    'Debug Test',
                    'Desktop',
                    'Debug Browser',
                    'Debug OS'
                ]);
                
                if ($result) {
                    echo "✓ Sessão criada com sucesso!\n";
                } else {
                    echo "✗ Falha ao criar sessão\n";
                }
            } else {
                foreach ($sessions as $session) {
                    echo "- ID: {$session['id']}, Ativo: {$session['is_active']}, Atual: {$session['is_current']}\n";
                }
            }
        }
        
    } else {
        echo "✗ Tabela user_sessions NÃO existe\n";
        
        // Tentar criar via SmartMigrationManager
        echo "\nTentando criar tabela...\n";
        require_once '../src/models/SmartMigrationManager.php';
        $migrationManager = new SmartMigrationManager($db);
        $migrationManager->createUserSessionsTable();
        echo "✓ Comando de criação executado\n";
    }
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Auto-remover
unlink(__FILE__);