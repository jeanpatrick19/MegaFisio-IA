<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Dashboard Profissional Avançado -->
<div class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                Dashboard Profissional
            </h1>
            <p class="dashboard-subtitle">
                Olá, <strong><?= htmlspecialchars($user['name']) ?></strong>! 
                Aqui está o resumo da sua atividade com IA
            </p>
        </div>
        <div class="header-right">
            <div class="quick-stats">
                <div class="quick-stat">
                    <span class="quick-number"><?= $stats['active_robots'] ?? 0 ?></span>
                    <span class="quick-label">Robôs Ativos</span>
                </div>
                <div class="quick-stat">
                    <span class="quick-number"><?= number_format($stats['ai_requests'] ?? 0) ?></span>
                    <span class="quick-label">Total de Consultas</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Métricas Principais -->
<div class="metrics-grid">
    <!-- Consultas IA -->
    <div class="metric-card primary">
        <div class="metric-header">
            <div class="metric-icon brain">
                <i class="fas fa-brain"></i>
            </div>
            <div class="metric-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12%</span>
            </div>
        </div>
        <div class="metric-content">
            <h3 class="metric-number"><?= number_format($stats['ai_requests'] ?? 0) ?></h3>
            <p class="metric-label">Consultas IA Realizadas</p>
            <div class="metric-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 75%"></div>
                </div>
                <span class="progress-text">75% da meta mensal</span>
            </div>
        </div>
    </div>

    <!-- Uso Mensal -->
    <div class="metric-card secondary">
        <div class="metric-header">
            <div class="metric-icon calendar">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="metric-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+8%</span>
            </div>
        </div>
        <div class="metric-content">
            <h3 class="metric-number">
                <?php 
                $thisMonth = 0;
                if (!empty($stats['monthly_usage'])) {
                    $thisMonth = $stats['monthly_usage'][0]['requests'] ?? 0;
                }
                echo number_format($thisMonth);
                ?>
            </h3>
            <p class="metric-label">Consultas Este Mês</p>
            <div class="metric-chart">
                <canvas id="monthlyChart" width="100" height="40"></canvas>
            </div>
        </div>
    </div>

    <!-- Robôs Disponíveis -->
    <div class="metric-card accent">
        <div class="metric-header">
            <div class="metric-icon robot">
                <i class="fas fa-robot"></i>
            </div>
            <div class="metric-status online">
                <i class="fas fa-circle"></i>
                <span>Online</span>
            </div>
        </div>
        <div class="metric-content">
            <h3 class="metric-number"><?= $stats['active_robots'] ?? 0 ?><span class="metric-total">/<?= $stats['available_robots'] ?? 0 ?></span></h3>
            <p class="metric-label">Robôs IA Liberados</p>
            <div class="robot-grid">
                <?php if (!empty($stats['user_robots']['usable'])): ?>
                    <?php foreach (array_slice($stats['user_robots']['usable'], 0, 4) as $robot): ?>
                        <div class="robot-mini" title="<?= htmlspecialchars($robot['robot_name']) ?>">
                            <i class="<?= htmlspecialchars($robot['robot_icon']) ?>"></i>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Produtividade -->
    <div class="metric-card success">
        <div class="metric-header">
            <div class="metric-icon document">
                <i class="fas fa-file-medical"></i>
            </div>
            <div class="metric-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+15%</span>
            </div>
        </div>
        <div class="metric-content">
            <h3 class="metric-number"><?= number_format($stats['documents_created'] ?? 0) ?></h3>
            <p class="metric-label">Documentos Gerados</p>
            <div class="efficiency-score">
                <div class="score-circle">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <path class="circle" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <text x="18" y="20.35" class="percentage">85%</text>
                    </svg>
                </div>
                <span class="score-label">Eficiência</span>
            </div>
        </div>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="dashboard-content">
    <!-- Coluna Esquerda -->
    <div class="content-left">
        <!-- Robôs IA Disponíveis -->
        <div class="dashboard-widget">
            <div class="widget-header">
                <h3><i class="fas fa-robot"></i> Seus Robôs IA</h3>
                <a href="<?= BASE_URL ?>/ai" class="widget-action">
                    <i class="fas fa-external-link-alt"></i> Ver Todos
                </a>
            </div>
            <div class="widget-content">
                <?php if (!empty($stats['user_robots']['visible'])): ?>
                    <div class="robots-list">
                        <?php foreach ($stats['user_robots']['visible'] as $robot): 
                            $categoryColors = [
                                'marketing' => 'marketing',
                                'atendimento' => 'atendimento', 
                                'vendas' => 'vendas',
                                'clinica' => 'clinica',
                                'educacao' => 'educacao'
                            ];
                            $colorClass = $categoryColors[$robot['robot_category']] ?? 'clinica';
                        ?>
                            <div class="robot-item <?= !$robot['can_use'] ? 'restricted' : '' ?>">
                                <div class="robot-avatar <?= $colorClass ?>">
                                    <i class="<?= htmlspecialchars($robot['robot_icon']) ?>"></i>
                                </div>
                                <div class="robot-info">
                                    <h4 class="robot-name"><?= htmlspecialchars($robot['robot_name']) ?></h4>
                                    <p class="robot-category"><?= ucfirst($robot['robot_category']) ?></p>
                                </div>
                                <div class="robot-actions">
                                    <?php if ($robot['can_use']): ?>
                                        <a href="<?= BASE_URL ?>/ai/<?= htmlspecialchars($robot['robot_slug']) ?>" class="btn-robot use">
                                            <i class="fas fa-play"></i> Usar
                                        </a>
                                    <?php else: ?>
                                        <span class="btn-robot view">
                                            <i class="fas fa-eye"></i> Visualizar
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state-widget">
                        <i class="fas fa-robot"></i>
                        <h4>Nenhum robô disponível</h4>
                        <p>Entre em contato com o administrador para liberar acesso aos robôs IA</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Atividade Recente -->
        <div class="dashboard-widget">
            <div class="widget-header">
                <h3><i class="fas fa-history"></i> Atividade Recente</h3>
                <div class="time-filter">
                    <button class="time-btn active" data-period="7">7 dias</button>
                    <button class="time-btn" data-period="30">30 dias</button>
                </div>
            </div>
            <div class="widget-content">
                <?php if (!empty($stats['monthly_usage'])): ?>
                    <div class="activity-chart">
                        <canvas id="activityChart" height="200"></canvas>
                    </div>
                    <div class="activity-summary">
                        <div class="summary-item">
                            <span class="summary-number"><?= number_format(array_sum(array_column($stats['monthly_usage'], 'requests'))) ?></span>
                            <span class="summary-label">Total de Consultas</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-number"><?= count($stats['monthly_usage']) ?></span>
                            <span class="summary-label">Meses Ativos</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state-widget">
                        <i class="fas fa-chart-line"></i>
                        <h4>Sem atividade registrada</h4>
                        <p>Comece a usar os robôs IA para ver suas estatísticas aqui</p>
                        <a href="<?= BASE_URL ?>/ai" class="btn-primary">
                            <i class="fas fa-rocket"></i> Começar Agora
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Coluna Direita -->
    <div class="content-right">
        <!-- Acesso Rápido -->
        <div class="dashboard-widget">
            <div class="widget-header">
                <h3><i class="fas fa-zap"></i> Acesso Rápido</h3>
            </div>
            <div class="widget-content">
                <?php if (!empty($stats['user_robots']['usable'])): ?>
                    <div class="quick-access-grid">
                        <?php foreach (array_slice($stats['user_robots']['usable'], 0, 6) as $robot): 
                            $quickActions = [
                                'dr_autoritas' => ['name' => 'Conteúdo', 'desc' => 'Posts para Instagram'],
                                'dr_acolhe' => ['name' => 'WhatsApp', 'desc' => 'Mensagens de acolhimento'], 
                                'dr_fechador' => ['name' => 'Vendas', 'desc' => 'Conversão de leads'],
                                'dr_reab' => ['name' => 'Exercícios', 'desc' => 'Protocolos terapêuticos'],
                                'dra_protoc' => ['name' => 'Protocolos', 'desc' => 'Planos de tratamento'],
                                'dra_edu' => ['name' => 'Educação', 'desc' => 'Material educativo']
                            ];
                            $action = $quickActions[$robot['robot_slug']] ?? ['name' => $robot['robot_name'], 'desc' => 'Assistente IA'];
                        ?>
                            <a href="<?= BASE_URL ?>/ai/<?= htmlspecialchars($robot['robot_slug']) ?>" class="quick-action-card">
                                <div class="quick-icon">
                                    <i class="<?= htmlspecialchars($robot['robot_icon']) ?>"></i>
                                </div>
                                <div class="quick-content">
                                    <h4><?= htmlspecialchars($action['name']) ?></h4>
                                    <p><?= htmlspecialchars($action['desc']) ?></p>
                                </div>
                                <div class="quick-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state-widget">
                        <i class="fas fa-lock"></i>
                        <h4>Sem acesso liberado</h4>
                        <p>Solicite ao administrador para liberar o uso dos robôs IA</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informações da Conta -->
        <div class="dashboard-widget">
            <div class="widget-header">
                <h3><i class="fas fa-user-circle"></i> Sua Conta</h3>
                <span class="status-indicator online">
                    <i class="fas fa-circle"></i> Ativo
                </span>
            </div>
            <div class="widget-content">
                <div class="account-info">
                    <div class="account-avatar">
                        <?php
                        if (!empty($user['avatar_path']) && $user['avatar_type'] === 'upload') {
                            echo '<img src="' . htmlspecialchars($user['avatar_path']) . '" alt="Avatar" class="avatar-img">';
                        } else {
                            $initials = strtoupper(substr($user['name'] ?? 'U', 0, 2));
                            echo '<div class="avatar-letter">' . $initials . '</div>';
                        }
                        ?>
                    </div>
                    <div class="account-details">
                        <h4><?= htmlspecialchars($user['name']) ?></h4>
                        <p class="account-role">Fisioterapeuta Profissional</p>
                        <p class="account-since">Membro desde <?= isset($user['created_at']) && $user['created_at'] ? date('M/Y', strtotime($user['created_at'])) : date('M/Y') ?></p>
                    </div>
                </div>
                <div class="account-stats">
                    <div class="account-stat">
                        <span class="stat-number"><?= $stats['active_robots'] ?? 0 ?></span>
                        <span class="stat-label">Robôs Ativos</span>
                    </div>
                    <div class="account-stat">
                        <span class="stat-number"><?= number_format($stats['ai_requests'] ?? 0) ?></span>
                        <span class="stat-label">Consultas Totais</span>
                    </div>
                    <div class="account-stat">
                        <span class="stat-number">
                            <?php 
                            $days = 0;
                            if (isset($user['created_at']) && $user['created_at']) {
                                $days = ceil((time() - strtotime($user['created_at'])) / 86400);
                            }
                            echo $days > 0 ? $days : '30+';
                            ?>
                        </span>
                        <span class="stat-label">Dias de Uso</span>
                    </div>
                </div>
                <div class="account-actions">
                    <a href="<?= BASE_URL ?>/profile" class="btn-account">
                        <i class="fas fa-cog"></i> Configurações
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para Gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Dashboard Profissional Moderno */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow-elevated: 0 20px 40px rgba(0, 0, 0, 0.1);
    --shadow-floating: 0 10px 30px rgba(0, 0, 0, 0.15);
}

