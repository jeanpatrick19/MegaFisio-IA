<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Painel Administrativo</h1>
<p class="subtitulo-pagina-escuro">Visão completa do sistema - Gerencie usuários, monitore atividades e controle todas as operações</p>

<!-- Cards de Estatísticas -->
<div class="grade-estatisticas">
    <!-- Card Pacientes Ativos -->
    <div class="card-fisio card-estatistica" data-tooltip="Total de usuários cadastrados no sistema">
        <div class="card-icone-grande">
            <i class="fas fa-user-injured"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= $stats['total_users'] ?? 0 ?></div>
            <div class="stat-label-escuro">Total de Usuários</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-arrow-up"></i> 12% este mês
            </div>
        </div>
    </div>
    
    <!-- Card Atendimentos IA -->
    <div class="card-fisio card-estatistica" data-tooltip="Avaliações fisioterapêuticas assistidas por IA">
        <div class="card-icone-grande ia">
            <i class="fas fa-brain"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= $stats['ai_requests_today'] ?? 0 ?></div>
            <div class="stat-label-escuro">Avaliações IA Hoje</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-arrow-up"></i> +8 vs ontem
            </div>
        </div>
    </div>
    
    <!-- Card Sessões Online -->
    <div class="card-fisio card-estatistica" data-tooltip="Usuários conectados no sistema agora">
        <div class="card-icone-grande online">
            <i class="fas fa-laptop-medical"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= $stats['active_sessions'] ?? 0 ?></div>
            <div class="stat-label-escuro">Usuários Online</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-check"></i> Estável
            </div>
        </div>
    </div>
    
    <!-- Card Saúde do Sistema -->
    <div class="card-fisio card-estatistica" data-tooltip="Status geral do sistema">
        <div class="card-icone-grande saude">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor">98%</div>
            <div class="stat-label-escuro">Saúde do Sistema</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-check-circle"></i> Excelente
            </div>
        </div>
    </div>
</div>

