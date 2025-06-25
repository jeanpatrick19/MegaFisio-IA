<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema - MegaFisio IA</title>
    <meta name="theme-color" content="#1e3a8a">
    <meta name="description" content="Sistema de gestão e inteligência artificial para fisioterapeutas">
    
    <!-- Fonts Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            /* Paleta Profissional de Saúde */
            --azul-saude: #1e3a8a;
            --verde-terapia: #059669;
            --dourado-premium: #ca8a04;
            --lilas-cuidado: #7c3aed;
            --branco-puro: #ffffff;
            --cinza-claro: #f8fafc;
            --cinza-medio: #e5e7eb;
            --cinza-escuro: #1f2937;
            --preto-menu: #111827;
            
            /* Cores de Status */
            --sucesso: #10b981;
            --alerta: #f59e0b;
            --erro: #ef4444;
            --info: #3b82f6;
            
            /* Gradientes */
            --gradiente-principal: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            --gradiente-fisio: linear-gradient(135deg, #059669 0%, #10b981 100%);
            
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
            font-size: 16px;
            background: var(--cinza-claro);
            color: var(--cinza-escuro);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Animado */
        .login-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: 
                radial-gradient(circle at 20% 80%, rgba(5, 150, 105, 0.06) 0%, transparent 60%),
                radial-gradient(circle at 80% 20%, rgba(30, 58, 138, 0.05) 0%, transparent 60%),
                radial-gradient(circle at 40% 50%, rgba(124, 58, 237, 0.04) 0%, transparent 60%),
                linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            animation: float-bg 20s ease-in-out infinite;
        }

        @keyframes float-bg {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Container Principal */
        .login-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 600px;
            background: var(--branco-puro);
            border-radius: 24px;
            box-shadow: var(--sombra-flutuante);
            overflow: hidden;
            position: relative;
        }

        /* Lado Esquerdo - Branding */
        .login-branding {
            flex: 1;
            background: var(--gradiente-principal);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float-circle 15s ease-in-out infinite;
        }

        @keyframes float-circle {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .brand-logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            margin-bottom: 32px;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .brand-title {
            font-size: 36px;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .brand-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* Lado Direito - Formulário */
        .login-form-container {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 32px;
            font-weight: 800;
            color: var(--cinza-escuro);
            margin-bottom: 12px;
        }

        .form-subtitle {
            font-size: 16px;
            color: var(--cinza-medio);
            font-weight: 500;
        }

        /* Formulário */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-grupo {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-grupo label {
            font-weight: 600;
            color: var(--cinza-escuro);
            font-size: 14px;
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cinza-medio);
            font-size: 18px;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px 16px 52px;
            border: 2px solid var(--cinza-medio);
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            transition: var(--transicao);
            background: var(--branco-puro);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--azul-saude);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-input::placeholder {
            color: var(--cinza-medio);
        }

        /* Opções do Formulário */
        .form-opcoes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-custom {
            width: 18px;
            height: 18px;
            border: 2px solid var(--cinza-medio);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transicao);
        }

        .checkbox-container input:checked + .checkbox-custom {
            background: var(--azul-saude);
            border-color: var(--azul-saude);
        }

        .checkbox-container input:checked + .checkbox-custom::after {
            content: '✓';
            color: white;
            font-weight: 700;
            font-size: 12px;
        }

        .checkbox-container input {
            display: none;
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--cinza-escuro);
        }

        .forgot-link {
            font-size: 14px;
            color: var(--azul-saude);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Botões */
        .btn-login {
            width: 100%;
            padding: 16px 24px;
            background: var(--gradiente-principal);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transicao);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--sombra-forte);
        }

        .btn-login:disabled {
            background: var(--cinza-medio);
            cursor: not-allowed;
            transform: none;
        }

        /* Links Auxiliares */
        .form-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--cinza-medio);
        }

        .footer-text {
            font-size: 14px;
            color: var(--cinza-medio);
            margin-bottom: 16px;
        }

        .register-link {
            color: var(--azul-saude);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        /* Alertas */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--erro);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--sucesso);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        /* Loading */
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsivo */
        @media (max-width: 1024px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
                margin: 20px;
            }
            
            .login-branding {
                padding: 40px 20px;
            }
            
            .brand-features {
                flex-direction: row;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .login-form-container {
                padding: 40px 20px;
            }
        }

        @media (max-width: 768px) {
            .form-title {
                font-size: 28px;
            }
            
            .brand-title {
                font-size: 28px;
            }
            
            .form-opcoes {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="login-bg"></div>
    
    <div class="login-container">
        <!-- Branding -->
        <div class="login-branding">
            <div class="brand-logo">
                <i class="fas fa-hand-holding-medical"></i>
            </div>
            
            <h1 class="brand-title">MegaFisio IA</h1>
            <p class="brand-subtitle">Inteligência Artificial para Fisioterapia</p>
            
            <div class="brand-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <span>IA Especializada</span>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span>Dados Seguros</span>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span>Análises Precisas</span>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span>Disponível 24/7</span>
                </div>
            </div>
        </div>
        
        <!-- Formulário -->
        <div class="login-form-container">
            <div class="form-header">
                <h2 class="form-title">Acesso ao Sistema</h2>
                <p class="form-subtitle">Entre com suas credenciais para continuar</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <form class="login-form" method="POST" action="/login">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-grupo">
                    <label for="email">Email</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-envelope"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-input"
                               placeholder="seu@email.com.br"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required>
                    </div>
                </div>
                
                <div class="form-grupo">
                    <label for="password">Senha</label>
                    <div class="input-container">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input"
                               placeholder="Digite sua senha"
                               required>
                    </div>
                </div>
                
                <div class="form-opcoes">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" id="remember">
                        <div class="checkbox-custom"></div>
                        <span class="checkbox-label">Manter conectado</span>
                    </label>
                    
                    <a href="/forgot-password" class="forgot-link">Esqueci minha senha</a>
                </div>
                
                <button type="submit" class="btn-login" id="btnLogin">
                    <span class="btn-text">Entrar no Sistema</span>
                    <div class="loading-spinner" style="display: none;"></div>
                </button>
            </form>
            
            <div class="form-footer">
                <p class="footer-text">Ainda não tem acesso ao sistema?</p>
                <a href="/register" class="register-link">Solicitar cadastro como fisioterapeuta</a>
            </div>
        </div>
    </div>
    
    <script>
        // Animação de loading no formulário
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const btn = document.getElementById('btnLogin');
            const btnText = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.loading-spinner');
            
            // Validação básica
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                return;
            }
            
            // Mostrar loading
            btn.disabled = true;
            btnText.textContent = 'Verificando...';
            spinner.style.display = 'block';
        });
        
        // Focar no primeiro campo
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
        
        // Efeito visual nos inputs
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>