<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Painel' ?> - MegaFisio IA | Sistema Inteligente para Fisioterapia</title>
    <meta name="theme-color" content="#1e3a8a">
    <meta name="description" content="Sistema de gestão e inteligência artificial para fisioterapeutas">
    <link rel="icon" href="<?= BASE_URL ?>/favicon.svg" type="image/svg+xml">
    
    <!-- Fonts Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- CSS Global de Temas -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/temas-globais.css">
    
    <!-- CSS CRÍTICO INLINE: Evitar flash completamente -->
    <style id="critical-theme">
        /* CSS base neutro - será sobrescrito pelo tema */
        html, body { 
            margin: 0; 
            padding: 0; 
            transition: none !important;
            background-color: #f8fafc;
            color: #1f2937;
        }
    </style>
    
    <!-- Script inline para aplicar tema IMEDIATAMENTE (sem flash) -->
    <script>
    (function() {
        try {
            var temaLocal = localStorage.getItem('tema-megafisio') || 'claro';
            var htmlElement = document.documentElement;
            
            // CSS crítico para evitar flash
            var criticalCSS = '';
            
            htmlElement.className = '';
            switch(temaLocal) {
                case 'escuro':
                    criticalCSS = `
                        html, body, * { 
                            background-color: #0f172a !important; 
                            color: #f8fafc !important;
                            border-color: #475569 !important;
                        }
                        input, select, textarea, button {
                            background-color: #1e293b !important;
                            color: #f8fafc !important;
                            border-color: #475569 !important;
                        }
                        .card, .card-fisio, div {
                            background-color: #1e293b !important;
                            color: #f8fafc !important;
                        }
                    `;
                    htmlElement.classList.add('tema-escuro');
                    break;
                case 'auto':
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        criticalCSS = `
                            html, body { 
                                background-color: #0f172a !important; 
                                color: #f8fafc !important;
                            }
                            * { background: transparent !important; }
                        `;
                        htmlElement.classList.add('tema-auto', 'tema-escuro');
                    } else {
                        criticalCSS = `
                            html, body { 
                                background-color: #f8fafc !important; 
                                color: #1f2937 !important;
                            }
                        `;
                        htmlElement.classList.add('tema-auto', 'tema-claro');
                    }
                    break;
                default:
                    criticalCSS = `
                        html, body { 
                            background-color: #f8fafc !important; 
                            color: #1f2937 !important;
                        }
                    `;
                    htmlElement.classList.add('tema-claro');
            }
            
            // Injetar CSS crítico
            var styleElement = document.getElementById('critical-theme');
            if (styleElement) {
                styleElement.innerHTML = criticalCSS;
            }
            
            // Aplicar no body quando disponível
            document.addEventListener('DOMContentLoaded', function() {
                document.body.className = htmlElement.className;
                // NÃO remover CSS crítico para evitar flash
                // O CSS será sobrescrito pelos arquivos carregados
            });
            
        } catch(e) {
            document.documentElement.classList.add('tema-claro');
        }
    })();
    </script>
    
    <style>
        :root {
            /* Paleta Profissional de Saúde */
            --azul-saude: #1e3a8a;          /* Azul profissional */
            --verde-terapia: #059669;       /* Verde saúde */
            --dourado-premium: #ca8a04;     /* Dourado suave */
            --lilas-cuidado: #7c3aed;       /* Lilás tecnologia */
            --branco-puro: #ffffff;         /* Branco limpo */
            --cinza-claro: #f8fafc;         /* Fundo suave */
            --cinza-medio: #e5e7eb;         /* Bordas */
            --cinza-escuro: #1f2937;        /* Texto principal */
            --preto-menu: #111827;          /* Menu escuro */
            
            /* Cores de Status */
            --sucesso: #10b981;             /* Verde positivo */
            --alerta: #f59e0b;              /* Amarelo atenção */
            --erro: #ef4444;                /* Vermelho erro */
            --info: #3b82f6;                /* Azul informação */
            
            /* Gradientes Suaves */
            --gradiente-principal: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            --gradiente-menu: linear-gradient(180deg, #111827 0%, #1f2937 100%);
            --gradiente-card: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            
            /* Sombras Modernas */
            --sombra-suave: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --sombra-media: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --sombra-forte: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --sombra-flutuante: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            
            /* Transições */
            --transicao: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transicao-lenta: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
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
            font-size: 16px;
            background: var(--cinza-claro);
            color: var(--cinza-escuro);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Layout Principal */
        .dashboard-fisio {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Profissional */
        .sidebar-fisio {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: var(--gradiente-menu);
            z-index: 1000;
            overflow-y: auto;
            transition: var(--transicao);
            box-shadow: var(--sombra-forte);
        }

        /* Logotipo */
        .logo-fisio {
            padding: 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
        }

        .logo-icone {
            width: 60px;
            height: 60px;
            background: var(--gradiente-principal);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 28px;
            color: white;
            box-shadow: var(--sombra-media);
        }

        .logo-texto {
            color: white;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo-subtexto {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Menu de Navegação */
        .menu-fisio {
            padding: 24px 0;
        }

        .menu-item {
            margin: 0 12px 4px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 12px;
            transition: var(--transicao);
            font-weight: 500;
            font-size: 15px;
            position: relative;
        }

        .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .menu-link.ativo {
            background: var(--azul-saude);
            color: white;
            box-shadow: var(--sombra-media);
        }

        .menu-link.ativo::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: var(--dourado-premium);
            border-radius: 0 4px 4px 0;
        }

        .menu-icone {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .menu-texto {
            flex: 1;
        }

        .menu-badge {
            background: var(--dourado-premium);
            color: white;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 700;
        }

        /* Área Principal */
        .conteudo-principal {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Fixo */
        .header-fisio {
            position: sticky;
            top: 0;
            background: var(--branco-puro);
            border-bottom: 1px solid var(--cinza-medio);
            z-index: 900;
            box-shadow: var(--sombra-suave);
        }

        .header-conteudo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 32px;
        }

        .header-esquerda {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--cinza-escuro);
            cursor: pointer;
        }

        .saudacao {
            color: var(--cinza-escuro);
            font-size: 18px;
            font-weight: 600;
        }

        .saudacao-nome {
            color: var(--azul-saude);
        }

        .saudacao-subtexto {
            font-size: 14px;
            color: var(--cinza-medio);
            font-weight: 400;
            margin-top: 2px;
        }

        .saudacao-subtexto-admin {
            font-size: 14px;
            color: var(--cinza-escuro);
            font-weight: 600;
            margin-top: 2px;
        }

        .header-direita {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        @media (max-width: 480px) {
            .header-direita {
                gap: 12px;
            }
            
            .user-avatar-header {
                width: 32px;
                height: 32px;
                margin-left: 6px;
            }
            
            .user-avatar-header .avatar-letter {
                font-size: 12px;
            }
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

        /* Avatar no Header */
        .user-avatar-header {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transicao);
            position: relative;
            overflow: hidden;
            border: 2px solid var(--cinza-medio);
            margin-left: 12px;
        }

        .user-avatar-header:hover {
            transform: scale(1.05);
            border-color: var(--azul-saude);
            box-shadow: 0 0 15px rgba(30, 58, 138, 0.3);
        }

        .user-avatar-header .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .user-avatar-header .avatar-letter {
            width: 100%;
            height: 100%;
            background: var(--gradiente-principal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            font-family: 'Inter', sans-serif;
        }

        .notificacoes {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--cinza-escuro);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: var(--transicao);
        }

        .notificacoes:hover {
            background: var(--cinza-claro);
        }

        .notificacao-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--erro);
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }


        /* Área de Conteúdo */
        .area-conteudo {
            flex: 1;
            padding: 32px;
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

        /* Títulos de Página */
        .titulo-pagina {
            font-size: 32px;
            font-weight: 800;
            color: var(--cinza-escuro);
            margin-bottom: 8px;
        }

        .subtitulo-pagina {
            font-size: 16px;
            color: var(--cinza-medio);
            margin-bottom: 32px;
        }

        /* Cards Modernos */
        .card-fisio {
            background: var(--branco-puro);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--sombra-media);
            transition: var(--transicao);
            border: 1px solid var(--cinza-medio);
        }

        .card-fisio:hover {
            transform: translateY(-2px);
            box-shadow: var(--sombra-forte);
        }

        /* Botões */
        .btn-fisio {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transicao);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primario {
            background: var(--gradiente-principal);
            color: white;
            box-shadow: var(--sombra-media);
        }

        .btn-primario:hover {
            transform: translateY(-2px);
            box-shadow: var(--sombra-forte);
        }

        .btn-secundario {
            background: var(--branco-puro);
            color: var(--azul-saude);
            border: 2px solid var(--azul-saude);
        }

        .btn-secundario:hover {
            background: var(--azul-saude);
            color: white;
        }

        /* Tooltips */
        [data-tooltip] {
            position: relative;
        }

        [data-tooltip]::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--preto-menu);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: var(--transicao);
            margin-bottom: 8px;
            z-index: 1000;
        }

        [data-tooltip]:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Responsivo */
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

        @media (max-width: 1024px) {
            .sidebar-fisio {
                transform: translateX(-100%);
            }
            
            .sidebar-fisio.aberta {
                transform: translateX(0);
            }
            
            .sidebar-overlay.ativa {
                display: block;
            }
            
            .conteudo-principal {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .area-conteudo {
                padding: 24px;
            }
        }

        @media (max-width: 768px) {
            .header-conteudo {
                padding: 16px;
            }
            
            .saudacao {
                font-size: 16px;
            }
            
            .data-hora {
                display: none;
            }
            
            .user-avatar-header {
                width: 36px;
                height: 36px;
                margin-left: 8px;
            }
            
            .user-avatar-header .avatar-letter {
                font-size: 14px;
            }
            
            .area-conteudo {
                padding: 16px;
            }
            
            .titulo-pagina {
                font-size: 24px;
            }
        }

        /* Mensagens de Feedback */
        .alerta-fisio {
            position: fixed;
            top: 24px;
            right: 24px;
            padding: 16px 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 14px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            box-shadow: var(--sombra-forte);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alerta-sucesso {
            background: var(--sucesso);
            color: white;
        }

        .alerta-erro {
            background: var(--erro);
            color: white;
        }

        .alerta-aviso {
            background: var(--alerta);
            color: white;
        }

        .alerta-info {
            background: var(--info);
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-fisio">
        <!-- Overlay para Mobile -->
        <div class="sidebar-overlay" onclick="fecharSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar-fisio" id="sidebar">
            <!-- Logo -->
            <div class="logo-fisio">
                <div class="logo-icone">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="logo-texto">MegaFisio IA</div>
                <div class="logo-subtexto">Inteligência para Fisioterapia</div>
            </div>

            <!-- Menu de Navegação -->
            <nav class="menu-fisio">
                <div class="menu-item">
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="menu-link <?= $currentPage === 'dashboard' ? 'ativo' : '' ?>">
                            <div class="menu-icone"><i class="fas fa-cogs"></i></div>
                            <span class="menu-texto" data-translate="Administração">Administração</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="menu-link <?= $currentPage === 'dashboard' ? 'ativo' : '' ?>">
                            <div class="menu-icone"><i class="fas fa-home"></i></div>
                            <span class="menu-texto" data-translate="Página Inicial">Página Inicial</span>
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="menu-item">
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin/ai" class="menu-link <?= $currentPage === 'ai' ? 'ativo' : '' ?>">
                            <div class="menu-icone"><i class="fas fa-brain"></i></div>
                            <span class="menu-texto" data-translate="IA do Sistema">IA do Sistema</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/ai" class="menu-link <?= $currentPage === 'ai' ? 'ativo' : '' ?>">
                            <div class="menu-icone"><i class="fas fa-brain"></i></div>
                            <span class="menu-texto" data-translate="Assistente IA">Assistente IA</span>
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($user['role'] === 'admin'): ?>
                <div class="menu-item">
                    <a href="<?= BASE_URL ?>/admin/users" class="menu-link <?= $currentPage === 'users' ? 'ativo' : '' ?>">
                        <div class="menu-icone"><i class="fas fa-user-md"></i></div>
                        <span class="menu-texto" data-translate="Usuários">Usuários</span>
                    </a>
                </div>
                
                <div class="menu-item">
                    <a href="<?= BASE_URL ?>/admin/settings" class="menu-link <?= $currentPage === 'settings' ? 'ativo' : '' ?>">
                        <div class="menu-icone"><i class="fas fa-cog"></i></div>
                        <span class="menu-texto" data-translate="Configurações">Configurações</span>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="menu-item">
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin/profile" class="menu-link <?= $currentPage === 'profile' ? 'ativo' : '' ?>">
                            <div class="menu-icone"><i class="fas fa-user-shield"></i></div>
                            <span class="menu-texto" data-translate="Perfil Admin">Perfil Admin</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/profile" class="menu-link <?= $currentPage === 'profile' ? 'ativo' : '' ?>">
                            <div class="menu-icone"><i class="fas fa-user-circle"></i></div>
                            <span class="menu-texto" data-translate="Meu Perfil">Meu Perfil</span>
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="menu-item" style="margin-top: auto; padding-top: 24px;">
                    <a href="<?= BASE_URL ?>/logout" class="menu-link">
                        <div class="menu-icone"><i class="fas fa-sign-out-alt"></i></div>
                        <span class="menu-texto" data-translate="Sair">Sair</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Conteúdo Principal -->
        <main class="conteudo-principal">
            <!-- Header -->
            <header class="header-fisio">
                <div class="header-conteudo">
                    <div class="header-esquerda">
                        <button class="menu-toggle" onclick="toggleSidebar()">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <div class="saudacao">
                            <div>Bem-vindo(a), <span class="saudacao-nome"><?= htmlspecialchars($user['name'] ?? 'Fisioterapeuta') ?></span>!</div>
                            <div class="<?= $user['role'] === 'admin' ? 'saudacao-subtexto-admin' : 'saudacao-subtexto' ?>">
                                <?php 
                                if ($user['role'] === 'admin') {
                                    echo 'Gerencie o sistema e acompanhe todas as operações';
                                } else {
                                    // Personalizar saudação por especialidade para profissionais
                                    $especialidade = $_SESSION['user_specialty'] ?? 'geral';
                                    $saudacoes = [
                                        'ortopedica' => 'Pronto(a) para reabilitar seus pacientes ortopédicos?',
                                        'neurologica' => 'Pronto(a) para evoluir seus pacientes neurológicos?',
                                        'respiratoria' => 'Pronto(a) para melhorar a função respiratória dos seus pacientes?',
                                        'geriatrica' => 'Pronto(a) para promover qualidade de vida aos idosos?',
                                        'pediatrica' => 'Pronto(a) para estimular o desenvolvimento das crianças?',
                                        'geral' => 'Pronto(a) para atender e evoluir seus pacientes?'
                                    ];
                                    echo $saudacoes[$especialidade];
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="header-direita">
                        <button class="notificacoes" data-tooltip="Você tem 3 novas notificações">
                            <i class="fas fa-bell"></i>
                            <span class="notificacao-badge">3</span>
                        </button>
                        
                        <div class="data-hora" id="dataHora">
                            <i class="fas fa-calendar-alt"></i>
                            <span id="dataHoraTexto"></span>
                        </div>
                        
                        <?php if (isset($user) && $user): ?>
                        <div class="user-avatar-header" data-tooltip="<?= htmlspecialchars($user['name'] ?? '') ?>" onclick="irParaPerfil()">
                            <?php
                            // Gerar avatar usando a mesma lógica do perfil
                            if (!empty($user['avatar_path']) && $user['avatar_type'] === 'upload') {
                                echo '<img src="' . htmlspecialchars($user['avatar_path']) . '" alt="Avatar" class="avatar-img">';
                            } elseif (!empty($user['avatar_default']) && $user['avatar_type'] === 'default') {
                                echo '<div class="avatar-letter">' . htmlspecialchars($user['avatar_default']) . '</div>';
                            } else {
                                $initials = strtoupper(substr($user['name'] ?? 'U', 0, 2));
                                echo '<div class="avatar-letter">' . $initials . '</div>';
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                        <script>
                        // Função para ir ao perfil
                        function irParaPerfil() {
                            // Detectar contexto (admin ou user)
                            const isAdminContext = window.location.pathname.includes('/admin/');
                            const profileUrl = isAdminContext ? '/admin/profile' : '/profile';
                            window.location.href = profileUrl;
                        }
                        </script>
                    </div>
                </div>
            </header>

            <!-- Área de Conteúdo -->
            <div class="area-conteudo">
                <?= $content ?>
            </div>
        </main>
    </div>

    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('aberta');
            overlay.classList.toggle('ativa');
        }

        function fecharSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('aberta');
            overlay.classList.remove('ativa');
        }


        // Função para mostrar alertas
        function mostrarAlerta(mensagem, tipo = 'info') {
            const alerta = document.createElement('div');
            alerta.className = `alerta-fisio alerta-${tipo}`;
            alerta.innerHTML = `
                <i class="fas fa-${tipo === 'sucesso' ? 'check-circle' : tipo === 'erro' ? 'exclamation-circle' : tipo === 'aviso' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${mensagem}</span>
            `;
            
            document.body.appendChild(alerta);
            
            // Remover após 5 segundos
            setTimeout(() => {
                alerta.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    document.body.removeChild(alerta);
                }, 300);
            }, 5000);
        }

        // Animação de saída
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOut {
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Atalhos de teclado
        document.addEventListener('keydown', (e) => {
            // Ctrl+K para abrir IA
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                window.location.href = '/ai';
            }
            
            // Esc para fechar menus
            if (e.key === 'Escape') {
                document.getElementById('perfilMenu').classList.remove('ativo');
                fecharSidebar();
            }
        });

        // Adicionar classe para animações suaves em cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-fisio');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '0';
                            entry.target.style.transform = 'translateY(20px)';
                            
                            setTimeout(() => {
                                entry.target.style.transition = 'all 0.5s ease';
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'translateY(0)';
                            }, 50);
                        }, index * 100);
                    }
                });
            });

            cards.forEach(card => {
                observer.observe(card);
            });
        });

    </script>
    
    <!-- JavaScript Global de Traduções e Temas -->
    <script src="<?= BASE_URL ?>/public/assets/js/traducoes-completas.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/temas-globais.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/formato-data-hora.js"></script>
</body>
</html>