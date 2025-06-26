<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <h1>Editar Meu Perfil</h1>
            <p>Atualize suas informações pessoais</p>
        </div>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/profile" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12H5m7-7l-7 7 7 7"/>
                </svg>
                Voltar ao Perfil
            </a>
        </div>
    </div>
    
    <div class="profile-edit-content">
        <div class="form-card">
            <form method="POST" class="profile-form">
                <?= $this->csrfField() ?>
                
                <div class="form-section">
                    <h3>Informações Pessoais</h3>
                    
                    <div class="profile-preview">
                        <div class="profile-avatar-edit">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                        <div class="profile-preview-info">
                            <p class="preview-note">Seu avatar é gerado automaticamente com a primeira letra do seu nome</p>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Nome Completo *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                required 
                                autocomplete="name"
                                value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>"
                                class="form-control"
                                placeholder="Seu nome completo"
                                minlength="3"
                            >
                            <small class="form-help">
                                Este nome será exibido em seu perfil e nas interações do sistema
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required 
                                autocomplete="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>"
                                class="form-control"
                                placeholder="seu@email.com"
                            >
                            <small class="form-help">
                                Usado para login e comunicações importantes do sistema
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Informações da Conta</h3>
                    
                    <div class="account-info">
                        <div class="info-row">
                            <div class="info-label">Perfil:</div>
                            <div class="info-value">
                                <span class="profile-badge profile-badge-<?= $user['role'] ?>">
                                    <?= $user['role'] === 'admin' ? 'Administrador' : 'Usuário' ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Status:</div>
                            <div class="info-value">
                                <span class="status-badge status-<?= $user['status'] ?>">
                                    <?= $user['status'] === 'active' ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <div class="info-label">Membro desde:</div>
                            <div class="info-value">
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <div>
                            <strong>Informação:</strong><br>
                            Para alterar seu perfil ou status da conta, entre em contato com um administrador.
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Segurança</h3>
                    
                    <div class="password-change-section">
                        <div class="password-header">
                            <div class="password-icon">🔐</div>
                            <div>
                                <div class="password-title">Alterar Senha</div>
                                <div class="password-desc">Mantenha sua conta segura com uma senha forte</div>
                            </div>
                        </div>
                        
                        <form id="passwordChangeForm" class="password-form" style="display: none;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="current_password">Senha Atual *</label>
                                    <div class="password-input">
                                        <input 
                                            type="password" 
                                            id="current_password" 
                                            name="current_password" 
                                            required 
                                            class="form-control"
                                            placeholder="Digite sua senha atual"
                                        >
                                        <button type="button" class="password-toggle" onclick="togglePasswordField('current_password')">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">Nova Senha *</label>
                                    <div class="password-input">
                                        <input 
                                            type="password" 
                                            id="new_password" 
                                            name="new_password" 
                                            required 
                                            class="form-control"
                                            placeholder="Mínimo 8 caracteres"
                                            minlength="8"
                                        >
                                        <button type="button" class="password-toggle" onclick="togglePasswordField('new_password')">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="password-strength" id="passwordStrength"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Confirmar Nova Senha *</label>
                                    <div class="password-input">
                                        <input 
                                            type="password" 
                                            id="confirm_password" 
                                            name="confirm_password" 
                                            required 
                                            class="form-control"
                                            placeholder="Digite a nova senha novamente"
                                        >
                                        <button type="button" class="password-toggle" onclick="togglePasswordField('confirm_password')">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="password-match" id="passwordMatch"></div>
                                </div>
                            </div>
                            
                            <div class="password-requirements">
                                <h5>Requisitos da Senha:</h5>
                                <div class="requirements-grid">
                                    <div id="req-length" class="requirement">○ Mínimo 8 caracteres</div>
                                    <div id="req-lower" class="requirement">○ Letra minúscula</div>
                                    <div id="req-upper" class="requirement">○ Letra maiúscula</div>
                                    <div id="req-number" class="requirement">○ Número</div>
                                </div>
                            </div>
                            
                            <div class="password-actions">
                                <button type="submit" class="btn btn-primary" id="changePasswordBtn" disabled>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z"/>
                                    </svg>
                                    Alterar Senha
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="cancelPasswordChange()">Cancelar</button>
                            </div>
                        </form>
                        
                        <button type="button" class="btn btn-outline" id="showPasswordForm">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z"/>
                            </svg>
                            Alterar Minha Senha
                        </button>
                    </div>
                    
                    <div class="two-factor-section" id="twoFactorSection">
                        <div class="two-factor-header">
                            <div class="two-factor-icon">🔐</div>
                            <div>
                                <div class="two-factor-title">Autenticação de Dois Fatores (2FA)</div>
                                <div class="two-factor-desc">Adicione uma camada extra de segurança à sua conta</div>
                            </div>
                            <div class="two-factor-status" id="twoFactorStatus">
                                <div class="status-loading">Carregando...</div>
                            </div>
                        </div>
                        
                        <!-- Interface quando 2FA está desativado -->
                        <div id="twoFactorDisabled" style="display: none;">
                            <div class="alert alert-info">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <div>
                                    <strong>Recomendado:</strong> Ative o 2FA para proteger sua conta contra acessos não autorizados.
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="enable2FA()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1l3.09 6.26L22 9l-6.91 1.74L12 17l-3.09-6.26L2 9l6.91-1.74L12 1z"/>
                                </svg>
                                Ativar 2FA
                            </button>
                        </div>
                        
                        <!-- Interface de configuração do 2FA -->
                        <div id="twoFactorSetup" style="display: none;">
                            <div class="setup-step">
                                <h4>1. Escaneie o QR Code</h4>
                                <p>Use o Google Authenticator ou outro app compatível para escanear o código:</p>
                                <div class="qr-code-container">
                                    <img id="qrCodeImage" src="" alt="QR Code" style="display: none;">
                                    <div class="manual-entry">
                                        <p><strong>Ou digite manualmente:</strong></p>
                                        <code id="manualSecret"></code>
                                        <button type="button" onclick="copySecret()" class="btn-copy">Copiar</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="setup-step">
                                <h4>2. Digite o código do seu app</h4>
                                <div class="form-group">
                                    <input type="text" id="setupCode" placeholder="000000" maxlength="6" class="form-control setup-code-input">
                                </div>
                                <div class="setup-actions">
                                    <button type="button" class="btn btn-primary" onclick="confirm2FA()">Ativar 2FA</button>
                                    <button type="button" class="btn btn-secondary" onclick="cancelSetup()">Cancelar</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Interface quando 2FA está ativado -->
                        <div id="twoFactorEnabled" style="display: none;">
                            <div class="alert alert-success">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <strong>2FA Ativo:</strong> Sua conta está protegida com autenticação de dois fatores.
                                </div>
                            </div>
                            
                            <div class="backup-codes-info">
                                <div class="info-item">
                                    <span class="info-label">Códigos de backup disponíveis:</span>
                                    <span class="info-value" id="backupCodesCount">-</span>
                                </div>
                            </div>
                            
                            <div class="two-factor-actions">
                                <button type="button" class="btn btn-outline" onclick="showBackupCodes()">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Gerenciar Códigos de Backup
                                </button>
                                <button type="button" class="btn btn-danger" onclick="showDisable2FA()">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M13 7a6 6 0 00-6 6v6h6V7zm0 0V4a2 2 0 114 0v3M5 8a2 2 0 012-2h1"/>
                                    </svg>
                                    Desativar 2FA
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="security-options">
                        <a href="<?= BASE_URL ?>/profile/privacy" class="security-option">
                            <div class="security-option-icon">🛡️</div>
                            <div class="security-option-content">
                                <div class="security-option-title">Privacidade e Dados</div>
                                <div class="security-option-desc">Gerenciar dados pessoais (LGPD)</div>
                            </div>
                            <div class="security-option-arrow">→</div>
                        </a>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                        </svg>
                        Salvar Alterações
                    </button>
                    <a href="<?= BASE_URL ?>/profile" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
        
        <!-- Sidebar com Informações -->
        <div class="edit-sidebar">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Dicas de Segurança</h3>
                </div>
                <div class="card-body">
                    <div class="security-tips">
                        <div class="tip-item">
                            <div class="tip-icon">🔒</div>
                            <div class="tip-content">
                                <h4>Use um email seguro</h4>
                                <p>Prefira emails que você acessa regularmente e que tenham boa segurança.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <div class="tip-icon">👤</div>
                            <div class="tip-content">
                                <h4>Nome real</h4>
                                <p>Use seu nome verdadeiro para facilitar a identificação nos relatórios.</p>
                            </div>
                        </div>
                        
                        <div class="tip-item">
                            <div class="tip-icon">🔄</div>
                            <div class="tip-content">
                                <h4>Mantenha atualizado</h4>
                                <p>Revise suas informações periodicamente para mantê-las sempre corretas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Histórico de Alterações</h3>
                </div>
                <div class="card-body">
                    <div class="change-history">
                        <div class="change-item">
                            <div class="change-date">
                                <?= date('d/m/Y H:i', strtotime($user['updated_at'])) ?>
                            </div>
                            <div class="change-action">
                                Última atualização do perfil
                            </div>
                        </div>
                        
                        <div class="change-item">
                            <div class="change-date">
                                <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                            </div>
                            <div class="change-action">
                                Conta criada
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Valores originais para detectar mudanças
const originalValues = {
    name: '<?= addslashes($user['name']) ?>',
    email: '<?= addslashes($user['email']) ?>'
};

