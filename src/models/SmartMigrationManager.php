<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

class SmartMigrationManager {
    private $db;
    private $database;
    private static $instance = null;
    
    public function __construct($db) {
        $this->db = $db;
        $config = require __DIR__ . '/../../config/db_config.php';
        $this->database = $config['database'];
        self::$instance = $this;
    }
    
    /**
     * Método estático para usar em qualquer lugar do sistema
     * Cria automaticamente tabelas/campos se não existirem
     */
    public static function ensureSchema($tableName, $columns) {
        if (self::$instance === null) {
            $db = Database::getInstance();
            self::$instance = new self($db);
        }
        
        self::$instance->ensureTableStructure($tableName, $columns);
    }
    
    /**
     * Método rápido para adicionar campo em qualquer tabela
     */
    public static function ensureColumn($tableName, $columnName, $definition) {
        if (self::$instance === null) {
            $db = Database::getInstance();
            self::$instance = new self($db);
        }
        
        self::$instance->addColumnIfNotExists($tableName, $columnName, $definition);
    }
    
    /**
     * Verifica se um campo existe na tabela
     */
    private function columnExists($table, $column) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
            ");
            $stmt->execute([$this->database, $table, $column]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Adiciona campo se não existir
     */
    private function addColumnIfNotExists($table, $column, $definition) {
        if (!$this->columnExists($table, $column)) {
            try {
                $sql = "ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}";
                $this->db->query($sql);
                return true;
            } catch (Exception $e) {
                error_log("Erro ao adicionar coluna {$column} na tabela {$table}: " . $e->getMessage());
                return false;
            }
        }
        return true;
    }
    
    public function createAllTables() {
        try {
            $this->createMigrationsTable();
            $this->createUsersTable();
            $this->createProfessionalProfilesTable();
            $this->createSystemLogsTable();
            $this->createAiPromptsTable();
            $this->createAiRequestsTable();
            $this->createResponseCacheTable();
            $this->createNotificationsTable();
            $this->createSettingsTable();
            $this->createUserSessionsTable();
            $this->createRateLimitsTable();
            $this->createUserLogsTable();
            $this->createUserSessionsActiveTable();
            $this->createPromptHistoryTable();
            $this->insertDefaultSettings();
            $this->insertDefaultAdmin();
            $this->insertDefaultPrompts();
            $this->updateAiPromptsStructure();
            $this->smartSchemaUpdate();
        } catch (Exception $e) {
            die("Erro ao criar estrutura do banco: " . $e->getMessage());
        }
    }
    
    private function createMigrationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            version VARCHAR(50) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            description TEXT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
    }
    
    private function createUsersTable() {
        // Criar tabela base
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            role ENUM('admin', 'usuario', 'professional', 'patient') DEFAULT 'usuario',
            status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
            email_verified_at TIMESTAMP NULL,
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        
        // Adicionar colunas extras se não existirem
        $this->addColumnIfNotExists('users', 'first_login', 'BOOLEAN DEFAULT TRUE');
        $this->addColumnIfNotExists('users', 'last_login', 'TIMESTAMP NULL');
        $this->addColumnIfNotExists('users', 'password_reset_token', 'VARCHAR(255) NULL');
        $this->addColumnIfNotExists('users', 'password_reset_expires', 'TIMESTAMP NULL');
        $this->addColumnIfNotExists('users', 'login_attempts', 'INT DEFAULT 0');
        $this->addColumnIfNotExists('users', 'locked_until', 'TIMESTAMP NULL');
        
        // Criar índices
        $this->createIndexIfNotExists('users', 'idx_email', '(email)');
        $this->createIndexIfNotExists('users', 'idx_status', '(status)');
        $this->createIndexIfNotExists('users', 'idx_role', '(role)');
        $this->createIndexIfNotExists('users', 'idx_email_status', '(email, status)');
        $this->createIndexIfNotExists('users', 'idx_password_reset_token', '(password_reset_token)');
        $this->createIndexIfNotExists('users', 'idx_locked_until', '(locked_until)');
    }
    
