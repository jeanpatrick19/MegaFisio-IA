<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Gestão de Prompts IA</h1>
<p class="subtitulo-pagina-escuro">Configure e gerencie todos os prompts de inteligência artificial do sistema</p>

<!-- Estatísticas dos Prompts -->
<div class="prompts-stats">
    <div class="stat-card-prompt">
        <div class="stat-icone-prompt total">
            <i class="fas fa-brain"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['total_prompts'] ?? 5 ?></div>
            <div class="stat-label-escuro">Total de Prompts</div>
        </div>
    </div>
    
    <div class="stat-card-prompt ativo">
        <div class="stat-icone-prompt ativo">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['active_prompts'] ?? 5 ?></div>
            <div class="stat-label-escuro">Prompts Ativos</div>
        </div>
    </div>
    
    <div class="stat-card-prompt uso">
        <div class="stat-icone-prompt uso">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['total_requests'] ?? 1250 ?></div>
            <div class="stat-label-escuro">Solicitações Hoje</div>
        </div>
    </div>
    
    <div class="stat-card-prompt sucesso">
        <div class="stat-icone-prompt sucesso">
            <i class="fas fa-thumbs-up"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['success_rate'] ?? 98 ?>%</div>
            <div class="stat-label-escuro">Taxa de Sucesso</div>
        </div>
    </div>
</div>

<!-- Ações e Filtros -->
<div class="prompts-acoes">
    <div class="acoes-esquerda">
        <button class="btn-fisio btn-primario" onclick="abrirModalNovoPrompt()">
            <i class="fas fa-plus"></i>
            Novo Prompt
        </button>
        
        <button class="btn-fisio btn-secundario" onclick="importarPrompts()">
            <i class="fas fa-file-import"></i>
            Importar Prompts
        </button>
        
        <button class="btn-fisio btn-secundario" onclick="abrirModalConfiguracoes()">
            <i class="fas fa-cog"></i>
            Configurações IA
        </button>
    </div>
    
    <div class="acoes-direita">
        <div class="filtro-grupo">
            <select class="filtro-select" id="filtroCategoria" onchange="filtrarPrompts()">
                <option value="">Todas as Especialidades</option>
                <option value="ortopedica">Ortopédica</option>
                <option value="neurologica">Neurológica</option>
                <option value="respiratoria">Respiratória</option>
                <option value="geriatrica">Geriátrica</option>
                <option value="pediatrica">Pediátrica</option>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <select class="filtro-select" id="filtroStatus" onchange="filtrarPrompts()">
                <option value="">Todos os Status</option>
                <option value="active">Ativos</option>
                <option value="inactive">Inativos</option>
                <option value="draft">Rascunhos</option>
            </select>
        </div>
        
        <div class="busca-grupo">
            <div class="busca-input">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar prompts..." id="buscaPrompt" onkeyup="buscarPrompts()">
            </div>
        </div>
    </div>
</div>

