<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 28px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover {
        border-color: #1e3a8a;
        transform: translateY(-3px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .stat-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.5rem;
    }

    .stat-icon.users { background: rgba(30, 58, 138, 0.1); color: #1e3a8a; }
    .stat-icon.sessions { background: rgba(5, 150, 105, 0.1); color: #059669; }
    .stat-icon.requests { background: rgba(202, 138, 4, 0.1); color: #ca8a04; }
    .stat-icon.health { background: rgba(124, 58, 237, 0.1); color: #7c3aed; }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1e3a8a;
        line-height: 1;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 8px;
        font-weight: 500;
    }

    .activities-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.3rem;
        color: #1e3a8a;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        font-weight: 700;
    }

    .section-title::before {
        content: "📊";
        margin-right: 10px;
        font-size: 1.5rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(5, 150, 105, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        color: #059669;
    }

    .activity-content {
        flex: 1;
    }

    .activity-description {
        color: #1f2937;
        font-size: 0.95rem;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .activity-meta {
        color: #6b7280;
        font-size: 0.85rem;
    }

    .health-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
    }

    .health-healthy {
        background: rgba(5, 150, 105, 0.1);
        color: #059669;
    }

    .health-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .stat-card {
            padding: 20px;
        }
        
        .stat-value {
            font-size: 2rem;
        }
    }
</style>

<!-- Título da Página -->
<h1 class="titulo-pagina">🏥 Dashboard Clínico Administrativo</h1>
<p class="subtitulo-pagina-escuro">Visão geral do sistema MegaFisio IA para sua clínica de fisioterapia</p>

    <!-- Cards de Estatísticas -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon users">👨‍⚕️</div>
                <div>
                    <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
                    <div class="stat-label">Fisioterapeutas Cadastrados</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon sessions">🟢</div>
                <div>
                    <div class="stat-value"><?= number_format($stats['active_sessions']) ?></div>
                    <div class="stat-label">Profissionais Online</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon requests">🧠</div>
                <div>
                    <div class="stat-value"><?= number_format($stats['ai_requests_today']) ?></div>
                    <div class="stat-label">Análises IA Realizadas Hoje</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon health">💚</div>
                <div>
                    <div class="stat-value">
                        <span class="health-status health-<?= $stats['system_health'] ?>">
                            <?= $stats['system_health'] === 'healthy' ? 'Operacional' : 'Verificar' ?>
                        </span>
                    </div>
                    <div class="stat-label">Status da Plataforma</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="activities-section">
        <h2 class="section-title">Atividades Clínicas Recentes</h2>
        
        <?php if (!empty($activities)): ?>
            <?php foreach ($activities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">📝</div>
                    <div class="activity-content">
                        <div class="activity-description">
                            <?= htmlspecialchars($activity['description'] ?? 'Atividade do sistema') ?>
                        </div>
                        <div class="activity-meta">
                            <?php if (!empty($activity['user_name'])): ?>
                                por <?= htmlspecialchars($activity['user_name']) ?> • 
                            <?php endif; ?>
                            <?php 
                            $date = new DateTime($activity['created_at']);
                            $date->setTimezone(new DateTimeZone('America/Sao_Paulo'));
                            echo $date->format('d/m/Y H:i');
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; color: #6b7280; padding: 48px;">
                <p>📋 Nenhuma atividade clínica registrada hoje</p>
                <small style="color: #9ca3af; margin-top: 8px; display: block;">As atividades dos fisioterapeutas aparecerão aqui</small>
            </div>
        <?php endif; ?>
    </div>