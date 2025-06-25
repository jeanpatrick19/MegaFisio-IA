<?php
/**
 * Configuração de Ambiente
 * Para facilitar a mudança entre desenvolvimento e produção
 */

// Sempre no modo produção para facilitar deploy
$isProduction = true;

// Configurações específicas do ambiente  
return [
    'is_production' => $isProduction,
    
    // URLs base - ajustado para desenvolvimento localhost:8080
    'base_url' => 'http://localhost:8080',
    'base_path' => '/',
    'public_url' => 'http://localhost:8080',
    
    // Debug habilitado para ver erros
    'debug' => true,
    'display_errors' => true,
    
    // Configurações de sessão (produção)
    'session_secure' => false, // false para localhost funcionar
    'session_httponly' => true,
    
    // Headers de segurança
    'security_headers' => true
];