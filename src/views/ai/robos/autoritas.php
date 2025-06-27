<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

<!-- Título da Página -->
<h1 class="titulo-pagina">📱 Dr. Autoritas</h1>
<p class="subtitulo-pagina-escuro">Especialista em conteúdo para Instagram - Crie posts magnéticos que atraem pacientes</p>

<!-- Navegação Breadcrumb -->
<nav class="breadcrumb">
    <a href="/ai"><i class="fas fa-robot"></i> Assistentes IA</a>
    <span class="breadcrumb-separator">></span>
    <span class="breadcrumb-current">Dr. Autoritas</span>
</nav>

<!-- Informações do Robô -->
<div class="robo-info-header">
    <div class="robo-avatar">
        <i class="fab fa-instagram"></i>
    </div>
    <div class="robo-details">
        <h2>Dr. Autoritas</h2>
        <p class="robo-especialidade">Social Media especializada em fisioterapia e marketing digital</p>
        <p class="robo-descricao">Ajudo fisioterapeutas a crescerem no Instagram, atraírem pacientes, educarem com autoridade e gerarem agendamentos com ética e clareza.</p>
    </div>
</div>

<!-- Formulário de Geração de Conteúdo -->
<div class="container-formulario">
    <form id="formAutoritas" class="formulario-robo">
        <div class="form-grid">
            <!-- Coluna Esquerda -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="tipoConteudo">Tipo de Conteúdo</label>
                    <select id="tipoConteudo" name="tipo_conteudo" required>
                        <option value="">Selecione o tipo de post</option>
                        <option value="carrossel_educativo">Carrossel Educativo</option>
                        <option value="reel_exercicios">Reel de Exercícios</option>
                        <option value="post_dicas">Post com Dicas</option>
                        <option value="stories_interativo">Stories Interativo</option>
                        <option value="depoimento_paciente">Depoimento de Paciente</option>
                        <option value="divulgacao_servico">Divulgação de Serviço</option>
                        <option value="mito_verdade">Mito ou Verdade</option>
                        <option value="antes_depois">Antes e Depois</option>
                    </select>
                    <small class="help-text">Escolha o formato ideal para seu objetivo</small>
                </div>

                <div class="campo-grupo">
                    <label for="especialidade">Sua Especialidade</label>
                    <select id="especialidade" name="especialidade" required>
                        <option value="">Selecione sua área</option>
                        <option value="ortopedia">Fisioterapia Ortopédica</option>
                        <option value="neurologia">Fisioterapia Neurológica</option>
                        <option value="respiratoria">Fisioterapia Respiratória</option>
                        <option value="pediatria">Fisioterapia Pediátrica</option>
                        <option value="geriatria">Fisioterapia Geriátrica</option>
                        <option value="esportiva">Fisioterapia Esportiva</option>
                        <option value="uroginecologia">Fisioterapia Uroginecológica</option>
                        <option value="estetica">Fisioterapia Dermatofuncional</option>
                        <option value="pilates">Pilates Clínico</option>
                        <option value="rpg">RPG</option>
                        <option value="geral">Fisioterapia Geral</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="publicoAlvo">Público-Alvo</label>
                    <select id="publicoAlvo" name="publico_alvo" required>
                        <option value="">Quem você quer atingir?</option>
                        <option value="jovens_adultos">Jovens Adultos (20-35 anos)</option>
                        <option value="adultos_ativos">Adultos Ativos (35-50 anos)</option>
                        <option value="idosos">Idosos (50+ anos)</option>
                        <option value="atletas">Atletas e Esportistas</option>
                        <option value="gestantes">Gestantes e Puérperas</option>
                        <option value="criancas">Pais de Crianças</option>
                        <option value="empresarios">Empresários/Executivos</option>
                        <option value="mulheres_estetica">Mulheres (Estética)</option>
                        <option value="geral">Público Geral</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="objetivo">Objetivo do Post</label>
                    <select id="objetivo" name="objetivo" required>
                        <option value="">Qual seu objetivo?</option>
                        <option value="educar">Educar sobre uma condição</option>
                        <option value="desmistificar">Desmistificar um mito</option>
                        <option value="mostrar_exercicios">Mostrar exercícios</option>
                        <option value="divulgar_servico">Divulgar serviço/tratamento</option>
                        <option value="humanizar">Humanizar atendimento</option>
                        <option value="gerar_agendamento">Gerar agendamentos</option>
                        <option value="engajar">Engajar e interagir</option>
                        <option value="autoridade">Demonstrar autoridade</option>
                    </select>
                </div>
            </div>

            <!-- Coluna Direita -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="temaConteudo">Tema/Assunto Principal</label>
                    <input type="text" id="temaConteudo" name="tema_conteudo" 
                           placeholder="Ex: Dor lombar, Exercícios para cervical, Pilates para postura..." required>
                    <small class="help-text">Seja específico sobre o tema que quer abordar</small>
                </div>

                <div class="campo-grupo">
                    <label for="tomVoz">Tom de Voz</label>
                    <select id="tomVoz" name="tom_voz" required>
                        <option value="">Escolha o tom</option>
                        <option value="educativo_formal">Educativo e Formal</option>
                        <option value="descontraido_proximo">Descontraído e Próximo</option>
                        <option value="motivacional">Motivacional e Inspirador</option>
                        <option value="tecnico_acessivel">Técnico mas Acessível</option>
                        <option value="empático_acolhedor">Empático e Acolhedor</option>
                        <option value="autoridade_confianca">Autoridade e Confiança</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="cta">Call to Action (CTA)</label>
                    <select id="cta" name="cta">
                        <option value="">Escolha a ação desejada</option>
                        <option value="agendar">Agendar consulta</option>
                        <option value="duvidas_dm">Tirar dúvidas no DM</option>
                        <option value="comentar">Comentar experiências</option>
                        <option value="compartilhar">Compartilhar com alguém</option>
                        <option value="salvar">Salvar o post</option>
                        <option value="seguir">Seguir o perfil</option>
                        <option value="whatsapp">Chamar no WhatsApp</option>
                        <option value="website">Visitar site/link</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="informacoesExtra">Informações Extras</label>
                    <textarea id="informacoesExtra" name="informacoes_extras" rows="4" 
                              placeholder="Adicione detalhes específicos que quer incluir no conteúdo: estatísticas, benefícios específicos, diferenciais do seu atendimento, etc."></textarea>
                    <small class="help-text">Opcional: Detalhes que tornarão seu conteúdo único</small>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-gerar">
                <i class="fas fa-magic"></i>
                Gerar Conteúdo para Instagram
            </button>
        </div>
    </form>
