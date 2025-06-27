<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">🤖 Dr. IA - Assistentes Especializados</h1>
<p class="subtitulo-pagina-escuro">23 robôs especialistas em fisioterapia, gestão clínica e marketing para impulsionar sua prática profissional</p>

<!-- Estatísticas do Usuário -->
<div class="user-stats-ai">
    <div class="stat-ai-card">
        <div class="stat-ai-icon">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-ai-info">
            <div class="stat-ai-numero"><?= $userStats['total_requests'] ?? 0 ?></div>
            <div class="stat-ai-label">Total de Análises</div>
        </div>
    </div>
    
    <div class="stat-ai-card">
        <div class="stat-ai-icon hoje">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-ai-info">
            <div class="stat-ai-numero"><?= $userStats['requests_today'] ?? 0 ?></div>
            <div class="stat-ai-label">Análises Hoje</div>
        </div>
    </div>
    
    <div class="stat-ai-card">
        <div class="stat-ai-icon favorito">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-ai-info">
            <div class="stat-ai-numero"><?= $userStats['favorite_module'] ?? 'Nenhum' ?></div>
            <div class="stat-ai-label">Módulo Favorito</div>
        </div>
    </div>
    
    <div class="stat-ai-card">
        <div class="stat-ai-icon sucesso">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-ai-info">
            <div class="stat-ai-numero"><?= $userStats['success_rate'] ?? 100 ?>%</div>
            <div class="stat-ai-label">Taxa de Sucesso</div>
        </div>
    </div>
</div>

