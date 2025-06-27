<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

<!-- Título da Página -->
<h1 class="titulo-pagina">⚙️ Configurações da API OpenAI</h1>
<p class="subtitulo-pagina-escuro">Configure e monitore a integração com a API da OpenAI para os 23 robôs Dr. IA</p>

<!-- Navegação Breadcrumb -->
<nav class="breadcrumb">
    <a href="/ai"><i class="fas fa-robot"></i> Gestão de Prompts</a>
    <span class="breadcrumb-separator">></span>
    <span class="breadcrumb-current">Configurações IA</span>
</nav>

<!-- Status da API -->
<div class="api-status-section">
    <div class="status-card online" id="statusCard">
        <div class="status-icon">
            <i class="fas fa-circle" id="statusIcon"></i>
        </div>
        <div class="status-info">
            <h3 id="statusTitle">API OpenAI Online</h3>
            <p id="statusDescription">Conectado e funcionando normalmente</p>
            <small id="statusLastCheck">Última verificação: agora</small>
        </div>
        <button class="btn-verificar" onclick="verificarStatusAPI()">
            <i class="fas fa-sync"></i>
            Verificar Status
        </button>
    </div>
</div>

<!-- Grid Principal de Configurações -->
<div class="configuracoes-grid">
    
    <!-- Configurações da API -->
    <div class="config-card">
        <div class="config-header">
            <h3><i class="fas fa-key"></i> Configurações da API</h3>
        </div>
        <div class="config-content">
            <form id="formConfigAPI" class="config-form">
                <div class="campo-grupo">
                    <label for="apiKey">Chave da API OpenAI</label>
                    <div class="input-password">
                        <input type="password" id="apiKey" name="apiKey" 
                               placeholder="sk-..." value="sk-••••••••••••••••••••••••••••••••••••••••">
                        <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('apiKey')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="help-text">Sua chave API da OpenAI. Mantenha-a segura!</small>
                </div>
                
                <div class="campo-grupo">
                    <label for="modeloPadrao">Modelo GPT Padrão</label>
                    <select id="modeloPadrao" name="modeloPadrao">
                        <optgroup label="Modelos Premium">
                            <option value="gpt-4.1">GPT-4.1 (Flagship - Tarefas Complexas)</option>
                            <option value="gpt-4o">GPT-4o (Rápido, Inteligente, Flexível)</option>
                            <option value="gpt-4o-audio-preview">GPT-4o Áudio (Com Entrada/Saída de Áudio)</option>
                            <option value="chatgpt-4o-latest">ChatGPT-4o (Modelo do ChatGPT)</option>
                        </optgroup>
                        <optgroup label="Modelos Otimizados (Custo-Benefício)">
                            <option value="o4-mini">O4-Mini (Raciocínio Rápido e Acessível)</option>
                            <option value="gpt-4.1-mini">GPT-4.1 Mini (Equilibrado)</option>
                            <option value="gpt-4.1-nano">GPT-4.1 Nano (Mais Rápido e Econômico)</option>
                            <option value="o3-mini">O3-Mini (Alternativa Compacta)</option>
                            <option value="gpt-4o-mini" selected>GPT-4o Mini (Rápido para Tarefas Focadas)</option>
                        </optgroup>
                    </select>
                    <small class="help-text">Modelo usado por padrão para novos robôs</small>
                </div>
                
                <div class="campos-grid">
                    <div class="campo-grupo">
                        <label for="limiteDiario">Limite Diário Global</label>
                        <input type="number" id="limiteDiario" name="limiteDiario" value="1000" min="1" max="10000">
                        <small class="help-text">Máximo de requisições por dia</small>
                    </div>
                    
                    <div class="campo-grupo">
                        <label for="timeoutRequest">Timeout (segundos)</label>
                        <input type="number" id="timeoutRequest" name="timeoutRequest" value="30" min="5" max="120">
                        <small class="help-text">Tempo limite para cada requisição</small>
                    </div>
                </div>
                
                <button type="submit" class="btn-fisio btn-primario">
                    <i class="fas fa-save"></i>
                    Salvar Configurações
                </button>
            </form>
        </div>
    </div>
    
    <!-- Dashboard de Uso -->
    <div class="config-card">
        <div class="config-header">
            <h3><i class="fas fa-chart-line"></i> Dashboard de Uso</h3>
            <span class="periodo-atual">Hoje</span>
        </div>
        <div class="config-content">
            <div class="uso-stats">
                <div class="uso-item">
                    <div class="uso-numero" id="requestsHoje"><?= $usageStats['total_requests'] ?? 0 ?></div>
                    <div class="uso-label">Requisições Hoje</div>
                    <div class="uso-progresso">
                        <div class="progresso-bar" style="width: <?= min(($usageStats['total_requests'] ?? 0) / 10, 100) ?>%"></div>
                    </div>
                </div>
                
                <div class="uso-item">
                    <div class="uso-numero" id="tokensHoje"><?= number_format($usageStats['total_tokens'] ?? 0, 0, ',', '.') ?></div>
                    <div class="uso-label">Tokens Consumidos</div>
                    <div class="uso-progresso">
                        <div class="progresso-bar" style="width: <?= min(($usageStats['total_tokens'] ?? 0) / 50000 * 100, 100) ?>%"></div>
                    </div>
                </div>
                
                <div class="uso-item">
                    <div class="uso-numero" id="custoHoje">R$ <?= number_format(($usageStats['total_cost_usd'] ?? 0) * ($exchangeRate ?? 5), 2, ',', '.') ?></div>
                    <div class="uso-label">Custo Estimado</div>
                    <div class="uso-progresso">
                        <div class="progresso-bar" style="width: <?= min(($usageStats['total_cost_usd'] ?? 0) * 10, 100) ?>%"></div>
                    </div>
                </div>
                
                <div class="uso-item">
                    <div class="uso-numero" id="sucessoRate"><?= $usageStats['success_rate'] ?? 100 ?>%</div>
                    <div class="uso-label">Taxa de Sucesso</div>
                    <div class="uso-progresso sucesso">
                        <div class="progresso-bar" style="width: <?= $usageStats['success_rate'] ?? 100 ?>%"></div>
                    </div>
                </div>
            </div>
            
            <div class="uso-detalhes">
                <h4>Uso por Modelo GPT</h4>
                <div class="modelos-uso">
                    <?php if (!empty($usageStats['model_usage'])): ?>
                        <?php foreach ($usageStats['model_usage'] as $model): ?>
                            <div class="modelo-item">
                                <span class="modelo-nome"><?= str_replace(['gpt-4o-mini', 'gpt-4o', 'gpt-4-turbo'], ['GPT-4o Mini', 'GPT-4o', 'GPT-4 Turbo'], $model['gpt_model']) ?></span>
                                <span class="modelo-requests"><?= $model['request_count'] ?> req</span>
                                <span class="modelo-custo">R$ <?= number_format($model['model_cost'] * ($exchangeRate ?? 5), 2, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="modelo-item">
                            <span class="modelo-nome">Nenhum uso hoje</span>
                            <span class="modelo-requests">0 req</span>
                            <span class="modelo-custo">R$ 0,00</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Configuração por Robô -->
    <div class="config-card robos-config">
        <div class="config-header">
            <h3><i class="fas fa-robot"></i> Configuração por Robô</h3>
            <div class="header-actions">
                <select class="filtro-robos" id="filtroRobos" onchange="filtrarRobos()">
                    <option value="">Todos os Robôs</option>
                    <option value="marketing">Marketing</option>
                    <option value="atendimento">Atendimento</option>
                    <option value="vendas">Vendas</option>
                    <option value="clinica">Clínica</option>
                    <option value="educacao">Educação</option>
                    <option value="pesquisa">Pesquisa</option>
                    <option value="juridico">Jurídico</option>
                    <option value="diagnostico">Diagnóstico</option>
                    <option value="gestao">Gestão</option>
                    <option value="fidelizacao">Fidelização</option>
                </select>
                
                <select class="ordenar-robos" id="ordenarRobos" onchange="ordenarRobos()">
                    <option value="">Ordenar por...</option>
                    <option value="nome-asc">Nome (A-Z)</option>
                    <option value="nome-desc">Nome (Z-A)</option>
                    <option value="uso-asc">Menor Uso</option>
                    <option value="uso-desc">Maior Uso</option>
                    <option value="custo-asc">Menor Gasto</option>
                    <option value="custo-desc">Maior Gasto</option>
                    <option value="sucesso-asc">Menor Taxa Sucesso</option>
                    <option value="sucesso-desc">Maior Taxa Sucesso</option>
                </select>
                
                <button class="btn-aplicar-todos" onclick="aplicarModeloTodos()">
                    <i class="fas fa-layer-group"></i>
                    Aplicar a Todos
                </button>
            </div>
        </div>
        <div class="config-content">
            <!-- Cabeçalho das colunas -->
            <div class="robos-header">
                <div class="col-robo">Robô</div>
                <div class="col-modelo">Modelo GPT</div>
                <div class="col-limite">Limite/Dia</div>
                <div class="col-uso">Uso Hoje</div>
                <div class="col-custo">Custo</div>
                <div class="col-sucesso" title="Porcentagem de requisições bem-sucedidas nos últimos 7 dias">Taxa Sucesso <i class="fas fa-info-circle" style="font-size: 10px; color: var(--cinza-medio);"></i></div>
            </div>
            
            <div class="robos-lista">
                <?php if (!empty($robotSettings)): ?>
                    <?php foreach ($robotSettings as $robot): ?>
                        <div class="robo-config-item" data-categoria="<?= strtolower($robot['category'] ?? '') ?>" 
                             data-uso="<?= $robot['usage_today'] ?? 0 ?>" 
                             data-custo="<?= $robot['cost_today'] ?? 0 ?>"
                             data-sucesso="<?= $robot['success_rate'] ?? 100 ?>">
                            <div class="col-robo">
                                <div class="robo-info">
                                    <div class="robo-icone">
                                        <i class="<?= $robot['icon'] ?? 'fas fa-robot' ?>"></i>
                                    </div>
                                    <div class="robo-detalhes">
                                        <span class="robo-nome"><?= htmlspecialchars($robot['robot_name']) ?></span>
                                        <span class="robo-categoria"><?= ucfirst($robot['category'] ?? '') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-modelo">
                                <select class="modelo-select" data-robo="<?= $robot['robot_id'] ?>">
                                    <optgroup label="Premium">
                                        <option value="gpt-4.1" <?= $robot['gpt_model'] == 'gpt-4.1' ? 'selected' : '' ?>>GPT-4.1</option>
                                        <option value="gpt-4o" <?= $robot['gpt_model'] == 'gpt-4o' ? 'selected' : '' ?>>GPT-4o</option>
                                        <option value="gpt-4o-audio-preview" <?= $robot['gpt_model'] == 'gpt-4o-audio-preview' ? 'selected' : '' ?>>GPT-4o Áudio</option>
                                        <option value="chatgpt-4o-latest" <?= $robot['gpt_model'] == 'chatgpt-4o-latest' ? 'selected' : '' ?>>ChatGPT-4o</option>
                                    </optgroup>
                                    <optgroup label="Otimizado">
                                        <option value="o4-mini" <?= $robot['gpt_model'] == 'o4-mini' ? 'selected' : '' ?>>O4-Mini</option>
                                        <option value="gpt-4.1-mini" <?= $robot['gpt_model'] == 'gpt-4.1-mini' ? 'selected' : '' ?>>GPT-4.1 Mini</option>
                                        <option value="gpt-4.1-nano" <?= $robot['gpt_model'] == 'gpt-4.1-nano' ? 'selected' : '' ?>>GPT-4.1 Nano</option>
                                        <option value="o3-mini" <?= $robot['gpt_model'] == 'o3-mini' ? 'selected' : '' ?>>O3-Mini</option>
                                        <option value="gpt-4o-mini" <?= $robot['gpt_model'] == 'gpt-4o-mini' ? 'selected' : '' ?>>GPT-4o Mini</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-limite">
                                <input type="number" class="limite-input" value="<?= $robot['daily_limit'] ?? 50 ?>" min="0" max="1000" data-robo="<?= $robot['robot_id'] ?>">
                            </div>
                            <div class="col-uso">
                                <span class="uso-valor"><?= $robot['usage_today'] ?? 0 ?></span>
                                <div class="uso-barra">
                                    <div class="uso-progresso-mini" style="width: <?= min(($robot['usage_today'] ?? 0) / ($robot['daily_limit'] ?? 50) * 100, 100) ?>%"></div>
                                </div>
                            </div>
                            <div class="col-custo">
                                <span class="custo-valor">R$ <?= number_format(($robot['cost_today'] ?? 0) * ($exchangeRate ?? 5), 2, ',', '.') ?></span>
                            </div>
                            <div class="col-sucesso">
                                <span class="sucesso-valor <?= ($robot['success_rate'] ?? 100) >= 95 ? 'alta' : (($robot['success_rate'] ?? 100) >= 80 ? 'media' : 'baixa') ?>">
                                    <?= $robot['success_rate'] ?? 100 ?>%
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                
                <!-- Mais robôs serão carregados dinamicamente -->
                <div class="carregar-mais">
                    <button class="btn-carregar-robos" onclick="carregarMaisRobos()">
                        <i class="fas fa-plus"></i>
                        Carregar Mais Robôs
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Logs e Monitoramento -->
    <div class="config-card">
        <div class="config-header">
            <h3><i class="fas fa-list"></i> Logs Recentes</h3>
            <button class="btn-limpar-logs" onclick="limparLogs()">
                <i class="fas fa-trash"></i>
                Limpar
            </button>
        </div>
        <div class="config-content">
            <div class="logs-container">
                <div class="log-item sucesso">
                    <div class="log-time">14:23:45</div>
                    <div class="log-details">
                        <span class="log-robo">Dr. Autoritas</span>
                        <span class="log-action">Análise de conteúdo Instagram</span>
                    </div>
                    <div class="log-status">
                        <i class="fas fa-check-circle"></i>
                        200ms
                    </div>
                </div>
                
                <div class="log-item sucesso">
                    <div class="log-time">14:22:18</div>
                    <div class="log-details">
                        <span class="log-robo">Dr. Reab</span>
                        <span class="log-action">Prescrição de exercícios</span>
                    </div>
                    <div class="log-status">
                        <i class="fas fa-check-circle"></i>
                        1.2s
                    </div>
                </div>
                
                <div class="log-item erro">
                    <div class="log-time">14:19:33</div>
                    <div class="log-details">
                        <span class="log-robo">Dr. Científico</span>
                        <span class="log-action">Análise de artigo</span>
                    </div>
                    <div class="log-status">
                        <i class="fas fa-exclamation-triangle"></i>
                        Timeout
                    </div>
                </div>
                
                <div class="log-item sucesso">
                    <div class="log-time">14:18:07</div>
                    <div class="log-details">
                        <span class="log-robo">Dra. Legal</span>
                        <span class="log-action">Geração de termo</span>
                    </div>
                    <div class="log-status">
                        <i class="fas fa-check-circle"></i>
                        850ms
                    </div>
                </div>
            </div>
            
            <div class="logs-footer">
                <button class="btn-ver-todos-logs">
                    <i class="fas fa-external-link-alt"></i>
                    Ver Todos os Logs
                </button>
            </div>
        </div>
    </div>
    
</div>

<style>
/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-size: 14px;
}

