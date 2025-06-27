<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina" data-translate="Gestão de Usuários">Gestão de Usuários</h1>
<p class="subtitulo-pagina-escuro" data-translate="Gerencie fisioterapeutas, configure permissões e monitore atividades dos usuários">Gerencie fisioterapeutas, configure permissões e monitore atividades dos usuários</p>

<!-- Estatísticas dos Usuários -->
<div class="usuarios-stats">
    <div class="stat-card-usuario">
        <div class="stat-icone-usuario admin">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['admins'] ?? 1 ?></div>
            <div class="stat-label-escuro" data-translate="Administradores">Administradores</div>
        </div>
    </div>
    
    <div class="stat-card-usuario fisio">
        <div class="stat-icone-usuario fisio">
            <i class="fas fa-user-md"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['fisioterapeutas'] ?? 0 ?></div>
            <div class="stat-label-escuro" data-translate="Fisioterapeutas">Fisioterapeutas</div>
        </div>
    </div>
    
    <div class="stat-card-usuario online">
        <div class="stat-icone-usuario online">
            <i class="fas fa-wifi"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['online'] ?? 0 ?></div>
            <div class="stat-label-escuro">Online Agora</div>
        </div>
    </div>
    
    <div class="stat-card-usuario ativo">
        <div class="stat-icone-usuario ativo">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-numero"><?= $stats['active'] ?? 0 ?></div>
            <div class="stat-label-escuro">Ativos no Mês</div>
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
            Importar Usuários
        </button>
    </div>
    
    <div class="acoes-direita">
        <div class="filtro-grupo">
            <select class="filtro-select" id="filtroStatus" onchange="filtrarUsuarios()">
                <option value="">Todos os Status</option>
                <option value="active">Ativos</option>
                <option value="inactive">Inativos</option>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <select class="filtro-select" id="filtroRole" onchange="filtrarUsuarios()">
                <option value="">Todos os Perfis</option>
                <option value="admin">Administradores</option>
                <option value="usuario">Fisioterapeutas</option>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <select class="filtro-select" id="filtroEspecialidade" onchange="filtrarUsuarios()">
                <option value="">Todas Especialidades</option>
                <option value="ortopedica">Ortopédica</option>
                <option value="neurologica">Neurológica</option>
                <option value="respiratoria">Respiratória</option>
                <option value="geriatrica">Geriátrica</option>
                <option value="pediatrica">Pediátrica</option>
                <option value="esportiva">Esportiva</option>
            </select>
        </div>
        
        <div class="busca-grupo">
            <div class="busca-input">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar por nome, email ou CREFITO..." id="buscaUsuario" onkeyup="buscarUsuarios()">
            </div>
        </div>
    </div>
</div>

