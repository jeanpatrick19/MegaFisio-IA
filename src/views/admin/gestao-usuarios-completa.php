<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Gestão Completa de Usuários</h1>
<p class="subtitulo-pagina-escuro">Administre usuários, permissões, acessos e conformidade com LGPD</p>

<!-- Abas de Navegação -->
<div class="usuarios-abas">
    <button class="aba-btn ativa" onclick="trocarAba('usuarios')" id="abaUsuarios">
        <i class="fas fa-users"></i>
        Usuários
    </button>
    <button class="aba-btn" onclick="trocarAba('permissoes')" id="abaPermissoes">
        <i class="fas fa-shield-alt"></i>
        Permissões
    </button>
    <button class="aba-btn" onclick="trocarAba('acessos')" id="abaAcessos">
        <i class="fas fa-history"></i>
        Logs de Acesso
    </button>
    <button class="aba-btn" onclick="trocarAba('lgpd')" id="abaLGPD">
        <i class="fas fa-gavel"></i>
        LGPD
    </button>
    <button class="aba-btn" onclick="trocarAba('relatorios')" id="abaRelatorios">
        <i class="fas fa-chart-bar"></i>
        Relatórios
    </button>
</div>

<!-- Aba Usuários -->
<div class="aba-conteudo ativa" id="conteudoUsuarios">
    <!-- Estatísticas dos Usuários -->
    <div class="usuarios-stats">
        <div class="stat-card-usuario admin">
            <div class="stat-icone-usuario admin">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero"><?= $stats['admins'] ?? 2 ?></div>
                <div class="stat-label-escuro">Administradores</div>
            </div>
        </div>
        
        <div class="stat-card-usuario fisio">
            <div class="stat-icone-usuario fisio">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero"><?= $stats['professionals'] ?? 15 ?></div>
                <div class="stat-label-escuro">Fisioterapeutas</div>
            </div>
        </div>
        
        <div class="stat-card-usuario online">
            <div class="stat-icone-usuario online">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero"><?= $stats['online'] ?? 8 ?></div>
                <div class="stat-label-escuro">Online Agora</div>
            </div>
        </div>
        
        <div class="stat-card-usuario bloqueado">
            <div class="stat-icone-usuario bloqueado">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero"><?= $stats['blocked'] ?? 1 ?></div>
                <div class="stat-label-escuro">Bloqueados</div>
            </div>
        </div>
    </div>

    <!-- Ações e Filtros -->
    <div class="usuarios-acoes">
        <div class="acoes-esquerda">
            <button class="btn-fisio btn-primario" onclick="abrirModalNovoUsuario()">
                <i class="fas fa-user-plus"></i>
                Novo Usuário
            </button>
            
            <button class="btn-fisio btn-secundario" onclick="importarUsuarios()">
                <i class="fas fa-file-excel"></i>
                Importar CSV
            </button>
            
            <button class="btn-fisio btn-secundario" onclick="abrirModalConvite()">
                <i class="fas fa-envelope"></i>
                Enviar Convite
            </button>
        </div>
        
        <div class="acoes-direita">
            <div class="filtro-grupo">
                <select class="filtro-select" id="filtroRole" onchange="filtrarUsuarios()">
                    <option value="">Todos os Perfis</option>
                    <option value="admin">Administradores</option>
                    <option value="professional">Fisioterapeutas</option>
                </select>
            </div>
            
            <div class="filtro-grupo">
                <select class="filtro-select" id="filtroStatus" onchange="filtrarUsuarios()">
                    <option value="">Todos os Status</option>
                    <option value="active">Ativos</option>
                    <option value="inactive">Inativos</option>
                    <option value="blocked">Bloqueados</option>
                    <option value="pending">Pendentes</option>
                </select>
            </div>
            
            <div class="busca-grupo">
                <div class="busca-input">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar usuários..." id="buscaUsuario" onkeyup="buscarUsuarios()">
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Usuários Expandida -->
    <div class="card-fisio usuarios-lista-container">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-users"></i>
                <span>Usuários Cadastrados</span>
                <span class="contador-usuarios" id="contadorUsuarios">(17 usuários)</span>
            </div>
            <div class="opcoes-lista">
                <button class="btn-opcao" onclick="exportarUsuarios()">
                    <i class="fas fa-download"></i>
                </button>
                <button class="btn-opcao" onclick="atualizarLista()">
                    <i class="fas fa-sync"></i>
                </button>
            </div>
        </div>
        
        <div class="usuarios-lista-expandida" id="usuariosLista">
            <!-- Usuario exemplo -->
            <div class="usuario-item-expandido" data-status="active" data-role="admin">
                <div class="usuario-principal">
                    <div class="usuario-avatar">
                        <div class="avatar-circulo admin">AD</div>
                        <div class="status-indicador active"></div>
                    </div>
                    
                    <div class="usuario-info">
                        <div class="usuario-nome">Dr. Admin Sistema</div>
                        <div class="usuario-email">admin@megafisio.com.br</div>
                        <div class="usuario-meta">
                            <span class="usuario-role">Administrador</span>
                            <span class="usuario-crefito">CREFITO: 123456-F</span>
                        </div>
                    </div>
                    
                    <div class="usuario-dados-expandidos">
                        <div class="dado-item">
                            <span class="dado-label">Último Acesso</span>
                            <span class="dado-valor">Agora</span>
                        </div>
                        <div class="dado-item">
                            <span class="dado-label">IP</span>
                            <span class="dado-valor">192.168.1.100</span>
                        </div>
                        <div class="dado-item">
                            <span class="dado-label">Sessões</span>
                            <span class="dado-valor">15</span>
                        </div>
                        <div class="dado-item">
                            <span class="dado-label">Avaliações IA</span>
                            <span class="dado-valor">340</span>
                        </div>
                    </div>
                    
                    <div class="usuario-acoes">
                        <button class="btn-acao editar" onclick="editarUsuario(1)" data-tooltip="Editar usuário">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-acao permissoes" onclick="gerenciarPermissoes(1)" data-tooltip="Gerenciar permissões">
                            <i class="fas fa-shield-alt"></i>
                        </button>
                        <button class="btn-acao logs" onclick="verLogsUsuario(1)" data-tooltip="Ver logs">
                            <i class="fas fa-history"></i>
                        </button>
                        <button class="btn-acao email" onclick="enviarEmail(1)" data-tooltip="Enviar email">
                            <i class="fas fa-envelope"></i>
                        </button>
                        <button class="btn-acao pausar" onclick="alterarStatusUsuario(1, 'active')" data-tooltip="Bloquear">
                            <i class="fas fa-ban"></i>
                        </button>
                    </div>
                </div>
                
                <div class="usuario-detalhes" style="display: none;">
                    <div class="detalhes-grid">
                        <div class="detalhe-secao">
                            <h5>Informações Pessoais</h5>
                            <div class="detalhe-item">
                                <span>Telefone:</span>
                                <span>(11) 99999-9999</span>
                            </div>
                            <div class="detalhe-item">
                                <span>Especialidade:</span>
                                <span>Administração</span>
                            </div>
                            <div class="detalhe-item">
                                <span>Cadastro:</span>
                                <span>15/01/2024 10:30</span>
                            </div>
                        </div>
                        
                        <div class="detalhe-secao">
                            <h5>Segurança</h5>
                            <div class="detalhe-item">
                                <span>2FA:</span>
                                <span class="status-ativo">Ativo</span>
                            </div>
                            <div class="detalhe-item">
                                <span>Tentativas de Login:</span>
                                <span>0 falhas</span>
                            </div>
                            <div class="detalhe-item">
                                <span>Senha Alterada:</span>
                                <span>20/01/2024</span>
                            </div>
                        </div>
                        
                        <div class="detalhe-secao">
                            <h5>LGPD</h5>
                            <div class="detalhe-item">
                                <span>Consentimento:</span>
                                <span class="status-ativo">Aceito</span>
                            </div>
                            <div class="detalhe-item">
                                <span>Data Aceite:</span>
                                <span>15/01/2024 10:35</span>
                            </div>
                            <div class="detalhe-item">
                                <span>Dados Exportados:</span>
                                <span>Nunca</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aba Permissões -->
