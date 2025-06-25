<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <h1>Criar Novo Usuário</h1>
            <p>Preencha os dados para criar um novo usuário no sistema</p>
        </div>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12H5m7-7l-7 7 7 7"/>
                </svg>
                Voltar
            </a>
        </div>
    </div>
    
    <div class="form-card">
        <form method="POST" class="user-form">
            <div class="form-section">
                <h3>Informações Pessoais</h3>
                
                <div class="form-row">
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
                            placeholder="Ex: João da Silva"
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
                            placeholder="joao@exemplo.com"
                        >
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Credenciais de Acesso</h3>
                
                <div class="form-row">
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
                </div>
            </div>
            
            <div class="form-section">
                <h3>Configurações da Conta</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Perfil *</label>
                        <select id="role" name="role" required class="form-control">
                            <option value="usuario" <?= ($_POST['role'] ?? '') === 'usuario' ? 'selected' : '' ?>>
                                Usuário
                            </option>
                            <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                                Administrador
                            </option>
                        </select>
                        <small class="form-help">
                            <strong>Usuário:</strong> Acesso básico ao sistema<br>
                            <strong>Admin:</strong> Acesso completo, incluindo gerenciamento de usuários
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required class="form-control">
                            <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>
                                Ativo
                            </option>
                            <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                Inativo
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            name="force_password_change" 
                            value="1"
                            <?= isset($_POST['force_password_change']) ? 'checked' : 'checked' ?>
                        >
                        <span class="checkmark"></span>
                        Forçar troca de senha no primeiro login
                    </label>
                    <small class="form-help">
                        Recomendado para garantir que o usuário defina sua própria senha
                    </small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                    </svg>
                    Criar Usuário
                </button>
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Cancelar</a>
            </div>
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

// Validação de força da senha
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('passwordStrength');
    
    let strength = 0;
    let messages = [];
    
    if (password.length >= 8) strength++;
    else messages.push('Mínimo 8 caracteres');
    
    if (/[a-z]/.test(password)) strength++;
    else messages.push('Letra minúscula');
    
    if (/[A-Z]/.test(password)) strength++;
    else messages.push('Letra maiúscula');
    
    if (/[0-9]/.test(password)) strength++;
    else messages.push('Número');
    
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    else messages.push('Caractere especial');
    
    const levels = ['Muito fraca', 'Fraca', 'Razoável', 'Boa', 'Forte'];
    const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#16a34a'];
    
    if (password.length > 0) {
        strengthDiv.innerHTML = `
            <div class="strength-bar">
                <div class="strength-fill" style="width: ${(strength/5)*100}%; background: ${colors[strength-1] || colors[0]}"></div>
            </div>
            <div class="strength-text" style="color: ${colors[strength-1] || colors[0]}">
                ${levels[strength-1] || levels[0]}
                ${messages.length > 0 ? ' - Falta: ' + messages.join(', ') : ''}
            </div>
        `;
    } else {
        strengthDiv.innerHTML = '';
    }
});

// Validação de confirmação de senha
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
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
});

// Validação do formulário
document.querySelector('.user-form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('As senhas não coincidem!');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('A senha deve ter no mínimo 8 caracteres!');
        return false;
    }
});
</script>