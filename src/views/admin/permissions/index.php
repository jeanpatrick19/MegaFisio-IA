<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <div class="page-title-group">
                <h1 class="page-title">
                    <i class="material-icons-outlined">security</i>
                    Gerenciar Permissões
                </h1>
                <p class="page-subtitle">Configure permissões granulares para usuários e roles</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="openRoleManager()">
                    <i class="material-icons-outlined">group</i>
                    Gerenciar Roles
                </button>
            </div>
        </div>
    </div>

    <div class="page-content">
        <!-- Abas de Navegação -->
        <div class="tab-navigation">
            <button class="tab-btn active" onclick="switchTab('users')">
                <i class="material-icons-outlined">people</i>
                Permissões por Usuário
            </button>
            <button class="tab-btn" onclick="switchTab('overview')">
                <i class="material-icons-outlined">analytics</i>
                Visão Geral
            </button>
        </div>

        <!-- Tab: Permissões por Usuário -->
        <div id="tab-users" class="tab-content active">
            <div class="permissions-section">
                <div class="section-header">
                    <h3>Usuários do Sistema</h3>
                    <div class="search-box">
                        <i class="material-icons-outlined">search</i>
                        <input type="text" id="searchUsers" placeholder="Buscar usuários..." onkeyup="filterUsers()">
                    </div>
                </div>

                <div class="users-grid" id="usersGrid">
                    <?php foreach ($users as $u): ?>
                    <div class="user-card" data-user-id="<?= $u['id'] ?>" data-name="<?= strtolower($u['name']) ?>" data-email="<?= strtolower($u['email']) ?>">
                        <div class="user-info">
                            <div class="user-avatar">
                                <?= strtoupper(substr($u['name'], 0, 2)) ?>
                            </div>
                            <div class="user-details">
                                <h4><?= htmlspecialchars($u['name']) ?></h4>
                                <p><?= htmlspecialchars($u['email']) ?></p>
                                <div class="user-meta">
                                    <span class="role-badge role-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span>
                                    <span class="status-badge status-<?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="user-permissions-summary">
                            <div class="permission-count">
                                <i class="material-icons-outlined">shield</i>
                                <span><?= $u['active_permissions_count'] ?? 0 ?> permissões</span>
                            </div>
                            <button class="btn btn-outline btn-sm" onclick="editUserPermissions(<?= $u['id'] ?>, '<?= htmlspecialchars($u['name']) ?>')">
                                <i class="material-icons-outlined">settings</i>
                                Gerenciar
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <!-- Tab: Visão Geral -->
        <div id="tab-overview" class="tab-content">
            <div class="overview-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="material-icons-outlined">people</i>
                        </div>
                        <div class="stat-info">
                            <h3><?= count($users) ?></h3>
                            <p>Usuários Totais</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="material-icons-outlined">people</i>
                        </div>
                        <div class="stat-info">
                            <h3><?= array_sum(array_column($users, 'active_permissions_count')) ?></h3>
                            <p>Permissões Ativas</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="material-icons-outlined">security</i>
                        </div>
                        <div class="stat-info">
                            <h3><?= array_sum(array_column($modules, 'permissions_count')) ?></h3>
                            <p>Permissões Disponíveis</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="material-icons-outlined">apps</i>
                        </div>
                        <div class="stat-info">
                            <h3><?= count($modules) ?></h3>
                            <p>Módulos do Sistema</p>
                        </div>
                    </div>
                </div>

                <div class="modules-overview">
                    <h3>Permissões por Módulo</h3>
                    <div class="modules-grid">
                        <?php foreach ($modules as $module): ?>
                        <div class="module-card">
                            <div class="module-header">
                                <h4>
                                    <i class="material-icons-outlined"><?= $module['icon'] ?? 'extension' ?></i>
                                    <?= htmlspecialchars($module['display_name']) ?>
                                </h4>
                                <span class="module-count"><?= count($module['permissions']) ?> permissões</span>
                            </div>
                            <div class="module-permissions">
                                <?php foreach ($module['permissions'] as $perm): ?>
                                <div class="permission-item">
                                    <div class="permission-details">
                                        <span class="permission-name"><?= htmlspecialchars($perm['display_name']) ?></span>
                                        <div class="permission-meta">
                                            <span class="permission-type badge-<?= $perm['permission_type'] ?>"><?= ucfirst($perm['permission_type']) ?></span>
                                            <?php if ($perm['is_critical']): ?>
                                                <span class="critical-badge">Crítica</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <code><?= htmlspecialchars($perm['name']) ?></code>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Gerenciar Permissões do Usuário -->