<div class="aba-conteudo" id="conteudoPermissoes">
    <div class="permissoes-container">
        <div class="permissoes-header">
            <h3>Sistema de Permissões</h3>
            <button class="btn-fisio btn-primario" onclick="abrirModalNovaPermissao()">
                <i class="fas fa-plus"></i>
                Nova Permissão
            </button>
        </div>
        
        <div class="permissoes-grid">
            <div class="card-fisio permissao-grupo">
                <h4>Administradores</h4>
                <div class="permissao-lista">
                    <div class="permissao-item ativa">
                        <i class="fas fa-check-circle"></i>
                        <span>Gerenciar usuários</span>
                    </div>
                    <div class="permissao-item ativa">
                        <i class="fas fa-check-circle"></i>
                        <span>Configurar sistema</span>
                    </div>
                    <div class="permissao-item ativa">
                        <i class="fas fa-check-circle"></i>
                        <span>Ver relatórios</span>
                    </div>
                    <div class="permissao-item ativa">
                        <i class="fas fa-check-circle"></i>
                        <span>Gerenciar IA</span>
                    </div>
                </div>
            </div>
            
            <div class="card-fisio permissao-grupo">
                <h4>Fisioterapeutas</h4>
                <div class="permissao-lista">
                    <div class="permissao-item ativa">
                        <i class="fas fa-check-circle"></i>
                        <span>Usar assistente IA</span>
                    </div>
                    <div class="permissao-item ativa">
                        <i class="fas fa-check-circle"></i>
                        <span>Ver próprio perfil</span>
                    </div>
                    <div class="permissao-item inativa">
                        <i class="fas fa-times-circle"></i>
                        <span>Gerenciar usuários</span>
                    </div>
                    <div class="permissao-item inativa">
                        <i class="fas fa-times-circle"></i>
                        <span>Configurar sistema</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aba Logs de Acesso -->
