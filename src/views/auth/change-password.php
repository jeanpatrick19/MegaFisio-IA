<div class="auth-container <?= $forceChange ? 'force-change' : '' ?>">
    <div class="auth-card">
        <div class="auth-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Mega Fisio IA" class="auth-logo">
            <h1><?= $forceChange ? 'Alterar Senha Obrigatória' : 'Alterar Senha' ?></h1>
            <p>
                <?php if ($forceChange): ?>
                    Por segurança, você deve alterar sua senha no primeiro acesso
                <?php else: ?>
                    Escolha uma nova senha forte para sua conta
                <?php endif; ?>
            </p>
        </div>
        
        <?php if ($forceChange): ?>
            <div class="alert alert-info">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <div>
                    <strong>Primeiro acesso detectado</strong><br>
                    Para garantir a segurança da sua conta, defina uma nova senha pessoal.
                </div>
            </div>
        <?php endif; ?>
        
        <form class="auth-form" method="POST" action="<?= BASE_URL ?>/change-password">
            <?php if (!$forceChange): ?>
                <div class="form-group">
                    <label for="current_password">Senha Atual *</label>
                    <div class="password-input">
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            required 
                            autocomplete="current-password"
                            class="form-control"
                            placeholder="Digite sua senha atual"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="password">Nova Senha *</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        class="form-control"
                        placeholder="Mínimo 8 caracteres"
                        minlength="8"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                </div>
                <div class="password-strength" id="passwordStrength"></div>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Confirmar Nova Senha *</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        required 
                        autocomplete="new-password"
                        class="form-control"
                        placeholder="Digite a nova senha novamente"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                </div>
                <div class="password-match" id="passwordMatch"></div>
            </div>
            
            <div class="password-requirements">
                <h4>Requisitos da Senha:</h4>
                <ul>
                    <li id="req-length" class="requirement">Mínimo de 8 caracteres</li>
                    <li id="req-lower" class="requirement">Pelo menos uma letra minúscula</li>
                    <li id="req-upper" class="requirement">Pelo menos uma letra maiúscula</li>
                    <li id="req-number" class="requirement">Pelo menos um número</li>
                    <li id="req-special" class="requirement">Pelo menos um caractere especial</li>
                </ul>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block" id="submitBtn" disabled>
                <span class="btn-text">
                    <?= $forceChange ? 'Definir Nova Senha' : 'Alterar Senha' ?>
                </span>
                <div class="btn-spinner" style="display: none;">
                    <div class="spinner"></div>
                </div>
            </button>
            
            <?php if (!$forceChange): ?>
                <div class="form-footer">
                    <a href="<?= BASE_URL ?>/dashboard" class="btn-link">Cancelar</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
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

// Validação em tempo real
const passwordField = document.getElementById('password');
const confirmField = document.getElementById('password_confirm');
const submitBtn = document.getElementById('submitBtn');

function checkRequirement(id, condition) {
    const elem = document.getElementById(id);
    if (condition) {
        elem.classList.add('met');
        elem.innerHTML = elem.innerHTML.replace(/^.*?(?=\w)/, '✓ ');
    } else {
        elem.classList.remove('met');
        elem.innerHTML = elem.innerHTML.replace(/^.*?(?=\w)/, '○ ');
    }
}

function validatePassword() {
    const password = passwordField.value;
    const confirm = confirmField.value;
    
    // Verificar requisitos
    checkRequirement('req-length', password.length >= 8);
    checkRequirement('req-lower', /[a-z]/.test(password));
    checkRequirement('req-upper', /[A-Z]/.test(password));
    checkRequirement('req-number', /[0-9]/.test(password));
    checkRequirement('req-special', /[^A-Za-z0-9]/.test(password));
    
    // Calcular força
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    // Mostrar força
    const strengthDiv = document.getElementById('passwordStrength');
    const levels = ['Muito fraca', 'Fraca', 'Razoável', 'Boa', 'Forte'];
    const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#16a34a'];
    
    if (password.length > 0) {
        strengthDiv.innerHTML = `
            <div class="strength-bar">
                <div class="strength-fill" style="width: ${(strength/5)*100}%; background: ${colors[strength-1] || colors[0]}"></div>
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
    const allRequirementsMet = strength === 5;
    const passwordsMatch = password === confirm && confirm.length > 0;
    submitBtn.disabled = !(allRequirementsMet && passwordsMatch);
}

passwordField.addEventListener('input', validatePassword);
confirmField.addEventListener('input', validatePassword);

// Form submission
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const password = passwordField.value;
    const confirm = confirmField.value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('As senhas não coincidem!');
        return false;
    }
    
    const button = submitBtn;
    const btnText = button.querySelector('.btn-text');
    const btnSpinner = button.querySelector('.btn-spinner');
    
    button.disabled = true;
    btnText.style.display = 'none';
    btnSpinner.style.display = 'block';
});

// Inicializar estado dos requisitos
document.addEventListener('DOMContentLoaded', function() {
    const requirements = document.querySelectorAll('.requirement');
    requirements.forEach(req => {
        req.innerHTML = '○ ' + req.innerHTML;
    });
});
</script>