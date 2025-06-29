<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Página de Robôs IA do Usuário -->
<div class="robots-header">
    <div class="header-content">
        <div class="header-info">
            <h1 class="page-title">
                <i class="fas fa-robot"></i>
                Seus Assistentes IA
            </h1>
            <p class="page-subtitle">
                Acesse e utilize os robôs IA liberados para sua conta
            </p>
        </div>
        <div class="header-stats">
            <div class="stat-badge">
                <span class="stat-number"><?= count($userRobots['usable']) ?></span>
                <span class="stat-text">Robôs Ativos</span>
            </div>
            <div class="stat-badge">
                <span class="stat-number"><?= count($userRobots['visible']) ?></span>
                <span class="stat-text">Total Disponível</span>
            </div>
        </div>
    </div>
</div>

<!-- Filtros de Categoria -->
<div class="category-filters">
    <button class="filter-btn active" data-category="all">
        <i class="fas fa-th"></i> Todos
    </button>
    <button class="filter-btn" data-category="marketing">
        <i class="fas fa-bullhorn"></i> Marketing
    </button>
    <button class="filter-btn" data-category="atendimento">
        <i class="fas fa-headset"></i> Atendimento
    </button>
    <button class="filter-btn" data-category="vendas">
        <i class="fas fa-hand-holding-usd"></i> Vendas
    </button>
    <button class="filter-btn" data-category="clinica">
        <i class="fas fa-stethoscope"></i> Clínica
    </button>
    <button class="filter-btn" data-category="educacao">
        <i class="fas fa-graduation-cap"></i> Educação
    </button>
</div>

<!-- Grade de Robôs -->
<?php if (!empty($userRobots['visible'])): ?>
    <div class="robots-grid">
        <?php foreach ($userRobots['visible'] as $robot): 
            $categoryColors = [
                'marketing' => 'marketing',
                'atendimento' => 'atendimento', 
                'vendas' => 'vendas',
                'clinica' => 'clinica',
                'educacao' => 'educacao'
            ];
            $colorClass = $categoryColors[$robot['robot_category']] ?? 'clinica';
        ?>
            <div class="robot-card <?= !$robot['can_use'] ? 'restricted' : '' ?>" data-category="<?= htmlspecialchars($robot['robot_category']) ?>">
                <div class="robot-header <?= $colorClass ?>">
                    <div class="robot-icon">
                        <i class="<?= htmlspecialchars($robot['robot_icon']) ?>"></i>
                    </div>
                    <?php if (!$robot['can_use']): ?>
                        <div class="restriction-badge">
                            <i class="fas fa-eye"></i> Visualização
                        </div>
                    <?php endif; ?>
                </div>
                <div class="robot-body">
                    <h3 class="robot-name"><?= htmlspecialchars($robot['robot_name']) ?></h3>
                    <p class="robot-description"><?= htmlspecialchars($robot['robot_description'] ?? 'Assistente IA especializado') ?></p>
                    <div class="robot-meta">
                        <span class="meta-item">
                            <i class="fas fa-layer-group"></i>
                            <?= ucfirst($robot['robot_category']) ?>
                        </span>
                        <?php if ($robot['can_use']): ?>
                            <span class="meta-item success">
                                <i class="fas fa-check-circle"></i>
                                Liberado
                            </span>
                        <?php else: ?>
                            <span class="meta-item warning">
                                <i class="fas fa-lock"></i>
                                Restrito
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="robot-footer">
                    <?php if ($robot['can_use']): ?>
                        <a href="<?= BASE_URL ?>/ai/<?= htmlspecialchars($robot['robot_slug']) ?>" class="btn-use">
                            <i class="fas fa-play"></i>
                            Usar Agora
                        </a>
                    <?php else: ?>
                        <button class="btn-restricted" disabled>
                            <i class="fas fa-lock"></i>
                            Solicitar Acesso
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-robot"></i>
        </div>
        <h2>Nenhum robô disponível</h2>
        <p>Você ainda não tem acesso a nenhum assistente IA.</p>
        <p>Entre em contato com o administrador para liberar o acesso aos robôs.</p>
        <a href="<?= BASE_URL ?>/dashboard" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Voltar ao Dashboard
        </a>
    </div>
<?php endif; ?>

<style>
/* Header */
.robots-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 40px;
    margin-bottom: 32px;
    color: white;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.header-stats {
    display: flex;
    gap: 24px;
}

.stat-badge {
    text-align: center;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 16px 24px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-text {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Filtros */
.category-filters {
    display: flex;
    gap: 12px;
    margin-bottom: 32px;
    flex-wrap: wrap;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
}

.filter-btn.active {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

/* Grid de Robôs */
.robots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.robot-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.robot-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.robot-card.restricted {
    opacity: 0.8;
}

.robot-header {
    padding: 32px;
    position: relative;
    color: white;
    text-align: center;
}

.robot-header.marketing { background: linear-gradient(135deg, #e91e63 0%, #f06292 100%); }
.robot-header.atendimento { background: linear-gradient(135deg, #25d366 0%, #4caf50 100%); }
.robot-header.vendas { background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%); }
.robot-header.clinica { background: linear-gradient(135deg, #2196f3 0%, #64b5f6 100%); }
.robot-header.educacao { background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%); }

.robot-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin: 0 auto;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.restriction-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
}

.robot-body {
    padding: 24px;
}

.robot-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
}

.robot-description {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 20px;
}

.robot-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}

.meta-item.success {
    color: #16a34a;
}

.meta-item.warning {
    color: #dc2626;
}

.robot-footer {
    padding: 20px 24px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.btn-use {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-use:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-restricted {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 14px 24px;
    background: #e5e7eb;
    color: #9ca3af;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: not-allowed;
}

/* Estado Vazio */
.empty-state {
    text-align: center;
    padding: 80px 40px;
    background: white;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.empty-icon {
    width: 120px;
    height: 120px;
    background: #f3f4f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    font-size: 48px;
    color: #9ca3af;
}

.empty-state h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
}

.empty-state p {
    font-size: 1rem;
    color: #6b7280;
    margin-bottom: 8px;
    line-height: 1.6;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 24px;
    padding: 12px 24px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-back:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Responsividade */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .header-stats {
        width: 100%;
        justify-content: center;
    }
    
    .robots-grid {
        grid-template-columns: 1fr;
    }
    
    .category-filters {
        justify-content: center;
    }
}
</style>

<script>
// Filtros de categoria
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Atualizar botão ativo
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filtrar robôs
        const category = this.dataset.category;
        const robots = document.querySelectorAll('.robot-card');
        
        robots.forEach(robot => {
            if (category === 'all' || robot.dataset.category === category) {
                robot.style.display = 'block';
            } else {
                robot.style.display = 'none';
            }
        });
    });
});
</script>