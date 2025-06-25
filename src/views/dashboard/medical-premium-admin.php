<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Grid de Estatísticas -->
<div class="stats-grid">
    <!-- Sistema Online -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="stat-trend trend-up">
            <i class="fas fa-arrow-up"></i> Online
        </div>
        <div class="stat-value"><?= $stats['total_users'] ?? 0 ?></div>
        <div class="stat-label">Usuários Ativos</div>
    </div>
    
    <!-- Atividade IA -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-brain"></i>
        </div>
        <div class="stat-trend trend-up">
            <i class="fas fa-arrow-up"></i> +12%
        </div>
        <div class="stat-value"><?= $stats['ai_requests_today'] ?? 0 ?></div>
        <div class="stat-label">Consultas IA Hoje</div>
    </div>
    
    <!-- Sessões Ativas -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-trend trend-up">
            <i class="fas fa-arrow-up"></i> Estável
        </div>
        <div class="stat-value"><?= $stats['active_sessions'] ?? 0 ?></div>
        <div class="stat-label">Sessões Conectadas</div>
    </div>
    
    <!-- Status do Sistema -->
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="stat-trend trend-up">
            <i class="fas fa-check"></i> Saudável
        </div>
        <div class="stat-value">98%</div>
        <div class="stat-label">Saúde do Sistema</div>
    </div>
</div>

<!-- Painel Principal -->
<div class="premium-card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-icon">
                <i class="fas fa-chart-area"></i>
            </div>
            <span>Centro de Controle Médico</span>
        </div>
        <div class="card-actions">
            <button class="btn-premium btn-secondary" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i>
                Atualizar
            </button>
        </div>
    </div>
    
    <!-- Métricas em Tempo Real -->
    <div class="metrics-grid">
        <div class="metric-item">
            <div class="metric-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="metric-content">
                <h4>Base de Dados</h4>
                <div class="metric-value">Conectada</div>
                <div class="metric-status status-healthy"></div>
            </div>
        </div>
        
        <div class="metric-item">
            <div class="metric-icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="metric-content">
                <h4>Motor de IA</h4>
                <div class="metric-value">Operacional</div>
                <div class="metric-status status-healthy"></div>
            </div>
        </div>
        
        <div class="metric-item">
            <div class="metric-icon">
                <i class="fas fa-cloud"></i>
            </div>
            <div class="metric-content">
                <h4>Armazenamento</h4>
                <div class="metric-value">78% Usado</div>
                <div class="metric-status status-warning"></div>
            </div>
        </div>
        
        <div class="metric-item">
            <div class="metric-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="metric-content">
                <h4>Segurança</h4>
                <div class="metric-value">Ativa</div>
                <div class="metric-status status-healthy"></div>
            </div>
        </div>
    </div>
</div>

<!-- Atividades Recentes -->
<div class="premium-card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <span>Atividade do Sistema</span>
        </div>
        <div class="card-actions">
            <a href="/admin/logs" class="btn-premium btn-secondary">
                <i class="fas fa-list"></i>
                Ver Todos
            </a>
        </div>
    </div>
    
    <div class="activity-list">
        <?php if (!empty($activities)): ?>
            <?php foreach (array_slice($activities, 0, 6) as $activity): ?>
                <div class="activity-item">
                    <div class="activity-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title"><?= htmlspecialchars($activity['action'] ?? 'Ação do Sistema') ?></div>
                        <div class="activity-subtitle">
                            <?= htmlspecialchars($activity['user_name'] ?? 'Sistema') ?>
                        </div>
                        <div class="activity-time"><?= $this->timeAgo($activity['created_at'] ?? date('Y-m-d H:i:s')) ?></div>
                    </div>
                    <div class="activity-status">
                        <div class="status-dot status-<?= $activity['status'] ?? 'info' ?>"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="activity-item">
                <div class="activity-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Sistema Iniciado</div>
                    <div class="activity-subtitle">Aguardando atividade do usuário</div>
                    <div class="activity-time">agora</div>
                </div>
                <div class="activity-status">
                    <div class="status-dot status-success"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Painel de Módulos IA -->
