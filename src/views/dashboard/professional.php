<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Painel Profissional</h1>
<p class="subtitulo-pagina-escuro">Bem-vindo, <?= htmlspecialchars($user['name']) ?>! Gerencie seus pacientes e utilize ferramentas de IA</p>

<!-- Cards de Estatísticas -->
<div class="grade-estatisticas">
    <!-- Card Requisições IA -->
    <div class="card-fisio card-estatistica" data-tooltip="Total de consultas à IA realizadas">
        <div class="card-icone-grande ia">
            <i class="fas fa-brain"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= number_format($stats['ai_requests']) ?></div>
            <div class="stat-label-escuro">Consultas IA Realizadas</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-chart-line"></i> Total
            </div>
        </div>
    </div>
    
    <!-- Card Documentos -->
    <div class="card-fisio card-estatistica" data-tooltip="Documentos criados com assistência da IA">
        <div class="card-icone-grande">
            <i class="fas fa-file-medical"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor"><?= number_format($stats['documents_created']) ?></div>
            <div class="stat-label-escuro">Documentos Criados</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-plus"></i> Profissionais
            </div>
        </div>
    </div>
    
    <!-- Card Uso Este Mês -->
    <div class="card-fisio card-estatistica" data-tooltip="Uso da IA no mês atual">
        <div class="card-icone-grande online">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor">
                <?php 
                $thisMonth = 0;
                if (!empty($stats['monthly_usage'])) {
                    $thisMonth = $stats['monthly_usage'][0]['requests'] ?? 0;
                }
                echo number_format($thisMonth);
                ?>
            </div>
            <div class="stat-label-escuro">Uso Este Mês</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-arrow-up"></i> Ativo
            </div>
        </div>
    </div>
    
    <!-- Card Próximas Features -->
    <div class="card-fisio card-estatistica" data-tooltip="Novas ferramentas em desenvolvimento">
        <div class="card-icone-grande saude">
            <i class="fas fa-rocket"></i>
        </div>
        <div class="card-conteudo-stat">
            <div class="stat-valor">4</div>
            <div class="stat-label-escuro">Ferramentas em Breve</div>
            <div class="stat-variacao positiva">
                <i class="fas fa-star"></i> Novidades
            </div>
        </div>
    </div>
</div>

