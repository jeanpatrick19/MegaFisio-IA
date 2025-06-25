<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - MegaFisio IA</title>
    <meta name="theme-color" content="#6366f1">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Paleta Principal - Médica/Tecnológica */
            --primary: #00d4aa;          /* Teal médico moderno */
            --primary-dark: #00b894;     /* Teal escuro */
            --primary-light: #55efc4;    /* Teal claro */
            
            --secondary: #0984e3;        /* Azul médico confiável */
            --secondary-dark: #0652dd;   /* Azul escuro */
            --secondary-light: #74b9ff;  /* Azul claro */
            
            --accent: #fd79a8;           /* Rosa suave para destaques */
            --accent-dark: #e84393;      /* Rosa escuro */
            
            --success: #00b894;          /* Verde sucesso */
            --warning: #fdcb6e;          /* Amarelo aviso */
            --danger: #e17055;           /* Vermelho erro */
            
            /* Tons de Cinza Modernos */
            --dark: #2d3436;             /* Cinza escuro principal */
            --dark-lighter: #636e72;     /* Cinza médio */
            --dark-card: #ddd;           /* Cards claros */
            --light-bg: #f8f9fa;         /* Fundo claro */
            --white: #ffffff;            /* Branco puro */
            
            /* Texto */
            --text-primary: #2d3436;     /* Texto principal escuro */
            --text-secondary: #636e72;   /* Texto secundário */
            --text-muted: #b2bec3;       /* Texto discreto */
            --text-white: #ffffff;       /* Texto branco */
            
            /* Bordas e Sombras */
            --border: #ddd;              /* Bordas sutis */
            --border-light: #f1f2f6;     /* Bordas muito claras */
            --shadow: rgba(45, 52, 54, 0.1);     /* Sombra suave */
            --shadow-hover: rgba(45, 52, 54, 0.2); /* Sombra hover */
            --glow: rgba(0, 212, 170, 0.3);       /* Brilho primary */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.6;
        }

        /* Background sutil e elegante */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            opacity: 0.02;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, var(--primary) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, var(--secondary) 0%, transparent 40%),
                radial-gradient(circle at 40% 40%, var(--accent) 0%, transparent 40%);
            animation: float 30s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.02; }
            50% { transform: translateY(-10px) rotate(2deg); opacity: 0.05; }
        }

        /* Layout Principal */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar Moderna */
        .sidebar {
            width: 320px;
            background: var(--white);
            border-right: 1px solid var(--border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 2px 0 12px var(--shadow);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* Header da Sidebar */
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: var(--white);
            position: relative;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--text-white);
            box-shadow: 0 4px 12px var(--glow);
        }

        .logo-text {
            color: var(--text-primary);
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-text p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Navegação */
        .nav-section {
            padding: 1rem 0;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
        }

        .nav-items {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.125rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.25s ease;
            border-radius: 0 25px 25px 0;
            margin-right: 1rem;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary);
            background: rgba(0, 212, 170, 0.08);
            transform: translateX(6px);
        }

        .nav-link.active {
            color: var(--primary);
            background: linear-gradient(90deg, rgba(0, 212, 170, 0.15) 0%, rgba(0, 212, 170, 0.05) 100%);
            border-left: 3px solid var(--primary);
            font-weight: 600;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .nav-text {
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Submenu IA */
        .ai-submenu {
            background: rgba(0, 212, 170, 0.05);
            border-radius: 12px;
            margin: 0.5rem 1rem;
            padding: 0.75rem 0;
            border: 1px solid rgba(0, 212, 170, 0.1);
        }

        .ai-submenu .nav-link {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            margin: 0;
            border-radius: 8px;
        }

        .ai-submenu .nav-icon {
            font-size: 0.9rem;
        }

        /* Conteúdo Principal */
        .main-content {
            flex: 1;
            margin-left: 320px;
            background: var(--light-bg);
            min-height: 100vh;
            transition: margin-left 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Header Principal */
        .top-header {
            background: var(--white);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px var(--shadow);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.25s ease;
        }

        .menu-toggle:hover {
            background: rgba(0, 212, 170, 0.1);
            color: var(--primary);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .time-display {
            background: rgba(0, 212, 170, 0.08);
            border: 1px solid rgba(0, 212, 170, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .user-menu {
            position: relative;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px var(--glow);
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px var(--glow);
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cards Modernos */
        .card {
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px var(--shadow);
            transition: all 0.25s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--shadow-hover);
            border-color: var(--primary);
        }

        /* Alertas */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            border-left: 4px solid;
            font-weight: 500;
            background: var(--white);
            box-shadow: 0 2px 8px var(--shadow);
        }

        .alert-success {
            border-left-color: var(--success);
            background: rgba(0, 184, 148, 0.05);
            color: var(--success);
        }

        .alert-error {
            border-left-color: var(--danger);
            background: rgba(225, 112, 85, 0.05);
            color: var(--danger);
        }

        .alert-warning {
            border-left-color: var(--warning);
            background: rgba(253, 203, 110, 0.05);
            color: var(--warning);
        }

        /* Responsivo */
        @media (max-width: 1024px) {
            .sidebar {
                width: 280px;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }

        @media (max-width: 768px) {
            .top-header {
                padding: 1rem;
            }
            
            .content-area {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 1.25rem;
            }
            
            .time-display {
                display: none;
            }
        }

        /* Overlay para mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Glowing effect */
        .glow {
            box-shadow: 0 0 20px var(--primary);
        }

        /* Pulso de notificação */
        .notification-pulse {
            position: relative;
        }

        .notification-pulse::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
            
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="logo-text">
                        <h1>MegaFisio</h1>
                        <p>Inteligência Artificial</p>
                    </div>
                </div>
            </div>
            
            <!-- Navegação Principal -->
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/profile" class="nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-user"></i></span>
                            <span class="nav-text">Perfil</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- IA Especializada -->
            <div class="nav-section">
                <div class="nav-section-title">IA Especializada</div>
                <div class="ai-submenu">
                    <?php if (!empty($aiPrompts)): ?>
                        <?php foreach ($aiPrompts as $prompt): ?>
                            <a href="/ai/<?= htmlspecialchars($prompt['slug'] ?? strtolower(str_replace(' ', '-', $prompt['name']))) ?>" 
                               class="nav-link">
                                <span class="nav-icon">
                                    <?php
                                    $icons = [
                                        'avaliacao' => 'fa-stethoscope',
                                        'exercicio' => 'fa-dumbbell', 
                                        'cid' => 'fa-file-medical',
                                        'neurologic' => 'fa-brain',
                                        'ortopedic' => 'fa-bone',
                                        'cardio' => 'fa-heartbeat'
                                    ];
                                    $key = strtolower($prompt['slug'] ?? $prompt['name']);
                                    $icon = 'fa-robot';
                                    foreach ($icons as $k => $i) {
                                        if (strpos($key, $k) !== false) {
                                            $icon = $i;
                                            break;
                                        }
                                    }
                                    ?>
                                    <i class="fas <?= $icon ?>"></i>
                                </span>
                                <span class="nav-text"><?= htmlspecialchars($prompt['name']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a href="/admin/prompts" class="nav-link">
                            <span class="nav-icon"><i class="fas fa-plus"></i></span>
                            <span class="nav-text">Configurar IA</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Administração -->
            <?php if ($user['role'] === 'admin'): ?>
            <div class="nav-section">
                <div class="nav-section-title">Administração</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="/admin/users" class="nav-link <?= $currentPage === 'admin-users' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-users"></i></span>
                            <span class="nav-text">Usuários</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/prompts" class="nav-link <?= $currentPage === 'admin-prompts' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-robot"></i></span>
                            <span class="nav-text">Prompts IA</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/data-cleanup" class="nav-link <?= $currentPage === 'admin-cleanup' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-broom"></i></span>
                            <span class="nav-text">Limpeza de Dados</span>
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Configurações -->
            <div class="nav-section">
                <div class="nav-section-title">Sistema</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="/password/change" class="nav-link">
                            <span class="nav-icon"><i class="fas fa-key"></i></span>
                            <span class="nav-text">Alterar Senha</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="nav-link">
                            <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                            <span class="nav-text">Sair</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Conteúdo Principal -->
        <main class="main-content" id="mainContent">
            <!-- Header Superior -->
            <header class="top-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?= htmlspecialchars($title ?? 'Dashboard') ?></h1>
                </div>
                
                <div class="header-right">
                    <div class="time-display" id="timeDisplay">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                    
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Área de Conteúdo -->
            <div class="content-area">
                <?php
                // Flash messages
                $flash = $_SESSION['flash'] ?? null;
                unset($_SESSION['flash']);
                
                if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>" style="margin-bottom: 2rem;">
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>
                
                <?= $content ?>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        });
        
        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
        
        // Time display
        function updateTime() {
            const now = new Date();
            const options = {
                timeZone: 'America/Sao_Paulo',
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            
            document.getElementById('timeDisplay').textContent = 
                now.toLocaleDateString('pt-BR', options);
        }
        
        updateTime();
        setInterval(updateTime, 60000); // Update every minute
        
        // Responsive handling
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>