// Detectar mudanças em tempo real
function checkChanges() {
    const nameField = document.getElementById('name');
    const emailField = document.getElementById('email');
    const saveBtn = document.getElementById('saveBtn');
    
    const nameChanged = nameField.value !== originalValues.name;
    const emailChanged = emailField.value !== originalValues.email;
    const hasChanges = nameChanged || emailChanged;
    
    // Destacar campos alterados
    nameField.classList.toggle('changed', nameChanged);
    emailField.classList.toggle('changed', emailChanged);
    
    // Destacar botão se houver mudanças
    saveBtn.classList.toggle('highlight', hasChanges);
    
    // Atualizar preview do avatar
    const avatarPreview = document.querySelector('.profile-avatar-edit');
    const newName = nameField.value.trim();
    if (newName) {
        avatarPreview.textContent = newName.charAt(0).toUpperCase();
    }
}

// Event listeners
document.getElementById('name').addEventListener('input', checkChanges);
document.getElementById('email').addEventListener('input', checkChanges);

// Validação do formulário
document.querySelector('.profile-form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (name.length < 3) {
        e.preventDefault();
        alert('O nome deve ter no mínimo 3 caracteres!');
        document.getElementById('name').focus();
        return false;
    }
    
    if (!email.includes('@') || email.length < 5) {
        e.preventDefault();
        alert('Por favor, insira um email válido!');
        document.getElementById('email').focus();
        return false;
    }
    
    // Confirmar se há mudanças
    const nameChanged = name !== originalValues.name;
    const emailChanged = email !== originalValues.email;
    
    if (!nameChanged && !emailChanged) {
        e.preventDefault();
        alert('Nenhuma alteração foi feita.');
        return false;
    }
    
    // Confirmar mudança de email
    if (emailChanged) {
        const confirm = window.confirm(
            'Você está alterando seu email de login.\\n\\n' +
            'Tem certeza que deseja continuar?\\n\\n' +
            'Novo email: ' + email
        );
        
        if (!confirm) {
            e.preventDefault();
            return false;
        }
    }
});

