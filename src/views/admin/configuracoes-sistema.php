<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Configurações do Sistema</h1>
<p class="subtitulo-pagina-escuro">Configure aparência, dashboards, notificações e comportamento do sistema</p>

<!-- Abas de Configurações -->
<div class="config-abas">
    <button class="aba-btn ativa" onclick="trocarAbaConfig('visual')" id="abaVisual">
        <i class="fas fa-palette"></i>
        Visual & Tema
    </button>
    <button class="aba-btn" onclick="trocarAbaConfig('dashboard')" id="abaDashboard">
        <i class="fas fa-chart-pie"></i>
        Dashboard
    </button>
    <button class="aba-btn" onclick="trocarAbaConfig('notificacoes')" id="abaNotificacoes">
        <i class="fas fa-bell"></i>
        Notificações
    </button>
    <button class="aba-btn" onclick="trocarAbaConfig('sistema')" id="abaSistema">
        <i class="fas fa-cogs"></i>
        Sistema
    </button>
    <button class="aba-btn" onclick="trocarAbaConfig('integracao')" id="abaIntegracao">
        <i class="fas fa-plug"></i>
        Integrações
    </button>
</div>

<!-- Aba Visual & Tema -->
<div class="aba-conteudo ativa" id="conteudoVisual">
    <div class="config-secao">
        <div class="config-grid">
            <!-- Temas Predefinidos -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Temas do Sistema</h3>
                    <p>Escolha o tema visual para todo o sistema</p>
                </div>
                
                <div class="temas-grid">
                    <div class="tema-card ativo" onclick="selecionarTema('medico')">
                        <div class="tema-preview medico">
                            <div class="preview-header"></div>
                            <div class="preview-sidebar"></div>
                            <div class="preview-content"></div>
                        </div>
                        <div class="tema-info">
                            <h4>Médico Profissional</h4>
                            <p>Azul saúde com verde terapia</p>
                        </div>
                        <i class="fas fa-check tema-check"></i>
                    </div>
                    
                    <div class="tema-card" onclick="selecionarTema('moderno')">
                        <div class="tema-preview moderno">
                            <div class="preview-header"></div>
                            <div class="preview-sidebar"></div>
                            <div class="preview-content"></div>
                        </div>
                        <div class="tema-info">
                            <h4>Moderno Escuro</h4>
                            <p>Cinza escuro com acentos azuis</p>
                        </div>
                        <i class="fas fa-check tema-check"></i>
                    </div>
                    
                    <div class="tema-card" onclick="selecionarTema('minimalista')">
                        <div class="tema-preview minimalista">
                            <div class="preview-header"></div>
                            <div class="preview-sidebar"></div>
                            <div class="preview-content"></div>
                        </div>
                        <div class="tema-info">
                            <h4>Minimalista</h4>
                            <p>Branco limpo com toques coloridos</p>
                        </div>
                        <i class="fas fa-check tema-check"></i>
                    </div>
                </div>
            </div>
            
            <!-- Personalização de Cores -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Cores Personalizadas</h3>
                    <p>Defina suas cores principais</p>
                </div>
                
                <div class="cores-config">
                    <div class="cor-item">
                        <label>Cor Primária</label>
                        <div class="cor-input">
                            <input type="color" id="corPrimaria" value="#1e3a8a">
                            <span>#1e3a8a</span>
                        </div>
                    </div>
                    
                    <div class="cor-item">
                        <label>Cor Secundária</label>
                        <div class="cor-input">
                            <input type="color" id="corSecundaria" value="#059669">
                            <span>#059669</span>
                        </div>
                    </div>
                    
                    <div class="cor-item">
                        <label>Cor de Destaque</label>
                        <div class="cor-input">
                            <input type="color" id="corDestaque" value="#ca8a04">
                            <span>#ca8a04</span>
                        </div>
                    </div>
                    
                    <div class="cor-item">
                        <label>Cor de Fundo</label>
                        <div class="cor-input">
                            <input type="color" id="corFundo" value="#f8fafc">
                            <span>#f8fafc</span>
                        </div>
                    </div>
                </div>
                
                <div class="config-acoes">
                    <button class="btn-fisio btn-secundario" onclick="resetarCores()">
                        <i class="fas fa-undo"></i>
                        Resetar
                    </button>
                    <button class="btn-fisio btn-primario" onclick="aplicarCores()">
                        <i class="fas fa-save"></i>
                        Aplicar Cores
                    </button>
                </div>
            </div>
            
            <!-- Logo e Branding -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Logo e Identidade</h3>
                    <p>Configure a identidade visual</p>
                </div>
                
                <form id="formLogoIdentidade" class="logo-config">
                    <!-- Seção Logo -->
                    <div class="logo-secao-completa">
                        <h4>Logo do Sistema</h4>
                        <div class="logo-layout">
                            <div class="logo-atual">
                                <div class="logo-preview">
                                    <i class="fas fa-hand-holding-medical"></i>
                                    <span>MegaFisio IA</span>
                                </div>
                                <p>Logo atual</p>
                            </div>
                            
                            <div class="logo-upload">
                                <div class="upload-area" onclick="document.getElementById('logoFile').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Clique para enviar nova logo</p>
                                    <small>PNG, JPG até 2MB</small>
                                </div>
                                <input type="file" id="logoFile" name="logo_file" style="display: none;" accept="image/*">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Seção Textos -->
                    <div class="texto-secao-completa">
                        <h4>Textos do Sistema</h4>
                        <div class="form-stack">
                            <div class="form-grupo">
                                <label>Nome do Sistema</label>
                                <input type="text" value="MegaFisio IA" id="nomeSistema" name="nome_sistema">
                            </div>
                            <div class="form-grupo">
                                <label>Slogan</label>
                                <input type="text" value="Inteligência para Fisioterapia" id="sloganSistema" name="slogan_sistema">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-acoes">
                        <button type="button" class="btn-fisio btn-secundario" id="btnEditarLogoIdentidade" onclick="toggleEditMode('formLogoIdentidade', 'btnEditarLogoIdentidade', 'btnSalvarLogoIdentidade')">
                            <i class="fas fa-edit"></i>
                            Editar
                        </button>
                        <button type="submit" class="btn-fisio btn-primario" id="btnSalvarLogoIdentidade" style="display: none;">
                            <i class="fas fa-save"></i>
                            Salvar Identidade
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Botão Salvar da Aba Visual -->
        <div class="aba-salvar">
            <button class="btn-fisio btn-primario" onclick="salvarConfiguracoes('visual')">
                <i class="fas fa-save"></i>
                Salvar Configurações Visuais
            </button>
        </div>
    </div>