    private function createProfessionalProfilesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS professional_profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            crefito VARCHAR(50) NULL,
            specialties TEXT,
            phone VARCHAR(20) NULL,
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('professional_profiles', 'fk_prof_user', 'user_id', 'users', 'id', 'CASCADE');
    }
    
    private function createSystemLogsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS system_logs (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            action VARCHAR(100) NOT NULL,
            entity_type VARCHAR(50) NULL,
            entity_id INT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT,
            details JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_action (action),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('system_logs', 'fk_syslog_user', 'user_id', 'users', 'id', 'SET NULL');
    }
    
    private function createAiPromptsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS ai_prompts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(120) NOT NULL COMMENT 'Nome do módulo/menu',
            descricao TEXT NOT NULL COMMENT 'Explicação da função',
            prompt_template TEXT NOT NULL COMMENT 'Template do prompt com variáveis',
            status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
            limite_requisicoes INT NULL COMMENT 'Max requisições por usuário/dia',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            updated_by INT NULL COMMENT 'Usuário que fez a última alteração',
            INDEX idx_status (status),
            INDEX idx_updated_by (updated_by)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('ai_prompts', 'fk_prompt_updated_by', 'updated_by', 'users', 'id', 'SET NULL');
    }
    
    private function createAiRequestsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS ai_requests (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            prompt_id INT NULL,
            input_usuario TEXT COMMENT 'O que o usuário digitou',
            prompt_gerado TEXT COMMENT 'Prompt final enviado à OpenAI',
            resposta_ia TEXT COMMENT 'Resposta da IA',
            tokens_used INT DEFAULT 0,
            processing_time DECIMAL(10,3),
            status ENUM('sucesso', 'erro', 'em_cache', 'processando') DEFAULT 'processando',
            error_message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_prompt_id (prompt_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('ai_requests', 'fk_aireq_user', 'user_id', 'users', 'id', 'CASCADE');
        $this->addForeignKeyIfNotExists('ai_requests', 'fk_aireq_prompt', 'prompt_id', 'ai_prompts', 'id', 'SET NULL');
    }
    
    private function createResponseCacheTable() {
        $sql = "CREATE TABLE IF NOT EXISTS response_cache (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cache_key VARCHAR(255) NOT NULL UNIQUE,
            request_hash VARCHAR(64) NOT NULL,
            response_data JSON NOT NULL,
            hit_count INT DEFAULT 0,
            expires_at TIMESTAMP NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_cache_key (cache_key),
            INDEX idx_expires_at (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
    }
    
    private function createNotificationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            type VARCHAR(50) NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            data JSON,
            read_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_read_at (read_at),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('notifications', 'fk_notif_user', 'user_id', 'users', 'id', 'CASCADE');
    }
    
    private function createSettingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(100) NOT NULL UNIQUE,
            `value` TEXT,
            `type` VARCHAR(20) DEFAULT 'string',
            description TEXT,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_key (`key`),
            INDEX idx_public (is_public)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
    }
    
    private function createUserSessionsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS user_sessions (
            id VARCHAR(128) PRIMARY KEY,
            user_id INT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT,
            payload TEXT NOT NULL,
            last_activity INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_last_activity (last_activity)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('user_sessions', 'fk_session_user', 'user_id', 'users', 'id', 'CASCADE');
    }
    
    private function createRateLimitsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS rate_limits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            identifier VARCHAR(255) NOT NULL,
            action VARCHAR(100) NOT NULL,
            attempts INT DEFAULT 1,
            reset_at TIMESTAMP NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_identifier_action (identifier, action),
            INDEX idx_reset_at (reset_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
    }
    
    private function createUserLogsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS user_logs (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            email VARCHAR(255) NULL,
            acao VARCHAR(50) NOT NULL,
            detalhes TEXT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            sucesso BOOLEAN DEFAULT TRUE,
            data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_acao (acao),
            INDEX idx_data_hora (data_hora),
            INDEX idx_ip_address (ip_address),
            INDEX idx_email_acao (email, acao)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('user_logs', 'fk_userlog_user', 'user_id', 'users', 'id', 'SET NULL');
    }
    
    private function createUserSessionsActiveTable() {
        $sql = "CREATE TABLE IF NOT EXISTS user_sessions_active (
            id VARCHAR(128) PRIMARY KEY,
            user_id INT NOT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            INDEX idx_user_id (user_id),
            INDEX idx_expires_at (expires_at),
            INDEX idx_last_activity (last_activity),
            INDEX idx_is_active (is_active)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('user_sessions_active', 'fk_active_session_user', 'user_id', 'users', 'id', 'CASCADE');
    }
    
    private function createPromptHistoryTable() {
        $sql = "CREATE TABLE IF NOT EXISTS prompt_history (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            prompt_id INT NOT NULL,
            nome_anterior VARCHAR(120),
            descricao_anterior TEXT,
            prompt_template_anterior TEXT,
            status_anterior ENUM('ativo', 'inativo'),
            limite_requisicoes_anterior INT,
            nome_novo VARCHAR(120),
            descricao_novo TEXT,
            prompt_template_novo TEXT,
            status_novo ENUM('ativo', 'inativo'),
            limite_requisicoes_novo INT,
            acao ENUM('criado', 'editado', 'excluido') NOT NULL,
            alterado_por INT NOT NULL,
            alterado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_prompt_id (prompt_id),
            INDEX idx_alterado_por (alterado_por),
            INDEX idx_alterado_em (alterado_em)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->query($sql);
        $this->addForeignKeyIfNotExists('prompt_history', 'fk_history_prompt', 'prompt_id', 'ai_prompts', 'id', 'CASCADE');
        $this->addForeignKeyIfNotExists('prompt_history', 'fk_history_user', 'alterado_por', 'users', 'id', 'CASCADE');
    }
    
    
    private function createIndexIfNotExists($table, $indexName, $columns) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?");
            $stmt->execute([$this->database, $table, $indexName]);
            
            if ($stmt->fetchColumn() == 0) {
                $this->db->query("ALTER TABLE `$table` ADD INDEX `$indexName` $columns");
            }
        } catch (Exception $e) {
            // Ignorar erros de índice duplicado
        }
    }
    
    private function addForeignKeyIfNotExists($table, $fkName, $column, $refTable, $refColumn, $onDelete) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?");
            $stmt->execute([$this->database, $table, $fkName]);
            
            if ($stmt->fetchColumn() == 0) {
                $this->db->query("ALTER TABLE `$table` ADD CONSTRAINT `$fkName` FOREIGN KEY (`$column`) REFERENCES `$refTable`(`$refColumn`) ON DELETE $onDelete");
            }
        } catch (Exception $e) {
            // Ignorar erros de FK duplicada
        }
    }
    
    private function insertDefaultSettings() {
        $settings = [
            ['max_login_attempts', '5', 'integer', 'Máximo de tentativas de login antes do bloqueio', 0],
            ['lockout_duration', '900', 'integer', 'Duração do bloqueio em segundos (15 minutos)', 0],
            ['session_lifetime', '3600', 'integer', 'Duração da sessão em segundos (1 hora)', 0],
            ['password_min_length', '8', 'integer', 'Tamanho mínimo da senha', 0],
            ['require_password_change', 'true', 'boolean', 'Forçar troca de senha no primeiro login', 0],
            ['password_reset_token_lifetime', '3600', 'integer', 'Validade do token de reset em segundos (1 hora)', 0],
            ['openai_api_key', '', 'string', 'Chave da API OpenAI (configurar via .env)', 0],
            ['openai_model', 'gpt-4o-mini', 'string', 'Modelo OpenAI a ser usado', 0],
            ['ai_requests_limit_daily', '50', 'integer', 'Limite diário padrão de requisições IA por usuário', 0],
            ['ai_cache_duration', '3600', 'integer', 'Duração do cache de respostas IA em segundos', 0]
        ];
        
        foreach ($settings as $setting) {
            try {
                $stmt = $this->db->prepare("INSERT INTO settings (`key`, `value`, `type`, description, is_public) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `key` = `key`");
                $stmt->execute($setting);
            } catch (Exception $e) {
                // Ignorar erros de duplicação
            }
        }
    }
    
    private function insertDefaultAdmin() {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (email, password, name, role, status, first_login) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE email = email");
            $stmt->execute([
                'admin@megafisio.com',
                '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // senha: admin123
                'Administrador do Sistema',
                'admin',
                'active',
                1
            ]);
        } catch (Exception $e) {
            // Ignorar erros de duplicação
        }
    }
    
    private function insertDefaultPrompts() {
        $prompts = [
            [
                'nome' => 'Análise de Caso Clínico',
                'descricao' => 'Análise completa de caso clínico com sugestões de tratamento',
                'prompt_template' => 'Analise o seguinte caso clínico de fisioterapia:

Paciente: {nome_paciente}
Idade: {idade}
Diagnóstico: {diagnostico}
Queixa principal: {queixa_principal}
História clínica: {historia_clinica}
Exame físico: {exame_fisico}

Por favor, forneça:
1. Análise detalhada do caso
2. Possíveis complicações e contraindicações
3. Objetivos do tratamento fisioterapêutico
4. Sugestões de técnicas e exercícios
5. Progressão esperada do tratamento
6. Orientações para o paciente

Responda de forma técnica mas compreensível.',
                'status' => 'ativo',
                'limite_requisicoes' => 30
            ],
            [
                'nome' => 'Geração de Relatório',
                'descricao' => 'Geração automatizada de relatórios de evolução',
                'prompt_template' => 'Gere um relatório de evolução fisioterapêutica baseado nos seguintes dados:

Paciente: {nome_paciente}
Período: {periodo_tratamento}
Diagnóstico: {diagnostico}
Objetivos iniciais: {objetivos_iniciais}
Tratamentos realizados: {tratamentos_realizados}
Evolução observada: {evolucao_observada}
Avaliações funcionais: {avaliacoes_funcionais}

Por favor, elabore um relatório profissional incluindo:
1. Resumo do caso
2. Evolução clínica detalhada
3. Objetivos alcançados
4. Recomendações para continuidade
5. Prognóstico funcional

Use linguagem técnica adequada para outros profissionais de saúde.',
                'status' => 'ativo',
                'limite_requisicoes' => 20
            ],
            [
                'nome' => 'Sugestão de Exercícios',
                'descricao' => 'Sugestões personalizadas de exercícios terapêuticos',
                'prompt_template' => 'Sugira exercícios fisioterapêuticos para:

Condição: {condicao}
Região afetada: {regiao_afetada}
Fase do tratamento: {fase_tratamento}
Limitações do paciente: {limitacoes}
Recursos disponíveis: {recursos_disponiveis}
Idade do paciente: {idade_paciente}

Forneça:
1. Lista de exercícios específicos com descrição detalhada
2. Parâmetros (séries, repetições, tempo)
3. Progressão sugerida
4. Cuidados e contraindicações
5. Adaptações se necessário
6. Orientações de segurança

Organize a resposta de forma prática e didática.',
                'status' => 'ativo',
                'limite_requisicoes' => 40
            ],
            [
                'nome' => 'Classificação CID-10',
                'descricao' => 'Auxílio na classificação e codificação CID-10',
                'prompt_template' => 'Auxilie na classificação CID-10 para fisioterapia:

Descrição clínica: {descricao_clinica}
Sinais e sintomas: {sinais_sintomas}
Localização: {localizacao}
Etiologia: {etiologia}

Por favor, forneça:
1. Códigos CID-10 mais apropriados
2. Justificativa para a escolha
3. Códigos alternativos se aplicável
4. Orientações sobre uso correto
5. Considerações especiais para fisioterapia

Seja preciso e baseie-se nas diretrizes oficiais da CID-10.',
                'status' => 'ativo',
                'limite_requisicoes' => 25
            ]
        ];
        
        foreach ($prompts as $prompt) {
            try {
                $stmt = $this->db->prepare("INSERT INTO ai_prompts (nome, descricao, prompt_template, status, limite_requisicoes) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nome = nome");
                $stmt->execute([$prompt['nome'], $prompt['descricao'], $prompt['prompt_template'], $prompt['status'], $prompt['limite_requisicoes']]);
            } catch (Exception $e) {
                // Ignorar erros de duplicação
            }
        }
    }
    
    /**
     * Atualiza estrutura da tabela ai_prompts para compatibilidade
     */
    private function updateAiPromptsStructure() {
        // Adicionar campos name e slug se não existirem
        $this->addColumnIfNotExists('ai_prompts', 'name', 'VARCHAR(120) NULL');
        $this->addColumnIfNotExists('ai_prompts', 'slug', 'VARCHAR(150) NULL');
        $this->addColumnIfNotExists('ai_prompts', 'description', 'TEXT NULL');
        
        // Sincronizar dados: copiar 'nome' para 'name' se necessário
        try {
            $this->db->query("
                UPDATE ai_prompts 
                SET name = nome, 
                    description = descricao,
                    slug = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(nome, ' ', '-'), 'ã', 'a'), 'ç', 'c'), 'ó', 'o'))
                WHERE name IS NULL OR name = ''
            ");
        } catch (Exception $e) {
            error_log("Erro ao sincronizar dados ai_prompts: " . $e->getMessage());
        }
    }
    
    /**
     * Sistema Universal de Migração Inteligente
     * Detecta e corrige automaticamente diferenças de schema
     */
    private function smartSchemaUpdate() {
        $schemaDefinitions = [
            'users' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'email' => 'VARCHAR(255) NOT NULL UNIQUE',
                'password' => 'VARCHAR(255) NOT NULL',
                'name' => 'VARCHAR(255) NOT NULL',
                'role' => "ENUM('admin', 'professional', 'patient') DEFAULT 'professional'",
                'status' => "ENUM('active', 'inactive', 'suspended') DEFAULT 'active'",
                'first_login' => 'BOOLEAN DEFAULT TRUE',
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                'deleted_at' => 'TIMESTAMP NULL',
                'last_login' => 'TIMESTAMP NULL'
            ],
            'ai_prompts' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'nome' => 'VARCHAR(120) NOT NULL',
                'name' => 'VARCHAR(120) NULL',
                'slug' => 'VARCHAR(150) NULL',
                'descricao' => 'TEXT NOT NULL',
                'description' => 'TEXT NULL',
                'prompt_template' => 'TEXT NOT NULL',
                'status' => "ENUM('ativo', 'inativo', 'active', 'inactive') DEFAULT 'ativo'",
                'limite_requisicoes' => 'INT NULL',
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                'updated_by' => 'INT NULL'
            ],
            'ai_requests' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'user_id' => 'INT NOT NULL',
                'prompt_id' => 'INT NULL',
                'request_data' => 'JSON NOT NULL',
                'response_data' => 'JSON NULL',
                'tokens_used' => 'INT DEFAULT 0',
                'processing_time' => 'DECIMAL(10,3)',
                'status' => "ENUM('sucesso', 'erro', 'em_cache', 'processando') DEFAULT 'processando'",
                'error_message' => 'TEXT',
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
            ],
            'settings' => [
                'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'key' => 'VARCHAR(100) NOT NULL UNIQUE',
                'value' => 'TEXT',
                'description' => 'TEXT',
                'type' => "ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string'",
                'is_public' => 'BOOLEAN DEFAULT FALSE',
                'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            ]
        ];
        
        foreach ($schemaDefinitions as $tableName => $columns) {
            $this->ensureTableStructure($tableName, $columns);
        }
    }
    
    /**
     * Garante que a tabela tenha todos os campos necessários
     */
    private function ensureTableStructure($tableName, $columns) {
        try {
            // Verificar se a tabela existe
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
            ");
            $stmt->execute([$this->database, $tableName]);
            
            if ($stmt->fetchColumn() == 0) {
                // Tabela não existe, criar
                $this->createTableFromDefinition($tableName, $columns);
                return;
            }
            
            // Tabela existe, verificar/adicionar campos
            foreach ($columns as $columnName => $definition) {
                if (!$this->columnExists($tableName, $columnName)) {
                    $this->addColumnIfNotExists($tableName, $columnName, $definition);
                }
            }
            
        } catch (Exception $e) {
            error_log("Erro ao garantir estrutura da tabela {$tableName}: " . $e->getMessage());
        }
    }
    
    /**
     * Cria tabela baseada na definição
     */
    private function createTableFromDefinition($tableName, $columns) {
        try {
            $columnDefinitions = [];
            foreach ($columns as $columnName => $definition) {
                $columnDefinitions[] = "`{$columnName}` {$definition}";
            }
            
            $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (" . 
                   implode(', ', $columnDefinitions) . 
                   ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $this->db->query($sql);
        } catch (Exception $e) {
            error_log("Erro ao criar tabela {$tableName}: " . $e->getMessage());
        }
    }
}