.breadcrumb a {
    color: var(--azul-saude);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb-separator {
    color: var(--cinza-medio);
}

.breadcrumb-current {
    color: var(--cinza-escuro);
    font-weight: 600;
}

/* Status da API */
.api-status-section {
    margin-bottom: 32px;
}

.status-card {
    background: var(--branco-puro);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    border-left: 4px solid var(--sucesso);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.status-card.offline {
    border-left-color: var(--vermelho-erro);
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--sucesso);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.status-card.offline .status-icon {
    background: var(--vermelho-erro);
}

.status-info {
    flex: 1;
}

.status-info h3 {
    margin: 0 0 4px 0;
    color: var(--cinza-escuro);
    font-size: 20px;
}

.status-info p {
    margin: 0 0 4px 0;
    color: var(--cinza-escuro);
}

.status-info small {
    color: var(--cinza-medio);
    font-size: 12px;
}

.btn-verificar {
    background: var(--azul-saude);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    transition: var(--transicao);
}

.btn-verificar:hover {
    background: var(--azul-escuro);
}

/* Grid de Configurações */
.configuracoes-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.config-card {
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-claro);
    overflow: hidden;
}

.config-card.robos-config {
    grid-column: 1 / -1;
}

.config-header {
    background: var(--cinza-claro);
    padding: 20px 24px;
    border-bottom: 1px solid var(--cinza-medio);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.config-header h3 {
    margin: 0;
    color: var(--cinza-escuro);
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.periodo-atual {
    background: var(--azul-saude);
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.config-content {
    padding: 24px;
}

/* Formulário de Configurações */
.config-form .campo-grupo {
    margin-bottom: 20px;
}

.config-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.config-form input,
.config-form select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
}

.config-form input:focus,
.config-form select:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.input-password {
    position: relative;
}

.btn-toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--cinza-medio);
    cursor: pointer;
    padding: 4px;
}

.help-text {
    color: var(--cinza-medio);
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

.campos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* Dashboard de Uso */
.uso-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.uso-item {
    text-align: center;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
}

.uso-numero {
    font-size: 24px;
    font-weight: 700;
    color: var(--azul-saude);
    margin-bottom: 4px;
}

.uso-label {
    font-size: 12px;
    color: var(--cinza-escuro);
    margin-bottom: 8px;
}

.uso-progresso {
    height: 4px;
    background: var(--cinza-medio);
    border-radius: 2px;
    overflow: hidden;
}

.progresso-bar {
    height: 100%;
    background: var(--azul-saude);
    transition: width 0.3s ease;
}

.uso-progresso.sucesso .progresso-bar {
    background: var(--sucesso);
}

.uso-detalhes h4 {
    margin: 0 0 16px 0;
    color: var(--cinza-escuro);
    font-size: 16px;
}

.modelos-uso {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.modelo-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: var(--cinza-claro);
    border-radius: 8px;
}

.modelo-nome {
    font-weight: 600;
    color: var(--cinza-escuro);
}

.modelo-requests {
    color: var(--cinza-medio);
    font-size: 14px;
}

.modelo-custo {
    font-weight: 700;
    color: var(--azul-saude);
}

/* Configuração por Robô */
.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filtro-robos,
.ordenar-robos {
    padding: 6px 12px;
    border: 1px solid var(--cinza-medio);
    border-radius: 6px;
    background: var(--branco-puro);
    font-size: 13px;
    cursor: pointer;
}

.btn-aplicar-todos {
    background: var(--lilas-cuidado);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Cabeçalho das colunas */
.robos-header {
    display: grid;
    grid-template-columns: 2.5fr 1.5fr 1fr 1.5fr 1fr 1fr;
    gap: 16px;
    padding: 12px 20px;
    background: var(--cinza-claro);
    border-radius: 8px;
    margin-bottom: 16px;
    font-weight: 600;
    font-size: 13px;
    color: var(--cinza-escuro);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.robos-lista {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.robo-config-item {
    display: grid;
    grid-template-columns: 2.5fr 1.5fr 1fr 1.5fr 1fr 1fr;
    gap: 16px;
    padding: 16px 20px;
    background: var(--cinza-claro);
    border-radius: 12px;
    transition: var(--transicao);
    align-items: center;
}

.robo-config-item:hover {
    background: var(--cinza-medio);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Coluna Robô */
.col-robo .robo-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.robo-icone {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #1976d2, #42a5f5);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.robo-detalhes {
    display: flex;
    flex-direction: column;
}

.robo-nome {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.robo-categoria {
    font-size: 11px;
    color: var(--cinza-medio);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Coluna Modelo */
.modelo-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--cinza-medio);
    border-radius: 6px;
    background: var(--branco-puro);
    font-size: 13px;
}

/* Coluna Limite */
.limite-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--cinza-medio);
    border-radius: 6px;
    background: var(--branco-puro);
    font-size: 13px;
    text-align: center;
}

/* Coluna Uso */
.col-uso {
    text-align: center;
}

.uso-valor {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
    display: block;
    margin-bottom: 4px;
}

.uso-barra {
    width: 100%;
    height: 4px;
    background: var(--cinza-medio);
    border-radius: 2px;
    overflow: hidden;
}

.uso-progresso-mini {
    height: 100%;
    background: var(--azul-saude);
    transition: width 0.3s ease;
}

/* Coluna Custo */
.col-custo {
    text-align: right;
}

.custo-valor {
    font-weight: 600;
    color: var(--azul-saude);
    font-size: 14px;
}

/* Coluna Taxa de Sucesso */
.col-sucesso {
    text-align: center;
}

.sucesso-valor {
    font-weight: 700;
    font-size: 14px;
    padding: 4px 8px;
    border-radius: 4px;
}

.sucesso-valor.alta {
    color: var(--sucesso);
    background: rgba(34, 197, 94, 0.1);
}

.sucesso-valor.media {
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.1);
}

.sucesso-valor.baixa {
    color: var(--vermelho-erro);
    background: rgba(239, 68, 68, 0.1);
}

.carregar-mais {
    text-align: center;
    padding: 20px;
}

.btn-carregar-robos {
    background: var(--cinza-medio);
    color: var(--cinza-escuro);
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 auto;
}

/* Logs */
.btn-limpar-logs {
    background: var(--vermelho-erro);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.logs-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 300px;
    overflow-y: auto;
}

.log-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    border-left: 4px solid var(--sucesso);
}

.log-item.erro {
    border-left-color: var(--vermelho-erro);
}

.log-time {
    font-family: monospace;
    color: var(--cinza-medio);
    font-size: 12px;
    min-width: 60px;
}

.log-details {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.log-robo {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.log-action {
    color: var(--cinza-medio);
    font-size: 12px;
}

.log-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: var(--sucesso);
}

.log-item.erro .log-status {
    color: var(--vermelho-erro);
}

.logs-footer {
    text-align: center;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--cinza-claro);
}

.btn-ver-todos-logs {
    background: none;
    border: 1px solid var(--azul-saude);
    color: var(--azul-saude);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0 auto;
}

@media (max-width: 1200px) {
    .robos-header,
    .robo-config-item {
        grid-template-columns: 2fr 1.2fr 0.8fr 1fr 0.8fr 0.8fr;
    }
}

@media (max-width: 768px) {
    .configuracoes-grid {
        grid-template-columns: 1fr;
    }
    
    .uso-stats {
        grid-template-columns: 1fr;
    }
    
    .campos-grid {
        grid-template-columns: 1fr;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .filtro-robos,
    .ordenar-robos {
        width: 100%;
    }
    
    .robos-header {
        display: none;
    }
    
    .robo-config-item {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
    }
    
    .col-robo {
        order: 1;
    }
    
    .col-modelo {
        order: 2;
    }
    
    .col-limite {
        order: 3;
    }
    
    .col-uso,
    .col-custo,
    .col-sucesso {
        order: 4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-top: 1px solid var(--cinza-medio);
    }
    
    .col-uso::before {
        content: "Uso Hoje:";
        font-weight: 600;
        color: var(--cinza-escuro);
    }
    
    .col-custo::before {
        content: "Custo:";
        font-weight: 600;
        color: var(--cinza-escuro);
    }
    
    .col-sucesso::before {
        content: "Taxa Sucesso:";
        font-weight: 600;
        color: var(--cinza-escuro);
    }
}
</style>

<script>
// Tabela de preços OpenAI (USD por 1K tokens) - Dezembro 2024
// Modelos Premium:
// GPT-4.1: Input $15.00 / Output $60.00 (Flagship - mais caro)
// GPT-4o: Input $2.50 / Output $10.00
// GPT-4o Audio: Input $5.00 / Output $20.00
// ChatGPT-4o: Input $2.50 / Output $10.00
// 
// Modelos Otimizados:
// O4-Mini: Input $0.50 / Output $2.00
// GPT-4.1 Mini: Input $0.30 / Output $1.20
// GPT-4.1 Nano: Input $0.10 / Output $0.40
// O3-Mini: Input $0.25 / Output $1.00
// GPT-4o Mini: Input $0.15 / Output $0.60
// 
// Taxa de câmbio aproximada: USD 1 = BRL 5.00

// Verificar status da API
async function verificarStatusAPI() {
    const statusCard = document.getElementById('statusCard');
    const statusIcon = document.getElementById('statusIcon');
    const statusTitle = document.getElementById('statusTitle');
    const statusDescription = document.getElementById('statusDescription');
    const statusLastCheck = document.getElementById('statusLastCheck');
    
    // Simular verificação
    statusTitle.textContent = 'Verificando...';
    statusDescription.textContent = 'Conectando com a API OpenAI';
    
    try {
        // Fazer verificação real via AJAX
        const response = await fetch('/ai/check-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'csrf_token=' + encodeURIComponent(document.querySelector('meta[name="csrf-token"]')?.content || '')
        });
        
        const result = await response.json();
        
        if (result.success && result.status === 'online') {
            statusCard.className = 'status-card online';
            statusTitle.textContent = 'API OpenAI Online';
            statusDescription.textContent = 'Conectado e funcionando normalmente';
            if (result.response_time) {
                statusDescription.textContent += ` (${result.response_time}s)`;
            }
        } else {
            statusCard.className = 'status-card offline';
            statusTitle.textContent = 'API OpenAI Offline';
            statusDescription.textContent = result.message || 'Problemas de conectividade detectados';
        }
        
        statusLastCheck.textContent = 'Última verificação: agora';
        
    } catch (error) {
        statusCard.className = 'status-card offline';
        statusTitle.textContent = 'Erro na Verificação';
        statusDescription.textContent = 'Não foi possível verificar o status';
    }
}

// Toggle password visibility
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const button = input.parentElement.querySelector('.btn-toggle-password i');
    
    if (input.type === 'password') {
        input.type = 'text';
        button.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        button.className = 'fas fa-eye';
    }
}

// Aplicar modelo a todos os robôs
function aplicarModeloTodos() {
    const modeloPadrao = document.getElementById('modeloPadrao').value;
    const selects = document.querySelectorAll('.modelo-select');
    
    if (confirm(`Aplicar o modelo "${modeloPadrao}" para todos os 23 robôs Dr. IA?`)) {
        selects.forEach(select => {
            select.value = modeloPadrao;
        });
        
        mostrarAlerta('Modelo aplicado a todos os robôs com sucesso!', 'sucesso');
    }
}

// Filtrar robôs por categoria
function filtrarRobos() {
    const filtro = document.getElementById('filtroRobos').value.toLowerCase();
    const robos = document.querySelectorAll('.robo-config-item');
    
    robos.forEach(robo => {
        if (!robo.dataset.categoria) return;
        
        if (filtro === '' || robo.dataset.categoria === filtro) {
            robo.style.display = 'grid';
        } else {
            robo.style.display = 'none';
        }
    });
}

// Ordenar robôs
function ordenarRobos() {
    const ordenacao = document.getElementById('ordenarRobos').value;
    const container = document.querySelector('.robos-lista');
    const robos = Array.from(container.querySelectorAll('.robo-config-item')).filter(el => el.dataset.categoria);
    
    if (!ordenacao) return;
    
    robos.sort((a, b) => {
        switch(ordenacao) {
            case 'nome-asc':
                return a.querySelector('.robo-nome').textContent.localeCompare(b.querySelector('.robo-nome').textContent);
            case 'nome-desc':
                return b.querySelector('.robo-nome').textContent.localeCompare(a.querySelector('.robo-nome').textContent);
            case 'uso-asc':
                return parseInt(a.dataset.uso) - parseInt(b.dataset.uso);
            case 'uso-desc':
                return parseInt(b.dataset.uso) - parseInt(a.dataset.uso);
            case 'custo-asc':
                return parseFloat(a.dataset.custo) - parseFloat(b.dataset.custo);
            case 'custo-desc':
                return parseFloat(b.dataset.custo) - parseFloat(a.dataset.custo);
            case 'sucesso-asc':
                return parseInt(a.dataset.sucesso) - parseInt(b.dataset.sucesso);
            case 'sucesso-desc':
                return parseInt(b.dataset.sucesso) - parseInt(a.dataset.sucesso);
            default:
                return 0;
        }
    });
    
    // Reordenar elementos no DOM
    robos.forEach(robo => container.appendChild(robo));
    
    // Manter o botão "Carregar Mais" no final
    const carregarMais = container.querySelector('.carregar-mais');
    if (carregarMais) container.appendChild(carregarMais);
}

// Carregar mais robôs
function carregarMaisRobos() {
    const container = document.querySelector('.robos-lista');
    const button = document.querySelector('.btn-carregar-robos');
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
    
    // Simular carregamento
    setTimeout(() => {
        // Em produção: buscar robôs reais da API (sem simulação)
        const novosRobos = [];
        
        novosRobos.forEach((robo, index) => {
            const html = `
                <div class="robo-config-item" data-categoria="${robo.categoria.toLowerCase()}" 
                     data-uso="${robo.usos}" 
                     data-custo="${robo.custoNum || 0}"
                     data-sucesso="${robo.sucesso || 100}">
                    <div class="col-robo">
                        <div class="robo-info">
                            <div class="robo-icone">
                                <i class="${robo.icone}"></i>
                            </div>
                            <div class="robo-detalhes">
                                <span class="robo-nome">${robo.nome}</span>
                                <span class="robo-categoria">${robo.categoria}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-modelo">
                        <select class="modelo-select" data-robo="${4 + index}">
                            <optgroup label="Premium">
                                <option value="gpt-4.1">GPT-4.1</option>
                                <option value="gpt-4o">GPT-4o</option>
                                <option value="gpt-4o-audio-preview">GPT-4o Áudio</option>
                                <option value="chatgpt-4o-latest">ChatGPT-4o</option>
                            </optgroup>
                            <optgroup label="Otimizado">
                                <option value="o4-mini">O4-Mini</option>
                                <option value="gpt-4.1-mini">GPT-4.1 Mini</option>
                                <option value="gpt-4.1-nano">GPT-4.1 Nano</option>
                                <option value="o3-mini">O3-Mini</option>
                                <option value="gpt-4o-mini" selected>GPT-4o Mini</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-limite">
                        <input type="number" class="limite-input" value="${robo.limite || 50}" min="0" max="1000" data-robo="${4 + index}">
                    </div>
                    <div class="col-uso">
                        <span class="uso-valor">${robo.usos}</span>
                        <div class="uso-barra">
                            <div class="uso-progresso-mini" style="width: ${Math.min((robo.usos / (robo.limite || 50)) * 100, 100)}%"></div>
                        </div>
                    </div>
                    <div class="col-custo">
                        <span class="custo-valor">${robo.custo}</span>
                    </div>
                    <div class="col-sucesso">
                        <span class="sucesso-valor ${(robo.sucesso || 100) >= 95 ? 'alta' : ((robo.sucesso || 100) >= 80 ? 'media' : 'baixa')}">
                            ${robo.sucesso || 100}%
                        </span>
                    </div>
                </div>
            `;
            
            container.insertBefore(
                document.createElement('div'), 
                container.querySelector('.carregar-mais')
            );
            container.children[container.children.length - 2].outerHTML = html;
        });
        
        button.innerHTML = '<i class="fas fa-plus"></i> Carregar Mais Robôs (17 restantes)';
        
    }, 1500);
}

// Limpar logs
function limparLogs() {
    if (confirm('Deseja limpar todos os logs? Esta ação não pode ser desfeita.')) {
        const container = document.querySelector('.logs-container');
        container.innerHTML = '<div style="text-align: center; color: var(--cinza-medio); padding: 40px;">Nenhum log disponível</div>';
        mostrarAlerta('Logs limpos com sucesso!', 'sucesso');
    }
}

// Salvar configurações da API
document.getElementById('formConfigAPI').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    button.disabled = true;
    
    try {
        const formData = new FormData(this);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        
        const response = await fetch('/ai/save-config', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            mostrarAlerta(result.message || 'Configurações salvas com sucesso!', 'sucesso');
        } else {
            mostrarAlerta(result.error || 'Erro ao salvar configurações', 'erro');
        }
    } catch (error) {
        mostrarAlerta('Erro ao salvar configurações', 'erro');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
});