<!-- Grid Principal -->
<div class="ai-interface-grid">
    <!-- Coluna Esquerda - Seleção de Especialidade -->
    <div class="coluna-especialidades">
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-robot"></i>
                    <span>Escolha seu Dr. IA</span>
                </div>
            </div>
            
            <div class="lista-especialidades">
                <?php
                // 23 Robôs Dr. IA
                $robotsIA = [
                    ['name' => 'Dr. Autoritas', 'description' => 'Conteúdo para Instagram', 'icon' => 'fa-instagram', 'slug' => 'dr_autoritas'],
                    ['name' => 'Dr. Acolhe', 'description' => 'Atendimento via WhatsApp/Direct', 'icon' => 'fa-whatsapp', 'slug' => 'dr_acolhe'],
                    ['name' => 'Dr. Fechador', 'description' => 'Vendas de Planos Fisioterapêuticos', 'icon' => 'fa-handshake', 'slug' => 'dr_fechador'],
                    ['name' => 'Dr. Reab', 'description' => 'Prescrição de Exercícios Personalizados', 'icon' => 'fa-dumbbell', 'slug' => 'dr_reab'],
                    ['name' => 'Dra. Protoc', 'description' => 'Protocolos Terapêuticos Estruturados', 'icon' => 'fa-clipboard-list', 'slug' => 'dra_protoc'],
                    ['name' => 'Dra. Edu', 'description' => 'Materiais Educativos para Pacientes', 'icon' => 'fa-graduation-cap', 'slug' => 'dra_edu'],
                    ['name' => 'Dr. Científico', 'description' => 'Resumos de Artigos e Evidências', 'icon' => 'fa-microscope', 'slug' => 'dr_cientifico'],
                    ['name' => 'Dr. Injetáveis', 'description' => 'Protocolos Terapêuticos com Injetáveis', 'icon' => 'fa-syringe', 'slug' => 'dr_injetaveis'],
                    ['name' => 'Dr. Local', 'description' => 'Autoridade de Bairro', 'icon' => 'fa-map-marker-alt', 'slug' => 'dr_local'],
                    ['name' => 'Dr. Recall', 'description' => 'Fidelização e Retorno de Pacientes', 'icon' => 'fa-undo', 'slug' => 'dr_recall'],
                    ['name' => 'Dr. Evolucio', 'description' => 'Acompanhamento Clínico do Paciente', 'icon' => 'fa-chart-line', 'slug' => 'dr_evolucio'],
                    ['name' => 'Dra. Legal', 'description' => 'Termos de Consentimento Personalizados', 'icon' => 'fa-gavel', 'slug' => 'dra_legal'],
                    ['name' => 'Dr. Contratus', 'description' => 'Contratos de Prestação de Serviço', 'icon' => 'fa-file-contract', 'slug' => 'dr_contratus'],
                    ['name' => 'Dr. Imago', 'description' => 'Autorização de Uso de Imagem', 'icon' => 'fa-camera', 'slug' => 'dr_imago'],
                    ['name' => 'Dr. Imaginário', 'description' => 'Análise de Exames de Imagem (RX, USG, RNM)', 'icon' => 'fa-x-ray', 'slug' => 'dr_imaginario'],
                    ['name' => 'Dr. Diagnostik', 'description' => 'Mapeamento de Marcadores para Fisioterapia', 'icon' => 'fa-search-plus', 'slug' => 'dr_diagnostik'],
                    ['name' => 'Dr. Integralis', 'description' => 'Análise Funcional de Exames Laboratoriais', 'icon' => 'fa-flask', 'slug' => 'dr_integralis'],
                    ['name' => 'Dr. POP', 'description' => 'Protocolos Operacionais Padrão (para pasta sanitária)', 'icon' => 'fa-folder-open', 'slug' => 'dr_pop'],
                    ['name' => 'Dr. Vigilantis', 'description' => 'Documentação e Exigências da Vigilância Sanitária', 'icon' => 'fa-shield-alt', 'slug' => 'dr_vigilantis'],
                    ['name' => 'Dr. Fórmula Oral', 'description' => 'Propostas Farmacológicas Via Oral para Dor', 'icon' => 'fa-pills', 'slug' => 'dr_formula_oral'],
                    ['name' => 'Dra. Contrology', 'description' => 'Especialista em prescrição de Pilates clássico terapêutico', 'icon' => 'fa-yoga', 'slug' => 'dra_contrology'],
                    ['name' => 'Dr. Posturalis', 'description' => 'Especialista em RPG de Souchard e análise postural', 'icon' => 'fa-user-check', 'slug' => 'dr_posturalis'],
                    ['name' => 'Dr. Peritus', 'description' => 'Mestre das Perícias', 'icon' => 'fa-balance-scale', 'slug' => 'dr_peritus']
                ];
                ?>
                
                <?php foreach ($robotsIA as $robot): ?>
                    <button class="btn-especialidade" 
                            data-robot-slug="<?= $robot['slug'] ?>"
                            data-robot-name="<?= htmlspecialchars($robot['name']) ?>"
                            onclick="selecionarRobotIA(this)">
                        <div class="especialidade-icone">
                            <i class="fas <?= $robot['icon'] ?>"></i>
                        </div>
                        <div class="especialidade-info">
                            <h4><?= htmlspecialchars($robot['name']) ?></h4>
                            <p><?= htmlspecialchars($robot['description']) ?></p>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Dicas de Uso -->
        <div class="card-fisio card-dicas">
            <div class="dicas-header">
                <i class="fas fa-lightbulb"></i>
                <h4>Dicas para Melhor Resultado</h4>
            </div>
            <ul class="lista-dicas">
                <li>Forneça informações completas do paciente</li>
                <li>Inclua dados de exames relevantes</li>
                <li>Descreva as limitações funcionais</li>
                <li>Mencione tratamentos anteriores</li>
                <li>Especifique objetivos terapêuticos</li>
            </ul>
        </div>
    </div>

    <!-- Coluna Central - Formulário -->
    <div class="coluna-formulario">
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-user-injured"></i>
                    <span>Dados do Paciente</span>
                </div>
                <div id="especialidadeSelecionada" class="especialidade-badge" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <span></span>
                </div>
            </div>
            
            <form id="formFisioterapia" class="form-fisio">
                <input type="hidden" id="promptId" name="prompt_id" value="">
                
                <!-- Dados Básicos -->
                <div class="form-secao">
                    <h4 class="secao-titulo">
                        <i class="fas fa-id-card"></i>
                        Identificação
                    </h4>
                    
                    <div class="form-grid">
                        <div class="form-grupo">
                            <label for="nome_paciente">Nome do Paciente *</label>
                            <input type="text" 
                                   id="nome_paciente" 
                                   name="nome_paciente" 
                                   placeholder="Ex: João Silva"
                                   required
                                   data-tooltip="Nome completo do paciente">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="idade">Idade *</label>
                            <input type="text" 
                                   id="idade" 
                                   name="idade" 
                                   placeholder="Ex: 45 anos"
                                   required
                                   data-tooltip="Idade do paciente (anos ou meses para bebês)">
                        </div>
                    </div>
                </div>
                
                <!-- Quadro Clínico -->
                <div class="form-secao">
                    <h4 class="secao-titulo">
                        <i class="fas fa-clipboard-list"></i>
                        Quadro Clínico
                    </h4>
                    
                    <div class="form-grupo">
                        <label for="diagnostico">Diagnóstico Clínico/Funcional *</label>
                        <textarea id="diagnostico" 
                                  name="diagnostico" 
                                  rows="3"
                                  placeholder="Ex: Lombalgia crônica com irradiação para membro inferior direito, CID M54.5"
                                  required
                                  data-tooltip="Diagnóstico médico e/ou funcional do paciente"></textarea>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="queixa_principal">Queixa Principal *</label>
                        <textarea id="queixa_principal" 
                                  name="queixa_principal" 
                                  rows="2"
                                  placeholder="Ex: Dor intensa na região lombar ao sentar e levantar"
                                  required
                                  data-tooltip="Principal queixa relatada pelo paciente"></textarea>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="limitacoes">Limitações Funcionais</label>
                        <textarea id="limitacoes" 
                                  name="limitacoes" 
                                  rows="3"
                                  placeholder="Ex: Dificuldade para caminhar distâncias superiores a 100m, impossibilidade de permanecer sentado por mais de 30 minutos"
                                  data-tooltip="Descreva as limitações nas atividades de vida diária"></textarea>
                    </div>
                </div>
                
                <!-- Exames e Avaliações -->
                <div class="form-secao">
                    <h4 class="secao-titulo">
                        <i class="fas fa-x-ray"></i>
                        Exames e Avaliações
                    </h4>
                    
                    <div class="form-grupo">
                        <label for="exames">Exames Complementares</label>
                        <textarea id="exames" 
                                  name="exames" 
                                  rows="3"
                                  placeholder="Ex: RX coluna lombar: redução do espaço L4-L5, RM: protrusão discal L4-L5"
                                  data-tooltip="Resultados de exames de imagem, laboratoriais ou funcionais"></textarea>
                    </div>
                </div>
                
                <!-- Solicitação -->
                <div class="form-secao">
                    <h4 class="secao-titulo">
                        <i class="fas fa-question-circle"></i>
                        Sua Solicitação
                    </h4>
                    
                    <div class="form-grupo">
                        <label for="solicitacao">O que você precisa? *</label>
                        <textarea id="solicitacao" 
                                  name="solicitacao" 
                                  rows="4"
                                  placeholder="Ex: Preciso de um plano de tratamento completo com exercícios específicos para fase aguda da lombalgia"
                                  required
                                  data-tooltip="Descreva o que você espera da análise da IA"></textarea>
                    </div>
                    
                    <div class="opcoes-rapidas">
                        <span class="opcao-label">Sugestões rápidas:</span>
                        <button type="button" class="btn-opcao" onclick="preencherSolicitacao('Plano de tratamento completo')">
                            Plano Completo
                        </button>
                        <button type="button" class="btn-opcao" onclick="preencherSolicitacao('Exercícios terapêuticos específicos')">
                            Exercícios
                        </button>
                        <button type="button" class="btn-opcao" onclick="preencherSolicitacao('Avaliação funcional detalhada')">
                            Avaliação
                        </button>
                        <button type="button" class="btn-opcao" onclick="preencherSolicitacao('Orientações para o paciente')">
                            Orientações
                        </button>
                    </div>
                </div>
                
                <!-- Botões de Ação -->
                <div class="form-acoes">
                    <button type="button" class="btn-fisio btn-secundario" onclick="limparFormulario()">
                        <i class="fas fa-eraser"></i>
                        Limpar
                    </button>
                    <button type="submit" class="btn-fisio btn-primario" id="btnAnalisar">
                        <i class="fas fa-brain"></i>
                        Analisar com IA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Coluna Direita - Resultado -->
    <div class="coluna-resultado">
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-file-medical"></i>
                    <span>Análise da IA</span>
                </div>
                <div class="acoes-resultado" id="acoesResultado" style="display: none;">
                    <button class="btn-acao" onclick="copiarResultado()" data-tooltip="Copiar análise">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn-acao" onclick="baixarPDF()" data-tooltip="Baixar como PDF">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                    <button class="btn-acao" onclick="imprimirResultado()" data-tooltip="Imprimir">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
            
            <div id="resultadoContainer" class="resultado-container">
                <div class="resultado-vazio">
                    <i class="fas fa-robot"></i>
                    <h3>Aguardando Análise</h3>
                    <p>Preencha os dados do paciente e clique em "Analisar com IA" para receber a avaliação fisioterapêutica especializada.</p>
                </div>
            </div>
            
            <div id="loadingAnalise" class="loading-analise" style="display: none;">
                <div class="loading-spinner"></div>
                <p>Analisando dados do paciente...</p>
                <small>Isso pode levar alguns segundos</small>
            </div>
        </div>
        
        <!-- Feedback da Análise -->
        <div class="card-fisio card-feedback" id="cardFeedback" style="display: none;">
            <h4>Esta análise foi útil?</h4>
            <div class="feedback-botoes">
                <button class="btn-feedback positivo" onclick="enviarFeedback('positivo')">
                    <i class="fas fa-thumbs-up"></i>
                    Sim
                </button>
                <button class="btn-feedback negativo" onclick="enviarFeedback('negativo')">
                    <i class="fas fa-thumbs-down"></i>
                    Não
                </button>
                <button class="btn-feedback neutro" onclick="mostrarFormFeedback()">
                    <i class="fas fa-comment"></i>
                    Comentar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estatísticas do Usuário */
