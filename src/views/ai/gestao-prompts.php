<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

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
            <div class="stat-numero" id="totalPrompts"><?= $stats['total_prompts'] ?></div>
            <div class="stat-label-escuro">Total de Prompts</div>
        </div>
    </div>
    
    <div class="stat-card-prompt ativo">
        <div class="stat-icone-prompt ativo">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero" id="promptsAtivos"><?= $stats['active_prompts'] ?></div>
            <div class="stat-label-escuro">Prompts Ativos</div>
        </div>
    </div>
    
    <div class="stat-card-prompt uso">
        <div class="stat-icone-prompt uso">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero" id="totalUsos"><?= $stats['total_requests'] ?></div>
            <div class="stat-label-escuro">Solicitações Total</div>
        </div>
    </div>
    
    <div class="stat-card-prompt sucesso">
        <div class="stat-icone-prompt sucesso">
            <i class="fas fa-thumbs-up"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero" id="taxaSucesso"><?= $stats['success_rate'] ?>%</div>
            <div class="stat-label-escuro">Taxa de Sucesso</div>
        </div>
    </div>
</div>

<!-- Ações e Filtros -->
<div class="prompts-acoes">
    <div class="acoes-esquerda">
        <button class="btn-fisio btn-primario" onclick="abrirModalNovoPrompt()">
            <i class="fas fa-robot"></i>
            Novo Robô Dr. IA
        </button>
        
        <button class="btn-fisio btn-secundario" onclick="importarPrompts()">
            <i class="fas fa-file-import"></i>
            Importar Robôs
        </button>
        
        <button class="btn-fisio btn-secundario" onclick="abrirModalConfiguracoes()">
            <i class="fas fa-cog"></i>
            Configurações IA
        </button>
    </div>
    
    <div class="acoes-direita">
        <div class="filtro-grupo">
            <select class="filtro-select" id="filtroCategoria" onchange="filtrarPrompts()">
                <option value="">Todas as Categorias</option>
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
            <span class="contador-prompts" id="contadorPrompts">(<?= count($promptsData) ?> robôs Dr. IA)</span>
        </div>
        <div class="opcoes-lista">
            <button class="btn-opcao" onclick="alternarVisualizacao()">
                <i class="fas fa-th-large" id="iconeVisualizacao"></i>
            </button>
            <div class="dropdown-export">
                <button class="btn-opcao" onclick="toggleDropdownExport()">
                    <i class="fas fa-download"></i>
                </button>
                <div class="dropdown-content" id="dropdownExport">
                    <a href="#" onclick="exportarCSV()"><i class="fas fa-file-csv"></i> CSV</a>
                    <a href="#" onclick="exportarExcel()"><i class="fas fa-file-excel"></i> Excel</a>
                    <a href="#" onclick="exportarPDF()"><i class="fas fa-file-pdf"></i> PDF</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="prompts-lista" id="promptsLista">
        <?php 
        // Usar dados do controller se disponível, senão fallback
        if (!isset($promptsData)) {
            $promptsData = [
            // Marketing e Vendas
            ['id' => 1, 'name' => 'Dr. Autoritas', 'category' => 'marketing', 'description' => 'Conteúdo para Instagram', 'status' => 'active', 'usage_count' => 1250, 'success_rate' => 95, 'icon' => 'fa-instagram', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 2, 'name' => 'Dr. Acolhe', 'category' => 'atendimento', 'description' => 'Atendimento via WhatsApp/Direct', 'status' => 'active', 'usage_count' => 890, 'success_rate' => 98, 'icon' => 'fa-whatsapp', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 3, 'name' => 'Dr. Fechador', 'category' => 'vendas', 'description' => 'Vendas de Planos Fisioterapêuticos', 'status' => 'active', 'usage_count' => 567, 'success_rate' => 92, 'icon' => 'fa-handshake', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 4, 'name' => 'Dr. Local', 'category' => 'marketing', 'description' => 'Autoridade de Bairro', 'status' => 'active', 'usage_count' => 432, 'success_rate' => 94, 'icon' => 'fa-map-marker-alt', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 5, 'name' => 'Dr. Recall', 'category' => 'fidelizacao', 'description' => 'Fidelização e Retorno de Pacientes', 'status' => 'active', 'usage_count' => 723, 'success_rate' => 96, 'icon' => 'fa-undo', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            
            // Clínica e Terapêutica
            ['id' => 6, 'name' => 'Dr. Reab', 'category' => 'clinica', 'description' => 'Prescrição de Exercícios Personalizados', 'status' => 'active', 'usage_count' => 1890, 'success_rate' => 99, 'icon' => 'fa-dumbbell', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 7, 'name' => 'Dra. Protoc', 'category' => 'clinica', 'description' => 'Protocolos Terapêuticos Estruturados', 'status' => 'active', 'usage_count' => 1456, 'success_rate' => 97, 'icon' => 'fa-clipboard-list', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 8, 'name' => 'Dr. Injetáveis', 'category' => 'clinica', 'description' => 'Protocolos Terapêuticos com Injetáveis', 'status' => 'active', 'usage_count' => 678, 'success_rate' => 96, 'icon' => 'fa-syringe', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 9, 'name' => 'Dr. Evolucio', 'category' => 'clinica', 'description' => 'Acompanhamento Clínico do Paciente', 'status' => 'active', 'usage_count' => 1123, 'success_rate' => 98, 'icon' => 'fa-chart-line', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 10, 'name' => 'Dra. Contrology', 'category' => 'especialidades', 'description' => 'Especialista em Pilates clássico terapêutico', 'status' => 'active', 'usage_count' => 834, 'success_rate' => 97, 'icon' => 'fa-yoga', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 11, 'name' => 'Dr. Posturalis', 'category' => 'especialidades', 'description' => 'Especialista em RPG de Souchard', 'status' => 'active', 'usage_count' => 612, 'success_rate' => 95, 'icon' => 'fa-user-check', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            
            // Educação e Documentação
            ['id' => 12, 'name' => 'Dra. Edu', 'category' => 'educacao', 'description' => 'Materiais Educativos para Pacientes', 'status' => 'active', 'usage_count' => 945, 'success_rate' => 94, 'icon' => 'fa-graduation-cap', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 13, 'name' => 'Dr. Científico', 'category' => 'educacao', 'description' => 'Resumos de Artigos e Evidências', 'status' => 'active', 'usage_count' => 756, 'success_rate' => 98, 'icon' => 'fa-microscope', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            
            // Jurídico e Documentação
            ['id' => 14, 'name' => 'Dra. Legal', 'category' => 'juridico', 'description' => 'Termos de Consentimento Personalizados', 'status' => 'active', 'usage_count' => 543, 'success_rate' => 99, 'icon' => 'fa-gavel', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 15, 'name' => 'Dr. Contratus', 'category' => 'juridico', 'description' => 'Contratos de Prestação de Serviço', 'status' => 'active', 'usage_count' => 398, 'success_rate' => 97, 'icon' => 'fa-file-contract', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 16, 'name' => 'Dr. Imago', 'category' => 'juridico', 'description' => 'Autorização de Uso de Imagem', 'status' => 'active', 'usage_count' => 467, 'success_rate' => 96, 'icon' => 'fa-camera', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 17, 'name' => 'Dr. Peritus', 'category' => 'juridico', 'description' => 'Mestre das Perícias', 'status' => 'active', 'usage_count' => 289, 'success_rate' => 98, 'icon' => 'fa-balance-scale', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            
            // Diagnóstico e Análise
            ['id' => 18, 'name' => 'Dr. Imaginário', 'category' => 'diagnostico', 'description' => 'Análise de Exames de Imagem (RX, USG, RNM)', 'status' => 'active', 'usage_count' => 1234, 'success_rate' => 97, 'icon' => 'fa-x-ray', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 19, 'name' => 'Dr. Diagnostik', 'category' => 'diagnostico', 'description' => 'Mapeamento de Marcadores para Fisioterapia', 'status' => 'active', 'usage_count' => 876, 'success_rate' => 96, 'icon' => 'fa-search-plus', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 20, 'name' => 'Dr. Integralis', 'category' => 'diagnostico', 'description' => 'Análise Funcional de Exames Laboratoriais', 'status' => 'active', 'usage_count' => 654, 'success_rate' => 95, 'icon' => 'fa-flask', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            
            // Gestão e Qualidade
            ['id' => 21, 'name' => 'Dr. POP', 'category' => 'gestao', 'description' => 'Protocolos Operacionais Padrão', 'status' => 'active', 'usage_count' => 432, 'success_rate' => 98, 'icon' => 'fa-folder-open', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 22, 'name' => 'Dr. Vigilantis', 'category' => 'gestao', 'description' => 'Documentação Vigilância Sanitária', 'status' => 'active', 'usage_count' => 321, 'success_rate' => 99, 'icon' => 'fa-shield-alt', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00'],
            ['id' => 23, 'name' => 'Dr. Fórmula Oral', 'category' => 'farmacologia', 'description' => 'Propostas Farmacológicas Via Oral para Dor', 'status' => 'active', 'usage_count' => 567, 'success_rate' => 94, 'icon' => 'fa-pills', 'created_at' => '2024-01-15 10:30:00', 'updated_at' => '2024-01-27 15:45:00']
            ];
        }
        
        foreach ($promptsData as $prompt): 
        ?>
            <div class="prompt-item" data-prompt-id="<?= $prompt['id'] ?>" data-status="<?= $prompt['status'] ?>" data-category="<?= $prompt['category'] ?>">
                <div class="prompt-header">
                    <div class="prompt-icone-especialidade <?= $prompt['category'] ?>">
                        <i class="prompt-icone <?= isset($prompt['icon']) ? $prompt['icon'] : 'fas fa-robot' ?>"></i>
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
                        <div class="stat-prompt-numero"><?= isset($prompt['updated_at']) ? date('d/m', strtotime($prompt['updated_at'])) : date('d/m') ?></div>
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

<!-- Modal Criar/Editar Robô Dr. IA -->
<div class="modal-overlay" id="modalPrompt" style="display: none;">
    <div class="modal-container-robos">
        <div class="modal-header">
            <h3 id="modalPromptTitulo">🤖 Criar Novo Robô Dr. IA</h3>
            <button class="btn-fechar" onclick="fecharModal('modalPrompt')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-content-scroll">
            <form id="formPrompt" class="modal-content" action="#" method="post" onsubmit="return false;">
                <input type="hidden" id="promptId" name="promptId">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="campos-grid">
                    <div class="campo-grupo">
                        <label for="promptNome">Nome do Robô</label>
                        <input type="text" id="promptNome" name="promptNome" required 
                               placeholder="Ex: Dr. Especialista">
                    </div>
                    
                    <div class="campo-grupo">
                        <label for="promptIcone">Ícone FontAwesome</label>
                        <div class="icone-selector">
                            <div class="icone-container">
                                <input type="text" id="promptIcone" name="promptIcone" required readonly
                                       placeholder="Ícone será selecionado automaticamente">
                                <div class="icone-preview" id="iconePreview">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <button type="button" class="btn-selecionar-icone" onclick="abrirSeletorIcone()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="icones-grid" id="iconesGrid" style="display: none;">
                                <!-- Ícones serão carregados aqui -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="campo-grupo">
                    <label for="promptCategoria">Categoria</label>
                    <div class="categoria-container">
                        <select id="promptCategoria" name="promptCategoria" onchange="toggleCategoriaCustom()">
                            <option value="">Selecione uma categoria</option>
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
                            <option value="custom">➕ Outra categoria...</option>
                        </select>
                        <input type="text" id="categoriaCustom" name="categoriaCustom" 
                               placeholder="Digite a nova categoria" style="display: none; margin-top: 8px;">
                    </div>
                </div>
                
                <div class="campo-grupo">
                    <label for="promptDescricao">Descrição</label>
                    <textarea id="promptDescricao" name="promptDescricao" rows="2" required
                              placeholder="Descreva brevemente a especialidade deste robô..."></textarea>
                </div>
                
                <div class="campo-grupo">
                    <label for="promptEspecializado">Prompt Especializado</label>
                    <textarea id="promptEspecializado" name="promptEspecializado" rows="4" required
                              placeholder="Digite o prompt que define a especialidade deste robô Dr. IA..."></textarea>
                </div>
                
                <div class="campos-grid">
                    <div class="campo-grupo">
                        <label for="promptStatus">Status</label>
                        <select id="promptStatus" name="promptStatus" required>
                            <option value="active">Ativo</option>
                            <option value="draft">Rascunho</option>
                            <option value="inactive">Inativo</option>
                        </select>
                    </div>
                    
                    <div class="campo-grupo">
                        <label for="promptLimiteDiario">Limite Diário</label>
                        <input type="number" id="promptLimiteDiario" name="promptLimiteDiario" min="1" max="1000" 
                               placeholder="Ex: 50 (vazio = ilimitado)">
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn-fisio btn-secundario" onclick="fecharModal('modalPrompt')">
                <i class="fas fa-times"></i>
                Cancelar
            </button>
            <button type="button" class="btn-fisio btn-primario" onclick="salvarRoboPrompt()">
                <i class="fas fa-robot"></i>
                Criar Robô Dr. IA
            </button>
        </div>
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

/* Todos os ícones com fundo azul uniforme */
.prompt-icone-especialidade.marketing,
.prompt-icone-especialidade.atendimento,
.prompt-icone-especialidade.vendas,
.prompt-icone-especialidade.fidelizacao,
.prompt-icone-especialidade.clinica,
.prompt-icone-especialidade.especialidades,
.prompt-icone-especialidade.educacao,
.prompt-icone-especialidade.juridico,
.prompt-icone-especialidade.diagnostico,
.prompt-icone-especialidade.gestao,
.prompt-icone-especialidade.farmacologia,
.prompt-icone-especialidade {
    background: linear-gradient(135deg, #1976d2, #42a5f5) !important;
    color: white !important;
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
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #1976d2, #42a5f5);
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transicao);
    color: white;
    font-size: 16px;
}

.btn-opcao:hover {
    background: linear-gradient(135deg, #1565c0, #1976d2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
}

/* Contador */
.contador-prompts {
    color: var(--cinza-escuro);
    font-weight: 400;
    font-size: 14px;
    margin-left: 8px;
}

/* Visualização em Grid */
.prompts-lista.visualizacao-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 24px;
    padding: 24px;
}

.prompts-lista.visualizacao-grid .prompt-item {
    min-height: 280px;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--cinza-claro);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.prompts-lista.visualizacao-grid .prompt-header {
    margin-bottom: 16px;
}

.prompts-lista.visualizacao-grid .prompt-info-principal {
    flex: 1;
}

.prompts-lista.visualizacao-grid .prompt-nome {
    font-size: 16px;
    margin-bottom: 8px;
}

.prompts-lista.visualizacao-grid .prompt-descricao {
    font-size: 13px;
    line-height: 1.4;
    margin-bottom: 12px;
}

.prompts-lista.visualizacao-grid .prompt-estatisticas {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin: 16px 0;
}

.prompts-lista.visualizacao-grid .stat-prompt-item {
    text-align: center;
    padding: 8px;
    background: var(--cinza-claro);
    border-radius: 8px;
}

.prompts-lista.visualizacao-grid .stat-prompt-numero {
    font-size: 16px;
    font-weight: 700;
}

.prompts-lista.visualizacao-grid .stat-prompt-label {
    font-size: 11px;
}

.prompts-lista.visualizacao-grid .prompt-acoes {
    margin-top: auto;
    display: flex;
    justify-content: center;
    gap: 8px;
    padding-top: 16px;
    border-top: 1px solid var(--cinza-claro);
}

/* Dropdown Export */
.dropdown-export {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: var(--branco-puro);
    min-width: 160px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    border-radius: 8px;
    z-index: 1;
    border: 1px solid var(--cinza-claro);
}

.dropdown-content a {
    color: var(--cinza-escuro);
    padding: 12px 16px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: var(--transicao);
}

.dropdown-content a:first-child {
    border-radius: 8px 8px 0 0;
}

.dropdown-content a:last-child {
    border-radius: 0 0 8px 8px;
}

.dropdown-content a:hover {
    background-color: var(--azul-saude);
    color: white;
}

.dropdown-content.show {
    display: block;
}

/* Modal Robôs Dr. IA */
.modal-container-robos {
    background: var(--branco-puro);
    border-radius: 16px;
    max-width: 800px;
    width: 95%;
    height: auto;
    max-height: 95vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.modal-content-scroll {
    padding: 0 24px;
    overflow: visible;
}

.campos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.categoria-container {
    display: flex;
    flex-direction: column;
}

.icone-selector {
    position: relative;
}

.icone-container {
    display: flex;
    gap: 8px;
    align-items: center;
}

.icone-container input {
    flex: 1;
    background: var(--cinza-claro);
}

.icone-preview {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #1976d2, #42a5f5);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.btn-selecionar-icone {
    width: 44px;
    height: 44px;
    background: var(--azul-saude);
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transicao);
}

.btn-selecionar-icone:hover {
    background: var(--azul-escuro);
}

.icones-grid {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--branco-puro);
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    padding: 16px;
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 8px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.icone-opcao {
    width: 40px;
    height: 40px;
    background: var(--cinza-claro);
    border: 2px solid transparent;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transicao);
    font-size: 16px;
    color: var(--cinza-escuro);
}

.icone-opcao:hover {
    background: var(--azul-saude);
    color: white;
    border-color: var(--azul-saude);
}

.icone-opcao.selected {
    background: var(--azul-saude);
    color: white;
    border-color: var(--azul-escuro);
}

.modal-header {
    padding: 24px 24px 16px 24px;
    border-bottom: 1px solid var(--cinza-claro);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

/* Modal do Robô */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-container-robos {
    background: white;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-content-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 0 24px;
    min-height: 0; /* Importante para flexbox scroll */
}

.modal-footer {
    padding: 16px 24px 24px 24px;
    border-top: 1px solid var(--cinza-claro);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    flex-shrink: 0;
    background: white;
    border-radius: 0 0 20px 20px;
}

.modal-content {
    padding: 20px 0;
}

.campo-grupo {
    margin-bottom: 20px;
}

.campo-grupo label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.campo-grupo input,
.campo-grupo select,
.campo-grupo textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    font-family: inherit;
    background: var(--branco-puro);
}

.campo-grupo input:focus,
.campo-grupo select:focus,
.campo-grupo textarea:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.btn-fechar {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: var(--cinza-claro);
    color: var(--cinza-escuro);
    cursor: pointer;
    transition: var(--transicao);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-fechar:hover {
    background: var(--vermelho-erro);
    color: white;
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

/* Alertas flutuantes */
.alerta-flutuante {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--branco-puro);
    border-radius: 12px;
    box-shadow: var(--sombra-forte);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 10000;
    max-width: 400px;
}

.alerta-flutuante.show {
    transform: translateX(0);
}

.alerta-flutuante.sucesso {
    background: var(--sucesso);
    color: white;
}

.alerta-flutuante.erro {
    background: var(--erro);
    color: white;
}

.alerta-flutuante i {
    font-size: 20px;
}
</style>

<meta name="csrf-token" content="<?= $this->csrfToken() ?>">

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

function filtrarPrompts() {
    const categoria = document.getElementById('filtroCategoria').value;
    const status = document.getElementById('filtroStatus').value;
    const busca = document.getElementById('buscaPrompt').value.toLowerCase();
    
    const items = document.querySelectorAll('.prompt-item');
    let visibleCount = 0;
    let activeCount = 0;
    let totalUsos = 0;
    let somaSuccessRate = 0;
    
    items.forEach(item => {
        const itemCategoria = item.getAttribute('data-category');
        const itemStatus = item.getAttribute('data-status');
        const itemNome = item.querySelector('.prompt-nome').textContent.toLowerCase();
        const itemDesc = item.querySelector('.prompt-descricao').textContent.toLowerCase();
        
        let mostrar = true;
        
        // Filtro por categoria
        if (categoria && itemCategoria !== categoria) {
            mostrar = false;
        }
        
        // Filtro por status
        if (status && itemStatus !== status) {
            mostrar = false;
        }
        
        // Filtro por busca
        if (busca && !itemNome.includes(busca) && !itemDesc.includes(busca)) {
            mostrar = false;
        }
        
        if (mostrar) {
            item.style.display = 'block';
            visibleCount++;
            if (itemStatus === 'active') activeCount++;
            
            // Pegar dados para estatísticas
            const usosElement = item.querySelector('.stat-prompt-item:first-child .stat-prompt-numero');
            const successElement = item.querySelector('.stat-prompt-item:nth-child(2) .stat-prompt-numero');
            
            if (usosElement) totalUsos += parseInt(usosElement.textContent) || 0;
            if (successElement) somaSuccessRate += parseInt(successElement.textContent.replace('%', '')) || 0;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Atualizar estatísticas
    document.getElementById('totalPrompts').textContent = visibleCount;
    document.getElementById('promptsAtivos').textContent = activeCount;
    document.getElementById('totalUsos').textContent = totalUsos.toLocaleString();
    document.getElementById('taxaSucesso').textContent = visibleCount > 0 ? Math.round(somaSuccessRate / visibleCount) + '%' : '0%';
    document.getElementById('contadorPrompts').textContent = `(${visibleCount} robôs Dr. IA)`;
}

function buscarPrompts() {
    filtrarPrompts();
}

// Modal Robô Dr. IA
function abrirModalNovoPrompt() {
    console.log('Abrindo modal novo prompt...');
    
    const modal = document.getElementById('modalPrompt');
    const titulo = document.getElementById('modalPromptTitulo');
    const form = document.getElementById('formPrompt');
    
    if (!modal) {
        console.error('Modal não encontrado!');
        return;
    }
    
    if (titulo) titulo.textContent = '🤖 Criar Novo Robô Dr. IA';
    if (form) form.reset();
    
    const promptId = document.getElementById('promptId');
    if (promptId) promptId.value = '';
    
    const categoriaCustom = document.getElementById('categoriaCustom');
    if (categoriaCustom) categoriaCustom.style.display = 'none';
    
    // Resetar preview do ícone
    const iconePreview = document.getElementById('iconePreview');
    if (iconePreview) {
        iconePreview.innerHTML = '<i class="fas fa-robot"></i>';
    }
    
    // Restaurar botão para "Criar"
    const btnSubmit = document.querySelector('button[onclick="salvarRoboPrompt()"]');
    if (btnSubmit) {
        btnSubmit.innerHTML = '<i class="fas fa-robot"></i> Criar Robô Dr. IA';
        btnSubmit.disabled = false;
    }
    
    // Mostrar modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    console.log('Modal deve estar visível agora!');
}

function toggleCategoriaCustom() {
    const select = document.getElementById('promptCategoria');
    const customInput = document.getElementById('categoriaCustom');
    
    if (select.value === 'custom') {
        customInput.style.display = 'block';
        customInput.required = true;
    } else {
        customInput.style.display = 'none';
        customInput.required = false;
        customInput.value = '';
    }
}

// Ícones disponíveis para seleção
const iconesDisponiveis = [
    // Robôs Dr. IA existentes
    { class: 'fas fa-robot', name: 'Robô Geral' },
    { class: 'fab fa-instagram', name: 'Instagram' },
    { class: 'fab fa-whatsapp', name: 'WhatsApp' },
    { class: 'fas fa-handshake', name: 'Vendas' },
    { class: 'fas fa-dumbbell', name: 'Exercícios' },
    { class: 'fas fa-clipboard-list', name: 'Protocolos' },
    { class: 'fas fa-graduation-cap', name: 'Educação' },
    { class: 'fas fa-microscope', name: 'Pesquisa' },
    { class: 'fas fa-syringe', name: 'Injetáveis' },
    { class: 'fas fa-map-marker-alt', name: 'Local' },
    { class: 'fas fa-undo', name: 'Recall' },
    { class: 'fas fa-chart-line', name: 'Evolução' },
    { class: 'fas fa-gavel', name: 'Jurídico' },
    { class: 'fas fa-file-contract', name: 'Contratos' },
    { class: 'fas fa-camera', name: 'Imagem' },
    { class: 'fas fa-x-ray', name: 'Raio-X' },
    { class: 'fas fa-search-plus', name: 'Diagnóstico' },
    { class: 'fas fa-flask', name: 'Laboratório' },
    { class: 'fas fa-folder-open', name: 'Protocolos' },
    { class: 'fas fa-shield-alt', name: 'Vigilância' },
    { class: 'fas fa-pills', name: 'Medicamentos' },
    { class: 'fas fa-child', name: 'Pilates' },
    { class: 'fas fa-user-check', name: 'Postura' },
    { class: 'fas fa-balance-scale', name: 'Perícias' },
    
    // Ícones médicos e de saúde
    { class: 'fas fa-stethoscope', name: 'Estetoscópio' },
    { class: 'fas fa-heartbeat', name: 'Cardio' },
    { class: 'fas fa-brain', name: 'Neurologia' },
    { class: 'fas fa-bone', name: 'Ortopedia' },
    { class: 'fas fa-lungs', name: 'Respiratório' },
    { class: 'fas fa-baby', name: 'Pediatria' },
    { class: 'fas fa-walking', name: 'Geriatria' },
    { class: 'fas fa-running', name: 'Esporte' },
    { class: 'fas fa-yoga', name: 'Yoga/Pilates' },
    { class: 'fas fa-hands-helping', name: 'Terapia' },
    { class: 'fas fa-hand-sparkles', name: 'Massagem' },
    { class: 'fas fa-magic', name: 'Acupuntura' },
    { class: 'fas fa-lightbulb', name: 'Laser' },
    { class: 'fas fa-wifi', name: 'Ultrassom' },
    { class: 'fas fa-bolt', name: 'Eletroterapia' },
    { class: 'fas fa-water', name: 'Hidroterapia' },
    { class: 'fas fa-hands', name: 'Manual' },
    { class: 'fas fa-spine', name: 'Coluna' },
    { class: 'fas fa-user-injured', name: 'Lesões' },
    { class: 'fas fa-wheelchair', name: 'Mobilidade' },
    { class: 'fas fa-procedures', name: 'Procedimentos' },
    { class: 'fas fa-hospital', name: 'Hospital' },
    { class: 'fas fa-clinic-medical', name: 'Clínica' },
    { class: 'fas fa-ambulance', name: 'Emergência' },
    { class: 'fas fa-first-aid', name: 'Primeiros Socorros' },
    
    // Ícones de negócios e marketing
    { class: 'fas fa-bullhorn', name: 'Marketing' },
    { class: 'fas fa-headset', name: 'Atendimento' },
    { class: 'fas fa-clipboard-check', name: 'Avaliação' },
    { class: 'fas fa-file-medical', name: 'Relatório' },
    { class: 'fas fa-list-alt', name: 'Lista' },
    { class: 'fas fa-calendar-check', name: 'Agendamento' },
    { class: 'fas fa-comments', name: 'Comunicação' },
    { class: 'fas fa-star', name: 'Qualidade' }
];

// Dicionário para sugestão automática
const iconesDictionary = {
    'autoritas': 'fab fa-instagram',
    'acolhe': 'fab fa-whatsapp', 
    'fechador': 'fas fa-handshake',
    'reab': 'fas fa-dumbbell',
    'protoc': 'fas fa-clipboard-list',
    'edu': 'fas fa-graduation-cap',
    'científico': 'fas fa-microscope',
    'injetáveis': 'fas fa-syringe',
    'local': 'fas fa-map-marker-alt',
    'recall': 'fas fa-undo',
    'evolucio': 'fas fa-chart-line',
    'legal': 'fas fa-gavel',
    'contratus': 'fas fa-file-contract',
    'imago': 'fas fa-camera',
    'imaginário': 'fas fa-x-ray',
    'diagnostik': 'fas fa-search-plus',
    'integralis': 'fas fa-flask',
    'pop': 'fas fa-folder-open',
    'vigilantis': 'fas fa-shield-alt',
    'fórmula': 'fas fa-pills',
    'contrology': 'fas fa-child',
    'posturalis': 'fas fa-user-check',
    'peritus': 'fas fa-balance-scale',
    'cardio': 'fas fa-heartbeat',
    'neuro': 'fas fa-brain',
    'ortopedia': 'fas fa-bone',
    'respiratorio': 'fas fa-lungs',
    'pediatria': 'fas fa-baby',
    'geriatria': 'fas fa-walking',
    'esporte': 'fas fa-running',
    'pilates': 'fas fa-yoga',
    'exercicio': 'fas fa-dumbbell',
    'terapia': 'fas fa-hands-helping',
    'massagem': 'fas fa-hand-sparkles',
    'marketing': 'fas fa-bullhorn',
    'atendimento': 'fas fa-headset',
    'diagnostico': 'fas fa-stethoscope'
};

function abrirSeletorIcone() {
    console.log('Abrindo seletor de ícones...');
    const grid = document.getElementById('iconesGrid');
    
    if (!grid) {
        console.error('Grid de ícones não encontrado!');
        return;
    }
    
    if (grid.style.display === 'none' || !grid.style.display) {
        console.log('Carregando ícones...');
        carregarIcones();
        grid.style.display = 'grid';
        console.log('Grid exibido');
    } else {
        grid.style.display = 'none';
        console.log('Grid ocultado');
    }
}

function carregarIcones() {
    console.log('Função carregarIcones chamada');
    const grid = document.getElementById('iconesGrid');
    const currentIcon = document.getElementById('promptIcone').value;
    
    if (!grid) {
        console.error('Grid não encontrado em carregarIcones');
        return;
    }
    
    console.log('Limpando grid e carregando', iconesDisponiveis.length, 'ícones');
    grid.innerHTML = '';
    
    iconesDisponiveis.forEach((icone, index) => {
        console.log(`Criando ícone ${index}: ${icone.class}`);
        const div = document.createElement('div');
        div.className = 'icone-opcao';
        div.innerHTML = `<i class="${icone.class}"></i>`;
        div.title = icone.name;
        
        if (icone.class === currentIcon) {
            div.classList.add('selected');
        }
        
        div.onclick = function() {
            console.log('Ícone clicado:', icone.class);
            selecionarIcone(icone.class);
        };
        
        grid.appendChild(div);
    });
    
    console.log('Total de ícones carregados:', grid.children.length);
}

function selecionarIcone(iconeClass) {
    console.log('Selecionando ícone:', iconeClass);
    const iconeInput = document.getElementById('promptIcone');
    const iconePreview = document.getElementById('iconePreview');
    const grid = document.getElementById('iconesGrid');
    
    if (!iconeInput || !iconePreview) {
        console.error('Elementos não encontrados em selecionarIcone');
        return;
    }
    
    iconeInput.value = iconeClass;
    iconePreview.innerHTML = `<i class="${iconeClass}"></i>`;
    
    if (grid) {
        grid.style.display = 'none';
        
        // Atualizar seleção visual
        const opcoes = grid.querySelectorAll('.icone-opcao');
        opcoes.forEach(opcao => opcao.classList.remove('selected'));
        
        const opcaoSelecionada = Array.from(opcoes).find(opcao => 
            opcao.innerHTML === `<i class="${iconeClass}"></i>`
        );
        if (opcaoSelecionada) {
            opcaoSelecionada.classList.add('selected');
        }
    }
    
    console.log('Ícone selecionado com sucesso');
}

function sugerirIcone() {
    const nome = document.getElementById('promptNome').value.toLowerCase();
    const iconeInput = document.getElementById('promptIcone');
    const iconePreview = document.getElementById('iconePreview');
    
    if (!nome) {
        selecionarIcone('fas fa-robot');
        return;
    }
    
    // Procurar por palavras-chave no nome
    for (const [palavra, icone] of Object.entries(iconesDictionary)) {
        if (nome.includes(palavra)) {
            selecionarIcone(icone);
            return;
        }
    }
    
    // Se não encontrou, manter robô
    selecionarIcone('fas fa-robot');
}

// Fechar dropdown quando clicar fora
document.addEventListener('click', function(event) {
    const seletor = document.querySelector('.icone-selector');
    const grid = document.getElementById('iconesGrid');
    
    if (seletor && !seletor.contains(event.target)) {
        grid.style.display = 'none';
    }
});

// Adicionar eventos
document.addEventListener('DOMContentLoaded', function() {
    const nomeInput = document.getElementById('promptNome');
    
    if (nomeInput) {
        nomeInput.addEventListener('input', sugerirIcone);
    }
    
    // Inicializar ícone padrão
    setTimeout(function() {
        if (document.getElementById('promptIcone')) {
            selecionarIcone('fas fa-robot');
        }
    }, 100);
});

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Ações dos prompts
async function editarPrompt(id) {
    console.log('Editando prompt com ID:', id);
    
    try {
        // Buscar dados reais do robô do servidor
        const response = await fetch('/admin/ai/edit-robot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                action: 'get',
                id: id,
                csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
            })
        });
        
        if (!response.ok) {
            throw new Error('Erro ao buscar dados do robô');
        }
        
        const robotData = await response.json();
        
        if (!robotData.success) {
            throw new Error(robotData.message || 'Erro ao carregar dados do robô');
        }
        
        const robot = robotData.robot;
        
        // Verificar se o modal existe
        const modal = document.getElementById('modalPrompt');
        const modalTitle = document.getElementById('modalPromptTitulo');
        
        if (!modal || !modalTitle) {
            alert('Erro: Modal não encontrado na página.');
            return;
        }
        
        // Atualizar título do modal
        modalTitle.textContent = `✏️ Editar ${robot.robot_name}`;
        
        // Preencher campos do formulário com dados reais
        const promptIdField = document.getElementById('promptId');
        const nomeRoboField = document.getElementById('promptNome');
        const categoriaRoboField = document.getElementById('promptCategoria');
        const descricaoRoboField = document.getElementById('promptDescricao');
        const statusRoboField = document.getElementById('promptStatus');
        const iconeRoboField = document.getElementById('promptIcone');
        const promptEspecializadoField = document.getElementById('promptEspecializado');
        const limiteDiarioField = document.getElementById('promptLimiteDiario');
        
        if (promptIdField) promptIdField.value = robot.id;
        if (nomeRoboField) nomeRoboField.value = robot.robot_name || '';
        if (categoriaRoboField) categoriaRoboField.value = robot.robot_category || '';
        if (descricaoRoboField) descricaoRoboField.value = robot.robot_description || '';
        if (statusRoboField) statusRoboField.value = robot.is_active ? 'active' : 'inactive';
        if (promptEspecializadoField) promptEspecializadoField.value = robot.robot_specialty || '';
        if (limiteDiarioField) limiteDiarioField.value = robot.daily_limit || '100';
        
        // Atualizar ícone 
        if (iconeRoboField) {
            iconeRoboField.value = robot.robot_icon || 'fas fa-robot';
            // Atualizar preview do ícone
            const iconePreview = document.getElementById('iconePreview');
            if (iconePreview) {
                iconePreview.innerHTML = `<i class="${robot.robot_icon || 'fas fa-robot'}"></i>`;
            }
        }
        
        // Atualizar botão para "Salvar Robô Dr. IA"
        const btnSubmit = document.querySelector('button[onclick="salvarRoboPrompt()"]');
        if (btnSubmit) {
            btnSubmit.innerHTML = '<i class="fas fa-save"></i> Salvar Robô Dr. IA';
        }
        
        // Mostrar modal
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
    } catch (error) {
        console.error('Erro ao carregar robô:', error);
        mostrarAlerta('Erro ao carregar dados do robô: ' + error.message, 'erro');
    }
}

function testarPrompt(id) {
    console.log('Testando robô ID:', id);
    
    // Fazer requisição AJAX para testar o robô
    fetch(`/admin/ai/test-robot?robot_id=${id}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            
            // Se há redirect, navegar para a página
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 800);
            }
        } else {
            mostrarAlerta(data.message || 'Erro ao testar robô', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão ao testar robô', 'erro');
    });
}

function getRobotNameById(id) {
    const robotNames = {
        1: 'Dr. Autoritas',
        2: 'Dr. Acolhe',
        3: 'Dr. Fechador',
        4: 'Dr. Reab',
        5: 'Dra. Protoc',
        6: 'Dra. Edu',
        7: 'Dr. Científico',
        8: 'Dr. Injetáveis',
        9: 'Dr. Local',
        10: 'Dr. Recall',
        11: 'Dr. Evolucio',
        12: 'Dra. Legal',
        13: 'Dr. Contratus',
        14: 'Dr. Imago',
        15: 'Dr. Imaginário',
        16: 'Dr. Diagnostik',
        17: 'Dr. Integralis',
        18: 'Dr. POP',
        19: 'Dr. Vigilantis',
        20: 'Dr. Fórmula Oral',
        21: 'Dra. Contrology',
        22: 'Dr. Posturalis',
        23: 'Dr. Peritus'
    };
    return robotNames[id] || 'Robô Dr. IA';
}

function duplicarPrompt(id) {
    if (confirm('Deseja duplicar este robô Dr. IA?')) {
        console.log('Duplicando robô ID:', id);
        
        // Preparar dados para envio
        const formData = new FormData();
        formData.append('robot_id', id);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]')?.value || '');
        
        // Fazer requisição AJAX para duplicar o robô
        fetch('/admin/ai/duplicate-robot', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'sucesso');
                
                // Recarregar a página para mostrar o robô duplicado
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } else {
                mostrarAlerta(data.message || 'Erro ao duplicar robô', 'erro');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro de conexão ao duplicar robô', 'erro');
        });
    }
}

function alterarStatusPrompt(id, status) {
    const novoStatus = status === 'active' ? 'inactive' : 'active';
    const acao = novoStatus === 'active' ? 'ativar' : 'desativar';
    
    if (confirm(`Confirma ${acao} este robô Dr. IA?`)) {
        console.log('Alterando status do robô ID:', id, 'para:', novoStatus);
        
        // Preparar dados para envio
        const formData = new FormData();
        formData.append('robot_id', id);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]')?.value || '');
        
        // Fazer requisição AJAX
        fetch('/admin/ai/toggle-robot-status', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'sucesso');
                
                // Recarregar a página para mostrar as mudanças
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } else {
                mostrarAlerta(data.message || 'Erro ao alterar status do robô', 'erro');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro de conexão ao alterar status', 'erro');
        });
    }
}

function confirmarExclusaoPrompt(id, nome) {
    if (confirm(`ATENÇÃO: Deseja excluir permanentemente o robô "${nome}"?\n\nEsta ação não pode ser desfeita!`)) {
        console.log('Deletando robô ID:', id);
        
        // Preparar dados para envio
        const formData = new FormData();
        formData.append('robot_id', id);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]')?.value || '');
        
        // Fazer requisição AJAX para deletar o robô
        fetch('/admin/ai/delete-robot', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'sucesso');
                
                // Recarregar a página para mostrar as mudanças
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } else {
                mostrarAlerta(data.message || 'Erro ao deletar robô', 'erro');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro de conexão ao deletar robô', 'erro');
        });
    }
}

function importarPrompts() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.json,.csv';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    let data;
                    if (file.name.endsWith('.json')) {
                        data = JSON.parse(e.target.result);
                    } else {
                        // Processar CSV básico
                        const lines = e.target.result.split('\n');
                        data = lines.slice(1).filter(line => line.trim()).map((line, index) => {
                            const cols = line.split(',');
                            return {
                                id: Date.now() + index,
                                name: cols[1] || 'Robô Importado',
                                category: cols[2] || 'geral',
                                description: cols[3] || 'Descrição importada',
                                status: 'draft',
                                usage_count: 0,
                                success_rate: 0,
                                icon: 'fas fa-robot'
                            };
                        });
                    }
                    
                    mostrarAlerta(`${data.length} robôs Dr. IA importados com sucesso! (Funcionalidade em desenvolvimento)`, 'sucesso');
                } catch (error) {
                    mostrarAlerta('Erro ao processar arquivo. Verifique o formato.', 'erro');
                }
            };
            reader.readAsText(file);
        }
    };
    input.click();
}

function toggleDropdownExport() {
    document.getElementById("dropdownExport").classList.toggle("show");
}

// Fechar dropdown quando clicar fora
window.onclick = function(event) {
    if (!event.target.matches('.btn-opcao')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

function exportarCSV() {
    const prompts = <?= json_encode($promptsData) ?>;
    
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "ID,Nome,Categoria,Descrição,Status,Usos,Taxa de Sucesso\n";
    
    prompts.forEach(prompt => {
        csvContent += `${prompt.id},"${prompt.name}","${prompt.category}","${prompt.description}","${prompt.status}",${prompt.usage_count},${prompt.success_rate}%\n`;
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "robos_dr_ia_" + new Date().toISOString().slice(0,10) + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    document.getElementById("dropdownExport").classList.remove("show");
    mostrarAlerta('Relatório CSV dos 23 Robôs Dr. IA exportado!', 'sucesso');
}

function exportarExcel() {
    const prompts = <?= json_encode($promptsData) ?>;
    
    let excelContent = `
    <table>
        <tr>
            <th>ID</th><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Status</th><th>Usos</th><th>Taxa de Sucesso</th>
        </tr>`;
    
    prompts.forEach(prompt => {
        excelContent += `
        <tr>
            <td>${prompt.id}</td>
            <td>${prompt.name}</td>
            <td>${prompt.category}</td>
            <td>${prompt.description}</td>
            <td>${prompt.status}</td>
            <td>${prompt.usage_count}</td>
            <td>${prompt.success_rate}%</td>
        </tr>`;
    });
    
    excelContent += `</table>`;
    
    const blob = new Blob([excelContent], { type: 'application/vnd.ms-excel' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "robos_dr_ia_" + new Date().toISOString().slice(0,10) + ".xls");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    document.getElementById("dropdownExport").classList.remove("show");
    mostrarAlerta('Relatório Excel dos 23 Robôs Dr. IA exportado!', 'sucesso');
}

function exportarPDF() {
    const prompts = <?= json_encode($promptsData) ?>;
    
    // Criar conteúdo HTML para PDF
    let pdfContent = `
    <html>
    <head>
        <title>Relatório dos 23 Robôs Dr. IA</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #1976d2; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #1976d2; color: white; }
            tr:nth-child(even) { background-color: #f2f2f2; }
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
            z-index: 10000;
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
    </head>
    <body>
        <h1>Relatório dos 23 Robôs Dr. IA</h1>
        <p>Data: ${new Date().toLocaleDateString('pt-BR')}</p>
        <table>
            <tr>
                <th>ID</th><th>Nome</th><th>Categoria</th><th>Descrição</th><th>Status</th><th>Usos</th><th>Taxa de Sucesso</th>
            </tr>`;
    
    prompts.forEach(prompt => {
        pdfContent += `
        <tr>
            <td>${prompt.id}</td>
            <td>${prompt.name}</td>
            <td>${prompt.category}</td>
            <td>${prompt.description}</td>
            <td>${prompt.status}</td>
            <td>${prompt.usage_count}</td>
            <td>${prompt.success_rate}%</td>
        </tr>`;
    });
    
    pdfContent += `
        </table>
    </body>
    </html>`;
    
    // Abrir em nova janela para impressão como PDF
    const printWindow = window.open('', '_blank');
    printWindow.document.write(pdfContent);
    printWindow.document.close();
    printWindow.print();
    
    document.getElementById("dropdownExport").classList.remove("show");
    mostrarAlerta('Relatório PDF dos 23 Robôs Dr. IA gerado! Use Ctrl+P para salvar como PDF.', 'info');
}

function alternarVisualizacao() {
    const lista = document.getElementById('promptsLista');
    const icone = document.getElementById('iconeVisualizacao');
    
    if (lista.classList.contains('visualizacao-grid')) {
        // Mudar para lista
        lista.classList.remove('visualizacao-grid');
        icone.className = 'fas fa-th-large';
    } else {
        // Mudar para grid
        lista.classList.add('visualizacao-grid');
        icone.className = 'fas fa-list';
    }
}

function abrirModalConfiguracoes() {
    // Redirecionar para página completa de configurações
    window.location.href = '/ai/configuracoes';
}

function testarPromptAtual() {
    mostrarAlerta('Teste do prompt será implementado', 'info');
}

// Sliders
const temperaturaSlider = document.getElementById('temperatura');
const topPSlider = document.getElementById('topP');

if (temperaturaSlider) {
    temperaturaSlider.addEventListener('input', function() {
        const temperaturaValor = document.getElementById('temperaturaValor');
        if (temperaturaValor) {
            temperaturaValor.textContent = this.value;
        }
    });
}

if (topPSlider) {
    topPSlider.addEventListener('input', function() {
        const topPValor = document.getElementById('topPValor');
        if (topPValor) {
            topPValor.textContent = this.value;
        }
    });
}

// Função principal para salvar robô
// Função salvarRoboPrompt removida - usando a versão async mais abaixo

// Submissão do formulário (backup)
document.addEventListener('DOMContentLoaded', function() {
    const formPrompt = document.getElementById('formPrompt');
    if (formPrompt) {
        formPrompt.addEventListener('submit', function(e) {
            console.log('Formulário interceptado via submit!');
            e.preventDefault();
            e.stopPropagation();
            salvarRoboPrompt();
        });
    }
});

// Função para criar card de robô na interface
function criarCardRobo(dados) {
    const card = document.createElement('div');
    card.className = 'prompt-item';
    card.setAttribute('data-prompt-id', dados.id);
    card.setAttribute('data-status', dados.status);
    card.setAttribute('data-category', dados.category);
    
    card.innerHTML = `
        <div class="prompt-header">
            <div class="prompt-icone-especialidade ${dados.category}">
                <i class="prompt-icone ${dados.icon || 'fas fa-robot'}"></i>
            </div>
            <div class="prompt-info-principal">
                <div class="prompt-nome">${dados.name}</div>
                <div class="prompt-descricao">${dados.description}</div>
                <div class="prompt-meta">
                    <span class="prompt-categoria">${dados.category.charAt(0).toUpperCase() + dados.category.slice(1)}</span>
                    <span class="prompt-status status-${dados.status}">
                        ${dados.status === 'active' ? 'Ativo' : (dados.status === 'draft' ? 'Rascunho' : 'Inativo')}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="prompt-estatisticas">
            <div class="stat-prompt-item">
                <div class="stat-prompt-numero">${dados.usage_count || 0}</div>
                <div class="stat-prompt-label">Usos</div>
            </div>
            <div class="stat-prompt-item">
                <div class="stat-prompt-numero">${dados.success_rate || 0}%</div>
                <div class="stat-prompt-label">Sucesso</div>
            </div>
            <div class="stat-prompt-item">
                <div class="stat-prompt-numero">${dados.limite_diario || 100}</div>
                <div class="stat-prompt-label">Limite Diário</div>
            </div>
        </div>
        
        <div class="prompt-acoes">
            <button class="btn-acao editar" onclick="editarPrompt(${dados.id})" data-tooltip="Editar prompt">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn-acao testar" onclick="testarPrompt(${dados.id})" data-tooltip="Testar prompt">
                <i class="fas fa-play"></i>
            </button>
            <button class="btn-acao duplicar" onclick="duplicarPrompt(${dados.id})" data-tooltip="Duplicar prompt">
                <i class="fas fa-copy"></i>
            </button>
            <button class="btn-acao ${dados.status === 'active' ? 'pausar' : 'ativar'}" 
                    onclick="alterarStatusPrompt(${dados.id}, '${dados.status}')" 
                    data-tooltip="${dados.status === 'active' ? 'Desativar' : 'Ativar'}">
                <i class="fas fa-${dados.status === 'active' ? 'pause' : 'play'}"></i>
            </button>
            <button class="btn-acao excluir" onclick="confirmarExclusaoPrompt(${dados.id}, '${dados.name}')" data-tooltip="Excluir prompt">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    return card;
}

// Função para salvar robô/prompt
async function salvarRoboPrompt() {
    const form = document.getElementById('formPrompt');
    if (!form) {
        console.error('Formulário não encontrado');
        return;
    }
    
    const formData = new FormData(form);
    
    // Adicionar CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    formData.append('csrf_token', csrfToken);
    
    // Pegar os valores do formulário
    const promptId = document.getElementById('promptId')?.value || '';
    const isEdit = promptId !== '';
    
    // Preparar dados para envio - IMPORTANTE: usar os nomes corretos esperados pelo PHP
    const data = {
        promptId: promptId,  // PHP espera 'promptId'
        promptNome: document.getElementById('promptNome')?.value || '',
        promptIcone: document.getElementById('promptIcone')?.value || 'fas fa-robot',
        promptCategoria: document.getElementById('promptCategoria')?.value || '',
        promptDescricao: document.getElementById('promptDescricao')?.value || '',
        promptEspecializado: document.getElementById('promptEspecializado')?.value || '',
        promptStatus: document.getElementById('promptStatus')?.value || 'active',
        promptLimiteDiario: document.getElementById('promptLimiteDiario')?.value || '100',
        csrf_token: csrfToken
    };
    
    // Mostrar loading
    const btnSubmit = form.querySelector('.btn-primario');
    const originalText = btnSubmit?.innerHTML || '';
    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    }
    
    try {
        const url = isEdit ? '/admin/ai/edit-robot' : '/admin/ai/create-robot';
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Mostrar mensagem de sucesso
            mostrarAlerta(result.message || 'Robô salvo com sucesso!', 'sucesso');
            
            // Fechar modal
            fecharModal('modalPrompt');
            
            // Recarregar lista de robôs
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Mostrar erro
            mostrarAlerta(result.error || 'Erro ao salvar robô', 'erro');
        }
    } catch (error) {
        console.error('Erro ao salvar:', error);
        mostrarAlerta('Erro ao salvar robô. Tente novamente.', 'erro');
    } finally {
        // Restaurar botão
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }
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

// Função para limpar formulário
function limparFormularioPrompt() {
    document.getElementById('promptId').value = '';
    document.getElementById('promptNome').value = '';
    document.getElementById('promptIcone').value = '';
    document.getElementById('promptCategoria').value = '';
    document.getElementById('promptDescricao').value = '';
    document.getElementById('promptEspecializado').value = '';
    document.getElementById('promptStatus').value = 'active';
    document.getElementById('promptLimiteDiario').value = '100';
    
    // Resetar título do modal
    document.getElementById('modalPromptTitulo').textContent = '🤖 Criar Novo Robô Dr. IA';
    
    // Resetar botão de submit
    const btnSubmit = document.querySelector('#modalPrompt .btn-primario');
    if (btnSubmit) {
        btnSubmit.innerHTML = '<i class="fas fa-robot"></i> Criar Robô Dr. IA';
    }
}

// Fechar modal clicando fora
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        fecharModal(e.target.id);
    }
});

// Garantir que as funções estejam disponíveis globalmente
window.abrirModalNovoPrompt = abrirModalNovoPrompt;
window.editarPrompt = editarPrompt;
window.testarPrompt = testarPrompt;
window.duplicarPrompt = duplicarPrompt;
window.alterarStatusPrompt = alterarStatusPrompt;
window.confirmarExclusaoPrompt = confirmarExclusaoPrompt;
window.salvarRoboPrompt = salvarRoboPrompt;
window.fecharModal = fecharModal;
window.limparFormularioPrompt = limparFormularioPrompt;
window.toggleCategoriaCustom = toggleCategoriaCustom;
window.abrirSeletorIcone = abrirSeletorIcone;
window.selecionarIcone = selecionarIcone;
window.sugerirIcone = sugerirIcone;
</script>