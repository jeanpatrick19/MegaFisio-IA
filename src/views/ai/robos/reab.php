<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

<!-- Título da Página -->
<h1 class="titulo-pagina">💪 Dr. Reab</h1>
<p class="subtitulo-pagina-escuro">Especialista em prescrição de exercícios personalizados - Crie planos de reabilitação eficazes</p>

<!-- Navegação Breadcrumb -->
<nav class="breadcrumb">
    <a href="/ai"><i class="fas fa-robot"></i> Assistentes IA</a>
    <span class="breadcrumb-separator">></span>
    <span class="breadcrumb-current">Dr. Reab</span>
</nav>

<!-- Informações do Robô -->
<div class="robo-info-header">
    <div class="robo-avatar">
        <i class="fas fa-dumbbell"></i>
    </div>
    <div class="robo-details">
        <h2>Dr. Reab</h2>
        <p class="robo-especialidade">Especialista em prescrição de exercícios terapêuticos e reabilitação</p>
        <p class="robo-descricao">Ajudo fisioterapeutas a criarem protocolos de exercícios personalizados, baseados em evidências científicas e adaptados às necessidades específicas de cada paciente.</p>
    </div>
</div>

<!-- Formulário de Geração de Conteúdo -->
<div class="container-formulario">
    <form id="formReab" class="formulario-robo">
        <div class="form-grid">
            <!-- Coluna Esquerda -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="areaFoco">Área de Foco</label>
                    <select id="areaFoco" name="area_foco" required>
                        <option value="">Selecione a área</option>
                        <option value="cervical">Cervical/Pescoço</option>
                        <option value="ombro">Ombro e Cintura Escapular</option>
                        <option value="lombar">Lombar/Coluna</option>
                        <option value="quadril">Quadril e Pelve</option>
                        <option value="joelho">Joelho</option>
                        <option value="tornozelo_pe">Tornozelo e Pé</option>
                        <option value="core">Core/Estabilização Central</option>
                        <option value="postura_global">Postura Global</option>
                        <option value="equilibrio">Equilíbrio e Propriocepção</option>
                        <option value="cardiorrespiratorio">Condicionamento Cardiorrespiratório</option>
                    </select>
                    <small class="help-text">Principal região a ser trabalhada</small>
                </div>

                <div class="campo-grupo">
                    <label for="tipoTratamento">Tipo de Tratamento</label>
                    <select id="tipoTratamento" name="tipo_tratamento" required>
                        <option value="">Qual o foco?</option>
                        <option value="pos_cirurgico">Pós-cirúrgico</option>
                        <option value="lesao_aguda">Lesão Aguda</option>
                        <option value="dor_cronica">Dor Crônica</option>
                        <option value="prevencao">Prevenção</option>
                        <option value="fortalecimento">Fortalecimento</option>
                        <option value="mobilidade">Ganho de Mobilidade</option>
                        <option value="estabilizacao">Estabilização</option>
                        <option value="retorno_esporte">Retorno ao Esporte</option>
                        <option value="condicionamento">Condicionamento Físico</option>
                        <option value="manutencao">Manutenção</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="faseTratamento">Fase do Tratamento</label>
                    <select id="faseTratamento" name="fase_tratamento" required>
                        <option value="">Em que fase está?</option>
                        <option value="aguda">Aguda (0-3 dias)</option>
                        <option value="subaguda">Subaguda (3-21 dias)</option>
                        <option value="cronica">Crônica (+21 dias)</option>
                        <option value="inicial">Inicial/Adaptação</option>
                        <option value="intermediaria">Intermediária</option>
                        <option value="avancada">Avançada</option>
                        <option value="retorno_atividade">Retorno à Atividade</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="nivelPaciente">Nível do Paciente</label>
                    <select id="nivelPaciente" name="nivel_paciente" required>
                        <option value="">Condição física atual</option>
                        <option value="sedentario">Sedentário</option>
                        <option value="pouco_ativo">Pouco Ativo</option>
                        <option value="ativo">Ativo</option>
                        <option value="muito_ativo">Muito Ativo</option>
                        <option value="atleta_amador">Atleta Amador</option>
                        <option value="atleta_profissional">Atleta Profissional</option>
                        <option value="debilitado">Debilitado/Limitado</option>
                        <option value="idoso_fragil">Idoso Frágil</option>
                    </select>
                </div>
            </div>

            <!-- Coluna Direita -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="objetivoPrincipal">Objetivo Principal</label>
                    <select id="objetivoPrincipal" name="objetivo_principal" required>
                        <option value="">Qual o objetivo?</option>
                        <option value="alivio_dor">Alívio da Dor</option>
                        <option value="ganho_forca">Ganho de Força</option>
                        <option value="ganho_amplitude">Ganho de Amplitude</option>
                        <option value="melhora_equilibrio">Melhora do Equilíbrio</option>
                        <option value="coordenacao">Coordenação Motora</option>
                        <option value="resistencia">Resistência/Endurance</option>
                        <option value="funcionalidade">Melhora Funcional</option>
                        <option value="performance">Performance Esportiva</option>
                        <option value="qualidade_vida">Qualidade de Vida</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="limitacoes">Limitações/Restrições</label>
                    <select id="limitacoes" name="limitacoes">
                        <option value="">Alguma limitação?</option>
                        <option value="dor_movimento">Dor ao movimento</option>
                        <option value="amplitude_limitada">Amplitude limitada</option>
                        <option value="carga_restrita">Carga restrita</option>
                        <option value="posicoes_evitar">Posições a evitar</option>
                        <option value="equipamentos_limitados">Equipamentos limitados</option>
                        <option value="tempo_limitado">Tempo limitado</option>
                        <option value="domiciliar">Exercícios domiciliares apenas</option>
                        <option value="sem_restricoes">Sem restrições</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="equipamentosDisponiveis">Equipamentos Disponíveis</label>
                    <select id="equipamentosDisponiveis" name="equipamentos_disponiveis" required>
                        <option value="">O que tem disponível?</option>
                        <option value="clinica_completa">Clínica Completa</option>
                        <option value="basico_clinica">Básico de Clínica</option>
                        <option value="peso_corporal">Apenas Peso Corporal</option>
                        <option value="elasticos_halteres">Elásticos e Halteres</option>
                        <option value="bola_suica">Bola Suíça</option>
                        <option value="pilates_solo">Pilates Solo</option>
                        <option value="piscina">Piscina/Hidroterapia</option>
                        <option value="academia">Academia</option>
                        <option value="casa_basico">Casa (básico)</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="informacoesAdicionais">Informações Adicionais</label>
                    <textarea id="informacoesAdicionais" name="informacoes_adicionais" rows="4" 
                              placeholder="Detalhes específicos: diagnóstico, sintomas, avaliações realizadas, histórico, etc."></textarea>
                    <small class="help-text">Contexto adicional para personalizar os exercícios</small>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-gerar">
                <i class="fas fa-clipboard-list"></i>
                Gerar Protocolo de Exercícios
            </button>
        </div>
    </form>
</div>

<!-- Área de Resultado -->
<div id="resultadoContainer" class="resultado-container" style="display: none;">
    <div class="resultado-header">
        <h3><i class="fas fa-list-check"></i> Seu Protocolo está Pronto!</h3>
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
    background: linear-gradient(135deg, #28A745 0%, #20C997 100%);
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
    border-color: #28A745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
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
    background: linear-gradient(135deg, #28A745 0%, #20C997 100%);
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
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
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
    background: #28A745;
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
document.getElementById('formReab').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('.btn-gerar');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando protocolo...';
    button.disabled = true;
    
    try {
        const formData = new FormData(this);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        formData.append('robo', 'reab');
        
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
            registrarUsoRobo('reab');
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
    document.getElementById('formReab').reset();
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