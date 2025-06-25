<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Neural Analytics Grid -->
<div class="quantum-grid">
    <!-- System Status Card -->
    <div class="quantum-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-satellite-dish"></i>
                Matriz do Sistema
            </h3>
            <div class="status-indicator status-online"></div>
        </div>
        <div class="metric-display">
            <div class="metric-value"><?= $stats['total_users'] ?? 0 ?></div>
            <div class="metric-label">Usuários Ativos</div>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 85%"></div>
        </div>
    </div>
    
    <!-- AI Neural Activity -->
    <div class="quantum-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-brain"></i>
                Atividade Neural da IA
            </h3>
            <div class="neural-pulse"></div>
        </div>
        <div class="metric-display">
            <div class="metric-value"><?= $stats['ai_requests_today'] ?? 0 ?></div>
            <div class="metric-label">Solicitações Hoje</div>
        </div>
        <div class="neural-graph">
            <div class="neural-line"></div>
            <div class="neural-line"></div>
            <div class="neural-line"></div>
        </div>
    </div>
    
    <!-- Active Sessions -->
    <div class="quantum-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-wifi"></i>
                Sessões Ativas
            </h3>
            <div class="session-blink"></div>
        </div>
        <div class="metric-display">
            <div class="metric-value"><?= $stats['active_sessions'] ?? 0 ?></div>
            <div class="metric-label">Conectados</div>
        </div>
        <div class="session-dots">
            <?php for($i = 0; $i < min(8, $stats['active_sessions'] ?? 0); $i++): ?>
                <div class="session-dot"></div>
            <?php endfor; ?>
        </div>
    </div>
    
    <!-- System Health -->
    <div class="quantum-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-heartbeat"></i>
                Saúde do Sistema
            </h3>
            <div class="health-status health-<?= $stats['system_health'] ?? 'healthy' ?>"></div>
        </div>
        <div class="health-grid">
            <div class="health-item">
                <span>Banco de Dados</span>
                <div class="health-bar health-good"></div>
            </div>
            <div class="health-item">
                <span>Motor IA</span>
                <div class="health-bar health-good"></div>
            </div>
            <div class="health-item">
                <span>Armazenamento</span>
                <div class="health-bar health-warning"></div>
            </div>
        </div>
    </div>
</div>

<!-- Command Center -->
<div class="command-center">
    <div class="command-header">
        <h2 class="command-title">
            <i class="fas fa-terminal"></i>
            Centro de Comando
        </h2>
        <div class="command-actions">
            <button class="cyber-btn btn-primary" onclick="refreshStats()">
                <i class="fas fa-sync-alt"></i>
                Sincronizar
            </button>
            <button class="cyber-btn btn-secondary" onclick="openTerminal()">
                <i class="fas fa-code"></i>
                Terminal
            </button>
        </div>
    </div>
    
    <!-- Recent Activities Stream -->
    <div class="activity-stream">
        <h3 class="stream-title">Fluxo de Atividade Neural</h3>
        <div class="stream-container" id="activityStream">
            <?php if (!empty($activities)): ?>
                <?php foreach (array_slice($activities, 0, 5) as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?= htmlspecialchars($activity['action'] ?? 'System Event') ?></div>
                            <div class="activity-details"><?= htmlspecialchars($activity['user_name'] ?? 'System') ?></div>
                            <div class="activity-time"><?= $this->timeAgo($activity['created_at'] ?? date('Y-m-d H:i:s')) ?></div>
                        </div>
                        <div class="activity-status status-<?= $activity['status'] ?? 'info' ?>"></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Sistema Iniciado</div>
                        <div class="activity-details">Aguardando atividade neural</div>
                        <div class="activity-time">agora</div>
                    </div>
                    <div class="activity-status status-success"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Holographic Console -->
<div class="holo-console">
    <div class="console-header">
        <div class="console-tabs">
            <div class="console-tab active" data-tab="overview">Overview</div>
            <div class="console-tab" data-tab="analytics">Analytics</div>
            <div class="console-tab" data-tab="terminal">Terminal</div>
        </div>
    </div>
    
    <div class="console-content">
        <div class="tab-content active" id="overview">
            <div class="overview-grid">
                <div class="overview-stat">
                    <div class="stat-icon">👥</div>
                    <div class="stat-data">
                        <div class="stat-number"><?= $stats['total_users'] ?? 0 ?></div>
                        <div class="stat-label">Total de Usuários</div>
                    </div>
                </div>
                <div class="overview-stat">
                    <div class="stat-icon">🤖</div>
                    <div class="stat-data">
                        <div class="stat-number"><?= count($aiPrompts ?? []) ?></div>
                        <div class="stat-label">Módulos IA</div>
                    </div>
                </div>
                <div class="overview-stat">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-data">
                        <div class="stat-number"><?= $stats['ai_requests_today'] ?? 0 ?></div>
                        <div class="stat-label">Solicitações Hoje</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tab-content" id="analytics">
            <div class="analytics-chart">
                <canvas id="neuralChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <div class="tab-content" id="terminal">
            <div class="cyber-terminal">
                <div class="terminal-header">
                    <span class="terminal-title">MEGAFISIO_AI_TERMINAL</span>
                    <div class="terminal-controls">
                        <div class="control-dot red"></div>
                        <div class="control-dot yellow"></div>
                        <div class="control-dot green"></div>
                    </div>
                </div>
                <div class="terminal-body">
                    <div class="terminal-line">
                        <span class="prompt">admin@megafisio:~$</span>
                        <span class="command">status --all</span>
                    </div>
                    <div class="terminal-output">
                        <div class="output-line success">✓ Banco de Dados: Conectado</div>
                        <div class="output-line success">✓ Motor IA: Online</div>
                        <div class="output-line success">✓ Segurança: Ativa</div>
                        <div class="output-line warning">⚠ Armazenamento: 78% usado</div>
                    </div>
                    <div class="terminal-cursor">
                        <span class="prompt">admin@megafisio:~$</span>
                        <span class="cursor-blink">_</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Cards Quânticos Aprimorados */
.quantum-card {
    position: relative;
    overflow: hidden;
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    animation: pulse-glow 2s infinite;
}

.status-online {
    background: var(--status-healthy);
    box-shadow: 0 0 10px var(--medical-green);
}

.metric-display {
    text-align: center;
    margin-bottom: 20px;
}

.metric-value {
    font-size: 2.5rem;
    font-weight: 700;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-family: 'JetBrains Mono', monospace;
}

.metric-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-top: 5px;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: var(--medical-mint);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-secondary);
    border-radius: 2px;
    animation: progress-glow 2s ease-in-out infinite alternate;
}

