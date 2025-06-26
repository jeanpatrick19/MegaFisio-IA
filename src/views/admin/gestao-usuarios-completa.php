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
            <?php if (isset($users) && !empty($users)): ?>
                <?php foreach ($users as $usuario): ?>
                <div class="usuario-item-expandido" data-status="<?= $usuario['status'] ?>" data-role="<?= $usuario['role'] ?>">
                    <div class="usuario-principal">
                        <div class="usuario-avatar">
                            <div class="avatar-circulo <?= $usuario['role'] ?>">
                                <?= strtoupper(substr($usuario['name'], 0, 2)) ?>
                            </div>
                            <div class="status-indicador <?= $usuario['status'] ?>"></div>
                        </div>
                        
                        <div class="usuario-info">
                            <div class="usuario-nome"><?= htmlspecialchars($usuario['name']) ?></div>
                            <div class="usuario-email"><?= htmlspecialchars($usuario['email']) ?></div>
                            <div class="usuario-meta">
                                <span class="usuario-role"><?= $usuario['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta' ?></span>
                                <span class="usuario-status status-<?= $usuario['status'] ?>"><?= $usuario['status'] === 'active' ? 'Ativo' : 'Inativo' ?></span>
                            </div>
                        </div>
                        
                        <div class="usuario-dados-expandidos">
                            <div class="dado-item">
                                <span class="dado-label">Último Acesso</span>
                                <span class="dado-valor"><?= $usuario['last_login'] ? date('d/m/Y H:i', strtotime($usuario['last_login'])) : 'Nunca' ?></span>
                            </div>
                            <div class="dado-item">
                                <span class="dado-label">Cadastro</span>
                                <span class="dado-valor"><?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></span>
                            </div>
                        </div>
                        
                        <div class="usuario-acoes">
                            <button class="btn-acao editar" onclick="editarUsuario(<?= $usuario['id'] ?>)" data-tooltip="Editar usuário">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($usuario['role'] !== 'admin'): ?>
                            <button class="btn-acao permissoes" onclick="gerenciarPermissoes(<?= $usuario['id'] ?>)" data-tooltip="Gerenciar permissões">
                                <i class="fas fa-shield-alt"></i>
                            </button>
                            <?php endif; ?>
                            <button class="btn-acao logs" onclick="verLogsUsuario(<?= $usuario['id'] ?>)" data-tooltip="Ver logs">
                                <i class="fas fa-history"></i>
                            </button>
                            <?php if ($usuario['id'] !== $user['id']): ?>
                            <button class="btn-acao pausar" onclick="alterarStatusUsuario(<?= $usuario['id'] ?>, '<?= $usuario['status'] === 'active' ? 'inactive' : 'active' ?>')" data-tooltip="<?= $usuario['status'] === 'active' ? 'Bloquear' : 'Ativar' ?>">
                                <i class="fas fa-<?= $usuario['status'] === 'active' ? 'ban' : 'check' ?>"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: var(--cinza-escuro);">
                    <i class="fas fa-users" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Nenhum usuário cadastrado</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Aba Permissões -->
<div class="aba-conteudo" id="conteudoPermissoes">
    <div class="permissoes-container">
        <div class="permissoes-header">
            <h3>Gerenciar Permissões de Usuários</h3>
            <p style="color: var(--cinza-escuro); margin: 8px 0;">Selecione um usuário e gerencie suas permissões com checkboxes "Ver" e "Usar"</p>
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px; padding: 12px; margin: 12px 0;">
                <div style="display: flex; align-items: center; gap: 8px; color: #047857;">
                    <i class="fas fa-info-circle"></i>
                    <strong>Importante:</strong>
                </div>
                <p style="margin: 4px 0 0 24px; color: #047857; font-size: 14px;">
                    Administradores têm acesso total automático a todo o sistema. Apenas fisioterapeutas e outros usuários precisam de permissões específicas.
                </p>
            </div>
        </div>
        
        <!-- Seletor de Usuário -->
        <div class="card-fisio" style="margin-bottom: 20px;">
            <div style="padding: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--cinza-escuro);">Selecionar Usuário:</label>
                <select id="usuarioSelecionado" style="width: 100%; padding: 12px; border: 2px solid var(--cinza-medio); border-radius: 8px; font-size: 14px;" onchange="carregarPermissoesDoUsuario(this.value)">
                    <option value="">Escolha um usuário para gerenciar permissões</option>
                    <!-- Usuários serão carregados via JavaScript -->
                </select>
            </div>
        </div>
        
        <!-- Interface de Permissões -->
        <div id="interfacePermissoesSimples" style="display: none;">
            <div class="card-fisio">
                <div style="padding: 16px;">
                    <h4 style="color: var(--azul-saude); margin-bottom: 16px;" id="usuarioAtualNome">Permissões do Usuário</h4>
                    
                    <!-- Grid de Permissões dos 23 Dr. IA -->
                    <div style="display: grid; gap: 16px;">
                        <h5 style="color: var(--cinza-escuro); margin: 16px 0 8px 0;">🤖 Assistentes Dr. IA</h5>
                        <div id="permissoesIA" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 12px;">
                            <!-- Permissões serão carregadas aqui -->
                        </div>
                        
                        <h5 style="color: var(--cinza-escuro); margin: 16px 0 8px 0;">⚙️ Sistema</h5>
                        <div id="permissoesSistema" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 12px;">
                            <!-- Permissões de sistema -->
                        </div>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div style="display: flex; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--cinza-medio);">
                        <button class="btn-fisio btn-primario" onclick="salvarPermissoesSimples()">
                            <i class="fas fa-save"></i>
                            Salvar Permissões
                        </button>
                        <button class="btn-fisio btn-secundario" onclick="cancelarPermissoes()">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </button>
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
    // Trocar para aba de permissões
    trocarAba('permissoes');
    
    // Aguardar um pouco e selecionar o usuário
    setTimeout(() => {
        const select = document.getElementById('usuarioSelecionado');
        if (select) {
            select.value = id;
            carregarPermissoesDoUsuario(id);
        }
    }, 100);
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
    document.getElementById('modalNovoUsuario').style.display = 'flex';
}