<div class="premium-card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-icon">
                <i class="fas fa-brain"></i>
            </div>
            <span>Módulos de IA Médica</span>
        </div>
        <div class="card-actions">
            <a href="/ai" class="btn-premium btn-primary">
                <i class="fas fa-plus"></i>
                Nova Consulta
            </a>
        </div>
    </div>
    
    <div class="modules-grid">
        <?php if (!empty($aiPrompts)): ?>
            <?php foreach (array_slice($aiPrompts, 0, 6) as $prompt): ?>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div class="module-content">
                        <h4><?= htmlspecialchars($prompt['name'] ?? 'Módulo IA') ?></h4>
                        <p><?= htmlspecialchars(substr($prompt['description'] ?? 'Assistente médico especializado', 0, 60)) ?>...</p>
                    </div>
                    <div class="module-action">
                        <a href="/ai?prompt=<?= $prompt['slug'] ?? '' ?>" class="btn-module">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
/* Métricas Grid */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-top: 24px;
}

.metric-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--light-gray);
    border-radius: 12px;
    transition: var(--transition);
}

.metric-item:hover {
    background: var(--medium-gray);
}

.metric-icon {
    width: 48px;
    height: 48px;
    background: var(--gradient-main);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.metric-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.metric-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-medium);
    margin-bottom: 8px;
}

.metric-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-left: auto;
}

.status-healthy {
    background: var(--accent-green);
    box-shadow: 0 0 8px rgba(40, 167, 69, 0.4);
}

.status-warning {
    background: #f59e0b;
    box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);
}

.status-critical {
    background: #ef4444;
    box-shadow: 0 0 8px rgba(239, 68, 68, 0.4);
}

/* Lista de Atividades */
.activity-list {
    margin-top: 24px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid var(--medium-gray);
    transition: var(--transition);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background: var(--light-gray);
    margin: 0 -20px;
    padding: 16px 20px;
    border-radius: 8px;
}

.activity-avatar {
    width: 40px;
    height: 40px;
    background: var(--gradient-main);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 2px;
}

.activity-subtitle {
    font-size: 12px;
    color: var(--text-light);
    margin-bottom: 4px;
}

.activity-time {
    font-size: 11px;
    color: var(--text-light);
    font-family: 'JetBrains Mono', monospace;
}

.activity-status {
    display: flex;
    align-items: center;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-success {
    background: var(--accent-green);
}

.status-info {
    background: var(--primary-blue);
}

.status-warning {
    background: #f59e0b;
}

.status-error {
    background: #ef4444;
}

.card-actions {
    display: flex;
    gap: 8px;
}

/* Módulos Grid */
.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 24px;
}

.module-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--light-gray);
    border-radius: 16px;
    transition: var(--transition);
    border: 1px solid var(--medium-gray);
}

.module-card:hover {
    background: white;
    box-shadow: var(--shadow-medium);
    transform: translateY(-2px);
}

.module-icon {
    width: 48px;
    height: 48px;
    background: var(--gradient-main);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.module-content {
    flex: 1;
}

.module-content h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.module-content p {
    font-size: 12px;
    color: var(--text-light);
    line-height: 1.4;
}

.btn-module {
    width: 32px;
    height: 32px;
    background: var(--primary-blue);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: var(--transition);
    font-size: 12px;
}

.btn-module:hover {
    background: var(--primary-teal);
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 768px) {
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .modules-grid {
        grid-template-columns: 1fr;
    }
    
    .activity-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .activity-status {
        align-self: flex-end;
    }
}
</style>

<script>
// Refresh Data
function refreshData() {
    const btn = event.target.closest('.btn-premium');
    const icon = btn.querySelector('i');
    
    // Animação do botão
    icon.style.animation = 'spin 1s linear';
    btn.style.opacity = '0.7';
    
    // Simular refresh
    setTimeout(() => {
        icon.style.animation = '';
        btn.style.opacity = '1';
        
        // Animar os valores
        document.querySelectorAll('.stat-value').forEach(val => {
            val.style.transform = 'scale(1.1)';
            setTimeout(() => {
                val.style.transform = 'scale(1)';
            }, 200);
        });
        
        // Mostrar notificação
        showNotification('Dados atualizados com sucesso!', 'success');
    }, 1500);
}

// Notificação
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;
    
    Object.assign(notification.style, {
        position: 'fixed',
        top: '24px',
        right: '24px',
        background: type === 'success' ? 'var(--accent-green)' : 'var(--primary-blue)',
        color: 'white',
        padding: '12px 20px',
        borderRadius: '12px',
        display: 'flex',
        alignItems: 'center',
        gap: '8px',
        fontSize: '14px',
        fontWeight: '600',
        zIndex: '9999',
        transform: 'translateX(100%)',
        transition: 'transform 0.3s ease'
    });
    
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Animação de loading nos cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.stat-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>