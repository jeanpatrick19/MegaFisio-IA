<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - MegaFisio IA</title>
    <meta name="theme-color" content="#0066cc">
    
    <!-- Fonts Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            /* Paleta Médica Premium Definitiva */
            --primary-blue: #0066cc;           /* Principal médico */
            --primary-teal: #00a693;           /* Secundário saúde */
            --accent-green: #28a745;           /* Sucesso médico */
            --soft-mint: #f0fdf9;             /* Fundo suave */
            --pure-white: #ffffff;            /* Branco puro */
            --light-gray: #f8fafc;            /* Cinza claro */
            --medium-gray: #e2e8f0;           /* Cinza médio */
            --text-dark: #1e293b;             /* Texto principal */
            --text-medium: #475569;           /* Texto secundário */
            --text-light: #94a3b8;            /* Texto suave */
            
            /* Gradientes Médicos Suaves */
            --gradient-main: linear-gradient(135deg, #0066cc 0%, #00a693 100%);
            --gradient-soft: linear-gradient(135deg, #f0fdf9 0%, #ffffff 100%);
            --gradient-card: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            
            /* Sombras Profissionais */
            --shadow-soft: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            /* Transições Suaves */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--soft-mint);
            color: var(--text-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Layout Moderno */
        .medical-dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Flutuante Ultra Premium */
        .premium-sidebar {
            position: fixed;
            left: 24px;
            top: 24px;
            bottom: 24px;
            width: 280px;
            background: var(--pure-white);
            border-radius: 20px;
            box-shadow: var(--shadow-large);
            z-index: 1000;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid var(--medium-gray);
        }

        .premium-sidebar:hover {
            box-shadow: var(--shadow-hover);
        }

        /* Header da Sidebar */
        .sidebar-header {
            padding: 32px 24px 24px;
            background: var(--gradient-main);
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            backdrop-filter: blur(10px);
        }

        .brand-text {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .brand-subtitle {
            font-size: 12px;
            opacity: 0.8;
            font-weight: 400;
            margin-top: 2px;
        }

        /* Menu de Navegação */
        .nav-menu {
            padding: 24px 0;
        }

        .nav-item {
            margin: 0 16px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-medium);
            text-decoration: none;
            border-radius: 12px;
            transition: var(--transition);
            font-weight: 500;
            font-size: 14px;
        }

        .nav-link:hover {
            background: var(--light-gray);
            color: var(--primary-blue);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: var(--gradient-main);
            color: white;
            box-shadow: var(--shadow-medium);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        /* Área Principal */
        .main-content {
            flex: 1;
            margin-left: 328px;
            padding: 24px 24px 24px 0;
        }

        /* Header Principal */
        .main-header {
            background: var(--pure-white);
            border-radius: 16px;
            padding: 24px 32px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-medium);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--medium-gray);
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.025em;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-light);
            margin-top: 4px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Botões Premium */
        .btn-premium {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gradient-main);
            color: white;
            box-shadow: var(--shadow-medium);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-large);
        }

        .btn-secondary {
            background: var(--pure-white);
            color: var(--text-medium);
            border: 1px solid var(--medium-gray);
        }

        .btn-secondary:hover {
            background: var(--light-gray);
            color: var(--primary-blue);
        }

        /* Cards Ultra Premium */
        .content-area {
            display: grid;
            gap: 24px;
        }

        .premium-card {
            background: var(--pure-white);
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--medium-gray);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-main);
            opacity: 0;
            transition: var(--transition);
        }

        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .premium-card:hover::before {
            opacity: 1;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-main);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        /* Estatísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--pure-white);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--medium-gray);
            transition: var(--transition);
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-large);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: var(--gradient-main);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-bottom: 16px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            font-family: 'JetBrains Mono', monospace;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 500;
        }

        .stat-trend {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .trend-up {
            background: rgba(40, 167, 69, 0.1);
            color: var(--accent-green);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .premium-sidebar {
                transform: translateX(-100%);
            }
            
            .premium-sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 24px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .main-header {
                padding: 20px;
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
            
            .premium-card {
                padding: 24px;
            }
        }

        /* Animações Sutis */
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 1001;
            background: var(--pure-white);
            border: none;
            border-radius: 12px;
            width: 48px;
            height: 48px;
            box-shadow: var(--shadow-medium);
            color: var(--primary-blue);
            font-size: 18px;
        }

        @media (max-width: 1200px) {
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Overlay para mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="medical-dashboard">
        <!-- Mobile Toggle -->
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Overlay para Mobile -->
        <div class="sidebar-overlay" onclick="closeSidebar()"></div>

        <!-- Sidebar Premium -->
        <aside class="premium-sidebar" id="sidebar">
            <!-- Header da Sidebar -->
            <div class="sidebar-header">
                <div class="brand-logo">
                    <div class="brand-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div>
                        <div class="brand-text">MegaFisio IA</div>
                        <div class="brand-subtitle">Sistema Médico</div>
                    </div>
                </div>
            </div>

            <!-- Menu de Navegação -->
            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="/dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fas fa-chart-line"></i></div>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/ai" class="nav-link <?= $currentPage === 'ai' ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fas fa-robot"></i></div>
                        <span>Assistente IA</span>
                    </a>
                </div>
                
                <?php if ($user['role'] === 'admin'): ?>
                <div class="nav-item">
                    <a href="/admin/users" class="nav-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fas fa-users"></i></div>
                        <span>Usuários</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/admin/data-cleanup" class="nav-link <?= $currentPage === 'cleanup' ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fas fa-broom"></i></div>
                        <span>Limpeza</span>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="nav-item">
                    <a href="/profile" class="nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fas fa-user"></i></div>
                        <span>Perfil</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="/settings" class="nav-link <?= $currentPage === 'settings' ? 'active' : '' ?>">
                        <div class="nav-icon"><i class="fas fa-cog"></i></div>
                        <span>Configurações</span>
                    </a>
                </div>
                
                <div class="nav-item" style="margin-top: auto; padding-top: 24px;">
                    <a href="/logout" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-sign-out-alt"></i></div>
                        <span>Sair</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Área Principal -->
        <main class="main-content">
            <!-- Header Principal -->
            <header class="main-header fade-in">
                <div>
                    <h1 class="page-title"><?= $title ?? 'Dashboard' ?></h1>
                    <p class="page-subtitle">Sistema de Fisioterapia com Inteligência Artificial</p>
                </div>
                
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-bell"></i>
                        Notificações
                    </button>
                    <a href="/ai" class="btn-premium btn-primary">
                        <i class="fas fa-brain"></i>
                        IA Médica
                    </a>
                </div>
            </header>

            <!-- Área de Conteúdo -->
            <div class="content-area fade-in">
                <?= $content ?>
            </div>
        </main>
    </div>

    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }

        // Smooth animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.premium-card, .stat-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('fade-in');
                        }, index * 100);
                    }
                });
            });

            cards.forEach(card => {
                observer.observe(card);
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.querySelector('.btn-primary').click();
            }
        });
    </script>
</body>
</html>