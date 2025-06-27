<?php
/**
 * Configuração de Ambiente
 * Para facilitar a mudança entre desenvolvimento e produção
 */

// Detectar automaticamente se está em produção ou desenvolvimento
$isLocalhost = (
    isset($_SERVER['HTTP_HOST']) && 
    (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
);

$isProduction = !$isLocalhost;

// Configurações dinâmicas baseadas no ambiente
if ($isProduction) {
    // PRODUÇÃO - configurações para hospedagem
    $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $baseUrl .= $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    $config = [
        'is_production' => true,
        'base_url' => $baseUrl,
        'base_path' => '/',
        'public_url' => $baseUrl,
        'debug' => false,
        'display_errors' => false,
        'session_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'session_httponly' => true,
        'security_headers' => true
    ];
} else {
    // DESENVOLVIMENTO - configurações para localhost
    $config = [
        'is_production' => false,
        'base_url' => 'http://localhost:8080',
        'base_path' => '/',
        'public_url' => 'http://localhost:8080',
        'debug' => true,
        'display_errors' => true,
        'session_secure' => false,
        'session_httponly' => true,
        'security_headers' => true
    ];
}

return $config;