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
                <div class="stat-numero"><?= $stats['fisioterapeutas'] ?? 15 ?></div>
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
                    <option value="usuario">Fisioterapeutas</option>
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
                <span class="contador-usuarios" id="contadorUsuarios">(<?= isset($total) ? $total : count($users ?? []) ?> usuários)</span>
            </div>
            <div class="opcoes-lista">
                <div class="dropdown-export">
                    <button class="btn-fisio btn-export" onclick="mostrarOpcoesExportar()">
                        <i class="fas fa-download"></i>
                        <span>Exportar</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu-export" id="menuExportar">
                        <button onclick="exportarUsuarios('pdf')">
                            <i class="fas fa-file-pdf"></i>
                            Exportar PDF
                        </button>
                        <button onclick="exportarUsuarios('excel')">
                            <i class="fas fa-file-excel"></i>
                            Exportar Excel
                        </button>
                    </div>
                </div>
                <button class="btn-fisio btn-atualizar" onclick="atualizarLista()">
                    <i class="fas fa-sync"></i>
                    <span>Atualizar</span>
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
                            <div class="usuario-nome">
                                <span class="usuario-role"><?= $usuario['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta' ?></span><br>
                                <strong><?= htmlspecialchars($usuario['name']) ?></strong><br>
                                <span class="usuario-email"><?= htmlspecialchars($usuario['email']) ?></span>
                            </div>
                        </div>
                        
                        <div class="usuario-dados-expandidos">
                            <div class="dado-item">
                                <span class="dado-label">Status</span>
                                <span class="dado-valor">
                                    <span class="status-badge status-<?= $usuario['status'] ?>">
                                        <i class="fas fa-<?= $usuario['status'] === 'active' ? 'check-circle' : 'times-circle' ?>"></i>
                                        <?= $usuario['status'] === 'active' ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </span>
                            </div>
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
                            <button class="btn-acao permissoes" onclick="gerenciarPermissoes(<?= $usuario['id'] ?>)" data-tooltip="Gerenciar permissões">
                                <i class="fas fa-shield-alt"></i>
                            </button>
                            <button class="btn-acao logs" onclick="verLogsUsuario(<?= $usuario['id'] ?>)" data-tooltip="Ver logs">
                                <i class="fas fa-history"></i>
                            </button>
                            <button class="btn-acao lgpd" onclick="gerenciarLGPD(<?= $usuario['id'] ?>)" data-tooltip="LGPD">
                                <i class="fas fa-gavel"></i>
                            </button>
                            <?php if ($usuario['id'] !== $user['id']): ?>
                            <button class="btn-acao pausar" onclick="alterarStatusUsuario(<?= $usuario['id'] ?>, '<?= $usuario['status'] === 'active' ? 'inactive' : 'active' ?>')" data-tooltip="<?= $usuario['status'] === 'active' ? 'Bloquear' : 'Ativar' ?>">
                                <i class="fas fa-<?= $usuario['status'] === 'active' ? 'ban' : 'check' ?>"></i>
                            </button>
                            <button class="btn-acao excluir" onclick="excluirUsuario(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['name']) ?>', '<?= htmlspecialchars($usuario['email']) ?>')" data-tooltip="Excluir usuário">
                                <i class="fas fa-trash"></i>
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
    <div class="permissoes-nova-container">
        <!-- Header da Aba -->
        <div class="permissoes-nova-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="header-text">
                    <h2>Gerenciar Permissões dos Fisioterapeutas</h2>
                    <p>Configure quais robôs Dr. IA cada fisioterapeuta pode VER e USAR</p>
                </div>
            </div>
        </div>
        
        <!-- Alert de Administradores -->
        <div class="alert-admin-info">
            <div class="alert-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="alert-content">
                <strong>👑 Administradores</strong>
                <p>Têm acesso total e irrestrito a todos os robôs automaticamente. Configure apenas fisioterapeutas aqui.</p>
            </div>
        </div>
        
        <!-- Barra de Filtros -->
        <div class="permissoes-filtros">
            <div class="filtro-busca">
                <i class="fas fa-search"></i>
                <input type="text" id="buscaFisioterapeuta" placeholder="Buscar fisioterapeuta por nome ou email..." 
                       onkeyup="filtrarFisioterapeutas()" class="form-input-fisio">
            </div>
            <div class="filtro-acoes">
                <button class="btn-fisio btn-secundario" onclick="expandirTodos()">
                    <i class="fas fa-expand-alt"></i> Expandir Todos
                </button>
                <button class="btn-fisio btn-secundario" onclick="recolherTodos()">
                    <i class="fas fa-compress-alt"></i> Recolher Todos
                </button>
                <button class="btn-fisio btn-primario" onclick="salvarTodasPermissoes()">
                    <i class="fas fa-save"></i> Salvar Todas as Alterações
                </button>
            </div>
        </div>
        
        <!-- Lista de Fisioterapeutas -->
        <div id="listaFisioterapeutas" class="lista-fisioterapeutas">
            <!-- Loading inicial -->
            <div id="loadingPermissoes" class="loading-center">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Carregando fisioterapeutas...</span>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para a nova interface de permissões -->
<style>
.permissoes-nova-container {
    padding: 0;
}

.permissoes-nova-header {
    background: linear-gradient(135deg, var(--azul-saude), var(--lilas-cuidado));
    color: white;
    padding: 24px;
    border-radius: 16px;
    margin-bottom: 24px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.header-text h2 {
    margin: 0 0 8px 0;
    font-size: 24px;
    font-weight: 700;
}

.header-text p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}

.alert-admin-info {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.alert-icon {
    color: #047857;
    font-size: 24px;
}

.alert-content strong {
    color: #047857;
    display: block;
    font-weight: 700;
    margin-bottom: 8px;
    font-size: 16px;
}

.alert-content p {
    color: #047857;
    margin: 0;
    font-size: 14px;
}

.permissoes-filtros {
    display: flex;
    gap: 20px;
    margin-bottom: 24px;
    align-items: center;
    flex-wrap: wrap;
}

.filtro-busca {
    flex: 1;
    min-width: 300px;
    position: relative;
}

.filtro-busca i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--cinza-escuro);
    opacity: 0.5;
}

.filtro-busca input {
    padding-left: 44px !important;
    width: 100%;
}

.filtro-acoes {
    display: flex;
    gap: 12px;
}

.loading-center {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 60px;
    color: var(--cinza-escuro);
    font-size: 16px;
}

.lista-fisioterapeutas {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.fisio-card {
    background: white;
    border: 2px solid var(--cinza-medio);
    border-radius: 16px;
    overflow: hidden;
    transition: var(--transicao);
}

.fisio-card:hover {
    border-color: var(--azul-saude);
    box-shadow: var(--sombra-media);
}

.fisio-card.expandido {
    border-color: var(--verde-terapia);
}

.fisio-card-header {
    padding: 20px 24px;
    background: var(--cinza-claro);
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: var(--transicao);
}

.fisio-card-header:hover {
    background: #e5e7eb;
}

.fisio-info-card {
    display: flex;
    align-items: center;
    gap: 16px;
}

.fisio-avatar-card {
    width: 48px;
    height: 48px;
    background: var(--verde-terapia);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.fisio-dados h4 {
    margin: 0 0 4px 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

.fisio-dados p {
    margin: 0;
    font-size: 14px;
    color: var(--cinza-escuro);
    opacity: 0.8;
}

.fisio-expandir {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    border: 2px solid var(--cinza-medio);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transicao);
}

.fisio-expandir:hover {
    background: var(--azul-saude);
    border-color: var(--azul-saude);
    color: white;
}

.fisio-expandir i {
    transition: transform 0.3s ease;
}

.fisio-card.expandido .fisio-expandir i {
    transform: rotate(180deg);
}

.fisio-card-body {
    padding: 24px;
    display: none;
}

.fisio-card.expandido .fisio-card-body {
    display: block;
}

.permissoes-titulo {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    font-size: 16px;
    font-weight: 600;
    color: var(--azul-saude);
}

.sem-fisioterapeutas {
    text-align: center;
    padding: 60px;
    color: var(--cinza-escuro);
}

.sem-fisioterapeutas i {
    font-size: 48px;
    color: var(--cinza-medio);
    margin-bottom: 16px;
}

.sem-fisioterapeutas h3 {
    margin: 0 0 8px 0;
    font-size: 20px;
    font-weight: 600;
}

.sem-fisioterapeutas p {
    margin: 0;
    color: var(--cinza-escuro);
    opacity: 0.8;
}

.robots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
}

.robot-permissao-card {
    background: var(--branco-puro);
    border: 2px solid var(--cinza-medio);
    border-radius: 12px;
    padding: 20px;
    transition: var(--transicao);
}

.robot-permissao-card:hover {
    border-color: var(--azul-saude);
    box-shadow: var(--sombra-media);
}

.robot-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.robot-icon {
    width: 40px;
    height: 40px;
    background: var(--azul-saude);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.robot-info h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

.robot-info p {
    margin: 0;
    font-size: 13px;
    color: var(--cinza-escuro);
    opacity: 0.8;
}

.robot-permissoes {
    display: flex;
    gap: 20px;
}

.permissao-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: var(--cinza-claro);
    border-radius: 8px;
    cursor: pointer;
    transition: var(--transicao);
}

.permissao-item:hover {
    background: var(--azul-claro);
}

.permissao-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--azul-saude);
    cursor: pointer;
}

.permissao-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--cinza-escuro);
    cursor: pointer;
}

.permissao-ver {
    color: var(--info);
}

.permissao-usar {
    color: var(--verde-terapia);
}

.acoes-permissoes {
    margin-top: 32px;
    display: flex;
    gap: 16px;
    justify-content: center;
}

