<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

<!-- Título da Página -->
<h1 class="titulo-pagina">🎯 Dr. Fechador</h1>
<p class="subtitulo-pagina-escuro">Especialista em vendas de planos fisioterapêuticos - Converta leads em pacientes fiéis</p>

<!-- Navegação Breadcrumb -->
<nav class="breadcrumb">
    <a href="/ai"><i class="fas fa-robot"></i> Assistentes IA</a>
    <span class="breadcrumb-separator">></span>
    <span class="breadcrumb-current">Dr. Fechador</span>
</nav>

<!-- Informações do Robô -->
<div class="robo-info-header">
    <div class="robo-avatar">
        <i class="fas fa-handshake"></i>
    </div>
    <div class="robo-details">
        <h2>Dr. Fechador</h2>
        <p class="robo-especialidade">Especialista em vendas éticas e consultivas para fisioterapia</p>
        <p class="robo-descricao">Ajudo fisioterapeutas a apresentarem seus planos de tratamento de forma profissional, ética e persuasiva, focando nos benefícios para a saúde do paciente.</p>
    </div>
</div>

<!-- Formulário de Geração de Conteúdo -->
<div class="container-formulario">
    <form id="formFechador" class="formulario-robo">
        <div class="form-grid">
            <!-- Coluna Esquerda -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="tipoVenda">Tipo de Venda</label>
                    <select id="tipoVenda" name="tipo_venda" required>
                        <option value="">Selecione o contexto</option>
                        <option value="primeira_consulta">Primeira Consulta - Apresentação inicial</option>
                        <option value="pos_avaliacao">Pós-avaliação - Proposta de tratamento</option>
                        <option value="renovacao_plano">Renovação de plano existente</option>
                        <option value="upgrade_servico">Upgrade de serviço</option>
                        <option value="pacote_intensivo">Pacote intensivo</option>
                        <option value="manutencao_preventiva">Manutenção preventiva</option>
                        <option value="plano_familiar">Plano familiar</option>
                        <option value="recuperacao_cliente">Recuperação de cliente</option>
                    </select>
                    <small class="help-text">Momento da venda no processo</small>
                </div>

                <div class="campo-grupo">
                    <label for="servicoPrincipal">Serviço Principal</label>
                    <select id="servicoPrincipal" name="servico_principal" required>
                        <option value="">Que serviço oferece?</option>
                        <option value="fisioterapia_ortopedica">Fisioterapia Ortopédica</option>
                        <option value="fisioterapia_neurologica">Fisioterapia Neurológica</option>
                        <option value="fisioterapia_respiratoria">Fisioterapia Respiratória</option>
                        <option value="fisioterapia_pediatrica">Fisioterapia Pediátrica</option>
                        <option value="fisioterapia_geriatrica">Fisioterapia Geriátrica</option>
                        <option value="fisioterapia_esportiva">Fisioterapia Esportiva</option>
                        <option value="fisioterapia_uroginecologica">Fisioterapia Uroginecológica</option>
                        <option value="pilates_clinico">Pilates Clínico</option>
                        <option value="rpg">RPG (Reeducação Postural Global)</option>
                        <option value="fisioterapia_domiciliar">Fisioterapia Domiciliar</option>
                        <option value="reabilitacao_pos_cirurgica">Reabilitação Pós-cirúrgica</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="perfilPaciente">Perfil do Paciente</label>
                    <select id="perfilPaciente" name="perfil_paciente" required>
                        <option value="">Quem é o paciente?</option>
                        <option value="jovem_atleta">Jovem Atleta/Esportista</option>
                        <option value="adulto_trabalhador">Adulto Trabalhador (dor postural)</option>
                        <option value="idoso_ativo">Idoso ativo</option>
                        <option value="pos_cirurgico">Pós-cirúrgico</option>
                        <option value="lesao_cronica">Lesão crônica</option>
                        <option value="gestante">Gestante</option>
                        <option value="crianca">Criança</option>
                        <option value="executivo">Executivo/Empresário</option>
                        <option value="dona_casa">Dona de casa</option>
                        <option value="aposentado">Aposentado</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="problemaPrincipal">Problema Principal</label>
                    <input type="text" id="problemaPrincipal" name="problema_principal" 
                           placeholder="Ex: Hérnia de disco, Lesão no joelho, Dor cervical..." required>
                    <small class="help-text">Principal queixa ou diagnóstico do paciente</small>
                </div>
            </div>

            <!-- Coluna Direita -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="objetivoTratamento">Objetivo do Tratamento</label>
                    <select id="objetivoTratamento" name="objetivo_tratamento" required>
                        <option value="">Qual o objetivo?</option>
                        <option value="alivio_dor">Alívio da dor</option>
                        <option value="recuperacao_movimento">Recuperação do movimento</option>
                        <option value="fortalecimento">Fortalecimento muscular</option>
                        <option value="melhora_postura">Melhora da postura</option>
                        <option value="retorno_esporte">Retorno ao esporte</option>
                        <option value="prevencao_lesoes">Prevenção de lesões</option>
                        <option value="melhora_qualidade_vida">Melhora da qualidade de vida</option>
                        <option value="preparacao_cirurgia">Preparação para cirurgia</option>
                        <option value="reabilitacao_pos_cirurgia">Reabilitação pós-cirurgia</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="urgencia">Urgência do Caso</label>
                    <select id="urgencia" name="urgencia" required>
                        <option value="">Nível de urgência</option>
                        <option value="alta">Alta - Dor intensa/limitação severa</option>
                        <option value="media">Média - Desconforto moderado</option>
                        <option value="baixa">Baixa - Prevenção/manutenção</option>
                        <option value="eletiva">Eletiva - Melhoria do desempenho</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="objecoesPaciente">Possíveis Objeções</label>
                    <select id="objecoesPaciente" name="objecoes_paciente">
                        <option value="">Principais preocupações</option>
                        <option value="custo">Preocupação com custo</option>
                        <option value="tempo">Falta de tempo</option>
                        <option value="dor_exercicio">Medo de sentir dor nos exercícios</option>
                        <option value="eficacia">Dúvida sobre eficácia</option>
                        <option value="duracao_tratamento">Duração do tratamento</option>
                        <option value="experiencia_anterior">Experiência ruim anterior</option>
                        <option value="conveniencia">Questões de conveniência</option>
                        <option value="segunda_opiniao">Quer segunda opinião</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="informacoesAdicionais">Informações Adicionais</label>
                    <textarea id="informacoesAdicionais" name="informacoes_adicionais" rows="4" 
                              placeholder="Detalhes específicos do caso, limitações, expectativas do paciente, etc."></textarea>
                    <small class="help-text">Contexto adicional para personalizar a proposta</small>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-gerar">
                <i class="fas fa-rocket"></i>
                Gerar Proposta de Vendas
            </button>
        </div>
    </form>