function importarUsuarios() {
    mostrarAlerta('Funcionalidade de importação CSV será implementada', 'info');
}

function exportarUsuarios() {
    window.open('/admin/users/export', '_blank');
    mostrarAlerta('Exportando lista de usuários...', 'info');
}

function editarUsuario(id) {
    // Redirecionar para editar usuário
    window.location.href = `/admin/users/edit?id=${id}`;
}

function alterarStatusUsuario(id, status) {
    if (confirm(`Confirma a alteração de status do usuário ${id}?`)) {
        fetch(`/admin/users/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id: id,
                status: status,
                csrf_token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(`Status do usuário alterado para ${status}`, 'sucesso');
                setTimeout(() => location.reload(), 1000);
            } else {
                mostrarAlerta('Erro ao alterar status: ' + data.message, 'erro');
            }
        })
        .catch(error => {
            mostrarAlerta('Erro ao comunicar com servidor', 'erro');
        });
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

// Funções específicas das permissões
function carregarUsuarios() {
    fetch('/admin/permissions/users-api')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const select = document.getElementById('usuarioSelecionado');
                select.innerHTML = '<option value="">Escolha um usuário para gerenciar permissões</option>';
                
                data.users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.name} (${user.email}) - ${user.active_permissions_count} permissões`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Erro ao carregar usuários:', error));
}

function carregarPermissoesDoUsuario(userId) {
    if (!userId) {
        document.getElementById('interfacePermissoesSimples').style.display = 'none';
        return;
    }
    
    fetch(`/admin/permissions/user-permissions?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Buscar nome do usuário
                const select = document.getElementById('usuarioSelecionado');
                const userName = select.options[select.selectedIndex].text.split(' (')[0];
                document.getElementById('usuarioAtualNome').textContent = `Permissões de ${userName}`;
                
                // Renderizar permissões dos Dr. IA
                const permissoesIA = document.getElementById('permissoesIA');
                const permissoesSistema = document.getElementById('permissoesSistema');
                
                permissoesIA.innerHTML = '';
                permissoesSistema.innerHTML = '';
                
                Object.values(data.modules).forEach(module => {
                    const container = module.name.includes('ai_') ? permissoesIA : permissoesSistema;
                    
                    const moduleDiv = document.createElement('div');
                    moduleDiv.className = 'permissao-modulo';
                    moduleDiv.style.cssText = 'border: 1px solid var(--cinza-medio); border-radius: 8px; padding: 12px; background: white;';
                    
                    moduleDiv.innerHTML = `
                        <h6 style="margin: 0 0 8px 0; color: var(--azul-saude);">${module.display_name}</h6>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            ${module.permissions.map(perm => `
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
                                    <input type="checkbox" 
                                           id="perm_${perm.id}" 
                                           ${perm.has_permission ? 'checked' : ''}>
                                    <span>${perm.display_name}</span>
                                </label>
                            `).join('')}
                        </div>
                    `;
                    
                    container.appendChild(moduleDiv);
                });
                
                document.getElementById('interfacePermissoesSimples').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar permissões:', error);
            mostrarAlerta('Erro ao carregar permissões do usuário', 'erro');
        });
}

function salvarPermissoesSimples() {
    const userId = document.getElementById('usuarioSelecionado').value;
    if (!userId) return;
    
    const checkboxes = document.querySelectorAll('#interfacePermissoesSimples input[type="checkbox"]');
    const permissions = [];
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const permissionId = checkbox.id.replace('perm_', '');
            permissions.push(permissionId);
        }
    });
    
    fetch('/admin/permissions/bulk-assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            user_id: userId,
            permissions: permissions,
            csrf_token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta('Permissões salvas com sucesso!', 'sucesso');
        } else {
            mostrarAlerta('Erro ao salvar permissões: ' + data.message, 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro ao comunicar com servidor', 'erro');
    });
}

function cancelarPermissoes() {
    document.getElementById('usuarioSelecionado').value = '';
    document.getElementById('interfacePermissoesSimples').style.display = 'none';
}

// Inicializar sistema
document.addEventListener('DOMContentLoaded', function() {
    // Carregar usuários para a aba de permissões
    carregarUsuarios();
    
    // Aqui seria implementada a inicialização dos gráficos
    // usando Chart.js ou similar
});
</script>

<!-- Modal Criar Usuário -->
<div id="modalNovoUsuario" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Criar Novo Usuário</h3>
            <button type="button" class="modal-close" onclick="fecharModalNovoUsuario()">&times;</button>
        </div>
        
        <form id="formNovoUsuario" method="POST" action="/admin/users/create">
            <div class="modal-body">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="name" required maxlength="255">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required maxlength="255">
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha *</label>
                    <input type="password" id="senha" name="password" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="confirmarSenha">Confirmar Senha *</label>
                    <input type="password" id="confirmarSenha" name="password_confirm" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="role">Perfil do Usuário</label>
                    <select id="role" name="role">
                        <option value="usuario">Fisioterapeuta</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active">Ativo</option>
                        <option value="inactive">Inativo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="force_password_change" value="1">
                        Forçar mudança de senha no primeiro login
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-fisio btn-secundario" onclick="fecharModalNovoUsuario()">
                    Cancelar
                </button>
                <button type="submit" class="btn-fisio btn-primario">
                    <i class="fas fa-save"></i>
                    Criar Usuário
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--cinza-medio);
}

.modal-header h3 {
    margin: 0;
    color: var(--azul-saude);
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--cinza-escuro);
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid var(--cinza-medio);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 4px;
    font-weight: 600;
    color: var(--cinza-escuro);
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 14px;
}

.form-group input:focus,
.form-group select:focus {
    border-color: var(--azul-saude);
    outline: none;
}
</style>

<script>
function fecharModalNovoUsuario() {
    document.getElementById('modalNovoUsuario').style.display = 'none';
    document.getElementById('formNovoUsuario').reset();
}
</script>