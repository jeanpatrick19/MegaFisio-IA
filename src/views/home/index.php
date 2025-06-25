<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MegaFisio IA - Sistema Inteligente de Fisioterapia</title>
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
            --gradiente-card: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            
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
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 80px;
            max-width: 1200px;
            width: 100%;
            align-items: center;
        }

        .welcome-section {
            text-align: left;
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 32px;
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
            margin-right: 20px;
            box-shadow: var(--sombra-media);
        }

        .logo {
            font-size: 3.2rem;
            font-weight: 800;
            color: var(--azul-saude);
            text-shadow: none;
            letter-spacing: -1px;
        }

        .logo-subtitle {
            color: var(--verde-terapia);
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .tagline {
            font-size: 1.6rem;
            color: var(--cinza-escuro);
            margin-bottom: 40px;
            line-height: 1.4;
            font-weight: 500;
        }

        .features {
            list-style: none;
            margin-bottom: 40px;
        }

        .features li {
            color: var(--cinza-escuro);
            margin-bottom: 16px;
            font-size: 1.1rem;
            position: relative;
            padding-left: 35px;
            font-weight: 500;
        }

        .features li:before {
            content: "\f058";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--verde-terapia);
            font-size: 1.2rem;
        }

        .login-card {
            background: var(--gradiente-card);
            border: 1px solid var(--cinza-medio);
            border-radius: 24px;
            padding: 48px;
            box-shadow: var(--sombra-flutuante);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h2 {
            color: var(--azul-saude);
            margin-bottom: 12px;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .login-header p {
            color: #6b7280;
            font-size: 0.95rem;
            font-weight: 500;
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
            color: #9ca3af;
        }

        .btn-primary {
            width: 100%;
            padding: 16px 24px;
            background: var(--gradiente-principal);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transicao);
            font-family: inherit;
            box-shadow: var(--sombra-media);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--sombra-forte);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .forgot-password {
            text-align: center;
            margin-top: 24px;
        }

        .forgot-password a {
            color: var(--azul-saude);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transicao);
        }

        .forgot-password a:hover {
            color: var(--verde-terapia);
        }

        .register-link {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--cinza-medio);
        }

        .register-link p {
            color: #6b7280;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .register-link a {
            color: var(--verde-terapia);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transicao);
        }

        .register-link a:hover {
            color: var(--azul-saude);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
            padding-top: 40px;
            border-top: 1px solid var(--cinza-medio);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--azul-saude);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 50px;
                text-align: center;
            }
            
            .welcome-section {
                text-align: center;
            }

            .logo-container {
                justify-content: center;
            }
            
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 32px 24px;
            }
            
            .logo {
                font-size: 2.5rem;
            }
            
            .tagline {
                font-size: 1.3rem;
            }

            .stats-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        @media (max-width: 480px) {
            .logo {
                font-size: 2rem;
            }
            
            .tagline {
                font-size: 1.1rem;
            }

            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <div class="welcome-section">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-hand-holding-medical"></i>
                    </div>
                    <div>
                        <div class="logo">MegaFisio IA</div>
                        <div class="logo-subtitle">Inteligência para Fisioterapia</div>
                    </div>
                </div>
                
                <div class="tagline">
                    Revolucione sua prática fisioterapêutica com inteligência artificial
                </div>
                
                <ul class="features">
                    <li>Análises personalizadas de pacientes</li>
                    <li>Protocolos de tratamento otimizados</li>
                    <li>Acompanhamento inteligente de evolução</li>
                    <li>Relatórios automáticos detalhados</li>
                    <li>Interface moderna e intuitiva</li>
                </ul>

                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Pacientes Atendidos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Fisioterapeutas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfação</div>
                    </div>
                </div>
            </div>

            <div class="login-card">
                <div class="login-header">
                    <h2>Acesso ao Sistema</h2>
                    <p>Entre com suas credenciais para continuar</p>
                </div>

                <form method="POST" action="<?= BASE_URL ?>/login" id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               placeholder="seu@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" class="form-control" 
                               placeholder="Sua senha" required>
                    </div>

                    <button type="submit" class="btn-primary" id="submitBtn">
                        <span class="btn-text">Entrar no Sistema</span>
                        <i class="fas fa-arrow-right" id="btnIcon"></i>
                    </button>
                </form>

                <div class="forgot-password">
                    <a href="<?= BASE_URL ?>/forgot-password">Esqueceu sua senha?</a>
                </div>

                <div class="register-link">
                    <p>Ainda não tem acesso?</p>
                    <a href="<?= BASE_URL ?>/register">Solicitar cadastro</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gerenciar estado do formulário
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.querySelector('.btn-text');
        const btnIcon = document.getElementById('btnIcon');

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
            btnText.textContent = 'Entrando...';
            btnIcon.className = 'fas fa-spinner fa-spin';
        });

        // Auto-foco no primeiro campo
        document.getElementById('email').focus();

        // Animação suave nos inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>