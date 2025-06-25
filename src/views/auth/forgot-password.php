<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Mega Fisio IA" class="auth-logo">
            <h1>Recuperar Senha</h1>
            <p>Digite seu email para receber instruções de recuperação</p>
        </div>
        
        <div class="recovery-info">
            <div class="info-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
            </div>
            <div class="info-content">
                <h3>Como funciona:</h3>
                <ol>
                    <li>Digite o email da sua conta</li>
                    <li>Você receberá um link de recuperação</li>
                    <li>Clique no link para definir nova senha</li>
                    <li>Faça login com a nova senha</li>
                </ol>
            </div>
        </div>
        
        <form class="auth-form" method="POST" action="<?= BASE_URL ?>/forgot-password">
            <div class="form-group">
                <label for="email">Email Cadastrado</label>
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
                    Digite o email que você usa para fazer login no sistema
                </small>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <span class="btn-text">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Enviar Instruções
                </span>
                <div class="btn-spinner" style="display: none;">
                    <div class="spinner"></div>
                </div>
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Lembrou da senha? <a href="<?= BASE_URL ?>/login">Fazer Login</a></p>
            <p class="help-text">
                <strong>Não recebeu o email?</strong><br>
                Verifique sua caixa de spam ou entre em contato com o suporte
            </p>
        </div>
    </div>
    
    <!-- Informações de Segurança -->
    <div class="security-notice">
        <div class="notice-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 1l3.09 6.26L22 9l-5 4.87L18.18 21 12 17.77 5.82 21 7 13.87 2 9l6.91-1.74L12 1z"/>
            </svg>
            <h3>Segurança</h3>
        </div>
        <ul>
            <li>O link de recuperação expira em 1 hora</li>
            <li>Só pode ser usado uma vez</li>
            <li>Enviamos apenas para emails cadastrados</li>
            <li>Por segurança, sempre mostramos a mesma mensagem</li>
        </ul>
    </div>
</div>

<script>
// Form submission with loading state
document.querySelector('.auth-form').addEventListener('submit', function(e) {
    const button = this.querySelector('button[type="submit"]');
    const btnText = button.querySelector('.btn-text');
    const btnSpinner = button.querySelector('.btn-spinner');
    
    button.disabled = true;
    btnText.style.display = 'none';
    btnSpinner.style.display = 'flex';
    
    // Re-enable after 5 seconds (in case of server issues)
    setTimeout(() => {
        button.disabled = false;
        btnText.style.display = 'flex';
        btnSpinner.style.display = 'none';
    }, 5000);
});

// Email validation
document.getElementById('email').addEventListener('input', function() {
    const email = this.value;
    const button = document.querySelector('button[type="submit"]');
    
    if (email && this.checkValidity()) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
});
</script>