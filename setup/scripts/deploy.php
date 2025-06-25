<?php
/**
 * Script de deploy para produção
 * Executa todas as tarefas necessárias para deploy seguro
 */

class Deployer {
    private $rootPath;
    private $steps = [];
    private $errors = [];
    
    public function __construct() {
        $this->rootPath = dirname(dirname(__DIR__));
        $this->defineSteps();
    }
    
    private function defineSteps() {
        $this->steps = [
            'checkEnvironment' => 'Verificar ambiente',
            'backupDatabase' => 'Backup do banco de dados',
            'updateConfigs' => 'Atualizar configurações',
            'runMigrations' => 'Executar migrations',
            'clearCache' => 'Limpar cache',
            'optimizeAutoloader' => 'Otimizar autoloader',
            'setPermissions' => 'Configurar permissões',
            'testSystem' => 'Testar sistema',
            'cleanup' => 'Limpeza final'
        ];
    }
    
    public function run() {
        echo "=== MEGA FISIO IA - DEPLOY ===\n\n";
        
        foreach ($this->steps as $method => $description) {
            echo "► $description... ";
            
            try {
                $this->$method();
                echo "✓\n";
            } catch (Exception $e) {
                echo "✗\n";
                $this->errors[] = "$description: " . $e->getMessage();
                
                // Parar em erros críticos
                if (in_array($method, ['checkEnvironment', 'backupDatabase'])) {
                    break;
                }
            }
        }
        
        $this->showResults();
    }
    
    private function checkEnvironment() {
        // Verificar se está em produção
        if (!file_exists($this->rootPath . '/.env.production')) {
            throw new Exception('Arquivo .env.production não encontrado');
        }
        
        // Verificar PHP
        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
            throw new Exception('PHP 8.0+ necessário');
        }
        
        // Verificar extensões
        $required = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'openssl'];
        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                throw new Exception("Extensão $ext não encontrada");
            }
        }
    }
    
    private function backupDatabase() {
        $backupDir = $this->rootPath . '/backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $backupFile = $backupDir . '/backup_' . date('Y-m-d_His') . '.sql';
        
        // Comando para backup (ajuste conforme necessário)
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s 2>&1',
            'localhost',
            'usuario_producao',
            'senha_producao',
            'banco_producao',
            $backupFile
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new Exception('Falha no backup do banco');
        }
        
        // Comprimir backup
        exec("gzip $backupFile");
    }
    
    private function updateConfigs() {
        // Copiar configurações de produção
        $configs = [
            '/setup/configs/config_production.php' => '/config/config.php',
            '/setup/configs/database_production.php' => '/config/database.php',
            '/setup/configs/.htaccess_production' => '/public/.htaccess'
        ];
        
        foreach ($configs as $source => $dest) {
            $sourceFile = $this->rootPath . $source;
            $destFile = $this->rootPath . $dest;
            
            if (file_exists($sourceFile)) {
                // Backup do arquivo atual
                if (file_exists($destFile)) {
                    copy($destFile, $destFile . '.backup');
                }
                
                copy($sourceFile, $destFile);
            }
        }
    }
    
    private function runMigrations() {
        require_once $this->rootPath . '/config/database.php';
        require_once $this->rootPath . '/src/models/MigrationManager.php';
        
        $db = Database::getInstance();
        $migrationManager = new MigrationManager($db);
        $migrationManager->run();
    }
    
    private function clearCache() {
        // Limpar diretórios de cache
        $cacheDirs = [
            '/cache',
            '/logs',
            '/tmp'
        ];
        
        foreach ($cacheDirs as $dir) {
            $path = $this->rootPath . $dir;
            if (is_dir($path)) {
                $this->clearDirectory($path);
            }
        }
    }
    
    private function optimizeAutoloader() {
        // Se usar Composer (futuro)
        if (file_exists($this->rootPath . '/composer.json')) {
            exec('composer dump-autoload --optimize --no-dev');
        }
    }
    
    private function setPermissions() {
        // Diretórios que precisam escrita
        $writableDirs = [
            '/uploads' => '0755',
            '/logs' => '0755',
            '/cache' => '0755',
            '/backups' => '0755'
        ];
        
        foreach ($writableDirs as $dir => $permission) {
            $path = $this->rootPath . $dir;
            if (!is_dir($path)) {
                mkdir($path, octdec($permission), true);
            } else {
                chmod($path, octdec($permission));
            }
        }
        
        // Proteger arquivos sensíveis
        $protectedFiles = [
            '/.env' => '0600',
            '/.env.production' => '0600',
            '/config/database.php' => '0644'
        ];
        
        foreach ($protectedFiles as $file => $permission) {
            $path = $this->rootPath . $file;
            if (file_exists($path)) {
                chmod($path, octdec($permission));
            }
        }
    }
    
    private function testSystem() {
        // Teste básico de conexão
        require_once $this->rootPath . '/config/database.php';
        
        $db = Database::getInstance();
        if (!$db->healthCheck()) {
            throw new Exception('Falha no teste de conexão com banco');
        }
        
        // Verificar tabelas essenciais
        $tables = ['users', 'migrations', 'system_logs'];
        foreach ($tables as $table) {
            $stmt = $db->query("SHOW TABLES LIKE '$table'");
            if (!$stmt->fetch()) {
                throw new Exception("Tabela '$table' não encontrada");
            }
        }
    }
    
    private function cleanup() {
        // Remover arquivos de desenvolvimento
        $removeFiles = [
            '/setup/auto_install.php',
            '/install.php',
            '/test_connection.php',
            '/.git',
            '/README.md' // Opcional
        ];
        
        foreach ($removeFiles as $file) {
            $path = $this->rootPath . $file;
            if (file_exists($path)) {
                if (is_dir($path)) {
                    $this->removeDirectory($path);
                } else {
                    unlink($path);
                }
            }
        }
    }
    
    private function clearDirectory($dir) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->clearDirectory($file);
            } else {
                unlink($file);
            }
        }
    }
    
    private function removeDirectory($dir) {
        if (!is_dir($dir)) return;
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
    
    private function showResults() {
        echo "\n=== RESULTADO DO DEPLOY ===\n";
        
        if (empty($this->errors)) {
            echo "\n✅ Deploy concluído com sucesso!\n";
            echo "\nPróximos passos:\n";
            echo "1. Teste o sistema acessando a URL de produção\n";
            echo "2. Monitore os logs em /logs/error.log\n";
            echo "3. Configure o cron para backups automáticos\n";
            echo "4. Ative o monitoramento de performance\n";
        } else {
            echo "\n❌ Deploy concluído com erros:\n";
            foreach ($this->errors as $error) {
                echo "- $error\n";
            }
        }
    }
}

// Executar apenas via CLI
if (php_sapi_name() !== 'cli') {
    die('Este script deve ser executado via linha de comando');
}

// Executar deploy
$deployer = new Deployer();
$deployer->run();