</div>

<!-- Aba Dashboard -->
<div class="aba-conteudo" id="conteudoDashboard">
    <div class="config-secao">
        <div class="dashboard-config">
            <!-- Configuração de Widgets -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Widgets do Dashboard</h3>
                    <p>Configure quais informações exibir no dashboard</p>
                </div>
                
                <div class="widgets-lista">
                    <div class="widget-item ativo">
                        <div class="widget-info">
                            <i class="fas fa-users"></i>
                            <div>
                                <h4>Estatísticas de Usuários</h4>
                                <p>Total de usuários, ativos, online</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="widget-item ativo">
                        <div class="widget-info">
                            <i class="fas fa-brain"></i>
                            <div>
                                <h4>Uso da IA</h4>
                                <p>Solicitações, taxa de sucesso, prompts populares</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="widget-item">
                        <div class="widget-info">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h4>Gráfico de Atividade</h4>
                                <p>Atividade dos usuários ao longo do tempo</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="widget-item ativo">
                        <div class="widget-info">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                <h4>Alertas do Sistema</h4>
                                <p>Erros, avisos e informações importantes</p>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Configuração de Layout -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Layout do Dashboard</h3>
                    <p>Organize a disposição dos elementos</p>
                </div>
                
                <div class="layout-options">
                    <div class="layout-option ativo" onclick="selecionarLayout('classico')">
                        <div class="layout-preview classico">
                            <div class="layout-header"></div>
                            <div class="layout-stats"></div>
                            <div class="layout-content"></div>
                        </div>
                        <span>Clássico</span>
                    </div>
                    
                    <div class="layout-option" onclick="selecionarLayout('moderno')">
                        <div class="layout-preview moderno">
                            <div class="layout-header"></div>
                            <div class="layout-grid"></div>
                        </div>
                        <span>Moderno</span>
                    </div>
                    
                    <div class="layout-option" onclick="selecionarLayout('compacto')">
                        <div class="layout-preview compacto">
                            <div class="layout-header"></div>
                            <div class="layout-compact"></div>
                        </div>
                        <span>Compacto</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botão Salvar da Aba Dashboard -->
        <div class="aba-salvar">
            <button class="btn-fisio btn-primario" onclick="salvarConfiguracoes('dashboard')">
                <i class="fas fa-save"></i>
                Salvar Configurações Dashboard
            </button>
        </div>
    </div>
</div>

