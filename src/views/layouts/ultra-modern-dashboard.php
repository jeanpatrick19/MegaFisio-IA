<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - MegaFisio IA</title>
    <meta name="theme-color" content="#6c5ce7">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            /* Paleta Médica Premium */
            --medical-blue: #0066cc;       /* Azul médico confiável */
            --medical-teal: #00a693;       /* Verde-azul saúde */
            --medical-green: #28a745;      /* Verde vida/sucesso */
            --medical-mint: #e8f5f3;       /* Verde menta suave */
            --medical-sky: #f0f8ff;        /* Azul céu hospitalar */
            
            /* Tons Profissionais */
            --steel-blue: #2c5aa0;         /* Azul aço médico */
            --soft-gray: #f8f9fa;          /* Cinza suave */
            --warm-white: #fdfdfd;         /* Branco acolhedor */
            --platinum: #e8eaed;           /* Platina médica */
            --deep-navy: #1a365d;          /* Azul marinho profundo */
            
            /* Gradientes Médicos */
            --gradient-primary: linear-gradient(135deg, #0066cc 0%, #2c5aa0 100%);
            --gradient-secondary: linear-gradient(135deg, #00a693 0%, #28a745 100%);
            --gradient-healing: linear-gradient(135deg, #e8f5f3 0%, #f0f8ff 100%);
            --gradient-trust: linear-gradient(135deg, #2c5aa0 0%, #1a365d 100%);
            
            /* Backgrounds Clínicos */
            --bg-primary: var(--warm-white);      /* Fundo principal */
            --bg-secondary: var(--medical-sky);   /* Fundo secundário */
            --bg-card: var(--warm-white);         /* Cards */
            --bg-sidebar: var(--warm-white);      /* Sidebar */
            --bg-accent: var(--medical-mint);     /* Destaques */
            
            /* Textos Médicos */
            --text-primary: var(--deep-navy);     /* Texto principal */
            --text-secondary: #4a5568;            /* Texto secundário */
            --text-muted: #718096;                /* Texto discreto */
            --text-white: #ffffff;                /* Texto branco */
            --text-accent: var(--medical-blue);   /* Texto destaque */
            
            /* Cores de Status Médico */
            --status-healthy: var(--medical-green);    /* Saudável */
            --status-warning: #f6ad55;                 /* Atenção */
            --status-critical: #e53e3e;               /* Crítico */
            --status-info: var(--medical-blue);       /* Informação */
            
            /* Efeitos Clínicos */
            --shadow-soft: 0 2px 15px rgba(44, 90, 160, 0.08);
            --shadow-medium: 0 4px 25px rgba(44, 90, 160, 0.12);
            --shadow-strong: 0 8px 35px rgba(44, 90, 160, 0.15);
            --glow-medical: 0 0 25px rgba(0, 166, 147, 0.2);
            --glow-trust: 0 0 20px rgba(0, 102, 204, 0.15);
            
            /* Bordas Médicas */
            --border-soft: #e2e8f0;               /* Bordas suaves */
            --border-medium: #cbd5e0;             /* Bordas médias */
            --border-accent: var(--medical-teal); /* Bordas coloridas */
            
            /* Animações Suaves */
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            --transition-medical: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-secondary);
            color: var(--text-primary);
            overflow-x: hidden;
            line-height: 1.7;
            position: relative;
        }

        /* Background Médico Acolhedor */
        .cyber-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 166, 147, 0.04) 0%, transparent 60%),
                radial-gradient(circle at 80% 20%, rgba(0, 102, 204, 0.03) 0%, transparent 60%),
                radial-gradient(circle at 40% 50%, rgba(40, 167, 69, 0.02) 0%, transparent 60%),
                var(--gradient-healing);
            animation: breathe 8s ease-in-out infinite alternate;
        }

        @keyframes breathe {
            0% { opacity: 0.9; }
            100% { opacity: 1; }
        }

        /* Padrão Médico Sutil */
        .hex-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.015;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2300a693' fill-opacity='0.03'%3E%3Ccircle cx='40' cy='40' r='2'/%3E%3Ccircle cx='20' cy='20' r='1'/%3E%3Ccircle cx='60' cy='20' r='1'/%3E%3Ccircle cx='20' cy='60' r='1'/%3E%3Ccircle cx='60' cy='60' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: float-medical 40s ease-in-out infinite;
        }

        @keyframes float-medical {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        /* Layout Principal */
        .cyber-dashboard {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar Flutuante Clean */
        .floating-sidebar {
            position: fixed;
            left: 20px;
            top: 20px;
            bottom: 20px;
            width: 80px;
            background: var(--bg-elevated);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-light);
            border-radius: 25px;
            z-index: 1000;
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-light);
            overflow: hidden;
        }

        .floating-sidebar.expanded {
            width: 320px;
            box-shadow: var(--shadow-medium);
            border-color: var(--border-medium);
        }

        /* Logo Cyberpunk */
        .cyber-logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 245, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .cyber-logo::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 245, 255, 0.1), transparent);
            animation: scan 3s infinite;
        }

        @keyframes scan {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            background: var(--gradient-secondary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            box-shadow: var(--glow-cyan);
            position: relative;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            inset: -2px;
            background: var(--gradient-secondary);
            border-radius: 14px;
            z-index: -1;
            filter: blur(4px);
            opacity: 0.7;
        }

        .logo-text {
            opacity: 0;
            transform: translateX(-20px);
            transition: var(--transition-smooth);
            font-family: 'JetBrains Mono', monospace;
        }

        .floating-sidebar.expanded .logo-text {
            opacity: 1;
            transform: translateX(0);
        }

        .logo-text h1 {
            font-size: 1.1rem;
            font-weight: 700;
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .logo-text p {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        /* Navegação Minimalista */
        .cyber-nav {
            padding: 20px 0;
            flex: 1;
        }

        .nav-section {
            margin-bottom: 30px;
        }

        .nav-section-title {
            padding: 0 20px 10px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            opacity: 0;
            transform: translateX(-20px);
            transition: var(--transition-smooth);
        }

        .floating-sidebar.expanded .nav-section-title {
            opacity: 1;
            transform: translateX(0);
        }

        .nav-item {
            position: relative;
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--text-cyber);
            text-decoration: none;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            border-radius: 0 20px 20px 0;
            margin-right: 15px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: var(--gradient-secondary);
            transform: scaleY(0);
            transition: var(--transition-smooth);
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link:hover {
            background: rgba(0, 245, 255, 0.05);
            color: var(--text-glow);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(108, 92, 231, 0.1);
            color: var(--neon-purple);
            box-shadow: inset var(--glow-purple);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            min-width: 20px;
        }

        .nav-text {
            margin-left: 15px;
            font-weight: 500;
            font-size: 0.9rem;
            opacity: 0;
            transform: translateX(-20px);
            transition: var(--transition-smooth);
            white-space: nowrap;
        }

        .floating-sidebar.expanded .nav-text {
            opacity: 1;
            transform: translateX(0);
        }

        /* AI Modules Especiais */
        .ai-modules {
            background: rgba(108, 92, 231, 0.05);
            border-radius: 15px;
            margin: 10px 15px;
            padding: 15px 0;
            border: 1px solid rgba(108, 92, 231, 0.1);
            backdrop-filter: blur(10px);
        }

        .ai-module {
            padding: 8px 15px;
            margin: 2px 0;
            transition: var(--transition-smooth);
            border-radius: 10px;
        }

        .ai-module:hover {
            background: rgba(108, 92, 231, 0.1);
            transform: translateX(3px);
        }

        /* Toggle Button Futurista */
        .sidebar-toggle {
            position: fixed;
            left: 120px;
            top: 40px;
            z-index: 1001;
            width: 50px;
            height: 50px;
            background: var(--glass-dark);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-bounce);
            box-shadow: var(--glow-cyan);
            color: var(--text-glow);
        }

        .sidebar-toggle:hover {
            transform: scale(1.1) rotate(180deg);
            box-shadow: var(--glow-purple);
            border-color: rgba(108, 92, 231, 0.3);
        }

        .floating-sidebar.expanded + .sidebar-toggle {
            left: 360px;
            transform: rotate(180deg);
        }

        /* Conteúdo Principal Revolucionário */
        .main-workspace {
            flex: 1;
            margin-left: 120px;
            padding: 20px;
            transition: var(--transition-smooth);
            min-height: 100vh;
        }

        .floating-sidebar.expanded ~ .main-workspace {
            margin-left: 360px;
        }

        /* Header Holográfico */
        .holo-header {
            background: var(--glass-dark);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 245, 255, 0.1);
            border-radius: 20px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-depth);
            position: relative;
            overflow: hidden;
        }

        .holo-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gradient-secondary);
            opacity: 0.5;
        }

        .page-title {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.8rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .time-crystal {
            background: rgba(0, 245, 255, 0.1);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 15px;
            padding: 10px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            color: var(--text-glow);
            box-shadow: inset var(--glow-cyan);
        }

        .user-hologram {
            width: 45px;
            height: 45px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition-bounce);
            box-shadow: var(--glow-purple);
            position: relative;
        }

        .user-hologram::before {
            content: '';
            position: absolute;
            inset: -3px;
            background: var(--gradient-primary);
            border-radius: 50%;
            z-index: -1;
            filter: blur(6px);
            opacity: 0.6;
            animation: hologram-pulse 2s ease-in-out infinite;
        }

        @keyframes hologram-pulse {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.05); }
        }

        .user-hologram:hover {
            transform: scale(1.1);
        }

        /* Cards Quânticos */
        .quantum-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .quantum-card {
            background: var(--glass-dark);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 245, 255, 0.1);
            border-radius: 20px;
            padding: 25px;
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-depth);
            position: relative;
            overflow: hidden;
        }

        .quantum-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-secondary);
            transform: scaleX(0);
            transition: var(--transition-smooth);
        }

        .quantum-card:hover {
            transform: translateY(-10px);
            border-color: rgba(108, 92, 231, 0.3);
            box-shadow: var(--glow-purple), var(--shadow-depth);
        }

        .quantum-card:hover::before {
            transform: scaleX(1);
        }

        /* Responsivo Inteligente */
        @media (max-width: 1024px) {
            .floating-sidebar {
                left: -260px;
                width: 280px;
            }
            
            .floating-sidebar.expanded {
                left: 20px;
            }
            
            .sidebar-toggle {
                left: 30px;
            }
            
            .floating-sidebar.expanded + .sidebar-toggle {
                left: 320px;
            }
            
            .main-workspace {
                margin-left: 20px;
            }
            
            .floating-sidebar.expanded ~ .main-workspace {
                margin-left: 20px;
            }
        }

        @media (max-width: 768px) {
            .holo-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .page-title {
                font-size: 1.4rem;
            }
            
            .time-crystal {
                display: none;
            }
        }

        /* Animações de Entrada */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Efeitos de Partículas */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--neon-cyan);
            border-radius: 50%;
            animation: particle-float 10s infinite linear;
            opacity: 0;
        }

        @keyframes particle-float {
            0% {
                opacity: 0;
                transform: translateY(100vh) scale(0);
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-100px) scale(1);
            }
        }

        /* Overlay para Mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 15, 0.8);
            backdrop-filter: blur(10px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition-smooth);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Scrollbar Cyberpunk */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark-matter);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-secondary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gradient-primary);
        }

        /* Alertas Cyberpunk */
        .quantum-alert {
            background: var(--glass-dark);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: var(--shadow-depth);
            border-left: 4px solid;
            animation: alert-slide-in 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .quantum-alert::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            opacity: 0.7;
        }

        .alert-success {
            border-left-color: var(--neon-green);
            color: var(--neon-green);
        }

        .alert-success::before {
            background: var(--gradient-success);
        }

        .alert-error {
            border-left-color: var(--neon-pink);
            color: var(--neon-pink);
        }

        .alert-error::before {
            background: var(--gradient-danger);
        }

        .alert-warning {
            border-left-color: #fdcb6e;
            color: #fdcb6e;
        }

        .alert-info {
            border-left-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }

        .alert-icon {
            font-size: 1.2rem;
            min-width: 20px;
        }

        @keyframes alert-slide-in {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Background Cyberpunk -->
    <div class="cyber-bg"></div>
    <div class="hex-grid"></div>
    
    <!-- Partículas Flutuantes -->
    <div class="particles" id="particles"></div>
    
    <!-- Overlay Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="cyber-dashboard">
        <!-- Sidebar Flutuante Cyberpunk -->
        <nav class="floating-sidebar" id="floatingSidebar">
            <!-- Logo Cyberpunk -->
            <div class="cyber-logo">
                <div class="logo-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="logo-text">
                    <h1>MegaFisio</h1>
                    <p>Sistema IA</p>
                </div>
            </div>
            
            <!-- Navegação Cyberpunk -->
            <div class="cyber-nav">
                <!-- Principal -->
                <div class="nav-section">
                    <div class="nav-section-title">Principal</div>
                    <div class="nav-item">
                        <a href="/dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-chart-pulse"></i></span>
                            <span class="nav-text">Centro Neural</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/profile" class="nav-link <?= $currentPage === 'profile' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-user-astronaut"></i></span>
                            <span class="nav-text">Meu Perfil</span>
                        </a>
                    </div>
                </div>
                
                <!-- Módulos IA -->
                <div class="nav-section">
                    <div class="nav-section-title">IA Especializada</div>
                    <div class="ai-modules">
                        <?php if (!empty($aiPrompts)): ?>
                            <?php foreach ($aiPrompts as $prompt): ?>
                                <div class="ai-module">
                                    <a href="/ai/<?= htmlspecialchars($prompt['slug'] ?? strtolower(str_replace(' ', '-', $prompt['name']))) ?>" 
                                       class="nav-link">
                                        <span class="nav-icon">
                                            <?php
                                            $icons = [
                                                'ortopedica' => 'fa-bone',
                                                'neurologica' => 'fa-brain', 
                                                'respiratoria' => 'fa-lungs',
                                                'geriatrica' => 'fa-heart-pulse',
                                                'pediatrica' => 'fa-baby'
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
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="ai-module">
                                <a href="/admin/prompts" class="nav-link">
                                    <span class="nav-icon"><i class="fas fa-plus-circle"></i></span>
                                    <span class="nav-text">Configurar IA</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Zona Administrativa -->
                <?php if ($user['role'] === 'admin'): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Administração</div>
                    <div class="nav-item">
                        <a href="/admin/users" class="nav-link <?= $currentPage === 'admin-users' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-users-cog"></i></span>
                            <span class="nav-text">Usuários</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/admin/prompts" class="nav-link <?= $currentPage === 'admin-prompts' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-robot"></i></span>
                            <span class="nav-text">Controle IA</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/admin/data-cleanup" class="nav-link <?= $currentPage === 'admin-cleanup' ? 'active' : '' ?>">
                            <span class="nav-icon"><i class="fas fa-broom"></i></span>
                            <span class="nav-text">Limpeza Dados</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Sistema -->
                <div class="nav-section">
                    <div class="nav-section-title">Sistema</div>
                    <div class="nav-item">
                        <a href="/password/change" class="nav-link">
                            <span class="nav-icon"><i class="fas fa-shield-halved"></i></span>
                            <span class="nav-text">Segurança</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/logout" class="nav-link">
                            <span class="nav-icon"><i class="fas fa-power-off"></i></span>
                            <span class="nav-text">Sair</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Workspace Principal -->
        <main class="main-workspace">
            <!-- Header Holográfico -->
            <header class="holo-header fade-in-up">
                <h1 class="page-title"><?= htmlspecialchars($title ?? 'Centro Neural') ?></h1>
                <div class="header-actions">
                    <div class="time-crystal" id="timeCrystal">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                    <div class="user-hologram">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                </div>
            </header>
            
            <!-- Área de Conteúdo -->
            <div class="content-workspace fade-in-up">
                <?php
                // Flash messages
                $flash = $_SESSION['flash'] ?? null;
                unset($_SESSION['flash']);
                
                if ($flash): ?>
                    <div class="quantum-alert alert-<?= $flash['type'] ?>" style="margin-bottom: 2rem;">
                        <div class="alert-icon">
                            <?php if ($flash['type'] === 'success'): ?>
                                <i class="fas fa-check-circle"></i>
                            <?php elseif ($flash['type'] === 'error'): ?>
                                <i class="fas fa-exclamation-triangle"></i>
                            <?php else: ?>
                                <i class="fas fa-info-circle"></i>
                            <?php endif; ?>
                        </div>
                        <span><?= htmlspecialchars($flash['message']) ?></span>
                    </div>
                <?php endif; ?>
                
                <?= $content ?>
            </div>
        </main>
    </div>
    
    <script>
        // Cyberpunk Dashboard Controller
        class CyberDashboard {
            constructor() {
                this.sidebar = document.getElementById('floatingSidebar');
                this.toggle = document.getElementById('sidebarToggle');
                this.overlay = document.getElementById('sidebarOverlay');
                this.timeCrystal = document.getElementById('timeCrystal');
                
                this.init();
                this.startTimeUpdate();
                this.createParticles();
            }
            
            init() {
                // Toggle sidebar
                this.toggle.addEventListener('click', () => this.toggleSidebar());
                
                // Close on overlay click
                this.overlay.addEventListener('click', () => this.closeSidebar());
                
                // Responsive handling
                window.addEventListener('resize', () => this.handleResize());
                
                // Smooth scrolling
                this.initSmoothScrolling();
                
                // Keyboard shortcuts
                this.initKeyboardShortcuts();
            }
            
            toggleSidebar() {
                this.sidebar.classList.toggle('expanded');
                
                if (window.innerWidth <= 1024) {
                    this.overlay.classList.toggle('active');
                }
                
                // Animate toggle button
                const icon = this.toggle.querySelector('i');
                icon.style.transform = this.sidebar.classList.contains('expanded') 
                    ? 'rotate(180deg)' : 'rotate(0deg)';
            }
            
            closeSidebar() {
                this.sidebar.classList.remove('expanded');
                this.overlay.classList.remove('active');
                
                const icon = this.toggle.querySelector('i');
                icon.style.transform = 'rotate(0deg)';
            }
            
            handleResize() {
                if (window.innerWidth > 1024) {
                    this.overlay.classList.remove('active');
                }
            }
            
            startTimeUpdate() {
                const updateTime = () => {
                    const now = new Date();
                    const options = {
                        timeZone: 'America/Sao_Paulo',
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    
                    this.timeCrystal.textContent = now.toLocaleDateString('pt-BR', options);
                };
                
                updateTime();
                setInterval(updateTime, 60000);
            }
            
            createParticles() {
                const container = document.getElementById('particles');
                const particleCount = 20;
                
                for (let i = 0; i < particleCount; i++) {
                    setTimeout(() => {
                        this.createParticle(container);
                    }, i * 200);
                }
                
                // Continuous particle generation
                setInterval(() => {
                    this.createParticle(container);
                }, 1000);
            }
            
            createParticle(container) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 2 + 's';
                particle.style.animationDuration = (Math.random() * 5 + 5) + 's';
                
                container.appendChild(particle);
                
                // Remove particle after animation
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }, 10000);
            }
            
            initSmoothScrolling() {
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
            }
            
            initKeyboardShortcuts() {
                document.addEventListener('keydown', (e) => {
                    // Ctrl + B or Cmd + B to toggle sidebar
                    if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                        e.preventDefault();
                        this.toggleSidebar();
                    }
                    
                    // Escape to close sidebar
                    if (e.key === 'Escape') {
                        this.closeSidebar();
                    }
                });
            }
        }
        
        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new CyberDashboard();
        });
        
        // Enhanced performance
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                // Lazy load non-critical resources
                console.log('🚀 CyberDashboard fully loaded');
            });
        }
    </script>
</body>
</html>