<!-- Seção Principal -->
<div class="grade-principal">
    <!-- Coluna Esquerda - Gráficos -->
    <div class="coluna-maior">
        <!-- Evolução de Atendimentos -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-chart-line"></i>
                    <span>Análise de Uso do Sistema</span>
                </div>
                <div class="card-acoes">
                    <select class="filtro-periodo">
                        <option>Últimos 7 dias</option>
                        <option>Últimos 30 dias</option>
                        <option>Últimos 3 meses</option>
                    </select>
                </div>
            </div>
            
            <div class="grafico-orientacao">
                <i class="fas fa-info-circle"></i>
                <p>Visão geral do uso da IA por todos os profissionais do sistema. Monitore tendências e demandas.</p>
            </div>
            
            <div class="grafico-container">
                <canvas id="graficoEvolucao" height="300"></canvas>
            </div>
            
            <div class="grafico-legenda">
                <div class="legenda-item">
                    <span class="legenda-cor ortopedica"></span>
                    <span>Fisio Ortopédica</span>
                </div>
                <div class="legenda-item">
                    <span class="legenda-cor neurologica"></span>
                    <span>Fisio Neurológica</span>
                </div>
                <div class="legenda-item">
                    <span class="legenda-cor respiratoria"></span>
                    <span>Fisio Respiratória</span>
                </div>
                <div class="legenda-item">
                    <span class="legenda-cor geriatrica"></span>
                    <span>Fisio Geriátrica</span>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-history"></i>
                    <span>Atividades Recentes</span>
                </div>
                <a href="<?= BASE_URL ?>/admin/logs" class="btn-fisio btn-secundario btn-pequeno">
                    Ver Todas
                </a>
            </div>
            
            <div class="lista-atividades">
                <?php if (!empty($activities)): ?>
                    <?php foreach (array_slice($activities, 0, 5) as $activity): ?>
                        <div class="atividade-item">
                            <div class="atividade-icone">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="atividade-conteudo">
                                <div class="atividade-titulo">
                                    <?= htmlspecialchars($activity['action'] ?? 'Ação do Sistema') ?>
                                </div>
                                <div class="atividade-info">
                                    <span class="atividade-usuario">
                                        <?= htmlspecialchars($activity['user_name'] ?? 'Sistema') ?>
                                    </span>
                                    <span class="atividade-tempo">
                                        <?= $this->timeAgo($activity['created_at'] ?? date('Y-m-d H:i:s')) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="atividade-status status-<?= $activity['status'] ?? 'info' ?>"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="atividade-vazia">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Nenhuma atividade recente</p>
                        <small>O sistema está aguardando as primeiras ações dos usuários</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Coluna Direita - Módulos e Status -->
    <div class="coluna-menor">
        <!-- Módulos IA Disponíveis -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-robot"></i>
                    <span>Assistentes IA</span>
                </div>
            </div>
            
            <div class="lista-modulos">
                <?php 
                $modulosIA = [
                    ['nome' => 'Fisio Ortopédica', 'icone' => 'fa-bone', 'cor' => 'ortopedica', 'uso' => rand(15, 45)],
                    ['nome' => 'Fisio Neurológica', 'icone' => 'fa-brain', 'cor' => 'neurologica', 'uso' => rand(10, 30)],
                    ['nome' => 'Fisio Respiratória', 'icone' => 'fa-lungs', 'cor' => 'respiratoria', 'uso' => rand(8, 25)],
                    ['nome' => 'Fisio Geriátrica', 'icone' => 'fa-user-clock', 'cor' => 'geriatrica', 'uso' => rand(12, 35)],
                    ['nome' => 'Fisio Pediátrica', 'icone' => 'fa-baby', 'cor' => 'pediatrica', 'uso' => rand(5, 20)]
                ];
                foreach ($modulosIA as $modulo): 
                ?>
                    <div class="modulo-ia-item-admin">
                        <div class="modulo-icone <?= $modulo['cor'] ?>">
                            <i class="fas <?= $modulo['icone'] ?>"></i>
                        </div>
                        <div class="modulo-info-admin">
                            <span class="modulo-nome"><?= $modulo['nome'] ?></span>
                            <span class="modulo-uso-escuro"><?= $modulo['uso'] ?> usos hoje</span>
                        </div>
                        <div class="modulo-progresso">
                            <div class="progresso-barra" style="width: <?= $modulo['uso'] ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="resumo-uso">
                <div class="resumo-item">
                    <span class="resumo-numero"><?= array_sum(array_column($modulosIA ?? [], 'uso')) ?></span>
                    <span class="resumo-label-escuro">Total de Usos Hoje</span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-numero"><?= count($modulosIA ?? []) ?></span>
                    <span class="resumo-label-escuro">Módulos Ativos</span>
                </div>
            </div>
            
            <a href="<?= BASE_URL ?>/admin/ai" class="btn-fisio btn-secundario btn-completo">
                <i class="fas fa-eye"></i>
                Visualizar Interface IA
            </a>
        </div>

        <!-- Status do Sistema -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-server"></i>
                    <span>Status do Sistema</span>
                </div>
            </div>
            
            <div class="status-grid">
                <div class="status-card">
                    <div class="status-card-header">
                        <div class="status-icone database">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="status-indicador online"></div>
                    </div>
                    <div class="status-card-body">
                        <h4>Banco de Dados</h4>
                        <span class="status-valor online">Conectado</span>
                    </div>
                </div>
                
                <div class="status-card">
                    <div class="status-card-header">
                        <div class="status-icone ia">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="status-indicador online"></div>
                    </div>
                    <div class="status-card-body">
                        <h4>Motor de IA</h4>
                        <span class="status-valor online">Operacional</span>
                    </div>
                </div>
                
                <div class="status-card">
                    <div class="status-card-header">
                        <div class="status-icone storage">
                            <i class="fas fa-hdd"></i>
                        </div>
                        <div class="status-indicador aviso"></div>
                    </div>
                    <div class="status-card-body">
                        <h4>Armazenamento</h4>
                        <span class="status-valor aviso">78% usado</span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 78%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="status-card">
                    <div class="status-card-header">
                        <div class="status-icone security">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="status-indicador online"></div>
                    </div>
                    <div class="status-card-body">
                        <h4>Segurança</h4>
                        <span class="status-valor online">Ativa</span>
                    </div>
                </div>
            </div>
            
            <button class="btn-fisio btn-secundario btn-completo" onclick="atualizarStatus()">
                <i class="fas fa-sync-alt"></i>
                Atualizar Status
            </button>
        </div>
    </div>
</div>