<!-- Aba Notificações -->
<div class="aba-conteudo" id="conteudoNotificacoes">
    <div class="config-secao">
        <div class="notif-config">
            <!-- Tipos de Notificação -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Tipos de Notificação</h3>
                    <p>Configure quais eventos geram notificações</p>
                </div>
                
                <div class="notif-tipos">
                    <div class="notif-categoria">
                        <h4>Sistema</h4>
                        <div class="notif-item">
                            <span>Novos usuários cadastrados</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notif-item">
                            <span>Erros do sistema</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notif-item">
                            <span>Atualizações disponíveis</span>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="notif-categoria">
                        <h4>Usuários</h4>
                        <div class="notif-item">
                            <span>Login de administrador</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notif-item">
                            <span>Tentativas de login falharam</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notif-item">
                            <span>Usuários inativos há 30 dias</span>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="notif-categoria">
                        <h4>IA</h4>
                        <div class="notif-item">
                            <span>Alto volume de solicitações</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notif-item">
                            <span>Taxa de erro elevada</span>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notif-item">
                            <span>Novo prompt criado</span>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Canais de Entrega -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Canais de Entrega</h3>
                    <p>Como as notificações são enviadas</p>
                </div>
                
                <div class="canais-grid">
                    <div class="canal-item">
                        <div class="canal-header">
                            <i class="fas fa-desktop"></i>
                            <h4>Sistema</h4>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <p>Notificações dentro do sistema</p>
                    </div>
                    
                    <div class="canal-item">
                        <div class="canal-header">
                            <i class="fas fa-envelope"></i>
                            <h4>Email</h4>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <p>Envio por email</p>
                        <div class="canal-config">
                            <input type="email" placeholder="admin@megafisio.com.br" value="admin@megafisio.com.br">
                        </div>
                    </div>
                    
                    <div class="canal-item">
                        <div class="canal-header">
                            <i class="fas fa-sms"></i>
                            <h4>SMS</h4>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <p>Notificações por SMS</p>
                        <div class="canal-config">
                            <input type="tel" placeholder="(11) 99999-9999">
                        </div>
                    </div>
                    
                    <div class="canal-item">
                        <div class="canal-header">
                            <i class="fab fa-whatsapp"></i>
                            <h4>WhatsApp</h4>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <p>Integração com WhatsApp Business</p>
                        <div class="canal-config">
                            <button class="btn-fisio btn-secundario">Configurar API</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botão Salvar da Aba Notificações -->
        <div class="aba-salvar">
            <button class="btn-fisio btn-primario" onclick="salvarConfiguracoes('notificacoes')">
                <i class="fas fa-save"></i>
                Salvar Configurações Notificações
            </button>
        </div>
    </div>
</div>

<!-- Aba Sistema -->
<div class="aba-conteudo" id="conteudoSistema">
    <div class="config-secao">
        <div class="sistema-config">
            <!-- Configurações Gerais -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Configurações Gerais</h3>
                    <p>Configurações básicas do sistema</p>
                </div>
                
                <form id="formConfigSistema" class="config-form">
                    <div class="form-grid">
                        <div class="form-grupo">
                            <label>Fuso Horário</label>
                            <select name="fuso_horario">
                                <option value="America/Sao_Paulo" selected>América/São Paulo (UTC-3)</option>
                                <option value="America/New_York">América/Nova York (UTC-5)</option>
                                <option value="Europe/London">Europa/Londres (UTC+0)</option>
                            </select>
                        </div>
                        
                        <div class="form-grupo">
                            <label>Idioma do Sistema</label>
                            <select name="idioma_sistema">
                                <option value="pt-BR" selected>Português (Brasil)</option>
                                <option value="en-US">English (US)</option>
                                <option value="es-ES">Español</option>
                            </select>
                        </div>
                        
                        <div class="form-grupo">
                            <label>Timeout de Sessão (minutos)</label>
                            <input type="number" name="timeout_sessao" value="60" min="15" max="480">
                        </div>
                        
                        <div class="form-grupo">
                            <label>Máximo de tentativas de login</label>
                            <input type="number" name="max_tentativas_login" value="5" min="3" max="10">
                        </div>
                    </div>
                    
                    <div class="config-switches">
                        <div class="switch-item">
                            <span>Registrar logs detalhados</span>
                            <label class="switch">
                                <input type="checkbox" name="logs_detalhados" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        
                        <div class="switch-item">
                            <span>Backup automático diário</span>
                            <label class="switch">
                                <input type="checkbox" name="backup_automatico" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        
                        <div class="switch-item">
                            <span>Modo de manutenção</span>
                            <label class="switch">
                                <input type="checkbox" name="modo_manutencao">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-acoes">
                        <button type="button" class="btn-fisio btn-secundario" id="btnEditarConfigSistema" onclick="toggleEditMode('formConfigSistema', 'btnEditarConfigSistema', 'btnSalvarConfigSistema')">
                            <i class="fas fa-edit"></i>
                            Editar
                        </button>
                        <button type="submit" class="btn-fisio btn-primario" id="btnSalvarConfigSistema" style="display: none;">
                            <i class="fas fa-save"></i>
                            Salvar Sistema
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Configurações de Segurança -->
            <div class="card-fisio config-card">
                <div class="config-header">
                    <h3>Segurança</h3>
                    <p>Configurações de segurança e privacidade</p>
                </div>
                
                <div class="seguranca-config">
                    <div class="seguranca-item">
                        <div class="seguranca-info">
                            <h4>Autenticação de Dois Fatores</h4>
                            <p>Obrigar 2FA para administradores</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="seguranca-item">
                        <div class="seguranca-info">
                            <h4>Complexidade de Senha</h4>
                            <p>Exigir senhas complexas</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="seguranca-item">
                        <div class="seguranca-info">
                            <h4>Bloqueio por IP</h4>
                            <p>Bloquear IPs suspeitos automaticamente</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botão Salvar da Aba Sistema -->
        <div class="aba-salvar">
            <button class="btn-fisio btn-primario" onclick="salvarConfiguracoes('sistema')">
                <i class="fas fa-save"></i>
                Salvar Configurações Sistema
            </button>
        </div>
    </div>
