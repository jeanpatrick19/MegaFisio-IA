<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <h1>Privacidade e Dados Pessoais</h1>
            <p>Gerencie seus dados pessoais conforme a LGPD</p>
        </div>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/profile" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12H5m7-7l-7 7 7 7"/>
                </svg>
                Voltar ao Perfil
            </a>
        </div>
    </div>

    <div class="privacy-content">
        <!-- Informações Pessoais -->
        <div class="privacy-card">
            <div class="privacy-header">
                <h3>📊 Seus Dados Pessoais</h3>
                <p>Conforme a Lei Geral de Proteção de Dados (LGPD), você tem direito de saber quais dados coletamos.</p>
            </div>
            
            <div class="data-table">
                <div class="data-row">
                    <div class="data-label">Nome Completo:</div>
                    <div class="data-value"><?= htmlspecialchars($personalData['name']) ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Email:</div>
                    <div class="data-value"><?= htmlspecialchars($personalData['email']) ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Perfil:</div>
                    <div class="data-value"><?= $personalData['role'] === 'admin' ? 'Administrador' : 'Usuário' ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Status da Conta:</div>
                    <div class="data-value">
                        <span class="status-badge status-<?= $personalData['status'] ?>">
                            <?= $personalData['status'] === 'active' ? 'Ativa' : 'Inativa' ?>
                        </span>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-label">Cadastro:</div>
                    <div class="data-value"><?= date('d/m/Y H:i:s', strtotime($personalData['created_at'])) ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Última Atualização:</div>
                    <div class="data-value"><?= date('d/m/Y H:i:s', strtotime($personalData['updated_at'])) ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Último Login:</div>
                    <div class="data-value">
                        <?= $personalData['last_login'] 
                            ? date('d/m/Y H:i:s', strtotime($personalData['last_login'])) 
                            : 'Primeiro acesso' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tipos de Dados Coletados -->
        <div class="privacy-card">
            <div class="privacy-header">
                <h3>🔍 Tipos de Dados que Coletamos</h3>
                <p>Transparência total sobre quais informações mantemos sobre você.</p>
            </div>
            
            <div class="data-types">
                <div class="data-type">
                    <div class="data-type-icon">👤</div>
                    <div class="data-type-content">
                        <h4>Dados de Identificação</h4>
                        <ul>
                            <li>Nome completo</li>
                            <li>Endereço de email</li>
                            <li>Data de cadastro</li>
                        </ul>
                    </div>
                </div>
                
                <div class="data-type">
                    <div class="data-type-icon">🔐</div>
                    <div class="data-type-content">
                        <h4>Dados de Segurança</h4>
                        <ul>
                            <li>Senha criptografada</li>
                            <li>Tokens de sessão</li>
                            <li>Histórico de logins</li>
                        </ul>
                    </div>
                </div>
                
                <div class="data-type">
                    <div class="data-type-icon">📝</div>
                    <div class="data-type-content">
                        <h4>Dados de Uso</h4>
                        <ul>
                            <li>Logs de atividades</li>
                            <li>Requisições de IA</li>
                            <li>Documentos criados</li>
                        </ul>
                    </div>
                </div>
                
                <div class="data-type">
                    <div class="data-type-icon">🌐</div>
                    <div class="data-type-content">
                        <h4>Dados Técnicos</h4>
                        <ul>
                            <li>Endereço IP</li>
                            <li>Navegador utilizado</li>
                            <li>Dispositivo de acesso</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seus Direitos -->
        <div class="privacy-card">
            <div class="privacy-header">
                <h3>⚖️ Seus Direitos (LGPD)</h3>
                <p>A Lei Geral de Proteção de Dados garante os seguintes direitos a você:</p>
            </div>
            
            <div class="rights-grid">
                <div class="right-item">
                    <div class="right-icon">📋</div>
                    <div class="right-content">
                        <h4>Acesso aos Dados</h4>
                        <p>Saber quais dados pessoais temos sobre você</p>
                    </div>
                </div>
                
                <div class="right-item">
                    <div class="right-icon">✏️</div>
                    <div class="right-content">
                        <h4>Correção</h4>
                        <p>Corrigir dados incompletos ou incorretos</p>
                    </div>
                </div>
                
                <div class="right-item">
                    <div class="right-icon">🗑️</div>
                    <div class="right-content">
                        <h4>Exclusão</h4>
                        <p>Solicitar a remoção de seus dados</p>
                    </div>
                </div>
                
                <div class="right-item">
                    <div class="right-icon">📦</div>
                    <div class="right-content">
                        <h4>Portabilidade</h4>
                        <p>Exportar seus dados em formato legível</p>
                    </div>
                </div>
                
                <div class="right-item">
                    <div class="right-icon">🚫</div>
                    <div class="right-content">
                        <h4>Oposição</h4>
                        <p>Contestar o tratamento de seus dados</p>
                    </div>
                </div>
                
                <div class="right-item">
                    <div class="right-icon">📊</div>
                    <div class="right-content">
                        <h4>Transparência</h4>
                        <p>Informações claras sobre o uso dos dados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Disponíveis -->
        <div class="privacy-actions">
            <div class="action-card">
                <div class="action-header">
                    <h3>📥 Exportar Meus Dados</h3>
                    <p>Baixe todos os seus dados em formato JSON</p>
                </div>
                <div class="action-body">
                    <p>O arquivo incluirá:</p>
                    <ul>
                        <li>Dados pessoais cadastrados</li>
                        <li>Histórico de acessos</li>
                        <li>Logs de atividades</li>
                        <li>Data da exportação</li>
                    </ul>
                    
                    <form method="POST" action="<?= BASE_URL ?>/profile/export-data">
                        <?= $this->csrfField() ?>
                        <button type="submit" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7,10 12,15 17,10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Exportar Dados
                        </button>
                    </form>
                </div>
            </div>

            <div class="action-card action-danger">
                <div class="action-header">
                    <h3>🗑️ Solicitar Exclusão de Dados</h3>
                    <p>Remover permanentemente seus dados do sistema</p>
                </div>
                <div class="action-body">
                    <div class="alert alert-warning">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <div>
                            <strong>Ação irreversível!</strong><br>
                            Esta ação removerá permanentemente sua conta e todos os dados associados.
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-danger" onclick="showDeletionForm()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <polyline points="3,6 5,6 21,6"/>
                            <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"/>
                        </svg>
                        Solicitar Exclusão
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Solicitação de Exclusão -->
<div id="deletionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Solicitar Exclusão de Dados</h3>
            <button class="modal-close" onclick="closeDeletionModal()">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/profile/request-deletion">
            <div class="modal-body">
                <div class="alert alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <div>
                        <strong>Confirmação necessária</strong><br>
                        Você está solicitando a exclusão permanente de todos os seus dados.
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reason">Motivo da exclusão (opcional):</label>
                    <textarea 
                        id="reason" 
                        name="reason" 
                        class="form-control" 
                        rows="3"
                        placeholder="Informe o motivo para nos ajudar a melhorar..."
                    ></textarea>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="confirm" value="1" required>
                        <span class="checkmark"></span>
                        Confirmo que entendo que esta ação é irreversível e todos os meus dados serão removidos permanentemente.
                    </label>
                </div>
                
                <p class="deletion-info">
                    <strong>O que acontecerá:</strong><br>
                    • Sua conta será desativada imediatamente<br>
                    • Todos os dados pessoais serão removidos em até 48 horas<br>
                    • Você receberá uma confirmação por email<br>
                    • Não será possível recuperar os dados após a exclusão
                </p>
            </div>
            <div class="modal-footer">
                <?= $this->csrfField() ?>
                <button type="button" class="btn btn-secondary" onclick="closeDeletionModal()">Cancelar</button>
                <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
            </div>
        </form>
    </div>
</div>

<script>
function showDeletionForm() {
    document.getElementById('deletionModal').classList.add('show');
}

function closeDeletionModal() {
    document.getElementById('deletionModal').classList.remove('show');
}

// Fechar modal ao clicar fora
document.getElementById('deletionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeletionModal();
    }
});
</script>