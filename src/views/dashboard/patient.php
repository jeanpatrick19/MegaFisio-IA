<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Dashboard do Paciente</h1>
<p class="subtitulo-pagina-escuro">Acompanhe seus tratamentos e evolução</p>

<!-- Cards de Informações -->
<div class="grid-fisio" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-calendar-check"></i>
                <span>Próximas Consultas</span>
            </div>
        </div>
        <div class="stat-valor">3</div>
        <div class="stat-descricao">Agendadas este mês</div>
    </div>

    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-file-medical"></i>
                <span>Documentos</span>
            </div>
        </div>
        <div class="stat-valor">8</div>
        <div class="stat-descricao">Relatórios disponíveis</div>
    </div>

    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-chart-line"></i>
                <span>Evolução</span>
            </div>
        </div>
        <div class="stat-valor">92%</div>
        <div class="stat-descricao">Progresso do tratamento</div>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="card-fisio">
    <div class="card-header-fisio">
        <div class="card-titulo">
            <i class="fas fa-info-circle"></i>
            <span>Área do Paciente</span>
        </div>
    </div>
    
    <div style="padding: 32px; text-align: center;">
        <i class="fas fa-user-injured" style="font-size: 4rem; color: var(--azul-saude); margin-bottom: 20px;"></i>
        <h3 style="color: var(--azul-saude); margin-bottom: 12px;">Bem-vindo(a) à sua área!</h3>
        <p style="color: #6b7280; margin-bottom: 24px;">
            Aqui você poderá acompanhar seus tratamentos, consultar relatórios e muito mais.
        </p>
        <p style="color: #6b7280; font-size: 0.9rem;">
            Esta área está em desenvolvimento e em breve terá todas as funcionalidades disponíveis.
        </p>
    </div>
</div>

<style>
.stat-valor {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--azul-saude);
    margin: 16px 0 8px 0;
}

.stat-descricao {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
}
</style>