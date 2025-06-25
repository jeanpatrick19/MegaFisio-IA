<?php
/**
 * Instalador Automático MegaFisio IA
 * Execute este arquivo apenas uma vez após fazer upload dos arquivos
 */

// Verificar se já foi instalado
if (file_exists('config/installed.lock')) {
    // Se já instalado, mostrar opções
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema Já Instalado - MegaFisio IA</title>
        <style>
            :root {
                --azul-saude: #1e3a8a;
                --verde-terapia: #059669;
                --dourado-premium: #ca8a04;
                --branco-puro: #ffffff;
                --cinza-claro: #f8fafc;
                --cinza-medio: #e5e7eb;
                --cinza-escuro: #1f2937;
                --erro: #ef4444;
                --gradiente-principal: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                --gradiente-fundo: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
                --sombra-flutuante: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            }
            
            * { margin: 0; padding: 0; box-sizing: border-box; }
            
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                background: var(--gradiente-fundo);
                min-height: 100vh;
                color: var(--cinza-escuro);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                -webkit-font-smoothing: antialiased;
            }
            
            .install-container {
                background: var(--branco-puro);
                border: 1px solid var(--cinza-medio);
                border-radius: 24px;
                padding: 48px;
                box-shadow: var(--sombra-flutuante);
                width: 100%;
                max-width: 500px;
            }
            
            .logo {
                text-align: center;
                font-size: 28px;
                font-weight: 800;
                color: var(--azul-saude);
                margin-bottom: 8px;
                letter-spacing: -0.5px;
            }
            
            .logo-subtitle {
                text-align: center;
                color: #6b7280;
                font-size: 14px;
                margin-bottom: 32px;
                font-weight: 500;
            }
            
            h2 {
                color: var(--azul-saude);
                margin-bottom: 24px;
                text-align: center;
                font-weight: 700;
                font-size: 24px;
            }
            
            .btn {
                width: 100%;
                padding: 16px 24px;
                background: var(--gradiente-principal);
                border: none;
                border-radius: 12px;
                color: white;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                font-family: inherit;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }
            
            .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
            
            .btn-link {
                background: transparent;
                border: 2px solid var(--cinza-medio);
                color: var(--cinza-escuro);
                margin-top: 16px;
                font-weight: 500;
            }
            
            .btn-link:hover {
                border-color: var(--verde-terapia);
                color: var(--verde-terapia);
                background: rgba(5, 150, 105, 0.05);
            }
            
            .button-container { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="install-container">
            <div class="logo">MegaFisio IA</div>
            <div class="logo-subtitle">Inteligência para Fisioterapia</div>
            
            <h2>✅ Sistema Já Instalado!</h2>
            <p style="text-align: center; margin-bottom: 32px; color: var(--cinza-escuro);">O sistema já foi instalado anteriormente.</p>
            
            <div class="button-container">
                <a href="public/index.php" class="btn">🏠 Acessar Sistema</a>
                <a href="reset_data.php" class="btn btn-link">🗑️ Resetar e Reinstalar</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = $_GET['success'] ?? '';

if ($_POST) {
    if ($step == 1 && !isset($_POST['admin_name'])) {
        // Configurar banco de dados
        $host = $_POST['host'] ?? 'localhost';
        $database = $_POST['database'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($database) || empty($username)) {
            $error = 'Preencha todos os campos obrigatórios';
        } else {
            try {
                // Testar conexão
                $dsn = "mysql:host={$host};charset=utf8mb4";
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Criar banco se não existir
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Salvar configuração
                $config = "<?php\nreturn [\n";
                $config .= "    'host' => '{$host}',\n";
                $config .= "    'database' => '{$database}',\n";
                $config .= "    'username' => '{$username}',\n";
                $config .= "    'password' => '{$password}',\n";
                $config .= "    'charset' => 'utf8mb4'\n";
                $config .= "];\n?>";
                
                file_put_contents('config/db_config.php', $config);
                
                // Redirecionar para step 2
                header('Location: install.php?step=2&success=' . urlencode('Banco configurado com sucesso!'));
                exit;
                
            } catch (Exception $e) {
                $error = 'Erro na conexão: ' . $e->getMessage();
            }
        }
    }
    
    if (isset($_POST['admin_name']) || $step == 2) {
        // Configurar administrador
        $step = 2; // Garantir que estamos no step 2
        $name = $_POST['admin_name'] ?? '';
        $email = $_POST['admin_email'] ?? '';
        $admin_password = $_POST['admin_password'] ?? '';
        
        if (empty($name) || empty($email) || empty($admin_password)) {
            $error = 'Preencha todos os campos do administrador';
        } else {
            try {
                // Inicializar sistema
                define('PUBLIC_ACCESS', true);
                define('ROOT_PATH', __DIR__);
                define('CONFIG_PATH', ROOT_PATH . '/config');
                define('SRC_PATH', ROOT_PATH . '/src');
                
                require_once 'config/config.php';
                require_once 'config/database.php';
                require_once 'src/models/SmartMigrationManager.php';
                
                $db = Database::getInstance();
                $migrationManager = new SmartMigrationManager($db);
                $migrationManager->createAllTables();
                
                // Criar admin personalizado
                $hashedPassword = password_hash($admin_password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("DELETE FROM users WHERE email = 'admin@megafisio.com'");
                $stmt->execute();
                
                $stmt = $db->prepare("INSERT INTO users (email, password, name, role, status, first_login) VALUES (?, ?, ?, 'admin', 'active', 0)");
                $stmt->execute([$email, $hashedPassword, $name]);
                
                // Configurar API Key OpenAI se fornecida
                if (!empty($_POST['openai_api_key'])) {
                    $stmt = $db->prepare("UPDATE settings SET `value` = ? WHERE `key` = 'openai_api_key'");
                    $stmt->execute([$_POST['openai_api_key']]);
                }
                
                // Marcar como instalado
                file_put_contents('config/installed.lock', date('Y-m-d H:i:s'));
                
                $success = 'Sistema instalado com sucesso!';
                $step = 3;
                
            } catch (Exception $e) {
                $error = 'Erro na instalação: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação - MegaFisio IA</title>
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

        .install-container {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .logo {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logo-subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 32px;
            font-weight: 500;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f3f4f6;
            border: 2px solid #d1d5db;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: 600;
        }

        .step.active {
            background: #1e3a8a;
            color: white;
            border-color: #1e3a8a;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        .step.completed {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--erro);
            color: var(--erro);
        }

        .alert-success {
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid var(--verde-terapia);
            color: var(--verde-terapia);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: var(--cinza-escuro);
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--cinza-medio);
            border-radius: 12px;
            background: var(--branco-puro);
            color: var(--cinza-escuro);
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--azul-saude);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .btn {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-link {
            background: transparent;
            border: 2px solid var(--cinza-medio);
            color: var(--cinza-escuro);
            margin-top: 16px;
            font-weight: 500;
        }

        .btn-link:hover {
            border-color: var(--verde-terapia);
            color: var(--verde-terapia);
            background: rgba(5, 150, 105, 0.05);
        }

        h2 {
            color: var(--azul-saude);
            margin-bottom: 24px;
            text-align: center;
            font-weight: 700;
            font-size: 24px;
        }

        .help-text {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
            font-weight: 400;
        }

        .success-box {
            background: rgba(5, 150, 105, 0.1);
            border: 1px solid var(--verde-terapia);
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            text-align: center;
        }

        .success-box h3 {
            color: var(--verde-terapia);
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 600;
        }

        .success-box p {
            color: var(--cinza-escuro);
            margin: 8px 0;
            font-weight: 500;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="logo">MegaFisio IA</div>
        <div class="logo-subtitle">Inteligência para Fisioterapia</div>
        
        <div class="step-indicator">
            <div class="step <?= $step >= 1 ? 'active' : '' ?> <?= $step > 1 ? 'completed' : '' ?>">1</div>
            <div class="step <?= $step >= 2 ? 'active' : '' ?> <?= $step > 2 ? 'completed' : '' ?>">2</div>
            <div class="step <?= $step >= 3 ? 'active' : '' ?> <?= $step > 3 ? 'completed' : '' ?>">3</div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
            <h2>Configuração do Banco de Dados</h2>
            <form method="POST">
                <input type="hidden" name="step" value="1">
                
                <div class="form-group">
                    <label for="host">Host do Banco</label>
                    <input type="text" id="host" name="host" class="form-control" 
                           value="<?= htmlspecialchars($_POST['host'] ?? 'localhost') ?>" required>
                    <div class="help-text">Geralmente "localhost" em hospedagens compartilhadas</div>
                </div>

                <div class="form-group">
                    <label for="database">Nome do Banco</label>
                    <input type="text" id="database" name="database" class="form-control" 
                           value="<?= htmlspecialchars($_POST['database'] ?? '') ?>" required>
                    <div class="help-text">Nome do banco de dados criado no painel da hospedagem</div>
                </div>

                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           value="<?= htmlspecialchars($_POST['password'] ?? '') ?>">
                </div>

                <button type="submit" class="btn">Configurar Banco</button>
            </form>

        <?php elseif ($step == 2): ?>
            <h2>Configuração do Administrador</h2>
            <form method="POST">
                <input type="hidden" name="step" value="2">
                
                <div class="form-group">
                    <label for="admin_name">Nome do Administrador</label>
                    <input type="text" id="admin_name" name="admin_name" class="form-control" 
                           value="<?= htmlspecialchars($_POST['admin_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="admin_email">Email do Administrador</label>
                    <input type="email" id="admin_email" name="admin_email" class="form-control" 
                           value="<?= htmlspecialchars($_POST['admin_email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="admin_password">Senha do Administrador</label>
                    <input type="password" id="admin_password" name="admin_password" class="form-control" 
                           minlength="8" required>
                    <div class="help-text">Mínimo 8 caracteres</div>
                </div>

                <div class="form-group">
                    <label for="openai_api_key">Chave API OpenAI (Opcional)</label>
                    <input type="password" id="openai_api_key" name="openai_api_key" class="form-control" 
                           placeholder="sk-proj-..." value="<?= htmlspecialchars($_POST['openai_api_key'] ?? '') ?>">
                    <div class="help-text">Pode ser configurada depois no painel admin</div>
                </div>

                <button type="submit" class="btn">Finalizar Instalação</button>
            </form>

        <?php elseif ($step == 3): ?>
            <h2>🎉 Instalação Concluída!</h2>
            <p style="text-align: center; margin: 16px 0; color: var(--verde-terapia); font-weight: 600;">
                Todas as tabelas foram criadas/atualizadas com sucesso!
            </p>
            <p style="text-align: center; margin: 24px 0; color: var(--cinza-escuro);">
                Seu sistema MegaFisio IA está pronto para uso!
            </p>
            
            <div class="success-box">
                <h3>Dados de Acesso:</h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($_POST['admin_email'] ?? '') ?></p>
                <p><strong>Senha:</strong> [A senha que você definiu]</p>
            </div>

            <div class="button-container">
                <a href="public/index.php" class="btn">Acessar Sistema</a>
                <button onclick="deleteInstaller()" class="btn btn-link">Remover Instalador</button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function deleteInstaller() {
            if (confirm('Tem certeza que deseja remover o instalador?\n\nIsso vai apagar o arquivo install.php para maior segurança.')) {
                fetch('?delete_installer=1')
                    .then(() => {
                        alert('Instalador removido com sucesso!');
                        window.location.href = 'public/index.php';
                    });
            }
        }
    </script>
</body>
</html>

<?php
// Opção para deletar instalador após instalação
if (isset($_GET['delete_installer']) && file_exists('config/installed.lock')) {
    unlink(__FILE__);
    exit('Instalador removido!');
}
?>