<div id="userPermissionsModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3 id="userModalTitle">Gerenciar Permissões</h3>
            <button class="modal-close" onclick="closeUserPermissionsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="permissions-editor">
                <div class="info-banner">
                    <i class="material-icons-outlined">info</i>
                    <p>Configure as permissões específicas deste usuário. Marque "Ver" para permitir visualização e "Usar" para permitir uso completo da funcionalidade.</p>
                </div>
                
                <div class="search-permissions">
                    <input type="text" id="searchPermissions" placeholder="Buscar permissões..." onkeyup="filterPermissions()">
                </div>
                
                <div id="userPermissionsContent" class="permissions-modules-list">
                    <!-- Conteúdo será carregado via JavaScript -->
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeUserPermissionsModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveUserPermissions()">Salvar Alterações</button>
        </div>
    </div>
</div>


<style>
.permissions-section {
    background: var(--surface-color);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border-color);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
}

.search-box {
    position: relative;
    width: 300px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
}

.search-box input {
    width: 100%;
    padding: 10px 12px 10px 40px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--input-bg);
}

.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.user-card {
    background: var(--surface-secondary);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border-color);
    transition: all 0.2s ease;
}

.user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.user-details h4 {
    margin: 0 0 4px 0;
    color: var(--text-primary);
}

.user-details p {
    margin: 0 0 8px 0;
    color: var(--text-secondary);
    font-size: 14px;
}

.user-meta {
    display: flex;
    gap: 8px;
}

.role-badge, .status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.role-badge {
    background: var(--primary-color);
    color: white;
}

.status-badge.status-active {
    background: var(--success-color);
    color: white;
}

.status-badge.status-inactive {
    background: var(--warning-color);
    color: white;
}

.user-permissions-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.permission-count {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-secondary);
    font-size: 14px;
}

.roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.role-card {
    background: var(--surface-secondary);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border-color);
}

.role-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.role-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--surface-secondary);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid var(--border-color);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.module-card {
    background: var(--surface-secondary);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border-color);
}

.module-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
}

.permission-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color-light);
}

.permission-item:last-child {
    border-bottom: none;
}

.permission-item code {
    background: var(--code-bg);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
}

.modal.large .modal-content {
    max-width: 800px;
    width: 90%;
}

.permissions-editor {
    min-height: 400px;
}

.permissions-tabs {
    display: flex;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 20px;
}

.perm-tab {
    padding: 12px 20px;
    background: none;
    border: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
}

.perm-tab.active {
    border-bottom-color: var(--primary-color);
    color: var(--primary-color);
}

.perm-content {
    display: none;
}

.perm-content.active {
    display: block;
}

.info-banner, .warning-banner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.info-banner {
    background: rgba(64, 150, 255, 0.1);
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
}

.warning-banner {
    background: rgba(255, 152, 0, 0.1);
    border: 1px solid var(--warning-color);
    color: var(--warning-color);
}

.permissions-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 12px;
}

.permission-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    transition: all 0.2s ease;
}

.permission-checkbox:hover {
    background: var(--surface-secondary);
}

.permission-checkbox input[type="checkbox"] {
    accent-color: var(--primary-color);
}

.permission-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.permission-meta {
    display: flex;
    gap: 6px;
    align-items: center;
}

.permission-type {
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
}