<!-- Lista de Prompts -->
<div class="card-fisio prompts-lista-container">
    <div class="card-header-fisio">
        <div class="card-titulo">
            <i class="fas fa-list"></i>
            <span>Prompts Cadastrados</span>
            <span class="contador-prompts" id="contadorPrompts">(<?= count($prompts ?? []) ?> prompts)</span>
        </div>
        <div class="opcoes-lista">
            <button class="btn-opcao" onclick="alternarVisualizacao()">
                <i class="fas fa-th-large" id="iconeVisualizacao"></i>
            </button>
            <button class="btn-opcao" onclick="exportarPrompts()">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>
    
    <div class="prompts-lista" id="promptsLista">
        <?php 
        $promptsData = [
            [
                'id' => 1,
                'name' => 'Fisioterapia Ortopédica',
                'category' => 'ortopedica',
                'description' => 'Análise especializada em condições musculoesqueléticas',
                'status' => 'active',
                'usage_count' => 450,
                'success_rate' => 96,
                'created_at' => '2024-01-15 10:30:00',
                'updated_at' => '2024-01-20 15:45:00'
            ],
            [
                'id' => 2,
                'name' => 'Fisioterapia Neurológica',
                'category' => 'neurologica',
                'description' => 'Tratamento de distúrbios neurológicos e reabilitação funcional',
                'status' => 'active',
                'usage_count' => 320,
                'success_rate' => 94,
                'created_at' => '2024-01-16 09:15:00',
                'updated_at' => '2024-01-19 11:20:00'
            ],
            [
                'id' => 3,
                'name' => 'Fisioterapia Respiratória',
                'category' => 'respiratoria',
                'description' => 'Cuidados respiratórios e reabilitação pulmonar',
                'status' => 'active',
                'usage_count' => 180,
                'success_rate' => 98,
                'created_at' => '2024-01-17 14:20:00',
                'updated_at' => '2024-01-21 16:30:00'
            ],
            [
                'id' => 4,
                'name' => 'Fisioterapia Geriátrica',
                'category' => 'geriatrica',
                'description' => 'Cuidados especializados para pacientes idosos',
                'status' => 'active',
                'usage_count' => 220,
                'success_rate' => 97,
                'created_at' => '2024-01-18 16:45:00',
                'updated_at' => '2024-01-22 10:15:00'
            ],
            [
                'id' => 5,
                'name' => 'Fisioterapia Pediátrica',
                'category' => 'pediatrica',
                'description' => 'Desenvolvimento motor e reabilitação infantil',
                'status' => 'draft',
                'usage_count' => 0,
                'success_rate' => 0,
                'created_at' => '2024-01-23 13:30:00',
                'updated_at' => '2024-01-23 13:30:00'
            ]
        ];
        
        foreach ($promptsData as $prompt): 
        ?>
            <div class="prompt-item" data-status="<?= $prompt['status'] ?>" data-category="<?= $prompt['category'] ?>">
                <div class="prompt-header">
                    <div class="prompt-icone-especialidade <?= $prompt['category'] ?>">
                        <?php
                        $icones = [
                            'ortopedica' => 'fa-bone',
                            'neurologica' => 'fa-brain',
                            'respiratoria' => 'fa-lungs',
                            'geriatrica' => 'fa-user-clock',
                            'pediatrica' => 'fa-baby'
                        ];
                        ?>
                        <i class="fas <?= $icones[$prompt['category']] ?>"></i>
                    </div>
                    <div class="prompt-info-principal">
                        <div class="prompt-nome"><?= htmlspecialchars($prompt['name']) ?></div>
                        <div class="prompt-descricao"><?= htmlspecialchars($prompt['description']) ?></div>
                        <div class="prompt-meta">
                            <span class="prompt-categoria"><?= ucfirst($prompt['category']) ?></span>
                            <span class="prompt-status status-<?= $prompt['status'] ?>">
                                <?= $prompt['status'] === 'active' ? 'Ativo' : ($prompt['status'] === 'draft' ? 'Rascunho' : 'Inativo') ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="prompt-estatisticas">
                    <div class="stat-prompt-item">
                        <div class="stat-prompt-numero"><?= $prompt['usage_count'] ?></div>
                        <div class="stat-prompt-label">Usos</div>
                    </div>
                    <div class="stat-prompt-item">
                        <div class="stat-prompt-numero"><?= $prompt['success_rate'] ?>%</div>
                        <div class="stat-prompt-label">Sucesso</div>
                    </div>
                    <div class="stat-prompt-item">
                        <div class="stat-prompt-numero"><?= date('d/m', strtotime($prompt['updated_at'])) ?></div>
                        <div class="stat-prompt-label">Atualizado</div>
                    </div>
                </div>
                
                <div class="prompt-acoes">
                    <button class="btn-acao editar" onclick="editarPrompt(<?= $prompt['id'] ?>)" data-tooltip="Editar prompt">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-acao testar" onclick="testarPrompt(<?= $prompt['id'] ?>)" data-tooltip="Testar prompt">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="btn-acao duplicar" onclick="duplicarPrompt(<?= $prompt['id'] ?>)" data-tooltip="Duplicar prompt">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn-acao <?= $prompt['status'] === 'active' ? 'pausar' : 'ativar' ?>" 
                            onclick="alterarStatusPrompt(<?= $prompt['id'] ?>, '<?= $prompt['status'] ?>')" 
                            data-tooltip="<?= $prompt['status'] === 'active' ? 'Desativar' : 'Ativar' ?>">
                        <i class="fas fa-<?= $prompt['status'] === 'active' ? 'pause' : 'play' ?>"></i>
                    </button>
                    <button class="btn-acao excluir" onclick="confirmarExclusaoPrompt(<?= $prompt['id'] ?>, '<?= htmlspecialchars($prompt['name']) ?>')" data-tooltip="Excluir prompt">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Novo/Editar Prompt -->