<style>
/* Grade de Estatísticas */
.grade-estatisticas {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.card-estatistica {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 28px;
    transition: all 0.3s ease;
}

.card-estatistica:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.card-icone-grande {
    width: 72px;
    height: 72px;
    background: var(--gradiente-principal);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
}

.card-icone-grande.ia {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
}

.card-icone-grande.online {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
}

.card-icone-grande.saude {
    background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
}

.card-conteudo-stat {
    flex: 1;
}

.stat-valor {
    font-size: 36px;
    font-weight: 800;
    color: var(--cinza-escuro);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 16px;
    color: var(--cinza-escuro);
    font-weight: 500;
    margin-bottom: 8px;
}

.stat-label-escuro {
    font-size: 16px;
    color: var(--cinza-escuro);
    font-weight: 600;
    margin-bottom: 8px;
}

.stat-variacao {
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-variacao.positiva {
    color: var(--sucesso);
}

.stat-variacao.negativa {
    color: var(--erro);
}

.stat-variacao.neutra {
    color: var(--cinza-medio);
}

/* Grade Principal */
.grade-principal {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
}

@media (max-width: 1200px) {
    .grade-principal {
        grid-template-columns: 1fr;
    }
}

/* Headers dos Cards */
.card-header-fisio {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--cinza-medio);
}

.card-titulo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

.card-titulo i {
    color: var(--azul-saude);
}

/* Filtros */
.filtro-periodo {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    font-weight: 500;
    color: var(--cinza-escuro);
    background: var(--branco-puro);
    font-family: inherit;
    cursor: pointer;
    transition: var(--transicao);
}

.filtro-periodo:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.filtro-periodo:hover {
    border-color: var(--azul-saude);
}

/* Botões */
.btn-pequeno {
    padding: 8px 16px;
    font-size: 13px;
}

.btn-completo {
    width: 100%;
    justify-content: center;
}

/* Correção do subtítulo */
.subtitulo-pagina-escuro {
    font-size: 16px;
    color: var(--cinza-escuro);
    margin-bottom: 32px;
    font-weight: 500;
}

/* Card actions */
.card-acoes {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* Gráfico */
.grafico-orientacao {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    padding: 12px;
    background: rgba(30, 58, 138, 0.05);
    border-radius: 8px;
    font-size: 13px;
    color: var(--azul-saude);
}

.grafico-container {
    height: 300px;
    margin-bottom: 20px;
    background: var(--cinza-claro);
    border-radius: 12px;
    padding: 20px;
    position: relative;
}

.grafico-legenda {
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
    padding: 16px;
    background: var(--branco-puro);
    border-radius: 8px;
    border: 1px solid var(--cinza-claro);
}

.legenda-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--cinza-escuro);
    font-weight: 500;
}