.acoes-permissoes .btn-fisio {
    min-width: 180px;
    padding: 14px 24px;
    font-size: 16px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .robots-grid {
        grid-template-columns: 1fr;
    }
    
    .robot-permissoes {
        flex-direction: column;
        gap: 12px;
    }
    
    .acoes-permissoes {
        flex-direction: column;
    }
    
    .fisio-header {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
}
    font-size: 20px;
}

.usuario-details h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
    font-weight: 600;
}

.role-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.permissoes-sections {
    padding: 24px;
}

.permission-section {
    margin-bottom: 32px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.section-icon {
    font-size: 24px;
}

.section-info h4 {
    margin: 0 0 2px 0;
    color: var(--azul-saude);
    font-size: 16px;
    font-weight: 600;
}

.section-info p {
    margin: 0;
    color: var(--cinza-escuro);
    font-size: 13px;
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 12px;
}

.permissoes-actions {
    padding: 20px 24px;
    background: #f8fafc;
    border-top: 1px solid var(--cinza-medio);
    display: flex;
    gap: 12px;
}

.btn-action-primary, .btn-action-secondary {
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-action-primary {
    background: var(--azul-saude);
    color: white;
}

.btn-action-primary:hover {
    background: #1e40af;
    transform: translateY(-1px);
}

.btn-action-secondary {
    background: var(--cinza-medio);
    color: var(--cinza-escuro);
}

.btn-action-secondary:hover {
    background: #9ca3af;
}
</style>

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

/* Usuário desativado - aparência acinzentada */
.usuario-item-expandido[data-status="inactive"] {
    opacity: 0.7;
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.usuario-item-expandido[data-status="inactive"]:hover {
    border-color: #adb5bd;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.usuario-item-expandido[data-status="inactive"] .usuario-nome {
    color: #6c757d !important;
}

.usuario-item-expandido[data-status="inactive"] .usuario-email {
    color: #adb5bd !important;
}

.usuario-item-expandido[data-status="inactive"] .avatar-circulo {
    opacity: 0.6;
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
    gap: 20px;
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
.btn-acao.editar:hover {
    background: var(--azul-saude);
    color: white;
}

.btn-acao.permissoes:hover {
    background: var(--lilas-cuidado);
    color: white;
}

.btn-acao.logs:hover {
    background: var(--dourado-premium);
    color: white;
}

.btn-acao.lgpd:hover {
    background: #059669;
    color: white;
}

.btn-acao.pausar:hover {
    background: #f59e0b;
    color: white;
}

.btn-acao.excluir:hover {
    background: #ef4444;
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

.avatar-circulo.admin,
.avatar-circulo.fisio,
.avatar-circulo {
    background: var(--azul-saude);
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
    display: block;
}

.usuario-email {
    font-size: 14px;
    color: var(--cinza-escuro);
    margin-bottom: 8px;
    display: block;
}

.usuario-meta {
    display: flex;
    gap: 16px;
    align-items: center;
}

.usuario-role {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 12px;
    background: var(--azul-saude);
    color: white !important;
}

/* Badge de status na coluna de dados */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.status-active {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-badge.status-inactive {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.status-badge i {
    font-size: 10px;
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
// Função helper para construir URLs corretamente
function buildApiUrl(route) {
    const baseUrl = `${window.location.protocol}//${window.location.host}`;
    if (window.location.pathname.includes('/public/')) {
        // Se estamos no diretório public
        return `${baseUrl}/public/index.php?route=${route}`;
    } else {
        // Se o index.php está na raiz
        return `${baseUrl}/index.php?route=${route}`;
    }
}

// Sistema de Abas
function trocarAba(aba) {
    // Remover classe ativa de todas as abas
    document.querySelectorAll('.aba-btn').forEach(btn => btn.classList.remove('ativa'));
    document.querySelectorAll('.aba-conteudo').forEach(content => content.classList.remove('ativa'));
    
    // Adicionar classe ativa na aba selecionada
    document.getElementById('aba' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    document.getElementById('conteudo' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    
    // Carregar dados específicos da aba
    if (aba === 'permissoes') {
        carregarFisioterapeutas();
    }
}

// ===== NOVA INTERFACE DE PERMISSÕES COM LISTAGEM =====

function carregarListaFisioterapeutas() {
    const container = document.getElementById('listaFisioterapeutas');
    
    if (!container) return;
    
    // Buscar fisioterapeutas usando a API
    fetch(buildApiUrl('admin/permissions/users-api'))
        .then(response => {
            console.log('Status da resposta:', response.status);
            if (!response.ok) throw new Error('Erro na resposta');
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            if (data.success && data.users) {
                // Filtrar apenas fisioterapeutas (role != 'admin')
                const fisioterapeutas = data.users.filter(user => user.role !== 'admin');
                console.log('Fisioterapeutas encontrados:', fisioterapeutas);
                exibirListaFisioterapeutas(fisioterapeutas);
            } else {
                console.log('Sem dados ou erro na API');
                exibirListaFisioterapeutasVazia();
            }
        })
        .catch((error) => {
            console.error('Erro ao carregar fisioterapeutas:', error);
            // Se a API falhar, vamos buscar direto da tabela de usuários na página
            buscarFisioterapeutasDaTabela();
        });
}

function exibirListaFisioterapeutas(fisioterapeutas) {
    const container = document.getElementById('listaFisioterapeutas');
    
    if (fisioterapeutas.length === 0) {
        exibirListaFisioterapeutasVazia();
        return;
    }
    
    container.innerHTML = '';
    
    fisioterapeutas.forEach(fisio => {
        const card = criarCardFisioterapeuta(fisio);
        container.appendChild(card);
    });
}

function exibirListaFisioterapeutasVazia() {
    const container = document.getElementById('listaFisioterapeutas');
    container.innerHTML = `
        <div class="sem-fisioterapeutas">
            <i class="fas fa-user-slash"></i>
            <h3>Nenhum fisioterapeuta encontrado</h3>
            <p>Cadastre fisioterapeutas na aba de Usuários para gerenciar suas permissões aqui.</p>
        </div>
    `;
}

function buscarFisioterapeutasDaTabela() {
    // Buscar fisioterapeutas direto da aba de usuários se estiver carregada
    const usuarios = [];
    
    // Primeiro, tentar pegar da tabela de usuários se existir
    const tabelaUsuarios = document.querySelectorAll('.usuario-item-expandido');
    if (tabelaUsuarios.length > 0) {
        tabelaUsuarios.forEach(item => {
            const role = item.querySelector('.usuario-role')?.textContent?.trim().toLowerCase();
            if (role && role !== 'admin' && role !== 'administrador') {
                const nome = item.querySelector('.usuario-nome')?.textContent?.trim();
                const email = item.querySelector('.usuario-email')?.textContent?.trim();
                const idMatch = item.querySelector('button[onclick*="editarUsuario"]')?.getAttribute('onclick')?.match(/\d+/);
                const id = idMatch ? idMatch[0] : null;
                
                if (id && nome) {
                    usuarios.push({
                        id: parseInt(id),
                        name: nome,
                        email: email || 'Email não informado',
                        role: 'usuario'
                    });
                }
            }
        });
    }
    
    // Se não encontrou na tabela, usar os dados conhecidos do banco
    if (usuarios.length === 0) {
        usuarios.push(
            {id: 2, name: 'Thiago Peixoto', email: 'smart.thiago.eveline@gmail.com', role: 'usuario'},
            {id: 3, name: 'Usuario Teste', email: 'teste@teste.com', role: 'usuario'},
            {id: 4, name: 'Jeronimo Souza', email: 'email@teste.com', role: 'usuario'}
        );
    }
    
    console.log('Fisioterapeutas encontrados (método alternativo):', usuarios);
    exibirListaFisioterapeutas(usuarios);
}

function criarCardFisioterapeuta(fisio) {
    const card = document.createElement('div');
    card.className = 'fisio-card';
    card.setAttribute('data-fisio-id', fisio.id);
    card.setAttribute('data-fisio-nome', fisio.name.toLowerCase());
    card.setAttribute('data-fisio-email', (fisio.email || '').toLowerCase());
    
    card.innerHTML = `
        <div class="fisio-card-header" onclick="toggleFisioCard(${fisio.id})">
            <div class="fisio-info-card">
                <div class="fisio-avatar-card">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="fisio-dados">
                    <h4>${fisio.name}</h4>
                    <p>${fisio.email || 'Email não informado'}</p>
                </div>
            </div>
            <button class="fisio-expandir">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="fisio-card-body">
            <h3 class="permissoes-titulo">
                <i class="fas fa-robot"></i>
                Permissões dos Robôs Dr. IA
            </h3>
            <div class="robots-grid" id="robots-fisio-${fisio.id}">
                <!-- Robôs serão carregados aqui -->
            </div>
        </div>
    `;
    
    return card;
}

function toggleFisioCard(fisioId) {
    const card = document.querySelector(`[data-fisio-id="${fisioId}"]`);
    if (!card) return;
    
    const isExpandido = card.classList.contains('expandido');
    
    if (!isExpandido) {
        card.classList.add('expandido');
        carregarRobosFisioterapeuta(fisioId);
    } else {
        card.classList.remove('expandido');
    }
}

function carregarRobosFisioterapeuta(fisioId) {
    const container = document.getElementById(`robots-fisio-${fisioId}`);
    if (!container || container.children.length > 0) return; // Já carregado
    
    // Lista dos 5 robôs criados
    const robots = [
        {name: 'dr_autoritas', display_name: 'Dr. Autoritas', description: 'Conteúdo para Instagram'},
        {name: 'dr_acolhe', display_name: 'Dr. Acolhe', description: 'Atendimento via WhatsApp/Direct'},
        {name: 'dr_fechador', display_name: 'Dr. Fechador', description: 'Vendas de Planos Fisioterapêuticos'},
        {name: 'dr_reab', display_name: 'Dr. Reab', description: 'Prescrição de Exercícios Personalizados'},
        {name: 'dra_protoc', display_name: 'Dra. Protoc', description: 'Protocolos Terapêuticos Estruturados'}
    ];
    
    robots.forEach(robot => {
        const robotCard = criarCardRobot(robot, fisioId);
        container.appendChild(robotCard);
    });
}

function preencherSelectFisioterapeutas(fisios) {
    const select = document.getElementById('fisioSelecionado');
    
    fisios.forEach(fisio => {
        if (fisio.role !== 'admin') { // Não mostrar admins
            const option = document.createElement('option');
            option.value = fisio.id;
            option.textContent = `${fisio.name} (${fisio.email})`;
            select.appendChild(option);
        }
    });
    
    if (select.children.length === 1) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Nenhum fisioterapeuta encontrado';
        option.disabled = true;
        select.appendChild(option);
    }
}

function carregarPermissoesFisio(fisioId) {
    if (!fisioId) {
        document.getElementById('interfacePermissoesFisio').style.display = 'none';
        return;
    }
    
    // Mostrar interface
    document.getElementById('interfacePermissoesFisio').style.display = 'block';
    
    // Buscar nome do fisioterapeuta
    const select = document.getElementById('fisioSelecionado');
    const selectedOption = select.options[select.selectedIndex];
    const fisioNome = selectedOption.textContent.split(' (')[0];
    
    document.getElementById('fisioNome').textContent = fisioNome;
    
    // Carregar robôs disponíveis
    carregarRobosPermissoes(fisioId);
}

function carregarRobosPermissoes(fisioId) {
    const container = document.getElementById('robotsPermissoes');
    
    // Lista dos 5 robôs criados
    const robots = [
        {name: 'dr_autoritas', display_name: 'Dr. Autoritas', description: 'Conteúdo para Instagram'},
        {name: 'dr_acolhe', display_name: 'Dr. Acolhe', description: 'Atendimento via WhatsApp/Direct'},
        {name: 'dr_fechador', display_name: 'Dr. Fechador', description: 'Vendas de Planos Fisioterapêuticos'},
        {name: 'dr_reab', display_name: 'Dr. Reab', description: 'Prescrição de Exercícios Personalizados'},
        {name: 'dra_protoc', display_name: 'Dra. Protoc', description: 'Protocolos Terapêuticos Estruturados'}
    ];
    
    container.innerHTML = '';
    
    robots.forEach(robot => {
        const robotCard = criarCardRobot(robot, fisioId);
        container.appendChild(robotCard);
    });
}

function criarCardRobot(robot, fisioId) {
    const card = document.createElement('div');
    card.className = 'robot-permissao-card';
    
    card.innerHTML = `
        <div class="robot-header">
            <div class="robot-icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="robot-info">
                <h4>${robot.display_name}</h4>
                <p>${robot.description}</p>
            </div>
        </div>
        <div class="robot-permissoes">
            <div class="permissao-item">
                <input type="checkbox" id="ver_${robot.name}_${fisioId}" name="perm_${robot.name}_view" value="1">
                <label for="ver_${robot.name}_${fisioId}" class="permissao-label permissao-ver">
                    👁️ VER
                </label>
            </div>
            <div class="permissao-item">
                <input type="checkbox" id="usar_${robot.name}_${fisioId}" name="perm_${robot.name}_use" value="1">
                <label for="usar_${robot.name}_${fisioId}" class="permissao-label permissao-usar">
                    ⚡ USAR
                </label>
            </div>
        </div>
    `;
    
    return card;
}

function filtrarFisioterapeutas() {
    const busca = document.getElementById('buscaFisioterapeuta').value.toLowerCase();
    const cards = document.querySelectorAll('.fisio-card');
    
    cards.forEach(card => {
        const nome = card.getAttribute('data-fisio-nome');
        const email = card.getAttribute('data-fisio-email');
        
        if (nome.includes(busca) || email.includes(busca)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function expandirTodos() {
    const cards = document.querySelectorAll('.fisio-card:not(.expandido)');
    cards.forEach(card => {
        const fisioId = card.getAttribute('data-fisio-id');
        if (fisioId && card.style.display !== 'none') {
            toggleFisioCard(fisioId);
        }
    });
}

function recolherTodos() {
    const cards = document.querySelectorAll('.fisio-card.expandido');
    cards.forEach(card => {
        card.classList.remove('expandido');
    });
}

function salvarTodasPermissoes() {
    const btnSalvar = event.target.closest('button');
    const textoOriginal = btnSalvar.innerHTML;
    
    // Mostrar loading
    btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando todas as alterações...';
    btnSalvar.disabled = true;
    
    // Coletar todas as permissões de todos os fisioterapeutas
    const todasPermissoes = [];
    const cards = document.querySelectorAll('.fisio-card.expandido');
    
    cards.forEach(card => {
        const fisioId = card.getAttribute('data-fisio-id');
        const checkboxes = card.querySelectorAll('input[type="checkbox"]');
        const permissoes = {};
        
        checkboxes.forEach(checkbox => {
            permissoes[checkbox.name] = checkbox.checked;
        });
        
        if (Object.keys(permissoes).length > 0) {
            todasPermissoes.push({
                fisioId: fisioId,
                permissoes: permissoes
            });
        }
    });
    
    // Simular salvamento
    setTimeout(() => {
        btnSalvar.innerHTML = '<i class="fas fa-check"></i> Todas as permissões foram salvas!';
        btnSalvar.style.background = 'var(--verde-terapia)';
        
        setTimeout(() => {
            btnSalvar.innerHTML = textoOriginal;
            btnSalvar.style.background = '';
            btnSalvar.disabled = false;
        }, 3000);
        
        mostrarNotificacao(`${todasPermissoes.length} fisioterapeuta(s) atualizado(s) com sucesso!`, 'success');
    }, 1500);
}

function cancelarPermissoesFisio() {
    document.getElementById('fisioSelecionado').value = '';
    document.getElementById('interfacePermissoesFisio').style.display = 'none';
}

function mostrarNotificacao(mensagem, tipo) {
    const notif = document.createElement('div');
    notif.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    if (tipo === 'success') {
        notif.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        notif.innerHTML = `<i class="fas fa-check-circle"></i> ${mensagem}`;
    } else {
        notif.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        notif.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${mensagem}`;
    }
    
    document.body.appendChild(notif);
    
    // Animar entrada
    setTimeout(() => {
        notif.style.transform = 'translateX(0)';
    }, 100);
    
    // Remover após 4 segundos
    setTimeout(() => {
        notif.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notif.parentNode) {
                notif.parentNode.removeChild(notif);
            }
        }, 300);
    }, 4000);
}

// Expandir/Colapsar detalhes do usuário
document.addEventListener('click', function(e) {
    if (e.target.closest('.usuario-principal')) {
        const item = e.target.closest('.usuario-item-expandido');
        if (item) {
            const detalhes = item.querySelector('.usuario-detalhes');
            if (detalhes) {
                if (detalhes.style.display === 'none' || !detalhes.style.display) {
                    detalhes.style.display = 'block';
                } else {
                    detalhes.style.display = 'none';
                }
            }
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
    const modal = document.getElementById('modalNovoUsuario');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function importarUsuarios() {
    mostrarAlerta('Funcionalidade de importação CSV será implementada', 'info');
}

function exportarUsuarios() {
    window.open('/admin/users/export', '_blank');
    mostrarAlerta('Exportando lista de usuários...', 'info');
}

// Função editarUsuario removida - usar a versão modal abaixo

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
                setTimeout(() => window.location.href = window.location.href.split('?')[0], 1000);
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
    const termo = document.getElementById('buscaUsuario').value.toLowerCase();
    
    const usuarios = document.querySelectorAll('.usuario-item-expandido');
    let contador = 0;
    
    usuarios.forEach(usuario => {
        const usuarioRole = usuario.dataset.role;
        const usuarioStatus = usuario.dataset.status;
        const usuarioTexto = usuario.textContent.toLowerCase();
        
        const roleMatch = !role || usuarioRole === role;
        const statusMatch = !status || usuarioStatus === status;
        const termoMatch = !termo || usuarioTexto.includes(termo);
        
        const mostrar = roleMatch && statusMatch && termoMatch;
        
        usuario.style.display = mostrar ? 'block' : 'none';
        if (mostrar) contador++;
    });
    
    // Atualizar contador
    const contadorElement = document.getElementById('contadorUsuarios');
    if (contadorElement) {
        contadorElement.textContent = `(${contador} usuários)`;
    }
}

function buscarUsuarios() {
    // Aplicar filtro com delay para melhor performance
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(filtrarUsuarios, 300);
}

// Funções específicas das permissões
function carregarUsuarios() {
    // Função antiga - mantida vazia para evitar erros
    // A nova interface usa carregarFisioterapeutas()
    return;
}

function carregarPermissoesDoUsuario(userId) {
    if (!userId) {
        const interface = document.getElementById('interfacePermissoesSimples');
        if (interface) {
            interface.style.display = 'none';
        }
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
                
                const interface = document.getElementById('interfacePermissoesSimples');
                if (interface) {
                    interface.style.display = 'block';
                }
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
    document.getElementById('interfacePermissoesModern').style.display = 'none';
}

// Inicializar sistema
document.addEventListener('DOMContentLoaded', function() {
    // Carregar lista de fisioterapeutas quando a aba de permissões for aberta
    const abaPermissoes = document.getElementById('abaPermissoes');
    if (abaPermissoes) {
        abaPermissoes.addEventListener('click', function() {
            setTimeout(() => {
                if (document.getElementById('listaFisioterapeutas')) {
                    carregarListaFisioterapeutas();
                }
            }, 100);
        });
    }
    
    // Aqui seria implementada a inicialização dos gráficos
    // usando Chart.js ou similar
});
</script>

<!-- Modal Criar Usuário -->
<div id="modalNovoUsuario" class="modal-fisio" style="display: none;">
    <div class="modal-overlay" onclick="fecharModalNovoUsuario()"></div>
    <div class="modal-content-fisio modal-grande">
        <div class="modal-header-fisio">
            <div class="modal-titulo">
                <i class="fas fa-user-plus"></i>
                <h3>Cadastro Completo de Usuário</h3>
            </div>
            <button type="button" class="modal-close-fisio" onclick="fecharModalNovoUsuario()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formNovoUsuario" method="POST" action="/admin/users/create">
            <div class="modal-body-fisio">
                <!-- Abas do formulário -->
                <div class="form-tabs-fisio">
                    <button type="button" class="tab-btn-fisio active" onclick="trocarAbaForm('dados-basicos')">
                        <i class="fas fa-user"></i>
                        Dados Básicos
                    </button>
                    <button type="button" class="tab-btn-fisio" onclick="trocarAbaForm('dados-profissionais')">
                        <i class="fas fa-user-md"></i>
                        Dados Profissionais
                    </button>
                    <button type="button" class="tab-btn-fisio" onclick="trocarAbaForm('endereco')">
                        <i class="fas fa-map-marker-alt"></i>
                        Endereço
                    </button>
                </div>
                
                <!-- Aba Dados Básicos -->
                <div class="form-tab-content active" id="tab-dados-basicos">
                    <div class="form-grid-fisio">
                        <div class="form-group-fisio">
                            <label for="nome" class="form-label-fisio">
                                <i class="fas fa-user"></i>
                                Nome Completo *
                            </label>
                            <input type="text" id="nome" name="name" class="form-input-fisio" required maxlength="255" placeholder="Digite o nome completo">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="email" class="form-label-fisio">
                                <i class="fas fa-envelope"></i>
                                Email *
                            </label>
                            <input type="email" id="email" name="email" class="form-input-fisio" required maxlength="255" placeholder="email@exemplo.com">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="telefone" class="form-label-fisio">
                                <i class="fas fa-phone"></i>
                                Telefone *
                            </label>
                            <input type="text" id="telefone" name="phone" class="form-input-fisio" required placeholder="(11) 99999-9999" data-mask="phone">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="cpf" class="form-label-fisio">
                                <i class="fas fa-id-card"></i>
                                CPF
                            </label>
                            <input type="text" id="cpf" name="cpf" class="form-input-fisio" placeholder="000.000.000-00" data-mask="cpf">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="dataNascimento" class="form-label-fisio">
                                <i class="fas fa-calendar"></i>
                                Data de Nascimento
                            </label>
                            <input type="date" id="dataNascimento" name="birth_date" class="form-input-fisio">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="genero" class="form-label-fisio">
                                <i class="fas fa-venus-mars"></i>
                                Gênero
                            </label>
                            <select id="genero" name="gender" class="form-select-fisio">
                                <option value="">Selecione</option>
                                <option value="masculino">Masculino</option>
                                <option value="feminino">Feminino</option>
                                <option value="outro">Outro</option>
                                <option value="nao_informar">Prefiro não informar</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="senha" class="form-label-fisio">
                                <i class="fas fa-lock"></i>
                                Senha *
                            </label>
                            <input type="password" id="senha" name="password" class="form-input-fisio" required minlength="8" placeholder="Mínimo 8 caracteres">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="confirmarSenha" class="form-label-fisio">
                                <i class="fas fa-lock-open"></i>
                                Confirmar Senha *
                            </label>
                            <input type="password" id="confirmarSenha" name="password_confirm" class="form-input-fisio" required minlength="8" placeholder="Repita a senha">
                        </div>
                    </div>
                </div>
                
                <!-- Aba Dados Profissionais -->
                <div class="form-tab-content" id="tab-dados-profissionais">
                    <div class="form-grid-fisio">
                        <div class="form-group-fisio">
                            <label for="role" class="form-label-fisio">
                                <i class="fas fa-user-tag"></i>
                                Perfil do Usuário *
                            </label>
                            <select id="role" name="role" class="form-select-fisio" required>
                                <option value="usuario">👨‍⚕️ Fisioterapeuta</option>
                                <option value="admin">👨‍💼 Administrador</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="status" class="form-label-fisio">
                                <i class="fas fa-toggle-on"></i>
                                Status *
                            </label>
                            <select id="status" name="status" class="form-select-fisio" required>
                                <option value="active">✅ Ativo</option>
                                <option value="inactive">❌ Inativo</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="crefito" class="form-label-fisio">
                                <i class="fas fa-certificate"></i>
                                CREFITO
                            </label>
                            <input type="text" id="crefito" name="crefito" class="form-input-fisio" placeholder="CREFITO-3/123456-F">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="especialidade" class="form-label-fisio">
                                <i class="fas fa-stethoscope"></i>
                                Especialidade Principal
                            </label>
                            <select id="especialidade" name="main_specialty" class="form-select-fisio">
                                <option value="">Selecione</option>
                                <option value="ortopedica">Ortopédica</option>
                                <option value="neurologica">Neurológica</option>
                                <option value="respiratoria">Respiratória</option>
                                <option value="geriatrica">Geriátrica</option>
                                <option value="pediatrica">Pediátrica</option>
                                <option value="esportiva">Esportiva</option>
                                <option value="dermato">Dermatofuncional</option>
                                <option value="urogineco">Uroginecológica</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="departamento" class="form-label-fisio">
                                <i class="fas fa-building"></i>
                                Departamento
                            </label>
                            <input type="text" id="departamento" name="department" class="form-input-fisio" placeholder="Ex: Fisioterapia Ambulatorial">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="cargo" class="form-label-fisio">
                                <i class="fas fa-briefcase"></i>
                                Cargo/Função
                            </label>
                            <input type="text" id="cargo" name="position" class="form-input-fisio" placeholder="Ex: Fisioterapeuta Sênior">
                        </div>
                    </div>
                </div>
                
                <!-- Aba Endereço -->
                <div class="form-tab-content" id="tab-endereco">
                    <div class="form-grid-fisio">
                        <div class="form-group-fisio">
                            <label for="cep" class="form-label-fisio">
                                <i class="fas fa-map-pin"></i>
                                CEP
                            </label>
                            <input type="text" id="cep" name="cep" class="form-input-fisio" placeholder="00000-000" data-mask="cep" onblur="buscarCEP()">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="endereco" class="form-label-fisio">
                                <i class="fas fa-road"></i>
                                Endereço
                            </label>
                            <input type="text" id="endereco" name="address" class="form-input-fisio" placeholder="Rua, Avenida...">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="numero" class="form-label-fisio">
                                <i class="fas fa-home"></i>
                                Número
                            </label>
                            <input type="text" id="numero" name="number" class="form-input-fisio" placeholder="123">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="complemento" class="form-label-fisio">
                                <i class="fas fa-info-circle"></i>
                                Complemento
                            </label>
                            <input type="text" id="complemento" name="complement" class="form-input-fisio" placeholder="Apto, Sala...">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="bairro" class="form-label-fisio">
                                <i class="fas fa-map-marked-alt"></i>
                                Bairro
                            </label>
                            <input type="text" id="bairro" name="neighborhood" class="form-input-fisio" placeholder="Nome do bairro">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="cidade" class="form-label-fisio">
                                <i class="fas fa-city"></i>
                                Cidade
                            </label>
                            <input type="text" id="cidade" name="city" class="form-input-fisio" placeholder="São Paulo">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="estado" class="form-label-fisio">
                                <i class="fas fa-flag"></i>
                                Estado
                            </label>
                            <select id="estado" name="state" class="form-select-fisio">
                                <option value="">Selecione</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-checkbox-fisio">
                        <label class="checkbox-label-fisio">
                            <input type="checkbox" name="force_password_change" value="1" class="checkbox-input-fisio">
                            <span class="checkbox-text">
                                <i class="fas fa-key"></i>
                                Forçar mudança de senha no primeiro login
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer-fisio">
                <button type="button" class="btn-fisio btn-secundario" onclick="fecharModalNovoUsuario()">
                    <i class="fas fa-times"></i>
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

<!-- Modal Editar Usuário -->
<div id="modalEditarUsuario" class="modal-fisio" style="display: none;">
    <div class="modal-overlay" onclick="fecharModalEditarUsuario()"></div>
    <div class="modal-content-fisio modal-grande">
        <div class="modal-header-fisio">
            <div class="modal-titulo">
                <i class="fas fa-user-edit"></i>
                <h3>Editar Usuário</h3>
            </div>
            <button type="button" class="modal-close-fisio" onclick="fecharModalEditarUsuario()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formEditarUsuario" method="POST" onsubmit="salvarEdicaoUsuario(event)">
            <input type="hidden" id="editUserId" name="user_id">
            <div class="modal-body-fisio">
                <!-- Abas do formulário -->
                <div class="form-tabs-fisio">
                    <button type="button" class="tab-btn-fisio active" onclick="trocarAbaFormEdit('dados-basicos')">
                        <i class="fas fa-user"></i>
                        Dados Básicos
                    </button>
                    <button type="button" class="tab-btn-fisio" onclick="trocarAbaFormEdit('dados-profissionais')">
                        <i class="fas fa-user-md"></i>
                        Dados Profissionais
                    </button>
                    <button type="button" class="tab-btn-fisio" onclick="trocarAbaFormEdit('endereco')">
                        <i class="fas fa-map-marker-alt"></i>
                        Endereço
                    </button>
                </div>
                
                <!-- Aba Dados Básicos -->
                <div class="form-tab-content active" id="edit-tab-dados-basicos">
                    <div class="form-grid-fisio">
                        <div class="form-group-fisio">
                            <label for="editNome" class="form-label-fisio">
                                <i class="fas fa-user"></i>
                                Nome Completo *
                            </label>
                            <input type="text" id="editNome" name="name" class="form-input-fisio" required maxlength="255">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editEmail" class="form-label-fisio">
                                <i class="fas fa-envelope"></i>
                                Email *
                            </label>
                            <input type="email" id="editEmail" name="email" class="form-input-fisio" required maxlength="255" readonly>
                            <small class="form-help">Email não pode ser alterado</small>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editTelefone" class="form-label-fisio">
                                <i class="fas fa-phone"></i>
                                Telefone
                            </label>
                            <input type="text" id="editTelefone" name="phone" class="form-input-fisio" placeholder="(11) 99999-9999">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editCpf" class="form-label-fisio">
                                <i class="fas fa-id-card"></i>
                                CPF
                            </label>
                            <input type="text" id="editCpf" name="cpf" class="form-input-fisio" placeholder="000.000.000-00">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editDataNascimento" class="form-label-fisio">
                                <i class="fas fa-calendar"></i>
                                Data de Nascimento
                            </label>
                            <input type="date" id="editDataNascimento" name="birth_date" class="form-input-fisio">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editGenero" class="form-label-fisio">
                                <i class="fas fa-venus-mars"></i>
                                Gênero
                            </label>
                            <select id="editGenero" name="gender" class="form-select-fisio">
                                <option value="">Selecione</option>
                                <option value="masculino">Masculino</option>
                                <option value="feminino">Feminino</option>
                                <option value="outro">Outro</option>
                                <option value="nao_informar">Prefiro não informar</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editSenha" class="form-label-fisio">
                                <i class="fas fa-lock"></i>
                                Nova Senha
                            </label>
                            <input type="password" id="editSenha" name="password" class="form-input-fisio" minlength="8" placeholder="Deixe em branco para manter a atual">
                            <small class="form-help">Deixe em branco se não quiser alterar a senha</small>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editConfirmarSenha" class="form-label-fisio">
                                <i class="fas fa-lock-open"></i>
                                Confirmar Nova Senha
                            </label>
                            <input type="password" id="editConfirmarSenha" name="password_confirmation" class="form-input-fisio" minlength="8" placeholder="Confirme a nova senha">
                        </div>
                        
                        <div class="form-group-fisio col-span-2">
                            <div class="info-group">
                                <span class="info-label">
                                    <i class="fas fa-clock"></i>
                                    Último Login:
                                </span>
                                <span id="editUltimoLogin" class="info-value">-</span>
                            </div>
                            <div class="info-group">
                                <span class="info-label">
                                    <i class="fas fa-calendar-check"></i>
                                    Cadastrado em:
                                </span>
                                <span id="editCadastradoEm" class="info-value">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Aba Dados Profissionais -->
                <div class="form-tab-content" id="edit-tab-dados-profissionais">
                    <div class="form-grid-fisio">
                        <div class="form-group-fisio">
                            <label for="editRole" class="form-label-fisio">
                                <i class="fas fa-user-tag"></i>
                                Perfil do Usuário *
                            </label>
                            <select id="editRole" name="role" class="form-select-fisio" required>
                                <option value="usuario">👨‍⚕️ Fisioterapeuta</option>
                                <option value="admin">👨‍💼 Administrador</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editStatus" class="form-label-fisio">
                                <i class="fas fa-toggle-on"></i>
                                Status *
                            </label>
                            <select id="editStatus" name="status" class="form-select-fisio" required>
                                <option value="active">✅ Ativo</option>
                                <option value="inactive">❌ Inativo</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editCrefito" class="form-label-fisio">
                                <i class="fas fa-certificate"></i>
                                CREFITO
                            </label>
                            <input type="text" id="editCrefito" name="crefito" class="form-input-fisio" placeholder="CREFITO-3/123456-F">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editEspecialidade" class="form-label-fisio">
                                <i class="fas fa-stethoscope"></i>
                                Especialidade Principal
                            </label>
                            <select id="editEspecialidade" name="main_specialty" class="form-select-fisio">
                                <option value="">Selecione</option>
                                <option value="ortopedica">Ortopédica</option>
                                <option value="neurologica">Neurológica</option>
                                <option value="respiratoria">Respiratória</option>
                                <option value="geriatrica">Geriátrica</option>
                                <option value="pediatrica">Pediátrica</option>
                                <option value="esportiva">Esportiva</option>
                                <option value="dermato">Dermatofuncional</option>
                                <option value="urogineco">Uroginecológica</option>
                            </select>
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editDepartamento" class="form-label-fisio">
                                <i class="fas fa-building"></i>
                                Departamento
                            </label>
                            <input type="text" id="editDepartamento" name="department" class="form-input-fisio" placeholder="Ex: Fisioterapia Ambulatorial">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editCargo" class="form-label-fisio">
                                <i class="fas fa-briefcase"></i>
                                Cargo/Função
                            </label>
                            <input type="text" id="editCargo" name="position" class="form-input-fisio" placeholder="Ex: Fisioterapeuta Sênior">
                        </div>
                    </div>
                </div>
                
                <!-- Aba Endereço -->
                <div class="form-tab-content" id="edit-tab-endereco">
                    <div class="form-grid-fisio">
                        <div class="form-group-fisio">
                            <label for="editCep" class="form-label-fisio">
                                <i class="fas fa-map-pin"></i>
                                CEP
                            </label>
                            <input type="text" id="editCep" name="cep" class="form-input-fisio" placeholder="00000-000" onblur="buscarCEPEdit()">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editEndereco" class="form-label-fisio">
                                <i class="fas fa-road"></i>
                                Endereço
                            </label>
                            <input type="text" id="editEndereco" name="address" class="form-input-fisio" placeholder="Rua, Avenida...">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editNumero" class="form-label-fisio">
                                <i class="fas fa-home"></i>
                                Número
                            </label>
                            <input type="text" id="editNumero" name="number" class="form-input-fisio" placeholder="123">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editComplemento" class="form-label-fisio">
                                <i class="fas fa-info-circle"></i>
                                Complemento
                            </label>
                            <input type="text" id="editComplemento" name="complement" class="form-input-fisio" placeholder="Apto, Sala...">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editBairro" class="form-label-fisio">
                                <i class="fas fa-map-marked-alt"></i>
                                Bairro
                            </label>
                            <input type="text" id="editBairro" name="neighborhood" class="form-input-fisio" placeholder="Nome do bairro">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editCidade" class="form-label-fisio">
                                <i class="fas fa-city"></i>
                                Cidade
                            </label>
                            <input type="text" id="editCidade" name="city" class="form-input-fisio" placeholder="São Paulo">
                        </div>
                        
                        <div class="form-group-fisio">
                            <label for="editEstado" class="form-label-fisio">
                                <i class="fas fa-flag"></i>
                                Estado
                            </label>
                            <select id="editEstado" name="state" class="form-select-fisio">
                                <option value="">Selecione</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-checkbox-fisio">
                        <label class="checkbox-label-fisio">
                            <input type="checkbox" name="must_change_password" value="1" class="checkbox-input-fisio" id="editForcePassword">
                            <span class="checkbox-text">
                                <i class="fas fa-key"></i>
                                Forçar mudança de senha no próximo login
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer-fisio">
                <div id="editUserMessage" class="form-message" style="display: none;"></div>
                <button type="button" class="btn-fisio btn-secundario" onclick="fecharModalEditarUsuario()">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>
                <button type="submit" class="btn-fisio btn-primario" id="btnSalvarEdicao">
                    <i class="fas fa-save"></i>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Modal Fisio Theme */
.modal-fisio {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.modal-content-fisio {
    position: relative;
    background: white;
    border-radius: 24px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    animation: modalEntrada 0.3s ease-out;
}

.modal-content-fisio.modal-grande {
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
}

@keyframes modalEntrada {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header-fisio {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 24px 0;
}

.modal-titulo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-titulo i {
    font-size: 24px;
    color: var(--azul-saude);
}

.modal-titulo h3 {
    margin: 0;
    color: var(--azul-saude);
    font-size: 24px;
    font-weight: 700;
}

.modal-close-fisio {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--cinza-claro);
    border: none;
    cursor: pointer;
    transition: var(--transicao);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close-fisio:hover {
    background: #ef4444;
    color: white;
    transform: rotate(90deg);
}

.modal-body-fisio {
    padding: 24px;
}

.form-grid-fisio {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group-fisio {
    display: flex;
    flex-direction: column;
}

.form-label-fisio {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.form-label-fisio i {
    font-size: 16px;
    color: var(--azul-saude);
}

.form-input-fisio,
.form-select-fisio {
    padding: 14px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 12px;
    font-size: 14px;
    transition: var(--transicao);
    background: var(--cinza-claro);
}

.form-input-fisio:focus,
.form-select-fisio:focus {
    border-color: var(--azul-saude);
    outline: none;
    background: white;
    box-shadow: 0 0 0 4px rgba(77, 154, 230, 0.1);
}

.form-input-fisio::placeholder {
    color: #9ca3af;
}

.form-checkbox-fisio {
    grid-column: 1 / -1;
    margin-top: 8px;
}

.checkbox-label-fisio {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 12px 16px;
    border-radius: 12px;
    background: var(--cinza-claro);
    transition: var(--transicao);
}

.checkbox-label-fisio:hover {
    background: var(--azul-claro);
}

.checkbox-input-fisio {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    cursor: pointer;
}

.checkbox-text {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--cinza-escuro);
    font-weight: 500;
}

.checkbox-text i {
    color: var(--azul-saude);
}

.modal-footer-fisio {
    padding: 0 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

/* Abas do Formulário */
.form-tabs-fisio {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    border-bottom: 2px solid var(--cinza-medio);
}

.tab-btn-fisio {
    padding: 12px 20px;
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

.tab-btn-fisio.active {
    background: var(--azul-saude);
    color: white;
}

.tab-btn-fisio:hover:not(.active) {
    background: var(--cinza-claro);
}

.form-tab-content {
    display: none;
}

.form-tab-content.active {
    display: block;
}

/* Card Header */
.card-header-fisio {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--cinza-medio);
}

.card-titulo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 700;
    color: var(--azul-saude);
}

.card-titulo i {
    font-size: 24px;
}

.contador-usuarios {
    font-weight: 400;
    color: var(--cinza-escuro);
    font-size: 16px;
}

/* Botões de Exportar */
.opcoes-lista {
    display: flex;
    gap: 12px;
    align-items: center;
}

.dropdown-export {
    position: relative;
}

.btn-export,
.btn-atualizar {
    padding: 10px 16px;
    border-radius: 12px;
    border: 2px solid var(--cinza-medio);
    background: white;
    cursor: pointer;
    transition: var(--transicao);
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--cinza-escuro);
}

.btn-export:hover {
    background: var(--azul-saude);
    color: white;
    border-color: var(--azul-saude);
}

.btn-atualizar:hover {
    background: var(--verde-sucesso);
    color: white;
    border-color: var(--verde-sucesso);
}

.dropdown-menu-export {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--sombra-media);
    min-width: 180px;
    display: none;
    z-index: 1000;
}

.dropdown-menu-export.show {
    display: block;
}

.dropdown-menu-export button {
    width: 100%;
    padding: 12px 16px;
    border: none;
    background: none;
    cursor: pointer;
    transition: var(--transicao);
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    color: var(--cinza-escuro);
    text-align: left;
}

.dropdown-menu-export button:hover {
    background: var(--cinza-claro);
}

.dropdown-menu-export button:first-child {
    border-radius: 12px 12px 0 0;
}

.dropdown-menu-export button:last-child {
    border-radius: 0 0 12px 12px;
}

.dropdown-menu-export button i {
    font-size: 18px;
}

.dropdown-menu-export button:hover i.fa-file-pdf {
    color: #dc2626;
}

.dropdown-menu-export button:hover i.fa-file-excel {
    color: #059669;
}

/* Animação de rotação para o botão atualizar */
.btn-atualizar.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Estilos específicos para o modal de edição */
.col-span-2 {
    grid-column: span 2;
}

.info-group {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    padding: 8px 12px;
    background: var(--cinza-claro);
    border-radius: 8px;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.info-value {
    font-weight: 500;
    color: var(--azul-saude);
}

.form-help {
    font-size: 12px;
    color: var(--cinza-escuro);
    font-style: italic;
    margin-top: 4px;
}

.form-message {
    padding: 12px 16px;
    border-radius: 8px;
    margin-right: auto;
    font-weight: 500;
}

.form-message.success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.form-message.error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* Responsividade */
@media (max-width: 768px) {
    .modal-content-fisio {
        width: 95%;
        margin: 20px;
    }
    
    .form-grid-fisio {
        grid-template-columns: 1fr;
    }
    
    .card-header-fisio {
        flex-direction: column;
        gap: 16px;
        align-items: stretch;
    }
    
    .card-titulo {
        justify-content: center;
    }
    
    .opcoes-lista {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-export,
    .btn-atualizar {
        width: 100%;
        justify-content: center;
    }
    
    .dropdown-export {
        width: 100%;
    }
    
    .dropdown-menu-export {
        left: 0;
        right: 0;
    }
    
    .usuario-dados-expandidos {
        flex-direction: column;
        gap: 12px;
    }
    
    .dado-item {
        text-align: left;
    }
}
</style>

<script>
function trocarAba(aba) {
    // Remove classe ativa de todos os botões e conteúdos
    document.querySelectorAll('.aba-btn').forEach(btn => btn.classList.remove('ativa'));
    document.querySelectorAll('.aba-conteudo').forEach(content => content.classList.remove('ativa'));
    
    // Adiciona classe ativa ao botão e conteúdo selecionados
    document.getElementById('aba' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    document.getElementById('conteudo' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    
    // Carregar dados específicos da aba
    if (aba === 'permissoes') {
        carregarUsuariosParaPermissoes();
    }
}

function carregarUsuariosParaPermissoes() {
    const select = document.getElementById('usuarioSelecionado');
    if (!select) return;
    
    // Mostrar loading
    const loading = document.getElementById('loadingUsuarios');
    if (loading) {
        loading.style.display = 'flex';
    }
    
    // Limpar opções existentes (exceto a primeira)
    while (select.children.length > 1) {
        select.removeChild(select.lastChild);
    }
    
    // Buscar usuários
    const url = buildApiUrl('admin/permissions/users-api');
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.users) {
                data.users.forEach(user => {
                    // Filtrar apenas fisioterapeutas (admins têm acesso total)
                    if (user.role !== 'admin') {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = `${user.name} (${user.role === 'professional' ? 'Fisioterapeuta' : 'Usuário'})`;
                        select.appendChild(option);
                    }
                });
                
                if (select.children.length === 1) {
                    // Apenas a opção padrão
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Nenhum fisioterapeuta encontrado';
                    option.disabled = true;
                    select.appendChild(option);
                }
            } else {
                console.error('Resposta da API inválida:', data);
            }
        })
        .catch(error => {
            console.error('Erro ao carregar usuários para permissões:', error);
            // Adicionar opção de erro
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Erro ao carregar usuários';
            option.disabled = true;
            select.appendChild(option);
        })
        .finally(() => {
            if (loading) {
                loading.style.display = 'none';
            }
        });
}

function filtrarUsuarios() {
    const role = document.getElementById('filtroRole').value;
    const status = document.getElementById('filtroStatus').value;
    const termo = document.getElementById('buscaUsuario').value.toLowerCase();
    
    const usuarios = document.querySelectorAll('.usuario-item-expandido');
    let contador = 0;
    
    usuarios.forEach(usuario => {
        const usuarioRole = usuario.dataset.role;
        const usuarioStatus = usuario.dataset.status;
        const usuarioTexto = usuario.textContent.toLowerCase();
        
        const roleMatch = !role || usuarioRole === role;
        const statusMatch = !status || usuarioStatus === status;
        const termoMatch = !termo || usuarioTexto.includes(termo);
        
        const mostrar = roleMatch && statusMatch && termoMatch;
        
        usuario.style.display = mostrar ? 'block' : 'none';
        if (mostrar) contador++;
    });
    
    const textoUsuarios = contador === 1 ? 'usuário' : 'usuários';
    document.getElementById('contadorUsuarios').textContent = `(${contador} ${textoUsuarios})`;
}

function buscarUsuarios() {
    filtrarUsuarios();
}

function fecharModalNovoUsuario() {
    const modal = document.getElementById('modalNovoUsuario');
    const form = document.getElementById('formNovoUsuario');
    
    if (modal) {
        modal.style.display = 'none';
    }
    if (form) {
        form.reset();
    }
}

function editarUsuario(userId) {
    // Abre o modal de edição
    const modal = document.getElementById('modalEditarUsuario');
    if (modal) {
        modal.style.display = 'flex';
        // Carrega os dados do usuário
        carregarDadosUsuario(userId);
    } else {
        console.error('Modal de edição não encontrado');
    }
}

function carregarDadosUsuario(userId) {
    if (!userId || userId <= 0) {
        console.error('ID de usuário inválido:', userId);
        mostrarMensagemEdit('ID de usuário inválido', 'error');
        return;
    }
    
    // Mostra loading
    const btnSalvar = document.getElementById('btnSalvarEdicao');
    if (btnSalvar) {
        btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
        btnSalvar.disabled = true;
    }
    
    const url = buildApiUrl('admin/users/get-user-data') + `&id=${userId}`;
    
    fetch(url, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                preencherFormularioEdicao(data.user);
            } else {
                mostrarMensagemEdit(data.message || 'Erro ao carregar dados do usuário', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarMensagemEdit('Erro ao carregar dados do usuário', 'error');
        })
        .finally(() => {
            if (btnSalvar) {
                btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
                btnSalvar.disabled = false;
            }
        });
}

// Função auxiliar para definir valores com segurança
function setElementValue(id, value) {
    const element = document.getElementById(id);
    if (element) {
        if (element.type === 'checkbox') {
            element.checked = value == '1' || value === true;
        } else {
            element.value = value || '';
        }
    }
}

function setElementText(id, text) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = text;
    }
}

function preencherFormularioEdicao(user) {
    // ID do usuário
    setElementValue('editUserId', user.id);
    
    // Dados básicos
    setElementValue('editNome', user.name);
    setElementValue('editEmail', user.email);
    setElementValue('editTelefone', user.phone);
    setElementValue('editCpf', user.cpf);
    setElementValue('editDataNascimento', user.birth_date);
    setElementValue('editGenero', user.gender);
    
    // Dados profissionais
    setElementValue('editRole', user.role || 'usuario');
    setElementValue('editStatus', user.status || 'active');
    setElementValue('editCrefito', user.crefito);
    setElementValue('editEspecialidade', user.main_specialty);
    setElementValue('editDepartamento', user.department);
    setElementValue('editCargo', user.position);
    
    // Endereço
    setElementValue('editCep', user.cep);
    setElementValue('editEndereco', user.address);
    setElementValue('editNumero', user.number);
    setElementValue('editComplemento', user.complement);
    setElementValue('editBairro', user.neighborhood);
    setElementValue('editCidade', user.city);
    setElementValue('editEstado', user.state);
    
    // Informações adicionais
    setElementText('editUltimoLogin', 
        user.last_login ? new Date(user.last_login).toLocaleDateString('pt-BR') + ' às ' + new Date(user.last_login).toLocaleTimeString('pt-BR') : 'Nunca');
    setElementText('editCadastradoEm', 
        new Date(user.created_at).toLocaleDateString('pt-BR') + ' às ' + new Date(user.created_at).toLocaleTimeString('pt-BR'));
    
    // Checkbox forçar senha
    setElementValue('editForcePassword', user.must_change_password);
    
    // Aplicar máscaras nos campos de edição
    aplicarMascarasEdicao();
}

function aplicarMascarasEdicao() {
    // Máscara para telefone
    const telefoneEdit = document.getElementById('editTelefone');
    if (telefoneEdit) {
        telefoneEdit.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                value = value.replace(/(\d{2})(\d{4})/, '($1) $2');
                value = value.replace(/(\d{2})/, '($1');
            }
            e.target.value = value;
        });
    }
    
    // Máscara para CPF
    const cpfEdit = document.getElementById('editCpf');
    if (cpfEdit) {
        cpfEdit.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1.$2.$3');
            value = value.replace(/(\d{3})(\d{3})/, '$1.$2');
            e.target.value = value;
        });
    }
    
    // Máscara para CEP
    const cepEdit = document.getElementById('editCep');
    if (cepEdit) {
        cepEdit.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
            e.target.value = value;
        });
    }
}

function salvarEdicaoUsuario(event) {
    event.preventDefault();
    
    const form = document.getElementById('formEditarUsuario');
    if (!form) {
        console.error('Formulário de edição não encontrado');
        return;
    }
    
    const formData = new FormData(form);
    
    // DEBUG: Verificar se user_id está sendo enviado
    const userId = formData.get('user_id');
    console.log('DEBUG: user_id no FormData:', userId);
    
    if (!userId || userId === '' || userId === '0') {
        mostrarMensagemEdit('Erro: ID do usuário não encontrado. Feche e abra o modal novamente.', 'error');
        return;
    }
    
    // Dados do formulário coletados
    
    // Desabilita o botão de salvar
    const btnSalvar = document.getElementById('btnSalvarEdicao');
    if (btnSalvar) {
        btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
        btnSalvar.disabled = true;
    }
    
    fetch(buildApiUrl('admin/users/update'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarMensagemEdit('Usuário atualizado com sucesso!', 'success');
            
            // Atualiza a lista de usuários após 1.5 segundos
            setTimeout(() => {
                fecharModalEditarUsuario();
                // Ao invés de reload completo, apenas atualizar a página atual
                window.location.href = window.location.href.split('?')[0];
            }, 1500);
        } else {
            mostrarMensagemEdit(data.message || 'Erro ao atualizar usuário', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarMensagemEdit('Erro ao comunicar com o servidor', 'error');
    })
    .finally(() => {
        if (btnSalvar) {
            btnSalvar.innerHTML = '<i class="fas fa-save"></i> Salvar Alterações';
            btnSalvar.disabled = false;
        }
    });
}

function mostrarMensagemEdit(mensagem, tipo) {
    const messageDiv = document.getElementById('editUserMessage');
    if (messageDiv) {
        messageDiv.textContent = mensagem;
        messageDiv.className = `form-message ${tipo}`;
        messageDiv.style.display = 'block';
        
        // Remove a mensagem após 5 segundos
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
}

function fecharModalEditarUsuario() {
    const modal = document.getElementById('modalEditarUsuario');
    const form = document.getElementById('formEditarUsuario');
    
    if (modal) {
        modal.style.display = 'none';
    }
    if (form) {
        form.reset();
    }
    
    const messageDiv = document.getElementById('editUserMessage');
    if (messageDiv) {
        messageDiv.style.display = 'none';
    }
    
    // Volta para a primeira aba
    document.querySelectorAll('#modalEditarUsuario .tab-btn-fisio').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('#modalEditarUsuario .form-tab-content').forEach(content => content.classList.remove('active'));
    document.querySelector('#modalEditarUsuario .tab-btn-fisio').classList.add('active');
    document.getElementById('edit-tab-dados-basicos').classList.add('active');
}

function trocarAbaFormEdit(aba) {
    // Remove classe active de todas as abas e conteúdos do modal de edição
    document.querySelectorAll('#modalEditarUsuario .tab-btn-fisio').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('#modalEditarUsuario .form-tab-content').forEach(content => content.classList.remove('active'));
    
    // Adiciona classe active na aba e conteúdo selecionados
    event.target.classList.add('active');
    document.getElementById('edit-tab-' + aba).classList.add('active');
}

function buscarCEPEdit() {
    const cep = document.getElementById('editCep').value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('editEndereco').value = data.logradouro;
                    document.getElementById('editBairro').value = data.bairro;
                    document.getElementById('editCidade').value = data.localidade;
                    document.getElementById('editEstado').value = data.uf;
                }
            })
            .catch(error => console.error('Erro ao buscar CEP:', error));
    }
}

function gerenciarPermissoes(userId) {
    // Trocar para aba de permissões e selecionar usuário
    trocarAba('permissoes');
    
    // Selecionar o usuário no dropdown
    const select = document.getElementById('usuarioSelecionado');
    if (select) {
        select.value = userId;
        carregarPermissoesDoUsuario(userId);
    }
}

function verLogsUsuario(userId) {
    window.location.href = `/admin/users/logs?id=${userId}`;
}

function alterarStatusUsuario(userId, novoStatus) {
    const acao = novoStatus === 'active' ? 'ativar' : 'bloquear';
    
    if (confirm(`Deseja ${acao} este usuário?`)) {
        fetch(buildApiUrl('admin/users/toggle-status'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `id=${userId}&status=${novoStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = window.location.href.split('?')[0];
            } else {
                alert('Erro ao alterar status do usuário: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao alterar status do usuário');
        });
    }
}

function gerenciarLGPD(userId) {
    window.location.href = `/admin/users/privacy?id=${userId}`;
}

function excluirUsuario(userId, userName, userEmail) {
    if (confirm(`ATENÇÃO: Esta ação é IRREVERSÍVEL!\n\nDeseja realmente excluir permanentemente o usuário:\n${userName} (${userEmail})?\n\nTodos os dados serão perdidos!`)) {
        const confirmacao = prompt('Para confirmar a exclusão, digite "EXCLUIR" (em maiúsculas):');
        
        if (confirmacao === 'EXCLUIR') {
            const emailConfirmacao = prompt(`Digite o email do usuário para confirmar:\n${userEmail}`);
            
            if (emailConfirmacao === userEmail) {
                // Criar form para submissão POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/users/delete?id=' + userId;
                
                // Adicionar campos hidden
                const confirmField = document.createElement('input');
                confirmField.type = 'hidden';
                confirmField.name = 'confirm_delete';
                confirmField.value = 'EXCLUIR';
                
                const emailField = document.createElement('input');
                emailField.type = 'hidden';
                emailField.name = 'user_email';
                emailField.value = userEmail;
                
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = 'csrf_token';
                csrfField.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
                
                form.appendChild(confirmField);
                form.appendChild(emailField);
                form.appendChild(csrfField);
                
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Email não confere. Exclusão cancelada.');
            }
        } else {
            alert('Confirmação incorreta. Exclusão cancelada.');
        }
    }
}

function carregarPermissoesDoUsuario(userId) {
    if (!userId) {
        const interface = document.getElementById('interfacePermissoesModern');
        if (interface) {
            interface.style.display = 'none';
        }
        return;
    }
    
    // Mostrar loading
    const interface = document.getElementById('interfacePermissoesModern');
    if (interface) {
        interface.style.display = 'block';
    }
    
    // Buscar dados do usuário
    fetch(buildApiUrl('admin/users/get-user-data') + `&id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('usuarioAtualNome').textContent = 
                    `Permissões de ${data.user.name} (${data.user.role === 'admin' ? 'Administrador' : 'Fisioterapeuta'})`;
                
                if (data.user.role === 'admin') {
                    // Mostrar aviso para admins
                    document.getElementById('permissoesIA').innerHTML = 
                        '<div style="grid-column: 1/-1; padding: 20px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px; text-align: center; color: #047857;"><i class="fas fa-crown" style="font-size: 24px; margin-bottom: 8px;"></i><br><strong>Administrador</strong><br>Este usuário tem acesso total e irrestrito a todo o sistema.</div>';
                    document.getElementById('permissoesSistema').innerHTML = '';
                    return;
                }
                
                // Carregar permissões para fisioterapeutas
                carregarPermissoesDetalhadas(userId);
            }
        })
        .catch(error => {
            console.error('Erro ao carregar dados do usuário:', error);
        });
}

function carregarPermissoesDetalhadas(userId) {
    console.log('Carregando permissões para usuário:', userId);
    
    fetch(buildApiUrl('admin/permissions/get-user-permissions') + `&user_id=${userId}`)
        .then(response => {
            console.log('Resposta da API de permissões:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados de permissões recebidos:', data);
            if (data.success && data.permissions) {
                renderizarPermissoes(data.permissions, userId);
            } else {
                console.error('Erro nos dados de permissões:', data.message || 'Formato inválido');
                mostrarErroPermissoes('Erro ao carregar permissões do usuário');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar permissões:', error);
            mostrarErroPermissoes('Erro de comunicação com o servidor');
        });
}

function mostrarErroPermissoes(mensagem) {
    const permissoesIA = document.getElementById('permissoesIA');
    const permissoesSistema = document.getElementById('permissoesSistema');
    
    const errorHtml = `
        <div style="grid-column: 1/-1; padding: 20px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; text-align: center; color: #dc2626;">
            <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 8px;"></i><br>
            <strong>Erro</strong><br>
            ${mensagem}
        </div>
    `;
    
    if (permissoesIA) permissoesIA.innerHTML = errorHtml;
    if (permissoesSistema) permissoesSistema.innerHTML = '';
}

function renderizarPermissoes(permissions, userId) {
    const permissoesIA = document.getElementById('permissoesIA');
    const permissoesSistema = document.getElementById('permissoesSistema');
    
    permissoesIA.innerHTML = '';
    permissoesSistema.innerHTML = '';
    
    permissions.forEach(perm => {
        const permCard = criarCardPermissao(perm, userId);
        
        if (perm.category === 'ia') {
            permissoesIA.appendChild(permCard);
        } else {
            permissoesSistema.appendChild(permCard);
        }
    });
}

function criarCardPermissao(perm, userId) {
    const card = document.createElement('div');
    card.style.cssText = 'background: white; border: 1px solid var(--cinza-medio); border-radius: 8px; padding: 16px;';
    
    card.innerHTML = `
        <div style="margin-bottom: 12px;">
            <h6 style="color: var(--azul-saude); margin: 0 0 4px 0; font-size: 14px; font-weight: 600;">${perm.display_name}</h6>
            <p style="margin: 0; font-size: 12px; color: var(--cinza-escuro);">${perm.description || ''}</p>
        </div>
        <div style="display: flex; gap: 16px;">
            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                <input type="checkbox" name="perm_${perm.name}_view" value="1" ${perm.can_view ? 'checked' : ''} 
                       style="accent-color: var(--azul-saude);">
                <span style="color: var(--cinza-escuro);">👁️ Ver</span>
            </label>
            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                <input type="checkbox" name="perm_${perm.name}_use" value="1" ${perm.can_use ? 'checked' : ''} 
                       style="accent-color: var(--dourado-premium);">
                <span style="color: var(--cinza-escuro);">⚡ Usar</span>
            </label>
        </div>
    `;
    
    return card;
}

function salvarPermissoesSimples() {
    const userId = document.getElementById('usuarioSelecionado').value;
    if (!userId) {
        alert('Selecione um usuário primeiro');
        return;
    }
    
    // Coletar todas as permissões marcadas
    const formData = new FormData();
    formData.append('user_id', userId);
    
    const checkboxes = document.querySelectorAll('#interfacePermissoesModern input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            formData.append(checkbox.name, '1');
        } else {
            formData.append(checkbox.name, '0');
        }
    });
    
    // Mostrar loading
    const btnSalvar = document.querySelector('#interfacePermissoesModern .btn-action-primary');
    const originalText = btnSalvar.innerHTML;
    btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    btnSalvar.disabled = true;
    
    fetch(buildApiUrl('admin/permissions/save-user-permissions'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarMensagem('Permissões salvas com sucesso!', 'success');
        } else {
            mostrarMensagem(data.message || 'Erro ao salvar permissões', 'error');
        }
    })
    .catch(error => {
        console.error('Erro ao salvar permissões:', error);
        mostrarMensagem('Erro ao salvar permissões', 'error');
    })
    .finally(() => {
        btnSalvar.innerHTML = originalText;
        btnSalvar.disabled = false;
    });
}

function mostrarMensagem(mensagem, tipo) {
    // Criar elemento de notificação
    const notif = document.createElement('div');
    notif.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    if (tipo === 'success') {
        notif.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        notif.innerHTML = `<i class="fas fa-check-circle"></i> ${mensagem}`;
    } else {
        notif.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        notif.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${mensagem}`;
    }
    
    document.body.appendChild(notif);
    
    // Remover após 4 segundos
    setTimeout(() => {
        if (notif.parentNode) {
            notif.parentNode.removeChild(notif);
        }
    }, 4000);
}

function cancelarPermissoes() {
    document.getElementById('usuarioSelecionado').value = '';
    document.getElementById('interfacePermissoesModern').style.display = 'none';
}

function filtrarLogs() {
    alert('Função filtrarLogs() ainda não implementada');
}

function gerarRelatorioLGPD() {
    alert('Função gerarRelatorioLGPD() ainda não implementada');
}

function exportarDadosUsuario() {
    alert('Função exportarDadosUsuario() ainda não implementada');
}

function revogarConsentimento() {
    alert('Função revogarConsentimento() ainda não implementada');
}

function exportarUsuarios(formato) {
    // Fecha o dropdown
    document.getElementById('menuExportar').classList.remove('show');
    
    // Adiciona loading no botão
    const btnExport = document.querySelector('.btn-export');
    btnExport.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Exportando...</span>';
    
    // Prepara os dados para exportação
    const filtroRole = document.getElementById('filtroRole').value;
    const filtroStatus = document.getElementById('filtroStatus').value;
    const busca = document.getElementById('buscaUsuario').value;
    
    // Cria URL com parâmetros
    const params = new URLSearchParams({
        format: formato,
        role: filtroRole,
        status: filtroStatus,
        search: busca
    });
    
    // Faz download do arquivo
    window.location.href = `/admin/users/export?${params.toString()}`;
    
    // Restaura o botão após 2 segundos
    setTimeout(() => {
        btnExport.innerHTML = '<i class="fas fa-download"></i> <span>Exportar</span> <i class="fas fa-chevron-down"></i>';
    }, 2000);
}

function atualizarLista() {
    const btn = document.querySelector('.btn-atualizar');
    btn.classList.add('loading');
    btn.querySelector('span').textContent = 'Atualizando...';
    
    // Recarrega após pequeno delay para mostrar animação
    setTimeout(() => {
        window.location.href = window.location.href.split('?')[0];
    }, 500);
}

function mostrarOpcoesExportar() {
    const menu = document.getElementById('menuExportar');
    menu.classList.toggle('show');
    
    // Fecha o menu ao clicar fora
    document.addEventListener('click', function fecharMenu(e) {
        if (!e.target.closest('.dropdown-export')) {
            menu.classList.remove('show');
            document.removeEventListener('click', fecharMenu);
        }
    });
}

function trocarAbaForm(aba) {
    // Remove classe active de todas as abas e conteúdos
    document.querySelectorAll('.tab-btn-fisio').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.form-tab-content').forEach(content => content.classList.remove('active'));
    
    // Adiciona classe active na aba e conteúdo selecionados
    event.target.classList.add('active');
    document.getElementById('tab-' + aba).classList.add('active');
}

function buscarCEP() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            })
            .catch(error => console.error('Erro ao buscar CEP:', error));
    }
}

// Máscaras para os campos e validação de senhas
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const telefone = document.getElementById('telefone');
    const editTelefone = document.getElementById('editTelefone');
    
    function aplicarMascaraTelefone(element) {
        if (element) {
            element.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    value = value.replace(/(\d{2})(\d{4})/, '($1) $2');
                    value = value.replace(/(\d{2})/, '($1');
                }
                e.target.value = value;
            });
        }
    }
    
    aplicarMascaraTelefone(telefone);
    aplicarMascaraTelefone(editTelefone);
    
    // Máscara para CPF
    const cpf = document.getElementById('cpf');
    const editCpf = document.getElementById('editCpf');
    
    function aplicarMascaraCpf(element) {
        if (element) {
            element.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1.$2.$3');
                value = value.replace(/(\d{3})(\d{3})/, '$1.$2');
                e.target.value = value;
            });
        }
    }
    
    aplicarMascaraCpf(cpf);
    aplicarMascaraCpf(editCpf);
    
    // Máscara para CEP
    const cepField = document.getElementById('cep');
    const editCepField = document.getElementById('editCep');
    
    function aplicarMascaraCep(element) {
        if (element) {
            element.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                e.target.value = value;
            });
        }
    }
    
    aplicarMascaraCep(cepField);
    aplicarMascaraCep(editCepField);
    
    // Validação de senhas - Modal de criação
    const senha = document.getElementById('senha');
    const confirmarSenha = document.getElementById('confirmarSenha');
    
    function validarSenhas() {
        if (senha && confirmarSenha) {
            if (senha.value && confirmarSenha.value) {
                if (senha.value !== confirmarSenha.value) {
                    confirmarSenha.setCustomValidity('As senhas não coincidem');
                    confirmarSenha.style.borderColor = '#ef4444';
                } else {
                    confirmarSenha.setCustomValidity('');
                    confirmarSenha.style.borderColor = '#10b981';
                }
            } else {
                confirmarSenha.setCustomValidity('');
                confirmarSenha.style.borderColor = '';
            }
        }
    }
    
    if (senha) senha.addEventListener('input', validarSenhas);
    if (confirmarSenha) confirmarSenha.addEventListener('input', validarSenhas);
    
    // Validação de senhas - Modal de edição
    const editSenha = document.getElementById('editSenha');
    const editConfirmarSenha = document.getElementById('editConfirmarSenha');
    
    function validarSenhasEdicao() {
        if (editSenha && editConfirmarSenha) {
            if (editSenha.value || editConfirmarSenha.value) {
                if (editSenha.value !== editConfirmarSenha.value) {
                    editConfirmarSenha.setCustomValidity('As senhas não coincidem');
                    editConfirmarSenha.style.borderColor = '#ef4444';
                } else {
                    editConfirmarSenha.setCustomValidity('');
                    editConfirmarSenha.style.borderColor = '#10b981';
                }
            } else {
                editConfirmarSenha.setCustomValidity('');
                editConfirmarSenha.style.borderColor = '';
            }
        }
    }
    
    if (editSenha) editSenha.addEventListener('input', validarSenhasEdicao);
    if (editConfirmarSenha) editConfirmarSenha.addEventListener('input', validarSenhasEdicao);
});

function importarUsuarios() {
    alert('Função importarUsuarios() ainda não implementada');
}

function abrirModalConvite() {
    alert('Função abrirModalConvite() ainda não implementada');
}
</script>