<div class="aba-conteudo" id="conteudoAcessos">
    <div class="logs-container">
        <div class="logs-filtros">
            <input type="date" id="dataInicio" class="filtro-select">
            <input type="date" id="dataFim" class="filtro-select">
            <select class="filtro-select" id="filtroTipoLog">
                <option value="">Todos os Tipos</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="action">Ação</option>
                <option value="error">Erro</option>
            </select>
            <button class="btn-fisio btn-secundario" onclick="filtrarLogs()">
                <i class="fas fa-filter"></i>
                Filtrar
            </button>
        </div>
        
        <div class="card-fisio logs-lista">
            <div class="log-item">
                <div class="log-timestamp">25/01/2024 14:30:15</div>
                <div class="log-usuario">Dr. Admin Sistema</div>
                <div class="log-acao">Login realizado</div>
                <div class="log-ip">192.168.1.100</div>
                <div class="log-status status-sucesso">Sucesso</div>
            </div>
            <!-- Mais logs... -->
        </div>
    </div>
</div>

<!-- Aba LGPD -->
<div class="aba-conteudo" id="conteudoLGPD">
    <div class="lgpd-container">
        <div class="lgpd-stats">
            <div class="stat-card-lgpd">
                <div class="stat-icone-lgpd consentimento">
                    <i class="fas fa-check-shield"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-numero">17</div>
                    <div class="stat-label-escuro">Consentimentos</div>
                </div>
            </div>
            
            <div class="stat-card-lgpd">
                <div class="stat-icone-lgpd export">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-numero">3</div>
                    <div class="stat-label-escuro">Exportações</div>
                </div>
            </div>
            
            <div class="stat-card-lgpd">
                <div class="stat-icone-lgpd exclusao">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-numero">0</div>
                    <div class="stat-label-escuro">Exclusões</div>
                </div>
            </div>
        </div>
        
        <div class="lgpd-acoes">
            <button class="btn-fisio btn-primario" onclick="gerarRelatorioLGPD()">
                <i class="fas fa-file-alt"></i>
                Relatório LGPD
            </button>
            <button class="btn-fisio btn-secundario" onclick="exportarDadosUsuario()">
                <i class="fas fa-download"></i>
                Exportar Dados
            </button>
            <button class="btn-fisio btn-secundario" onclick="revogarConsentimento()">
                <i class="fas fa-ban"></i>
                Revogar Consentimento
            </button>
        </div>
    </div>