<div class="modal-overlay" id="modalPrompt" style="display: none;">
    <div class="modal-container modal-grande">
        <div class="modal-header">
            <h3 id="modalPromptTitulo">Criar Novo Prompt</h3>
            <button class="modal-close" onclick="fecharModal('modalPrompt')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formPrompt" class="modal-form">
            <input type="hidden" id="promptId" name="prompt_id" value="">
            
            <div class="form-grid-prompt">
                <div class="form-coluna-esquerda">
                    <div class="form-grupo">
                        <label for="nomePrompt">Nome do Prompt *</label>
                        <input type="text" id="nomePrompt" name="nome" placeholder="Ex: Fisioterapia Esportiva" required>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="categoriaPrompt">Especialidade *</label>
                        <select id="categoriaPrompt" name="categoria" required>
                            <option value="">Selecione...</option>
                            <option value="ortopedica">Fisioterapia Ortopédica</option>
                            <option value="neurologica">Fisioterapia Neurológica</option>
                            <option value="respiratoria">Fisioterapia Respiratória</option>
                            <option value="geriatrica">Fisioterapia Geriátrica</option>
                            <option value="pediatrica">Fisioterapia Pediátrica</option>
                            <option value="esportiva">Fisioterapia Esportiva</option>
                            <option value="dermatofuncional">Dermatofuncional</option>
                        </select>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="descricaoPrompt">Descrição</label>
                        <textarea id="descricaoPrompt" name="descricao" rows="3" placeholder="Descreva o objetivo e aplicação deste prompt..."></textarea>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="statusPrompt">Status</label>
                        <select id="statusPrompt" name="status">
                            <option value="draft">Rascunho</option>
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-coluna-direita">
                    <div class="form-grupo">
                        <label for="sistemaPrompt">Prompt do Sistema *</label>
                        <textarea id="sistemaPrompt" name="sistema_prompt" rows="8" required placeholder="Você é um fisioterapeuta especialista em... Analise os dados do paciente e forneça..."></textarea>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="templateResposta">Template de Resposta</label>
                        <textarea id="templateResposta" name="template_resposta" rows="4" placeholder="# Análise Fisioterapêutica
## Diagnóstico
## Plano de Tratamento
## Exercícios Recomendados"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-configuracoes-avancadas">
                <h4>Configurações Avançadas</h4>
                <div class="form-grid-configuracoes">
                    <div class="form-grupo">
                        <label for="temperatura">Temperatura (Criatividade)</label>
                        <input type="range" id="temperatura" name="temperatura" min="0" max="1" step="0.1" value="0.7">
                        <span id="temperaturaValor">0.7</span>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="maxTokens">Máximo de Tokens</label>
                        <input type="number" id="maxTokens" name="max_tokens" value="2048" min="100" max="4096">
                    </div>
                    
                    <div class="form-grupo">
                        <label for="topP">Top P</label>
                        <input type="range" id="topP" name="top_p" min="0" max="1" step="0.1" value="0.9">
                        <span id="topPValor">0.9</span>
                    </div>
                </div>
            </div>
            
            <div class="modal-acoes">
                <button type="button" class="btn-fisio btn-secundario" onclick="testarPromptAtual()">
                    <i class="fas fa-play"></i>
                    Testar Prompt
                </button>
                <button type="button" class="btn-fisio btn-secundario" onclick="fecharModal('modalPrompt')">
                    Cancelar
                </button>
                <button type="submit" class="btn-fisio btn-primario">
                    <i class="fas fa-save"></i>
                    Salvar Prompt
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Estatísticas dos Prompts */
.prompts-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card-prompt {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-medio);
    transition: var(--transicao);
}

.stat-card-prompt:hover {
    transform: translateY(-2px);
    box-shadow: var(--sombra-forte);
}

