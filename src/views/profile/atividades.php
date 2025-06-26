<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<div class="atividades-container">
    <!-- Cabeçalho da Aba -->
    <div class="atividades-header">
        <h2 class="titulo-secao">Atividades da Conta</h2>
        <p class="descricao-secao">Visualize todas as atividades realizadas em sua conta</p>
    </div>

    <!-- Filtros e Ações -->
    <div class="atividades-controles">
        <div class="filtros-atividades">
            <select id="filtro-categoria" class="fisio-select">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" 
                            <?= ($paginacao['categoria_filtro'] === $cat) ? 'selected' : '' ?>>
                        <?= ActivityLogger::formatCategory($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="button" class="btn-fisio btn-secundario" onclick="filtrarAtividades()">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </div>
        
        <div class="acoes-dados">
            <div class="dropdown">
                <button class="btn-fisio btn-primario dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fas fa-download"></i> Exportar Dados
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="exportarDados('full')">
                        <i class="fas fa-file-archive"></i> Dados Completos
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportarDados('logs')">
                        <i class="fas fa-list"></i> Apenas Atividades
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportarDados('profile')">
                        <i class="fas fa-user"></i> Apenas Perfil
                    </a>
                </div>
            </div>
            
            <?php if ($_SESSION['user_role'] !== 'admin'): ?>
            <button type="button" class="btn-fisio btn-perigo" onclick="solicitarExclusaoConta()">
                <i class="fas fa-user-times"></i> Excluir Conta
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <?php if (!empty($stats)): ?>
    <div class="atividades-stats">
        <div class="stats-grid">
            <?php 
            $statsAgrupadas = [];
            foreach ($stats as $stat) {
                $categoria = $stat['categoria'] ?? 'Outras';
                if (!isset($statsAgrupadas[$categoria])) {
                    $statsAgrupadas[$categoria] = 0;
                }
                $statsAgrupadas[$categoria] += $stat['total'];
            }
            ?>
            
            <?php foreach ($statsAgrupadas as $categoria => $total): ?>
            <div class="stat-card-atividade">
                <div class="stat-icone">
                    <?php
                    $icones = [
                        'authentication' => 'fas fa-sign-in-alt',
                        'profile' => 'fas fa-user-edit',
                        'security' => 'fas fa-shield-alt',
                        'admin' => 'fas fa-cog',
                        'data' => 'fas fa-database',
                        'account' => 'fas fa-user-circle'
                    ];
                    $icone = $icones[$categoria] ?? 'fas fa-activity';
                    ?>
                    <i class="<?= $icone ?>"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-numero"><?= $total ?></div>
                    <div class="stat-label"><?= ActivityLogger::formatCategory($categoria) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Lista de Atividades -->
    <div class="atividades-lista">
        <?php if (empty($atividades)): ?>
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h3>Nenhuma atividade encontrada</h3>
            <p>Ainda não há atividades registradas para esta conta.</p>
        </div>
        <?php else: ?>
        
        <?php foreach ($atividades as $atividade): ?>
        <div class="atividade-item">
            <div class="atividade-icone">
                <?php
                $cores = [
                    'authentication' => 'verde',
                    'profile' => 'azul',
                    'security' => 'laranja',
                    'admin' => 'roxo',
                    'data' => 'cinza',
                    'account' => 'vermelho'
                ];
                $cor = $cores[$atividade['categoria']] ?? 'cinza';
                $icone = $icones[$atividade['categoria']] ?? 'fas fa-circle';
                ?>
                <div class="icone-container icone-<?= $cor ?>">
                    <i class="<?= $icone ?>"></i>
                </div>
            </div>
            
            <div class="atividade-conteudo">
                <div class="atividade-principal">
                    <h4 class="atividade-titulo">
                        <?= ActivityLogger::formatAction($atividade['acao']) ?>
                    </h4>
                    
                    <?php if (!empty($atividade['detalhes'])): ?>
                    <p class="atividade-detalhes">
                        <?= htmlspecialchars($atividade['detalhes']) ?>
                    </p>
                    <?php endif; ?>
                </div>
                
                <div class="atividade-meta">
                    <span class="atividade-categoria badge badge-<?= $cor ?>">
                        <?= ActivityLogger::formatCategory($atividade['categoria']) ?>
                    </span>
                    
                    <span class="atividade-data">
                        <i class="fas fa-clock"></i>
                        <?= DateTimeHelper::formatDateTime(new DateTime($atividade['data_hora'])) ?>
                    </span>
                    
                    <?php if (!empty($atividade['ip_address'])): ?>
                    <span class="atividade-ip">
                        <i class="fas fa-globe"></i>
                        <?= htmlspecialchars($atividade['ip_address']) ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!$atividade['sucesso']): ?>
                    <span class="atividade-status status-erro">
                        <i class="fas fa-exclamation-triangle"></i>
                        Falhou
                    </span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($atividade['metadados'])): ?>
                <div class="atividade-metadados">
                    <button type="button" class="btn-detalhes" onclick="toggleMetadados('metadados-<?= $atividade['id'] ?>')">
                        <i class="fas fa-info-circle"></i> Ver detalhes
                    </button>
                    <div id="metadados-<?= $atividade['id'] ?>" class="metadados-conteudo" style="display: none;">
                        <pre><?= htmlspecialchars(json_encode(json_decode($atividade['metadados']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Paginação -->
        <?php if ($paginacao['total_paginas'] > 1): ?>
        <div class="paginacao-atividades">
            <div class="paginacao-info">
                Mostrando <?= (($paginacao['pagina_atual'] - 1) * 20) + 1 ?> a 
                <?= min($paginacao['pagina_atual'] * 20, $paginacao['total_registros']) ?> 
                de <?= $paginacao['total_registros'] ?> atividades
            </div>
            
            <div class="paginacao-controles">
                <?php if ($paginacao['pagina_atual'] > 1): ?>
                <a href="?page=<?= $paginacao['pagina_atual'] - 1 ?><?= $paginacao['categoria_filtro'] ? '&categoria=' . urlencode($paginacao['categoria_filtro']) : '' ?>" 
                   class="btn-pagina">
                    <i class="fas fa-chevron-left"></i> Anterior
                </a>
                <?php endif; ?>
                
                <?php 
                $inicio = max(1, $paginacao['pagina_atual'] - 2);
                $fim = min($paginacao['total_paginas'], $paginacao['pagina_atual'] + 2);
                ?>
                
                <?php for ($i = $inicio; $i <= $fim; $i++): ?>
                <a href="?page=<?= $i ?><?= $paginacao['categoria_filtro'] ? '&categoria=' . urlencode($paginacao['categoria_filtro']) : '' ?>" 
                   class="btn-pagina <?= ($i === $paginacao['pagina_atual']) ? 'ativo' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($paginacao['pagina_atual'] < $paginacao['total_paginas']): ?>
                <a href="?page=<?= $paginacao['pagina_atual'] + 1 ?><?= $paginacao['categoria_filtro'] ? '&categoria=' . urlencode($paginacao['categoria_filtro']) : '' ?>" 
                   class="btn-pagina">
                    Próxima <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Exportação -->
<div id="modal-exportacao" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Exportar Dados</h3>
            <button type="button" class="close" onclick="fecharModal('modal-exportacao')">&times;</button>
        </div>
        <div class="modal-body">
            <p>Sua solicitação de exportação foi enviada. Você receberá um email quando os dados estiverem prontos.</p>
            <div class="export-status">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Processando...</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exclusão de Conta -->
<div id="modal-exclusao" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Excluir Conta
            </h3>
            <button type="button" class="close" onclick="fecharModal('modal-exclusao')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <strong>Atenção:</strong> Esta ação não pode ser desfeita. Todos os seus dados serão permanentemente removidos.
            </div>
            
            <form id="form-exclusao">
                <div class="form-group">
                    <label for="motivo-exclusao">Motivo da exclusão (opcional):</label>
                    <textarea id="motivo-exclusao" name="motivo" class="fisio-input" rows="3" 
                              placeholder="Conte-nos por que está excluindo sua conta..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="confirmar-exclusao" required>
                        <span class="checkmark"></span>
                        Confirmo que desejo excluir permanentemente minha conta
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-fisio btn-secundario" onclick="fecharModal('modal-exclusao')">
                Cancelar
            </button>
            <button type="button" class="btn-fisio btn-perigo" onclick="confirmarExclusao()">
                <i class="fas fa-trash"></i> Excluir Conta
            </button>
        </div>
    </div>
</div>

<style>
.atividades-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.atividades-header {
    margin-bottom: 30px;
}

.titulo-secao {
    color: var(--texto);
    margin-bottom: 10px;
}

.descricao-secao {
    color: var(--texto-secundario);
    margin: 0;
}

.atividades-controles {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    gap: 20px;
}

.filtros-atividades {
    display: flex;
    gap: 10px;
    align-items: center;
}

.acoes-dados {
    display: flex;
    gap: 10px;
}

.atividades-stats {
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card-atividade {
    background: var(--fundo-secundario);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icone {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primario);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-numero {
    font-size: 24px;
    font-weight: bold;
    color: var(--texto);
}

.stat-label {
    color: var(--texto-secundario);
    font-size: 14px;
}

.atividade-item {
    background: var(--fundo-secundario);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    display: flex;
    gap: 15px;
}

.atividade-icone {
    flex-shrink: 0;
}

.icone-container {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.icone-verde { background: var(--sucesso); }
.icone-azul { background: var(--info); }
.icone-laranja { background: var(--alerta); }
.icone-roxo { background: var(--lilas-cuidado); }
.icone-cinza { background: var(--texto-secundario); }
.icone-vermelho { background: var(--erro); }

.atividade-conteudo {
    flex: 1;
}

.atividade-titulo {
    color: var(--texto);
    margin: 0 0 5px 0;
    font-size: 16px;
}

.atividade-detalhes {
    color: var(--texto-secundario);
    margin: 0 0 15px 0;
    font-size: 14px;
}

.atividade-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    font-size: 12px;
    color: var(--texto-secundario);
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
}

.badge-verde { background: rgba(16, 185, 129, 0.1); color: var(--sucesso); }
.badge-azul { background: rgba(59, 130, 246, 0.1); color: var(--info); }
.badge-laranja { background: rgba(245, 158, 11, 0.1); color: var(--alerta); }
.badge-roxo { background: rgba(124, 58, 237, 0.1); color: var(--lilas-cuidado); }
.badge-cinza { background: rgba(107, 114, 128, 0.1); color: var(--texto-secundario); }
.badge-vermelho { background: rgba(239, 68, 68, 0.1); color: var(--erro); }

.status-erro {
    color: var(--erro);
}

.btn-detalhes {
    background: none;
    border: none;
    color: var(--primario);
    cursor: pointer;
    font-size: 12px;
    padding: 5px 0;
}

.metadados-conteudo {
    background: var(--fundo-terciario);
    border: 1px solid var(--border);
    border-radius: 4px;
    padding: 10px;
    margin-top: 10px;
    font-size: 11px;
    max-height: 200px;
    overflow-y: auto;
}

.metadados-conteudo pre {
    margin: 0;
    color: var(--texto);
}

.paginacao-atividades {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
}

.paginacao-info {
    color: var(--texto-secundario);
    font-size: 14px;
}

.paginacao-controles {
    display: flex;
    gap: 5px;
}

.btn-pagina {
    padding: 8px 12px;
    background: var(--fundo-secundario);
    border: 1px solid var(--border);
    border-radius: 4px;
    color: var(--texto);
    text-decoration: none;
    font-size: 14px;
}

.btn-pagina:hover {
    background: var(--fundo-terciario);
}

.btn-pagina.ativo {
    background: var(--primario);
    color: white;
    border-color: var(--primario);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--texto-secundario);
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.empty-state h3 {
    margin-bottom: 10px;
    color: var(--texto);
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: var(--fundo);
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 20px 20px 10px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--texto);
}

.close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--texto-secundario);
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 10px 20px 20px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.export-status {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: var(--fundo-secundario);
    border-radius: 4px;
    margin-top: 15px;
}

@media (max-width: 768px) {
    .atividades-controles {
        flex-direction: column;
        align-items: stretch;
    }
    
    .acoes-dados {
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .atividade-item {
        flex-direction: column;
        gap: 10px;
    }
    
    .atividade-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .paginacao-atividades {
        flex-direction: column;
        gap: 15px;
    }
}
</style>

<script>
function filtrarAtividades() {
    const categoria = document.getElementById('filtro-categoria').value;
    const url = new URL(window.location.href);
    
    if (categoria) {
        url.searchParams.set('categoria', categoria);
    } else {
        url.searchParams.delete('categoria');
    }
    url.searchParams.delete('page'); // Reset page when filtering
    
    window.location.href = url.toString();
}

function toggleMetadados(id) {
    const elemento = document.getElementById(id);
    if (elemento.style.display === 'none') {
        elemento.style.display = 'block';
    } else {
        elemento.style.display = 'none';
    }
}

function exportarDados(tipo) {
    const formData = new FormData();
    formData.append('tipo', tipo);
    
    fetch('/profile/export-data', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modal-exportacao').style.display = 'flex';
            setTimeout(() => {
                fecharModal('modal-exportacao');
                alert('Exportação concluída! Verifique seu email.');
            }, 3000);
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar solicitação');
    });
}

function solicitarExclusaoConta() {
    document.getElementById('modal-exclusao').style.display = 'flex';
}

function confirmarExclusao() {
    if (!document.getElementById('confirmar-exclusao').checked) {
        alert('Você deve confirmar que deseja excluir a conta');
        return;
    }
    
    const motivo = document.getElementById('motivo-exclusao').value;
    const formData = new FormData();
    formData.append('motivo', motivo);
    
    fetch('/profile/request-account-deletion', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fecharModal('modal-exclusao');
            alert('Solicitação registrada. Verifique seu email para confirmar a exclusão.');
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar solicitação');
    });
}

function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
}

// Fechar modal ao clicar fora
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
    }
});
</script>