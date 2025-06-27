<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <div class="page-title-group">
                <h1 class="page-title">
                    <i class="material-icons-outlined">security</i>
                    Gerenciar Permissões
                </h1>
                <p class="page-subtitle">Configurar permissões para <?= htmlspecialchars($user['name']) ?></p>
            </div>
            <div class="header-actions">
                <a href="/admin/users" class="btn btn-secondary">
                    <i class="material-icons-outlined">arrow_back</i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="permissions-container">
            <!-- Informações do Usuário -->
            <div class="user-info-card">
                <div class="user-avatar">
                    <div class="avatar-circle <?= $user['role'] ?>">
                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                    </div>
                </div>
                <div class="user-details">
                    <h3><?= htmlspecialchars($user['name']) ?></h3>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                    <span class="user-role-badge <?= $user['role'] ?>">
                        <?= $user['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta' ?>
                    </span>
                </div>
            </div>

            <?php if ($user['role'] === 'admin'): ?>
                <!-- Administrador tem acesso total -->
                <div class="permissions-section">
                    <div class="alert alert-info">
                        <i class="material-icons-outlined">info</i>
                        <div>
                            <strong>Administrador</strong>
                            <p>Este usuário possui acesso total ao sistema. Não é necessário configurar permissões específicas.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Formulário de Permissões para Fisioterapeutas -->
                <form method="POST" class="permissions-form">
                    <?= $this->csrfField() ?>
                    
                    <!-- Módulo: Robôs de IA -->
                    <div class="permission-section">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">smart_toy</i>
                            Robôs de Inteligência Artificial
                        </h3>
                        
                        <div class="permissions-grid">
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="ai_basic_access" 
                                           <?= in_array('ai_basic_access', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Acesso Básico aos Robôs</strong>
                                        <p>Permite usar os robôs de IA para avaliações básicas</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="ai_advanced_features" 
                                           <?= in_array('ai_advanced_features', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Recursos Avançados</strong>
                                        <p>Acesso a relatórios detalhados e análises avançadas</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="ai_export_reports" 
                                           <?= in_array('ai_export_reports', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Exportar Relatórios</strong>
                                        <p>Permite exportar relatórios em PDF e outros formatos</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Módulo: Pacientes -->
                    <div class="permission-section">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">people</i>
                            Gestão de Pacientes
                        </h3>
                        
                        <div class="permissions-grid">
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="patients_view" 
                                           <?= in_array('patients_view', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Visualizar Pacientes</strong>
                                        <p>Ver lista e detalhes dos pacientes</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="patients_create" 
                                           <?= in_array('patients_create', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Cadastrar Pacientes</strong>
                                        <p>Criar novos registros de pacientes</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="patients_edit" 
                                           <?= in_array('patients_edit', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Editar Pacientes</strong>
                                        <p>Modificar dados dos pacientes existentes</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Módulo: Relatórios -->
                    <div class="permission-section">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">assessment</i>
                            Relatórios e Análises
                        </h3>
                        
                        <div class="permissions-grid">
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="reports_view" 
                                           <?= in_array('reports_view', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Visualizar Relatórios</strong>
                                        <p>Acessar relatórios e dashboards</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="reports_advanced" 
                                           <?= in_array('reports_advanced', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Relatórios Avançados</strong>
                                        <p>Acesso a análises estatísticas e comparativas</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Módulo: Sistema -->
                    <div class="permission-section">
                        <h3 class="section-title">
                            <i class="material-icons-outlined">settings</i>
                            Configurações do Sistema
                        </h3>
                        
                        <div class="permissions-grid">
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="system_preferences" 
                                           <?= in_array('system_preferences', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Preferências Pessoais</strong>
                                        <p>Configurar tema, idioma e notificações</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="permission-item">
                                <label class="permission-label">
                                    <input type="checkbox" name="permissions[]" value="backup_access" 
                                           <?= in_array('backup_access', $userPermissions ?? []) ? 'checked' : '' ?>>
                                    <span class="checkmark"></span>
                                    <div class="permission-info">
                                        <strong>Backup de Dados</strong>
                                        <p>Realizar backup dos próprios dados</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Ações do Formulário -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/users'">
                            <i class="material-icons-outlined">cancel</i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons-outlined">save</i>
                            Salvar Permissões
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.permissions-container {
    max-width: 1000px;
    margin: 0 auto;
}

.user-info-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: var(--surface-color);
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    border: 1px solid var(--border-color);
}

.user-avatar .avatar-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 24px;
    color: white;
}

.avatar-circle.admin {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.avatar-circle.usuario {
    background: linear-gradient(135deg, #059669, #10b981);
}

.user-details h3 {
    margin: 0 0 8px 0;
    font-size: 20px;
    color: var(--text-primary);
}

.user-details p {
    margin: 0 0 12px 0;
    color: var(--text-secondary);
}

.user-role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.user-role-badge.admin {
    background: #7c3aed;
}

.user-role-badge.usuario {
    background: #059669;
}

.permission-section {
    background: var(--surface-color);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
}

.permission-item {
    padding: 16px;
    background: var(--background-light);
    border-radius: 8px;
    border: 1px solid var(--border-light);
    transition: all 0.2s ease;
}

.permission-item:hover {
    border-color: var(--primary-color);
    background: var(--surface-color);
}

.permission-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    width: 100%;
}

.permission-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    flex-shrink: 0;
    margin-top: 2px;
}

.permission-label input:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.permission-label input:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-weight: 700;
    font-size: 12px;
}

.permission-info strong {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.permission-info p {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.4;
}

.alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.alert.alert-info {
    background: #dbeafe;
    border: 1px solid #bfdbfe;
    color: #1e40af;
}

.alert i {
    font-size: 24px;
    margin-top: 2px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
    margin-top: 32px;
}

@media (max-width: 768px) {
    .permissions-grid {
        grid-template-columns: 1fr;
    }
    
    .user-info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>

<script>
// Selecionar/desselecionar todas as permissões de uma seção
function toggleSectionPermissions(sectionElement, checked) {
    const checkboxes = sectionElement.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = checked;
    });
}

// Adicionar botões de controle rápido
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.permission-section');
    
    sections.forEach(section => {
        const title = section.querySelector('.section-title');
        const buttonsContainer = document.createElement('div');
        buttonsContainer.className = 'section-controls';
        buttonsContainer.innerHTML = `
            <button type="button" class="btn-link" onclick="toggleSectionPermissions(this.closest('.permission-section'), true)">
                Marcar Todas
            </button>
            <button type="button" class="btn-link" onclick="toggleSectionPermissions(this.closest('.permission-section'), false)">
                Desmarcar Todas
            </button>
        `;
        
        title.appendChild(buttonsContainer);
    });
});
</script>