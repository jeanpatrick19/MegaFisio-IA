<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Limpeza do Sistema</h1>
<p class="subtitulo-pagina-escuro">Gerencie dados do sistema, remova informações desnecessárias e mantenha a performance otimizada</p>

<!-- Estatísticas de Dados -->
<div class="limpeza-stats">
    <div class="stat-limpeza-card">
        <div class="stat-limpeza-icon dados">
            <i class="fas fa-database"></i>
        </div>
        <div class="stat-limpeza-info">
            <div class="stat-limpeza-numero"><?= number_format($stats['total_records'] ?? 1250) ?></div>
            <div class="stat-limpeza-label">Total de Registros</div>
        </div>
    </div>
    
    <div class="stat-limpeza-card">
        <div class="stat-limpeza-icon logs">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-limpeza-info">
            <div class="stat-limpeza-numero"><?= number_format($stats['logs_count'] ?? 850) ?></div>
            <div class="stat-limpeza-label">Logs do Sistema</div>
        </div>
    </div>
    
    <div class="stat-limpeza-card">
        <div class="stat-limpeza-icon sessoes">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-limpeza-info">
            <div class="stat-limpeza-numero"><?= number_format($stats['old_sessions'] ?? 45) ?></div>
            <div class="stat-limpeza-label">Sessões Antigas</div>
        </div>
    </div>
    
    <div class="stat-limpeza-card">
        <div class="stat-limpeza-icon espaco">
            <i class="fas fa-hdd"></i>
        </div>
        <div class="stat-limpeza-info">
            <div class="stat-limpeza-numero"><?= $stats['disk_usage'] ?? '78%' ?></div>
            <div class="stat-limpeza-label">Uso do Disco</div>
        </div>
    </div>
</div>