</div>

<!-- Aba Relatórios -->
<div class="aba-conteudo" id="conteudoRelatorios">
    <div class="relatorios-container">
        <div class="relatorios-grid">
            <div class="card-fisio relatorio-card">
                <h4>Usuários Ativos</h4>
                <canvas id="graficoUsuariosAtivos" width="400" height="200"></canvas>
            </div>
            
            <div class="card-fisio relatorio-card">
                <h4>Acessos por Dia</h4>
                <canvas id="graficoAcessos" width="400" height="200"></canvas>
            </div>
            
            <div class="card-fisio relatorio-card">
                <h4>Uso por Especialidade</h4>
                <canvas id="graficoEspecialidades" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
/* Sistema de Abas */
.usuarios-abas {
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

/* Stat Card Bloqueado */
.stat-icone-usuario.bloqueado {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

/* Lista Expandida de Usuários */
.usuario-item-expandido {
    border: 1px solid var(--cinza-medio);
    border-radius: 16px;
    margin-bottom: 16px;
    transition: var(--transicao);
}

.usuario-item-expandido:hover {
    border-color: var(--azul-saude);
    box-shadow: var(--sombra-media);
}

.usuario-principal {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    cursor: pointer;
}

.usuario-dados-expandidos {
    display: flex;
    gap: 24px;
}

.usuario-detalhes {
    padding: 20px;
    border-top: 1px solid var(--cinza-medio);
    background: var(--cinza-claro);
}

.detalhes-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.detalhe-secao h5 {
    color: var(--azul-saude);
    margin-bottom: 12px;
    font-size: 14px;
    font-weight: 700;
}

.detalhe-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--cinza-medio);
}

.detalhe-item:last-child {
    border-bottom: none;
}

.status-ativo {
    color: var(--sucesso);
    font-weight: 600;
}

/* Botões de Ação Expandidos */
.btn-acao.permissoes:hover {
    background: var(--lilas-cuidado);
    color: white;
}

.btn-acao.logs:hover {
    background: var(--dourado-premium);
    color: white;
}

.btn-acao.email:hover {
    background: var(--info);
    color: white;
}

/* Permissões */
.permissoes-container {
    padding: 24px;
}

.permissoes-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.permissoes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.permissao-grupo h4 {
    color: var(--azul-saude);
    margin-bottom: 16px;
}

.permissao-lista {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.permissao-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px;
    border-radius: 8px;
}

.permissao-item.ativa {
    background: rgba(16, 185, 129, 0.1);
    color: var(--sucesso);
}

.permissao-item.inativa {
    background: rgba(239, 68, 68, 0.1);
    color: var(--erro);
}

/* Logs */
.logs-container {
    padding: 24px;
}

.logs-filtros {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    align-items: center;
    flex-wrap: wrap;
}

.log-item {
    display: grid;
    grid-template-columns: 150px 200px 1fr 120px 100px;
    gap: 16px;
    padding: 12px 16px;
    border-bottom: 1px solid var(--cinza-medio);
    align-items: center;
}

.log-timestamp {
    font-size: 12px;
    color: var(--cinza-escuro);
    font-family: 'JetBrains Mono', monospace;
}

.log-status.status-sucesso {
    color: var(--sucesso);
    font-weight: 600;
}

/* LGPD */
.lgpd-container {
    padding: 24px;
}

.lgpd-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card-lgpd {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-medio);
}