/* Header do Dashboard */
.dashboard-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 32px;
    color: white;
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.dashboard-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
}

.quick-stats {
    display: flex;
    gap: 32px;
}

.quick-stat {
    text-align: center;
}

.quick-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.quick-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Grid de Métricas */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.metric-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    box-shadow: var(--shadow-floating);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.metric-card.secondary::before { background: var(--secondary-gradient); }
.metric-card.accent::before { background: var(--success-gradient); }
.metric-card.success::before { background: var(--warning-gradient); }

.metric-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-elevated);
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.metric-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.metric-icon.brain { background: var(--primary-gradient); }
.metric-icon.calendar { background: var(--secondary-gradient); }
.metric-icon.robot { background: var(--success-gradient); }
.metric-icon.document { background: var(--warning-gradient); }

.metric-trend {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 8px;
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
}

.metric-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #16a34a;
}

.metric-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 8px;
}

.metric-total {
    font-size: 1.5rem;
    opacity: 0.5;
    font-weight: 400;
}

.metric-label {
    font-size: 1rem;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 16px;
}

.metric-progress {
    margin-top: 16px;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: var(--primary-gradient);
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

.robot-grid {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.robot-mini {
    width: 32px;
    height: 32px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #1f2937;
}

/* Gráfico Circular */
.efficiency-score {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-top: 16px;
}

.score-circle {
    width: 60px;
    height: 60px;
}

.circular-chart {
    display: block;
    margin: 0 auto;
    max-width: 60px;
    max-height: 60px;
}

.circle-bg {
    fill: none;
    stroke: #f3f4f6;
    stroke-width: 2.8;
}

.circle {
    fill: none;
    stroke: #16a34a;
    stroke-width: 2.8;
    stroke-linecap: round;
    animation: progress 1s ease-in-out forwards;
}

.percentage {
    fill: #1f2937;
    font-family: 'Inter', sans-serif;
    font-size: 8px;
    font-weight: 600;
    text-anchor: middle;
}

@keyframes progress {
    0% { stroke-dasharray: 0 100; }
}

.score-label {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}

/* Conteúdo Principal */
.dashboard-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 32px;
}

.dashboard-widget {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-floating);
    margin-bottom: 24px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.widget-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 28px 20px;
    border-bottom: 1px solid #f3f4f6;
    background: rgba(249, 250, 251, 0.5);
}

