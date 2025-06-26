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
            <div class="stat-numero"><?= $stats['professionals'] ?? 0 ?></div>
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
            Novo Fisioterapeuta
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
                <option value="pending">Pendentes</option>
            </select>
        </div>
        
        <div class="filtro-grupo">
            <select class="filtro-select" id="filtroRole" onchange="filtrarUsuarios()">
                <option value="">Todos os Perfis</option>
                <option value="admin">Administradores</option>
                <option value="professional">Fisioterapeutas</option>
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
                        <button class="btn-acao visualizar" onclick="visualizarUsuario(<?= $user['id'] ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-acao <?= $user['status'] === 'active' ? 'pausar' : 'ativar' ?>" 
                                onclick="alterarStatusUsuario(<?= $user['id'] ?>, '<?= $user['status'] ?>')" 
                                data-tooltip="<?= $user['status'] === 'active' ? 'Desativar' : 'Ativar' ?>">
                            <i class="fas fa-<?= $user['status'] === 'active' ? 'pause' : 'play' ?>"></i>
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
            <h3>Cadastrar Novo Fisioterapeuta</h3>
            <button class="modal-close" onclick="fecharModal('modalNovoUsuario')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="formNovoUsuario" class="modal-form">
            <div class="form-grid-modal">
                <div class="form-grupo">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Dr. João Silva" required>
                </div>
                
                <div class="form-grupo">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="joao@clinica.com.br" required>
                </div>
                
                <div class="form-grupo">
                    <label for="crefito">CREFITO</label>
                    <input type="text" id="crefito" name="crefito" placeholder="Ex: 123456-F">
                </div>
                
                <div class="form-grupo">
                    <label for="especialidade">Especialidade</label>
                    <select id="especialidade" name="especialidade">
                        <option value="">Selecione...</option>
                        <option value="ortopedica">Fisioterapia Ortopédica</option>
                        <option value="neurologica">Fisioterapia Neurológica</option>
                        <option value="respiratoria">Fisioterapia Respiratória</option>
                        <option value="geriatrica">Fisioterapia Geriátrica</option>
                        <option value="pediatrica">Fisioterapia Pediátrica</option>
                        <option value="esportiva">Fisioterapia Esportiva</option>
                    </select>
                </div>
                
                <div class="form-grupo">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(11) 99999-9999">
                </div>
                
                <div class="form-grupo">
                    <label for="role">Perfil *</label>
                    <select id="role" name="role" required>
                        <option value="professional">Fisioterapeuta</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>
            
            <div class="form-grupo">
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" rows="3" placeholder="Informações adicionais sobre o usuário..."></textarea>
            </div>
            
            <div class="form-opcoes">
                <label class="checkbox-custom">
                    <input type="checkbox" id="enviarEmail" name="enviar_email" checked>
                    <span class="checkmark"></span>
                    Enviar email de boas-vindas com dados de acesso
                </label>
            </div>
            
            <div class="modal-acoes">
                <button type="button" class="btn-fisio btn-secundario" onclick="fecharModal('modalNovoUsuario')">
                    Cancelar
                </button>
                <button type="submit" class="btn-fisio btn-primario">
                    <i class="fas fa-user-plus"></i>
                    Cadastrar Fisioterapeuta
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

.avatar-circulo.professional {
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

.btn-acao.pausar {
    background: var(--cinza-claro);
    color: var(--alerta);
}

.btn-acao.pausar:hover {
    background: var(--alerta);
    color: white;
}

.btn-acao.ativar {
    background: var(--cinza-claro);
    color: var(--sucesso);
}

.btn-acao.ativar:hover {
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
    border-radius: 20px;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
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
    padding: 32px;
}

.form-grid-modal {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-grupo {
    display: flex;
    flex-direction: column;
    gap: 6px;
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
    border: 1px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 14px;
    transition: var(--transicao);
}

.form-grupo input:focus,
.form-grupo select:focus,
.form-grupo textarea:focus {
    outline: none;
    border-color: var(--azul-saude);
}

.form-opcoes {
    margin: 20px 0;
}

.checkbox-custom {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-size: 14px;
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
    margin-top: 24px;
    padding-top: 20px;
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
// Filtrar usuários
function filtrarUsuarios() {
    const status = document.getElementById('filtroStatus').value;
    const role = document.getElementById('filtroRole').value;
    const usuarios = document.querySelectorAll('.usuario-item');
    let contador = 0;
    
    usuarios.forEach(usuario => {
        const usuarioStatus = usuario.dataset.status;
        const usuarioRole = usuario.dataset.role;
        
        const mostrar = (!status || usuarioStatus === status) && 
                       (!role || usuarioRole === role);
        
        usuario.style.display = mostrar ? 'flex' : 'none';
        if (mostrar) contador++;
    });
    
    document.getElementById('contadorUsuarios').textContent = `(${contador} usuários)`;
}

// Buscar usuários
function buscarUsuarios() {
    const termo = document.getElementById('buscaUsuario').value.toLowerCase();
    const usuarios = document.querySelectorAll('.usuario-item');
    let contador = 0;
    
    usuarios.forEach(usuario => {
        const nome = usuario.querySelector('.usuario-nome').textContent.toLowerCase();
        const email = usuario.querySelector('.usuario-email').textContent.toLowerCase();
        const crefito = usuario.querySelector('.usuario-crefito')?.textContent.toLowerCase() || '';
        
        const mostrar = nome.includes(termo) || email.includes(termo) || crefito.includes(termo);
        
        usuario.style.display = mostrar ? 'flex' : 'none';
        if (mostrar) contador++;
    });
    
    document.getElementById('contadorUsuarios').textContent = `(${contador} usuários)`;
}

// Modal
function abrirModalNovoUsuario() {
    document.getElementById('modalNovoUsuario').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Ações dos usuários
function editarUsuario(id) {
    mostrarAlerta('Função de edição será implementada', 'info');
}

function visualizarUsuario(id) {
    mostrarAlerta('Visualização de perfil será implementada', 'info');
}

function alterarStatusUsuario(id, status) {
    const novoStatus = status === 'active' ? 'inactive' : 'active';
    const acao = novoStatus === 'active' ? 'ativar' : 'desativar';
    
    if (confirm(`Confirma ${acao} este usuário?`)) {
        mostrarAlerta(`Usuário ${acao === 'ativar' ? 'ativado' : 'desativado'} com sucesso!`, 'sucesso');
        // Implementar chamada AJAX aqui
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

// Submissão do formulário
document.getElementById('formNovoUsuario').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validação básica
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    
    if (!nome || !email) {
        mostrarAlerta('Preencha todos os campos obrigatórios', 'aviso');
        return;
    }
    
    // Simular cadastro
    mostrarAlerta('Fisioterapeuta cadastrado com sucesso!', 'sucesso');
    fecharModal('modalNovoUsuario');
    
    // Limpar formulário
    this.reset();
});

// Fechar modal clicando fora
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        fecharModal(e.target.id);
    }
});
</script>