.stat-icone-prompt {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icone-prompt.total {
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
}

.stat-icone-prompt.ativo {
    background: linear-gradient(135deg, #059669, #10b981);
}

.stat-icone-prompt.uso {
    background: linear-gradient(135deg, #ca8a04, #eab308);
}

.stat-icone-prompt.sucesso {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

/* Filtros e Busca */
.filtro-select {
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
    min-width: 180px;
}

.filtro-select:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.filtro-select:hover {
    border-color: var(--azul-saude);
}

.busca-input {
    position: relative;
    min-width: 280px;
}

.busca-input i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--cinza-medio);
    font-size: 16px;
}

.busca-input input {
    padding: 12px 16px 12px 40px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    font-weight: 500;
    color: var(--cinza-escuro);
    background: var(--branco-puro);
    font-family: inherit;
    transition: var(--transicao);
    width: 100%;
}

.busca-input input:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.busca-input input:focus + i {
    color: var(--azul-saude);
}

.busca-input input:hover {
    border-color: var(--azul-saude);
}

/* Ações e Filtros */
.prompts-acoes {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.acoes-esquerda {
    display: flex;
    gap: 12px;
}

.acoes-direita {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* Lista de Prompts */
.prompts-lista {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.prompt-item {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 24px;
    background: var(--cinza-claro);
    border-radius: 16px;
    transition: var(--transicao);
    border: 1px solid transparent;
}

.prompt-item:hover {
    background: white;
    border-color: var(--azul-saude);
    transform: translateX(4px);
    box-shadow: var(--sombra-media);
}

.prompt-header {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
}

.prompt-icone-especialidade {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.prompt-icone-especialidade.ortopedica {
    background: linear-gradient(135deg, #059669, #10b981);
}

.prompt-icone-especialidade.neurologica {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.prompt-icone-especialidade.respiratoria {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
}

.prompt-icone-especialidade.geriatrica {
    background: linear-gradient(135deg, #ca8a04, #eab308);
}

.prompt-icone-especialidade.pediatrica {
    background: linear-gradient(135deg, #ec4899, #f472b6);
}

.prompt-info-principal {
    flex: 1;
}

.prompt-nome {
    font-size: 18px;
    font-weight: 700;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.prompt-descricao {
    font-size: 14px;
    color: var(--cinza-escuro);
    margin-bottom: 8px;
    line-height: 1.4;
}

.prompt-meta {
    display: flex;
    gap: 12px;
    align-items: center;
}

.prompt-categoria {
    background: var(--azul-saude);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.prompt-status {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--sucesso);
}

.status-draft {
    background: rgba(245, 158, 11, 0.1);
    color: var(--alerta);
}

.status-inactive {
    background: rgba(107, 114, 128, 0.1);
    color: var(--cinza-medio);
}

/* Estatísticas do Prompt */
.prompt-estatisticas {
    display: flex;
    gap: 20px;
}

.stat-prompt-item {
    text-align: center;
    min-width: 60px;
}

.stat-prompt-numero {
    font-size: 16px;
    font-weight: 700;
    color: var(--cinza-escuro);
    font-family: 'JetBrains Mono', monospace;
}

.stat-prompt-label {
    font-size: 10px;
    color: var(--cinza-escuro);
    text-transform: uppercase;
    margin-top: 2px;
    font-weight: 600;
}

/* Ações do Prompt */
.prompt-acoes {
    display: flex;
    gap: 8px;
}

.btn-acao {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transicao);
    font-size: 14px;
    background: var(--cinza-claro);
    color: var(--cinza-escuro);
}

.btn-acao.editar:hover {
    background: var(--azul-saude);
    color: white;
}

.btn-acao.testar:hover {
    background: var(--info);
    color: white;
}

.btn-acao.duplicar:hover {
    background: var(--dourado-premium);
    color: white;
}

.btn-acao.ativar:hover {
    background: var(--sucesso);
    color: white;
}

.btn-acao.pausar:hover {
    background: var(--alerta);
    color: white;
}

.btn-acao.excluir:hover {
    background: var(--erro);
    color: white;
}

/* Modal Grande */
.modal-grande {
    max-width: 1000px;
    width: 95%;
}

.form-grid-prompt {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    margin-bottom: 24px;
}

.form-coluna-esquerda,
.form-coluna-direita {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-configuracoes-avancadas {
    border-top: 1px solid var(--cinza-medio);
    padding-top: 20px;
    margin-top: 20px;
}

.form-configuracoes-avancadas h4 {
    color: var(--azul-saude);
    margin-bottom: 16px;
    font-size: 16px;
}

.form-grid-configuracoes {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.form-grupo input[type="range"] {
    width: 100%;
    margin-bottom: 4px;
}

.form-grupo span {
    font-size: 12px;
    color: var(--cinza-medio);
    font-family: 'JetBrains Mono', monospace;
}

/* Correções para filtros e campos */
.filtro-select {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
    color: var(--cinza-escuro);
    font-family: inherit;
    min-width: 180px;
}

.filtro-select:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

/* Busca */
.busca-grupo {
    display: flex;
    align-items: center;
}

.busca-input {
    position: relative;
    display: flex;
    align-items: center;
    min-width: 250px;
}

.busca-input i {
    position: absolute;
    left: 12px;
    color: var(--cinza-medio);
    font-size: 16px;
    z-index: 2;
}

.busca-input input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
    color: var(--cinza-escuro);
    font-family: inherit;
}

.busca-input input:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.busca-input input::placeholder {
    color: var(--cinza-medio);
}

/* Opções da lista */
.opcoes-lista {
    display: flex;
    gap: 8px;
}

.btn-opcao {
    width: 36px;
    height: 36px;
    background: var(--cinza-claro);
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transicao);
    color: var(--cinza-escuro);
}

.btn-opcao:hover {
    background: var(--azul-saude);
    color: white;
}

/* Contador */
.contador-prompts {
    color: var(--cinza-escuro);
    font-weight: 400;
    font-size: 14px;
    margin-left: 8px;
}

/* Header do card */
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
    font-size: 20px;
}

/* Subtítulo escuro */
.subtitulo-pagina-escuro {
    font-size: 16px;
    color: var(--cinza-escuro);
    margin-bottom: 32px;
    font-weight: 500;
}

/* Stat labels escuros */
.stat-label-escuro {
    font-size: 14px;
    color: var(--cinza-escuro);
    font-weight: 600;
    margin-top: 4px;
}

/* Container da lista */
.prompts-lista-container {
    margin-top: 24px;
}

/* Formulários do modal */
.form-grupo {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-grupo label {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.form-grupo input,
.form-grupo select,
.form-grupo textarea {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
    color: var(--cinza-escuro);
    font-family: inherit;
}

.form-grupo input:focus,
.form-grupo select:focus,
.form-grupo textarea:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.form-grupo textarea {
    min-height: 100px;
    resize: vertical;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.modal-container {
    background: var(--branco-puro);
    border-radius: 16px;
    max-width: 500px;
    width: 90%;
    max-height: 90%;
    overflow-y: auto;
    box-shadow: var(--sombra-flutuante);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid var(--cinza-medio);
}

.modal-header h3 {
    color: var(--cinza-escuro);
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--cinza-medio);
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transicao);
}

.modal-close:hover {
    background: var(--cinza-claro);
    color: var(--cinza-escuro);
}

.modal-form {
    padding: 24px;
}

.modal-acoes {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
}

/* Responsivo */
@media (max-width: 1024px) {
    .form-grid-prompt {
        grid-template-columns: 1fr;
    }
    
    .form-grid-configuracoes {
        grid-template-columns: 1fr;
    }
    
    .prompt-item {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .prompt-estatisticas {
        justify-content: space-around;
    }
    
    .prompts-acoes {
        flex-direction: column;
    }
    
    .acoes-esquerda,
    .acoes-direita {
        width: 100%;
        justify-content: center;
    }
    
    .filtro-select {
        min-width: 100%;
    }
    
    .busca-input {
        min-width: 100%;
    }
}

@media (max-width: 768px) {
    .prompts-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .acoes-esquerda,
    .acoes-direita {
        flex-direction: column;
    }
    
    .btn-fisio {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .prompts-stats {
        grid-template-columns: 1fr;
    }
    
    .modal-grande {
        max-width: 100%;
        margin: 0;
        border-radius: 0;
        height: 100%;
    }
}
</style>

<script>
// Filtrar prompts
function filtrarPrompts() {
    const categoria = document.getElementById('filtroCategoria').value;
    const status = document.getElementById('filtroStatus').value;
    const prompts = document.querySelectorAll('.prompt-item');
    let contador = 0;
    
    prompts.forEach(prompt => {
        const promptCategoria = prompt.dataset.category;
        const promptStatus = prompt.dataset.status;
        
        const mostrar = (!categoria || promptCategoria === categoria) && 
                       (!status || promptStatus === status);
        
        prompt.style.display = mostrar ? 'flex' : 'none';
        if (mostrar) contador++;
    });
    
    document.getElementById('contadorPrompts').textContent = `(${contador} prompts)`;
}

// Buscar prompts
function buscarPrompts() {
    const termo = document.getElementById('buscaPrompt').value.toLowerCase();
    const prompts = document.querySelectorAll('.prompt-item');
    let contador = 0;
    
    prompts.forEach(prompt => {
        const nome = prompt.querySelector('.prompt-nome').textContent.toLowerCase();
        const descricao = prompt.querySelector('.prompt-descricao').textContent.toLowerCase();
        
        const mostrar = nome.includes(termo) || descricao.includes(termo);
        
        prompt.style.display = mostrar ? 'flex' : 'none';
        if (mostrar) contador++;
    });
    
    document.getElementById('contadorPrompts').textContent = `(${contador} prompts)`;
}

// Modal
function abrirModalNovoPrompt() {
    document.getElementById('modalPromptTitulo').textContent = 'Criar Novo Prompt';
    document.getElementById('formPrompt').reset();
    document.getElementById('promptId').value = '';
    document.getElementById('modalPrompt').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Ações dos prompts
function editarPrompt(id) {
    document.getElementById('modalPromptTitulo').textContent = 'Editar Prompt';
    document.getElementById('promptId').value = id;
    // Carregar dados do prompt
    document.getElementById('modalPrompt').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function testarPrompt(id) {
    mostrarAlerta('Redirecionando para teste do prompt...', 'info');
    setTimeout(() => {
        window.location.href = '/ai/test/' + id;
    }, 1000);
}

function duplicarPrompt(id) {
    if (confirm('Deseja duplicar este prompt?')) {
        mostrarAlerta('Prompt duplicado com sucesso!', 'sucesso');
    }
}

function alterarStatusPrompt(id, status) {
    const novoStatus = status === 'active' ? 'inactive' : 'active';
    const acao = novoStatus === 'active' ? 'ativar' : 'desativar';
    
    if (confirm(`Confirma ${acao} este prompt?`)) {
        mostrarAlerta(`Prompt ${acao === 'ativar' ? 'ativado' : 'desativado'} com sucesso!`, 'sucesso');
    }
}

function confirmarExclusaoPrompt(id, nome) {
    if (confirm(`ATENÇÃO: Deseja excluir permanentemente o prompt "${nome}"?\n\nEsta ação não pode ser desfeita!`)) {
        mostrarAlerta('Prompt excluído permanentemente', 'sucesso');
    }
}

function importarPrompts() {
    mostrarAlerta('Funcionalidade de importação será implementada', 'info');
}

function exportarPrompts() {
    mostrarAlerta('Exportando prompts...', 'info');
}

function alternarVisualizacao() {
    mostrarAlerta('Visualização em cards será implementada', 'info');
}

function abrirModalConfiguracoes() {
    mostrarAlerta('Modal de configurações da IA será implementado', 'info');
}

function testarPromptAtual() {
    mostrarAlerta('Teste do prompt será implementado', 'info');
}

// Sliders
document.getElementById('temperatura').addEventListener('input', function() {
    document.getElementById('temperaturaValor').textContent = this.value;
});

document.getElementById('topP').addEventListener('input', function() {
    document.getElementById('topPValor').textContent = this.value;
});

// Submissão do formulário
document.getElementById('formPrompt').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nome = document.getElementById('nomePrompt').value;
    const categoria = document.getElementById('categoriaPrompt').value;
    const sistemaPrompt = document.getElementById('sistemaPrompt').value;
    
    if (!nome || !categoria || !sistemaPrompt) {
        mostrarAlerta('Preencha todos os campos obrigatórios', 'aviso');
        return;
    }
    
    mostrarAlerta('Prompt salvo com sucesso!', 'sucesso');
    fecharModal('modalPrompt');
});

// Fechar modal clicando fora
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        fecharModal(e.target.id);
    }
});
</script>