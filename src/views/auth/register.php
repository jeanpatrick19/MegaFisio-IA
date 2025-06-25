<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Mega Fisio IA" class="auth-logo">
            <h1>Criar Conta</h1>
            <p>Junte-se ao Mega Fisio IA e revolucione sua prática</p>
        </div>
        
        <form class="auth-form" method="POST" action="<?= BASE_URL ?>/register">
            <?= $this->csrfField() ?>
            
            <div class="form-group">
                <label for="name">Nome Completo *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    required 
                    autocomplete="name"
                    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                    class="form-control"
                    placeholder="Seu nome completo"
                    minlength="3"
                >
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    autocomplete="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    class="form-control"
                    placeholder="seu@email.com"
                >
                <small class="form-help">
                    Use um email válido - você receberá informações importantes
                </small>
            </div>
            
            <div class="form-group">
                <label for="password">Senha *</label>
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
                <label for="password_confirm">Confirmar Senha *</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        required 
                        autocomplete="new-password"
                        class="form-control"
                        placeholder="Digite a senha novamente"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                </div>
                <div class="password-match" id="passwordMatch"></div>
            </div>
            
            <!-- Termos e Políticas LGPD -->
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="accept_terms" value="1" required>
                    <span class="checkmark"></span>
                    Li e aceito os <a href="<?= BASE_URL ?>/terms" target="_blank">Termos de Uso</a> e 
                    <a href="<?= BASE_URL ?>/privacy" target="_blank">Política de Privacidade</a>
                </label>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="consent_data" value="1" required>
                    <span class="checkmark"></span>
                    Autorizo o tratamento dos meus dados pessoais conforme descrito na Política de Privacidade
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block" id="submitBtn" disabled>
                <span class="btn-text">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Criar Conta
                </span>
                <div class="btn-spinner" style="display: none;">
                    <div class="spinner"></div>
                </div>
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Já tem uma conta? <a href="<?= BASE_URL ?>/login">Fazer Login</a></p>
        </div>
    </div>
    
    <!-- Informações LGPD -->
    <div class="lgpd-notice">
        <div class="lgpd-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1l3.09 6.26L22 9l-5 4.87L18.18 21 12 17.77 5.82 21 7 13.87 2 9l6.91-1.74L12 1z"/>
            </svg>
            <h3>Proteção de Dados (LGPD)</h3>
        </div>
        <div class="lgpd-content">
            <h4>Seus dados estão seguros conosco:</h4>
            <ul>
                <li>✓ Coletamos apenas dados necessários</li>
                <li>✓ Criptografia de ponta a ponta</li>
                <li>✓ Você pode exportar seus dados a qualquer momento</li>
                <li>✓ Direito ao esquecimento garantido</li>
                <li>✓ Transparência total no uso dos dados</li>
            </ul>
        </div>
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
const nameField = document.getElementById('name');
const emailField = document.getElementById('email');
const passwordField = document.getElementById('password');
const confirmField = document.getElementById('password_confirm');
const termsCheckbox = document.querySelector('input[name="accept_terms"]');
const consentCheckbox = document.querySelector('input[name="consent_data"]');
const submitBtn = document.getElementById('submitBtn');

function validateForm() {
    const name = nameField.value.trim();
    const email = emailField.value.trim();
    const password = passwordField.value;
    const confirm = confirmField.value;
    const termsAccepted = termsCheckbox.checked;
    const consentGiven = consentCheckbox.checked;
    
    // Validar força da senha
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
    const isValid = name.length >= 3 && 
                   email.includes('@') && 
                   strength >= 3 && 
                   password === confirm && 
                   confirm.length > 0 &&
                   termsAccepted &&
                   consentGiven;
    
    submitBtn.disabled = !isValid;
}

// Event listeners
[nameField, emailField, passwordField, confirmField, termsCheckbox, consentCheckbox].forEach(field => {
    field.addEventListener('input', validateForm);
    field.addEventListener('change', validateForm);
});

// Form submission
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const password = passwordField.value;
    const confirm = confirmField.value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('As senhas não coincidem!');
        return false;
    }
    
    if (!termsCheckbox.checked || !consentCheckbox.checked) {
        e.preventDefault();
        alert('Você deve aceitar os termos e consentir com o tratamento de dados!');
        return false;
    }
    
    const button = submitBtn;
    const btnText = button.querySelector('.btn-text');
    const btnSpinner = button.querySelector('.btn-spinner');
    
    button.disabled = true;
    btnText.style.display = 'none';
    btnSpinner.style.display = 'flex';
});

// Validação inicial
validateForm();
</script>