<!-- Grid de Limpeza -->
<div class="limpeza-grid">
    <!-- Limpeza Automática -->
    <div class="card-fisio limpeza-card automatica">
        <div class="limpeza-header">
            <div class="limpeza-icon-grande">
                <i class="fas fa-magic"></i>
            </div>
            <div class="limpeza-titulo">
                <h3>Limpeza Automática</h3>
                <p>Remove automaticamente dados desnecessários sem afetar o funcionamento</p>
            </div>
        </div>
        
        <div class="limpeza-conteudo">
            <div class="limpeza-itens">
                <div class="item-limpeza">
                    <i class="fas fa-check-circle"></i>
                    <span>Logs antigos (>30 dias)</span>
                    <span class="item-quantidade">~150 registros</span>
                </div>
                <div class="item-limpeza">
                    <i class="fas fa-check-circle"></i>
                    <span>Sessões expiradas</span>
                    <span class="item-quantidade">~45 sessões</span>
                </div>
                <div class="item-limpeza">
                    <i class="fas fa-check-circle"></i>
                    <span>Cache temporário</span>
                    <span class="item-quantidade">~25 MB</span>
                </div>
                <div class="item-limpeza">
                    <i class="fas fa-check-circle"></i>
                    <span>Notificações lidas antigas</span>
                    <span class="item-quantidade">~80 notificações</span>
                </div>
            </div>
            
            <button class="btn-fisio btn-primario btn-limpeza" onclick="executarLimpezaAutomatica()">
                <i class="fas fa-play"></i>
                Executar Limpeza Automática
            </button>
        </div>
    </div>
    
    <!-- Limpeza Personalizada -->
    <div class="card-fisio limpeza-card personalizada">
        <div class="limpeza-header">
            <div class="limpeza-icon-grande">
                <i class="fas fa-sliders-h"></i>
            </div>
            <div class="limpeza-titulo">
                <h3>Limpeza Personalizada</h3>
                <p>Escolha especificamente o que deseja limpar do sistema</p>
            </div>
        </div>
        
        <div class="limpeza-conteudo">
            <form id="formLimpezaPersonalizada" class="limpeza-form">
                <div class="opcoes-limpeza">
                    <label class="opcao-checkbox">
                        <input type="checkbox" name="logs_antigos" value="1">
                        <span class="checkmark-limpeza"></span>
                        <div class="opcao-info">
                            <span class="opcao-titulo">Logs do Sistema</span>
                            <span class="opcao-descricao">Logs anteriores a 30 dias (150 registros)</span>
                        </div>
                    </label>
                    
                    <label class="opcao-checkbox">
                        <input type="checkbox" name="sessoes_antigas" value="1">
                        <span class="checkmark-limpeza"></span>
                        <div class="opcao-info">
                            <span class="opcao-titulo">Sessões Expiradas</span>
                            <span class="opcao-descricao">Sessões inativas há mais de 7 dias (45 sessões)</span>
                        </div>
                    </label>
                    
                    <label class="opcao-checkbox">
                        <input type="checkbox" name="cache_temp" value="1">
                        <span class="checkmark-limpeza"></span>
                        <div class="opcao-info">
                            <span class="opcao-titulo">Cache Temporário</span>
                            <span class="opcao-descricao">Arquivos de cache gerados pelo sistema (25 MB)</span>
                        </div>
                    </label>
                    
                    <label class="opcao-checkbox">
                        <input type="checkbox" name="notificacoes_lidas" value="1">
                        <span class="checkmark-limpeza"></span>
                        <div class="opcao-info">
                            <span class="opcao-titulo">Notificações Antigas</span>
                            <span class="opcao-descricao">Notificações lidas há mais de 15 dias (80 notificações)</span>
                        </div>
                    </label>
                    
                    <label class="opcao-checkbox">
                        <input type="checkbox" name="backup_antigo" value="1">
                        <span class="checkmark-limpeza"></span>
                        <div class="opcao-info">
                            <span class="opcao-titulo">Backups Antigos</span>
                            <span class="opcao-descricao">Backups automáticos anteriores a 60 dias (120 MB)</span>
                        </div>
                    </label>
                </div>
                
                <button type="submit" class="btn-fisio btn-secundario btn-limpeza">
                    <i class="fas fa-broom"></i>
                    Executar Limpeza Selecionada
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Seção de Reset -->
<div class="card-fisio reset-section">
    <div class="reset-header">
        <div class="reset-icon-grande perigo">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="reset-info">
            <h3>Reset Completo do Sistema</h3>
            <p class="reset-aviso">⚠️ <strong>ATENÇÃO:</strong> Esta ação irá remover TODOS os dados do sistema, exceto sua conta de administrador</p>
        </div>
    </div>
    
    <div class="reset-conteudo">
        <div class="reset-itens">
            <div class="reset-item">
                <i class="fas fa-users"></i>
                <span>Todos os usuários fisioterapeutas</span>
            </div>
            <div class="reset-item">
                <i class="fas fa-brain"></i>
                <span>Histórico de análises IA</span>
            </div>
            <div class="reset-item">
                <i class="fas fa-file-alt"></i>
                <span>Todos os logs e registros</span>
            </div>
            <div class="reset-item">
                <i class="fas fa-bell"></i>
                <span>Notificações e alertas</span>
            </div>
            <div class="reset-item">
                <i class="fas fa-cog"></i>
                <span>Configurações personalizadas</span>
            </div>
        </div>
        
        <div class="reset-confirmacao">
            <div class="confirmacao-grupo">
                <label for="resetConfirmacao">Digite "RESETAR_SISTEMA" para confirmar:</label>
                <input type="text" 
                       id="resetConfirmacao" 
                       name="reset_confirmacao" 
                       placeholder="RESETAR_SISTEMA"
                       class="input-confirmacao">
            </div>
            
            <button class="btn-fisio btn-perigo btn-limpeza" 
                    id="btnReset" 
                    onclick="confirmarReset()" 
                    disabled>
                <i class="fas fa-trash-alt"></i>
                Resetar Sistema Completo
            </button>
        </div>
    </div>
</div>