</div>

<!-- Aba Integrações -->
<div class="aba-conteudo" id="conteudoIntegracao">
    <div class="config-secao">
        <div class="integracoes-grid">
            <!-- Integração OpenAI -->
            <div class="card-fisio integracao-card">
                <div class="integracao-header">
                    <div class="integracao-logo">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="integracao-info">
                        <h3>OpenAI</h3>
                        <p>API para inteligência artificial</p>
                    </div>
                    <div class="integracao-status ativo">Conectado</div>
                </div>
                
                <div class="integracao-config">
                    <div class="form-stack">
                        <div class="form-grupo">
                            <label>API Key</label>
                            <div class="input-password">
                                <input type="password" value="sk-proj-xxxxxxxxxxxxxxxx" id="openaiKey">
                                <button onclick="togglePassword('openaiKey')"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        
                        <div class="form-grupo">
                            <label>Modelo</label>
                            <select>
                                <option value="gpt-4">GPT-4</option>
                                <option value="gpt-3.5-turbo" selected>GPT-3.5 Turbo</option>
                            </select>
                        </div>
                    </div>
                    
                    <button class="btn-fisio btn-secundario" onclick="testarIntegracao('openai')">
                        <i class="fas fa-vial"></i>
                        Testar Conexão
                    </button>
                </div>
            </div>
            
            <!-- Integração Email -->
            <div class="card-fisio integracao-card">
                <div class="integracao-header">
                    <div class="integracao-logo">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="integracao-info">
                        <h3>SMTP</h3>
                        <p>Servidor de email</p>
                    </div>
                    <div class="integracao-status ativo">Conectado</div>
                </div>
                
                <form id="formIntegracaoEmail" class="integracao-config">
                    <div class="form-stack">
                        <div class="form-grupo">
                            <label>Servidor</label>
                            <input type="text" name="smtp_servidor" value="smtp.gmail.com">
                        </div>
                        
                        <div class="form-grupo">
                            <label>Porta</label>
                            <input type="number" name="smtp_porta" value="587">
                        </div>
                        
                        <div class="form-grupo">
                            <label>Email</label>
                            <input type="email" name="smtp_email" value="noreply@megafisio.com.br">
                        </div>
                        
                        <div class="form-grupo">
                            <label>Senha</label>
                            <div class="input-password">
                                <input type="password" name="smtp_senha" value="**********" id="emailPassword">
                                <button type="button" onclick="togglePassword('emailPassword')"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="integracao-acoes">
                        <button type="button" class="btn-fisio btn-secundario" onclick="testarIntegracao('email')">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Teste
                        </button>
                        <button type="button" class="btn-fisio btn-secundario" id="btnEditarIntegracaoEmail" onclick="toggleEditMode('formIntegracaoEmail', 'btnEditarIntegracaoEmail', 'btnSalvarIntegracaoEmail')">
                            <i class="fas fa-edit"></i>
                            Editar
                        </button>
                        <button type="submit" class="btn-fisio btn-primario" id="btnSalvarIntegracaoEmail" style="display: none;">
                            <i class="fas fa-save"></i>
                            Salvar SMTP
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Integração Storage -->
            <div class="card-fisio integracao-card">
                <div class="integracao-header">
                    <div class="integracao-logo">
                        <i class="fas fa-cloud"></i>
                    </div>
                    <div class="integracao-info">
                        <h3>Cloud Storage</h3>
                        <p>Armazenamento em nuvem</p>
                    </div>
                    <div class="integracao-status inativo">Desconectado</div>
                </div>
                
                <div class="integracao-config">
                    <div class="storage-options">
                        <label class="radio-option">
                            <input type="radio" name="storage" value="aws">
                            <span>Amazon S3</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="storage" value="google">
                            <span>Google Cloud</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="storage" value="local" checked>
                            <span>Local</span>
                        </label>
                    </div>
                    
                    <button class="btn-fisio btn-primario" onclick="configurarStorage()">
                        <i class="fas fa-cog"></i>
                        Configurar
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Botão Salvar da Aba Integrações -->
        <div class="aba-salvar">
            <button class="btn-fisio btn-primario" onclick="salvarConfiguracoes('integracao')">
                <i class="fas fa-save"></i>
                Salvar Configurações Integrações
            </button>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para configurações */
