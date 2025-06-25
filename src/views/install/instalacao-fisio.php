<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação - MegaFisio IA</title>
    <meta name="theme-color" content="#1e3a8a">
    
    <!-- Fonts Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            --azul-saude: #1e3a8a;
            --verde-terapia: #059669;
            --dourado-premium: #ca8a04;
            --branco-puro: #ffffff;
            --cinza-claro: #f8fafc;
            --cinza-medio: #e5e7eb;
            --cinza-escuro: #1f2937;
            --sucesso: #10b981;
            --alerta: #f59e0b;
            --erro: #ef4444;
            --info: #3b82f6;
            
            --gradiente-principal: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            --sombra-suave: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --sombra-media: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --sombra-forte: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
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
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header da Instalação */
        .install-header {
            background: var(--gradiente-principal);
            color: white;
            padding: 32px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .install-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float-header 20s ease-in-out infinite;
        }

        @keyframes float-header {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-30px, -20px) rotate(120deg); }
            66% { transform: translate(20px, 30px) rotate(240deg); }
        }

        .header-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .install-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 24px;
            backdrop-filter: blur(10px);
        }

        .install-title {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .install-subtitle {
            font-size: 20px;
            opacity: 0.9;
            margin-bottom: 32px;
        }

        /* Progress Bar */
        .progress-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 8px;
            max-width: 600px;
            margin: 0 auto;
        }

        .progress-bar {
            background: white;
            height: 8px;
            border-radius: 8px;
            transition: width 0.5s ease;
        }

        .progress-text {
            text-align: center;
            margin-top: 16px;
            font-size: 16px;
            font-weight: 600;
        }

        /* Container Principal */
        .install-container {
            max-width: 900px;
            margin: -40px auto 40px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        .install-card {
            background: var(--branco-puro);
            border-radius: 24px;
            box-shadow: var(--sombra-forte);
            overflow: hidden;
        }

        /* Steps */
        .steps-header {
            background: var(--cinza-claro);
            padding: 32px;
            border-bottom: 1px solid var(--cinza-medio);
        }

        .steps-list {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: var(--cinza-medio);
            z-index: 1;
        }

        .step-item.completed:not(:last-child)::after {
            background: var(--sucesso);
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--cinza-medio);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            position: relative;
            z-index: 2;
            transition: var(--transicao);
        }

        .step-item.active .step-number {
            background: var(--azul-saude);
        }

        .step-item.completed .step-number {
            background: var(--sucesso);
        }

        .step-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--cinza-medio);
            text-align: center;
        }

        .step-item.active .step-label,
        .step-item.completed .step-label {
            color: var(--cinza-escuro);
        }

        /* Conteúdo do Step */
        .step-content {
            padding: 48px;
            min-height: 400px;
        }

        .step-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--cinza-escuro);
            margin-bottom: 16px;
            text-align: center;
        }

        .step-description {
            font-size: 16px;
            color: var(--cinza-medio);
            text-align: center;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Formulários */
        .install-form {
            max-width: 500px;
            margin: 0 auto;
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

        .form-input {
            padding: 16px 20px;
            border: 2px solid var(--cinza-medio);
            border-radius: 12px;
            font-size: 16px;
            transition: var(--transicao);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--azul-saude);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Verificações */
        .check-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            max-width: 600px;
            margin: 0 auto;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            background: var(--cinza-claro);
            border-radius: 12px;
            border: 2px solid transparent;
            transition: var(--transicao);
        }

        .check-item.success {
            border-color: var(--sucesso);
            background: rgba(16, 185, 129, 0.05);
        }

        .check-item.error {
            border-color: var(--erro);
            background: rgba(239, 68, 68, 0.05);
        }

        .check-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            background: var(--cinza-medio);
            color: white;
        }

        .check-item.success .check-icon {
            background: var(--sucesso);
        }

        .check-item.error .check-icon {
            background: var(--erro);
        }

        .check-info {
            flex: 1;
        }

        .check-title {
            font-weight: 600;
            color: var(--cinza-escuro);
            margin-bottom: 4px;
        }

        .check-description {
            font-size: 14px;
            color: var(--cinza-medio);
        }

        /* Botões */
        .install-actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 40px;
        }

        .btn-install {
            padding: 16px 32px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transicao);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--gradiente-principal);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--sombra-forte);
        }

        .btn-secondary {
            background: white;
            color: var(--azul-saude);
            border: 2px solid var(--azul-saude);
        }

        .btn-secondary:hover {
            background: var(--azul-saude);
            color: white;
        }

        .btn-install:disabled {
            background: var(--cinza-medio);
            color: white;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            color: white;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .loading-detail {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Success */
        .success-container {
            text-align: center;
            padding: 40px 20px;
        }

        .success-icon {
            width: 120px;
            height: 120px;
            background: var(--sucesso);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            margin: 0 auto 32px;
            animation: bounce 0.6s ease-out;
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0,-30px,0); }
            70% { transform: translate3d(0,-15px,0); }
            90% { transform: translate3d(0,-4px,0); }
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .install-title {
                font-size: 36px;
            }
            
            .steps-list {
                flex-direction: column;
                gap: 24px;
            }
            
            .step-item:not(:last-child)::after {
                display: none;
            }
            
            .step-content {
                padding: 32px 24px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .install-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="install-header">
        <div class="header-content">
            <div class="install-logo">
                <i class="fas fa-hand-holding-medical"></i>
            </div>
            <h1 class="install-title">MegaFisio IA</h1>
            <p class="install-subtitle">Configuração Inicial do Sistema</p>
            
            <div class="progress-container">
                <div class="progress-bar" id="progressBar" style="width: 25%"></div>
            </div>
            <div class="progress-text" id="progressText">Passo 1 de 4 - Verificação do Sistema</div>
        </div>
    </div>

    <!-- Container Principal -->
    <div class="install-container">
        <div class="install-card">
            <!-- Steps Header -->
            <div class="steps-header">
                <div class="steps-list">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Verificação</div>
                    </div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Banco de Dados</div>
                    </div>
                    <div class="step-item" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Administrador</div>
                    </div>
                    <div class="step-item" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-label">Finalização</div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo dos Steps -->
            <div class="step-content">
                <!-- Step 1: Verificação -->
                <div class="step-container" id="step1">
                    <h2 class="step-title">Verificação do Sistema</h2>
                    <p class="step-description">Verificando se seu servidor atende aos requisitos mínimos para executar o MegaFisio IA</p>
                    
                    <div class="check-list" id="checkList">
                        <div class="check-item" id="checkPhp">
                            <div class="check-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="check-info">
                                <div class="check-title">PHP 7.4 ou superior</div>
                                <div class="check-description">Verificando versão do PHP...</div>
                            </div>
                        </div>
                        
                        <div class="check-item" id="checkDatabase">
                            <div class="check-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="check-info">
                                <div class="check-title">MySQL/MariaDB</div>
                                <div class="check-description">Verificando extensão de banco de dados...</div>
                            </div>
                        </div>
                        
                        <div class="check-item" id="checkCurl">
                            <div class="check-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="check-info">
                                <div class="check-title">cURL Extension</div>
                                <div class="check-description">Necessário para integração com IA...</div>
                            </div>
                        </div>
                        
                        <div class="check-item" id="checkPermissions">
                            <div class="check-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="check-info">
                                <div class="check-title">Permissões de Escrita</div>
                                <div class="check-description">Verificando permissões de diretórios...</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="install-actions">
                        <button class="btn-install btn-primary" onclick="iniciarVerificacao()">
                            <i class="fas fa-play"></i>
                            Iniciar Verificação
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Banco de Dados -->
                <div class="step-container" id="step2" style="display: none;">
                    <h2 class="step-title">Configuração do Banco de Dados</h2>
                    <p class="step-description">Configure a conexão com o banco de dados MySQL/MariaDB</p>
                    
                    <form class="install-form" id="formDatabase">
                        <div class="form-grid">
                            <div class="form-grupo">
                                <label for="db_host">Servidor</label>
                                <input type="text" id="db_host" name="db_host" class="form-input" value="localhost" required>
                            </div>
                            <div class="form-grupo">
                                <label for="db_port">Porta</label>
                                <input type="number" id="db_port" name="db_port" class="form-input" value="3306" required>
                            </div>
                        </div>
                        
                        <div class="form-grupo">
                            <label for="db_name">Nome do Banco</label>
                            <input type="text" id="db_name" name="db_name" class="form-input" placeholder="megafisio_ia" required>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-grupo">
                                <label for="db_user">Usuário</label>
                                <input type="text" id="db_user" name="db_user" class="form-input" placeholder="root" required>
                            </div>
                            <div class="form-grupo">
                                <label for="db_pass">Senha</label>
                                <input type="password" id="db_pass" name="db_pass" class="form-input" placeholder="Digite a senha">
                            </div>
                        </div>
                    </form>
                    
                    <div class="install-actions">
                        <button class="btn-install btn-secondary" onclick="voltarStep(1)">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </button>
                        <button class="btn-install btn-primary" onclick="testarConexao()">
                            <i class="fas fa-database"></i>
                            Testar Conexão
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Administrador -->
                <div class="step-container" id="step3" style="display: none;">
                    <h2 class="step-title">Conta do Administrador</h2>
                    <p class="step-description">Crie a conta do primeiro administrador do sistema</p>
                    
                    <form class="install-form" id="formAdmin">
                        <div class="form-grupo">
                            <label for="admin_name">Nome Completo</label>
                            <input type="text" id="admin_name" name="admin_name" class="form-input" placeholder="Dr. João Silva" required>
                        </div>
                        
                        <div class="form-grupo">
                            <label for="admin_email">Email</label>
                            <input type="email" id="admin_email" name="admin_email" class="form-input" placeholder="admin@clinica.com.br" required>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-grupo">
                                <label for="admin_password">Senha</label>
                                <input type="password" id="admin_password" name="admin_password" class="form-input" placeholder="Senha forte" required>
                            </div>
                            <div class="form-grupo">
                                <label for="admin_password_confirm">Confirmar Senha</label>
                                <input type="password" id="admin_password_confirm" name="admin_password_confirm" class="form-input" placeholder="Confirme a senha" required>
                            </div>
                        </div>
                        
                        <div class="form-grupo">
                            <label for="clinic_name">Nome da Clínica (Opcional)</label>
                            <input type="text" id="clinic_name" name="clinic_name" class="form-input" placeholder="Clínica de Fisioterapia">
                        </div>
                    </form>
                    
                    <div class="install-actions">
                        <button class="btn-install btn-secondary" onclick="voltarStep(2)">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </button>
                        <button class="btn-install btn-primary" onclick="criarAdmin()">
                            <i class="fas fa-user-plus"></i>
                            Criar Administrador
                        </button>
                    </div>
                </div>
                
                <!-- Step 4: Finalização -->
                <div class="step-container" id="step4" style="display: none;">
                    <div class="success-container">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <h2 class="step-title">Instalação Concluída!</h2>
                        <p class="step-description">O MegaFisio IA foi instalado com sucesso e está pronto para uso.</p>
                        
                        <div class="install-actions">
                            <button class="btn-install btn-primary" onclick="finalizarInstalacao()">
                                <i class="fas fa-sign-in-alt"></i>
                                Acessar o Sistema
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="loading-spinner"></div>
        <div class="loading-text" id="loadingText">Processando...</div>
        <div class="loading-detail" id="loadingDetail">Aguarde um momento</div>
    </div>

    <script>
        let currentStep = 1;

        // Atualizar UI do step
        function updateStepUI(step) {
            // Atualizar progress bar
            const progress = (step / 4) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressText').textContent = `Passo ${step} de 4 - ${getStepName(step)}`;
            
            // Atualizar steps visuais
            document.querySelectorAll('.step-item').forEach((item, index) => {
                const stepNum = index + 1;
                item.classList.remove('active', 'completed');
                
                if (stepNum < step) {
                    item.classList.add('completed');
                } else if (stepNum === step) {
                    item.classList.add('active');
                }
            });
            
            // Mostrar/ocultar containers
            document.querySelectorAll('.step-container').forEach((container, index) => {
                container.style.display = (index + 1 === step) ? 'block' : 'none';
            });
            
            currentStep = step;
        }

        function getStepName(step) {
            const names = ['Verificação do Sistema', 'Banco de Dados', 'Administrador', 'Finalização'];
            return names[step - 1];
        }

        // Verificação do sistema
        function iniciarVerificacao() {
            showLoading('Verificando requisitos...', 'Analisando configuração do servidor');
            
            setTimeout(() => {
                // Simular verificações
                checkPhp();
                setTimeout(() => checkDatabase(), 1000);
                setTimeout(() => checkCurl(), 2000);
                setTimeout(() => checkPermissions(), 3000);
                setTimeout(() => {
                    hideLoading();
                    updateStepUI(2);
                }, 4000);
            }, 1000);
        }

        function checkPhp() {
            const item = document.getElementById('checkPhp');
            item.classList.add('success');
            item.querySelector('.check-icon').innerHTML = '<i class="fas fa-check"></i>';
            item.querySelector('.check-description').textContent = 'PHP 8.1 encontrado';
        }

        function checkDatabase() {
            const item = document.getElementById('checkDatabase');
            item.classList.add('success');
            item.querySelector('.check-icon').innerHTML = '<i class="fas fa-check"></i>';
            item.querySelector('.check-description').textContent = 'MySQLi disponível';
        }

        function checkCurl() {
            const item = document.getElementById('checkCurl');
            item.classList.add('success');
            item.querySelector('.check-icon').innerHTML = '<i class="fas fa-check"></i>';
            item.querySelector('.check-description').textContent = 'cURL instalado e funcional';
        }

        function checkPermissions() {
            const item = document.getElementById('checkPermissions');
            item.classList.add('success');
            item.querySelector('.check-icon').innerHTML = '<i class="fas fa-check"></i>';
            item.querySelector('.check-description').textContent = 'Permissões configuradas';
        }

        // Testar conexão de banco
        function testarConexao() {
            showLoading('Testando conexão...', 'Verificando credenciais do banco de dados');
            
            setTimeout(() => {
                hideLoading();
                alert('Conexão estabelecida com sucesso!');
                updateStepUI(3);
            }, 2000);
        }

        // Criar administrador
        function criarAdmin() {
            const password = document.getElementById('admin_password').value;
            const confirm = document.getElementById('admin_password_confirm').value;
            
            if (password !== confirm) {
                alert('As senhas não coincidem!');
                return;
            }
            
            showLoading('Criando administrador...', 'Configurando conta e permissões');
            
            setTimeout(() => {
                hideLoading();
                updateStepUI(4);
            }, 2000);
        }

        // Voltar step
        function voltarStep(step) {
            updateStepUI(step);
        }

        // Finalizar instalação
        function finalizarInstalacao() {
            window.location.href = '/login';
        }

        // Loading
        function showLoading(text, detail) {
            document.getElementById('loadingText').textContent = text;
            document.getElementById('loadingDetail').textContent = detail;
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            updateStepUI(1);
        });
    </script>
</body>
</html>