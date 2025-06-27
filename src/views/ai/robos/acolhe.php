<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

<!-- Título da Página -->
<h1 class="titulo-pagina">💬 Dr. Acolhe</h1>
<p class="subtitulo-pagina-escuro">Especialista em atendimento via WhatsApp e Direct - Transforme conversas em agendamentos</p>

<!-- Navegação Breadcrumb -->
<nav class="breadcrumb">
    <a href="/ai"><i class="fas fa-robot"></i> Assistentes IA</a>
    <span class="breadcrumb-separator">></span>
    <span class="breadcrumb-current">Dr. Acolhe</span>
</nav>

<!-- Informações do Robô -->
<div class="robo-info-header">
    <div class="robo-avatar">
        <i class="fab fa-whatsapp"></i>
    </div>
    <div class="robo-details">
        <h2>Dr. Acolhe</h2>
        <p class="robo-especialidade">Especialista em atendimento humanizado e conversão digital</p>
        <p class="robo-descricao">Ajudo fisioterapeutas a atenderem pacientes no WhatsApp e Direct com acolhimento, profissionalismo e foco na conversão em agendamentos.</p>
    </div>
</div>

<!-- Formulário de Geração de Conteúdo -->
<div class="container-formulario">
    <form id="formAcolhe" class="formulario-robo">
        <div class="form-grid">
            <!-- Coluna Esquerda -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="tipoAtendimento">Tipo de Atendimento</label>
                    <select id="tipoAtendimento" name="tipo_atendimento" required>
                        <option value="">Selecione o tipo</option>
                        <option value="primeira_conversa">Primeira Conversa (Lead Novo)</option>
                        <option value="retorno_interesse">Retorno de Interesse</option>
                        <option value="agendamento_direto">Agendamento Direto</option>
                        <option value="esclarecimento_duvidas">Esclarecimento de Dúvidas</option>
                        <option value="apresentacao_servicos">Apresentação de Serviços</option>
                        <option value="follow_up">Follow-up pós consulta</option>
                        <option value="reagendamento">Reagendamento</option>
                        <option value="cancelamento">Tratamento de Cancelamento</option>
                    </select>
                    <small class="help-text">Contexto da conversa com o paciente</small>
                </div>

                <div class="campo-grupo">
                    <label for="canalOrigem">Canal de Origem</label>
                    <select id="canalOrigem" name="canal_origem" required>
                        <option value="">De onde veio o contato?</option>
                        <option value="instagram_direct">Instagram Direct</option>
                        <option value="whatsapp_business">WhatsApp Business</option>
                        <option value="facebook_messenger">Facebook Messenger</option>
                        <option value="site_chat">Chat do Site</option>
                        <option value="indicacao">Indicação de Paciente</option>
                        <option value="google_ads">Google Ads</option>
                        <option value="redes_sociais">Redes Sociais</option>
                        <option value="evento_palestra">Evento/Palestra</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="perfilPaciente">Perfil do Paciente</label>
                    <select id="perfilPaciente" name="perfil_paciente" required>
                        <option value="">Qual o perfil esperado?</option>
                        <option value="jovem_adulto">Jovem Adulto (20-35 anos)</option>
                        <option value="adulto_ativo">Adulto Ativo (35-50 anos)</option>
                        <option value="idoso">Idoso (50+ anos)</option>
                        <option value="atleta_esportista">Atleta/Esportista</option>
                        <option value="gestante">Gestante</option>
                        <option value="pai_mae_crianca">Pai/Mãe de Criança</option>
                        <option value="empresario">Empresário/Executivo</option>
                        <option value="dona_casa">Dona de Casa</option>
                        <option value="estudante">Estudante</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="problemaRelato">Problema/Queixa Relatada</label>
                    <input type="text" id="problemaRelato" name="problema_relato" 
                           placeholder="Ex: Dor nas costas, Lesão no joelho, Postura ruim..." required>
                    <small class="help-text">Principal queixa mencionada pelo paciente</small>
                </div>
            </div>

            <!-- Coluna Direita -->
            <div class="form-column">
                <div class="campo-grupo">
                    <label for="objetivoConversa">Objetivo da Conversa</label>
                    <select id="objetivoConversa" name="objetivo_conversa" required>
                        <option value="">Qual seu objetivo?</option>
                        <option value="agendar_avaliacao">Agendar Avaliação</option>
                        <option value="agendar_consulta">Agendar Consulta</option>
                        <option value="apresentar_tratamento">Apresentar Plano de Tratamento</option>
                        <option value="explicar_valores">Explicar Valores e Condições</option>
                        <option value="educar_problema">Educar sobre o Problema</option>
                        <option value="tranquilizar_paciente">Tranquilizar Ansiedades</option>
                        <option value="recuperar_interesse">Recuperar Interesse</option>
                        <option value="fidelizar_paciente">Fidelizar Paciente</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="tomConversa">Tom da Conversa</label>
                    <select id="tomConversa" name="tom_conversa" required>
                        <option value="">Escolha o tom</option>
                        <option value="acolhedor_empatico">Acolhedor e Empático</option>
                        <option value="profissional_confiante">Profissional e Confiante</option>
                        <option value="educativo_informativo">Educativo e Informativo</option>
                        <option value="motivacional_encorajador">Motivacional e Encorajador</option>
                        <option value="descontraido_amigavel">Descontraído e Amigável</option>
                        <option value="tecnico_didatico">Técnico mas Didático</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="servicoFoco">Serviço em Foco</label>
                    <select id="servicoFoco" name="servico_foco">
                        <option value="">Serviço a ser destacado</option>
                        <option value="avaliacao_fisioterapeutica">Avaliação Fisioterapêutica</option>
                        <option value="fisioterapia_ortopedica">Fisioterapia Ortopédica</option>
                        <option value="fisioterapia_neurologica">Fisioterapia Neurológica</option>
                        <option value="pilates_clinico">Pilates Clínico</option>
                        <option value="rpg">RPG</option>
                        <option value="fisioterapia_respiratoria">Fisioterapia Respiratória</option>
                        <option value="fisioterapia_pediatrica">Fisioterapia Pediátrica</option>
                        <option value="fisioterapia_geriatrica">Fisioterapia Geriátrica</option>
                        <option value="fisioterapia_esportiva">Fisioterapia Esportiva</option>
                        <option value="fisioterapia_uroginecologica">Fisioterapia Uroginecológica</option>
                    </select>
                </div>

                <div class="campo-grupo">
                    <label for="informacoesPaciente">Informações do Paciente (Opcional)</label>
                    <textarea id="informacoesPaciente" name="informacoes_paciente" rows="4" 
                              placeholder="Informações adicionais mencionadas pelo paciente: idade, profissão, sintomas específicos, tratamentos anteriores, etc."></textarea>
                    <small class="help-text">Detalhes para personalizar o atendimento</small>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-gerar">
                <i class="fas fa-comments"></i>
                Gerar Resposta Personalizada
            </button>
        </div>
    </form>