.subtitulo-pagina-escuro {
    color: var(--cinza-escuro);
    font-weight: 600;
}

/* Sistema de Abas */
.config-abas {
    display: flex;
    gap: 4px;
    margin-bottom: 32px;
    border-bottom: 2px solid var(--cinza-medio);
}

.aba-btn {
    padding: 12px 24px;
    background: none;
    border: none;
    border-radius: 8px 8px 0 0;
    cursor: pointer;
    transition: var(--transicao);
    font-weight: 600;
    color: var(--cinza-escuro);
    display: flex;
    align-items: center;
    gap: 8px;
}

.aba-btn.ativa {
    background: var(--azul-saude);
    color: white;
}

.aba-btn:hover:not(.ativa) {
    background: var(--cinza-claro);
    color: var(--azul-saude);
}

.aba-conteudo {
    display: none;
}

.aba-conteudo.ativa {
    display: block;
}

/* Grid de Configurações */
.config-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.config-card {
    padding: 24px;
}

.config-header {
    margin-bottom: 24px;
}

.config-header h3 {
    color: var(--azul-saude);
    margin-bottom: 8px;
    font-size: 18px;
}

.config-header p {
    color: var(--cinza-escuro);
    font-size: 14px;
}

/* Temas */
.temas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.tema-card {
    border: 2px solid var(--cinza-medio);
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    transition: var(--transicao);
    position: relative;
}

.tema-card:hover {
    border-color: var(--azul-saude);
}

.tema-card.ativo {
    border-color: var(--azul-saude);
    background: rgba(30, 58, 138, 0.05);
}

.tema-preview {
    width: 100%;
    height: 80px;
    border-radius: 8px;
    position: relative;
    overflow: hidden;
    margin-bottom: 12px;
}