// Auto-focus no primeiro campo
document.getElementById('name').focus();

// Funcionalidade de alteração de senha
const showPasswordFormBtn = document.getElementById('showPasswordForm');
const passwordChangeForm = document.getElementById('passwordChangeForm');
const newPasswordField = document.getElementById('new_password');
const confirmPasswordField = document.getElementById('confirm_password');
const changePasswordBtn = document.getElementById('changePasswordBtn');

showPasswordFormBtn.addEventListener('click', function() {
    passwordChangeForm.style.display = 'block';
    showPasswordFormBtn.style.display = 'none';
    document.getElementById('current_password').focus();
});

function cancelPasswordChange() {
    passwordChangeForm.style.display = 'none';
    showPasswordFormBtn.style.display = 'block';
    passwordChangeForm.reset();
    document.getElementById('passwordStrength').innerHTML = '';
    document.getElementById('passwordMatch').innerHTML = '';
    resetRequirements();
}

function togglePasswordField(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    
    if (field.type === 'password') {
        field.type = 'text';
        button.classList.add('visible');
    } else {
        field.type = 'password';
        button.classList.remove('visible');
    }
}

function checkPasswordRequirement(id, condition) {
    const elem = document.getElementById(id);
    if (condition) {
        elem.classList.add('met');
        elem.textContent = elem.textContent.replace(/^○/, '✓');
    } else {
        elem.classList.remove('met');
        elem.textContent = elem.textContent.replace(/^✓/, '○');
    }
}

function resetRequirements() {
    const requirements = ['req-length', 'req-lower', 'req-upper', 'req-number'];
    requirements.forEach(req => {
        const elem = document.getElementById(req);
        elem.classList.remove('met');
        elem.textContent = elem.textContent.replace(/^✓/, '○');
    });
}