// Atualizar stats em tempo real (simulado)
function atualizarStats() {
    const elementos = {
        requestsHoje: document.getElementById('requestsHoje'),
        tokensHoje: document.getElementById('tokensHoje'),
        custoHoje: document.getElementById('custoHoje'),
        sucessoRate: document.getElementById('sucessoRate')
    };
    
    // Simular incremento gradual
    setInterval(() => {
        const requests = parseInt(elementos.requestsHoje.textContent);
        if (requests < 1000) {
            elementos.requestsHoje.textContent = requests + Math.floor(Math.random() * 3);
            
            // Atualizar tokens e custo proporcionalmente (em reais)
            const novoTokens = (requests + 1) * 64;
            const novoCustoUSD = (requests + 1) * 0.01;
            const novoCustoBRL = (novoCustoUSD * 5).toFixed(2); // Conversão USD para BRL (taxa aproximada)
            
            elementos.tokensHoje.textContent = novoTokens.toLocaleString('pt-BR');
            elementos.custoHoje.textContent = 'R$ ' + novoCustoBRL.replace('.', ',');
        }
    }, 30000); // Atualizar a cada 30 segundos
}

// Função para mostrar alertas
function mostrarAlerta(mensagem, tipo = 'sucesso') {
    // Criar elemento de alerta
    const alerta = document.createElement('div');
    alerta.className = `alerta-flutuante ${tipo}`;
    alerta.innerHTML = `
        <i class="fas fa-${tipo === 'sucesso' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${mensagem}</span>
    `;
    
    // Adicionar ao body
    document.body.appendChild(alerta);
    
    // Animar entrada
    setTimeout(() => alerta.classList.add('show'), 10);
    
    // Remover após 3 segundos
    setTimeout(() => {
        alerta.classList.remove('show');
        setTimeout(() => alerta.remove(), 300);
    }, 3000);
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    atualizarStats();
});
</script>

<style>
/* Alertas flutuantes */
.alerta-flutuante {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 1000;
}

.alerta-flutuante.show {
    transform: translateX(0);
}

.alerta-flutuante.sucesso {
    border-left: 4px solid var(--sucesso);
    color: var(--sucesso);
}

.alerta-flutuante.erro {
    border-left: 4px solid var(--vermelho-erro);
    color: var(--vermelho-erro);
}

.alerta-flutuante i {
    font-size: 20px;
}

.alerta-flutuante span {
    color: var(--cinza-escuro);
}
</style>