<!-- Histórico de Limpezas -->
<div class="card-fisio">
    <div class="card-header-fisio">
        <div class="card-titulo">
            <i class="fas fa-history"></i>
            <span>Histórico de Limpezas</span>
        </div>
    </div>
    
    <div class="historico-lista">
        <?php 
        $historico = [
            ['data' => '2024-01-15 14:30', 'tipo' => 'Automática', 'itens' => '150 logs, 45 sessões', 'status' => 'sucesso'],
            ['data' => '2024-01-10 09:15', 'tipo' => 'Personalizada', 'itens' => '80 notificações, cache', 'status' => 'sucesso'],
            ['data' => '2024-01-05 16:45', 'tipo' => 'Automática', 'itens' => '200 logs, 60 sessões', 'status' => 'sucesso']
        ];
        
        foreach ($historico as $item): 
        ?>
            <div class="historico-item">
                <div class="historico-icone <?= $item['status'] ?>">
                    <i class="fas fa-<?= $item['status'] === 'sucesso' ? 'check' : 'times' ?>"></i>
                </div>
                <div class="historico-info">
                    <div class="historico-titulo">Limpeza <?= $item['tipo'] ?></div>
                    <div class="historico-descricao">Removidos: <?= $item['itens'] ?></div>
                    <div class="historico-data"><?= date('d/m/Y H:i', strtotime($item['data'])) ?></div>
                </div>
                <div class="historico-status status-<?= $item['status'] ?>">
                    <?= ucfirst($item['status']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* Estatísticas de Limpeza */
.limpeza-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-limpeza-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-medio);
    transition: var(--transicao);
}

.stat-limpeza-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--sombra-forte);
}

.stat-limpeza-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-limpeza-icon.dados {
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
}

.stat-limpeza-icon.logs {
    background: linear-gradient(135deg, #059669, #10b981);
}

.stat-limpeza-icon.sessoes {
    background: linear-gradient(135deg, #ca8a04, #eab308);
}

.stat-limpeza-icon.espaco {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
}

.stat-limpeza-numero {
    font-size: 24px;
    font-weight: 800;
    color: var(--cinza-escuro);
    font-family: 'JetBrains Mono', monospace;
}

.stat-limpeza-label {
    font-size: 14px;
    color: var(--cinza-escuro);
    font-weight: 600;
    margin-top: 4px;
}

/* Grid de Limpeza */
.limpeza-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.limpeza-card {
    padding: 0;
    overflow: hidden;
}

.limpeza-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px;
    background: var(--cinza-claro);
    border-bottom: 1px solid var(--cinza-medio);
}

.limpeza-icon-grande {
    width: 64px;
    height: 64px;
    background: var(--gradiente-principal);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
}

.limpeza-titulo h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.limpeza-titulo p {
    font-size: 14px;
    color: var(--cinza-medio);
    line-height: 1.4;
}

.limpeza-conteudo {
    padding: 24px;
}

/* Itens de Limpeza */
.limpeza-itens {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 24px;
}

.item-limpeza {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: var(--cinza-claro);
    border-radius: 8px;
}

.item-limpeza i {
    color: var(--sucesso);
    font-size: 16px;
}

.item-limpeza span:first-of-type {
    flex: 1;
    font-weight: 500;
    color: var(--cinza-escuro);
}

.item-quantidade {
    font-size: 12px;
    color: var(--cinza-medio);
    background: white;
    padding: 4px 8px;
    border-radius: 12px;
}

/* Opções de Limpeza */
.opcoes-limpeza {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 24px;
}

.opcao-checkbox {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
    transition: var(--transicao);
}

.opcao-checkbox:hover {
    background: white;
    box-shadow: var(--sombra-suave);
}

.opcao-checkbox input {
    display: none;
}

.checkmark-limpeza {
    width: 20px;
    height: 20px;
    border: 2px solid var(--cinza-medio);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transicao);
}

.opcao-checkbox input:checked + .checkmark-limpeza {
    background: var(--azul-saude);
    border-color: var(--azul-saude);
}

.opcao-checkbox input:checked + .checkmark-limpeza::after {
    content: '✓';
    color: white;
    font-weight: 700;
    font-size: 12px;
}

.opcao-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.opcao-titulo {
    font-weight: 600;
    color: var(--cinza-escuro);
}

.opcao-descricao {
    font-size: 12px;
    color: var(--cinza-medio);
}

/* Botões de Limpeza */
.btn-limpeza {
    width: 100%;
    justify-content: center;
}

/* Reset Section */
.reset-section {
    border: 2px solid var(--erro);
    background: rgba(239, 68, 68, 0.02);
}