</div>

<!-- Área de Resultado -->
<div id="resultadoContainer" class="resultado-container" style="display: none;">
    <div class="resultado-header">
        <h3><i class="fas fa-trophy"></i> Sua Proposta está Pronta!</h3>
        <div class="resultado-actions">
            <button onclick="copiarResultado()" class="btn-copiar">
                <i class="fas fa-copy"></i> Copiar
            </button>
            <button onclick="baixarProposta()" class="btn-download">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button onclick="novaVenda()" class="btn-novo">
                <i class="fas fa-plus"></i> Nova
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
    background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
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
    border-color: #FF6B35;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
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
    background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
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
    box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
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
    background: #FF6B35;
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
document.getElementById('formFechador').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('.btn-gerar');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando proposta...';
    button.disabled = true;
    
    try {
        const formData = new FormData(this);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        formData.append('robo', 'fechador');
        
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
            registrarUsoRobo('fechador');
        } else {
            mostrarAlerta(result.error || 'Erro ao gerar proposta', 'erro');
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
        mostrarAlerta('Proposta copiada para a área de transferência!', 'sucesso');
    });
}

// Função para baixar proposta
function baixarProposta() {
    const conteudo = document.getElementById('conteudoGerado').textContent;
    // Implementar geração de PDF aqui
    mostrarAlerta('Download em desenvolvimento', 'info');
}

// Função para nova venda
function novaVenda() {
    document.getElementById('resultadoContainer').style.display = 'none';
    document.getElementById('formFechador').reset();
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