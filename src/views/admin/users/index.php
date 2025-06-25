<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <h1>Gerenciar Usuários</h1>
            <p>Total: <?= $total ?> usuário(s)</p>
        </div>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/admin/users/create" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
                Novo Usuário
            </a>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="filters-card">
        <form method="GET" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="search">Buscar</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Nome ou email..."
                        class="form-control"
                    >
                </div>
                
                <div class="filter-group">
                    <label for="role">Perfil</label>
                    <select id="role" name="role" class="form-control">
                        <option value="">Todos</option>
                        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="usuario" <?= $role === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Ativo</option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-outline">Filtrar</button>
                    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">Limpar</a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Tabela de usuários -->
    <div class="table-card">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h3>Nenhum usuário encontrado</h3>
                <p>Não há usuários que correspondam aos filtros aplicados.</p>
                <a href="<?= BASE_URL ?>/admin/users/create" class="btn btn-primary">Criar Primeiro Usuário</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Status</th>
                            <th>Último Login</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $userItem): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <?= strtoupper(substr($userItem['name'], 0, 1)) ?>
                                        </div>
                                        <span class="user-name"><?= htmlspecialchars($userItem['name']) ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($userItem['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $userItem['role'] === 'admin' ? 'primary' : 'secondary' ?>">
                                        <?= $userItem['role'] === 'admin' ? 'Admin' : 'Usuário' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $userItem['status'] ?>">
                                        <?= $userItem['status'] === 'active' ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($userItem['last_login']): ?>
                                        <time title="<?= date('d/m/Y H:i:s', strtotime($userItem['last_login'])) ?>">
                                            <?= date('d/m/Y', strtotime($userItem['last_login'])) ?>
                                        </time>
                                    <?php else: ?>
                                        <span class="text-muted">Nunca</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <time title="<?= date('d/m/Y H:i:s', strtotime($userItem['created_at'])) ?>">
                                        <?= date('d/m/Y', strtotime($userItem['created_at'])) ?>
                                    </time>
                                </td>
                                <td>
                                    <div class="actions-dropdown">
                                        <button class="actions-button" onclick="toggleActions(<?= $userItem['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                <circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/>
                                            </svg>
                                        </button>
                                        
                                        <div class="actions-menu" id="actions-<?= $userItem['id'] ?>">
                                            <a href="<?= BASE_URL ?>/admin/users/edit?id=<?= $userItem['id'] ?>" class="action-item">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                                                </svg>
                                                Editar
                                            </a>
                                            
                                            <a href="<?= BASE_URL ?>/admin/users/change-password?id=<?= $userItem['id'] ?>" class="action-item">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                    <circle cx="12" cy="16" r="1"/>
                                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                                </svg>
                                                Alterar Senha
                                            </a>
                                            
                                            <form method="POST" action="<?= BASE_URL ?>/admin/users/unlock" style="display: inline;">
                                                <input type="hidden" name="id" value="<?= $userItem['id'] ?>">
                                                <button type="submit" class="action-item action-button">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                                        <path d="M7 11V7a5 5 0 0 1 9.9-1"/>
                                                    </svg>
                                                    Desbloquear
                                                </button>
                                            </form>
                                            
                                            <?php if ($userItem['id'] != $user['id']): ?>
                                                <div class="action-divider"></div>
                                                <button 
                                                    class="action-item action-danger" 
                                                    onclick="confirmDelete(<?= $userItem['id'] ?>, '<?= htmlspecialchars($userItem['name']) ?>')"
                                                >
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                        <polyline points="3,6 5,6 21,6"/>
                                                        <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"/>
                                                    </svg>
                                                    Excluir
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper">
                    <nav class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>" class="pagination-link">
                                ← Anterior
                            </a>
                        <?php endif; ?>
                        
                        <span class="pagination-info">
                            Página <?= $page ?> de <?= $totalPages ?>
                        </span>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>" class="pagination-link">
                                Próxima →
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Exclusão</h3>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Tem certeza que deseja excluir o usuário <strong id="deleteUserName"></strong>?</p>
            <p class="text-muted">Esta ação não pode ser desfeita.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
            <form id="deleteForm" method="POST" action="<?= BASE_URL ?>/admin/users/delete" style="display: inline;">
                <input type="hidden" name="id" id="deleteUserId">
                <button type="submit" class="btn btn-danger">Excluir</button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleActions(userId) {
    const menu = document.getElementById('actions-' + userId);
    const allMenus = document.querySelectorAll('.actions-menu');
    
    // Fechar outros menus
    allMenus.forEach(m => {
        if (m !== menu) m.classList.remove('show');
    });
    
    menu.classList.toggle('show');
}

function confirmDelete(userId, userName) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

// Fechar menus ao clicar fora
document.addEventListener('click', function(e) {
    if (!e.target.closest('.actions-dropdown')) {
        document.querySelectorAll('.actions-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

// Fechar modal ao clicar fora
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>