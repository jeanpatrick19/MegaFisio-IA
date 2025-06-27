<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

/**
 * Sistema de Permissões do MegaFisio IA
 * 
 * Regras:
 * - Admins: Acesso total e irrestrito a tudo
 * - Fisioterapeutas: Permissões específicas (usar/ver) por funcionalidade
 */
class PermissionSystem {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->initializeTables();
    }
    
    /**
     * Inicializar tabelas de permissões
     */
    private function initializeTables() {
        try {
            require_once SRC_PATH . '/models/SmartMigrationManager.php';
            
            // Criar tabelas automaticamente usando SmartMigrationManager
            SmartMigrationManager::ensureSchema('system_features', []);
            SmartMigrationManager::ensureSchema('user_permissions', []);
            
            // Inserir funcionalidades básicas do sistema
            $this->insertDefaultFeatures();
        } catch (Exception $e) {
            error_log("Erro ao inicializar tabelas de permissões: " . $e->getMessage());
        }
    }
    
    /**
     * Inserir funcionalidades padrão do sistema
     */
    private function insertDefaultFeatures() {
        $features = [
            // 23 Robôs de IA Dr. IA
            ['name' => 'dr_autoritas', 'display_name' => 'Dr. Autoritas', 'description' => 'Conteúdo para Instagram', 'category' => 'ia'],
            ['name' => 'dr_acolhe', 'display_name' => 'Dr. Acolhe', 'description' => 'Atendimento via WhatsApp/Direct', 'category' => 'ia'],
            ['name' => 'dr_fechador', 'display_name' => 'Dr. Fechador', 'description' => 'Vendas de Planos Fisioterapêuticos', 'category' => 'ia'],
            ['name' => 'dr_reab', 'display_name' => 'Dr. Reab', 'description' => 'Prescrição de Exercícios Personalizados', 'category' => 'ia'],
            ['name' => 'dra_protoc', 'display_name' => 'Dra. Protoc', 'description' => 'Protocolos Terapêuticos Estruturados', 'category' => 'ia'],
            ['name' => 'dra_edu', 'display_name' => 'Dra. Edu', 'description' => 'Materiais Educativos para Pacientes', 'category' => 'ia'],
            ['name' => 'dr_cientifico', 'display_name' => 'Dr. Científico', 'description' => 'Resumos de Artigos e Evidências', 'category' => 'ia'],
            ['name' => 'dr_injetaveis', 'display_name' => 'Dr. Injetáveis', 'description' => 'Protocolos Terapêuticos com Injetáveis', 'category' => 'ia'],
            ['name' => 'dr_local', 'display_name' => 'Dr. Local', 'description' => 'Autoridade de Bairro', 'category' => 'ia'],
            ['name' => 'dr_recall', 'display_name' => 'Dr. Recall', 'description' => 'Fidelização e Retorno de Pacientes', 'category' => 'ia'],
            ['name' => 'dr_evolucio', 'display_name' => 'Dr. Evolucio', 'description' => 'Acompanhamento Clínico do Paciente', 'category' => 'ia'],
            ['name' => 'dra_legal', 'display_name' => 'Dra. Legal', 'description' => 'Termos de Consentimento Personalizados', 'category' => 'ia'],
            ['name' => 'dr_contratus', 'display_name' => 'Dr. Contratus', 'description' => 'Contratos de Prestação de Serviço', 'category' => 'ia'],
            ['name' => 'dr_imago', 'display_name' => 'Dr. Imago', 'description' => 'Autorização de Uso de Imagem', 'category' => 'ia'],
            ['name' => 'dr_imaginario', 'display_name' => 'Dr. Imaginário', 'description' => 'Análise de Exames de Imagem (RX, USG, RNM)', 'category' => 'ia'],
            ['name' => 'dr_diagnostik', 'display_name' => 'Dr. Diagnostik', 'description' => 'Mapeamento de Marcadores para Fisioterapia', 'category' => 'ia'],
            ['name' => 'dr_integralis', 'display_name' => 'Dr. Integralis', 'description' => 'Análise Funcional de Exames Laboratoriais', 'category' => 'ia'],
            ['name' => 'dr_pop', 'display_name' => 'Dr. POP', 'description' => 'Protocolos Operacionais Padrão (para pasta sanitária)', 'category' => 'ia'],
            ['name' => 'dr_vigilantis', 'display_name' => 'Dr. Vigilantis', 'description' => 'Documentação e Exigências da Vigilância Sanitária', 'category' => 'ia'],
            ['name' => 'dr_formula_oral', 'display_name' => 'Dr. Fórmula Oral', 'description' => 'Propostas Farmacológicas Via Oral para Dor', 'category' => 'ia'],
            ['name' => 'dra_contrology', 'display_name' => 'Dra. Contrology', 'description' => 'Especialista em prescrição de Pilates clássico terapêutico para reabilitação musculoesquelética', 'category' => 'ia'],
            ['name' => 'dr_posturalis', 'display_name' => 'Dr. Posturalis', 'description' => 'Especialista em RPG de Souchard e análise postural detalhada com sugestões terapêuticas', 'category' => 'ia'],
            ['name' => 'dr_peritus', 'display_name' => 'Dr. Peritus', 'description' => 'Mestre das Perícias', 'category' => 'ia'],
            
            // Sistema e Administração
            ['name' => 'user_management', 'display_name' => 'Gestão de Usuários', 'description' => 'Criar, editar e gerenciar usuários do sistema', 'category' => 'admin'],
            ['name' => 'user_permissions', 'display_name' => 'Permissões de Usuários', 'description' => 'Configurar permissões de acesso', 'category' => 'admin'],
            ['name' => 'system_logs', 'display_name' => 'Logs do Sistema', 'description' => 'Visualizar logs de auditoria', 'category' => 'admin'],
            ['name' => 'reports_export', 'display_name' => 'Exportar Relatórios', 'description' => 'Exportar dados em PDF/Excel', 'category' => 'relatorios'],
            ['name' => 'system_settings', 'display_name' => 'Configurações do Sistema', 'description' => 'Alterar configurações gerais', 'category' => 'admin'],
            ['name' => 'ai_prompts', 'display_name' => 'Gerenciar Prompts IA', 'description' => 'Configurar prompts dos assistentes', 'category' => 'admin'],
        ];
        
        foreach ($features as $feature) {
            $stmt = $this->db->prepare("
                INSERT IGNORE INTO system_features (name, display_name, description, category) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$feature['name'], $feature['display_name'], $feature['description'], $feature['category']]);
        }
    }
    
    /**
     * Verificar se usuário tem permissão
     */
    public function hasPermission($userId, $featureName, $type = 'use') {
        // Admins têm acesso total
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if ($user && $user['role'] === 'admin') {
            return true;
        }
        
        // Verificar permissão específica para fisioterapeutas
        $column = $type === 'view' ? 'can_view' : 'can_use';
        
        $stmt = $this->db->prepare("
            SELECT up.$column 
            FROM user_permissions up
            JOIN system_features sf ON up.feature_id = sf.id
            WHERE up.user_id = ? AND sf.name = ?
        ");
        $stmt->execute([$userId, $featureName]);
        $permission = $stmt->fetchColumn();
        
        return $permission == 1;
    }
    
    /**
     * Definir permissão para usuário
     */
    public function setPermission($userId, $featureName, $canUse = false, $canView = false) {
        // Não definir permissões para admins (eles têm acesso total)
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if ($user && $user['role'] === 'admin') {
            return true; // Admins não precisam de permissões específicas
        }
        
        // Buscar ID da funcionalidade
        $stmt = $this->db->prepare("SELECT id FROM system_features WHERE name = ?");
        $stmt->execute([$featureName]);
        $featureId = $stmt->fetchColumn();
        
        if (!$featureId) {
            return false;
        }
        
        // Inserir ou atualizar permissão
        $stmt = $this->db->prepare("
            INSERT INTO user_permissions (user_id, feature_id, can_use, can_view)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                can_use = VALUES(can_use),
                can_view = VALUES(can_view),
                updated_at = CURRENT_TIMESTAMP
        ");
        
        return $stmt->execute([$userId, $featureId, $canUse ? 1 : 0, $canView ? 1 : 0]);
    }
    
    /**
     * Obter todas as permissões de um usuário
     */
    public function getUserPermissions($userId) {
        $stmt = $this->db->prepare("
            SELECT 
                sf.name,
                sf.display_name,
                sf.description,
                sf.category,
                COALESCE(up.can_use, 0) as can_use,
                COALESCE(up.can_view, 0) as can_view
            FROM system_features sf
            LEFT JOIN user_permissions up ON sf.id = up.feature_id AND up.user_id = ?
            WHERE sf.is_active = 1
            ORDER BY sf.category, sf.display_name
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obter todas as funcionalidades do sistema
     */
    public function getAllFeatures() {
        $stmt = $this->db->query("
            SELECT * FROM system_features 
            WHERE is_active = 1 
            ORDER BY category, display_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Definir múltiplas permissões para um usuário
     */
    public function setMultiplePermissions($userId, $permissions) {
        $this->db->beginTransaction();
        
        try {
            foreach ($permissions as $featureName => $perms) {
                $this->setPermission(
                    $userId, 
                    $featureName, 
                    isset($perms['use']) && $perms['use'], 
                    isset($perms['view']) && $perms['view']
                );
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erro ao definir permissões: " . $e->getMessage());
            return false;
        }
    }
}