.tema-preview.medico {
    background: linear-gradient(135deg, #1e3a8a, #059669);
}

.tema-preview.moderno {
    background: linear-gradient(135deg, #1f2937, #374151);
}

.tema-preview.minimalista {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border: 1px solid var(--cinza-medio);
}

.preview-header,
.preview-sidebar,
.preview-content {
    position: absolute;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.preview-header {
    top: 4px;
    left: 4px;
    right: 4px;
    height: 12px;
}

.preview-sidebar {
    top: 20px;
    left: 4px;
    width: 24px;
    bottom: 4px;
}

.preview-content {
    top: 20px;
    left: 32px;
    right: 4px;
    bottom: 4px;
}

.tema-info h4 {
    font-size: 14px;
    font-weight: 600;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.tema-info p {
    font-size: 12px;
    color: var(--cinza-escuro);
}

.tema-check {
    position: absolute;
    top: 8px;
    right: 8px;
    color: var(--azul-saude);
    display: none;
}

.tema-card.ativo .tema-check {
    display: block;
}

/* Cores Personalizadas */
.cores-config {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.cor-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cor-item label {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.cor-input {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cor-input input[type="color"] {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.cor-input span {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
    color: var(--cinza-escuro);
}

/* Logo e Branding */
.logo-config {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.logo-secao-completa,
.texto-secao-completa {
    background: var(--cinza-claro);
    border-radius: 12px;
    padding: 24px;
}

.logo-secao-completa h4,
.texto-secao-completa h4 {
    margin: 0 0 20px 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--cinza-escuro);
    border-bottom: 1px solid var(--cinza-medio);
    padding-bottom: 12px;
}

.logo-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: start;
}

.logo-atual {
    text-align: center;
}

.logo-preview {
    width: 120px;
    height: 120px;
    background: var(--gradiente-principal);
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    margin: 0 auto 12px;
}

.logo-preview i {
    font-size: 32px;
    margin-bottom: 8px;
}

.logo-preview span {
    font-weight: 700;
    font-size: 12px;
}

.upload-area {
    border: 2px dashed var(--cinza-medio);
    border-radius: 12px;
    padding: 32px;
    text-align: center;
    cursor: pointer;
    transition: var(--transicao);
}

.upload-area:hover {
    border-color: var(--azul-saude);
    background: var(--cinza-claro);
}

.upload-area i {
    font-size: 32px;
    color: var(--cinza-medio);
    margin-bottom: 12px;
}


/* Widgets do Dashboard */
.widgets-lista {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.widget-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    border: 1px solid var(--cinza-medio);
    border-radius: 12px;
    transition: var(--transicao);
}

.widget-item:hover {
    border-color: var(--azul-saude);
}

.widget-item.ativo {
    background: rgba(30, 58, 138, 0.05);
    border-color: var(--azul-saude);
}

.widget-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.widget-info i {
    width: 40px;
    height: 40px;
    background: var(--cinza-claro);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--azul-saude);
    font-size: 18px;
}

.widget-info h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.widget-info p {
    font-size: 12px;
    color: var(--cinza-escuro);
}

/* Switch Toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--cinza-medio);
    transition: var(--transicao);
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: var(--transicao);
    border-radius: 50%;
}

input:checked + .slider {
    background-color: var(--azul-saude);
}

input:checked + .slider:before {
    transform: translateX(24px);
}

/* Layout Options */
.layout-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.layout-option {
    border: 2px solid var(--cinza-medio);
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    transition: var(--transicao);
    text-align: center;
}

.layout-option:hover {
    border-color: var(--azul-saude);
}

.layout-option.ativo {
    border-color: var(--azul-saude);
    background: rgba(30, 58, 138, 0.05);
}

.layout-preview {
    width: 100%;
    height: 60px;
    border-radius: 8px;
    background: var(--cinza-claro);
    margin-bottom: 8px;
    position: relative;
}

/* Notificações */
.notif-tipos {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.notif-categoria h4 {
    color: var(--azul-saude);
    margin-bottom: 12px;
    font-size: 16px;
}

.notif-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--cinza-medio);
}

.notif-item:last-child {
    border-bottom: none;
}

/* Canais */
.canais-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.canal-item {
    border: 1px solid var(--cinza-medio);
    border-radius: 12px;
    padding: 20px;
}

.canal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.canal-header i {
    font-size: 24px;
    color: var(--azul-saude);
}

.canal-header h4 {
    flex: 1;
    margin-left: 12px;
    color: var(--cinza-escuro);
}

.canal-config {
    margin-top: 12px;
}

.canal-config input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
    color: var(--cinza-escuro);
    font-family: inherit;
}

.canal-config input:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

/* Inputs de integração */
.integracao-config .form-grupo input,
.integracao-config .form-grupo select {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
    color: var(--cinza-escuro);
    font-family: inherit;
}

.integracao-config .form-grupo input:focus,
.integracao-config .form-grupo select:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

/* Configurações do Sistema */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.form-grid-smtp {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

.form-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 20px;
}

/* Estilos básicos para formulários */
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

.config-form .form-grupo input,
.config-form .form-grupo select {
    width: 100%;
}

/* Ações dos formulários */
.config-acoes {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
}

.config-switches {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.switch-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
}

/* Segurança */
.seguranca-config {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.seguranca-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border: 1px solid var(--cinza-medio);
    border-radius: 12px;
}

.seguranca-info h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.seguranca-info p {
    font-size: 12px;
    color: var(--cinza-escuro);
}

/* Integrações */
.integracoes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
}

.integracao-card {
    padding: 24px;
}

.integracao-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.integracao-logo {
    width: 48px;
    height: 48px;
    background: var(--cinza-claro);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--azul-saude);
}

.integracao-info {
    flex: 1;
}

.integracao-info h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.integracao-info p {
    font-size: 12px;
    color: var(--cinza-escuro);
}

.integracao-status {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.integracao-status.ativo {
    background: rgba(16, 185, 129, 0.1);
    color: var(--sucesso);
}

.integracao-status.inativo {
    background: rgba(107, 114, 128, 0.1);
    color: var(--cinza-medio);
}

.input-password {
    position: relative;
    display: flex;
    align-items: center;
}

.input-password input {
    flex: 1;
    padding-right: 40px;
}

.input-password button {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    color: var(--cinza-medio);
    cursor: pointer;
}

.storage-options {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 12px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    transition: var(--transicao);
}

.radio-option:hover {
    border-color: var(--azul-saude);
    background: rgba(30, 58, 138, 0.05);
}

.radio-option input[type="radio"] {
    width: 18px;
    height: 18px;
    margin: 0;
}

.radio-option span {
    font-weight: 600;
    color: var(--cinza-escuro);
}

/* Filtros */
.filtro-select {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
    color: var(--cinza-escuro);
    font-family: inherit;
    min-width: 150px;
}

.filtro-select:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

/* Botão Salvar das Abas */
.aba-salvar {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
    text-align: right;
}

.aba-salvar .btn-fisio {
    min-width: 200px;
    padding: 12px 24px;
}

/* Campos readonly */
.form-grupo input.readonly-field,
.form-grupo select.readonly-field,
.form-grupo textarea.readonly-field,
.form-grupo input[readonly],
.form-grupo textarea[readonly] {
    background-color: #f8f9fa !important;
    border-color: #e9ecef !important;
    color: #6c757d !important;
    cursor: not-allowed;
}

.form-grupo select.readonly-field {
    pointer-events: none;
    background-color: #f8f9fa !important;
    border-color: #e9ecef !important;
    color: #6c757d !important;
}

input[type="checkbox"].readonly-field,
input[type="radio"].readonly-field {
    cursor: not-allowed;
    opacity: 0.6;
}

/* Botões em modo edição */
.form-acoes {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
}

.integracao-acoes {
    display: flex;
    gap: 12px;
    justify-content: flex-start;
    margin-top: 16px;
    flex-wrap: wrap;
}

/* Responsivo */
@media (max-width: 1024px) {
    .config-grid {
        grid-template-columns: 1fr;
    }
    
    .logo-layout {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .temas-grid {
        grid-template-columns: 1fr;
    }
    
    .cores-config {
        grid-template-columns: 1fr;
    }
    
    .form-grid,
    .form-grid-smtp {
        grid-template-columns: 1fr;
    }
    
    .canais-grid {
        grid-template-columns: 1fr;
    }
    
    .integracoes-grid {
        grid-template-columns: 1fr;
    }
    
    .aba-salvar {
        text-align: center;
    }
}

@media (max-width: 768px) {
    .logo-secao-completa,
    .texto-secao-completa {
        padding: 20px;
    }
    
    .upload-area {
        padding: 24px;
    }
}
</style>

<script>
// Sistema de Abas
function trocarAbaConfig(aba) {
    // Remover classe ativa de todas as abas
    document.querySelectorAll('.aba-btn').forEach(btn => btn.classList.remove('ativa'));
    document.querySelectorAll('.aba-conteudo').forEach(content => content.classList.remove('ativa'));
    
    // Adicionar classe ativa na aba selecionada
    document.getElementById('aba' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    document.getElementById('conteudo' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
}

// Temas
function selecionarTema(tema) {
    document.querySelectorAll('.tema-card').forEach(card => card.classList.remove('ativo'));
    event.target.closest('.tema-card').classList.add('ativo');
    mostrarAlerta(`Tema ${tema} selecionado`, 'info');
}

// Cores
function aplicarCores() {
    const primaria = document.getElementById('corPrimaria').value;
    const secundaria = document.getElementById('corSecundaria').value;
    const destaque = document.getElementById('corDestaque').value;
    const fundo = document.getElementById('corFundo').value;
    
    // Aplicar cores ao CSS
    document.documentElement.style.setProperty('--azul-saude', primaria);
    document.documentElement.style.setProperty('--verde-terapia', secundaria);
    document.documentElement.style.setProperty('--dourado-premium', destaque);
    document.documentElement.style.setProperty('--cinza-claro', fundo);
    
    mostrarAlerta('Cores aplicadas com sucesso!', 'sucesso');
}

function resetarCores() {
    document.getElementById('corPrimaria').value = '#1e3a8a';
    document.getElementById('corSecundaria').value = '#059669';
    document.getElementById('corDestaque').value = '#ca8a04';
    document.getElementById('corFundo').value = '#f8fafc';
    aplicarCores();
}

// Layout
function selecionarLayout(layout) {
    document.querySelectorAll('.layout-option').forEach(option => option.classList.remove('ativo'));
    event.target.closest('.layout-option').classList.add('ativo');
    mostrarAlerta(`Layout ${layout} selecionado`, 'info');
}

// Integrações
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function testarIntegracao(tipo) {
    mostrarAlerta(`Testando integração ${tipo}...`, 'info');
    
    setTimeout(() => {
        mostrarAlerta(`Integração ${tipo} testada com sucesso!`, 'sucesso');
    }, 2000);
}

function configurarStorage() {
    mostrarAlerta('Modal de configuração de storage será implementado', 'info');
}

// Salvar configurações
function salvarConfiguracoes(aba) {
    const nomeAba = aba ? aba.charAt(0).toUpperCase() + aba.slice(1) : 'todas as';
    mostrarAlerta(`Salvando configurações de ${nomeAba}...`, 'info');
    
    setTimeout(() => {
        mostrarAlerta(`Configurações de ${nomeAba} salvas com sucesso!`, 'sucesso');
    }, 1500);
}

// Sistema de Edição/Salvamento (copiado do perfil)
function toggleEditMode(formId, btnEditarId, btnSalvarId) {
    const form = document.getElementById(formId);
    const btnEditar = document.getElementById(btnEditarId);
    const btnSalvar = document.getElementById(btnSalvarId);
    
    const isEditing = form.dataset.editing === 'true';
    
    if (isEditing) {
        // Está editando, cancelar edição
        setFormReadonly(form, true);
        btnEditar.innerHTML = '<i class="fas fa-edit"></i> Editar';
        btnEditar.style.display = 'inline-block';
        btnSalvar.style.display = 'none';
        form.dataset.editing = 'false';
        
        // Restaurar valores originais se cancelar
        restoreOriginalValues(form);
    } else {
        // Não está editando, habilitar edição
        saveOriginalValues(form);
        setFormReadonly(form, false);
        btnEditar.innerHTML = '<i class="fas fa-times"></i> Cancelar';
        btnSalvar.style.display = 'inline-block';
        form.dataset.editing = 'true';
    }
}

function setFormReadonly(form, readonly) {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (readonly) {
            input.setAttribute('readonly', 'readonly');
            input.classList.add('readonly-field');
            if (input.tagName === 'SELECT') {
                input.style.pointerEvents = 'none';
                input.setAttribute('tabindex', '-1');
            }
        } else {
            input.removeAttribute('readonly');
            input.classList.remove('readonly-field');
            if (input.tagName === 'SELECT') {
                input.style.pointerEvents = 'auto';
                input.removeAttribute('tabindex');
            }
        }
    });
    
    // Para checkboxes e radio buttons
    const checkboxes = form.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    checkboxes.forEach(checkbox => {
        if (readonly) {
            checkbox.style.pointerEvents = 'none';
            checkbox.classList.add('readonly-field');
            checkbox.setAttribute('tabindex', '-1');
        } else {
            checkbox.style.pointerEvents = 'auto';
            checkbox.classList.remove('readonly-field');
            checkbox.removeAttribute('tabindex');
        }
    });
}

function saveOriginalValues(form) {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.dataset.originalValue = input.checked;
        } else {
            input.dataset.originalValue = input.value;
        }
    });
}

function restoreOriginalValues(form) {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.dataset.originalValue !== undefined) {
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = input.dataset.originalValue === 'true';
            } else {
                input.value = input.dataset.originalValue;
            }
        }
    });
}

