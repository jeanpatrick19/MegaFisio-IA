<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

<!-- Título da Página -->
<h1 class="titulo-pagina">📋 Dra. Protoc</h1>
<p class="subtitulo-pagina-escuro">Especialista em protocolos terapêuticos estruturados - Crie protocolos baseados em evidências</p>

<!-- Navegação Breadcrumb -->
<nav class="breadcrumb">
    <a href="/ai"><i class="fas fa-robot"></i> Assistentes IA</a>
    <span class="breadcrumb-separator">></span>
    <span class="breadcrumb-current">Dra. Protoc</span>
</nav>

<!-- Informações do Robô -->
<div class="robo-info-header">
    <div class="robo-avatar">
        <i class="fas fa-clipboard-list"></i>
    </div>
    <div class="robo-details">
        <h2>Dra. Protoc</h2>
        <p class="robo-especialidade">Especialista em protocolos terapêuticos baseados em evidências científicas</p>
        <p class="robo-descricao">Ajudo fisioterapeutas a criarem protocolos estruturados, padronizados e baseados nas melhores evidências científicas para diferentes condições clínicas.</p>
    </div>
</div>

<!-- Formulário de Geração de Conteúdo -->
<div class="container-formulario">
    <form id="formProtoc" class="formulario-robo">
        <div class="form-grid">
            <!-- Coluna Esquerda -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="condicaoClinica">Condição Clínica</label>
                    <select id="condicaoClinica" name="condicao_clinica" required>
                        <option value="">Selecione a condição</option>
                        <option value="lombalgia_cronica">Lombalgia Crônica</option>
                        <option value="cervicalgia">Cervicalgia</option>
                        <option value="ombro_doloroso">Síndrome do Ombro Doloroso</option>
                        <option value="lesao_lca">Lesão de LCA</option>
                        <option value="tendinopatia_aquiles">Tendinopatia de Aquiles</option>
                        <option value="avc_hemiplegia">AVC/Hemiplegia</option>
                        <option value="parkinson">Doença de Parkinson</option>
                        <option value="osteoartrite_joelho">Osteoartrite de Joelho</option>
                        <option value="fibromialgia">Fibromialgia</option>
                        <option value="pos_cirurgia_joelho">Pós-cirurgia de Joelho</option>
                        <option value="pos_cirurgia_ombro">Pós-cirurgia de Ombro</option>
                        <option value="escoliose">Escoliose</option>
                        <option value="artrite_reumatoide">Artrite Reumatoide</option>
                        <option value="dpoc">DPOC</option>
                        <option value="pos_covid">Síndrome Pós-COVID</option>
                    </select>
                    <small class="help-text">Condição principal a ser tratada</small>
                </div>

                <div class="campo-grupo">
                    <label for="faseCondicao">Fase da Condição</label>
                    <select id="faseCondicao" name="fase_condicao" required>
                        <option value="">Qual a fase atual?</option>
                        <option value="aguda">Aguda (0-72h)</option>
                        <option value="subaguda">Subaguda (3-21 dias)</option>
                        <option value="cronica">Crônica (+6 semanas)</option>
                        <option value="pos_operatorio_precoce">Pós-operatório Precoce (1-6 semanas)</option>
                        <option value="pos_operatorio_tardio">Pós-operatório Tardio (6+ semanas)</option>
                        <option value="manutencao">Manutenção/Prevenção</option>
                        <option value="reagudizacao">Reagudização</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="objetivoTerapeutico">Objetivo Terapêutico Principal</label>
                    <select id="objetivoTerapeutico" name="objetivo_terapeutico" required>
                        <option value="">Qual o foco principal?</option>
                        <option value="alivio_dor">Alívio da Dor</option>
                        <option value="ganho_amplitude">Ganho de Amplitude de Movimento</option>
                        <option value="fortalecimento">Fortalecimento Muscular</option>
                        <option value="melhora_funcionalidade">Melhora da Funcionalidade</option>
                        <option value="estabilizacao_articular">Estabilização Articular</option>
                        <option value="coordenacao_equilibrio">Coordenação e Equilíbrio</option>
                        <option value="resistencia_cardiovascular">Resistência Cardiovascular</option>
                        <option value="retorno_atividades">Retorno às Atividades</option>
                        <option value="prevencao_recidivas">Prevenção de Recidivas</option>
                        <option value="educacao_paciente">Educação do Paciente</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="duracaoTratamento">Duração Estimada do Protocolo</label>
                    <select id="duracaoTratamento" name="duracao_tratamento" required>
                        <option value="">Tempo previsto</option>
                        <option value="2_semanas">2 semanas</option>
                        <option value="4_semanas">4 semanas</option>
                        <option value="6_semanas">6 semanas</option>
                        <option value="8_semanas">8 semanas</option>
                        <option value="12_semanas">12 semanas</option>
                        <option value="16_semanas">16 semanas</option>
                        <option value="24_semanas">24 semanas</option>
                        <option value="protocolo_continuo">Protocolo Contínuo</option>
                    </select>
                </div>
            </div>

            <!-- Coluna Direita -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="frequenciaSemanal">Frequência Semanal</label>
                    <select id="frequenciaSemanal" name="frequencia_semanal" required>
                        <option value="">Quantas vezes por semana?</option>
                        <option value="1x_semana">1x por semana</option>
                        <option value="2x_semana">2x por semana</option>
                        <option value="3x_semana">3x por semana</option>
                        <option value="4x_semana">4x por semana</option>
                        <option value="5x_semana">5x por semana</option>
                        <option value="diario">Diário</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="modalidadeTerapeutica">Modalidades Terapêuticas</label>
                    <select id="modalidadeTerapeutica" name="modalidade_terapeutica" required>
                        <option value="">Recursos disponíveis</option>
                        <option value="terapia_manual">Terapia Manual</option>
                        <option value="cinesioterapia">Cinesioterapia</option>
                        <option value="hidroterapia">Hidroterapia</option>
                        <option value="eletroterapia">Eletroterapia</option>
                        <option value="pilates_clinico">Pilates Clínico</option>
                        <option value="rpg">RPG</option>
                        <option value="exercicios_funcionais">Exercícios Funcionais</option>
                        <option value="treino_cardio">Treinamento Cardiovascular</option>
                        <option value="combinado">Abordagem Combinada</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="nivelEvidencia">Nível de Evidência Desejado</label>
                    <select id="nivelEvidencia" name="nivel_evidencia">
                        <option value="">Nível científico</option>
                        <option value="alto">Alto (Revisões Sistemáticas/Meta-análises)</option>
                        <option value="moderado">Moderado (Ensaios Clínicos Randomizados)</option>
                        <option value="baixo">Baixo (Estudos Observacionais)</option>
                        <option value="consenso">Consenso de Especialistas</option>
                        <option value="experiencia_clinica">Experiência Clínica</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="observacoesEspeciais">Observações Especiais</label>
                    <textarea id="observacoesEspeciais" name="observacoes_especiais" rows="4" 
                              placeholder="Particularidades do caso, contraindicações, comorbidades, preferências do paciente, etc."></textarea>
                    <small class="help-text">Informações adicionais para personalizar o protocolo</small>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-gerar">
                <i class="fas fa-file-medical"></i>
                Gerar Protocolo Estruturado
            </button>
        </div>
    </form>
