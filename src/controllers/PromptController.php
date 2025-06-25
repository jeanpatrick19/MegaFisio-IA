<?php
if (!defined('PUBLIC_ACCESS')) {
    die('Acesso negado');
}

require_once __DIR__ . '/BaseController.php';

class PromptController extends BaseController {
    
    public function index() {
        $this->requireRole('admin');
        
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        
        // Construir query com filtros
        $whereConditions = [];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(nome LIKE ? OR descricao LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($status)) {
            $whereConditions[] = "status = ?";
            $params[] = $status;
        }
        
        $whereClause = empty($whereConditions) ? '' : 'WHERE ' . implode(' AND ', $whereConditions);
        
        // Contar total de registros
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM ai_prompts {$whereClause}");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();
        
        // Buscar prompts paginados
        $stmt = $this->db->prepare("
            SELECT p.*, u.name as updated_by_name 
            FROM ai_prompts p
            LEFT JOIN users u ON p.updated_by = u.id
            {$whereClause}
            ORDER BY p.updated_at DESC 
            LIMIT {$perPage} OFFSET {$offset}
        ");
        $stmt->execute($params);
        $prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalPages = ceil($total / $perPage);
        
        $this->renderDashboard('admin/prompts/index', [
            'title' => 'Gestão de Prompts IA',
            'currentPage' => 'admin-prompts',
            'prompts' => $prompts,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'status' => $status
        ]);
    }
    
    public function create() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $errors = $this->validate([
                'nome' => ['required' => true, 'max' => 120],
                'descricao' => ['required' => true],
                'prompt_template' => ['required' => true],
                'status' => ['required' => true],
                'limite_requisicoes' => []
            ]);
            
            if (empty($errors)) {
                try {
                    $stmt = $this->db->prepare("
                        INSERT INTO ai_prompts (nome, descricao, prompt_template, status, limite_requisicoes, updated_by)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    $limiteRequisicoes = !empty($_POST['limite_requisicoes']) ? (int)$_POST['limite_requisicoes'] : null;
                    
                    $stmt->execute([
                        $_POST['nome'],
                        $_POST['descricao'],
                        $_POST['prompt_template'],
                        $_POST['status'],
                        $limiteRequisicoes,
                        $this->user['id']
                    ]);
                    
                    $promptId = $this->db->lastInsertId();
                    
                    // Registrar no histórico
                    $this->logPromptHistory($promptId, 'criado', null, $_POST);
                    
                    // Log da ação
                    $this->log('prompt_created', 'ai_prompts', $promptId, $_POST);
                    
                    $this->flash('success', 'Prompt criado com sucesso!');
                    $this->redirect('/admin/prompts');
                    
                } catch (Exception $e) {
                    $errors['general'] = 'Erro ao criar prompt: ' . $e->getMessage();
                }
            }
        }
        
        $this->render('admin/prompts/create', [
            'errors' => $errors ?? [],
            'old' => $_POST ?? []
        ]);
    }
    
    public function edit() {
        $this->requireRole('admin');
        
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $this->notFound();
        }
        