</div>

<!-- Área de Resultado -->
<div id="resultadoContainer" class="resultado-container" style="display: none;">
    <div class="resultado-header">
        <h3><i class="fas fa-sparkles"></i> Seu Conteúdo está Pronto!</h3>
        <div class="resultado-actions">
            <button onclick="copiarResultado()" class="btn-copiar">
                <i class="fas fa-copy"></i> Copiar
            </button>
            <button onclick="baixarPDF()" class="btn-download">
                <i class="fas fa-download"></i> PDF
            </button>
            <button onclick="novoConteudo()" class="btn-novo">
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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
    background: var(--lilas-cuidado);
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
document.getElementById('formAutoritas').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('.btn-gerar');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando conteúdo...';
    button.disabled = true;
    
    try {
        const formData = new FormData(this);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        formData.append('robo', 'autoritas');
        
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
            registrarUsoRobo('autoritas');
        } else {
            mostrarAlerta(result.error || 'Erro ao gerar conteúdo', 'erro');
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
        mostrarAlerta('Conteúdo copiado para a área de transferência!', 'sucesso');
    });
}

// Função para baixar PDF
function baixarPDF() {
    const conteudo = document.getElementById('conteudoGerado').textContent;
    // Implementar geração de PDF aqui
    mostrarAlerta('Funcionalidade em desenvolvimento', 'info');
}

// Função para novo conteúdo
function novoConteudo() {
    document.getElementById('resultadoContainer').style.display = 'none';
    document.getElementById('formAutoritas').reset();
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