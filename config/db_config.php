<?php
/**
 * Configuração de Banco de Dados
 * 
 * Para facilitar deploy:
 * 1. Em desenvolvimento: usa as configurações padrão
 * 2. Em produção: você pode alterar apenas este arquivo
 */

// Detectar se está em localhost
$isLocalhost = (
    isset($_SERVER['HTTP_HOST']) && 
    (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
) || php_sapi_name() === 'cli'; // Forçar localhost quando executando via CLI

if ($isLocalhost) {
    // DESENVOLVIMENTO - Configurações locais
    return [
        'host' => 'localhost',
        'database' => 'megafisio_ia',
        'username' => 'megafisio',
        'password' => 'MegaFisio123!',
        'charset' => 'utf8mb4'
    ];
} else {
    // PRODUÇÃO - Altere aqui com os dados da sua hospedagem
    return [
        'host' => 'localhost',                    // Host do MySQL da hospedagem
        'database' => 'megafisio_ia',             // Nome do banco na hospedagem
        'username' => 'seu_usuario_mysql',        // Usuário do MySQL da hospedagem
        'password' => 'sua_senha_mysql',          // Senha do MySQL da hospedagem
        'charset' => 'utf8mb4'
    ];
}