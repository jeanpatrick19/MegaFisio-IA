<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="delete-confirmation-container">
    <div class="danger-zone">
        <div class="danger-header">
            <i class="icon-warning"></i>
            <h2>⚠️ ZONA DE PERIGO</h2>
        </div>
        
        <div class="warning-content">
            <h3>Você está prestes a EXCLUIR PERMANENTEMENTE este usuário:</h3>
            
            <div class="user-details">
                <div class="user-avatar">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <div class="user-info">
                    <h4><?= htmlspecialchars($user['name']) ?></h4>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                    <span class="role-badge role-<?= $user['role'] ?>"><?= ucfirst($user['role']) ?></span>
                </div>
            </div>
            
            <div class="warning-list">
                <h4>Esta ação irá:</h4>
                <ul>
                    <li>🗑️ Remover o usuário PERMANENTEMENTE do banco de dados</li>
                    <li>📊 Excluir TODOS os logs de atividade deste usuário</li>
                    <li>🤖 Apagar TODAS as requisições de IA feitas por este usuário</li>
                    <li>⚠️ Esta ação é IRREVERSÍVEL - não há como desfazer</li>
                </ul>
            </div>
            
            <?php if (isset($error) && $error): ?>
                <div class="error-message">
                    <i class="icon-error"></i>
                    Confirmação inválida. Tente novamente.
                </div>
            <?php endif; ?>
            
            <form method="POST" class="confirmation-form" id="deleteForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                
                <div class="confirmation-fields">
                    <div class="field-group">
                        <label for="user_email">Para confirmar, digite o email do usuário:</label>
                        <input type="email" 
                               id="user_email" 
                               name="user_email" 
                               placeholder="<?= htmlspecialchars($user['email']) ?>"
                               required
                               autocomplete="off">
                    </div>
                    
                    <div class="field-group">
                        <label for="confirm_delete">Digite exatamente "EXCLUIR" (em maiúsculas):</label>
                        <input type="text" 
                               id="confirm_delete" 
                               name="confirm_delete" 
                               placeholder="EXCLUIR"
                               required
                               autocomplete="off">
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="/admin/users" class="btn btn-cancel">
                        <i class="icon-close"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                        <i class="icon-delete"></i>
                        EXCLUIR PERMANENTEMENTE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.delete-confirmation-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.danger-zone {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d1b1b 100%);
    border: 2px solid #ff4444;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(255, 68, 68, 0.1);
}

.danger-header {
    background: linear-gradient(135deg, #ff4444 0%, #cc3333 100%);
    color: white;
    padding: 1.5rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.danger-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.warning-content {
    padding: 2rem;
    color: #e0e0e0;
}

.warning-content h3 {
    color: #ff6666;
    margin-bottom: 1.5rem;
    text-align: center;
    font-size: 1.2rem;
}

.user-details {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    margin-bottom: 2rem;
    border: 1px solid rgba(255, 68, 68, 0.2);
}

.user-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}

.user-info h4 {
    margin: 0 0 0.5rem 0;
    color: white;
    font-size: 1.1rem;
}

.user-info p {
    margin: 0 0 0.5rem 0;
    color: #999;
    font-size: 0.9rem;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.role-admin { background: #ff4444; color: white; }
.role-professional { background: #4f46e5; color: white; }
.role-patient { background: #059669; color: white; }

.warning-list {
    background: rgba(255, 68, 68, 0.1);
    border: 1px solid rgba(255, 68, 68, 0.3);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.warning-list h4 {
    color: #ff6666;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.warning-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.warning-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 68, 68, 0.1);
    color: #e0e0e0;
}

.warning-list li:last-child {
    border-bottom: none;
    color: #ff6666;
    font-weight: 600;
}

.error-message {
    background: rgba(255, 68, 68, 0.2);
    border: 1px solid #ff4444;
    color: #ff6666;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.confirmation-form {
    margin-top: 2rem;
}

.confirmation-fields {
    margin-bottom: 2rem;
}

.field-group {
    margin-bottom: 1.5rem;
}

.field-group label {
    display: block;
    color: #e0e0e0;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.field-group input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 68, 68, 0.3);
    border-radius: 8px;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.field-group input:focus {
    outline: none;
    border-color: #ff4444;
    background: rgba(255, 255, 255, 0.15);
}

.field-group input::placeholder {
    color: #666;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: space-between;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-cancel {
    background: rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.btn-cancel:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #ff4444 0%, #cc3333 100%);
    color: white;
    border: 2px solid #ff4444;
}

.btn-danger:hover:not(:disabled) {
    background: linear-gradient(135deg, #ff6666 0%, #ff4444 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 68, 68, 0.3);
}

.btn-danger:disabled {
    background: #666;
    border-color: #666;
    cursor: not-allowed;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .user-details {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('deleteForm');
    const emailInput = document.getElementById('user_email');
    const confirmInput = document.getElementById('confirm_delete');
    const deleteBtn = document.getElementById('deleteBtn');
    
    const expectedEmail = '<?= htmlspecialchars($user['email']) ?>';
    
    function validateForm() {
        const emailValid = emailInput.value === expectedEmail;
        const confirmValid = confirmInput.value === 'EXCLUIR';
        
        deleteBtn.disabled = !(emailValid && confirmValid);
        
        // Visual feedback
        emailInput.style.borderColor = emailInput.value ? (emailValid ? '#4ade80' : '#ff4444') : 'rgba(255, 68, 68, 0.3)';
        confirmInput.style.borderColor = confirmInput.value ? (confirmValid ? '#4ade80' : '#ff4444') : 'rgba(255, 68, 68, 0.3)';
    }
    
    emailInput.addEventListener('input', validateForm);
    confirmInput.addEventListener('input', validateForm);
    
    form.addEventListener('submit', function(e) {
        if (!confirm('ÚLTIMA CONFIRMAÇÃO: Tem certeza que deseja excluir este usuário PERMANENTEMENTE?')) {
            e.preventDefault();
        }
    });
});
</script>