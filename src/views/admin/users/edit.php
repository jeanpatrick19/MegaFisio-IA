<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <h1>Editar Usuário</h1>
            <p>Editando: <strong><?= htmlspecialchars($user['name']) ?></strong></p>
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
                <h3>Informações Básicas</h3>
                
                <div class="user-info-display">
                    <div class="user-avatar-large">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <div class="detail-item">
                            <label>Email:</label>
                            <span><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Cadastrado em:</label>
                            <span><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Último login:</label>
                            <span><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Nunca' ?></span>
                        </div>
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
                            placeholder="Ex: João da Silva"
                        >
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Configurações da Conta</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Perfil *</label>
                        <select id="role" name="role" required class="form-control">
                            <option value="usuario" <?= ($_POST['role'] ?? $user['role']) === 'usuario' ? 'selected' : '' ?>>
                                Usuário
                            </option>
                            <option value="admin" <?= ($_POST['role'] ?? $user['role']) === 'admin' ? 'selected' : '' ?>>
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
                            <option value="active" <?= ($_POST['status'] ?? $user['status']) === 'active' ? 'selected' : '' ?>>
                                Ativo
                            </option>
                            <option value="inactive" <?= ($_POST['status'] ?? $user['status']) === 'inactive' ? 'selected' : '' ?>>
                                Inativo
                            </option>
                        </select>
                        <small class="form-help">
                            Usuários inativos não conseguem fazer login
                        </small>
                    </div>
                </div>
                
                <?php if ($user['login_attempts'] > 0 || $user['locked_until']): ?>
                    <div class="alert alert-warning">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <div>
                            <strong>Conta com tentativas de login falhadas</strong><br>
                            Tentativas: <?= $user['login_attempts'] ?> 
                            <?php if ($user['locked_until']): ?>
                                • Bloqueada até: <?= date('d/m/Y H:i', strtotime($user['locked_until'])) ?>
                            <?php endif; ?>
                            <br>
                            <a href="<?= BASE_URL ?>/admin/users/unlock?id=<?= $user['id'] ?>" class="btn-link">
                                Desbloquear conta
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-section">
                <h3>Ações Adicionais</h3>
                
                <div class="action-buttons">
                    <a href="<?= BASE_URL ?>/admin/users/change-password?id=<?= $user['id'] ?>" class="btn btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <circle cx="12" cy="16" r="1"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Alterar Senha
                    </a>
                    
                    <?php if ($user['login_attempts'] > 0 || $user['locked_until']): ?>
                        <form method="POST" action="<?= BASE_URL ?>/admin/users/unlock" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit" class="btn btn-warning">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 9.9-1"/>
                                </svg>
                                Desbloquear Conta
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>/admin/users/logs?id=<?= $user['id'] ?>" class="btn btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        Ver Logs
                    </a>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                    </svg>
                    Salvar Alterações
                </button>
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Cancelar</a>
                
                <?php if ($user['id'] != $this->user['id']): ?>
                    <button 
                        type="button" 
                        class="btn btn-danger btn-delete" 
                        onclick="confirmDelete('<?= htmlspecialchars($user['name']) ?>', <?= $user['id'] ?>)"
                    >
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <polyline points="3,6 5,6 21,6"/>
                            <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"/>
                        </svg>
                        Excluir Usuário
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Exclusão</h3>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <div>
                    <strong>Ação irreversível!</strong><br>
                    Tem certeza que deseja excluir o usuário <strong id="deleteUserName"></strong>?
                </div>
            </div>
            
            <p>Esta ação irá:</p>
            <ul>
                <li>Marcar o usuário como excluído (soft delete)</li>
                <li>Impedir que o usuário faça login</li>
                <li>Manter os dados para auditoria</li>
                <li>Registrar a ação nos logs</li>
            </ul>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
            <form id="deleteForm" method="POST" action="<?= BASE_URL ?>/admin/users/delete" style="display: inline;">
                <input type="hidden" name="id" id="deleteUserId">
                <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(userName, userId) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

// Fechar modal ao clicar fora
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Validação do formulário
document.querySelector('.user-form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    
    if (name.length < 3) {
        e.preventDefault();
        alert('O nome deve ter no mínimo 3 caracteres!');
        document.getElementById('name').focus();
        return false;
    }
});

// Destaque de mudanças
const originalValues = {
    name: '<?= addslashes($user['name']) ?>',
    role: '<?= $user['role'] ?>',
    status: '<?= $user['status'] ?>'
};

function checkChanges() {
    let hasChanges = false;
    
    ['name', 'role', 'status'].forEach(field => {
        const element = document.getElementById(field);
        const isChanged = element.value !== originalValues[field];
        
        element.classList.toggle('changed', isChanged);
        if (isChanged) hasChanges = true;
    });
    
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.classList.toggle('highlight', hasChanges);
}

['name', 'role', 'status'].forEach(field => {
    document.getElementById(field).addEventListener('input', checkChanges);
    document.getElementById(field).addEventListener('change', checkChanges);
});
</script>