.neural-pulse {
    width: 15px;
    height: 15px;
    background: var(--medical-teal);
    border-radius: 50%;
    animation: neural-pulse 1.5s infinite;
}

.neural-graph {
    display: flex;
    gap: 5px;
    height: 40px;
    align-items: flex-end;
}

.neural-line {
    flex: 1;
    background: var(--gradient-primary);
    border-radius: 2px;
    animation: neural-activity 2s infinite ease-in-out;
}

.neural-line:nth-child(1) { height: 60%; animation-delay: 0s; }
.neural-line:nth-child(2) { height: 80%; animation-delay: 0.2s; }
.neural-line:nth-child(3) { height: 40%; animation-delay: 0.4s; }

.session-blink {
    width: 8px;
    height: 8px;
    background: var(--medical-blue);
    border-radius: 50%;
    animation: blink 1s infinite;
}

.session-dots {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.session-dot {
    width: 6px;
    height: 6px;
    background: var(--medical-blue);
    border-radius: 50%;
    animation: dot-pulse 2s infinite;
}

.session-dot:nth-child(even) {
    animation-delay: 0.5s;
}

.health-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.health-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.8rem;
}

.health-bar {
    width: 60px;
    height: 3px;
    border-radius: 2px;
}

.health-good { background: var(--status-healthy); }
.health-warning { background: var(--status-warning); }
.health-error { background: var(--status-critical); }

/* Command Center */
.command-center {
    background: var(--glass-dark);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(0, 245, 255, 0.1);
    border-radius: 20px;
    padding: 25px;
    margin: 30px 0;
}

.command-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
}

.command-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-cyber);
    display: flex;
    align-items: center;
    gap: 10px;
}

.command-actions {
    display: flex;
    gap: 10px;
}

.cyber-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition-smooth);
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.btn-primary {
    background: var(--gradient-secondary);
    color: white;
    box-shadow: var(--glow-cyan);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--glow-purple);
}

.btn-secondary {
    background: var(--glass-dark);
    color: var(--text-cyber);
    border: 1px solid rgba(0, 245, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(0, 245, 255, 0.1);
    border-color: var(--neon-cyan);
}

/* Activity Stream */
.activity-stream {
    margin-top: 20px;
}

.stream-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-cyber);
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: rgba(0, 245, 255, 0.03);
    border-radius: 10px;
    margin-bottom: 10px;
    border-left: 3px solid var(--neon-cyan);
    transition: var(--transition-smooth);
}

.activity-item:hover {
    background: rgba(0, 245, 255, 0.08);
    transform: translateX(5px);
}