.reset-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.reset-icon-grande.perigo {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.reset-info h3 {
    font-size: 20px;
    font-weight: 700;
    color: var(--erro);
    margin-bottom: 8px;
}

.reset-aviso {
    color: var(--cinza-escuro);
    font-weight: 500;
}

.reset-itens {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 24px;
}

.reset-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 8px;
    color: var(--erro);
    font-weight: 500;
    font-size: 14px;
}

.reset-confirmacao {
    border-top: 1px solid var(--cinza-medio);
    padding-top: 24px;
}

.confirmacao-grupo {
    margin-bottom: 16px;
}

.confirmacao-grupo label {
    display: block;
    font-weight: 600;
    color: var(--cinza-escuro);
    margin-bottom: 8px;
}

.input-confirmacao {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 16px;
    font-family: 'JetBrains Mono', monospace;
    transition: var(--transicao);
}

.input-confirmacao:focus {
    outline: none;
    border-color: var(--erro);
}

.btn-perigo {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-perigo:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--sombra-forte);
}

.btn-perigo:disabled {
    background: var(--cinza-medio);
    cursor: not-allowed;
}

/* Histórico */
.historico-lista {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.historico-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
}

.historico-icone {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
}

.historico-icone.sucesso {
    background: var(--sucesso);
}

.historico-info {
    flex: 1;
}

.historico-titulo {
    font-weight: 600;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.historico-descricao {
    font-size: 14px;
    color: var(--cinza-medio);
    margin-bottom: 4px;
}

.historico-data {
    font-size: 12px;
    color: var(--cinza-medio);
    font-family: 'JetBrains Mono', monospace;
}

.historico-status {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-sucesso {
    background: rgba(16, 185, 129, 0.1);
    color: var(--sucesso);
}

/* Responsivo */
@media (max-width: 1024px) {
    .limpeza-grid {
        grid-template-columns: 1fr;
    }
    
    .reset-itens {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Limpeza Automática
function executarLimpezaAutomatica() {
    if (confirm('Confirma a execução da limpeza automática?\n\nEsta ação irá remover dados antigos e desnecessários.')) {
        mostrarAlerta('Iniciando limpeza automática...', 'info');
        
        // Simular processo de limpeza
        setTimeout(() => {
            mostrarAlerta('Limpeza automática concluída com sucesso!', 'sucesso');
            // Atualizar estatísticas
            atualizarEstatisticas();
        }, 3000);
    }
}

// Limpeza Personalizada
document.getElementById('formLimpezaPersonalizada').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const itens = [];
    
    for (let [key, value] of formData.entries()) {
        if (value === '1') {
            itens.push(key);
        }
    }
    
    if (itens.length === 0) {
        mostrarAlerta('Selecione pelo menos um item para limpeza', 'aviso');
        return;
    }
    
    if (confirm(`Confirma a limpeza dos itens selecionados?\n\n${itens.length} item(ns) será(ão) removido(s).`)) {
        mostrarAlerta('Executando limpeza personalizada...', 'info');
        
        setTimeout(() => {
            mostrarAlerta('Limpeza personalizada concluída!', 'sucesso');
            this.reset();
            atualizarEstatisticas();
        }, 2500);
    }
});

// Reset do Sistema
document.getElementById('resetConfirmacao').addEventListener('input', function() {
    const btn = document.getElementById('btnReset');
    btn.disabled = this.value !== 'RESETAR_SISTEMA';
});

function confirmarReset() {
    const confirmacao = document.getElementById('resetConfirmacao').value;
    
    if (confirmacao !== 'RESETAR_SISTEMA') {
        mostrarAlerta('Digite exatamente "RESETAR_SISTEMA" para confirmar', 'erro');
        return;
    }
    
    if (confirm('⚠️ ÚLTIMA CONFIRMAÇÃO ⚠️\n\nEsta ação é IRREVERSÍVEL e irá remover TODOS os dados do sistema!\n\nTem absoluta certeza?')) {
        mostrarAlerta('Iniciando reset completo do sistema...', 'info');
        
        setTimeout(() => {
            mostrarAlerta('Sistema resetado com sucesso! Redirecionando...', 'sucesso');
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 2000);
        }, 5000);
    }
}

// Atualizar Estatísticas
function atualizarEstatisticas() {
    // Simular atualização das estatísticas
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Animações de carregamento
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.stat-limpeza-card, .limpeza-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>