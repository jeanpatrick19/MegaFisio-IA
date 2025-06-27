<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <div class="page-title-group">
                <h1 class="page-title">
                    <i class="material-icons-outlined">person_add</i>
                    Criar Novo Usuário
                </h1>
                <p class="page-subtitle">Cadastre um novo usuário no sistema</p>
            </div>
            <div class="header-actions">
                <a href="/admin/users" class="btn btn-secondary">
                    <i class="material-icons-outlined">arrow_back</i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="form-container">
            <form id="createUserForm" method="POST" class="user-form">
                <?= $this->csrfField() ?>
                
                <!-- Informações Básicas -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">person</i>
                            Informações Básicas
                        </h3>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name" class="form-label required">Nome Completo</label>
                            <input type="text" id="name" name="name" class="form-control" required maxlength="255" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            <div class="form-help">Nome que será exibido no sistema</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required maxlength="255" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            <div class="form-help">Email único para login no sistema</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" maxlength="20" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                            <div class="form-help">Formato: (11) 99999-9999</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="department" class="form-label">Departamento</label>
                            <input type="text" id="department" name="department" class="form-control" maxlength="100" value="<?= htmlspecialchars($_POST['department'] ?? '') ?>">
                            <div class="form-help">Setor ou departamento do usuário</div>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Acesso -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">security</i>
                            Configurações de Acesso
                        </h3>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="password" class="form-label required">Senha Temporária</label>
                            <div class="password-input-group">
                                <input type="password" id="password" name="password" class="form-control" required minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="material-icons-outlined">visibility</i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="passwordStrengthFill"></div>
                                </div>
                                <div class="strength-text" id="passwordStrengthText">Digite uma senha</div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirm" class="form-label required">Confirmar Senha</label>
                            <div class="password-input-group">
                                <input type="password" id="password_confirm" name="password_confirm" class="form-control" required minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                                    <i class="material-icons-outlined">visibility</i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="role" class="form-label required">Perfil de Acesso</label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">Selecione um perfil</option>
                                <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                <option value="usuario" <?= ($_POST['role'] ?? 'usuario') === 'usuario' ? 'selected' : '' ?>>Fisioterapeuta</option>
                            </select>
                            <div class="form-help">Administrador: acesso total | Fisioterapeuta: permissões específicas</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label required">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                            <div class="form-help">Status inicial da conta</div>
                        </div>
                    </div>
                </div>

                <!-- Informações Profissionais -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">work</i>
                            Informações Profissionais
                        </h3>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="position" class="form-label">Cargo/Função</label>
                            <input type="text" id="position" name="position" class="form-control" maxlength="100" value="<?= htmlspecialchars($_POST['position'] ?? '') ?>">
                            <div class="form-help">Cargo ou função exercida</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="manager_id" class="form-label">Supervisor</label>
                            <select id="manager_id" name="manager_id" class="form-control">
                                <option value="">Selecione um supervisor</option>
                                <?php
                                // Buscar administradores que podem ser supervisores
                                $stmt = $this->db->query("
                                    SELECT id, name, role 
                                    FROM users 
                                    WHERE role = 'admin' 
                                    AND status = 'active' 
                                    AND deleted_at IS NULL 
                                    ORDER BY name
                                ");
                                $managers = $stmt->fetchAll();
                                foreach ($managers as $manager): ?>
                                    <option value="<?= $manager['id'] ?>" <?= ($_POST['manager_id'] ?? '') == $manager['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($manager['name']) ?> (<?= ucfirst($manager['role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-help">Supervisor direto do usuário</div>
                        </div>
                        
                        <div class="form-group form-group-full">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3" maxlength="500"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                            <div class="form-help">Observações internas sobre o usuário</div>
                        </div>
                    </div>
                </div>

                <!-- Configurações Especiais -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">settings</i>
                            Configurações Especiais
                        </h3>
                    </div>
                    
                    <div class="form-options">
                        <div class="form-checkbox">
                            <input type="checkbox" id="force_password_change" name="force_password_change" <?= isset($_POST['force_password_change']) ? 'checked' : 'checked' ?>>
                            <label for="force_password_change">
                                <i class="material-icons-outlined">key</i>
                                Forçar alteração de senha no primeiro login
                                <span class="option-help">Usuário será obrigado a alterar a senha</span>
                            </label>
                        </div>
                        
                        <div class="form-checkbox">
                            <input type="checkbox" id="send_welcome_email" name="send_welcome_email" <?= isset($_POST['send_welcome_email']) ? 'checked' : 'checked' ?>>
                            <label for="send_welcome_email">
                                <i class="material-icons-outlined">mail</i>
                                Enviar email de boas-vindas
                                <span class="option-help">Email com credenciais e instruções</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Ações do Formulário -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/users'">
                        <i class="material-icons-outlined">cancel</i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="material-icons-outlined">person_add</i>
                        Criar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.user-form {
    max-width: 1000px;
    margin: 0 auto;
}

.form-section {
    background: var(--surface-color);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid var(--border-color);
}

.section-header {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-group-full {
    grid-column: 1 / -1;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.form-label.required::after {
    content: '*';
    color: var(--error-color);
    margin-left: 4px;
}

.form-control {
    padding: 12px 16px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: var(--input-bg);
    color: var(--text-primary);
}

.form-control:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(64, 150, 255, 0.1);
}

.form-help {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 4px;
}

.password-input-group {
    position: relative;
    display: flex;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: color 0.2s ease;
}

.password-toggle:hover {
    color: var(--text-primary);
}

.password-strength {
    margin-top: 8px;
}

.strength-bar {
    height: 4px;
    background: var(--border-color);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 4px;
}

.strength-fill {
    height: 100%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-text {
    font-size: 12px;
    font-weight: 500;
}

.form-options {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.form-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.form-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    accent-color: var(--primary-color);
}

.form-checkbox label {
    display: flex;
    flex-direction: column;
    gap: 4px;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-primary);
}

.form-checkbox label i {
    margin-right: 8px;
    font-size: 18px;
    color: var(--primary-color);
}

.option-help {
    font-size: 12px;
    color: var(--text-secondary);
    font-weight: normal;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
    margin-top: 32px;
}

/* Responsividade */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>

<script>
// Alternância de visibilidade da senha
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Validação de força da senha
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthFill = document.getElementById('passwordStrengthFill');
    const strengthText = document.getElementById('passwordStrengthText');
    
    if (password.length === 0) {
        strengthFill.style.width = '0%';
        strengthFill.style.backgroundColor = '';
        strengthText.textContent = 'Digite uma senha';
        strengthText.style.color = '';
        return;
    }
    
    let strength = 0;
    let feedback = [];
    
    // Critérios de força
    if (password.length >= 8) strength += 20;
    else feedback.push('mín. 8 caracteres');
    
    if (/[a-z]/.test(password)) strength += 20;
    else feedback.push('letras minúsculas');
    
    if (/[A-Z]/.test(password)) strength += 20;
    else feedback.push('letras maiúsculas');
    
    if (/\d/.test(password)) strength += 20;
    else feedback.push('números');
    
    if (/[^a-zA-Z\d]/.test(password)) strength += 20;
    else feedback.push('símbolos');
    
    // Atualizar visual
    strengthFill.style.width = strength + '%';
    
    if (strength < 40) {
        strengthFill.style.backgroundColor = '#ef4444';
        strengthText.textContent = 'Fraca - Faltam: ' + feedback.join(', ');
        strengthText.style.color = '#ef4444';
    } else if (strength < 60) {
        strengthFill.style.backgroundColor = '#f59e0b';
        strengthText.textContent = 'Regular - Faltam: ' + feedback.join(', ');
        strengthText.style.color = '#f59e0b';
    } else if (strength < 80) {
        strengthFill.style.backgroundColor = '#10b981';
        strengthText.textContent = 'Boa' + (feedback.length ? ' - Faltam: ' + feedback.join(', ') : '');
        strengthText.style.color = '#10b981';
    } else {
        strengthFill.style.backgroundColor = '#059669';
        strengthText.textContent = 'Excelente';
        strengthText.style.color = '#059669';
    }
});

// Formatação de telefone
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        value = value.replace(/(\d{2})(\d{4})/, '($1) $2');
        value = value.replace(/(\d{2})/, '($1');
    }
    
    e.target.value = value;
});

// Validação de confirmação de senha
document.getElementById('password_confirm').addEventListener('input', function(e) {
    const password = document.getElementById('password').value;
    const confirm = e.target.value;
    
    if (confirm && password !== confirm) {
        e.target.setCustomValidity('As senhas não coincidem');
    } else {
        e.target.setCustomValidity('');
    }
});

// Validação do formulário
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirm').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('As senhas não coincidem. Por favor, verifique.');
        return;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('A senha deve ter pelo menos 8 caracteres.');
        return;
    }
    
    // Desabilitar botão durante envio
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="material-icons-outlined">hourglass_empty</i> Criando...';
});
</script>