</div>

<!-- Área de Resultado -->
<div id="resultadoContainer" class="resultado-container" style="display: none;">
    <div class="resultado-header">
        <h3><i class="fas fa-certificate"></i> Seu Protocolo Científico está Pronto!</h3>
        <div class="resultado-actions">
            <button onclick="copiarResultado()" class="btn-copiar">
                <i class="fas fa-copy"></i> Copiar
            </button>
            <button onclick="baixarProtocolo()" class="btn-download">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button onclick="novoProtocolo()" class="btn-novo">
                <i class="fas fa-plus"></i> Novo
            </button>
        </div>
    </div>
    <div class="resultado-content">
        <div id="conteudoGerado"></div>
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

/* Header do Robô */
.robo-info-header {
    background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 24px;
    color: white;
}

.robo-avatar {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    backdrop-filter: blur(10px);
}

.robo-details h2 {
    margin: 0 0 8px 0;
    font-size: 28px;
    font-weight: 700;
}

.robo-especialidade {
    font-size: 16px;
    opacity: 0.9;
    margin: 0 0 12px 0;
    font-weight: 500;
}

.robo-descricao {
    font-size: 15px;
    opacity: 0.8;
    line-height: 1.6;
    margin: 0;
}

/* Container do Formulário */
.container-formulario {
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-claro);
    overflow: hidden;
    margin-bottom: 32px;
}

