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
            if (!$promptTemplate) {
                throw new Exception('Prompt não encontrado ou inativo.');
            }
            
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
            $this->logRequest($userId, $promptId, $inputUsuario, $promptGerado ?? '', '', 'erro', 0, $e->getMessage());
            throw $e;
        }
    }
    
    private function checkRequestLimit($userId, $promptId) {
        // Buscar limite específico do prompt
        $stmt = $this->db->prepare("SELECT limite_requisicoes FROM ai_prompts WHERE id = ? AND status = 'ativo'");
        $stmt->execute([$promptId]);
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
        $stmt = $this->db->prepare("SELECT prompt_template FROM ai_prompts WHERE id = ? AND status = 'ativo'");
        $stmt->execute([$promptId]);
        return $stmt->fetchColumn();
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
                    'content' => 'Você é um assistente especializado em fisioterapia. Forneça respostas técnicas, precisas e baseadas em evidências científicas. Use linguagem profissional mas acessível.'
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