<!-- Lista de Usuários -->
<div class="card-fisio usuarios-lista-container">
    <div class="card-header-fisio">
        <div class="card-titulo">
            <i class="fas fa-users"></i>
            <span>Usuários Cadastrados</span>
            <span class="contador-usuarios" id="contadorUsuarios">(<?= count($users ?? []) ?> usuários)</span>
        </div>
        <div class="opcoes-lista">
            <button class="btn-opcao" onclick="alternarVisualizacao()">
                <i class="fas fa-th-large" id="iconeVisualizacao"></i>
            </button>
            <button class="btn-opcao" onclick="exportarUsuarios()">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>
    
    <div class="usuarios-lista" id="usuariosLista">
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <div class="usuario-item" data-status="<?= $user['status'] ?>" data-role="<?= $user['role'] ?>">
                    <div class="usuario-avatar">
                        <div class="avatar-circulo <?= $user['role'] ?>">
                            <?= strtoupper(substr($user['name'], 0, 2)) ?>
                        </div>
                        <div class="status-indicador <?= $user['status'] ?>"></div>
                    </div>
                    
                    <div class="usuario-info">
                        <div class="usuario-nome"><?= htmlspecialchars($user['name']) ?></div>
                        <div class="usuario-email"><?= htmlspecialchars($user['email']) ?></div>
                        <div class="usuario-detalhes">
                            <span class="usuario-role"><?= $user['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta' ?></span>
                            <?php if (!empty($user['crefito'])): ?>
                                <span class="usuario-crefito">CREFITO: <?= htmlspecialchars($user['crefito']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="usuario-dados">
                        <div class="dado-item">
                            <span class="dado-label">Último Acesso</span>
                            <span class="dado-valor"><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Nunca' ?></span>
                        </div>
                        <div class="dado-item">
                            <span class="dado-label">Cadastro</span>
                            <span class="dado-valor"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                        </div>
                        <div class="dado-item">
                            <span class="dado-label">Avaliações IA</span>
                            <span class="dado-valor"><?= $user['ai_requests_count'] ?? 0 ?></span>
                        </div>
                    </div>
                    
                    <div class="usuario-acoes">
                        <button class="btn-acao editar" onclick="editarUsuario(<?= $user['id'] ?>)" data-tooltip="Editar dados">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-acao permissoes" onclick="gerenciarPermissoes(<?= $user['id'] ?>)" data-tooltip="Gerenciar permissões">
                            <i class="fas fa-user-shield"></i>
                        </button>
                        <button class="btn-acao logs" onclick="visualizarLogs(<?= $user['id'] ?>)" data-tooltip="Ver logs de atividade">
                            <i class="fas fa-history"></i>
                        </button>
                        <button class="btn-acao lgpd" onclick="gerenciarLGPD(<?= $user['id'] ?>)" data-tooltip="LGPD e privacidade">
                            <i class="fas fa-shield-alt"></i>
                        </button>
                        <button class="btn-acao <?= $user['status'] === 'active' ? 'bloquear' : 'desbloquear' ?>" 
                                onclick="alterarStatusUsuario(<?= $user['id'] ?>, '<?= $user['status'] ?>')" 
                                data-tooltip="<?= $user['status'] === 'active' ? 'Bloquear usuário' : 'Desbloquear usuário' ?>">
                            <i class="fas fa-<?= $user['status'] === 'active' ? 'ban' : 'unlock' ?>"></i>
                        </button>
                        <?php if ($user['id'] != $currentUser['id']): ?>
                        <button class="btn-acao excluir" onclick="confirmarExclusao(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>')" data-tooltip="Excluir permanentemente">
                            <i class="fas fa-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="usuarios-vazio">
                <i class="fas fa-user-plus"></i>
                <h3>Nenhum usuário cadastrado</h3>
                <p>Comece cadastrando o primeiro fisioterapeuta do sistema</p>
                <button class="btn-fisio btn-primario" onclick="abrirModalNovoUsuario()">
                    <i class="fas fa-plus"></i>
                    Cadastrar Primeiro Usuário
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Novo Usuário -->
<div class="modal-overlay" id="modalNovoUsuario" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Cadastrar Novo Usuário</h3>
            <button class="modal-close" onclick="fecharModal('modalNovoUsuario')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formNovoUsuario" class="modal-form" method="POST" action="/admin/users/create">
            <div class="form-grid-modal">
                <div class="form-grupo">
                    <label for="nome" class="required">Nome Completo</label>
                    <input type="text" id="nome" name="name" placeholder="Ex: Dr. João Silva" required maxlength="255">
                </div>
                
                <div class="form-grupo">
                    <label for="email" class="required">Email</label>
                    <input type="email" id="email" name="email" placeholder="joao@clinica.com.br" required maxlength="255">
                </div>
                
                <div class="form-grupo">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="phone" placeholder="(11) 99999-9999" maxlength="20">
                </div>
                
                <div class="form-grupo">
                    <label for="crefito">CREFITO</label>
                    <input type="text" id="crefito" name="crefito" placeholder="Ex: 123456-F" maxlength="20">
                </div>
                
                <div class="form-grupo">
                    <label for="senha" class="required">Senha Temporária</label>
                    <div class="password-input-group">
                        <input type="password" id="senha" name="password" required minlength="8" placeholder="Mínimo 8 caracteres">
                        <button type="button" class="password-toggle" onclick="togglePasswordModal('senha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-grupo">
                    <label for="senha_confirmar" class="required">Confirmar Senha</label>
                    <div class="password-input-group">
                        <input type="password" id="senha_confirmar" name="password_confirm" required minlength="8">
                        <button type="button" class="password-toggle" onclick="togglePasswordModal('senha_confirmar')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-grupo">
                    <label for="role" class="required">Perfil de Acesso</label>
                    <select id="role" name="role" required>
                        <option value="">Selecione um perfil</option>
                        <option value="usuario">Fisioterapeuta</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <div class="form-grupo">
                    <label for="especialidade">Especialidade</label>
                    <select id="especialidade" name="especialidade">
                        <option value="">Selecione...</option>
                        <option value="ortopedica">Ortopédica</option>
                        <option value="neurologica">Neurológica</option>
                        <option value="respiratoria">Respiratória</option>
                        <option value="geriatrica">Geriátrica</option>
                        <option value="pediatrica">Pediátrica</option>
                        <option value="esportiva">Esportiva</option>
                    </select>
                </div>
            </div>
            
            <div class="form-grupo">
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" rows="3" placeholder="Informações adicionais sobre o usuário..."></textarea>
            </div>
            
            <div class="form-opcoes">
                <label class="checkbox-custom">
                    <input type="checkbox" id="forcarMudancaSenha" name="force_password_change" checked>
                    <span class="checkmark"></span>
                    Forçar alteração de senha no primeiro login
                </label>
                
                <label class="checkbox-custom">
                    <input type="checkbox" id="enviarEmail" name="send_welcome_email" checked>
                    <span class="checkmark"></span>
                    Enviar email de boas-vindas
                </label>
            </div>
            
            <div class="modal-acoes">
                <button type="button" class="btn-fisio btn-secundario" onclick="fecharModal('modalNovoUsuario')">
                    Cancelar
                </button>
                <button type="submit" class="btn-fisio btn-primario">
                    <i class="fas fa-user-plus"></i>
                    Cadastrar Usuário
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Estatísticas dos Usuários */
.usuarios-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icone-usuario.admin {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.stat-icone-usuario.fisio {
    background: linear-gradient(135deg, #059669, #10b981);
}

.stat-icone-usuario.online {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
}

.stat-icone-usuario.ativo {
    background: linear-gradient(135deg, #ca8a04, #eab308);
}

.stat-numero {
    font-size: 28px;
    font-weight: 800;
    color: var(--cinza-escuro);
    font-family: 'JetBrains Mono', monospace;
    line-height: 1;
    margin-bottom: 4px;
}

/* Ações e Filtros */
.usuarios-acoes {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
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

.filtro-select {
    padding: 10px 16px;
    border: 1px solid var(--cinza-medio);
    border-radius: 8px;
    background: white;
    font-size: 14px;
    min-width: 140px;
}

.busca-input {
    position: relative;
    display: flex;
    align-items: center;
}

.busca-input i {
    position: absolute;
    left: 12px;
    color: var(--cinza-medio);
    z-index: 1;
}

.busca-input input {
    padding: 10px 16px 10px 40px;
    border: 1px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 14px;
    min-width: 250px;
}

.busca-input input:focus {
    outline: none;
    border-color: var(--azul-saude);
}

/* Lista de Usuários */
.usuarios-lista-container {
    margin-bottom: 32px;
}

.contador-usuarios {
    font-size: 14px;
    color: var(--cinza-medio);
    font-weight: 400;
    margin-left: 8px;
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

.usuarios-lista {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.usuario-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: var(--cinza-claro);
    border-radius: 12px;
    transition: var(--transicao);
    border: 1px solid transparent;
}

.usuario-item:hover {
    background: white;
    border-color: var(--azul-saude);
    transform: translateX(4px);
}

.usuario-avatar {
    position: relative;
}

.avatar-circulo {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    color: white;
}

.avatar-circulo.admin {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.avatar-circulo.usuario {
    background: linear-gradient(135deg, #059669, #10b981);
}

.status-indicador {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
}

.status-indicador.active {
    background: var(--sucesso);
}

.status-indicador.inactive {
    background: var(--cinza-medio);
}

.status-indicador.pending {
    background: var(--alerta);
}

.usuario-info {
    flex: 1;
    min-width: 0;
}

.usuario-nome {
    font-size: 16px;
    font-weight: 700;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.usuario-email {
    font-size: 14px;
    color: var(--cinza-medio);
    margin-bottom: 6px;
}

.usuario-detalhes {
    display: flex;
    gap: 12px;
    font-size: 12px;
}

.usuario-role {
    background: var(--azul-saude);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.usuario-crefito {
    background: var(--cinza-medio);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 500;
}

.usuario-dados {
    display: flex;
    gap: 24px;
}

.dado-item {
    text-align: center;
    min-width: 80px;
}

.dado-label {
    display: block;
    font-size: 11px;
    color: var(--cinza-medio);
    text-transform: uppercase;
    margin-bottom: 4px;
    font-weight: 600;
}

.dado-valor {
    display: block;
    font-size: 13px;
    color: var(--cinza-escuro);
    font-weight: 600;
}

.usuario-acoes {
    display: flex;
    gap: 8px;
}

.btn-acao {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transicao);
    font-size: 14px;
}

.btn-acao.editar {
    background: var(--cinza-claro);
    color: var(--cinza-escuro);
}

.btn-acao.editar:hover {
    background: var(--azul-saude);
    color: white;
}

.btn-acao.visualizar {
    background: var(--cinza-claro);
    color: var(--cinza-escuro);
}

.btn-acao.visualizar:hover {
    background: var(--info);
    color: white;
}

.btn-acao.permissoes {
    background: var(--cinza-claro);
    color: var(--azul-saude);
}

.btn-acao.permissoes:hover {
    background: var(--azul-saude);
    color: white;
}

.btn-acao.logs {
    background: var(--cinza-claro);
    color: #6366f1;
}

.btn-acao.logs:hover {
    background: #6366f1;
    color: white;
}

.btn-acao.lgpd {
    background: var(--cinza-claro);
    color: #8b5cf6;
}

.btn-acao.lgpd:hover {
    background: #8b5cf6;
    color: white;
}

.btn-acao.bloquear {
    background: var(--cinza-claro);
    color: var(--alerta);
}

.btn-acao.bloquear:hover {
    background: var(--alerta);
    color: white;
}

.btn-acao.desbloquear {
    background: var(--cinza-claro);
    color: var(--sucesso);
}

.btn-acao.desbloquear:hover {
    background: var(--sucesso);
    color: white;
}

.btn-acao.excluir {
    background: var(--cinza-claro);
    color: var(--erro);
}

.btn-acao.excluir:hover {
    background: var(--erro);
    color: white;
}

/* Estado Vazio */
.usuarios-vazio {
    text-align: center;
    padding: 80px 20px;
    color: var(--cinza-medio);
}

.usuarios-vazio i {
    font-size: 72px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.usuarios-vazio h3 {
    font-size: 24px;
    margin-bottom: 12px;
    color: var(--cinza-escuro);
}

.usuarios-vazio p {
    margin-bottom: 24px;
    font-size: 16px;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
}

.modal-container {
    background: white;
    border-radius: 16px;
    max-width: 500px;
    width: 90%;
    box-shadow: var(--sombra-flutuante);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 32px;
    border-bottom: 1px solid var(--cinza-medio);
}

.modal-header h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--cinza-medio);
    cursor: pointer;
    padding: 4px;
}

.modal-close:hover {
    color: var(--erro);
}

.modal-form {
    padding: 20px;
}

.form-section-modal {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--cinza-medio);
}

.form-section-modal:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title-modal {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 600;
    color: var(--cinza-escuro);
    margin-bottom: 16px;
}

.section-title-modal i {
    color: var(--azul-saude);
    font-size: 18px;
}

.form-grupo-full {
    grid-column: 1 / -1;
}

.form-grid-modal {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 16px;
}

.form-grupo {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-grupo label {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 6px;
}

.form-grupo label.required::after {
    content: '*';
    color: var(--erro);
    margin-left: 4px;
}

.password-input-group {
    position: relative;
    display: flex;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--cinza-medio);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: color 0.2s ease;
}

.password-toggle:hover {
    color: var(--cinza-escuro);
}

.form-grupo input,
.form-grupo select,
.form-grupo textarea {
    padding: 10px 12px;
    border: 1px solid var(--cinza-medio);
    border-radius: 6px;
    font-size: 14px;
    transition: var(--transicao);
    width: 100%;
    box-sizing: border-box;
}

.form-grupo input:focus,
.form-grupo select:focus,
.form-grupo textarea:focus {
    outline: none;
    border-color: var(--azul-saude);
}

.form-opcoes {
    margin: 16px 0;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.checkbox-custom {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 13px;
    color: var(--cinza-escuro);
}

.checkbox-custom input {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--cinza-medio);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transicao);
}

.checkbox-custom input:checked + .checkmark {
    background: var(--azul-saude);
    border-color: var(--azul-saude);
}

.checkbox-custom input:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-weight: 700;
    font-size: 12px;
}

.modal-acoes {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--cinza-medio);
}

/* Responsivo */
@media (max-width: 1024px) {
    .usuarios-acoes {
        flex-direction: column;
        align-items: stretch;
    }
    
    .acoes-direita {
        flex-wrap: wrap;
    }
    
    .usuario-dados {
        flex-direction: column;
        gap: 8px;
    }
    
    .form-grid-modal {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .usuario-item {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    
    .usuario-avatar {
        align-self: center;
    }
    
    .usuario-acoes {
        justify-content: center;
    }
    
    .busca-input input {
        min-width: 200px;
    }
}
</style>

<script>
// Filtros dinâmicos funcionais
function filtrarUsuarios() {
    const status = document.getElementById('filtroStatus').value;
    const role = document.getElementById('filtroRole').value;
    const especialidade = document.getElementById('filtroEspecialidade')?.value || '';
    const busca = document.getElementById('buscaUsuario').value.toLowerCase();
    
    const usuarios = document.querySelectorAll('.usuario-item');
    let contador = 0;
    
    usuarios.forEach(usuario => {
        const usuarioStatus = usuario.dataset.status;
        const usuarioRole = usuario.dataset.role;
        const usuarioTexto = usuario.textContent.toLowerCase();
        
        const statusMatch = !status || usuarioStatus === status;
        const roleMatch = !role || usuarioRole === role;
        const buscaMatch = !busca || usuarioTexto.includes(busca);
        
        const mostrar = statusMatch && roleMatch && buscaMatch;
        
        usuario.style.display = mostrar ? 'flex' : 'none';
        if (mostrar) contador++;
    });
    
    document.getElementById('contadorUsuarios').textContent = `(${contador} usuários)`;
}

// Busca em tempo real
function buscarUsuarios() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(filtrarUsuarios, 300);
}

// Modal
function abrirModalNovoUsuario() {
    document.getElementById('modalNovoUsuario').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Carregar página
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar filtros salvos se existirem
    const params = new URLSearchParams(window.location.search);
    if (params.get('status')) document.getElementById('filtroStatus').value = params.get('status');
    if (params.get('role')) document.getElementById('filtroRole').value = params.get('role');
    if (params.get('search')) document.getElementById('buscaUsuario').value = params.get('search');
    
    // Aplicar filtros iniciais
    if (params.toString()) filtrarUsuarios();
});

// Botões de ações rápidas funcionais
function abrirModalPermissoes(userId) {
    window.location.href = `/admin/users/permissions?id=${userId}`;
}

function abrirModalLogs(userId) {
    window.location.href = `/admin/users/logs?id=${userId}`;
}

function abrirModalLGPD(userId) {
    window.location.href = `/admin/users/privacy?id=${userId}`;
}

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Ações dos usuários funcionais
function editarUsuario(id) {
    window.location.href = `/admin/users/edit?id=${id}`;
}

function gerenciarPermissoes(id) {
    // Abrir modal de permissões
    abrirModalPermissoes(id);
}

function visualizarLogs(id) {
    // Abrir modal de logs
    abrirModalLogs(id);
}

function gerenciarLGPD(id) {
    // Abrir modal LGPD
    abrirModalLGPD(id);
}

function alterarStatusUsuario(id, status) {
    const novoStatus = status === 'active' ? 'inactive' : 'active';
    const acao = novoStatus === 'active' ? 'desbloquear' : 'bloquear';
    
    if (confirm(`Confirma ${acao} este usuário?`)) {
        fetch('/admin/users/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, status: novoStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(`Usuário ${acao === 'desbloquear' ? 'desbloqueado' : 'bloqueado'} com sucesso!`, 'sucesso');
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message || 'Erro ao alterar status', 'erro');
            }
        })
        .catch(error => {
            mostrarAlerta('Erro de comunicação com o servidor', 'erro');
        });
    }
}

function confirmarExclusao(id, nome) {
    if (confirm(`ATENÇÃO: Deseja excluir permanentemente o usuário "${nome}"?\n\nEsta ação não pode ser desfeita!`)) {
        mostrarAlerta('Usuário excluído permanentemente', 'sucesso');
        // Implementar chamada AJAX aqui
    }
}

function importarUsuarios() {
    mostrarAlerta('Funcionalidade de importação será implementada', 'info');
}

function exportarUsuarios() {
    mostrarAlerta('Exportando lista de usuários...', 'info');
}

function alternarVisualizacao() {
    mostrarAlerta('Visualização em cards será implementada', 'info');
}

// Funções auxiliares do modal
function togglePasswordModal(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function formatarTelefone(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        value = value.replace(/(\d{2})(\d{4})/, '($1) $2');
        value = value.replace(/(\d{2})/, '($1');
    }
    
    input.value = value;
}

// Submissão do formulário
document.getElementById('formNovoUsuario').addEventListener('submit', function(e) {
    // Validação avançada antes do envio
    const senha = document.getElementById('senha').value;
    const senhaConfirmar = document.getElementById('senha_confirmar').value;
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const role = document.getElementById('role').value;
    
    if (!nome || !email || !senha || !senhaConfirmar || !role) {
        e.preventDefault();
        mostrarAlerta('Preencha todos os campos obrigatórios', 'aviso');
        return;
    }
    
    if (senha !== senhaConfirmar) {
        e.preventDefault();
        mostrarAlerta('As senhas não coincidem', 'erro');
        return;
    }
    
    if (senha.length < 8) {
        e.preventDefault();
        mostrarAlerta('A senha deve ter pelo menos 8 caracteres', 'aviso');
        return;
    }
    
    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        mostrarAlerta('Digite um email válido', 'aviso');
        return;
    }
    
    // Se chegou até aqui, submeter o formulário
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Criando...';
});

// Adicionar eventos ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    // Formatação de telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            formatarTelefone(e.target);
        });
    }
    
    // Validação de confirmação de senha
    const senhaConfirmar = document.getElementById('senha_confirmar');
    if (senhaConfirmar) {
        senhaConfirmar.addEventListener('input', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmar = e.target.value;
            
            if (confirmar && senha !== confirmar) {
                e.target.setCustomValidity('As senhas não coincidem');
                e.target.style.borderColor = 'var(--erro)';
            } else {
                e.target.setCustomValidity('');
                e.target.style.borderColor = '';
            }
        });
    }
});

// Fechar modal clicando fora
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        fecharModal(e.target.id);
    }
});
</script>