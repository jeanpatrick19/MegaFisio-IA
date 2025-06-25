<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MegaFisio IA | Sistema Inteligente para Fisioterapia</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            /* Paleta Profissional de Saúde */
            --azul-saude: #1e3a8a;
            --verde-terapia: #059669;
            --dourado-premium: #ca8a04;
            --branco-puro: #ffffff;
            --cinza-claro: #f8fafc;
            --cinza-medio: #e5e7eb;
            --cinza-escuro: #1f2937;
            --erro: #ef4444;
            
            /* Gradientes */
            --gradiente-principal: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            --gradiente-fundo: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
            
            /* Sombras */
            --sombra-suave: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --sombra-media: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --sombra-forte: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --sombra-flutuante: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            
            /* Transições */
            --transicao: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            -moz-osx-font-smoothing: grayscale;
        }

        .login-container {
            background: var(--branco-puro);
            border-radius: 24px;
            padding: 48px;
            box-shadow: var(--sombra-flutuante);
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--cinza-medio);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 24px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--gradiente-principal);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            margin-bottom: 16px;
            box-shadow: var(--sombra-media);
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--azul-saude);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--erro);
            color: var(--erro);
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-group {
            margin-bottom: 24px;
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
            transition: var(--transicao);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--azul-saude);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-control::placeholder {
            color: var(--cinza-medio);
        }

        .btn-login {
            width: 100%;
            padding: 16px 24px;
            background: var(--gradiente-principal);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transicao);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: var(--sombra-media);
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--sombra-forte);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .login-footer {
            margin-top: 32px;
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid var(--cinza-medio);
        }

        .login-footer a {
            color: var(--azul-saude);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transicao);
        }

        .login-footer a:hover {
            color: var(--verde-terapia);
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
            }
            
            .logo {
                font-size: 24px;
            }
            
            .logo-icon {
                width: 64px;
                height: 64px;
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="logo">MegaFisio IA</div>
                <div class="subtitle">Inteligência para Fisioterapia</div>
            </div>
        </div>

        <?php if (isset($errors['general'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($errors['general']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" 
                       placeholder="seu@email.com" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Sua senha" required>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span class="btn-text">Entrar no Sistema</span>
                <div class="loading-spinner" id="loadingSpinner"></div>
            </button>
        </form>

        <div class="login-footer">
            <a href="<?= BASE_URL ?>/forgot-password">Esqueceu sua senha?</a>
        </div>
    </div>

    <script>
        // Gerenciar estado do formulário
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.querySelector('.btn-text');
        const loadingSpinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos.');
                return;
            }
            
            // Mostrar loading
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            loadingSpinner.style.display = 'block';
        });

        // Auto-foco no primeiro campo
        document.getElementById('email').focus();
    </script>
</body>
</html>