        $stmt = $this->db->prepare("SELECT * FROM ai_prompts WHERE id = ?");
        $stmt->execute([$id]);
        $prompt = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$prompt) {
            $this->notFound();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $errors = $this->validate([
                'nome' => ['required' => true, 'max' => 120],
                'descricao' => ['required' => true],
                'prompt_template' => ['required' => true],
                'status' => ['required' => true],
                'limite_requisicoes' => []
            ]);
            
            if (empty($errors)) {
                try {
                    $oldData = $prompt;
                    $newData = $_POST;
                    
                    $stmt = $this->db->prepare("
                        UPDATE ai_prompts 
                        SET nome = ?, descricao = ?, prompt_template = ?, status = ?, 
                            limite_requisicoes = ?, updated_by = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    
                    $limiteRequisicoes = !empty($_POST['limite_requisicoes']) ? (int)$_POST['limite_requisicoes'] : null;
                    
                    $stmt->execute([
                        $_POST['nome'],
                        $_POST['descricao'],
                        $_POST['prompt_template'],
                        $_POST['status'],
                        $limiteRequisicoes,
                        $this->user['id'],
                        $id
                    ]);
                    
                    // Registrar no histórico
                    $this->logPromptHistory($id, 'editado', $oldData, $newData);
                    
                    // Log da ação
                    $this->log('prompt_updated', 'ai_prompts', $id, [
                        'changes' => array_diff_assoc($newData, $oldData)
                    ]);
                    
                    $this->flash('success', 'Prompt atualizado com sucesso!');
                    $this->redirect('/admin/prompts');
                    
                } catch (Exception $e) {
                    $errors['general'] = 'Erro ao atualizar prompt: ' . $e->getMessage();
                }
            }
        }
        
        $this->render('admin/prompts/edit', [
            'prompt' => $prompt,
            'errors' => $errors ?? [],
            'old' => $_POST ?? $prompt
        ]);
    }
    
    public function delete() {
        $this->requireRole('admin');
        $this->validateCSRF();
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido'], 400);
        }
        
        $stmt = $this->db->prepare("SELECT * FROM ai_prompts WHERE id = ?");
        $stmt->execute([$id]);
        $prompt = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$prompt) {
            $this->json(['success' => false, 'message' => 'Prompt não encontrado'], 404);
        }
        
        try {
            // Verificar se tem requisições associadas
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM ai_requests WHERE prompt_id = ?");
            $stmt->execute([$id]);
            $requestCount = $stmt->fetchColumn();
            
            if ($requestCount > 0) {
                // Apenas inativar se tem requisições associadas
                $stmt = $this->db->prepare("UPDATE ai_prompts SET status = 'inativo', updated_by = ? WHERE id = ?");
                $stmt->execute([$this->user['id'], $id]);
                
                $message = "Prompt inativado (havia {$requestCount} requisições associadas)";
            } else {
                // Deletar completamente se não tem requisições
                $this->db->prepare("DELETE FROM ai_prompts WHERE id = ?")->execute([$id]);
                $message = "Prompt excluído completamente";
            }
            
            // Registrar no histórico
            $this->logPromptHistory($id, 'excluido', $prompt, null);
            
            // Log da ação
            $this->log('prompt_deleted', 'ai_prompts', $id, $prompt);
            
            $this->json(['success' => true, 'message' => $message]);
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Erro ao excluir prompt: ' . $e->getMessage()], 500);
        }
    }
    
    public function history() {
        $this->requireRole('admin');
        
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            $this->notFound();
        }
        
        $stmt = $this->db->prepare("SELECT nome FROM ai_prompts WHERE id = ?");
        $stmt->execute([$id]);
        $promptName = $stmt->fetchColumn();
        
        if (!$promptName) {
            $this->notFound();
        }
        
        $stmt = $this->db->prepare("
            SELECT h.*, u.name as alterado_por_name 
            FROM prompt_history h
            JOIN users u ON h.alterado_por = u.id
            WHERE h.prompt_id = ?
            ORDER BY h.alterado_em DESC
        ");
        $stmt->execute([$id]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->render('admin/prompts/history', [
            'promptId' => $id,
            'promptName' => $promptName,
            'history' => $history
        ]);
    }
    
    public function toggleStatus() {
        $this->requireRole('admin');
        $this->validateCSRF();
        
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido'], 400);
        }
        
        $stmt = $this->db->prepare("SELECT * FROM ai_prompts WHERE id = ?");
        $stmt->execute([$id]);
        $prompt = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$prompt) {
            $this->json(['success' => false, 'message' => 'Prompt não encontrado'], 404);
        }
        
        $newStatus = $prompt['status'] === 'ativo' ? 'inativo' : 'ativo';
        
        try {
            $stmt = $this->db->prepare("UPDATE ai_prompts SET status = ?, updated_by = ? WHERE id = ?");
            $stmt->execute([$newStatus, $this->user['id'], $id]);
            
            // Registrar no histórico
            $oldData = $prompt;
            $newData = array_merge($prompt, ['status' => $newStatus]);
            $this->logPromptHistory($id, 'editado', $oldData, $newData);
            
            // Log da ação
            $this->log('prompt_status_changed', 'ai_prompts', $id, [
                'old_status' => $prompt['status'],
                'new_status' => $newStatus
            ]);
            
            $this->json([
                'success' => true, 
                'message' => "Status alterado para {$newStatus}",
                'new_status' => $newStatus
            ]);
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Erro ao alterar status: ' . $e->getMessage()], 500);
        }
    }
    
    private function logPromptHistory($promptId, $acao, $oldData, $newData) {
        $stmt = $this->db->prepare("
            INSERT INTO prompt_history (
                prompt_id, nome_anterior, descricao_anterior, prompt_template_anterior, 
                status_anterior, limite_requisicoes_anterior, nome_novo, descricao_novo, 
                prompt_template_novo, status_novo, limite_requisicoes_novo, 
                acao, alterado_por
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $promptId,
            $oldData['nome'] ?? null,
            $oldData['descricao'] ?? null,
            $oldData['prompt_template'] ?? null,
            $oldData['status'] ?? null,
            $oldData['limite_requisicoes'] ?? null,
            $newData['nome'] ?? null,
            $newData['descricao'] ?? null,
            $newData['prompt_template'] ?? null,
            $newData['status'] ?? null,
            !empty($newData['limite_requisicoes']) ? (int)$newData['limite_requisicoes'] : null,
            $acao,
            $this->user['id']
        ]);
    }
}