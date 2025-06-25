<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class AIController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->render('ai/gestao-prompts', [
            'title' => 'Assistente IA para Fisioterapia',
            'currentPage' => 'ai',
            'user' => $this->user
        ], 'fisioterapia-premium');
    }
    
    public function analyze() {
        $this->requireAuth();
        $this->validateCSRF();
        
        try {
            // Processar solicitação de análise
            $promptId = $_POST['prompt_id'] ?? null;
            $patientData = [
                'nome_paciente' => $_POST['nome_paciente'] ?? '',
                'idade' => $_POST['idade'] ?? '',
                'diagnostico' => $_POST['diagnostico'] ?? '',
                'queixa_principal' => $_POST['queixa_principal'] ?? '',
                'limitacoes' => $_POST['limitacoes'] ?? '',
                'exames' => $_POST['exames'] ?? '',
                'solicitacao' => $_POST['solicitacao'] ?? ''
            ];
            
            // Registrar log da solicitação
            $this->logUserAction(
                $this->user['id'], 
                'ai_request', 
                "Solicitação de análise IA para paciente: {$patientData['nome_paciente']}"
            );
            
            // Simular resposta da IA (implementar integração real)
            $response = $this->generateAIResponse($promptId, $patientData);
            
            $this->json([
                'success' => true,
                'response' => $response,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            $this->logUserAction(
                $this->user['id'], 
                'ai_request_error', 
                "Erro na análise IA: " . $e->getMessage(),
                false
            );
            
            $this->json([
                'success' => false,
                'error' => 'Erro ao processar análise. Tente novamente.'
            ], 500);
        }
    }
    
    private function getUserTotalRequests() {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM ai_requests 
                WHERE user_id = ?
            ");
            $stmt->execute([$this->user['id']]);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getUserRequestsToday() {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM ai_requests 
                WHERE user_id = ? AND DATE(created_at) = CURDATE()
            ");
            $stmt->execute([$this->user['id']]);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function getUserFavoriteModule() {
        try {
            $stmt = $this->db->prepare("
                SELECT ap.name, COUNT(*) as usage_count
                FROM ai_requests ar
                JOIN ai_prompts ap ON ar.prompt_id = ap.id
                WHERE ar.user_id = ?
                GROUP BY ar.prompt_id
                ORDER BY usage_count DESC
                LIMIT 1
            ");
            $stmt->execute([$this->user['id']]);
            $result = $stmt->fetch();
            return $result ? $result['name'] : 'Nenhum';
        } catch (Exception $e) {
            return 'Nenhum';
        }
    }
    
    private function getUserSuccessRate() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) * 100.0 / COUNT(*) as success_rate
                FROM ai_requests 
                WHERE user_id = ?
            ");
            $stmt->execute([$this->user['id']]);
            return round($stmt->fetchColumn(), 1);
        } catch (Exception $e) {
            return 100.0;
        }
    }
    
    private function getRecentRequests($limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    ar.*,
                    ap.name as prompt_name
                FROM ai_requests ar
                LEFT JOIN ai_prompts ap ON ar.prompt_id = ap.id
                WHERE ar.user_id = ?
                ORDER BY ar.created_at DESC
                LIMIT ?
            ");
            $stmt->execute([$this->user['id'], $limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function generateAIResponse($promptId, $patientData) {
        // Simular resposta da IA - implementar integração real aqui
        $responses = [
            'ortopedica' => $this->generateOrtopedicResponse($patientData),
            'neurologica' => $this->generateNeurologicResponse($patientData),
            'respiratoria' => $this->generateRespiratoryResponse($patientData),
            'geriatrica' => $this->generateGeriatricResponse($patientData),
            'pediatrica' => $this->generatePediatricResponse($patientData)
        ];
        
        // Buscar tipo do prompt
        try {
            $stmt = $this->db->prepare("SELECT slug FROM ai_prompts WHERE id = ?");
            $stmt->execute([$promptId]);
            $slug = $stmt->fetchColumn();
            
            return $responses[$slug] ?? $this->generateGenericResponse($patientData);
        } catch (Exception $e) {
            return $this->generateGenericResponse($patientData);
        }
    }
    
    private function generateOrtopedicResponse($data) {
        return [
            'analise_clinica' => "Com base nos dados apresentados, o paciente {$data['nome_paciente']} apresenta um quadro típico de disfunção musculoesquelética que requer abordagem fisioterapêutica especializada.",
            'plano_tratamento' => [
                'Fase Inicial (1-2 semanas): Controle da dor e inflamação',
                'Fase Intermediária (3-6 semanas): Restauração da mobilidade',
                'Fase Final (7-12 semanas): Fortalecimento e retorno funcional'
            ],
            'exercicios' => [
                'Mobilização articular passiva',
                'Exercícios isométricos progressivos',
                'Alongamentos específicos',
                'Fortalecimento muscular graduado',
                'Treino proprioceptivo'
            ],
            'orientacoes' => [
                'Aplicar gelo 15-20min, 3x/dia nas primeiras 48h',
                'Evitar movimentos que causem dor intensa',
                'Manter atividades de vida diária dentro do limite tolerável',
                'Retornar para reavaliação em 1 semana'
            ],
            'prognostico' => 'Bom prognóstico com adesão ao tratamento. Melhora esperada em 4-8 semanas.',
            'contraindicacoes' => [
                'Evitar sobrecarga nas primeiras semanas',
                'Suspender exercícios se houver aumento significativo da dor',
                'Atenção para sinais de piora neurológica'
            ]
        ];
    }
    
    private function generateNeurologicResponse($data) {
        return [
            'analise_clinica' => "Paciente {$data['nome_paciente']} com comprometimento neurológico que se beneficiará de abordagem neurofuncional especializada.",
            'plano_tratamento' => [
                'Avaliação neurológica detalhada',
                'Facilitação neuromuscular proprioceptiva',
                'Treinamento de marcha e equilíbrio',
                'Estimulação sensório-motora'
            ],
            'exercicios' => [
                'Exercícios de coordenação motora',
                'Treino de transferências',
                'Atividades de vida diária adaptadas',
                'Estimulação sensorial',
                'Treinamento de marcha assistida'
            ],
            'orientacoes' => [
                'Ambiente seguro para prevenção de quedas',
                'Estímulos sensoriais constantes',
                'Paciência com o tempo de resposta',
                'Envolvimento familiar no tratamento'
            ],
            'prognostico' => 'Prognóstico depende da plasticidade neuronal e adesão. Evolução gradual esperada.',
            'contraindicacoes' => [
                'Evitar fadiga excessiva',
                'Monitorar sinais vitais durante exercícios',
                'Atenção para crises convulsivas se histórico'
            ]
        ];
    }
    
    private function generateRespiratoryResponse($data) {
        return [
            'analise_clinica' => "Paciente {$data['nome_paciente']} com comprometimento respiratório necessitando intervenção fisioterapêutica especializada.",
            'plano_tratamento' => [
                'Higiene brônquica',
                'Reexpansão pulmonar',
                'Fortalecimento muscular respiratório',
                'Treinamento aeróbico gradual'
            ],
            'exercicios' => [
                'Exercícios respiratórios diafragmáticos',
                'Técnicas de desobstrução',
                'Exercícios com incentivador respiratório',
                'Caminhada progressiva',
                'Exercícios de expansão torácica'
            ],
            'orientacoes' => [
                'Manter ambiente livre de irritantes',
                'Hidratação adequada',
                'Posicionamento correto para respiração',
                'Monitorar saturação de oxigênio'
            ],
            'prognostico' => 'Melhora da função respiratória esperada com tratamento regular.',
            'contraindicacoes' => [
                'Suspender exercícios se dispneia intensa',
                'Monitorar saturação < 90%',
                'Evitar exercícios em ambiente poluído'
            ]
        ];
    }
    
    private function generateGeriatricResponse($data) {
        return [
            'analise_clinica' => "Paciente idoso {$data['nome_paciente']} requer abordagem geriátrica específica considerando aspectos multissistêmicos.",
            'plano_tratamento' => [
                'Prevenção de quedas',
                'Manutenção da independência funcional',
                'Fortalecimento muscular adaptado',
                'Treinamento de equilíbrio'
            ],
            'exercicios' => [
                'Exercícios de equilíbrio estático e dinâmico',
                'Fortalecimento com resistência leve',
                'Caminhada assistida',
                'Exercícios funcionais',
                'Alongamentos suaves'
            ],
            'orientacoes' => [
                'Adaptar ambiente domiciliar',
                'Uso de dispositivos auxiliares se necessário',
                'Medicação em horários adequados',
                'Hidratação e nutrição adequadas'
            ],
            'prognostico' => 'Manutenção ou melhora da qualidade de vida com tratamento adequado.',
            'contraindicacoes' => [
                'Evitar exercícios de alto impacto',
                'Monitorar pressão arterial',
                'Atenção para medicações que causam tontura'
            ]
        ];
    }
    
    private function generatePediatricResponse($data) {
        return [
            'analise_clinica' => "Criança {$data['nome_paciente']} necessita abordagem pediátrica lúdica e adequada ao desenvolvimento.",
            'plano_tratamento' => [
                'Estimulação do desenvolvimento motor',
                'Atividades lúdicas terapêuticas',
                'Integração sensorial',
                'Orientação familiar'
            ],
            'exercicios' => [
                'Brincadeiras motoras dirigidas',
                'Exercícios com bolas e brinquedos',
                'Atividades de coordenação',
                'Estimulação sensorial',
                'Jogos de equilíbrio'
            ],
            'orientacoes' => [
                'Envolver a família no tratamento',
                'Manter ambiente lúdico',
                'Respeitar tempo da criança',
                'Reforço positivo constante'
            ],
            'prognostico' => 'Excelente potencial de desenvolvimento com estimulação adequada.',
            'contraindicacoes' => [
                'Não forçar atividades que causem choro',
                'Respeitar limites de atenção',
                'Evitar exercícios quando febril'
            ]
        ];
    }
    
    private function generateGenericResponse($data) {
        return [
            'analise_clinica' => "Análise personalizada para {$data['nome_paciente']} baseada nos dados fornecidos.",
            'plano_tratamento' => [
                'Avaliação funcional completa',
                'Definição de objetivos terapêuticos',
                'Programa de exercícios personalizado',
                'Reavaliações periódicas'
            ],
            'exercicios' => [
                'Exercícios específicos para a condição',
                'Progressão gradual de intensidade',
                'Atividades funcionais',
                'Exercícios domiciliares'
            ],
            'orientacoes' => [
                'Seguir prescrições médicas',
                'Manter regularidade no tratamento',
                'Comunicar alterações no quadro',
                'Aderir ao programa domiciliar'
            ],
            'prognostico' => 'Prognóstico favorável com adesão ao tratamento proposto.',
            'contraindicacoes' => [
                'Respeitar limites de dor',
                'Comunicar efeitos adversos',
                'Seguir orientações profissionais'
            ]
        ];
    }
}