.widget-header h3 {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
}

.widget-action {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #6366f1;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

.widget-action:hover {
    color: #4f46e5;
    transform: translateX(4px);
}

.widget-content {
    padding: 28px;
}

/* Lista de Robôs */
.robots-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.robot-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: rgba(249, 250, 251, 0.5);
    border-radius: 16px;
    border: 1px solid #f3f4f6;
    transition: all 0.3s ease;
}

.robot-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    background: white;
}

.robot-item.restricted {
    opacity: 0.6;
    background: rgba(249, 250, 251, 0.3);
}

.robot-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.robot-avatar.marketing { background: linear-gradient(135deg, #e91e63 0%, #f06292 100%); }
.robot-avatar.atendimento { background: linear-gradient(135deg, #25d366 0%, #4caf50 100%); }
.robot-avatar.vendas { background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%); }
.robot-avatar.clinica { background: linear-gradient(135deg, #2196f3 0%, #64b5f6 100%); }
.robot-avatar.educacao { background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%); }

.robot-info {
    flex: 1;
}

.robot-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.robot-category {
    font-size: 0.9rem;
    color: #6b7280;
    text-transform: capitalize;
}

.btn-robot {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.btn-robot.use {
    background: var(--primary-gradient);
    color: white;
}

.btn-robot.use:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-robot.view {
    background: #f3f4f6;
    color: #6b7280;
    cursor: default;
}

/* Acesso Rápido */
.quick-access-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.quick-action-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: rgba(249, 250, 251, 0.5);
    border-radius: 16px;
    border: 1px solid #f3f4f6;
    text-decoration: none;
    transition: all 0.3s ease;
    color: inherit;
}

.quick-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    background: white;
}

.quick-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-gradient);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    flex-shrink: 0;
}

.quick-content {
    flex: 1;
}

.quick-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.quick-content p {
    font-size: 0.85rem;
    color: #6b7280;
}

.quick-arrow {
    color: #6b7280;
    font-size: 14px;
    transition: transform 0.2s ease;
}

.quick-action-card:hover .quick-arrow {
    transform: translateX(4px);
}

/* Informações da Conta */
.account-info {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f3f4f6;
}

.account-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-letter {
    width: 100%;
    height: 100%;
    background: var(--primary-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.5rem;
}

.account-details h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
}

.account-role {
    font-size: 0.95rem;
    color: #6366f1;
    font-weight: 600;
    margin-bottom: 2px;
}

.account-since {
    font-size: 0.85rem;
    color: #6b7280;
}

.account-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.account-stat {
    text-align: center;
    padding: 16px 12px;
    background: rgba(249, 250, 251, 0.5);
    border-radius: 12px;
    border: 1px solid #f3f4f6;
}

.account-stat .stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 4px;
}

