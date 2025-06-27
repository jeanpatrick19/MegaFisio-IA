<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">🎛️ Central de Comando Administrativo</h1>
<p class="subtitulo-pagina-escuro">Painel completo de controle - Usuários, Robôs Dr. IA, Sistema e Monitoramento em tempo real</p>

<!-- Cards de Estatísticas Principais -->
<div class="grade-estatisticas">
    <!-- Card Total de Usuários -->
    <div class="card-fisio card-estatistica" data-tooltip="Total de usuários cadastrados no sistema">
        <div class="card-icone-grande usuarios">
            <i class="fas fa-users"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= $stats['total_users'] ?? 0 ?></div>
            <div class="stat-label-escuro">Total de Usuários</div>
            <div class="stat-variacao <?= ($stats['users_growth'] ?? 0) >= 0 ? 'positiva' : 'negativa' ?>">
                <i class="fas fa-chart-line"></i> <?= ($stats['users_growth'] ?? 0) > 0 ? '+' . $stats['users_growth'] . '%' : ($stats['users_growth'] < 0 ? $stats['users_growth'] . '%' : 'Estável') ?>
            </div>
        </div>
    </div>
    
    <!-- Card Robôs Dr. IA -->
    <div class="card-fisio card-estatistica" data-tooltip="Robôs Dr. IA ativos no sistema">
        <div class="card-icone-grande robots">
            <i class="fas fa-robot"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= $stats['active_robots'] ?? 0 ?>/<?= $stats['total_robots'] ?? 0 ?></div>
            <div class="stat-label-escuro">Robôs Dr. IA Ativos</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-check-circle"></i> <?= round(($stats['active_robots'] / max($stats['total_robots'], 1)) * 100) ?>% Operacional
            </div>
        </div>
    </div>
    
    <!-- Card Requisições IA -->
    <div class="card-fisio card-estatistica" data-tooltip="Total de requisições de IA processadas">
        <div class="card-icone-grande ia">
            <i class="fas fa-brain"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= number_format($stats['ai_requests_total'] ?? 0) ?></div>
            <div class="stat-label-escuro">Requisições IA Total</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-percentage"></i> <?= $stats['ai_success_rate'] ?? 100 ?>% Taxa de Sucesso
            </div>
        </div>
    </div>
    
    <!-- Card Status do Sistema -->
    <div class="card-fisio card-estatistica" data-tooltip="Status geral do sistema">
        <div class="card-icone-grande <?= $stats['system_health'] >= 90 ? 'sistema-ok' : ($stats['system_health'] >= 70 ? 'sistema-atencao' : 'sistema-critico') ?>">
            <i class="fas fa-server"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor">100%</div>
            <div class="stat-label-escuro">Saúde do Sistema</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-database"></i> <?= $stats['database_size'] ?? 'N/A' ?>
            </div>
        </div>
    </div>
</div>

