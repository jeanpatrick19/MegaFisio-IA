<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

class OpenAIService {
    private $db;
    private $apiKey;
    private $model;
    private $cacheTime;
    
    public function __construct($db) {
        $this->db = $db;
        $this->loadSettings();
    }
    
    private function loadSettings() {
        // Carregar configurações do banco
        $stmt = $this->db->query("SELECT `key`, `value` FROM settings WHERE `key` IN ('openai_api_key', 'openai_model', 'ai_cache_duration')");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Priorizar variável de ambiente sobre banco de dados
        $this->apiKey = $_ENV['OPENAI_API_KEY'] ?? $settings['openai_api_key'] ?? '';
        $this->model = $settings['openai_model'] ?? 'gpt-4o-mini';
        $this->cacheTime = (int)($settings['ai_cache_duration'] ?? 3600);
        
        if (empty($this->apiKey)) {
            throw new Exception('API Key da OpenAI não configurada. Configure via variável de ambiente OPENAI_API_KEY ou no painel administrativo.');
        }
    }
    
    public function processRequest($userId, $promptId, $inputUsuario, $variables = []) {
        try {
            // Verificar limite de requisições
            if (!$this->checkRequestLimit($userId, $promptId)) {
                throw new Exception('Limite diário de requisições atingido para este prompt.');
            }
            
            // Buscar o prompt template
            $promptTemplate = $this->getPromptTemplate($promptId);
            
            // Log para debug
            error_log("DEBUG OpenAI - Prompt ID recebido: " . $promptId);
            error_log("DEBUG OpenAI - Template encontrado: " . $promptTemplate);
            error_log("DEBUG OpenAI - Input do usuário: " . $inputUsuario);
            
            // Montar prompt final substituindo variáveis
            $promptGerado = $this->buildFinalPrompt($promptTemplate, $inputUsuario, $variables);
            
            // Verificar cache
            $cachedResponse = $this->getCachedResponse($promptGerado);
            if ($cachedResponse) {
                return $this->logRequest($userId, $promptId, $inputUsuario, $promptGerado, $cachedResponse, 'em_cache');
            }
            
            // Fazer requisição para OpenAI
            $startTime = microtime(true);
            $response = $this->callOpenAI($promptGerado);
            $processingTime = microtime(true) - $startTime;
            
            // Salvar no cache
            $this->cacheResponse($promptGerado, $response);
            
            // Log da requisição
            return $this->logRequest($userId, $promptId, $inputUsuario, $promptGerado, $response, 'sucesso', $processingTime);
            
        } catch (Exception $e) {
            // Log do erro
            error_log("ERRO OpenAI: " . $e->getMessage());
            $this->logRequest($userId, $promptId, $inputUsuario, $promptGerado ?? '', '', 'erro', 0, $e->getMessage());
            throw $e;
        }
    }
    
    private function checkRequestLimit($userId, $promptId) {
        // Buscar limite específico do prompt
        if (is_numeric($promptId)) {
            $stmt = $this->db->prepare("SELECT limite_requisicoes FROM ai_prompts WHERE id = ? AND status = 'ativo'");
            $stmt->execute([$promptId]);
        } else {
            // Buscar por nome se não for numérico
            $searchTerm = str_replace(['dr_', '_'], ['Dr. ', ' '], $promptId);
            $stmt = $this->db->prepare("SELECT limite_requisicoes FROM ai_prompts WHERE nome LIKE ? AND status = 'ativo'");
            $stmt->execute(['%' . $searchTerm . '%']);
        }
        $prompt = $stmt->fetch();
        
        if (!$prompt) {
            return false;
        }
        
        $limite = $prompt['limite_requisicoes'];
        if ($limite === null) {
            // Sem limite específico, usar padrão global
            $stmt = $this->db->query("SELECT `value` FROM settings WHERE `key` = 'ai_requests_limit_daily'");
            $limite = (int)$stmt->fetchColumn();
        }
        
        // Contar requisições do usuário hoje para este prompt
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM ai_requests 
            WHERE user_id = ? AND prompt_id = ? 
            AND DATE(created_at) = CURDATE()
            AND status IN ('sucesso', 'em_cache')
        ");
        $stmt->execute([$userId, $promptId]);
        $requestsToday = (int)$stmt->fetchColumn();
        
        return $requestsToday < $limite;
    }
    
