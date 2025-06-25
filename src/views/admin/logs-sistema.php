<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Logs do Sistema</h1>
<p class="subtitulo-pagina-escuro">Acompanhe todas as atividades e eventos do sistema em tempo real</p>

<!-- Estatísticas dos Logs -->
<div class="grid-fisio" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-list-alt"></i>
                <span>Total de Logs</span>
            </div>
        </div>
        <div class="stat-valor"><?= number_format($stats['total_logs']) ?></div>
        <div class="stat-descricao">Registros históricos</div>
    </div>

    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-calendar-day"></i>
                <span>Logs Hoje</span>
            </div>
        </div>
        <div class="stat-valor"><?= number_format($stats['logs_today']) ?></div>
        <div class="stat-descricao">Atividades de hoje</div>
    </div>

    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-users"></i>
                <span>Usuários Ativos</span>
            </div>
        </div>
        <div class="stat-valor"><?= number_format($stats['unique_users_today']) ?></div>
        <div class="stat-descricao">Usuários únicos hoje</div>
    </div>

    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-star"></i>
                <span>Mais Ativo</span>
            </div>
        </div>
        <div class="stat-valor-texto"><?= htmlspecialchars($stats['most_active_user']) ?></div>
        <div class="stat-descricao">Usuário mais ativo hoje</div>
    </div>
</div>

<!-- Lista de Logs -->
<div class="card-fisio">
    <div class="card-header-fisio">
        <div class="card-titulo">
            <i class="fas fa-history"></i>
            <span>Atividades Recentes</span>
        </div>
        <div class="card-acoes">
            <button class="btn-fisio btn-secundario" onclick="recarregarLogs()">
                <i class="fas fa-sync"></i>
                Atualizar
            </button>
        </div>
    </div>

    <div class="logs-container">
        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>Nenhum log encontrado</h3>
                <p>As atividades do sistema aparecerão aqui</p>
            </div>
        <?php else: ?>
            <div class="logs-lista">
                <?php foreach ($logs as $log): ?>
                    <div class="log-item">
                        <div class="log-icon">
                            <i class="fas fa-circle" style="color: #059669;"></i>
                        </div>
                        <div class="log-content">
                            <div class="log-description">
                                <?= htmlspecialchars($log['description'] ?? 'Atividade do sistema') ?>
                            </div>
                            <div class="log-meta">
                                <?php if (!empty($log['user_name'])): ?>
                                    <span class="log-user">
                                        <i class="fas fa-user"></i>
                                        <?= htmlspecialchars($log['user_name']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="log-time">
                                    <i class="fas fa-clock"></i>
                                    <?php
                                    $date = new DateTime($log['created_at']);
                                    $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));
                                    echo $date->format('d/m/Y H:i:s');
                                    ?>
                                </span>
                                <?php if (!empty($log['ip_address'])): ?>
                                    <span class="log-ip">
                                        <i class="fas fa-globe"></i>
                                        <?= htmlspecialchars($log['ip_address']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.stat-valor {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--azul-saude);
    margin: 16px 0 8px 0;
}

.stat-valor-texto {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--verde-terapia);
    margin: 16px 0 8px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stat-descricao {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}

.logs-container {
    padding: 24px;
}

.logs-lista {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.log-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
    border-left: 4px solid var(--verde-terapia);
}

.log-icon {
    margin-top: 4px;
}

.log-content {
    flex: 1;
}

.log-description {
    font-weight: 500;
    color: var(--cinza-escuro);
    margin-bottom: 8px;
}

.log-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 0.85rem;
    color: #6b7280;
}

.log-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.empty-state i {
    font-size: 4rem;
    color: var(--verde-terapia);
    margin-bottom: 20px;
}

.empty-state h3 {
    color: var(--azul-saude);
    margin-bottom: 8px;
}

@media (max-width: 768px) {
    .log-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .stat-valor {
        font-size: 2rem;
    }
}
</style>

<script>
function recarregarLogs() {
    window.location.reload();
}

// Auto-recarregar logs a cada 30 segundos
setInterval(function() {
    // Apenas recarregar se a página estiver visível
    if (!document.hidden) {
        recarregarLogs();
    }
}, 30000);
</script>