.formulario-robo {
    padding: 32px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    margin-bottom: 32px;
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.campo-grupo {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.campo-grupo label {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.campo-grupo select,
.campo-grupo input,
.campo-grupo textarea {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
}

.campo-grupo select:focus,
.campo-grupo input:focus,
.campo-grupo textarea:focus {
    outline: none;
    border-color: #6366F1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.campo-grupo textarea {
    resize: vertical;
    min-height: 100px;
}

.help-text {
    font-size: 12px;
    color: var(--cinza-medio);
    margin-top: 4px;
}

.form-footer {
    text-align: center;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-claro);
}

.btn-gerar {
    background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
    color: white;
    border: none;
    padding: 16px 32px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 auto;
    transition: var(--transicao);
}

.btn-gerar:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
}

/* Área de Resultado */
.resultado-container {
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-claro);
    overflow: hidden;
}

.resultado-header {
    background: var(--cinza-claro);
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--cinza-medio);
}

.resultado-header h3 {
    margin: 0;
    color: var(--cinza-escuro);
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.resultado-actions {
    display: flex;
    gap: 12px;
}

.btn-copiar,
.btn-download,
.btn-novo {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: var(--transicao);
}

.btn-copiar {
    background: var(--azul-saude);
    color: white;
}

.btn-download {
    background: #6366F1;
    color: white;
}

.btn-novo {
    background: var(--cinza-medio);
    color: var(--cinza-escuro);
}

.btn-copiar:hover,
.btn-download:hover,
.btn-novo:hover {
    transform: translateY(-1px);
    opacity: 0.9;
}

.resultado-content {
    padding: 24px;
}

#conteudoGerado {
    background: var(--cinza-claro);
    padding: 24px;
    border-radius: 12px;
    line-height: 1.7;
    white-space: pre-wrap;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

/* Responsivo */
@media (max-width: 768px) {
    .robo-info-header {
        flex-direction: column;
        text-align: center;
        padding: 24px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .resultado-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .resultado-actions {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Formulário de geração de conteúdo
document.getElementById('formProtoc').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('.btn-gerar');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando protocolo...';
    button.disabled = true;
    
    try {
        const formData = new FormData(this);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        formData.append('robo', 'protoc');
        
        const response = await fetch('/ai/gerar-conteudo', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('conteudoGerado').textContent = result.conteudo;
            document.getElementById('resultadoContainer').style.display = 'block';
            document.getElementById('resultadoContainer').scrollIntoView({ behavior: 'smooth' });
            
            // Registrar uso
            registrarUsoRobo('protoc');
        } else {
            mostrarAlerta(result.error || 'Erro ao gerar protocolo', 'erro');
        }
    } catch (error) {
        mostrarAlerta('Erro ao conectar com o servidor', 'erro');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
});

// Função para copiar resultado
function copiarResultado() {
    const conteudo = document.getElementById('conteudoGerado').textContent;
    navigator.clipboard.writeText(conteudo).then(() => {
        mostrarAlerta('Protocolo copiado para a área de transferência!', 'sucesso');
    });
}

// Função para baixar protocolo
function baixarProtocolo() {
    const conteudo = document.getElementById('conteudoGerado').textContent;
    // Implementar geração de PDF aqui
    mostrarAlerta('Download em desenvolvimento', 'info');
}

// Função para novo protocolo
function novoProtocolo() {
    document.getElementById('resultadoContainer').style.display = 'none';
    document.getElementById('formProtoc').reset();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Função para registrar uso do robô
async function registrarUsoRobo(nomeRobo) {
    try {
        await fetch('/ai/registrar-uso', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'robo': nomeRobo,
                'csrf_token': document.querySelector('meta[name="csrf-token"]')?.content || ''
            })
        });
    } catch (error) {
        console.log('Erro ao registrar uso:', error);
    }
}

// Função para mostrar alertas
function mostrarAlerta(mensagem, tipo = 'sucesso') {
    const alerta = document.createElement('div');
    alerta.className = `alerta-flutuante ${tipo}`;
    alerta.innerHTML = `
        <i class="fas fa-${tipo === 'sucesso' ? 'check-circle' : tipo === 'erro' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${mensagem}</span>
    `;
    
    document.body.appendChild(alerta);
    setTimeout(() => alerta.classList.add('show'), 10);
    
    setTimeout(() => {
        alerta.classList.remove('show');
        setTimeout(() => alerta.remove(), 300);
    }, 3000);
}
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

.alerta-flutuante.info {
    border-left: 4px solid var(--azul-saude);
    color: var(--azul-saude);
}

.alerta-flutuante i {
    font-size: 20px;
}

.alerta-flutuante span {
    color: var(--cinza-escuro);
}
</style>