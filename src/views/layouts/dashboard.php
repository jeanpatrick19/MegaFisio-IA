<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - MegaFisio IA</title>
    <meta name="theme-color" content="#1e3a8a">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Sistema de Design Unificado -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/sistema-unificado.css">
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
            --preto-menu: #111827;
            
            /* Gradientes */
            --gradiente-principal: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            --gradiente-menu: linear-gradient(180deg, #111827 0%, #1f2937 100%);
            --gradiente-card: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            
            /* Sombras */
            --sombra-suave: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --sombra-media: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --sombra-forte: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            
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
            background: var(--cinza-claro);
            color: var(--cinza-escuro);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Layout Principal */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--gradiente-menu);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: var(--transicao);
            box-shadow: var(--sombra-forte);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-logo {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--branco-puro);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
        }

        .sidebar-logo-icon {
            width: 48px;
            height: 48px;
            background: var(--gradiente-principal);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .sidebar-menu {
            padding: 24px 0;
        }

        .menu-section {
            margin-bottom: 32px;
        }

        .menu-section-title {
            color: #9ca3af;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            padding: 0 24px;
            margin-bottom: 12px;
        }

        .menu-item {
            display: block;
            padding: 14px 24px;
            color: #d1d5db;
            text-decoration: none;
            transition: var(--transicao);
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-item:hover {
            background: rgba(30, 58, 138, 0.1);
            color: var(--branco-puro);
            padding-left: 32px;
        }

        .menu-item.active {
            background: var(--gradiente-principal);
            color: var(--branco-puro);
            border-right: 4px solid var(--verde-terapia);
            padding-left: 32px;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        /* Conteúdo Principal */
        .main-content {
            flex: 1;
            margin-left: 280px;
            background: var(--cinza-claro);
            min-height: 100vh;
            transition: var(--transicao);
        }

        .main-content.sidebar-collapsed {
            margin-left: 0;
        }

        /* Header do Dashboard */
        .dashboard-header {
            background: var(--branco-puro);
            border-bottom: 1px solid var(--cinza-medio);
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--sombra-suave);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: var(--cinza-escuro);
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: var(--transicao);
        }

        .menu-toggle:hover {
            background: var(--cinza-medio);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--azul-saude);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .data-hora {
            font-size: 14px;
            color: var(--cinza-escuro);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(30, 58, 138, 0.05);
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid rgba(30, 58, 138, 0.1);
        }
        
        .data-hora i {
            color: var(--azul-saude);
            font-size: 16px;
        }


        /* Conteúdo da Página */
        .page-content {
            padding: 32px;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .dashboard-header {
                padding: 16px 20px;
            }

            .page-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.2rem;
            }
            
            .data-hora {
                display: none;
            }
        }

        /* Overlay para mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Estados especiais */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            font-weight: 500;
            transition: var(--transicao);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--gradiente-principal);
            color: white;
            box-shadow: var(--sombra-media);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--sombra-forte);
        }

        .btn-secondary {
            background: var(--cinza-medio);
            color: var(--cinza-escuro);
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        /* Notifications e alerts */
        .notification {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid;
        }

        .notification.success {
            background: rgba(5, 150, 105, 0.1);
            border-color: var(--verde-terapia);
            color: var(--verde-terapia);
        }

        .notification.error {
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
            color: #ef4444;
        }

        .notification.warning {
            background: rgba(202, 138, 4, 0.1);
            border-color: var(--dourado-premium);
            color: var(--dourado-premium);
        }

        .notification.info {
            background: rgba(30, 58, 138, 0.1);
            border-color: var(--azul-saude);
            color: var(--azul-saude);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/dashboard" class="sidebar-logo">
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/dashboard" class="sidebar-logo">
                <?php endif; ?>
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-hand-holding-medical"></i>
                    </div>
                    <div>
                        <div>MegaFisio IA</div>
                        <small style="font-size: 0.7rem; color: #9ca3af;">Inteligência para Fisioterapia</small>
                    </div>
                </a>
            </div>

            <div class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Principal</div>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="menu-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                            <i class="fas fa-cogs"></i>
                            Administração
                        </a>
                        <a href="<?= BASE_URL ?>/admin/ai" class="menu-item <?= $currentPage === 'ai' ? 'active' : '' ?>">
                            <i class="fas fa-brain"></i>
                            IA do Sistema
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="menu-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                            <i class="fas fa-chart-line"></i>
                            Dashboard
                        </a>
                        <a href="<?= BASE_URL ?>/ai" class="menu-item <?= $currentPage === 'ai' ? 'active' : '' ?>">
                            <i class="fas fa-brain"></i>
                            Assistente IA
                        </a>
                    <?php endif; ?>
                </div>

                <?php if ($user['role'] === 'admin'): ?>
                <div class="menu-section">
                    <div class="menu-section-title">Administração</div>
                    <a href="<?= BASE_URL ?>/admin/users" class="menu-item <?= $currentPage === 'users' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i>
                        Usuários
                    </a>
                    <a href="<?= BASE_URL ?>/admin/settings" class="menu-item <?= $currentPage === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i>
                        Configurações
                    </a>
                </div>
                <?php endif; ?>

                <div class="menu-section">
                    <div class="menu-section-title">Conta</div>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin/profile" class="menu-item <?= $currentPage === 'profile' ? 'active' : '' ?>">
                            <i class="fas fa-user-shield"></i>
                            Perfil Admin
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/profile" class="menu-item <?= $currentPage === 'profile' ? 'active' : '' ?>">
                            <i class="fas fa-user"></i>
                            Meu Perfil
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/logout" class="menu-item">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </div>
            </div>
        </nav>

        <!-- Overlay para mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Conteúdo Principal -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?= $pageTitle ?? $title ?? 'Dashboard' ?></h1>
                </div>

                <div class="header-right">
                    <button class="notificacoes" data-tooltip="Você tem 3 novas notificações" style="background: none; border: none; font-size: 20px; color: var(--cinza-escuro); cursor: pointer; padding: 8px; border-radius: 8px; transition: var(--transicao); position: relative;">
                        <i class="fas fa-bell"></i>
                        <span style="position: absolute; top: 0; right: 0; background: var(--erro); color: white; font-size: 10px; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">3</span>
                    </button>
                    
                    <div class="data-hora" id="dataHora">
                        <i class="fas fa-calendar-alt"></i>
                        <span id="dataHoraTexto"></span>
                    </div>
                </div>
            </header>

            <!-- Conteúdo da Página -->
            <div class="page-content">
                <div class="content-wrapper">
                    <?= $content ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle sidebar
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('mobile-open');
                sidebarOverlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
            }
        });

        // Fechar sidebar no mobile ao clicar no overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        });

        // Fechar sidebar no mobile ao redimensionar
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('active');
                mainContent.classList.remove('sidebar-collapsed');
                sidebar.classList.remove('collapsed');
            }
        });

        // Atualizar data e hora em tempo real - Timezone São Paulo
        function atualizarDataHora() {
            const agora = new Date();
            const opcoes = {
                timeZone: 'America/Sao_Paulo',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            
            const dataHoraFormatada = agora.toLocaleString('pt-BR', opcoes);
            const dataHoraElement = document.getElementById('dataHoraTexto');
            if (dataHoraElement) {
                dataHoraElement.textContent = dataHoraFormatada;
            }
        }

        // Atualizar a cada segundo
        atualizarDataHora();
        setInterval(atualizarDataHora, 1000);

        // Smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>