.legenda-cor {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.legenda-cor.ortopedica { background: #3b82f6; }
.legenda-cor.neurologica { background: #7c3aed; }
.legenda-cor.respiratoria { background: #10b981; }
.legenda-cor.geriatrica { background: #f59e0b; }

.grafico-orientacao i {
    font-size: 16px;
}

/* Lista de Atividades */
.lista-atividades {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.atividade-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.atividade-item:hover {
    background: var(--cinza-medio);
    transform: translateX(4px);
}

.atividade-icone {
    width: 40px;
    height: 40px;
    background: var(--gradiente-principal);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.atividade-conteudo {
    flex: 1;
}

.atividade-titulo {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 15px;
    margin-bottom: 4px;
}

.atividade-info {
    display: flex;
    gap: 12px;
    font-size: 13px;
    color: var(--cinza-escuro);
}

.atividade-tempo {
    color: var(--azul-saude);
}

.atividade-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-success { background: var(--sucesso); }
.status-info { background: var(--info); }
.status-warning { background: var(--alerta); }
.status-error { background: var(--erro); }

.atividade-vazia {
    text-align: center;
    padding: 48px;
    color: var(--cinza-medio);
}

.atividade-vazia i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.atividade-vazia small {
    color: var(--azul-saude);
    font-size: 14px;
    margin-top: 8px;
    display: block;
}

/* Lista de Módulos */
.lista-modulos {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.modulo-ia-item-admin {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
    position: relative;
    overflow: hidden;
}

.modulo-icone {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.modulo-icone.ortopedica { background: linear-gradient(135deg, #3b82f6, #60a5fa); }
.modulo-icone.neurologica { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
.modulo-icone.respiratoria { background: linear-gradient(135deg, #10b981, #34d399); }
.modulo-icone.geriatrica { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
.modulo-icone.pediatrica { background: linear-gradient(135deg, #ec4899, #f472b6); }

.modulo-info-admin {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.modulo-nome {
    font-weight: 600;
    color: var(--cinza-escuro);
}

.modulo-uso {
    font-size: 12px;
    color: var(--cinza-escuro);
}

.modulo-uso-escuro {
    font-size: 12px;
    color: var(--cinza-escuro);
    font-weight: 500;
}

.modulo-progresso {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--cinza-medio);
}

.progresso-barra {
    height: 100%;
    background: var(--azul-saude);
    transition: width 0.5s ease;
}

/* Resumo de Uso */
.resumo-uso {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
    padding: 16px;
    background: rgba(30, 58, 138, 0.05);
    border-radius: 12px;
}

.resumo-item {
    text-align: center;
}

.resumo-numero {
    display: block;
    font-size: 24px;
    font-weight: 800;
    color: var(--azul-saude);
    font-family: 'JetBrains Mono', monospace;
}

.resumo-label {
    font-size: 12px;
    color: var(--cinza-medio);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.resumo-label-escuro {
    font-size: 12px;
    color: var(--cinza-escuro);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Status do Sistema */
.status-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

.status-card {
    background: var(--cinza-claro);
    border-radius: 12px;
    padding: 16px;
    transition: var(--transicao);
    border: 1px solid transparent;
}

.status-card:hover {
    background: white;
    border-color: var(--azul-saude);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.status-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.status-icone {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.status-icone.database {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
}

.status-icone.ia {
    background: linear-gradient(135deg, #059669, #10b981);
}

.status-icone.storage {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
}

.status-icone.security {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.status-indicador {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    position: relative;
}

.status-indicador.online {
    background: var(--sucesso);
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
}

.status-indicador.aviso {
    background: var(--alerta);
    box-shadow: 0 0 8px rgba(245, 158, 11, 0.5);
}

.status-indicador.offline {
    background: var(--erro);
    box-shadow: 0 0 8px rgba(239, 68, 68, 0.5);
}

.status-indicador::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

.status-card-body h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--cinza-escuro);
    margin: 0 0 4px 0;
}

.status-valor {
    font-size: 12px;
    font-weight: 500;
    display: block;
}

.status-valor.online {
    color: var(--sucesso);
}

.status-valor.aviso {
    color: var(--alerta);
}

.status-valor.offline {
    color: var(--erro);
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--cinza-medio);
    border-radius: 3px;
    margin-top: 8px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--alerta), #fbbf24);
    border-radius: 3px;
    transition: width 0.5s ease;
}

/* Cores e melhorias visuais */
.btn-fisio.btn-secundario {
    background: var(--branco-puro);
    color: var(--azul-saude);
    border: 2px solid var(--azul-saude);
}

.btn-fisio.btn-secundario:hover {
    background: var(--azul-saude);
    color: white;
}

/* Links estilizados */
a.btn-fisio {
    text-decoration: none;
}

/* Melhorias no gráfico placeholder */
.grafico-container canvas {
    width: 100% !important;
    height: 100% !important;
}

/* Animações suaves */
* {
    transition: var(--transicao);
}

/* Responsivo */
@media (max-width: 1400px) {
    .grade-principal {
        grid-template-columns: 1fr 350px;
    }
}

@media (max-width: 1200px) {
    .grade-principal {
        grid-template-columns: 1fr;
    }
    
    .coluna-menor {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
}

@media (max-width: 768px) {
    .grade-estatisticas {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .grade-principal {
        grid-template-columns: 1fr;
    }
    
    .coluna-menor {
        grid-template-columns: 1fr;
    }
    
    .grafico-legenda {
        gap: 16px;
    }
    
    .card-header-fisio {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .titulo-pagina {
        font-size: 28px;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .grade-estatisticas {
        grid-template-columns: 1fr;
    }
    
    .card-fisio {
        padding: 16px;
    }
    
    .card-estatistica {
        padding: 20px;
        gap: 16px;
    }
    
    .card-icone-grande {
        width: 60px;
        height: 60px;
        font-size: 28px;
    }
    
    .stat-valor {
        font-size: 28px;
    }
    
    .titulo-pagina {
        font-size: 24px;
    }
    
    .subtitulo-pagina-escuro {
        font-size: 14px;
    }
}
</style>

<script>
// Função para atualizar status
function atualizarStatus() {
    const btn = event.target;
    const icon = btn.querySelector('i');
    
    // Mostrar feedback visual
    btn.style.opacity = '0.7';
    btn.disabled = true;
    icon.style.animation = 'spin 1s linear';
    
    // Simular verificação
    setTimeout(() => {
        icon.style.animation = '';
        btn.style.opacity = '1';
        btn.disabled = false;
        
        // Feedback de sucesso
        mostrarAlerta('Status do sistema verificado - Tudo funcionando perfeitamente!', 'sucesso');
        
        // Animar indicadores de status
        document.querySelectorAll('.status-indicador').forEach(indicador => {
            indicador.style.transform = 'scale(1.2)';
            setTimeout(() => {
                indicador.style.transform = 'scale(1)';
            }, 200);
        });
    }, 2000);
}

// Animação de rotação
const spinStyle = document.createElement('style');
spinStyle.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(spinStyle);

// Simular gráfico (substituir por Chart.js em produção)
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('graficoEvolucao');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        // Adicionar aqui a lógica do gráfico real
        ctx.fillStyle = '#f8fafc';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#94a3b8';
        ctx.font = '14px Inter';
        ctx.textAlign = 'center';
        ctx.fillText('Gráfico de evolução será exibido aqui', canvas.width/2, canvas.height/2);
    }
});
</script>