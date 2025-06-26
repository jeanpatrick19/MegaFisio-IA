<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <div class="page-title-group">
                <h1 class="page-title">
                    <i class="material-icons-outlined">edit</i>
                    Editar Usuário
                </h1>
                <p class="page-subtitle">Edite as informações de <?= htmlspecialchars($user['name']) ?></p>
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
            <form id="editUserForm" method="POST" class="user-form">
                <?= $this->csrfField() ?>
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                
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
                            <input type="text" id="name" name="name" class="form-control" required maxlength="255" value="<?= htmlspecialchars($user['name']) ?>">
                            <div class="form-help">Nome que será exibido no sistema</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" readonly value="<?= htmlspecialchars($user['email']) ?>">
                            <div class="form-help">Email não pode ser alterado</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" maxlength="20" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            <div class="form-help">Formato: (11) 99999-9999</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="department" class="form-label">Departamento</label>
                            <input type="text" id="department" name="department" class="form-control" maxlength="100" value="<?= htmlspecialchars($user['department'] ?? '') ?>">
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
                            <label for="role" class="form-label required">Perfil de Acesso</label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">Selecione um perfil</option>
                                <option value="super_admin" <?= $user['role'] === 'super_admin' ? 'selected' : '' ?>>Super Administrador</option>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                                <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Gerente</option>
                                <option value="professional" <?= $user['role'] === 'professional' ? 'selected' : '' ?>>Profissional</option>
                                <option value="assistant" <?= $user['role'] === 'assistant' ? 'selected' : '' ?>>Assistente</option>
                                <option value="viewer" <?= $user['role'] === 'viewer' ? 'selected' : '' ?>>Visualizador</option>
                            </select>
                            <div class="form-help">Determina as permissões do usuário</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label required">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                            <div class="form-help">Status da conta do usuário</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Último Login</label>
                            <div class="info-display">
                                <?php if ($user['last_login']): ?>
                                    <?= date('d/m/Y H:i:s', strtotime($user['last_login'])) ?>
                                <?php else: ?>
                                    Nunca fez login
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Membro desde</label>
                            <div class="info-display">
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </div>
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
                            <input type="text" id="position" name="position" class="form-control" maxlength="100" value="<?= htmlspecialchars($user['position'] ?? '') ?>">
                            <div class="form-help">Cargo ou função exercida</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="manager_id" class="form-label">Supervisor</label>
                            <select id="manager_id" name="manager_id" class="form-control">
                                <option value="">Selecione um supervisor</option>
                                <?php
                                // Buscar usuários que podem ser supervisores (exceto o próprio usuário)
                                $stmt = $this->db->prepare("
                                    SELECT id, name, role 
                                    FROM users 
                                    WHERE role IN ('admin', 'manager', 'super_admin') 
                                    AND status = 'active' 
                                    AND deleted_at IS NULL 
                                    AND id != ?
                                    ORDER BY name
                                ");
                                $stmt->execute([$user['id']]);
                                $managers = $stmt->fetchAll();
                                foreach ($managers as $manager): ?>
                                    <option value="<?= $manager['id'] ?>" <?= ($user['manager_id'] ?? '') == $manager['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($manager['name']) ?> (<?= ucfirst($manager['role']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-help">Supervisor direto do usuário</div>
                        </div>
                        
                        <div class="form-group form-group-full">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3" maxlength="500"><?= htmlspecialchars($user['notes'] ?? '') ?></textarea>
                            <div class="form-help">Observações internas sobre o usuário</div>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Segurança -->
                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">shield</i>
                            Configurações de Segurança
                        </h3>
                    </div>
                    
                    <div class="security-actions">
                        <div class="security-action">
                            <div class="action-info">
                                <h4>Forçar Alteração de Senha</h4>
                                <p>Obriga o usuário a alterar a senha no próximo login</p>
                            </div>
                            <div class="action-controls">
                                <input type="checkbox" id="force_password_change" name="force_password_change" <?= $user['must_change_password'] ? 'checked' : '' ?>>
                                <label for="force_password_change" class="toggle-label">Forçar</label>
                            </div>
                        </div>
                        
                        <div class="security-action">
                            <div class="action-info">
                                <h4>Redefinir Senha</h4>
                                <p>Enviar link para redefinir senha por email</p>
                            </div>
                            <div class="action-controls">
                                <button type="button" class="btn btn-outline" onclick="resetPassword()">
                                    <i class="material-icons-outlined">refresh</i>
                                    Enviar Reset
                                </button>
                            </div>
                        </div>
                        
                        <?php if ($user['locked_until'] && strtotime($user['locked_until']) > time()): ?>
                        <div class="security-action">
                            <div class="action-info">
                                <h4>Conta Bloqueada</h4>
                                <p>Usuário bloqueado até <?= date('d/m/Y H:i', strtotime($user['locked_until'])) ?></p>
                            </div>
                            <div class="action-controls">
                                <button type="button" class="btn btn-warning" onclick="unlockUser()">
                                    <i class="material-icons-outlined">lock_open</i>
                                    Desbloquear
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ações do Formulário -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/users'">
                        <i class="material-icons-outlined">cancel</i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="material-icons-outlined">save</i>
                        Salvar Alterações
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

.form-control[readonly] {
    background: var(--disabled-bg);
    color: var(--text-secondary);
    cursor: not-allowed;
}

.form-help {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 4px;
}

.info-display {
    padding: 12px 16px;
    background: var(--surface-secondary);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-secondary);
    font-size: 14px;
}

.security-actions {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.security-action {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: var(--surface-secondary);
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.action-info h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.action-info p {
    margin: 0;
    font-size: 14px;
    color: var(--text-secondary);
}

.action-controls {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toggle-label {
    cursor: pointer;
    font-weight: 500;
    color: var(--text-primary);
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
    
    .security-action {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
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

// Função para redefinir senha
function resetPassword() {
    if (confirm('Deseja enviar um link de redefinição de senha para o email do usuário?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/users/reset-password';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = document.querySelector('input[name="csrf_token"]').value;
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = '<?= $user['id'] ?>';
        
        form.appendChild(csrfInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Função para desbloquear usuário
function unlockUser() {
    if (confirm('Deseja desbloquear este usuário?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/users/unlock';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = 'csrf_token';
        csrfInput.value = document.querySelector('input[name="csrf_token"]').value;
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = '<?= $user['id'] ?>';
        
        form.appendChild(csrfInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Validação do formulário
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="material-icons-outlined">hourglass_empty</i> Salvando...';
});
</script>