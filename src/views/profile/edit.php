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
                    
                    <div class="security-options">
                        <a href="<?= BASE_URL ?>/change-password" class="security-option">
                            <div class="security-option-icon">🔐</div>
                            <div class="security-option-content">
                                <div class="security-option-title">Alterar Senha</div>
                                <div class="security-option-desc">Definir uma nova senha de acesso</div>
                            </div>
                            <div class="security-option-arrow">→</div>
                        </a>
                        
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
</script>