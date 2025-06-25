<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class DataCleanupController extends BaseController {
    
    public function cleanFakeData() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->renderDashboard('admin/data-cleanup', [
                'title' => 'Limpeza de Dados Fictícios',
                'currentPage' => 'admin-cleanup',
                'stats' => $this->getCleanupStats()
            ]);
            return;
        }
        
        if (!isset($_POST['confirm_cleanup']) || $_POST['confirm_cleanup'] !== 'LIMPAR') {
            $this->flash('error', 'Confirmação inválida. Digite "LIMPAR" para confirmar.');
            $this->redirect('/admin/data-cleanup');
        }
        
        try {
            $this->db->beginTransaction();
            
            $cleanupResults = [];
            
            // 1. Remover prompts fictícios padrão (manter apenas se não há prompts customizados)
            $customPrompts = $this->db->query("SELECT COUNT(*) FROM ai_prompts WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)")->fetchColumn();
            
            if ($customPrompts == 0) {
                // Se não há prompts customizados, limpar todos os padrão
                $stmt = $this->db->query("DELETE FROM ai_prompts");
                $cleanupResults['ai_prompts'] = $stmt->rowCount();
            } else {
                $cleanupResults['ai_prompts'] = 0;
            }
            
            // 2. Remover requisições IA fictícias/teste
            $stmt = $this->db->query("DELETE FROM ai_requests WHERE 1=1");
            $cleanupResults['ai_requests'] = $stmt->rowCount();
            
            // 3. Limpar logs de usuários fictícios
            $stmt = $this->db->query("DELETE FROM user_logs WHERE acao LIKE '%teste%' OR acao LIKE '%demo%'");
            $cleanupResults['test_logs'] = $stmt->rowCount();
            
            // 4. Remover sessões antigas
            $stmt = $this->db->query("DELETE FROM user_sessions WHERE last_activity < UNIX_TIMESTAMP() - " . (7 * 24 * 3600)); // 7 dias
            $cleanupResults['old_sessions'] = $stmt->rowCount();
            
            // 5. Limpar notificações antigas/fictícias
            $stmt = $this->db->query("DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY) OR message LIKE '%demo%'");
            $cleanupResults['notifications'] = $stmt->rowCount();
            
            $this->db->commit();
            
            // Log da limpeza
            $this->logUserAction($this->user['id'], 'data_cleanup', 
                'Limpeza de dados fictícios executada: ' . json_encode($cleanupResults), true);
            
            $this->flash('success', 'Dados fictícios removidos com sucesso! Sistema agora reflete apenas dados reais.');
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->flash('error', 'Erro ao limpar dados: ' . $e->getMessage());
            error_log("Erro na limpeza de dados: " . $e->getMessage());
        }
        
        $this->redirect('/admin/data-cleanup');
    }
    
    public function resetSystem() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/data-cleanup');
        }
        
        if (!isset($_POST['confirm_reset']) || $_POST['confirm_reset'] !== 'RESETAR_SISTEMA') {
            $this->flash('error', 'Confirmação inválida. Digite "RESETAR_SISTEMA" para confirmar.');
            $this->redirect('/admin/data-cleanup');
        }
        
        try {
            $this->db->beginTransaction();
            
            // CUIDADO: Isso remove TODOS os dados exceto o usuário atual
            $currentUserId = $this->user['id'];
            
            // Remover todos os dados exceto o admin atual
            $this->db->query("DELETE FROM ai_requests");
            $this->db->query("DELETE FROM user_logs WHERE user_id != $currentUserId");
            $this->db->query("DELETE FROM notifications WHERE user_id != $currentUserId");
            $this->db->query("DELETE FROM user_sessions WHERE user_id != $currentUserId");
            $this->db->query("DELETE FROM users WHERE id != $currentUserId");
            
            // Resetar AI prompts para padrão
            $this->db->query("DELETE FROM ai_prompts");
            $this->createDefaultPrompts();
            
            $this->db->commit();
            
            $this->logUserAction($currentUserId, 'system_reset', 
                'Sistema resetado para estado inicial', true);
            
            $this->flash('success', 'Sistema resetado com sucesso! Agora você pode começar do zero.');
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->flash('error', 'Erro ao resetar sistema: ' . $e->getMessage());
            error_log("Erro no reset do sistema: " . $e->getMessage());
        }
        
        $this->redirect('/dashboard');
    }
    
    private function getCleanupStats() {
        $stats = [];
        
        try {
            // Contar prompts padrão
            $stmt = $this->db->query("SELECT COUNT(*) FROM ai_prompts");
            $stats['ai_prompts'] = $stmt->fetchColumn();
            
            // Contar requisições IA
            $stmt = $this->db->query("SELECT COUNT(*) FROM ai_requests");
            $stats['ai_requests'] = $stmt->fetchColumn();
            
            // Contar logs
            $stmt = $this->db->query("SELECT COUNT(*) FROM user_logs");
            $stats['user_logs'] = $stmt->fetchColumn();
            
            // Contar usuários
            $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL");
            $stats['users'] = $stmt->fetchColumn();
            
            // Contar notificações
            $stmt = $this->db->query("SELECT COUNT(*) FROM notifications");
            $stats['notifications'] = $stmt->fetchColumn();
            
        } catch (Exception $e) {
            error_log("Erro ao obter estatísticas: " . $e->getMessage());
            $stats = [
                'ai_prompts' => 0,
                'ai_requests' => 0,
                'user_logs' => 0,
                'users' => 1,
                'notifications' => 0
            ];
        }
        
        return $stats;
    }
    
    private function createDefaultPrompts() {
        $prompts = [
            [
                'name' => 'Fisioterapia Ortopédica',
                'slug' => 'ortopedica',
                'description' => 'Especialista em reabilitação musculoesquelética e traumato-ortopédica',
                'prompt_template' => 'Você é um fisioterapeuta especialista em ortopedia e traumatologia com 15 anos de experiência.

DADOS DO PACIENTE:
- Nome: {nome_paciente}
- Idade: {idade}
- Diagnóstico: {diagnostico}
- Queixa principal: {queixa_principal}
- Exames: {exames}
- Limitações: {limitacoes}

SOLICITAÇÃO:
{solicitacao}

Responda como especialista, fornecendo:
1. Análise clínica detalhada
2. Plano de tratamento específico
3. Exercícios terapêuticos com progressão
4. Orientações para o paciente
5. Prognóstico realista
6. Cuidados e contraindicações

Use linguagem técnica mas acessível. Seja preciso e baseado em evidências.',
                'status' => 'ativo',
                'limite_requisicoes' => 100
            ],
            [
                'name' => 'Fisioterapia Neurológica',
                'slug' => 'neurologica',
                'description' => 'Especialista em reabilitação neurofuncional e neuromotora',
                'prompt_template' => 'Você é um fisioterapeuta especialista em neurologia com expertise em reabilitação neurológica.

DADOS DO PACIENTE:
- Nome: {nome_paciente}
- Idade: {idade}
- Diagnóstico neurológico: {diagnostico}
- Nível de consciência: {nivel_consciencia}
- Déficits apresentados: {deficits}
- Funcionalidade atual: {funcionalidade}

SOLICITAÇÃO:
{solicitacao}

Forneça avaliação especializada incluindo:
1. Análise neurológica funcional
2. Técnicas de reabilitação específicas
3. Exercícios neuromotores progressivos
4. Estratégias de neuroplasticidade
5. Orientações para família/cuidadores
6. Adaptações necessárias
7. Prognóstico de reabilitação

Considere evidências científicas atuais em neuroreabilitação.',
                'status' => 'ativo',
                'limite_requisicoes' => 80
            ],
            [
                'name' => 'Fisioterapia Respiratória',
                'slug' => 'respiratoria',
                'description' => 'Especialista em reabilitação cardiorrespiratória e pneumofuncional',
                'prompt_template' => 'Você é um fisioterapeuta especialista em fisioterapia respiratória e cardiopulmonar.

DADOS DO PACIENTE:
- Nome: {nome_paciente}
- Idade: {idade}
- Diagnóstico pulmonar: {diagnostico}
- Função pulmonar: {funcao_pulmonar}
- Gases arteriais: {gasometria}
- Sintomas: {sintomas}

SOLICITAÇÃO:
{solicitacao}

Desenvolva plano respiratório com:
1. Avaliação funcional respiratória
2. Técnicas de higiene brônquica
3. Exercícios respiratórios específicos
4. Treinamento muscular respiratório
5. Orientações de posicionamento
6. Monitorização de parâmetros
7. Critérios de alta/progressão

Base-se em diretrizes atuais de fisioterapia respiratória.',
                'status' => 'ativo',
                'limite_requisicoes' => 60
            ],
            [
                'name' => 'Fisioterapia Geriátrica',
                'slug' => 'geriatrica',
                'description' => 'Especialista em reabilitação do idoso e gerontologia funcional',
                'prompt_template' => 'Você é um fisioterapeuta especialista em geriatria com foco em envelhecimento saudável.

DADOS DO PACIENTE:
- Nome: {nome_paciente}
- Idade: {idade}
- Comorbidades: {comorbidades}
- Medicações: {medicacoes}
- Funcionalidade: {funcionalidade}
- Risco de quedas: {risco_quedas}

SOLICITAÇÃO:
{solicitacao}

Elabore abordagem geriátrica incluindo:
1. Avaliação geriátrica ampla
2. Prevenção de quedas
3. Exercícios funcionais adaptados
4. Fortalecimento muscular seguro
5. Treino de equilíbrio e propriocepção
6. Orientações de segurança domiciliar
7. Envolvimento familiar

Considere síndrome da fragilidade e sarcopenia.',
                'status' => 'ativo',
                'limite_requisicoes' => 70
            ],
            [
                'name' => 'Fisioterapia Pediátrica',
                'slug' => 'pediatrica',
                'description' => 'Especialista em neuropediatria e desenvolvimento motor infantil',
                'prompt_template' => 'Você é um fisioterapeuta especialista em pediatria com expertise em desenvolvimento neuropsicomotor.

DADOS DA CRIANÇA:
- Nome: {nome_paciente}
- Idade: {idade}
- Desenvolvimento motor: {desenvolvimento}
- Diagnóstico: {diagnostico}
- Marcos do desenvolvimento: {marcos}
- Histórico perinatal: {historico}

SOLICITAÇÃO:
{solicitacao}

Desenvolva plano pediátrico com:
1. Avaliação do desenvolvimento neuromotor
2. Atividades lúdicas terapêuticas
3. Estimulação sensório-motora
4. Orientações aos pais/cuidadores
5. Integração com escola/creche
6. Adaptações necessárias
7. Acompanhamento evolutivo

Use abordagem lúdica e adequada à faixa etária.',
                'status' => 'ativo',
                'limite_requisicoes' => 50
            ]
        ];
        
        foreach ($prompts as $prompt) {
            $stmt = $this->db->prepare("
                INSERT INTO ai_prompts (name, slug, description, prompt_template, status, limite_requisicoes) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $prompt['name'],
                $prompt['slug'],
                $prompt['description'], 
                $prompt['prompt_template'],
                $prompt['status'],
                $prompt['limite_requisicoes']
            ]);
        }
    }
}