<!-- Seção de Detalhamento -->
<div class="admin-sections">
    <!-- Seção Usuários -->
    <div class="admin-section">
        <div class="section-header-vertical">
            <h3><i class="fas fa-users"></i> Gestão de Usuários</h3>
            <a href="/admin/users" class="btn-fisio btn-secundario">
                <i class="fas fa-cog"></i> Gerenciar
            </a>
        </div>
        <div class="stats-grid-vertical">
            <div class="mini-stat-vertical">
                <div class="mini-stat-icon admin">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="mini-stat-info">
                    <div class="mini-stat-number"><?= $stats['admins'] ?? 0 ?></div>
                    <div class="mini-stat-label">Administradores</div>
                </div>
            </div>
            <div class="mini-stat-vertical">
                <div class="mini-stat-icon fisio">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="mini-stat-info">
                    <div class="mini-stat-number"><?= $stats['fisioterapeutas'] ?? 0 ?></div>
                    <div class="mini-stat-label">Fisioterapeutas</div>
                </div>
            </div>
            <div class="mini-stat-vertical">
                <div class="mini-stat-icon online">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="mini-stat-info">
                    <div class="mini-stat-number"><?= $stats['active_sessions'] ?? 0 ?></div>
                    <div class="mini-stat-label">Online Agora</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Robôs Dr. IA -->
    <div class="admin-section">
        <div class="section-header-vertical">
            <h3><i class="fas fa-robot"></i> Sistema Dr. IA</h3>
            <a href="/ai" class="btn-fisio btn-secundario">
                <i class="fas fa-brain"></i> IA do Sistema
            </a>
        </div>
        
        <!-- Top 5 Robôs mais usados -->
        <div class="top-robots">
            <h4>🏆 Top 5 Robôs Mais Utilizados</h4>
            <?php if (!empty($stats['top_robots'])): ?>
                <?php foreach ($stats['top_robots'] as $robot): ?>
                <div class="robot-item">
                    <div class="robot-icon">
                        <i class="<?= htmlspecialchars($robot['robot_icon'] ?? 'fas fa-robot') ?>"></i>
                    </div>
                    <div class="robot-info">
                        <div class="robot-name"><?= htmlspecialchars($robot['robot_name']) ?></div>
                        <div class="robot-category"><?= htmlspecialchars($robot['robot_category']) ?></div>
                    </div>
                    <div class="robot-stats">
                        <div class="robot-usage"><?= number_format($robot['usage_count']) ?> usos</div>
                        <div class="robot-success"><?= $robot['success_rate'] ?>% sucesso</div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    Nenhum dado de uso disponível ainda
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Seção Status API -->
    <div class="admin-section">
        <div class="section-header-vertical">
            <h3><i class="fas fa-plug"></i> Status da API</h3>
            <a href="/ai/configuracoes" class="btn-fisio btn-secundario">
                <i class="fas fa-cog"></i> Configurar
            </a>
        </div>
        <div class="api-status-info">
            <div class="api-indicator <?= $stats['api_status'] ?>">
                <i class="fas fa-circle"></i>
                <span><?= ucfirst($stats['api_status'] ?? 'offline') ?></span>
            </div>
            <div class="api-stats">
                <div class="api-stat">
                    <span class="api-stat-label">Requisições Hoje:</span>
                    <span class="api-stat-value"><?= $stats['ai_requests_today'] ?? 0 ?></span>
                </div>
                <div class="api-stat">
                    <span class="api-stat-label">Taxa de Sucesso:</span>
                    <span class="api-stat-value"><?= $stats['ai_success_rate'] ?? 100 ?>%</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Seção Atividades Recentes -->
    <div class="admin-section">
        <div class="section-header-vertical">
            <h3><i class="fas fa-clock"></i> Atividades Recentes</h3>
            <a href="/admin/logs" class="btn-fisio btn-secundario">
                <i class="fas fa-list"></i> Ver Todos
            </a>
        </div>
        <div class="activities-list">
            <?php if (!empty($activities)): ?>
                <?php foreach (array_slice($activities, 0, 5) as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-<?= $activity['icon'] ?? 'circle' ?>"></i>
                    </div>
                    <div class="activity-info">
                        <div class="activity-action"><?= htmlspecialchars($activity['action'] ?? '') ?></div>
                        <div class="activity-time"><?= date('d/m/Y H:i', strtotime($activity['created_at'] ?? 'now')) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    Nenhuma atividade recente
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Seção Robôs por Categoria -->
<div class="admin-sections">
    <div class="admin-section full-width">
        <div class="section-header">
            <h3><i class="fas fa-layer-group"></i> Robôs Dr. IA por Categoria</h3>
        </div>
        <div class="category-grid">
            <?php if (!empty($stats['robots_by_category'])): ?>
                <?php foreach ($stats['robots_by_category'] as $category): ?>
                <div class="category-card">
                    <div class="category-info">
                        <div class="category-name"><?= ucfirst(htmlspecialchars($category['category'])) ?></div>
                        <div class="category-stats">
                            <span class="category-total"><?= $category['total'] ?> total</span>
                            <span class="category-active"><?= $category['active'] ?> ativos</span>
                        </div>
                    </div>
                    <div class="category-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= round(($category['active'] / max($category['total'], 1)) * 100) ?>%"></div>
                        </div>
                        <span class="progress-text"><?= round(($category['active'] / max($category['total'], 1)) * 100) ?>%</span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-info-circle"></i>
                    Nenhuma categoria encontrada
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* CSS específico para o dashboard admin */
.grade-estatisticas {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.card-estatistica {
    display: flex;
    align-items: center;
    gap: 20px;
    background: var(--gradiente-card);
    border: 1px solid var(--cinza-medio);
    transition: var(--transicao);
}

.card-estatistica:hover {
    transform: translateY(-4px);
    box-shadow: var(--sombra-flutuante);
}

.card-icone-grande {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    margin-bottom: 16px;
}

.card-icone-grande.usuarios { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
.card-icone-grande.robots { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
.card-icone-grande.ia { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.card-icone-grande.sistema-ok { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.card-icone-grande.sistema-atencao { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.card-icone-grande.sistema-critico { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

.card-conteudo-stat {
    flex: 1;
}

.stat-valor {
    font-size: 36px;
    font-weight: 800;
    color: var(--cinza-escuro);
    line-height: 1;
    margin-bottom: 8px;
}

.stat-label-escuro {
    font-size: 14px;
    color: var(--cinza-escuro);
    font-weight: 600;
    margin-bottom: 8px;
}

.stat-variacao {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
}

.stat-variacao.positiva { color: var(--sucesso); }
.stat-variacao.negativa { color: var(--erro); }

.admin-sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.admin-section {
    background: var(--branco-puro);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--sombra-media);
    border: 1px solid var(--cinza-medio);
}

.admin-section.full-width {
    grid-column: 1 / -1;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--cinza-medio);
}

.section-header-vertical {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--cinza-medio);
}

.section-header h3 {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

.stats-grid-mini {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 16px;
}

.stats-grid-vertical {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.mini-stat {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
    border: 1px solid var(--cinza-medio);
}

.mini-stat-vertical {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--cinza-claro);
    border-radius: 8px;
    border: 1px solid var(--cinza-medio);
    transition: var(--transicao);
}

.mini-stat-vertical:hover {
    background: white;
    box-shadow: var(--sombra-leve);
}

.mini-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}

.mini-stat-icon.admin { background: var(--azul-saude); }
.mini-stat-icon.fisio { background: var(--verde-terapia); }
.mini-stat-icon.online { background: var(--sucesso); }

.mini-stat-number {
    font-size: 20px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

.mini-stat-label {
    font-size: 11px;
    color: var(--cinza-escuro);
    font-weight: 600;
}

.top-robots {
    margin-top: 16px;
}

.top-robots h4 {
    margin-bottom: 16px;
    color: var(--cinza-escuro);
    font-size: 16px;
}

.robot-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1px solid var(--cinza-medio);
    border-radius: 8px;
    margin-bottom: 8px;
    transition: var(--transicao);
}

.robot-item:hover {
    background: var(--cinza-claro);
}

.robot-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--azul-saude);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.robot-info {
    flex: 1;
}

.robot-name {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.robot-category {
    font-size: 12px;
    color: var(--cinza-medio);
    text-transform: capitalize;
}

.robot-stats {
    text-align: right;
}

.robot-usage, .robot-success {
    font-size: 12px;
    font-weight: 600;
}

.robot-usage { color: var(--azul-saude); }
.robot-success { color: var(--sucesso); }

.api-status-info {
    margin-top: 16px;
}

.api-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    font-weight: 600;
}

.api-indicator.online { color: var(--sucesso); }
.api-indicator.idle { color: var(--alerta); }
.api-indicator.offline { color: var(--erro); }

.api-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.api-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: var(--cinza-claro);
    border-radius: 8px;
}

.api-stat-label {
    font-size: 12px;
    color: var(--cinza-escuro);
}

.api-stat-value {
    font-weight: 700;
    color: var(--azul-saude);
}

.activities-list {
    margin-top: 16px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-bottom: 1px solid var(--cinza-medio);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--cinza-claro);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--azul-saude);
    font-size: 14px;
}

.activity-action {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.activity-time {
    font-size: 12px;
    color: var(--cinza-medio);
}

.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-top: 16px;
}

.category-card {
    padding: 16px;
    border: 1px solid var(--cinza-medio);
    border-radius: 12px;
    background: var(--cinza-claro);
}

.category-name {
    font-weight: 700;
    color: var(--cinza-escuro);
    margin-bottom: 8px;
}

.category-stats {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 12px;
}

.category-total {
    color: var(--cinza-escuro);
    font-weight: 600;
}

.category-active {
    color: var(--sucesso);
    font-weight: 600;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: var(--cinza-medio);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 4px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--azul-saude), var(--verde-terapia));
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 12px;
    font-weight: 600;
    color: var(--azul-saude);
}

.no-data {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 20px;
    text-align: center;
    color: var(--cinza-medio);
    font-style: italic;
    justify-content: center;
}

@media (max-width: 768px) {
    .grade-estatisticas {
        grid-template-columns: 1fr;
    }
    
    .admin-sections {
        grid-template-columns: 1fr;
    }
    
    .stats-grid-mini {
        grid-template-columns: 1fr;
    }
    
    .category-grid {
        grid-template-columns: 1fr;
    }
}
</style>