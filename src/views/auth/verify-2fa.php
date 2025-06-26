<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação 2FA - MegaFisio IA | Sistema Inteligente para Fisioterapia</title>
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

        .verify-container {
            background: var(--branco-puro);
            border-radius: 24px;
            padding: 48px;
            box-shadow: var(--sombra-flutuante);
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--cinza-medio);
        }

        .verify-header {
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
            margin-bottom: 16px;
        }

        .user-info {
            background: rgba(30, 58, 138, 0.05);
            border: 1px solid rgba(30, 58, 138, 0.1);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            background: var(--gradiente-principal);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .user-details h3 {
            color: var(--azul-saude);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .user-details p {
            color: #6b7280;
            font-size: 14px;
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

        .code-input {
            width: 100%;
            padding: 20px;
            border: 2px solid var(--cinza-medio);
            border-radius: 12px;
            background: var(--branco-puro);
            color: var(--cinza-escuro);
            font-size: 24px;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            text-align: center;
            letter-spacing: 8px;
            transition: var(--transicao);
            font-weight: 600;
        }

        .code-input:focus {
            outline: none;
            border-color: var(--azul-saude);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .backup-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
            padding: 12px;
            background: var(--cinza-claro);
            border-radius: 8px;
        }

        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--cinza-medio);
            transition: var(--transicao);
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: var(--transicao);
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--azul-saude);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }

        .toggle-label {
            font-size: 14px;
            color: var(--cinza-escuro);
            cursor: pointer;
            font-weight: 500;
        }

        .backup-info {
            display: none;
            background: rgba(202, 138, 4, 0.1);
            border: 1px solid rgba(202, 138, 4, 0.3);
            color: #92400e;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-top: 12px;
        }

        .backup-info.show {
            display: block;
        }

        .form-help {
            color: #6b7280;
            font-size: 13px;
            margin-top: 8px;
            text-align: center;
        }

        .btn-verify {
            width: 100%;
            padding: 18px 24px;
            background: var(--gradiente-principal);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transicao);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-verify:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--sombra-forte);
        }

        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-cancel {
            width: 100%;
            padding: 16px 24px;
            background: transparent;
            color: var(--cinza-escuro);
            border: 2px solid var(--cinza-medio);
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transicao);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-cancel:hover {
            background: var(--cinza-claro);
            border-color: var(--cinza-escuro);
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .help-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--cinza-medio);
        }

        .help-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: #6b7280;
            font-size: 13px;
        }

        .help-item i {
            color: var(--azul-saude);
            width: 16px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .verify-container {
                padding: 32px 24px;
                margin: 10px;
            }
            
            .code-input {
                font-size: 20px;
                letter-spacing: 4px;
                padding: 16px;
            }
            
            .user-info {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="logo">MegaFisio IA</div>
                <div class="subtitle">Verificação de Dois Fatores</div>
            </div>
            
            <?php if ($userData): ?>
                <div class="user-info">
                    <div class="user-avatar">
                        <?= strtoupper(substr($userData['name'], 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <h3><?= htmlspecialchars($userData['name']) ?></h3>
                        <p><?= htmlspecialchars($userData['email']) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (isset($errors['general'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($errors['general']) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <?= $this->csrfField() ?>
            
            <div class="form-group">
                <label for="code">Código de Verificação</label>
                <input 
                    type="text" 
                    id="code" 
                    name="code" 
                    placeholder="000000"
                    maxlength="8"
                    class="code-input"
                    autocomplete="one-time-code"
                    required
                    autofocus
                >
                <div class="form-help">
                    Digite o código de 6 dígitos do seu aplicativo autenticador
                </div>
                <?php if (isset($errors['code'])): ?>
                    <div class="error-message" style="margin-top: 12px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= htmlspecialchars($errors['code']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="backup-toggle">
                <label class="toggle-switch">
                    <input type="checkbox" id="backupToggle" name="backup_code" value="1">
                    <span class="toggle-slider"></span>
                </label>
                <label for="backupToggle" class="toggle-label">Usar código de backup</label>
            </div>

            <div class="backup-info" id="backupInfo">
                <i class="fas fa-key"></i>
                <strong>Código de Backup:</strong> Digite um código no formato XXXX-XXXX que você salvou durante a configuração do 2FA.
            </div>

            <button type="submit" class="btn-verify" id="verifyBtn">
                <i class="fas fa-shield-check"></i>
                Verificar Código
            </button>

            <a href="<?= BASE_URL ?>/cancel-2fa" class="btn-cancel">
                <i class="fas fa-arrow-left"></i>
                Voltar ao Login
            </a>
        </form>

        <div class="help-section">
            <div class="help-item">
                <i class="fas fa-mobile-alt"></i>
                Verifique se o horário do seu dispositivo está correto
            </div>
            <div class="help-item">
                <i class="fas fa-clock"></i>
                Os códigos expiram a cada 30 segundos
            </div>
            <div class="help-item">
                <i class="fas fa-life-ring"></i>
                Use um código de backup se não conseguir acessar o app
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('code');
            const backupToggle = document.getElementById('backupToggle');
            const backupInfo = document.getElementById('backupInfo');
            const verifyBtn = document.getElementById('verifyBtn');
            const form = document.querySelector('form');
            
            // Toggle entre código normal e backup
            backupToggle.addEventListener('change', function() {
                if (this.checked) {
                    codeInput.placeholder = 'XXXX-XXXX';
                    codeInput.maxLength = 9;
                    codeInput.style.letterSpacing = '4px';
                    codeInput.style.fontSize = '20px';
                    backupInfo.classList.add('show');
                } else {
                    codeInput.placeholder = '000000';
                    codeInput.maxLength = 6;
                    codeInput.style.letterSpacing = '8px';
                    codeInput.style.fontSize = '24px';
                    backupInfo.classList.remove('show');
                }
                codeInput.value = '';
                codeInput.focus();
            });
            
            // Formatação do input
            codeInput.addEventListener('input', function(e) {
                let value = e.target.value;
                
                if (backupToggle.checked) {
                    // Formato: XXXX-XXXX
                    value = value.replace(/\D/g, '');
                    if (value.length > 4) {
                        value = value.substring(0, 4) + '-' + value.substring(4, 8);
                    }
                    e.target.value = value;
                } else {
                    // Apenas números
                    e.target.value = value.replace(/\D/g, '');
                }
            });
            
            // Auto-submit quando código completo
            codeInput.addEventListener('input', function(e) {
                const value = e.target.value;
                const isComplete = backupToggle.checked ? 
                    value.length === 9 && value.includes('-') : 
                    value.length === 6;
                    
                if (isComplete) {
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                }
            });
            
            // Loading state no submit
            form.addEventListener('submit', function() {
                verifyBtn.disabled = true;
                verifyBtn.innerHTML = '<span class="spinner"></span>Verificando...';
            });
            
            // Focus no input ao carregar
            codeInput.focus();
            
            // Prevenir múltiplos submits
            let isSubmitting = false;
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }
                isSubmitting = true;
            });
        });
    </script>
</body>
</html>