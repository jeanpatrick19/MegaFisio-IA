<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="cleanup-container">
    <div class="cleanup-header">
        <h1>🧹 Limpeza de Dados do Sistema</h1>
        <p>Remova dados fictícios e mantenha apenas informações reais</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🤖</div>
            <div class="stat-content">
                <h3><?= $stats['ai_prompts'] ?></h3>
                <p>Prompts IA</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3><?= $stats['ai_requests'] ?></h3>
                <p>Requisições IA</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3><?= $stats['users'] ?></h3>
                <p>Usuários</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-content">
                <h3><?= $stats['user_logs'] ?></h3>
                <p>Logs de Atividade</p>
            </div>
        </div>
    </div>
    
    <div class="cleanup-actions">
        <!-- Limpeza de Dados Fictícios -->
        <div class="action-card cleanup-card">
            <div class="action-header">
                <div class="action-icon">🧹</div>
                <div class="action-title">
                    <h3>Limpeza Inteligente</h3>
                    <p>Remove apenas dados fictícios e de teste</p>
                </div>
            </div>
            
            <div class="action-content">
                <h4>Esta ação irá remover:</h4>
                <ul class="cleanup-list">
                    <li>🗑️ Requisições de IA de teste/demo</li>
                    <li>📊 Logs de atividades fictícias</li>
                    <li>🔔 Notificações antigas e de demo</li>
                    <li>⏰ Sessões expiradas (7+ dias)</li>
                </ul>
                
                <form method="POST" action="/admin/data-cleanup/clean" class="cleanup-form">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    
                    <div class="confirmation-input">
                        <label for="confirm_cleanup">Digite "LIMPAR" para confirmar:</label>
                        <input type="text" 
                               id="confirm_cleanup" 
                               name="confirm_cleanup" 
                               placeholder="LIMPAR"
                               required>
                    </div>
                    
                    <button type="submit" class="btn btn-cleanup" disabled>
                        <i class="icon-clean"></i>
                        Executar Limpeza
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Reset Completo do Sistema -->
        <div class="action-card danger-card">
            <div class="action-header">
                <div class="action-icon">⚠️</div>
                <div class="action-title">
                    <h3>Reset Completo</h3>
                    <p>Volta o sistema ao estado inicial</p>
                </div>
            </div>
            
            <div class="action-content">
                <h4>⚠️ ATENÇÃO: Esta ação irá:</h4>
                <ul class="danger-list">
                    <li>🔥 Excluir TODOS os usuários (exceto você)</li>
                    <li>🗑️ Remover TODAS as requisições de IA</li>
                    <li>📊 Apagar TODOS os logs</li>
                    <li>🔔 Limpar TODAS as notificações</li>
                    <li>🤖 Resetar prompts para padrão</li>
                </ul>
                
                <form method="POST" action="/admin/data-cleanup/reset" class="reset-form">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    
                    <div class="confirmation-input">
                        <label for="confirm_reset">Digite "RESETAR_SISTEMA" para confirmar:</label>
                        <input type="text" 
                               id="confirm_reset" 
                               name="confirm_reset" 
                               placeholder="RESETAR_SISTEMA"
                               required>
                    </div>
                    
                    <button type="submit" class="btn btn-danger" disabled>
                        <i class="icon-reset"></i>
                        Resetar Sistema Completo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.cleanup-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.cleanup-header {
    text-align: center;
    margin-bottom: 3rem;
}

.cleanup-header h1 {
    color: #2d3436;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.cleanup-header p {
    color: #636e72;
    font-size: 1.1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: #ffffff;
    border: 1px solid #f1f2f6;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(45, 52, 54, 0.1);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(45, 52, 54, 0.15);
    border-color: #00d4aa;
}

.stat-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 212, 170, 0.1);
    border-radius: 12px;
    color: #00d4aa;
}

.stat-content h3 {
    color: #2d3436;
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.stat-content p {
    color: #636e72;
    margin: 0;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.cleanup-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 2rem;
}

.action-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.action-card:hover {
    transform: translateY(-4px);
}

.cleanup-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border: 2px solid #3b82f6;
}

.danger-card {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d1b1b 100%);
    border: 2px solid #ef4444;
}

.action-header {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.cleanup-card .action-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.danger-card .action-header {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.action-icon {
    font-size: 2rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
}

.action-title h3 {
    color: white;
    margin: 0 0 0.25rem 0;
    font-size: 1.3rem;
    font-weight: 700;
}

.action-title p {
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
    font-size: 0.9rem;
}

.action-content {
    padding: 2rem;
    color: #e0e0e0;
}

.action-content h4 {
    color: #e0e0e0;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.cleanup-list li {
    color: #94a3b8;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.danger-list li {
    color: #fca5a5;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(239, 68, 68, 0.1);
}

.cleanup-list,
.danger-list {
    list-style: none;
    padding: 0;
    margin-bottom: 2rem;
}

.cleanup-list li:last-child,
.danger-list li:last-child {
    border-bottom: none;
}

.cleanup-form,
.reset-form {
    margin-top: 1.5rem;
}

.confirmation-input {
    margin-bottom: 1.5rem;
}

.confirmation-input label {
    display: block;
    color: #e0e0e0;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.confirmation-input input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.confirmation-input input:focus {
    outline: none;
    border-color: #3b82f6;
    background: rgba(255, 255, 255, 0.15);
}

.danger-card .confirmation-input input:focus {
    border-color: #ef4444;
}

.btn {
    padding: 0.875rem 2rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cleanup {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.btn-cleanup:hover:not(:disabled) {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-danger:hover:not(:disabled) {
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
}

.btn:disabled {
    background: #666;
    cursor: not-allowed;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .cleanup-actions {
        grid-template-columns: 1fr;
    }
    
    .action-header {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cleanup form validation
    const cleanupInput = document.getElementById('confirm_cleanup');
    const cleanupBtn = document.querySelector('.btn-cleanup');
    
    if (cleanupInput && cleanupBtn) {
        cleanupInput.addEventListener('input', function() {
            cleanupBtn.disabled = this.value !== 'LIMPAR';
        });
    }
    
    // Reset form validation
    const resetInput = document.getElementById('confirm_reset');
    const resetBtn = document.querySelector('.btn-danger');
    
    if (resetInput && resetBtn) {
        resetInput.addEventListener('input', function() {
            resetBtn.disabled = this.value !== 'RESETAR_SISTEMA';
        });
    }
    
    // Confirmation dialogs
    document.querySelector('.cleanup-form')?.addEventListener('submit', function(e) {
        if (!confirm('Tem certeza que deseja executar a limpeza de dados fictícios?')) {
            e.preventDefault();
        }
    });
    
    document.querySelector('.reset-form')?.addEventListener('submit', function(e) {
        if (!confirm('ATENÇÃO: Esta ação é IRREVERSÍVEL! Tem certeza que deseja resetar TODO o sistema?')) {
            e.preventDefault();
        }
    });
});
</script>