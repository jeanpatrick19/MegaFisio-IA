<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página não encontrada</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 24px;
            color: #1f2937;
            margin: 16px 0;
            font-weight: 700;
        }
        .error-message {
            color: #6b7280;
            margin-bottom: 32px;
            font-size: 16px;
        }
        .error-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .btn-voltar {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-voltar:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔍</div>
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Página não encontrada</h2>
        <p class="error-message">A página que você está procurando não existe ou foi movida.</p>
        <a href="<?= BASE_URL ?>" class="btn-voltar">Voltar ao Início</a>
    </div>
</body>
</html>