function validateNewPassword() {
    const password = newPasswordField.value;
    const confirm = confirmPasswordField.value;
    
    // Verificar requisitos
    checkPasswordRequirement('req-length', password.length >= 8);
    checkPasswordRequirement('req-lower', /[a-z]/.test(password));
    checkPasswordRequirement('req-upper', /[A-Z]/.test(password));
    checkPasswordRequirement('req-number', /[0-9]/.test(password));
    
    // Calcular força
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    
    // Mostrar força
    const strengthDiv = document.getElementById('passwordStrength');
    const levels = ['Muito fraca', 'Fraca', 'Razoável', 'Boa'];
    const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e'];
    
    if (password.length > 0) {
        strengthDiv.innerHTML = `
            <div class="strength-bar">
                <div class="strength-fill" style="width: ${(strength/4)*100}%; background: ${colors[strength-1] || colors[0]}"></div>
            </div>
            <div class="strength-text" style="color: ${colors[strength-1] || colors[0]}">
                Força: ${levels[strength-1] || levels[0]}
            </div>
        `;
    } else {
        strengthDiv.innerHTML = '';
    }
    
    // Verificar confirmação
    const matchDiv = document.getElementById('passwordMatch');
    if (confirm.length > 0) {
        if (password === confirm) {
            matchDiv.innerHTML = '<div class="match-text match-success">✓ Senhas coincidem</div>';
        } else {
            matchDiv.innerHTML = '<div class="match-text match-error">✗ Senhas não coincidem</div>';
        }
    } else {
        matchDiv.innerHTML = '';
    }
    
    // Habilitar/desabilitar botão
    const allRequirementsMet = strength === 4;
    const passwordsMatch = password === confirm && confirm.length > 0;
    const currentPassword = document.getElementById('current_password').value;
    
    changePasswordBtn.disabled = !(allRequirementsMet && passwordsMatch && currentPassword.length > 0);
}

newPasswordField.addEventListener('input', validateNewPassword);
confirmPasswordField.addEventListener('input', validateNewPassword);
document.getElementById('current_password').addEventListener('input', validateNewPassword);

// Submissão do formulário de alteração de senha
passwordChangeForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = newPasswordField.value;
    const confirmPassword = confirmPasswordField.value;
    
    if (newPassword !== confirmPassword) {
        alert('As senhas não coincidem!');
        return false;
    }
    
    changePasswordBtn.disabled = true;
    changePasswordBtn.innerHTML = '<div class="spinner"></div> Alterando...';
    
    // Fazer requisição AJAX
    fetch('<?= BASE_URL ?>/profile/changePassword', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'senha_atual': currentPassword,
            'nova_senha': newPassword,
            'confirmar_senha': confirmPassword
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Senha alterada com sucesso!');
            cancelPasswordChange();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao alterar senha. Tente novamente.');
    })
    .finally(() => {
        changePasswordBtn.disabled = false;
        changePasswordBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z"/>
            </svg>
            Alterar Senha
        `;
    });
});

// ================ FUNCIONALIDADES DE 2FA ================

// Carregar status do 2FA ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    load2FAStatus();
});

let currentSecret = '';

function load2FAStatus() {
    fetch('<?= BASE_URL ?>/profile/get2FAStatus')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                update2FAInterface(data.enabled, data.backupCodesCount);
            } else {
                console.error('Erro ao carregar status 2FA:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro ao carregar status 2FA:', error);
        });
}

function update2FAInterface(enabled, backupCodesCount = 0) {
    const statusDiv = document.getElementById('twoFactorStatus');
    const disabledDiv = document.getElementById('twoFactorDisabled');
    const enabledDiv = document.getElementById('twoFactorEnabled');
    const setupDiv = document.getElementById('twoFactorSetup');
    
    // Esconder todos primeiro
    disabledDiv.style.display = 'none';
    enabledDiv.style.display = 'none';
    setupDiv.style.display = 'none';
    
    if (enabled) {
        statusDiv.innerHTML = '<div class="status-badge status-active">Ativo</div>';
        enabledDiv.style.display = 'block';
        document.getElementById('backupCodesCount').textContent = backupCodesCount;
    } else {
        statusDiv.innerHTML = '<div class="status-badge status-inactive">Inativo</div>';
        disabledDiv.style.display = 'block';
    }
}

function enable2FA() {
    const enableBtn = event.target;
    enableBtn.disabled = true;
    enableBtn.innerHTML = '<div class="spinner"></div> Gerando...';
    
    fetch('<?= BASE_URL ?>/profile/enable2FA', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentSecret = data.secret;
            document.getElementById('qrCodeImage').src = data.qrCodeURL;
            document.getElementById('qrCodeImage').style.display = 'block';
            document.getElementById('manualSecret').textContent = data.secret;
            
            // Mostrar interface de configuração
            document.getElementById('twoFactorDisabled').style.display = 'none';
            document.getElementById('twoFactorSetup').style.display = 'block';
            document.getElementById('setupCode').focus();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao gerar QR Code. Tente novamente.');
    })
    .finally(() => {
        enableBtn.disabled = false;
        enableBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1l3.09 6.26L22 9l-6.91 1.74L12 17l-3.09-6.26L2 9l6.91-1.74L12 1z"/>
            </svg>
            Ativar 2FA
        `;
    });
}