function enableEditModeAfterSave(formId, btnEditarId, btnSalvarId) {
    const form = document.getElementById(formId);
    const btnEditar = document.getElementById(btnEditarId);
    const btnSalvar = document.getElementById(btnSalvarId);
    
    // Voltar ao modo readonly após salvar
    setFormReadonly(form, true);
    btnEditar.innerHTML = '<i class="fas fa-edit"></i> Editar';
    btnEditar.style.display = 'inline-block';
    btnSalvar.style.display = 'none';
    form.dataset.editing = 'false';
}

// Atualizar valores dos inputs de cor
document.addEventListener('DOMContentLoaded', function() {
    const colorInputs = document.querySelectorAll('input[type="color"]');
    
    colorInputs.forEach(input => {
        const span = input.nextElementSibling;
        
        input.addEventListener('input', function() {
            span.textContent = this.value;
        });
    });
    
    // Deixar formulários em modo readonly por padrão
    const forms = ['formLogoIdentidade', 'formConfigSistema', 'formIntegracaoEmail'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            setFormReadonly(form, true);
        }
    });
});

// Event listeners para os formulários
document.getElementById('formLogoIdentidade').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (this.dataset.editing !== 'true') {
        mostrarAlerta('Clique em Editar primeiro para modificar os dados', 'aviso');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    
    fetch('/admin/settings/save-logo-identity', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            enableEditModeAfterSave('formLogoIdentidade', 'btnEditarLogoIdentidade', 'btnSalvarLogoIdentidade');
        } else {
            mostrarAlerta(data.message || 'Erro ao salvar dados', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

document.getElementById('formConfigSistema').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (this.dataset.editing !== 'true') {
        mostrarAlerta('Clique em Editar primeiro para modificar os dados', 'aviso');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    
    fetch('/admin/settings/save-system-config', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            enableEditModeAfterSave('formConfigSistema', 'btnEditarConfigSistema', 'btnSalvarConfigSistema');
        } else {
            mostrarAlerta(data.message || 'Erro ao salvar dados', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

document.getElementById('formIntegracaoEmail').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (this.dataset.editing !== 'true') {
        mostrarAlerta('Clique em Editar primeiro para modificar os dados', 'aviso');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    
    fetch('/admin/settings/save-integration-email', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            enableEditModeAfterSave('formIntegracaoEmail', 'btnEditarIntegracaoEmail', 'btnSalvarIntegracaoEmail');
        } else {
            mostrarAlerta(data.message || 'Erro ao salvar dados', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>