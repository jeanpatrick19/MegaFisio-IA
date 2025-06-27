<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <div class="page-title-group">
                <h1 class="page-title">
                    <i class="material-icons-outlined">history</i>
                    Logs de Atividade
                </h1>
                <p class="page-subtitle">Histórico de atividades de <?= htmlspecialchars($user['name']) ?></p>
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
        <div class="logs-container">
            <!-- Informações do Usuário -->
            <div class="user-info-card">
                <div class="user-avatar">
                    <div class="avatar-circle <?= $user['role'] ?>">
                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                    </div>
                </div>
                <div class="user-details">
                    <h3><?= htmlspecialchars($user['name']) ?></h3>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                    <span class="user-role-badge <?= $user['role'] ?>">
                        <?= $user['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta' ?>
                    </span>
                </div>
                <div class="user-stats">
                    <div class="stat-item">
                        <span class="stat-value"><?= count($logs) ?></span>
                        <span class="stat-label">Atividades</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?= $user['last_login'] ? date('d/m/Y', strtotime($user['last_login'])) : 'Nunca' ?></span>
                        <span class="stat-label">Último Login</span>
                    </div>
                </div>
            </div>

            <!-- Lista de Logs -->
            <div class="logs-section">
                <div class="section-header">
                    <h3>Histórico de Atividades</h3>
                    <div class="section-actions">
                        <button class="btn btn-outline" onclick="exportLogs()">
                            <i class="material-icons-outlined">download</i>
                            Exportar
                        </button>
                    </div>
                </div>

                <?php if (empty($logs)): ?>
                    <div class="empty-state">
                        <i class="material-icons-outlined">history</i>
                        <h4>Nenhuma atividade registrada</h4>
                        <p>Este usuário ainda não possui histórico de atividades no sistema.</p>
                    </div>
                <?php else: ?>
                    <div class="logs-timeline">
                        <?php foreach ($logs as $log): ?>
                            <div class="log-item" data-action="<?= htmlspecialchars($log['action']) ?>">
                                <div class="log-icon">
                                    <?php
                                    $iconMap = [
                                        'login' => 'login',
                                        'logout' => 'logout',
                                        'user_created' => 'person_add',
                                        'user_updated' => 'edit',
                                        'password_changed' => 'key',
                                        'permissions_updated' => 'security',
                                        'ai_request' => 'smart_toy',
                                        'export' => 'download',
                                        'delete' => 'delete',
                                        'status_changed' => 'toggle_on'
                                    ];
                                    $icon = $iconMap[$log['action']] ?? 'info';
                                    ?>
                                    <i class="material-icons-outlined"><?= $icon ?></i>
                                </div>
                                
                                <div class="log-content">
                                    <div class="log-header">
                                        <span class="log-action"><?= ucfirst(str_replace('_', ' ', $log['action'])) ?></span>
                                        <span class="log-time"><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></span>
                                    </div>
                                    
                                    <div class="log-description">
                                        <?= htmlspecialchars($log['description']) ?>
                                    </div>
                                    
                                    <?php if (!empty($log['ip_address'])): ?>
                                        <div class="log-meta">
                                            <span class="meta-item">
                                                <i class="material-icons-outlined">public</i>
                                                IP: <?= htmlspecialchars($log['ip_address']) ?>
                                            </span>
                                            <?php if (!empty($log['user_agent'])): ?>
                                                <span class="meta-item">
                                                    <i class="material-icons-outlined">devices</i>
                                                    <?= htmlspecialchars(substr($log['user_agent'], 0, 50)) ?>...
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.logs-container {
    max-width: 1000px;
    margin: 0 auto;
}

.user-info-card {
    display: flex;
    align-items: center;
    gap: 24px;
    background: var(--surface-color);
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 1px solid var(--border-color);
}

.user-avatar .avatar-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 24px;
    color: white;
}

.avatar-circle.admin {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.avatar-circle.usuario {
    background: linear-gradient(135deg, #059669, #10b981);
}

.user-details h3 {
    margin: 0 0 8px 0;
    font-size: 20px;
    color: var(--text-primary);
}

.user-details p {
    margin: 0 0 12px 0;
    color: var(--text-secondary);
}

.user-role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.user-role-badge.admin {
    background: #7c3aed;
}

.user-role-badge.usuario {
    background: #059669;
}

.user-stats {
    display: flex;
    gap: 24px;
    margin-left: auto;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
}

.logs-section {
    background: var(--surface-color);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
}

.section-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.3;
}

.empty-state h4 {
    font-size: 20px;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.logs-timeline {
    position: relative;
}

.logs-timeline::before {
    content: '';
    position: absolute;
    left: 24px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

.log-item {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid var(--border-light);
}

.log-item:last-child {
    border-bottom: none;
}

.log-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--surface-color);
    border: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
}

.log-item[data-action="login"] .log-icon {
    background: #10b981;
    border-color: #10b981;
    color: white;
}

.log-item[data-action="logout"] .log-icon {
    background: #6b7280;
    border-color: #6b7280;
    color: white;
}

.log-item[data-action*="user"] .log-icon {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.log-item[data-action*="password"] .log-icon {
    background: #f59e0b;
    border-color: #f59e0b;
    color: white;
}

.log-item[data-action*="ai"] .log-icon {
    background: #8b5cf6;
    border-color: #8b5cf6;
    color: white;
}

.log-content {
    flex: 1;
    min-width: 0;
}

.log-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.log-action {
    font-weight: 600;
    color: var(--text-primary);
    text-transform: capitalize;
}

.log-time {
    font-size: 12px;
    color: var(--text-secondary);
    font-family: monospace;
}

.log-description {
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 8px;
}

.log-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--text-secondary);
}

.meta-item i {
    font-size: 14px;
}

@media (max-width: 768px) {
    .user-info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .user-stats {
        margin-left: 0;
        justify-content: center;
    }
    
    .section-header {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
    }
    
    .logs-timeline::before {
        left: 16px;
    }
    
    .log-icon {
        width: 32px;
        height: 32px;
    }
    
    .log-meta {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<script>
function exportLogs() {
    // Implementar exportação de logs
    const userId = <?= $user['id'] ?>;
    window.open(`/admin/users/export-logs?id=${userId}`, '_blank');
}
</script>