</div>

<!-- Área de Resultado -->
<div id="resultadoContainer" class="resultado-container" style="display: none;">
    <div class="resultado-header">
        <h3><i class="fas fa-message"></i> Sua Resposta está Pronta!</h3>
        <div class="resultado-actions">
            <button onclick="copiarResultado()" class="btn-copiar">
                <i class="fas fa-copy"></i> Copiar
            </button>
            <button onclick="enviarWhatsApp()" class="btn-whatsapp">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </button>
            <button onclick="novoAtendimento()" class="btn-novo">
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
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
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
    border-color: #25D366;
    box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
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
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
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
    box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
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
.btn-whatsapp,
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

.btn-whatsapp {
    background: #25D366;
    color: white;
}

.btn-novo {
    background: var(--cinza-medio);
    color: var(--cinza-escuro);
}

.btn-copiar:hover,
.btn-whatsapp:hover,
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
document.getElementById('formAcolhe').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('.btn-gerar');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando resposta...';
    button.disabled = true;
    
    try {
        const formData = new FormData(this);
        formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        formData.append('robo', 'acolhe');
        
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
            registrarUsoRobo('acolhe');
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
        mostrarAlerta('Resposta copiada para a área de transferência!', 'sucesso');
    });
}

// Função para enviar via WhatsApp
function enviarWhatsApp() {
    const conteudo = document.getElementById('conteudoGerado').textContent;
    const encoded = encodeURIComponent(conteudo);
    window.open(`https://wa.me/?text=${encoded}`, '_blank');
}

// Função para novo atendimento
function novoAtendimento() {
    document.getElementById('resultadoContainer').style.display = 'none';
    document.getElementById('formAcolhe').reset();
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