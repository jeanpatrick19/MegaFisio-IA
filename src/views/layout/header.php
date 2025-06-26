<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS CRÍTICO INLINE: Evitar flash completamente -->
    <style id="critical-theme">
        /* CSS base neutro - será sobrescrito pelo tema */
        html, body { 
            margin: 0; 
            padding: 0; 
            transition: none !important;
        }
    </style>
    
    <!-- SCRIPT CRÍTICO: Aplicar tema ANTES de qualquer CSS -->
    <script>
    (function() {
        try {
            var temaLocal = localStorage.getItem('tema-megafisio') || 'claro';
            var htmlElement = document.documentElement;
            
            // CSS crítico para evitar flash
            var criticalCSS = '';
            
            switch(temaLocal) {
                case 'escuro':
                    htmlElement.classList.add('tema-escuro');
                    break;
                case 'auto':
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        htmlElement.classList.add('tema-auto', 'tema-escuro');
                    } else {
                        htmlElement.classList.add('tema-auto', 'tema-claro');
                    }
                    break;
                default:
                    htmlElement.classList.add('tema-claro');
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
    
    <title>MegaFisio IA - Sistema Administrativo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/sistema-unificado.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/temas-globais.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }

        .flash-success {
            background: rgba(34, 197, 94, 0.9);
            color: white;
        }

        .flash-error {
            background: rgba(239, 68, 68, 0.9);
            color: white;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <?php 
    // Gerar token CSRF se não existir
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    // Mostrar mensagens flash
    $flash = $this->getFlash();
    if ($flash): ?>
        <div class="flash-message flash-<?= $flash['type'] ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
        <script>
            setTimeout(() => {
                const msg = document.querySelector('.flash-message');
                if (msg) msg.remove();
            }, 5000);
        </script>
    <?php endif; ?>