.activity-icon {
    width: 35px;
    height: 35px;
    background: var(--gradient-secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: var(--text-cyber);
    font-size: 0.9rem;
}

.activity-details {
    color: var(--text-muted);
    font-size: 0.8rem;
    margin: 2px 0;
}

.activity-time {
    color: var(--neon-cyan);
    font-size: 0.7rem;
    font-family: 'JetBrains Mono', monospace;
}

.activity-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-success { background: var(--neon-green); }
.status-warning { background: #fdcb6e; }
.status-error { background: var(--neon-pink); }
.status-info { background: var(--neon-cyan); }

/* Holographic Console */
.holo-console {
    background: var(--glass-dark);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(108, 92, 231, 0.2);
    border-radius: 20px;
    margin: 30px 0;
    overflow: hidden;
}

.console-header {
    background: rgba(108, 92, 231, 0.1);
    border-bottom: 1px solid rgba(108, 92, 231, 0.2);
    padding: 0;
}

.console-tabs {
    display: flex;
}

.console-tab {
    padding: 15px 25px;
    cursor: pointer;
    color: var(--text-muted);
    transition: var(--transition-smooth);
    border-bottom: 3px solid transparent;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 1px;
}

.console-tab.active {
    color: var(--neon-purple);
    border-bottom-color: var(--neon-purple);
    background: rgba(108, 92, 231, 0.1);
}

.console-tab:hover {
    color: var(--text-cyber);
    background: rgba(108, 92, 231, 0.05);
}

.console-content {
    padding: 25px;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.overview-stat {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: rgba(0, 245, 255, 0.05);
    border-radius: 15px;
    border: 1px solid rgba(0, 245, 255, 0.1);
}

.stat-icon {
    font-size: 2rem;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-cyber);
    font-family: 'JetBrains Mono', monospace;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Terminal Cyberpunk */
.cyber-terminal {
    background: #0a0a0f;
    border-radius: 10px;
    overflow: hidden;
    font-family: 'JetBrains Mono', monospace;
}

.terminal-header {
    background: #1a1a2e;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #333;
}

.terminal-title {
    color: var(--neon-cyan);
    font-size: 0.8rem;
    font-weight: 600;
}

.terminal-controls {
    display: flex;
    gap: 8px;
}

.control-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.red { background: #ff6b6b; }
.yellow { background: #fdcb6e; }
.green { background: var(--neon-green); }

.terminal-body {
    padding: 20px;
    color: var(--neon-green);
    line-height: 1.8;
}

.terminal-line {
    margin-bottom: 10px;
}

.prompt {
    color: var(--neon-cyan);
}

.command {
    color: white;
    margin-left: 10px;
}

.terminal-output {
    margin: 15px 0;
    padding-left: 20px;
}

.output-line {
    margin-bottom: 5px;
}

.output-line.success {
    color: var(--neon-green);
}

.output-line.warning {
    color: #fdcb6e;
}

.cursor-blink {
    animation: cursor-blink 1s infinite;
}

/* Animações */
@keyframes pulse-glow {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
}

@keyframes neural-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.8; }
}

@keyframes neural-activity {
    0%, 100% { height: 20%; }
    50% { height: 100%; }
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}

@keyframes dot-pulse {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.3); }
}

@keyframes progress-glow {
    0% { box-shadow: 0 0 5px var(--neon-cyan); }
    100% { box-shadow: 0 0 15px var(--neon-cyan); }
}

@keyframes cursor-blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}
</style>

<script>
// Console Tab Switching
document.querySelectorAll('.console-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Remove active from all tabs and contents
        document.querySelectorAll('.console-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active to clicked tab and corresponding content
        this.classList.add('active');
        document.getElementById(this.dataset.tab).classList.add('active');
    });
});

// Refresh Stats
function refreshStats() {
    // Animate button
    const btn = event.target.closest('.cyber-btn');
    const icon = btn.querySelector('i');
    
    icon.style.animation = 'spin 1s linear';
    btn.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        icon.style.animation = '';
        btn.style.transform = '';
        
        // Simulate data refresh
        document.querySelectorAll('.metric-value').forEach(val => {
            const current = parseInt(val.textContent);
            val.style.transform = 'scale(1.1)';
            setTimeout(() => {
                val.style.transform = '';
            }, 200);
        });
    }, 1000);
}

// Open Terminal
function openTerminal() {
    // Switch to terminal tab
    document.querySelectorAll('.console-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    
    document.querySelector('[data-tab="terminal"]').classList.add('active');
    document.getElementById('terminal').classList.add('active');
    
    // Scroll to console
    document.querySelector('.holo-console').scrollIntoView({ 
        behavior: 'smooth',
        block: 'center'
    });
}

// Auto-refresh activity stream
setInterval(() => {
    const stream = document.getElementById('activityStream');
    if (stream) {
        // Add subtle pulse to show activity
        stream.style.borderLeft = '3px solid var(--neon-cyan)';
        setTimeout(() => {
            stream.style.borderLeft = '';
        }, 500);
    }
}, 30000);

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.key === 'r') {
        e.preventDefault();
        refreshStats();
    }
    
    if (e.ctrlKey && e.key === 't') {
        e.preventDefault();
        openTerminal();
    }
});
</script>