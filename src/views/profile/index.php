<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <h1>Meu Perfil</h1>
            <p>Gerencie suas informações pessoais e configurações</p>
        </div>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
                Editar Perfil
            </a>
        </div>
    </div>

    <!-- Informações do Perfil -->
    <div class="profile-content">
        <div class="profile-main">
            <!-- Card Principal -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        <?= strtoupper(substr($userProfile['name'], 0, 1)) ?>
                    </div>
                    <div class="profile-info">
                        <h2><?= htmlspecialchars($userProfile['name']) ?></h2>
                        <p class="profile-email"><?= htmlspecialchars($userProfile['email']) ?></p>
                        <div class="profile-badges">
                            <span class="profile-badge profile-badge-<?= $userProfile['role'] ?>">
                                <?= $userProfile['role'] === 'admin' ? 'Administrador' : 'Usuário' ?>
                            </span>
                            <span class="profile-badge profile-badge-<?= $userProfile['status'] ?>">
                                <?= $userProfile['status'] === 'active' ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="profile-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-icon">📅</div>
                            <div class="detail-content">
                                <label>Membro desde</label>
                                <value><?= date('d/m/Y', strtotime($userProfile['created_at'])) ?></value>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">🕒</div>
                            <div class="detail-content">
                                <label>Último acesso</label>
                                <value>
                                    <?= $userProfile['last_login'] 
                                        ? date('d/m/Y H:i', strtotime($userProfile['last_login'])) 
                                        : 'Primeiro acesso' ?>
                                </value>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">🔄</div>
                            <div class="detail-content">
                                <label>Última atualização</label>
                                <value><?= date('d/m/Y H:i', strtotime($userProfile['updated_at'])) ?></value>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">🤖</div>
                            <div class="detail-content">
                                <label>Requisições IA</label>
                                <value><?= number_format($usageStats['ai_requests']) ?></value>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histórico de Acessos -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Histórico de Acessos Recentes</h3>
                    <span class="card-badge">Últimos 10</span>
                </div>
                <div class="card-body">
                    <?php if (empty($recentLogins)): ?>
                        <div class="empty-state-small">
                            <p>Nenhum histórico de acesso encontrado.</p>
                        </div>
                    <?php else: ?>
                        <div class="access-history">
                            <?php foreach ($recentLogins as $login): ?>
                                <div class="access-item">
                                    <div class="access-icon">
                                        <?= $login['sucesso'] ? '✅' : '❌' ?>
                                    </div>
                                    <div class="access-content">
                                        <div class="access-type">
                                            <?= $login['acao'] === 'login_success' ? 'Login realizado' : 'Tentativa de login' ?>
                                            <?php if (!$login['sucesso']): ?>
                                                <span class="access-failed"> (Falhou)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="access-details">
                                            <time><?= date('d/m/Y H:i:s', strtotime($login['data_hora'])) ?></time>
                                            <?php if ($login['ip_address']): ?>
                                                • IP: <?= htmlspecialchars($login['ip_address']) ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($login['user_agent']): ?>
                                            <div class="access-agent">
                                                <?= htmlspecialchars(substr($login['user_agent'], 0, 80)) ?>
                                                <?= strlen($login['user_agent']) > 80 ? '...' : '' ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="profile-sidebar">
            <!-- Ações Rápidas -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Ações Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions-profile">
                        <a href="<?= BASE_URL ?>/profile/edit" class="profile-action">
                            <div class="action-icon">✏️</div>
                            <div class="action-content">
                                <div class="action-title">Editar Perfil</div>
                                <div class="action-desc">Alterar nome e email</div>
                            </div>
                        </a>
                        
                        <a href="<?= BASE_URL ?>/change-password" class="profile-action">
                            <div class="action-icon">🔐</div>
                            <div class="action-content">
                                <div class="action-title">Alterar Senha</div>
                                <div class="action-desc">Trocar senha de acesso</div>
                            </div>
                        </a>
                        
                        <a href="<?= BASE_URL ?>/profile/activities" class="profile-action">
                            <div class="action-icon">📊</div>
                            <div class="action-content">
                                <div class="action-title">Atividades</div>
                                <div class="action-desc">Histórico e exportação</div>
                            </div>
                        </a>
                        
                        <a href="<?= BASE_URL ?>/profile/privacy" class="profile-action">
                            <div class="action-icon">🔒</div>
                            <div class="action-content">
                                <div class="action-title">Privacidade</div>
                                <div class="action-desc">Dados pessoais e LGPD</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Atividade Mensal -->
            <?php if (!empty($usageStats['monthly_activity'])): ?>
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Atividade Mensal</h3>
                    </div>
                    <div class="card-body">
                        <div class="activity-chart">
                            <?php 
                            $maxActivity = max(array_column($usageStats['monthly_activity'], 'actions'));
                            foreach ($usageStats['monthly_activity'] as $activity): 
                            ?>
                                <div class="activity-month">
                                    <div class="month-label">
                                        <?= date('M/y', strtotime($activity['month'] . '-01')) ?>
                                    </div>
                                    <div class="month-bar">
                                        <div 
                                            class="month-fill" 
                                            style="height: <?= $maxActivity > 0 ? ($activity['actions'] / $maxActivity) * 100 : 0 ?>%"
                                        ></div>
                                    </div>
                                    <div class="month-value">
                                        <?= number_format($activity['actions']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Informações de Segurança -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Segurança</h3>
                </div>
                <div class="card-body">
                    <div class="security-info">
                        <div class="security-item">
                            <div class="security-icon">🔐</div>
                            <div class="security-content">
                                <div class="security-title">Senha</div>
                                <div class="security-desc">
                                    Última alteração: 
                                    <?= date('d/m/Y', strtotime($userProfile['updated_at'])) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="security-item">
                            <div class="security-icon">📱</div>
                            <div class="security-content">
                                <div class="security-title">Sessão Atual</div>
                                <div class="security-desc">
                                    IP: <?= $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido' ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="security-item">
                            <div class="security-icon">🕒</div>
                            <div class="security-content">
                                <div class="security-title">Login Atual</div>
                                <div class="security-desc">
                                    <?= date('d/m/Y H:i', $_SESSION['login_time'] ?? time()) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="security-actions">
                        <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                            </svg>
                            Sair desta Sessão
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>