.stat-icone-lgpd {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-icone-lgpd.consentimento {
    background: linear-gradient(135deg, #059669, #10b981);
}

.stat-icone-lgpd.export {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
}

.stat-icone-lgpd.exclusao {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.lgpd-acoes {
    display: flex;
    gap: 16px;
}

/* Relatórios */
.relatorios-container {
    padding: 24px;
}

.relatorios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.relatorio-card {
    padding: 24px;
}

.relatorio-card h4 {
    color: var(--azul-saude);
    margin-bottom: 16px;
}

/* Estilos para campos de formulário */
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

/* Filtros e campos de busca */
.filtro-grupo {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

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
    color: var(--cinza-escuro);
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
    color: var(--cinza-escuro);
}

/* Ações dos usuários */
.usuarios-acoes {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 20px;
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

/* Statistics cards */
.usuarios-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card-usuario {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-medio);
    transition: var(--transicao);
}

.stat-card-usuario:hover {
    transform: translateY(-2px);
    box-shadow: var(--sombra-forte);
}

.stat-icone-usuario {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-icone-usuario.admin {
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
}

.stat-icone-usuario.fisio {
    background: linear-gradient(135deg, #059669, #10b981);
}

.stat-icone-usuario.online {
    background: linear-gradient(135deg, #ca8a04, #eab308);
}

.stat-icone-usuario.bloqueado {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.stat-numero {
    font-size: 24px;
    font-weight: 800;
    color: var(--cinza-escuro);
    font-family: 'JetBrains Mono', monospace;
    line-height: 1;
}

.stat-label-escuro {
    font-size: 14px;
    color: var(--cinza-escuro);
    font-weight: 600;
    margin-top: 4px;
}

/* Container da lista */
.usuarios-lista-container {
    margin-top: 24px;
}

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

.contador-usuarios {
    color: var(--cinza-escuro);
    font-weight: 400;
    font-size: 14px;
    margin-left: 8px;
}

/* Lista expandida */
.usuarios-lista-expandida {
    margin-top: 16px;
}

/* Avatares dos usuários */
.usuario-avatar {
    position: relative;
}

.avatar-circulo {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
    font-size: 16px;
}

.avatar-circulo.admin {
    background: var(--azul-saude);
}

.avatar-circulo.fisio {
    background: var(--verde-terapia);
}

.status-indicador {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
}

.status-indicador.active {
    background: var(--sucesso);
}

.status-indicador.inactive {
    background: var(--cinza-medio);
}

/* Informações do usuário */
.usuario-info {
    flex: 1;
}

.usuario-nome {
    font-size: 16px;
    font-weight: 700;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.usuario-email {
    font-size: 14px;
    color: var(--cinza-escuro);
    margin-bottom: 8px;
}

.usuario-meta {
    display: flex;
    gap: 16px;
    align-items: center;
}

.usuario-role {
    font-size: 12px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 12px;
    background: var(--azul-saude);
    color: white;
}

.usuario-crefito {
    font-size: 12px;
    color: var(--cinza-escuro);
}

/* Dados expandidos */
.dado-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    text-align: center;
}

.dado-label {
    font-size: 12px;
    color: var(--cinza-escuro);
    font-weight: 600;
}

.dado-valor {
    font-size: 16px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

/* Correções adicionais para elementos de texto */
.detalhe-item span,
.log-usuario,
.log-acao,
.log-ip,
.permissao-item span {
    color: var(--cinza-escuro);
}

.usuario-detalhes span,
.usuario-info p,
.usuario-meta span {
    color: var(--cinza-escuro);
}

/* Elementos específicos das abas */
.detalhe-secao h5,
.detalhe-secao span:first-child {
    color: var(--cinza-escuro);
}

.permissao-grupo h4,
.permissao-lista span {
    color: var(--cinza-escuro);
}

.lgpd-container span,
.lgpd-container p {
    color: var(--cinza-escuro);
}

.relatorios-container span,
.relatorios-container p {
    color: var(--cinza-escuro);
}

/* Ações dos usuários */
.usuario-acoes {
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
    transform: translateY(-2px);
}

.btn-acao.editar:hover {
    background: var(--info);
    color: white;
}

.btn-acao.pausar:hover {
    background: var(--erro);
    color: white;
}

/* Responsivo */
@media (max-width: 1024px) {
    .detalhes-grid {
        grid-template-columns: 1fr;
    }
    
    .usuario-dados-expandidos {
        flex-direction: column;
        gap: 8px;
    }
    
    .relatorios-grid {
        grid-template-columns: 1fr;
    }
    
    .usuarios-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .acoes-direita {
        flex-direction: column;
        gap: 8px;
    }
    
    .busca-input {
        min-width: 200px;
    }
}

@media (max-width: 768px) {
    .usuarios-stats {
        grid-template-columns: 1fr;
    }
    
    .usuarios-acoes {
        flex-direction: column;
        align-items: stretch;
    }
    
    .acoes-esquerda {
        justify-content: center;
    }
    
    .acoes-direita {
        justify-content: center;
    }
}
</style>

<script>
// Sistema de Abas
function trocarAba(aba) {
    // Remover classe ativa de todas as abas
    document.querySelectorAll('.aba-btn').forEach(btn => btn.classList.remove('ativa'));
    document.querySelectorAll('.aba-conteudo').forEach(content => content.classList.remove('ativa'));
    
    // Adicionar classe ativa na aba selecionada
    document.getElementById('aba' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    document.getElementById('conteudo' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
}

// Expandir/Colapsar detalhes do usuário
document.addEventListener('click', function(e) {
    if (e.target.closest('.usuario-principal')) {
        const item = e.target.closest('.usuario-item-expandido');
        const detalhes = item.querySelector('.usuario-detalhes');
        
        if (detalhes.style.display === 'none' || !detalhes.style.display) {
            detalhes.style.display = 'block';
        } else {
            detalhes.style.display = 'none';
        }
    }
});

// Funções específicas das abas
function gerenciarPermissoes(id) {
    trocarAba('permissoes');
    mostrarAlerta('Carregando permissões do usuário...', 'info');
}

function verLogsUsuario(id) {
    trocarAba('acessos');
    mostrarAlerta('Carregando logs do usuário...', 'info');
}

function enviarEmail(id) {
    mostrarAlerta('Modal de envio de email será implementado', 'info');
}

function abrirModalConvite() {
    mostrarAlerta('Modal de convite será implementado', 'info');
}

function abrirModalNovaPermissao() {
    mostrarAlerta('Modal de nova permissão será implementado', 'info');
}

function gerarRelatorioLGPD() {
    mostrarAlerta('Gerando relatório LGPD...', 'info');
}

function exportarDadosUsuario() {
    mostrarAlerta('Exportando dados do usuário...', 'info');
}

function revogarConsentimento() {
    if (confirm('Confirma a revogação do consentimento?')) {
        mostrarAlerta('Consentimento revogado', 'sucesso');
    }
}

function filtrarLogs() {
    mostrarAlerta('Filtrando logs...', 'info');
}

function atualizarLista() {
    mostrarAlerta('Lista atualizada', 'sucesso');
}

// Funções específicas da gestão de usuários
function abrirModalNovoUsuario() {
    mostrarAlerta('Modal de novo usuário será implementado', 'info');
}

function importarUsuarios() {
    mostrarAlerta('Funcionalidade de importação CSV será implementada', 'info');
}

function exportarUsuarios() {
    mostrarAlerta('Exportando lista de usuários...', 'info');
}

function editarUsuario(id) {
    mostrarAlerta(`Editando usuário ${id}...`, 'info');
}

function alterarStatusUsuario(id, status) {
    if (confirm(`Confirma a alteração de status do usuário ${id}?`)) {
        mostrarAlerta(`Status do usuário ${id} alterado para ${status}`, 'sucesso');
    }
}

function filtrarUsuarios() {
    const role = document.getElementById('filtroRole').value;
    const status = document.getElementById('filtroStatus').value;
    
    mostrarAlerta(`Filtrando usuários: ${role || 'todos'} perfis, ${status || 'todos'} status`, 'info');
}

function buscarUsuarios() {
    const termo = document.getElementById('buscaUsuario').value;
    
    if (termo.length > 2) {
        mostrarAlerta(`Buscando por: ${termo}`, 'info');
    }
}

// Inicializar gráficos (placeholder)
document.addEventListener('DOMContentLoaded', function() {
    // Aqui seria implementada a inicialização dos gráficos
    // usando Chart.js ou similar
});
</script>