.account-stat .stat-label {
    font-size: 0.8rem;
    color: #6b7280;
    font-weight: 500;
}

.btn-account {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 24px;
    background: var(--primary-gradient);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-account:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Estados Vazios */
.empty-state-widget {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-state-widget i {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
    color: #9ca3af;
}

.empty-state-widget h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.empty-state-widget p {
    font-size: 0.9rem;
    margin-bottom: 20px;
    line-height: 1.5;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--primary-gradient);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Filtros de Tempo */
.time-filter {
    display: flex;
    gap: 8px;
    background: #f3f4f6;
    border-radius: 8px;
    padding: 4px;
}

.time-btn {
    padding: 6px 12px;
    border: none;
    background: transparent;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s ease;
}

.time-btn.active {
    background: white;
    color: #1f2937;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #16a34a;
}

/* Gráficos */
.activity-chart {
    height: 200px;
    margin-bottom: 20px;
}

.activity-summary {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.summary-item {
    text-align: center;
    padding: 16px;
    background: rgba(249, 250, 251, 0.5);
    border-radius: 12px;
    border: 1px solid #f3f4f6;
}

.summary-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 4px;
}

.summary-label {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
}

/* Responsividade */
@media (max-width: 1200px) {
    .dashboard-content {
        grid-template-columns: 1fr;
    }
    
    .metrics-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-access-grid {
        grid-template-columns: 1fr;
    }
    
    .account-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Dados para os gráficos
const monthlyData = <?= json_encode($stats['monthly_usage'] ?? []) ?>;

// Gráfico de linha pequeno para o card mensal
if (document.getElementById('monthlyChart')) {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.slice(0, 6).map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('pt-BR', { month: 'short' });
            }),
            datasets: [{
                data: monthlyData.slice(0, 6).map(item => item.requests),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { display: false },
                y: { display: false }
            },
            elements: {
                point: { radius: 0 }
            }
        }
    });
}

// Gráfico principal de atividade
if (document.getElementById('activityChart') && monthlyData.length > 0) {
    const ctx2 = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('pt-BR', { month: 'short', year: '2-digit' });
            }),
            datasets: [{
                label: 'Consultas IA',
                data: monthlyData.map(item => item.requests),
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: '#667eea',
                borderWidth: 1,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { color: '#6b7280' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#6b7280' }
                }
            }
        }
    });
}

// Filtros de tempo
document.querySelectorAll('.time-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        // Aqui você pode implementar a lógica para filtrar os dados
    });
});
</script>