.user-stats-ai {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-ai-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-medio);
    transition: var(--transicao);
}

.stat-ai-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--sombra-forte);
}

.stat-ai-icon {
    width: 48px;
    height: 48px;
    background: var(--gradiente-principal);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-ai-icon.hoje {
    background: linear-gradient(135deg, #059669, #10b981);
}

.stat-ai-icon.favorito {
    background: linear-gradient(135deg, #ca8a04, #eab308);
}

.stat-ai-icon.sucesso {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.stat-ai-numero {
    font-size: 24px;
    font-weight: 800;
    color: var(--cinza-escuro);
    font-family: 'JetBrains Mono', monospace;
    line-height: 1;
}

.stat-ai-label {
    font-size: 14px;
    color: var(--cinza-escuro);
    font-weight: 600;
    margin-top: 4px;
}

/* Grid Principal */
.ai-interface-grid {
    display: grid;
    grid-template-columns: 320px 1fr 480px;
    gap: 24px;
    margin-top: 24px;
}

@media (max-width: 1400px) {
    .ai-interface-grid {
        grid-template-columns: 1fr 480px;
    }
    
    .coluna-especialidades {
        grid-column: 1 / -1;
    }
}

@media (max-width: 1024px) {
    .ai-interface-grid {
        grid-template-columns: 1fr;
    }
}

/* Especialidades */
.lista-especialidades {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-especialidade {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--cinza-claro);
    border: 2px solid transparent;
    border-radius: 12px;
    text-align: left;
    cursor: pointer;
    transition: var(--transicao);
}

.btn-especialidade:hover {
    background: white;
    border-color: var(--azul-saude);
    transform: translateX(4px);
}

.btn-especialidade.ativo {
    background: var(--azul-saude);
    border-color: var(--azul-saude);
    color: white;
}

.btn-especialidade.ativo .especialidade-info h4,
.btn-especialidade.ativo .especialidade-info p {
    color: white;
}

.especialidade-icone {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--azul-saude);
}

.btn-especialidade.ativo .especialidade-icone {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.especialidade-info h4 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 4px;
    color: var(--cinza-escuro);
}

.especialidade-info p {
    font-size: 12px;
    color: var(--cinza-escuro);
    line-height: 1.4;
}

/* Card Dicas */
.card-dicas {
    margin-top: 20px;
    background: var(--lilas-cuidado);
    color: white;
}

.dicas-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.dicas-header i {
    font-size: 24px;
}

.dicas-header h4 {
    font-size: 16px;
    font-weight: 700;
}

.lista-dicas {
    list-style: none;
    padding-left: 0;
}

.lista-dicas li {
    position: relative;
    padding-left: 24px;
    margin-bottom: 8px;
    font-size: 14px;
    opacity: 0.9;
}

.lista-dicas li::before {
    content: '✓';
    position: absolute;
    left: 0;
    top: 0;
    font-weight: 700;
}

/* Formulário */
.form-fisio {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-secao {
    padding: 20px;
    background: var(--cinza-claro);
    border-radius: 12px;
}

.secao-titulo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 700;
    color: var(--azul-saude);
    margin-bottom: 16px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

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
.form-grupo textarea {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    transition: var(--transicao);
}

.form-grupo input:focus,
.form-grupo textarea:focus {
    outline: none;
    border-color: var(--azul-saude);
    background: white;
}

.form-grupo textarea {
    resize: vertical;
    min-height: 80px;
}

/* Opções Rápidas */
.opcoes-rapidas {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.opcao-label {
    font-size: 13px;
    color: var(--cinza-medio);
    font-weight: 600;
}

.btn-opcao {
    padding: 6px 12px;
    background: white;
    border: 1px solid var(--cinza-medio);
    border-radius: 20px;
    font-size: 12px;
    cursor: pointer;
    transition: var(--transicao);
}

.btn-opcao:hover {
    background: var(--azul-saude);
    border-color: var(--azul-saude);
    color: white;
}

/* Badge Especialidade */
.especialidade-badge {
    background: var(--verde-terapia);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Resultado */
.resultado-container {
    min-height: 400px;
    max-height: 600px;
    overflow-y: auto;
    padding: 20px;
}

.resultado-vazio {
    text-align: center;
    padding: 60px 20px;
    color: var(--cinza-medio);
}

.resultado-vazio i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.resultado-vazio h3 {
    font-size: 20px;
    margin-bottom: 12px;
    color: var(--cinza-escuro);
}

.resultado-conteudo {
    font-size: 15px;
    line-height: 1.8;
    color: var(--cinza-escuro);
}

.resultado-conteudo h3 {
    color: var(--azul-saude);
    margin: 20px 0 12px;
    font-size: 18px;
}

.resultado-conteudo ul {
    margin-left: 20px;
    margin-bottom: 16px;
}

.resultado-conteudo li {
    margin-bottom: 8px;
}

/* Loading */
.loading-analise {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 16px;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid var(--cinza-medio);
    border-top-color: var(--azul-saude);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.loading-analise p {
    margin-top: 16px;
    font-weight: 600;
    color: var(--cinza-escuro);
}

.loading-analise small {
    color: var(--cinza-medio);
    margin-top: 4px;
}

/* Ações do Resultado */
.acoes-resultado {
    display: flex;
    gap: 8px;
}

.btn-acao {
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

.btn-acao:hover {
    background: var(--azul-saude);
    color: white;
}

/* Feedback */
.card-feedback {
    padding: 20px;
    text-align: center;
}

.card-feedback h4 {
    margin-bottom: 16px;
    color: var(--cinza-escuro);
}

.feedback-botoes {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.btn-feedback {
    padding: 10px 20px;
    background: var(--cinza-claro);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transicao);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-feedback:hover {
    transform: translateY(-2px);
}

.btn-feedback.positivo:hover {
    background: var(--sucesso);
    color: white;
}

.btn-feedback.negativo:hover {
    background: var(--erro);
    color: white;
}

.btn-feedback.neutro:hover {
    background: var(--info);
    color: white;
}

/* Correções para campos de formulário */
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

/* Ações dos formulários */
.form-acoes {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
}

/* Subtítulo escuro para consistência */
.subtitulo-pagina-escuro {
    font-size: 16px;
    color: var(--cinza-escuro);
    margin-bottom: 32px;
    font-weight: 500;
}

/* Header do card com estilos consistentes */
.card-header-fisio {
    padding: 0 0 16px 0;
    border-bottom: 1px solid var(--cinza-medio);
    margin-bottom: 24px;
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

/* Melhorias no layout das estatísticas */
.user-stats-ai {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

/* Responsividade melhorada */
@media (max-width: 1200px) {
    .ai-interface-grid {
        grid-template-columns: 300px 1fr;
    }
    
    .coluna-resultado {
        grid-column: 1 / -1;
        margin-top: 24px;
    }
}

@media (max-width: 900px) {
    .ai-interface-grid {
        grid-template-columns: 1fr;
    }
    
    .user-stats-ai {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .user-stats-ai {
        grid-template-columns: 1fr;
    }
    
    .form-acoes {
        flex-direction: column;
    }
    
    .opcoes-rapidas {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
// Variáveis globais
let promptSelecionado = null;

// Selecionar especialidade (função original mantida para compatibilidade)
function selecionarEspecialidade(btn) {
    // Remover ativo de todos
    document.querySelectorAll('.btn-especialidade').forEach(b => b.classList.remove('ativo'));
    
    // Adicionar ativo no selecionado
    btn.classList.add('ativo');
    
    // Atualizar dados
    promptSelecionado = {
        id: btn.dataset.promptId,
        name: btn.dataset.promptName
    };
    
    // Atualizar input hidden
    document.getElementById('promptId').value = promptSelecionado.id;
    
    // Mostrar badge
    const badge = document.getElementById('especialidadeSelecionada');
    badge.querySelector('span').textContent = promptSelecionado.name;
    badge.style.display = 'flex';
}

// Selecionar robô IA (nova função para os 23 robôs)
function selecionarRobotIA(btn) {
    // Remover ativo de todos
    document.querySelectorAll('.btn-especialidade').forEach(b => b.classList.remove('ativo'));
    
    // Adicionar ativo no selecionado
    btn.classList.add('ativo');
    
    // Atualizar dados
    promptSelecionado = {
        slug: btn.dataset.robotSlug,
        name: btn.dataset.robotName
    };
    
    // Atualizar input hidden com o slug do robô
    document.getElementById('promptId').value = promptSelecionado.slug;
    
    // Mostrar badge
    const badge = document.getElementById('especialidadeSelecionada');
    badge.querySelector('span').textContent = promptSelecionado.name;
    badge.style.display = 'flex';
    
    // Adaptar formulário baseado no robô selecionado
    adaptarFormularioParaRobot(promptSelecionado.slug);
}

// Adaptar formulário baseado no tipo de robô
function adaptarFormularioParaRobot(robotSlug) {
    // Adaptar labels e placeholders baseado no robô
    const adaptacoes = {
        'dr_autoritas': {
            titulo: 'Conteúdo para Instagram',
            placeholder: 'Descreva o tipo de conteúdo que deseja criar para Instagram...'
        },
        'dr_acolhe': {
            titulo: 'Atendimento ao Cliente',
            placeholder: 'Descreva a situação do atendimento via WhatsApp/Direct...'
        },
        'dr_fechador': {
            titulo: 'Vendas de Planos',
            placeholder: 'Descreva o perfil do cliente e o plano a ser oferecido...'
        },
        'dr_reab': {
            titulo: 'Prescrição de Exercícios',
            placeholder: 'Descreva a condição clínica e necessidades do paciente...'
        }
        // Adicionar mais adaptações conforme necessário
    };
    
    const adaptacao = adaptacoes[robotSlug] || {
        titulo: 'Solicitação para IA',
        placeholder: 'Descreva sua solicitação...'
    };
    
    // Atualizar placeholder do campo de solicitação
    const campoSolicitacao = document.getElementById('solicitacao');
    if (campoSolicitacao) {
        campoSolicitacao.placeholder = adaptacao.placeholder;
    }
}

// Preencher solicitação rápida
function preencherSolicitacao(texto) {
    const campo = document.getElementById('solicitacao');
    campo.value = texto;
    campo.focus();
}

// Limpar formulário
function limparFormulario() {
    if (confirm('Deseja limpar todos os campos?')) {
        document.getElementById('formFisioterapia').reset();
        document.getElementById('promptId').value = '';
        document.getElementById('especialidadeSelecionada').style.display = 'none';
        document.querySelectorAll('.btn-especialidade').forEach(b => b.classList.remove('ativo'));
        promptSelecionado = null;
    }
}

// Submeter formulário
document.getElementById('formFisioterapia').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!promptSelecionado) {
        mostrarAlerta('Por favor, selecione uma especialidade fisioterapêutica', 'aviso');
        return;
    }
    
    // Mostrar loading
    document.getElementById('loadingAnalise').style.display = 'flex';
    document.getElementById('btnAnalisar').disabled = true;
    
    // Coletar dados
    const formData = new FormData(this);
    const dados = {};
    for (let [key, value] of formData.entries()) {
        dados[key] = value;
    }
    
    try {
        // Simular chamada à API (substituir por chamada real)
        await new Promise(resolve => setTimeout(resolve, 3000));
        
        // Simular resposta (substituir por resposta real)
        const resposta = gerarRespostaSimulada(dados);
        
        // Mostrar resultado
        mostrarResultado(resposta);
        
        // Mostrar ações e feedback
        document.getElementById('acoesResultado').style.display = 'flex';
        document.getElementById('cardFeedback').style.display = 'block';
        
        mostrarAlerta('Análise concluída com sucesso!', 'sucesso');
        
    } catch (erro) {
        mostrarAlerta('Erro ao processar análise. Tente novamente.', 'erro');
        console.error(erro);
        
    } finally {
        document.getElementById('loadingAnalise').style.display = 'none';
        document.getElementById('btnAnalisar').disabled = false;
    }
});

// Gerar resposta simulada (temporário)
function gerarRespostaSimulada(dados) {
    return `
        <div class="resultado-conteudo">
            <h3>📋 Análise Fisioterapêutica - ${promptSelecionado.name}</h3>
            
            <h3>👤 Paciente</h3>
            <p><strong>${dados.nome_paciente}</strong>, ${dados.idade}</p>
            
            <h3>🔍 Avaliação Clínica</h3>
            <p>${dados.diagnostico}</p>
            <p><strong>Queixa principal:</strong> ${dados.queixa_principal}</p>
            ${dados.limitacoes ? `<p><strong>Limitações:</strong> ${dados.limitacoes}</p>` : ''}
            
            <h3>💊 Plano de Tratamento Sugerido</h3>
            <ul>
                <li>Terapia manual para alívio da dor</li>
                <li>Exercícios de fortalecimento progressivo</li>
                <li>Alongamentos específicos da cadeia posterior</li>
                <li>Reeducação postural</li>
                <li>Orientações ergonômicas</li>
            </ul>
            
            <h3>🏃 Exercícios Recomendados</h3>
            <ol>
                <li><strong>Fase Aguda (1-2 semanas):</strong>
                    <ul>
                        <li>Mobilização pélvica em decúbito dorsal</li>
                        <li>Exercícios respiratórios diafragmáticos</li>
                        <li>Contrações isométricas de transverso abdominal</li>
                    </ul>
                </li>
                <li><strong>Fase Subaguda (3-4 semanas):</strong>
                    <ul>
                        <li>Ponte com progressão</li>
                        <li>Exercícios de estabilização lombar</li>
                        <li>Alongamento de isquiotibiais e piriforme</li>
                    </ul>
                </li>
            </ol>
            
            <h3>📊 Prognóstico</h3>
            <p>Com tratamento adequado e adesão do paciente, espera-se melhora significativa em 4-6 semanas.</p>
            
            <h3>⚠️ Observações Importantes</h3>
            <ul>
                <li>Evitar movimentos de flexão anterior do tronco na fase aguda</li>
                <li>Manter atividades de vida diária dentro do limite de dor</li>
                <li>Retornar para reavaliação em 2 semanas</li>
            </ul>
        </div>
    `;
}

// Mostrar resultado
function mostrarResultado(html) {
    const container = document.getElementById('resultadoContainer');
    container.innerHTML = html;
    
    // Scroll suave para o resultado
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Copiar resultado
function copiarResultado() {
    const texto = document.getElementById('resultadoContainer').innerText;
    navigator.clipboard.writeText(texto).then(() => {
        mostrarAlerta('Análise copiada para a área de transferência!', 'sucesso');
    });
}

// Baixar PDF (implementar com biblioteca apropriada)
function baixarPDF() {
    mostrarAlerta('Funcionalidade de PDF será implementada em breve', 'info');
}

// Imprimir resultado
function imprimirResultado() {
    const conteudo = document.getElementById('resultadoContainer').innerHTML;
    const janela = window.open('', '_blank');
    janela.document.write(`
        <html>
            <head>
                <title>Análise Fisioterapêutica - MegaFisio IA</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h3 { color: #1e3a8a; margin-top: 20px; }
                    ul, ol { margin-left: 20px; }
                    li { margin-bottom: 8px; }
                </style>
            </head>
            <body>
                <h1>MegaFisio IA - Análise Fisioterapêutica</h1>
                ${conteudo}
                <p style="margin-top: 40px; text-align: center; color: #666;">
                    Gerado em ${new Date().toLocaleString('pt-BR')}
                </p>
            </body>
        </html>
    `);
    janela.document.close();
    janela.print();
}

// Enviar feedback
function enviarFeedback(tipo) {
    mostrarAlerta(`Obrigado pelo seu feedback ${tipo === 'positivo' ? 'positivo' : tipo === 'negativo' ? 'negativo' : ''}!`, 'sucesso');
    document.getElementById('cardFeedback').style.display = 'none';
}

// Mostrar formulário de feedback detalhado
function mostrarFormFeedback() {
    mostrarAlerta('Formulário de feedback detalhado será implementado em breve', 'info');
}

// Inicializar ao carregar
document.addEventListener('DOMContentLoaded', function() {
    // Se houver uma especialidade pré-selecionada via URL
    const urlParams = new URLSearchParams(window.location.search);
    const tipo = urlParams.get('tipo');
    
    if (tipo) {
        const btn = document.querySelector(`[data-prompt-id*="${tipo}"]`);
        if (btn) {
            selecionarEspecialidade(btn);
        }
    }
});
</script>