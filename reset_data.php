<?php
/**
 * Reset de Dados - MegaFisio IA
 * CUIDADO: Este arquivo remove TODOS os dados do sistema!
 */

// Verificar se é uma solicitação de confirmação
$confirmed = $_GET['confirm'] ?? false;

if ($confirmed === 'yes') {
    try {
        // Remover arquivo de instalação
        if (file_exists('config/installed.lock')) {
            unlink('config/installed.lock');
        }
        
        // Limpar banco de dados se existir
        if (file_exists('config/db_config.php')) {
            $config = require 'config/db_config.php';
            
            try {
                $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
                $pdo = new PDO($dsn, $config['username'], $config['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Dropar banco inteiro
                $pdo->exec("DROP DATABASE IF EXISTS `{$config['database']}`");
                
                $success = "Banco de dados '{$config['database']}' removido com sucesso!";
                
            } catch (Exception $e) {
                $error = "Erro ao limpar banco: " . $e->getMessage();
            }
        }
        
        // Remover configuração do banco
        if (file_exists('config/db_config.php')) {
            unlink('config/db_config.php');
        }
        
        if (!isset($error)) {
            $success = "Sistema resetado com sucesso! Agora você pode reinstalar.";
        }
        
    } catch (Exception $e) {
        $error = "Erro no reset: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Sistema - MegaFisio IA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
            min-height: 100vh;
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }

        .reset-container {
            background: #ffffff;
            border: 2px solid #ef4444;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logo-subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 32px;
            font-weight: 500;
        }

        h2 {
            color: #ef4444;
            margin-bottom: 24px;
            font-weight: 700;
            font-size: 22px;
            text-align: center;
        }

        .warning {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            color: #ef4444;
        }

        .warning strong {
            color: #ff4444;
        }

        .success {
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid #059669;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            color: #059669;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background: linear-gradient(45deg, #ff4444, #cc0000);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(45deg, #cc0000, #ff4444);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 68, 68, 0.4);
        }

        .btn-safe {
            background: linear-gradient(45deg, #6b7280, #9ca3af);
            color: white;
        }

        .btn-safe:hover {
            background: linear-gradient(45deg, #9ca3af, #6b7280);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 114, 128, 0.4);
        }

        .btn-success {
            background: linear-gradient(45deg, #059669, #047857);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(45deg, #047857, #059669);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.4);
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="logo">MegaFisio IA</div>
        <div class="logo-subtitle">Inteligência para Fisioterapia</div>
        
        <?php if (isset($success)): ?>
            <h2>✅ Reset Concluído!</h2>
            <div class="success">
                <?= htmlspecialchars($success) ?>
            </div>
            <a href="install.php" class="btn btn-success">Reinstalar Sistema</a>
            
        <?php elseif (isset($error)): ?>
            <h2>❌ Erro no Reset</h2>
            <div class="warning">
                <?= htmlspecialchars($error) ?>
            </div>
            <a href="reset_data.php" class="btn btn-safe">Tentar Novamente</a>
            
        <?php elseif ($confirmed): ?>
            <h2>🔄 Processando...</h2>
            
        <?php else: ?>
            <h2>⚠️ Reset do Sistema</h2>
            
            <div class="warning">
                <strong>ATENÇÃO!</strong><br><br>
                Esta ação irá:<br>
                • Remover TODOS os dados do banco<br>
                • Deletar todas as configurações<br>
                • Resetar o sistema completamente<br><br>
                <strong>Esta ação NÃO pode ser desfeita!</strong>
            </div>
            
            <p style="margin: 20px 0; color: #6b7280; font-weight: 500;">
                Tem certeza que deseja resetar completamente o sistema?
            </p>
            
            <a href="reset_data.php?confirm=yes" class="btn btn-danger" 
               onclick="return confirm('ÚLTIMA CHANCE! Tem CERTEZA ABSOLUTA que deseja resetar tudo?')">
               🗑️ SIM, RESETAR TUDO
            </a>
            
            <a href="public/index.php" class="btn btn-safe">❌ Cancelar</a>
        <?php endif; ?>
    </div>
</body>
</html>