.badge-view { background: #e3f2fd; color: #1976d2; }
.badge-edit { background: #f3e5f5; color: #7b1fa2; }
.badge-create { background: #e8f5e8; color: #388e3c; }
.badge-delete { background: #ffebee; color: #d32f2f; }
.badge-execute { background: #fff3e0; color: #f57c00; }
.badge-manage { background: #fce4ec; color: #c2185b; }

.critical-badge {
    background: #fff3cd;
    color: #856404;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

.search-permissions {
    margin-bottom: 20px;
}

.search-permissions input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--input-bg);
}

.permissions-modules-list {
    max-height: 500px;
    overflow-y: auto;
}

.permission-module-card {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 16px;
    overflow: hidden;
}

.module-header-card {
    background: var(--surface-secondary);
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.module-permissions-grid {
    padding: 16px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 12px;
}

.permission-toggle-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background: var(--surface-color);
    transition: all 0.2s ease;
}

.permission-toggle-item:hover {
    background: var(--surface-secondary);
}

.permission-toggle-item.critical {
    border-color: #ffc107;
    background: #fff9c4;
}

.permission-controls {
    display: flex;
    gap: 8px;
    align-items: center;
}

.toggle-switch {
    position: relative;
    width: 40px;
    height: 20px;
}

.toggle-switch input[type="checkbox"] {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 20px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: var(--primary-color);
}

input:checked + .toggle-slider:before {
    transform: translateX(20px);
}

@media (max-width: 768px) {
    .users-grid, .roles-grid, .modules-grid {
        grid-template-columns: 1fr;
    }
    
    .search-box {
        width: 100%;
    }
    
    .section-header {
        flex-direction: column;
        gap: 16px;
        align-items: stretch;
    }
}
</style>

<script>
let currentUserId = null;
let userPermissions = {};
let originalPermissions = {};

// Navegação entre abas
function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    document.querySelector(`.tab-btn[onclick="switchTab('${tabName}')"]`).classList.add('active');
    document.getElementById(`tab-${tabName}`).classList.add('active');
}

// Filtrar usuários
function filterUsers() {
    const search = document.getElementById('searchUsers').value.toLowerCase();
    const userCards = document.querySelectorAll('.user-card');
    
    userCards.forEach(card => {
        const name = card.dataset.name;
        const email = card.dataset.email;
        
        if (name.includes(search) || email.includes(search)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Filtrar permissões no modal
function filterPermissions() {
    const search = document.getElementById('searchPermissions').value.toLowerCase();
    const permissionItems = document.querySelectorAll('.permission-toggle-item');
    
    permissionItems.forEach(item => {
        const permissionName = item.querySelector('.permission-name').textContent.toLowerCase();
        const moduleName = item.closest('.permission-module-card').querySelector('h4').textContent.toLowerCase();
        
        if (permissionName.includes(search) || moduleName.includes(search)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Gerenciar permissões do usuário
async function editUserPermissions(userId, userName) {
    currentUserId = userId;
    document.getElementById('userModalTitle').textContent = `Permissões de ${userName}`;
    
    // Mostrar loading
    const content = document.getElementById('userPermissionsContent');
    content.innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <div style="margin-bottom: 16px;">Carregando permissões...</div>
            <div class="spinner"></div>
        </div>
    `;
    
    document.getElementById('userPermissionsModal').classList.add('show');
    
    try {
        const response = await fetch(`/admin/permissions/getUserPermissions?user_id=${userId}`);
        const data = await response.json();
        
        if (data.success) {
            userPermissions = data.modules;
            originalPermissions = JSON.parse(JSON.stringify(data.modules));
            renderUserPermissions(data.modules);
        } else {
            content.innerHTML = `<div style="text-align: center; color: red; padding: 40px;">Erro: ${data.message}</div>`;
        }
    } catch (error) {
        content.innerHTML = `<div style="text-align: center; color: red; padding: 40px;">Erro: ${error.message}</div>`;
    }
}

// Renderizar permissões do usuário
function renderUserPermissions(modules) {
    const content = document.getElementById('userPermissionsContent');
    
    if (!modules || Object.keys(modules).length === 0) {
        content.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #666;">
                <i class="material-icons-outlined" style="font-size: 48px; margin-bottom: 16px;">security</i>
                <div>Nenhuma permissão disponível para este usuário</div>
            </div>
        `;
        return;
    }
    
    let html = '';
    Object.values(modules).forEach(module => {
        const iconClass = getModuleIcon(module.name);
        const allChecked = module.permissions.every(p => p.has_permission);
        const someChecked = module.permissions.some(p => p.has_permission);
        
        html += `
            <div class="permission-module-card">
                <div class="module-header-card">
                    <div>
                        <h4>
                            <i class="material-icons-outlined">${iconClass}</i>
                            ${module.display_name}
                        </h4>
                        <small style="color: #666;">${module.permissions.length} permissões disponíveis</small>
                    </div>
                    <div class="permission-controls">
                        <span style="font-size: 14px; margin-right: 8px;">Todas:</span>
                        <label class="toggle-switch">
                            <input type="checkbox" onchange="toggleModule('${module.name}')" 
                                   ${allChecked ? 'checked' : ''} 
                                   ${someChecked && !allChecked ? 'data-indeterminate="true"' : ''}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                <div class="module-permissions-grid">
        `;
        
        // Agrupar permissões por tipo
        const groupedPerms = groupPermissionsByType(module.permissions);
        
        Object.entries(groupedPerms).forEach(([type, permissions]) => {
            permissions.forEach(permission => {
                const isChecked = permission.has_permission;
                const isCritical = permission.is_critical;
                const typeLabel = getTypeLabel(type);
                
                html += `
                    <div class="permission-toggle-item ${isCritical ? 'critical' : ''}" data-permission-id="${permission.id}">
                        <div class="permission-info">
                            <div class="permission-name">${permission.display_name}</div>
                            <div class="permission-meta">
                                <span class="permission-type badge-${type}">${typeLabel}</span>
                                ${isCritical ? '<span class="critical-badge">Crítica</span>' : ''}
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" 
                                   data-permission-id="${permission.id}" 
                                   data-module="${module.name}"
                                   onchange="togglePermission(this)"
                                   ${isChecked ? 'checked' : ''}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                `;
            });
        });
        
        html += `
                </div>
            </div>
        `;
    });
    
    content.innerHTML = html;
    
    // Aplicar indeterminado nos checkboxes de módulo
    document.querySelectorAll('input[data-indeterminate="true"]').forEach(input => {
        input.indeterminate = true;
    });
}

// Agrupar permissões por tipo
function groupPermissionsByType(permissions) {
    const grouped = {};
    permissions.forEach(permission => {
        const type = permission.type;
        if (!grouped[type]) {
            grouped[type] = [];
        }
        grouped[type].push(permission);
    });
    return grouped;
}

// Obter ícone do módulo
function getModuleIcon(moduleName) {
    const icons = {
        'auth': 'security',
        'dashboard': 'dashboard',
        'users': 'people',
        'profile': 'account_circle',
        'ai': 'psychology',
        'settings': 'settings',
        'logs': 'list_alt',
        'activities': 'timeline',
        'permissions': 'vpn_key',
        'maintenance': 'build'
    };
    return icons[moduleName] || 'extension';
}

// Obter label do tipo
function getTypeLabel(type) {
    const labels = {
        'view': 'Ver',
        'edit': 'Editar', 
        'create': 'Criar',
        'delete': 'Excluir',
        'execute': 'Usar',
        'manage': 'Gerenciar'
    };
    return labels[type] || type;
}

// Toggle de módulo completo
function toggleModule(moduleName) {
    const moduleInput = event.target;
    const isChecked = moduleInput.checked;
    
    // Atualizar permissões no objeto
    if (userPermissions[moduleName]) {
        userPermissions[moduleName].permissions.forEach(permission => {
            permission.has_permission = isChecked;
        });
    }
    
    // Atualizar checkboxes individuais
    const moduleCard = moduleInput.closest('.permission-module-card');
    const permissionInputs = moduleCard.querySelectorAll('.permission-toggle-item input[type="checkbox"]');
    permissionInputs.forEach(input => {
        input.checked = isChecked;
    });
    
    moduleInput.indeterminate = false;
}

// Toggle de permissão individual
function togglePermission(input) {
    const permissionId = input.dataset.permissionId;
    const moduleName = input.dataset.module;
    const isChecked = input.checked;
    
    // Atualizar no objeto de permissões
    if (userPermissions[moduleName]) {
        const permission = userPermissions[moduleName].permissions.find(p => p.id == permissionId);
        if (permission) {
            permission.has_permission = isChecked;
        }
        
        // Atualizar checkbox do módulo
        updateModuleCheckbox(moduleName);
    }
}

// Atualizar checkbox do módulo baseado nas permissões individuais
function updateModuleCheckbox(moduleName) {
    const modulePermissions = userPermissions[moduleName].permissions;
    const allChecked = modulePermissions.every(p => p.has_permission);
    const someChecked = modulePermissions.some(p => p.has_permission);
    
    const moduleInput = document.querySelector(`input[onchange="toggleModule('${moduleName}')"]`);
    if (moduleInput) {
        moduleInput.checked = allChecked;
        moduleInput.indeterminate = someChecked && !allChecked;
    }
}

// Fechar modal
function closeUserPermissionsModal() {
    document.getElementById('userPermissionsModal').classList.remove('show');
    currentUserId = null;
    userPermissions = {};
}

// Salvar permissões
async function saveUserPermissions() {
    if (!currentUserId) return;
    
    // Coletar permissões ativas
    const activePermissions = [];
    Object.values(userPermissions).forEach(module => {
        module.permissions.forEach(permission => {
            if (permission.has_permission) {
                activePermissions.push(permission.id);
            }
        });
    });
    
    // Preparar dados para envio
    const formData = new FormData();
    formData.append('user_id', currentUserId);
    activePermissions.forEach(permId => {
        formData.append('permissions[]', permId);
    });
    
    try {
        const response = await fetch('/admin/permissions/bulkAssignPermissions', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Permissões salvas com sucesso!');
            closeUserPermissionsModal();
            
            // Atualizar contagem na lista de usuários
            const userCard = document.querySelector(`[data-user-id="${currentUserId}"]`);
            if (userCard) {
                const countElement = userCard.querySelector('.permission-count span');
                if (countElement) {
                    countElement.textContent = `${activePermissions.length} permissões`;
                }
            }
        } else {
            alert('Erro ao salvar permissões: ' + data.message);
        }
    } catch (error) {
        alert('Erro ao salvar permissões: ' + error.message);
    }
}
</script>