<!-- Seção Principal -->
<div class="grade-principal">
    <!-- Coluna Esquerda - Ferramentas -->
    <div class="coluna-maior">
        <!-- Ferramentas IA Disponíveis -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-tools"></i>
                    <span>Ferramentas IA Disponíveis</span>
                </div>
                <a href="<?= BASE_URL ?>/ai" class="btn-fisio btn-primario btn-pequeno">
                    <i class="fas fa-brain"></i>
                    Acessar IA
                </a>
            </div>
            
            <div class="lista-modulos">
                <div class="modulo-ia-item-admin">
                    <div class="modulo-icone ortopedica">
                        <i class="fas fa-bone"></i>
                    </div>
                    <div class="modulo-info-admin">
                        <span class="modulo-nome">Fisioterapia Ortopédica</span>
                        <span class="modulo-uso-escuro">Análise de lesões e reabilitação</span>
                    </div>
                </div>
                
                <div class="modulo-ia-item-admin">
                    <div class="modulo-icone neurologica">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="modulo-info-admin">
                        <span class="modulo-nome">Fisioterapia Neurológica</span>
                        <span class="modulo-uso-escuro">Reabilitação neuromotora</span>
                    </div>
                </div>
                
                <div class="modulo-ia-item-admin">
                    <div class="modulo-icone respiratoria">
                        <i class="fas fa-lungs"></i>
                    </div>
                    <div class="modulo-info-admin">
                        <span class="modulo-nome">Fisioterapia Respiratória</span>
                        <span class="modulo-uso-escuro">Função pulmonar e respiração</span>
                    </div>
                </div>
                
                <div class="modulo-ia-item-admin">
                    <div class="modulo-icone geriatrica">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="modulo-info-admin">
                        <span class="modulo-nome">Fisioterapia Geriátrica</span>
                        <span class="modulo-uso-escuro">Cuidados com idosos</span>
                    </div>
                </div>
                
                <div class="modulo-ia-item-admin">
                    <div class="modulo-icone pediatrica">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="modulo-info-admin">
                        <span class="modulo-nome">Fisioterapia Pediátrica</span>
                        <span class="modulo-uso-escuro">Desenvolvimento infantil</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uso Mensal -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-chart-bar"></i>
                    <span>Histórico de Uso</span>
                </div>
            </div>
            
            <?php if (empty($stats['monthly_usage'])): ?>
                <div class="atividade-vazia">
                    <i class="fas fa-chart-line"></i>
                    <p>Nenhum uso registrado ainda</p>
                    <small>Comece a usar a IA para ver suas estatísticas aqui</small>
                </div>
            <?php else: ?>
                <div class="lista-atividades">
                    <?php foreach ($stats['monthly_usage'] as $usage): ?>
                        <div class="atividade-item">
                            <div class="atividade-icone">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="atividade-conteudo">
                                <div class="atividade-titulo">
                                    <?= date('F Y', strtotime($usage['month'] . '-01')) ?>
                                </div>
                                <div class="atividade-info">
                                    <span class="atividade-tempo">
                                        <?= number_format($usage['requests']) ?> consultas
                                    </span>
                                    <span>
                                        <?= number_format($usage['tokens'] ?? 0) ?> tokens
                                    </span>
                                </div>
                            </div>
                            <div class="atividade-status status-success"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Coluna Direita - Configurações e Links -->
    <div class="coluna-menor">
        <!-- Acesso Rápido -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-shortcuts"></i>
                    <span>Acesso Rápido</span>
                </div>
            </div>
            
            <div class="lista-status">
                <div class="status-item">
                    <div class="status-indicador online"></div>
                    <div class="status-info">
                        <div class="status-nome">
                            <a href="<?= BASE_URL ?>/ai" style="text-decoration: none; color: inherit;">
                                Assistente IA
                            </a>
                        </div>
                        <div class="status-valor">Disponível</div>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicador online"></div>
                    <div class="status-info">
                        <div class="status-nome">
                            <a href="<?= BASE_URL ?>/profile" style="text-decoration: none; color: inherit;">
                                Meu Perfil
                            </a>
                        </div>
                        <div class="status-valor">Configurar</div>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicador aviso"></div>
                    <div class="status-info">
                        <div class="status-nome">
                            <a href="<?= BASE_URL ?>/change-password" style="text-decoration: none; color: inherit;">
                                Alterar Senha
                            </a>
                        </div>
                        <div class="status-valor">Segurança</div>
                    </div>
                </div>
            </div>
            
            <a href="<?= BASE_URL ?>/ai" class="btn-fisio btn-primario btn-completo">
                <i class="fas fa-brain"></i>
                Começar a Usar IA
            </a>
        </div>

        <!-- Ferramentas Futuras -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-rocket"></i>
                    <span>Em Desenvolvimento</span>
                </div>
            </div>
            
            <div class="lista-status">
                <div class="status-item">
                    <div class="status-indicador aviso"></div>
                    <div class="status-info">
                        <div class="status-nome">Análise de Paciente</div>
                        <div class="status-valor">Em breve</div>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicador aviso"></div>
                    <div class="status-info">
                        <div class="status-nome">Gerador de Relatórios</div>
                        <div class="status-valor">Em breve</div>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicador aviso"></div>
                    <div class="status-info">
                        <div class="status-nome">Sugestões de Exercícios</div>
                        <div class="status-valor">Em breve</div>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicador aviso"></div>
                    <div class="status-info">
                        <div class="status-nome">Análise de Evolução</div>
                        <div class="status-valor">Em breve</div>
                    </div>
                </div>
            </div>
            
            <div class="resumo-uso">
                <div class="resumo-item">
                    <span class="resumo-numero">4</span>
                    <span class="resumo-label-escuro">Ferramentas</span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-numero">2024</span>
                    <span class="resumo-label-escuro">Lançamento</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Animação suave para os cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card-estatistica');
    
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>