    private function getPromptTemplate($promptId) {
        // Primeiro tenta buscar por ID numérico (que é o caso normal quando vem do frontend)
        if (is_numeric($promptId)) {
            $stmt = $this->db->prepare("SELECT prompt_template FROM ai_prompts WHERE id = ? AND status = 'ativo'");
            $stmt->execute([$promptId]);
            $template = $stmt->fetchColumn();
            
            if ($template) {
                error_log("DEBUG: Usando prompt ID $promptId com template: " . substr($template, 0, 100));
                return $template;
            }
        }
        
        // Se não é numérico, tenta buscar por nome (fallback)
        $robotName = $this->formatRobotName($promptId);
        error_log("DEBUG: Buscando prompt por nome: $robotName");
        
        $stmt = $this->db->prepare("
            SELECT prompt_template FROM ai_prompts 
            WHERE status = 'ativo' AND nome = ?
            LIMIT 1
        ");
        $stmt->execute([$robotName]);
        $template = $stmt->fetchColumn();
        
        if ($template) {
            error_log("DEBUG: Encontrado prompt por nome com template: " . substr($template, 0, 100));
            return $template;
        }
        
        // Se não encontrou, usa template padrão específico do robô
        error_log("DEBUG: Não encontrou prompt cadastrado, usando template padrão");
        return $this->createDefaultPromptForRobot($promptId, $robotName);
    }
    
    private function formatRobotName($slug) {
        // Converte slugs como "dr_acolhe" para "Dr. Acolhe"
        $name = str_replace('_', ' ', $slug);
        $name = ucwords($name);
        $name = str_replace(['Dr ', 'Dra '], ['Dr. ', 'Dra. '], $name);
        return $name;
    }
    
    private function createDefaultPromptForRobot($slug, $robotName) {
        // Templates específicos para cada robô
        $robotTemplates = [
            'dr_acolhe' => 'Você é o Dr. Acolhe, especialista em atendimento humanizado via WhatsApp e Direct. Seu objetivo é acolher o paciente, entender suas necessidades e converter a conversa em agendamento. Seja empático, profissional e persuasivo. Use emojis moderadamente. Sempre direcione para agendar uma avaliação.',
            'dr_autoritas' => 'Você é o Dr. Autoritas, especialista em criar conteúdo de autoridade para Instagram. Crie posts educativos, carrosséis e legendas que demonstrem expertise em fisioterapia. Use linguagem acessível mas técnica. Inclua CTAs para engajamento.',
            'dr_fechador' => 'Você é o Dr. Fechador, especialista em vendas de planos fisioterapêuticos. Identifique as dores do paciente, apresente soluções personalizadas e conduza ao fechamento. Seja consultivo e focado em resultados.',
            'dr_reab' => 'Você é o Dr. Reab, especialista em prescrição de exercícios personalizados. Crie programas de reabilitação detalhados com progressões, séries, repetições e orientações técnicas precisas.',
            'dra_protoc' => 'Você é a Dra. Protoc, especialista em protocolos terapêuticos estruturados. Desenvolva protocolos completos com fases, objetivos, condutas e critérios de progressão bem definidos.',
            'dra_edu' => 'Você é a Dra. Edu, especialista em criar materiais educativos para pacientes. Desenvolva conteúdo didático, ilustrativo e de fácil compreensão sobre condições e tratamentos.',
            'dr_cientifico' => 'Você é o Dr. Científico, especialista em análise de evidências científicas. Resuma artigos, interprete estudos e forneça recomendações baseadas em evidências atualizadas.',
            'dr_injetaveis' => 'Você é o Dr. Injetáveis, especialista em terapias com injetáveis. Elabore protocolos detalhados com indicações, contraindicações, técnicas de aplicação e cuidados pós-procedimento.',
            'dr_local' => 'Você é o Dr. Local, especialista em marketing local para fisioterapeutas. Crie estratégias para dominar o bairro, parcerias locais e ações de proximidade.',
            'dr_recall' => 'Você é o Dr. Recall, especialista em fidelização de pacientes. Desenvolva estratégias de retorno, programas de fidelidade e comunicação pós-alta.',
            'dr_evolucio' => 'Você é o Dr. Evolucio, especialista em acompanhamento clínico. Crie relatórios de evolução detalhados, análise de progressos e ajustes terapêuticos.',
            'dra_legal' => 'Você é a Dra. Legal, especialista em documentação legal para fisioterapia. Elabore termos de consentimento claros, completos e juridicamente adequados.',
            'dr_contratus' => 'Você é o Dr. Contratus, especialista em contratos de prestação de serviços. Crie contratos profissionais com cláusulas apropriadas para fisioterapia.',
            'dr_imago' => 'Você é o Dr. Imago, especialista em direitos de imagem. Elabore autorizações de uso de imagem adequadas para fisioterapeutas.',
            'dr_imaginario' => 'Você é o Dr. Imaginário, especialista em análise de exames de imagem. Interprete achados radiológicos relevantes para a fisioterapia de forma clara e aplicada.',
            'dr_diagnostik' => 'Você é o Dr. Diagnostik, especialista em avaliação diagnóstica fisioterapêutica. Identifique marcadores funcionais e desenvolva raciocínios clínicos estruturados.',
            'dr_integralis' => 'Você é o Dr. Integralis, especialista em análise funcional de exames laboratoriais. Interprete resultados sob a ótica da fisioterapia integrativa.',
            'dr_pop' => 'Você é o Dr. POP, especialista em Procedimentos Operacionais Padrão. Crie POPs detalhados para clínicas de fisioterapia conforme normas sanitárias.',
            'dr_vigilantis' => 'Você é o Dr. Vigilantis, especialista em adequação à vigilância sanitária. Oriente sobre documentação, estrutura e processos necessários.',
            'dr_formula_oral' => 'Você é o Dr. Fórmula Oral, especialista em propostas farmacológicas para dor. Sugira formulações magistrais apropriadas para fisioterapeutas prescreverem.',
            'dra_contrology' => 'Você é a Dra. Contrology, especialista em Pilates clássico terapêutico. Prescreva exercícios de Pilates com foco terapêutico e progressões adequadas.',
            'dr_posturalis' => 'Você é o Dr. Posturalis, especialista em RPG e análise postural. Realize avaliações posturais detalhadas e prescreva correções baseadas no método Souchard.',
            'dr_peritus' => 'Você é o Dr. Peritus, mestre em perícias fisioterapêuticas. Elabore laudos periciais técnicos e análises de nexo causal.'
        ];
        
        // Pega o template específico ou usa um genérico
        $template = $robotTemplates[$slug] ?? "Você é {$robotName}, um assistente especializado em fisioterapia. Forneça respostas profissionais, técnicas e personalizadas.";
        
        // Adiciona instrução para processar o input
        $template .= "\n\nAnalise a seguinte solicitação e forneça uma resposta completa e profissional:\n\n{input_usuario}";
        
        // Tenta criar o prompt no banco para uso futuro
        $this->createPromptInDatabase($robotName, $template, $slug);
        
        return $template;
    }
    
    private function createPromptInDatabase($nome, $template, $slug) {
        try {
            // Verifica se já não existe
            $stmt = $this->db->prepare("SELECT id FROM ai_prompts WHERE nome = ?");
            $stmt->execute([$nome]);
            if ($stmt->fetchColumn()) {
                return; // Já existe
            }
            
            // Cria o novo prompt
            $stmt = $this->db->prepare("
                INSERT INTO ai_prompts (nome, descricao, prompt_template, status, created_at, updated_at)
                VALUES (?, ?, ?, 'ativo', NOW(), NOW())
            ");
            
            $descricao = "Prompt automático para " . $nome;
            $stmt->execute([$nome, $descricao, $template]);
            
        } catch (Exception $e) {
            // Ignora erros de criação, usa o template em memória
            error_log("Não foi possível criar prompt no banco: " . $e->getMessage());
        }
    }
    
    private function buildFinalPrompt($template, $inputUsuario, $variables) {
        $prompt = $template;
        
        // Substituir variáveis fornecidas
        foreach ($variables as $key => $value) {
            $prompt = str_replace('{' . $key . '}', $value, $prompt);
        }
        
        // Adicionar input do usuário se houver placeholder genérico
        if (strpos($prompt, '{input_usuario}') !== false) {
            $prompt = str_replace('{input_usuario}', $inputUsuario, $prompt);
        } else {
            // Se não há placeholder específico, adicionar no final
            $prompt .= "\n\nInput do usuário: " . $inputUsuario;
        }
        
        return $prompt;
    }
    
    private function getCachedResponse($promptGerado) {
        $hash = md5($promptGerado);
        $stmt = $this->db->prepare("
            SELECT response_data FROM response_cache 
            WHERE request_hash = ? AND expires_at > NOW()
        ");
        $stmt->execute([$hash]);
        $cached = $stmt->fetchColumn();
        
        if ($cached) {
            // Atualizar hit count e last accessed
            $this->db->prepare("
                UPDATE response_cache 
                SET hit_count = hit_count + 1, last_accessed_at = NOW() 
                WHERE request_hash = ?
            ")->execute([$hash]);
            
            return json_decode($cached, true);
        }
        
        return null;
    }
    
    private function callOpenAI($prompt) {
        $url = 'https://api.openai.com/v1/chat/completions';
        
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Você é um fisioterapeuta especialista altamente qualificado. Suas respostas devem ser:
- Profissionais mas acolhedoras
- Baseadas em evidências científicas
- Práticas e aplicáveis
- Personalizadas para cada caso
- Estruturadas com títulos e subtítulos claros
- Use emojis moderadamente para tornar o texto mais amigável
- Sempre mantenha o foco no bem-estar e recuperação do paciente'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.7
        ];
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('Erro de conexão com OpenAI: ' . $error);
        }
        
        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            throw new Exception('Erro da API OpenAI: ' . ($errorData['error']['message'] ?? 'Erro desconhecido'));
        }
        
        $result = json_decode($response, true);
        
        if (!isset($result['choices'][0]['message']['content'])) {
            throw new Exception('Resposta inválida da OpenAI');
        }
        
        return [
            'content' => $result['choices'][0]['message']['content'],
            'tokens_used' => $result['usage']['total_tokens'] ?? 0,
            'finish_reason' => $result['choices'][0]['finish_reason'] ?? 'completed'
        ];
    }
    
    private function cacheResponse($prompt, $response) {
        $hash = md5($prompt);
        $expiresAt = date('Y-m-d H:i:s', time() + $this->cacheTime);
        
        $stmt = $this->db->prepare("
            INSERT INTO response_cache (cache_key, request_hash, response_data, expires_at)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                response_data = VALUES(response_data),
                expires_at = VALUES(expires_at),
                hit_count = 0
        ");
        
        $stmt->execute([
            substr($hash, 0, 255), // Limitar tamanho da chave
            $hash,
            json_encode($response),
            $expiresAt
        ]);
    }
    
    private function logRequest($userId, $promptId, $inputUsuario, $promptGerado, $resposta, $status, $processingTime = 0, $errorMessage = null) {
        $stmt = $this->db->prepare("
            INSERT INTO ai_requests (
                user_id, prompt_id, input_usuario, prompt_gerado, resposta_ia, 
                tokens_used, processing_time, status, error_message
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $respostaText = is_array($resposta) ? $resposta['content'] : $resposta;
        $tokensUsed = is_array($resposta) ? ($resposta['tokens_used'] ?? 0) : 0;
        
        $stmt->execute([
            $userId,
            $promptId,
            $inputUsuario,
            $promptGerado,
            $respostaText,
            $tokensUsed,
            $processingTime,
            $status,
            $errorMessage
        ]);
        
        $requestId = $this->db->lastInsertId();
        
        return [
            'id' => $requestId,
            'resposta' => $respostaText,
            'tokens_used' => $tokensUsed,
            'status' => $status,
            'processing_time' => $processingTime
        ];
    }
    
    public function getAvailablePrompts() {
        $stmt = $this->db->query("
            SELECT id, nome, descricao, limite_requisicoes 
            FROM ai_prompts 
            WHERE status = 'ativo' 
            ORDER BY nome
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserRequestsToday($userId) {
        $stmt = $this->db->prepare("
            SELECT p.nome, COUNT(*) as requests_today, p.limite_requisicoes
            FROM ai_requests r
            JOIN ai_prompts p ON r.prompt_id = p.id
            WHERE r.user_id = ? AND DATE(r.created_at) = CURDATE()
            AND r.status IN ('sucesso', 'em_cache')
            GROUP BY r.prompt_id, p.nome, p.limite_requisicoes
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function cleanupExpiredCache() {
        $this->db->query("DELETE FROM response_cache WHERE expires_at < NOW()");
        return $this->db->rowCount();
    }
}