function confirm2FA() {
    const code = document.getElementById('setupCode').value.trim();
    
    if (!code || code.length !== 6 || !/^\d{6}$/.test(code)) {
        alert('Digite um código de 6 dígitos válido.');
        return;
    }
    
    const confirmBtn = event.target;
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<div class="spinner"></div> Verificando...';
    
    fetch('<?= BASE_URL ?>/profile/confirm2FA', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'code': code
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('2FA ativado com sucesso!');
            showBackupCodesModal(data.backupCodes);
            load2FAStatus();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao verificar código. Tente novamente.');
    })
    .finally(() => {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = 'Ativar 2FA';
    });
}

function cancelSetup() {
    document.getElementById('twoFactorSetup').style.display = 'none';
    document.getElementById('twoFactorDisabled').style.display = 'block';
    document.getElementById('setupCode').value = '';
    currentSecret = '';
}

function copySecret() {
    const secret = document.getElementById('manualSecret').textContent;
    navigator.clipboard.writeText(secret).then(() => {
        const btn = event.target;
        const originalText = btn.textContent;
        btn.textContent = 'Copiado!';
        setTimeout(() => {
            btn.textContent = originalText;
        }, 2000);
    });
}

function showDisable2FA() {
    const password = prompt('Digite sua senha para desativar o 2FA:');
    if (!password) return;
    
    const code = prompt('Digite um código do seu app ou um código de backup (formato: XXXX-XXXX):');
    if (!code) return;
    
    fetch('<?= BASE_URL ?>/profile/disable2FA', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'password': password,
            'code': code
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('2FA desativado com sucesso!');
            load2FAStatus();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao desativar 2FA. Tente novamente.');
    });
}

function showBackupCodes() {
    const password = prompt('Digite sua senha para acessar os códigos de backup:');
    if (!password) return;
    
    fetch('<?= BASE_URL ?>/profile/regenerateBackupCodes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'password': password
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showBackupCodesModal(data.backupCodes);
            load2FAStatus();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao gerar códigos. Tente novamente.');
    });
}

function showBackupCodesModal(codes) {
    const modal = document.createElement('div');
    modal.className = 'backup-codes-modal';
    modal.innerHTML = `
        <div class="modal-backdrop" onclick="closeBackupCodes()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>Códigos de Backup de Emergência</h3>
                <button type="button" onclick="closeBackupCodes()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Importante:</strong> Guarde estes códigos em local seguro. Cada código só pode ser usado uma vez.
                </div>
                <div class="backup-codes-grid">
                    ${codes.map(code => `<div class="backup-code">${code}</div>`).join('')}
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="downloadBackupCodes()" class="btn btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Baixar como TXT
                    </button>
                    <button type="button" onclick="copyAllCodes()" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                        Copiar Todos
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    window.currentBackupCodes = codes;
}

function closeBackupCodes() {
    const modal = document.querySelector('.backup-codes-modal');
    if (modal) {
        modal.remove();
    }
}

function copyAllCodes() {
    const codes = window.currentBackupCodes;
    const text = codes.join('\n');
    navigator.clipboard.writeText(text).then(() => {
        alert('Códigos copiados para a área de transferência!');
    });
}

function downloadBackupCodes() {
    const codes = window.currentBackupCodes;
    const text = `CÓDIGOS DE BACKUP - MEGAFISIO IA\nGerados em: ${new Date().toLocaleString('pt-BR')}\n\n${codes.join('\n')}\n\nGuarde estes códigos em local seguro. Cada código só pode ser usado uma vez.`;
    
    const blob = new Blob([text], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `backup-codes-megafisio-${new Date().toISOString().split('T')[0]}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Formatar código automaticamente no input
document.addEventListener('DOMContentLoaded', function() {
    const setupCodeInput = document.getElementById('setupCode');
    if (setupCodeInput) {
        setupCodeInput.addEventListener('input', function(e) {
            // Permitir apenas números
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
});
</script>