<?php
/**
 * Redirecionamento para pasta public
 * Este arquivo garante que localhost:8080/ funcione
 */

// Verificar se é uma requisição para a raiz
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

// Se acessou a raiz diretamente, redirecionar para welcome
if ($requestUri === '/' || $requestUri === '/index.php') {
    header('Location: welcome');
    exit;
}

// Para outras rotas, incluir o public/index.php com a rota
$route = trim($requestUri, '/');
$_GET['route'] = $route;

require_once 'public/index.php';
?>