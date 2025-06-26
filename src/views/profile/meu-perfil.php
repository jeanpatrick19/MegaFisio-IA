<?php if (!defined('PUBLIC_ACCESS')) die('Acesso negado'); ?>

<!-- Título da Página -->
<h1 class="titulo-pagina">Meu Perfil</h1>
<p class="subtitulo-pagina-escuro">Configure suas informações pessoais, preferências e segurança</p>

<!-- Abas do Perfil -->
<div class="perfil-abas">
    <button class="aba-btn ativa" onclick="trocarAbaPerfil('pessoal')" id="abaPessoal">
        <i class="fas fa-user"></i>
        Dados Pessoais
    </button>
    <button class="aba-btn" onclick="trocarAbaPerfil('profissional')" id="abaProfissional">
        <i class="fas fa-user-md"></i>
        Dados Profissionais
    </button>
    <button class="aba-btn" onclick="trocarAbaPerfil('seguranca')" id="abaSeguranca">
        <i class="fas fa-shield-alt"></i>
        Segurança
    </button>
    <button class="aba-btn" onclick="trocarAbaPerfil('preferencias')" id="abaPreferencias">
        <i class="fas fa-cog"></i>
        Preferências
    </button>
    <button class="aba-btn" onclick="trocarAbaPerfil('atividade')" id="abaAtividade">
        <i class="fas fa-chart-line"></i>
        Atividade
    </button>
</div>

<!-- Aba Dados Pessoais -->
<div class="aba-conteudo ativa" id="conteudoPessoal">
    <div class="perfil-grid">
        <!-- Avatar e Info Básica -->
        <div class="card-fisio perfil-avatar-card">
            <div class="avatar-section">
                <div class="avatar-atual">
                    <div class="avatar-grande" id="avatarAtual">
                        <?php if (!empty($userProfile['avatar_path']) && $userProfile['avatar_type'] === 'upload'): ?>
                            <img src="<?= htmlspecialchars($userProfile['avatar_path']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php elseif (!empty($userProfile['avatar_default']) && $userProfile['avatar_type'] === 'default'): ?>
                            <?= htmlspecialchars($userProfile['avatar_default']) ?>
                        <?php else: ?>
                            <?= strtoupper(substr($userProfile['name'] ?? $user['name'] ?? 'U', 0, 2)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="avatar-status online"></div>
                </div>
                
                <div class="avatar-info">
                    <h3><?= htmlspecialchars($user['name'] ?? 'Usuário') ?></h3>
                    <p class="user-role"><?= $user['role'] === 'admin' ? 'Administrador' : 'Fisioterapeuta' ?></p>
                    <p class="user-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                </div>
                
                <div class="avatar-upload">
                    <button class="btn-fisio btn-secundario" onclick="abrirUploadAvatar()">
                        <i class="fas fa-camera"></i>
                        Alterar Foto
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Formulário de Dados Pessoais -->
        <div class="card-fisio perfil-form-card">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-edit"></i>
                    <span>Informações Pessoais</span>
                </div>
            </div>
            
            <form id="formDadosPessoais" class="perfil-form">
                <div class="form-grid-perfil">
                    <div class="form-grupo">
                        <label for="nomeCompleto">Nome Completo *</label>
                        <input type="text" id="nomeCompleto" name="nome" value="<?= htmlspecialchars($userProfile['name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($userProfile['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($userProfile['phone'] ?? '') ?>" placeholder="(11) 99999-9999">
                    </div>
                    
                    <div class="form-grupo">
                        <label for="dataNascimento">Data de Nascimento</label>
                        <input type="date" id="dataNascimento" name="data_nascimento" value="<?= $userProfile['birth_date'] ?? '' ?>">
                    </div>
                    
                    <div class="form-grupo">
                        <label for="genero">Gênero</label>
                        <select id="genero" name="genero">
                            <option value="">Selecione...</option>
                            <option value="masculino" <?= ($userProfile['gender'] ?? '') === 'masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="feminino" <?= ($userProfile['gender'] ?? '') === 'feminino' ? 'selected' : '' ?>>Feminino</option>
                            <option value="outro" <?= ($userProfile['gender'] ?? '') === 'outro' ? 'selected' : '' ?>>Outro</option>
                            <option value="nao_informar" <?= ($userProfile['gender'] ?? '') === 'nao_informar' ? 'selected' : '' ?>>Prefiro não informar</option>
                        </select>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="estadoCivil">Estado Civil</label>
                        <select id="estadoCivil" name="estado_civil">
                            <option value="">Selecione...</option>
                            <option value="solteiro" <?= ($userProfile['marital_status'] ?? '') === 'solteiro' ? 'selected' : '' ?>>Solteiro(a)</option>
                            <option value="casado" <?= ($userProfile['marital_status'] ?? '') === 'casado' ? 'selected' : '' ?>>Casado(a)</option>
                            <option value="divorciado" <?= ($userProfile['marital_status'] ?? '') === 'divorciado' ? 'selected' : '' ?>>Divorciado(a)</option>
                            <option value="viuvo" <?= ($userProfile['marital_status'] ?? '') === 'viuvo' ? 'selected' : '' ?>>Viúvo(a)</option>
                            <option value="uniao_estavel" <?= ($userProfile['marital_status'] ?? '') === 'uniao_estavel' ? 'selected' : '' ?>>União Estável</option>
                        </select>
                    </div>
                </div>
                
                <!-- Endereço -->
                <div class="form-secao">
                    <h4>Endereço</h4>
                    <div class="form-grid-perfil">
                        <div class="form-grupo">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($userProfile['cep'] ?? '') ?>" placeholder="00000-000" onblur="buscarCep()">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="endereco">Endereço</label>
                            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($userProfile['address'] ?? '') ?>" placeholder="Rua, Avenida...">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="numero">Número</label>
                            <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($userProfile['number'] ?? '') ?>" placeholder="123">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="complemento">Complemento</label>
                            <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($userProfile['complement'] ?? '') ?>" placeholder="Apto, Sala...">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="bairro">Bairro</label>
                            <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($userProfile['neighborhood'] ?? '') ?>">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($userProfile['city'] ?? '') ?>">
                        </div>
                        
                        <div class="form-grupo">
                            <label for="estado">Estado</label>
                            <select id="estado" name="estado">
                                <option value="">Selecione...</option>
                                <option value="AC" <?= ($userProfile['state'] ?? '') === 'AC' ? 'selected' : '' ?>>Acre</option>
                                <option value="AL" <?= ($userProfile['state'] ?? '') === 'AL' ? 'selected' : '' ?>>Alagoas</option>
                                <option value="AP" <?= ($userProfile['state'] ?? '') === 'AP' ? 'selected' : '' ?>>Amapá</option>
                                <option value="AM" <?= ($userProfile['state'] ?? '') === 'AM' ? 'selected' : '' ?>>Amazonas</option>
                                <option value="BA" <?= ($userProfile['state'] ?? '') === 'BA' ? 'selected' : '' ?>>Bahia</option>
                                <option value="CE" <?= ($userProfile['state'] ?? '') === 'CE' ? 'selected' : '' ?>>Ceará</option>
                                <option value="DF" <?= ($userProfile['state'] ?? '') === 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                                <option value="ES" <?= ($userProfile['state'] ?? '') === 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                                <option value="GO" <?= ($userProfile['state'] ?? '') === 'GO' ? 'selected' : '' ?>>Goiás</option>
                                <option value="MA" <?= ($userProfile['state'] ?? '') === 'MA' ? 'selected' : '' ?>>Maranhão</option>
                                <option value="MT" <?= ($userProfile['state'] ?? '') === 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                                <option value="MS" <?= ($userProfile['state'] ?? '') === 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                <option value="MG" <?= ($userProfile['state'] ?? '') === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                                <option value="PA" <?= ($userProfile['state'] ?? '') === 'PA' ? 'selected' : '' ?>>Pará</option>
                                <option value="PB" <?= ($userProfile['state'] ?? '') === 'PB' ? 'selected' : '' ?>>Paraíba</option>
                                <option value="PR" <?= ($userProfile['state'] ?? '') === 'PR' ? 'selected' : '' ?>>Paraná</option>
                                <option value="PE" <?= ($userProfile['state'] ?? '') === 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                                <option value="PI" <?= ($userProfile['state'] ?? '') === 'PI' ? 'selected' : '' ?>>Piauí</option>
                                <option value="RJ" <?= ($userProfile['state'] ?? '') === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                                <option value="RN" <?= ($userProfile['state'] ?? '') === 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                <option value="RS" <?= ($userProfile['state'] ?? '') === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                <option value="RO" <?= ($userProfile['state'] ?? '') === 'RO' ? 'selected' : '' ?>>Rondônia</option>
                                <option value="RR" <?= ($userProfile['state'] ?? '') === 'RR' ? 'selected' : '' ?>>Roraima</option>
                                <option value="SC" <?= ($userProfile['state'] ?? '') === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                                <option value="SP" <?= ($userProfile['state'] ?? '') === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                                <option value="SE" <?= ($userProfile['state'] ?? '') === 'SE' ? 'selected' : '' ?>>Sergipe</option>
                                <option value="TO" <?= ($userProfile['state'] ?? '') === 'TO' ? 'selected' : '' ?>>Tocantins</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-acoes">
                    <button type="button" class="btn-fisio btn-secundario" id="btnEditarPessoais" onclick="toggleEditMode('formDadosPessoais', 'btnEditarPessoais', 'btnSalvarPessoais')">
                        <i class="fas fa-edit"></i>
                        Editar
                    </button>
                    <button type="submit" class="btn-fisio btn-primario" id="btnSalvarPessoais" style="display: none;">
                        <i class="fas fa-save"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Aba Dados Profissionais -->
<div class="aba-conteudo" id="conteudoProfissional">
    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-stethoscope"></i>
                <span>Informações Profissionais</span>
            </div>
        </div>
        
        <form id="formDadosProfissionais" class="perfil-form">
            <div class="form-grid-perfil">
                <div class="form-grupo">
                    <label for="crefito">CREFITO</label>
                    <input type="text" id="crefito" name="crefito" placeholder="123456-F" value="<?= htmlspecialchars($userProfile['crefito'] ?? '') ?>">
                </div>
                
                <div class="form-grupo">
                    <label for="especialidade">Especialidade Principal</label>
                    <select id="especialidade" name="especialidade">
                        <option value="">Selecione...</option>
                        <option value="ortopedica" <?= ($userProfile['main_specialty'] ?? '') === 'ortopedica' ? 'selected' : '' ?>>Fisioterapia Ortopédica</option>
                        <option value="neurologica" <?= ($userProfile['main_specialty'] ?? '') === 'neurologica' ? 'selected' : '' ?>>Fisioterapia Neurológica</option>
                        <option value="respiratoria" <?= ($userProfile['main_specialty'] ?? '') === 'respiratoria' ? 'selected' : '' ?>>Fisioterapia Respiratória</option>
                        <option value="geriatrica" <?= ($userProfile['main_specialty'] ?? '') === 'geriatrica' ? 'selected' : '' ?>>Fisioterapia Geriátrica</option>
                        <option value="pediatrica" <?= ($userProfile['main_specialty'] ?? '') === 'pediatrica' ? 'selected' : '' ?>>Fisioterapia Pediátrica</option>
                        <option value="esportiva" <?= ($userProfile['main_specialty'] ?? '') === 'esportiva' ? 'selected' : '' ?>>Fisioterapia Esportiva</option>
                        <option value="dermatofuncional" <?= ($userProfile['main_specialty'] ?? '') === 'dermatofuncional' ? 'selected' : '' ?>>Dermatofuncional</option>
                        <option value="uroginecologica" <?= ($userProfile['main_specialty'] ?? '') === 'uroginecologica' ? 'selected' : '' ?>>Uroginecológica</option>
                    </select>
                </div>
                
                <div class="form-grupo">
                    <label for="formacao">Formação</label>
                    <input type="text" id="formacao" name="formacao" placeholder="Universidade onde se formou" value="<?= htmlspecialchars($userProfile['education'] ?? '') ?>">
                </div>
                
                <div class="form-grupo">
                    <label for="anoFormacao">Ano de Formação</label>
                    <input type="number" id="anoFormacao" name="ano_formacao" min="1950" max="2024" placeholder="2020" value="<?= $userProfile['graduation_year'] ?? '' ?>">
                </div>
                
                <div class="form-grupo">
                    <label for="tempoExperiencia">Tempo de Experiência</label>
                    <select id="tempoExperiencia" name="tempo_experiencia">
                        <option value="">Selecione...</option>
                        <option value="0-1" <?= ($userProfile['experience_time'] ?? '') === '0-1' ? 'selected' : '' ?>>Menos de 1 ano</option>
                        <option value="1-3" <?= ($userProfile['experience_time'] ?? '') === '1-3' ? 'selected' : '' ?>>1 a 3 anos</option>
                        <option value="3-5" <?= ($userProfile['experience_time'] ?? '') === '3-5' ? 'selected' : '' ?>>3 a 5 anos</option>
                        <option value="5-10" <?= ($userProfile['experience_time'] ?? '') === '5-10' ? 'selected' : '' ?>>5 a 10 anos</option>
                        <option value="10+" <?= ($userProfile['experience_time'] ?? '') === '10+' ? 'selected' : '' ?>>Mais de 10 anos</option>
                    </select>
                </div>
                
                <div class="form-grupo">
                    <label for="localTrabalho">Local de Trabalho</label>
                    <input type="text" id="localTrabalho" name="local_trabalho" placeholder="Clínica, Hospital..." value="<?= htmlspecialchars($userProfile['workplace'] ?? '') ?>">
                </div>
            </div>
            
            <!-- Especialidades Secundárias -->
            <div class="form-secao">
                <h4>Especialidades Secundárias</h4>
                <div class="especialidades-checkbox">
                    <label class="checkbox-especialidade">
                        <input type="checkbox" name="especialidades[]" value="ortopedica">
                        <span class="checkmark"></span>
                        Ortopédica
                    </label>
                    <label class="checkbox-especialidade">
                        <input type="checkbox" name="especialidades[]" value="neurologica">
                        <span class="checkmark"></span>
                        Neurológica
                    </label>
                    <label class="checkbox-especialidade">
                        <input type="checkbox" name="especialidades[]" value="respiratoria">
                        <span class="checkmark"></span>
                        Respiratória
                    </label>
                    <label class="checkbox-especialidade">
                        <input type="checkbox" name="especialidades[]" value="geriatrica">
                        <span class="checkmark"></span>
                        Geriátrica
                    </label>
                    <label class="checkbox-especialidade">
                        <input type="checkbox" name="especialidades[]" value="pediatrica">
                        <span class="checkmark"></span>
                        Pediátrica
                    </label>
                    <label class="checkbox-especialidade">
                        <input type="checkbox" name="especialidades[]" value="esportiva">
                        <span class="checkmark"></span>
                        Esportiva
                    </label>
                </div>
            </div>
            
            <!-- Bio Profissional -->
            <div class="form-grupo">
                <label for="bioProfissional">Biografia Profissional</label>
                <textarea id="bioProfissional" name="bio_profissional" rows="4" placeholder="Descreva sua experiência, áreas de interesse e abordagem profissional..."><?= htmlspecialchars($userProfile['professional_bio'] ?? '') ?></textarea>
            </div>
            
            <div class="form-acoes">
                <button type="button" class="btn-fisio btn-secundario" id="btnEditarProfissionais" onclick="toggleEditMode('formDadosProfissionais', 'btnEditarProfissionais', 'btnSalvarProfissionais')">
                    <i class="fas fa-edit"></i>
                    Editar
                </button>
                <button type="submit" class="btn-fisio btn-primario" id="btnSalvarProfissionais" style="display: none;">
                    <i class="fas fa-save"></i>
                    Salvar Dados Profissionais
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Aba Segurança -->
<div class="aba-conteudo" id="conteudoSeguranca">
    <div class="seguranca-grid">
        <!-- Alterar Senha -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-key"></i>
                    <span>Alterar Senha</span>
                </div>
            </div>
            
            <form id="formAlterarSenha" class="perfil-form">
                <div class="form-grupo">
                    <label for="senhaAtual">Senha Atual *</label>
                    <div class="input-password">
                        <input type="password" id="senhaAtual" name="senha_atual" required>
                        <button type="button" onclick="togglePasswordVisibility('senhaAtual')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-grupo">
                    <label for="novaSenha">Nova Senha *</label>
                    <div class="input-password">
                        <input type="password" id="novaSenha" name="nova_senha" required>
                        <button type="button" onclick="togglePasswordVisibility('novaSenha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="senha-requisitos">
                        <small>A senha deve ter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos</small>
                    </div>
                </div>
                
                <div class="form-grupo">
                    <label for="confirmarSenha">Confirmar Nova Senha *</label>
                    <div class="input-password">
                        <input type="password" id="confirmarSenha" name="confirmar_senha" required>
                        <button type="button" onclick="togglePasswordVisibility('confirmarSenha')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="botao-senha">
                    <button type="submit" class="btn-fisio btn-primario">
                        <i class="fas fa-shield-alt"></i>
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
        
        <!-- 2FA -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Autenticação de Dois Fatores</span>
                </div>
            </div>
            
            <div class="twofa-section">
                <div class="twofa-status">
                    <div class="status-indicator <?= $userProfile['two_factor_enabled'] ?? false ? 'ativo' : 'inativo' ?>">
                        <i class="fas fa-<?= $userProfile['two_factor_enabled'] ?? false ? 'shield-alt' : 'shield' ?>"></i>
                    </div>
                    <div class="status-info">
                        <h4><?= $userProfile['two_factor_enabled'] ?? false ? '2FA Ativo' : '2FA Inativo' ?></h4>
                        <p><?= $userProfile['two_factor_enabled'] ?? false ? 'Sua conta está protegida' : 'Adicione uma camada extra de segurança' ?></p>
                    </div>
                </div>
                
                <?php if ($userProfile['two_factor_enabled'] ?? false): ?>
                    <button class="btn-fisio btn-secundario" onclick="desativar2FA()">
                        <i class="fas fa-times"></i>
                        Desativar 2FA
                    </button>
                <?php else: ?>
                    <button class="btn-fisio btn-primario" onclick="ativar2FA()">
                        <i class="fas fa-qrcode"></i>
                        Ativar 2FA
                    </button>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Sessões Ativas -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-laptop"></i>
                    <span>Sessões Ativas</span>
                </div>
                <button class="btn-fisio btn-secundario btn-pequeno" onclick="carregarSessoes()">
                    <i class="fas fa-sync-alt"></i>
                    Atualizar
                </button>
            </div>
            
            <div id="sessoes-loading" class="loading-container" style="display: none;">
                <div class="spinner"></div>
                <span>Carregando sessões...</span>
            </div>
            
            <div class="sessoes-lista" id="sessoesLista">
                <!-- Sessões serão carregadas dinamicamente -->
            </div>
            
            <div class="sessoes-actions">
                <button class="btn-fisio btn-secundario btn-pequeno" onclick="revogarTodasSessoes()" id="btnRevogarTodas">
                    <i class="fas fa-sign-out-alt"></i>
                    Revogar Todas as Outras Sessões
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Aba Preferências -->
<div class="aba-conteudo" id="conteudoPreferencias">
    <div class="preferencias-grid">
        <!-- Notificações -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-bell"></i>
                    <span>Notificações</span>
                </div>
            </div>
            
            <div class="notificacoes-config">
                <div class="notif-item">
                    <span>Notificações por email</span>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="notif-item">
                    <span>Notificações do sistema</span>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="notif-item">
                    <span>Atualizações de IA</span>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
                
                <div class="notif-item">
                    <span>Newsletter mensal</span>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Idioma e Região -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-globe"></i>
                    <span>Idioma e Região</span>
                </div>
            </div>
            
            <form class="preferencias-form">
                <div class="form-grupo">
                    <label for="idioma">Idioma</label>
                    <select id="idioma" name="idioma">
                        <option value="pt-BR" selected>Português (Brasil)</option>
                        <option value="en-US">English (US)</option>
                        <option value="es-ES">Español</option>
                    </select>
                </div>
                
                <div class="form-grupo">
                    <label for="fusoHorario">Fuso Horário</label>
                    <select id="fusoHorario" name="fuso_horario">
                        <option value="America/Sao_Paulo" selected>Brasília, São Paulo (UTC-3)</option>
                        <option value="America/Manaus">Manaus, Cuiabá (UTC-4)</option>
                        <option value="America/Rio_Branco">Rio Branco, Acre (UTC-5)</option>
                        <option value="America/Noronha">Fernando de Noronha (UTC-2)</option>
                        <option value="America/Fortaleza">Fortaleza (UTC-3)</option>
                        <option value="America/Recife">Recife (UTC-3)</option>
                        <option value="America/Salvador">Salvador (UTC-3)</option>
                        <option value="America/Belem">Belém (UTC-3)</option>
                    </select>
                </div>
                
                <div class="form-grupo">
                    <label for="formatoData">Formato de Data</label>
                    <select id="formatoData" name="formato_data">
                        <option value="dd/mm/yyyy" selected>DD/MM/AAAA</option>
                        <option value="mm/dd/yyyy">MM/DD/AAAA</option>
                        <option value="yyyy-mm-dd">AAAA-MM-DD</option>
                    </select>
                </div>
            </form>
        </div>
        
        <!-- Tema e Aparência -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-palette"></i>
                    <span>Tema e Aparência</span>
                </div>
            </div>
            
            <div class="tema-config">
                <div class="tema-opcoes">
                    <div class="tema-opcao ativa" onclick="selecionarTemaUsuario('claro')">
                        <div class="tema-preview claro"></div>
                        <span>Claro</span>
                    </div>
                    
                    <div class="tema-opcao" onclick="selecionarTemaUsuario('escuro')">
                        <div class="tema-preview escuro"></div>
                        <span>Escuro</span>
                    </div>
                    
                    <div class="tema-opcao" onclick="selecionarTemaUsuario('auto')">
                        <div class="tema-preview auto"></div>
                        <span>Automático</span>
                    </div>
                </div>
                
                <div class="compactacao-config">
                    <div class="config-item">
                        <span>Interface compacta</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="config-item">
                        <span>Animações reduzidas</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-acoes">
        <button class="btn-fisio btn-primario" onclick="salvarPreferencias()">
            <i class="fas fa-save"></i>
            Salvar Preferências
        </button>
    </div>
</div>

<!-- Aba Atividade -->
<div class="aba-conteudo" id="conteudoAtividade">
    <div class="atividade-stats">
        <div class="stat-card-atividade">
            <div class="stat-icone">
                <i class="fas fa-brain"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero">156</div>
                <div class="stat-label">Análises IA</div>
            </div>
        </div>
        
        <div class="stat-card-atividade">
            <div class="stat-icone">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero">23</div>
                <div class="stat-label">Dias Ativos</div>
            </div>
        </div>
        
        <div class="stat-card-atividade">
            <div class="stat-icone">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero">45h</div>
                <div class="stat-label">Tempo de Uso</div>
            </div>
        </div>
        
        <div class="stat-card-atividade">
            <div class="stat-icone">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-info">
                <div class="stat-numero">98%</div>
                <div class="stat-label">Satisfação</div>
            </div>
        </div>
    </div>
    
    <div class="atividade-grid">
        <!-- Histórico Recente -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-history"></i>
                    <span>Atividade Recente</span>
                </div>
            </div>
            
            <div class="atividade-lista">
                <div class="atividade-item">
                    <div class="atividade-icone login">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div class="atividade-info">
                        <span>Login realizado</span>
                        <small>Há 5 minutos</small>
                    </div>
                </div>
                
                <div class="atividade-item">
                    <div class="atividade-icone ia">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="atividade-info">
                        <span>Análise IA - Fisioterapia Ortopédica</span>
                        <small>Há 1 hora</small>
                    </div>
                </div>
                
                <div class="atividade-item">
                    <div class="atividade-icone perfil">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="atividade-info">
                        <span>Perfil atualizado</span>
                        <small>Ontem</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Uso -->
        <div class="card-fisio">
            <div class="card-header-fisio">
                <div class="card-titulo">
                    <i class="fas fa-chart-area"></i>
                    <span>Uso nos Últimos 7 Dias</span>
                </div>
            </div>
            
            <div class="grafico-container">
                <canvas id="graficoUso" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Dados de Conta -->
    <div class="card-fisio">
        <div class="card-header-fisio">
            <div class="card-titulo">
                <i class="fas fa-download"></i>
                <span>Dados da Conta</span>
            </div>
        </div>
        
        <div class="dados-conta">
            <p>Você pode exportar todos os seus dados ou solicitar a exclusão da conta conforme a LGPD.</p>
            
            <div class="dados-acoes">
                <button class="btn-fisio btn-secundario" onclick="exportarDados()">
                    <i class="fas fa-download"></i>
                    Exportar Meus Dados
                </button>
                
                <button class="btn-fisio btn-secundario" onclick="abrirModalExclusao()">
                    <i class="fas fa-trash"></i>
                    Excluir Conta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Avatar -->
<div class="modal-overlay" id="modalUploadAvatar" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Alterar Foto de Perfil</h3>
            <button class="modal-close" onclick="fecharModal('modalUploadAvatar')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-form">
            <div class="upload-avatar-area">
                <div class="avatar-preview">
                    <img id="avatarPreview" src="" alt="Preview" style="display: none;">
                    <div class="avatar-placeholder">
                        <i class="fas fa-camera"></i>
                        <p>Clique para selecionar uma imagem</p>
                    </div>
                </div>
                <input type="file" id="avatarFile" accept="image/*" style="display: none;">
            </div>
            
            <div class="avatar-opcoes">
                <p>Ou escolha um avatar padrão:</p>
                <div class="avatars-padroes">
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('A')">A</div>
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('B')">B</div>
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('C')">C</div>
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('D')">D</div>
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('F')">F</div>
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('M')">M</div>
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('R')">R</div>
                    <div class="avatar-padrao" onclick="selecionarAvatarPadrao('S')">S</div>
                </div>
            </div>
            
            <div class="modal-acoes">
                <button class="btn-fisio btn-secundario" onclick="fecharModal('modalUploadAvatar')">
                    Cancelar
                </button>
                <button class="btn-fisio btn-primario" onclick="salvarAvatar()">
                    <i class="fas fa-save"></i>
                    Salvar Foto
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Abas do Perfil */
.perfil-abas {
    display: flex;
    gap: 4px;
    margin-bottom: 32px;
    border-bottom: 2px solid var(--cinza-medio);
}

.aba-btn {
    background: none;
    border: none;
    padding: 16px 24px;
    color: var(--cinza-escuro);
    font-weight: 600;
    font-size: 15px;
    border-radius: 12px 12px 0 0;
    cursor: pointer;
    transition: var(--transicao);
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 3px solid transparent;
    font-family: inherit;
}

.aba-btn:hover {
    color: var(--azul-saude);
    background: rgba(30, 58, 138, 0.05);
}

.aba-btn.ativa {
    color: var(--azul-saude);
    background: rgba(30, 58, 138, 0.05);
    border-bottom-color: var(--azul-saude);
}

.aba-conteudo {
    display: none;
    animation: fadeIn 0.3s ease-out;
}

.aba-conteudo.ativa {
    display: block;
}

/* Títulos */
.subtitulo-pagina-escuro {
    font-size: 16px;
    color: var(--cinza-escuro);
    margin-bottom: 32px;
    font-weight: 500;
}

/* Headers de Cards */
.card-header-fisio {
    padding: 0 0 16px 0;
    border-bottom: 1px solid var(--cinza-medio);
    margin-bottom: 24px;
}

.card-titulo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 700;
    color: var(--cinza-escuro);
}

.card-titulo i {
    color: var(--azul-saude);
    font-size: 20px;
}

/* Formulários */
.form-grupo {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-grupo label {
    font-weight: 600;
    color: var(--cinza-escuro);
    font-size: 14px;
}

.form-grupo input,
.form-grupo select,
.form-grupo textarea {
    padding: 12px 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    font-size: 15px;
    transition: var(--transicao);
    background: var(--branco-puro);
    color: var(--cinza-escuro);
    font-family: inherit;
}

.form-grupo input:focus,
.form-grupo select:focus,
.form-grupo textarea:focus {
    outline: none;
    border-color: var(--azul-saude);
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
}

.form-grupo textarea {
    min-height: 100px;
    resize: vertical;
}

/* Ações dos formulários */
.form-acoes {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
}

/* Switches */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--cinza-medio);
    transition: var(--transicao);
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: var(--transicao);
    border-radius: 50%;
}

.switch input:checked + .slider {
    background-color: var(--azul-saude);
}

.switch input:checked + .slider:before {
    transform: translateX(26px);
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.modal-container {
    background: var(--branco-puro);
    border-radius: 16px;
    max-width: 500px;
    width: 90%;
    max-height: 90%;
    overflow-y: auto;
    box-shadow: var(--sombra-flutuante);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid var(--cinza-medio);
}

.modal-header h3 {
    color: var(--cinza-escuro);
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--cinza-escuro);
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transicao);
}

.modal-close:hover {
    background: var(--cinza-claro);
    color: var(--cinza-escuro);
}

.modal-form {
    padding: 24px;
}

.modal-acoes {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
}

/* Grid do Perfil */
.perfil-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 24px;
}

/* Avatar Card */
.perfil-avatar-card {
    padding: 32px 24px;
    text-align: center;
}

.avatar-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.avatar-atual {
    position: relative;
}

.avatar-grande {
    width: 120px;
    height: 120px;
    background: var(--gradiente-principal);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 4px;
}

.avatar-status {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 4px solid white;
}

.avatar-status.online {
    background: var(--sucesso);
}

.avatar-info h3 {
    font-size: 24px;
    font-weight: 700;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.user-role {
    color: var(--azul-saude);
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.user-email {
    color: var(--cinza-escuro);
    font-size: 14px;
}

/* Formulários */
.perfil-form {
    padding: 0;
}

.form-grid-perfil {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.form-secao {
    margin: 32px 0 24px;
    padding-top: 24px;
    border-top: 1px solid var(--cinza-medio);
}

.form-secao h4 {
    color: var(--azul-saude);
    margin-bottom: 16px;
    font-size: 16px;
    font-weight: 700;
}

/* Checkboxes de Especialidades */
.especialidades-checkbox {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.checkbox-especialidade {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
}

.checkbox-especialidade input {
    display: none;
}

.checkbox-especialidade .checkmark {
    width: 16px;
    height: 16px;
    border: 2px solid var(--cinza-medio);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transicao);
}

.checkbox-especialidade input:checked + .checkmark {
    background: var(--azul-saude);
    border-color: var(--azul-saude);
}

.checkbox-especialidade input:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-weight: 700;
    font-size: 10px;
}

/* Segurança */
.seguranca-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
}

.input-password {
    position: relative;
    display: flex;
    align-items: center;
}

.input-password input {
    flex: 1;
    padding-right: 40px;
}

.input-password button {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    color: var(--cinza-escuro);
    cursor: pointer;
}

.senha-requisitos {
    margin-top: 8px;
}

.senha-requisitos small {
    color: var(--cinza-escuro);
    font-size: 12px;
}

/* 2FA */
.twofa-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.twofa-status {
    display: flex;
    align-items: center;
    gap: 16px;
}

.status-indicator {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.status-indicator.ativo {
    background: var(--sucesso);
}

.status-indicator.inativo {
    background: var(--cinza-medio);
}

.status-info h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--cinza-escuro);
    margin-bottom: 4px;
}

.status-info p {
    font-size: 12px;
    color: var(--cinza-escuro);
}

/* Sessões */
.sessoes-lista {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}

.sessao-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px;
    border: 1px solid var(--cinza-medio);
    border-radius: 8px;
}

.sessao-item.atual {
    border-color: var(--azul-saude);
    background: rgba(30, 58, 138, 0.05);
}

.sessao-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.sessao-device {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--cinza-escuro);
}

.sessao-detalhes {
    display: flex;
    gap: 12px;
    font-size: 12px;
    color: var(--cinza-escuro);
}

.sessao-badge.atual {
    background: var(--azul-saude);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}

.btn-pequeno {
    padding: 6px 12px;
    font-size: 12px;
}

/* Preferências */
.preferencias-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

.notificacoes-config {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.notif-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
}

.tema-config {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.tema-opcoes {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.tema-opcao {
    border: 2px solid var(--cinza-medio);
    border-radius: 8px;
    padding: 12px;
    cursor: pointer;
    transition: var(--transicao);
    text-align: center;
}

.tema-opcao:hover {
    border-color: var(--azul-saude);
}

.tema-opcao.ativa {
    border-color: var(--azul-saude);
    background: rgba(30, 58, 138, 0.05);
}

.tema-preview {
    width: 100%;
    height: 40px;
    border-radius: 4px;
    margin-bottom: 8px;
}

.tema-preview.claro {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border: 1px solid var(--cinza-medio);
}

.tema-preview.escuro {
    background: linear-gradient(135deg, #1f2937, #374151);
}

.tema-preview.auto {
    background: linear-gradient(135deg, #ffffff 50%, #1f2937 50%);
}

.compactacao-config {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.config-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

/* Atividade */
.atividade-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card-atividade {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: var(--branco-puro);
    border-radius: 16px;
    border: 1px solid var(--cinza-medio);
}

.stat-card-atividade .stat-icone {
    width: 48px;
    height: 48px;
    background: var(--gradiente-principal);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-card-atividade .stat-numero {
    font-size: 24px;
    font-weight: 800;
    color: var(--cinza-escuro);
    font-family: 'JetBrains Mono', monospace;
}

.stat-card-atividade .stat-label {
    font-size: 12px;
    color: var(--cinza-escuro);
    font-weight: 600;
}

.atividade-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

.atividade-lista {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.atividade-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    background: var(--cinza-claro);
}

.atividade-icone {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.atividade-icone.login {
    background: var(--sucesso);
}

.atividade-icone.ia {
    background: var(--azul-saude);
}

.atividade-icone.perfil {
    background: var(--dourado-premium);
}

.atividade-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.atividade-info span {
    font-weight: 500;
    color: var(--cinza-escuro);
}

.atividade-info small {
    color: var(--cinza-escuro);
    font-size: 11px;
}

.grafico-container {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--cinza-escuro);
}

.dados-conta {
    text-align: center;
    padding: 20px 0;
}

.dados-acoes {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-top: 20px;
}

/* Modal Upload Avatar */
.upload-avatar-area {
    text-align: center;
    margin-bottom: 24px;
    cursor: pointer;
}

.avatar-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 3px dashed var(--cinza-medio);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    overflow: hidden;
    position: relative;
}

.avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: var(--cinza-escuro);
}

.avatar-placeholder i {
    font-size: 32px;
}

/* Correções adicionais para todos os elementos de texto */
.perfil-dados span,
.perfil-dados p,
.form-grupo label,
.twofa-section p,
.sessao-item span,
.atividade-conteudo span,
.dados-conta span,
.detalhe-item span {
    color: var(--cinza-escuro);
}

/* Placeholders e elementos auxiliares */
.form-grupo input::placeholder,
.form-grupo textarea::placeholder {
    color: var(--cinza-escuro);
}

/* Elementos das abas */
.aba-conteudo span,
.aba-conteudo p:not(.user-email) {
    color: var(--cinza-escuro);
}

/* Espaçamento do botão alterar senha */
.botao-senha {
    margin-top: 24px;
    text-align: right;
}

.avatars-padroes {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 12px;
}

.avatar-padrao {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--gradiente-principal);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transicao);
}

.avatar-padrao:hover {
    transform: scale(1.1);
}

.avatar-padrao.selecionado {
    background: var(--verde-terapia);
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(5, 150, 105, 0.5);
}

/* Campos readonly */
.form-grupo input.readonly-field,
.form-grupo select.readonly-field,
.form-grupo textarea.readonly-field,
.form-grupo input[readonly],
.form-grupo textarea[readonly] {
    background-color: #f8f9fa !important;
    border-color: #e9ecef !important;
    color: #6c757d !important;
    cursor: not-allowed;
}

.form-grupo select.readonly-field {
    pointer-events: none;
    background-color: #f8f9fa !important;
    border-color: #e9ecef !important;
    color: #6c757d !important;
}

.checkbox-especialidade input[type="checkbox"].readonly-field + .checkmark {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    cursor: not-allowed;
}

.checkbox-especialidade input[type="checkbox"].readonly-field:checked + .checkmark {
    background-color: #6c757d;
    border-color: #6c757d;
}

/* Botões em modo edição */
.form-acoes .btn-fisio:not(:last-child) {
    margin-right: 12px;
}

/* Responsivo */
@media (max-width: 1024px) {
    .perfil-grid {
        grid-template-columns: 1fr;
    }
    
    .form-grid-perfil {
        grid-template-columns: 1fr;
    }
    
    .especialidades-checkbox {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .seguranca-grid {
        grid-template-columns: 1fr;
    }
    
    .preferencias-grid {
        grid-template-columns: 1fr;
    }
    
    .atividade-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .atividade-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .atividade-stats {
        grid-template-columns: 1fr;
    }
    
    .dados-acoes {
        flex-direction: column;
    }
    
    .botao-senha {
        text-align: center;
    }
}

/* Estilos para Sessões Ativas */
.loading-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 40px;
    color: var(--cinza-escuro);
}

.spinner {
    width: 20px;
    height: 20px;
    border: 2px solid var(--cinza-medio);
    border-top: 2px solid var(--azul-saude);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.sessoes-actions {
    padding-top: 16px;
    border-top: 1px solid var(--cinza-medio);
    margin-top: 16px;
}

.sessao-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    background: var(--cinza-claro);
    border-radius: 12px;
    margin-bottom: 12px;
    border: 1px solid var(--cinza-medio);
    transition: var(--transicao);
}

.sessao-item:hover {
    background: #f1f5f9;
    transform: translateY(-1px);
}

.sessao-item.atual {
    background: rgba(30, 58, 138, 0.05);
    border-color: var(--azul-saude);
}

.sessao-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

.sessao-device {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    color: var(--cinza-escuro);
}

.sessao-device i {
    width: 20px;
    color: var(--azul-saude);
}

.sessao-detalhes {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 0.85rem;
    color: #6b7280;
}

.sessao-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 12px;
}

.sessao-status.online {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.sessao-status.recent {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.sessao-status.offline {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.sessao-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.sessao-badge {
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sessao-badge.atual {
    background: var(--azul-saude);
    color: white;
}

.sessao-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-revogar {
    padding: 8px 16px;
    background: transparent;
    color: var(--erro);
    border: 1px solid var(--erro);
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transicao);
}

.btn-revogar:hover {
    background: var(--erro);
    color: white;
}

.sessao-empty {
    text-align: center;
    padding: 40px;
    color: var(--cinza-escuro);
}

.sessao-empty i {
    font-size: 3rem;
    color: var(--cinza-medio);
    margin-bottom: 16px;
}

/* Responsividade para sessões */
@media (max-width: 768px) {
    .sessao-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .sessao-actions {
        align-self: stretch;
        justify-content: flex-end;
    }
    
    .sessao-detalhes {
        flex-direction: column;
        gap: 4px;
    }
}

/* =================== ESTILOS PARA TEMAS =================== */

/* Tema Escuro */
body.tema-escuro {
    --fundo: #1a1a1a;
    --fundo-secundario: #2d2d2d;
    --fundo-terciario: #3a3a3a;
    --texto: #ffffff;
    --texto-secundario: #b3b3b3;
    --border: #404040;
    --cinza-claro: #4a4a4a;
    --cinza-medio: #666666;
    --cinza-escuro: #999999;
    --sombra: rgba(0, 0, 0, 0.5);
}

body.tema-escuro .card-fisio {
    background: var(--fundo-secundario);
    border-color: var(--border);
}

body.tema-escuro .fisio-input,
body.tema-escuro .fisio-select {
    background: var(--fundo-terciario);
    border-color: var(--border);
    color: var(--texto);
}

body.tema-escuro .fisio-input:focus,
body.tema-escuro .fisio-select:focus {
    border-color: var(--primario);
    background: var(--fundo);
}

/* Interface Compacta */
body.interface-compacta .card-fisio {
    padding: 12px !important;
}

body.interface-compacta .form-grupo {
    margin-bottom: 12px !important;
}

body.interface-compacta .btn-fisio {
    padding: 6px 12px !important;
    font-size: 0.85rem !important;
}

body.interface-compacta .card-header-fisio {
    margin-bottom: 12px !important;
}

body.interface-compacta .aba-conteudo {
    padding: 16px !important;
}

body.interface-compacta .preferencias-grid {
    gap: 16px !important;
}

body.interface-compacta .fisio-input,
body.interface-compacta .fisio-select {
    padding: 8px 12px !important;
    font-size: 0.9rem !important;
}

/* Animações Reduzidas */
body.animacoes-reduzidas * {
    transition: none !important;
    animation: none !important;
}

body.animacoes-reduzidas .spinner {
    animation: spin 1s linear infinite;
}

/* Alertas do Sistema */
.alerta {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 10000;
    max-width: 400px;
    animation: slideInRight 0.3s ease-out;
}

.alerta-sucesso {
    background: #28a745;
}

.alerta-erro {
    background: #dc3545;
}

.alerta i {
    font-size: 1.2rem;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Estilos específicos para tema preview */
.tema-preview {
    width: 40px;
    height: 30px;
    border-radius: 6px;
    margin-bottom: 8px;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

.tema-preview.claro {
    background: linear-gradient(135deg, #ffffff 50%, #f8f9fa 50%);
    border-color: #dee2e6;
}

.tema-preview.escuro {
    background: linear-gradient(135deg, #2d2d2d 50%, #1a1a1a 50%);
    border-color: #404040;
}

.tema-preview.auto {
    background: linear-gradient(90deg, #ffffff 50%, #2d2d2d 50%);
    border-color: #6c757d;
}

.tema-opcao.ativa .tema-preview {
    border-color: var(--primario);
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.tema-opcao {
    cursor: pointer;
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    transition: var(--transicao);
}

.tema-opcao:hover {
    background: var(--fundo-terciario);
}

.tema-opcao.ativa {
    background: var(--primario-claro);
}

</style>

<script>
// Sistema de Abas
function trocarAbaPerfil(aba) {
    // Remover classe ativa de todas as abas
    document.querySelectorAll('.aba-btn').forEach(btn => btn.classList.remove('ativa'));
    document.querySelectorAll('.aba-conteudo').forEach(content => content.classList.remove('ativa'));
    
    // Adicionar classe ativa na aba selecionada
    document.getElementById('aba' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    document.getElementById('conteudo' + aba.charAt(0).toUpperCase() + aba.slice(1)).classList.add('ativa');
    
    // Carregar sessões quando abrir aba de segurança
    if (aba === 'seguranca') {
        carregarSessoes();
    }
}

// Sistema de Edição/Salvamento
function toggleEditMode(formId, btnEditarId, btnSalvarId) {
    const form = document.getElementById(formId);
    const btnEditar = document.getElementById(btnEditarId);
    const btnSalvar = document.getElementById(btnSalvarId);
    
    const isEditing = form.dataset.editing === 'true';
    
    if (isEditing) {
        // Está editando, cancelar edição
        setFormReadonly(form, true);
        btnEditar.innerHTML = '<i class="fas fa-edit"></i> Editar';
        btnEditar.style.display = 'inline-block';
        btnSalvar.style.display = 'none';
        form.dataset.editing = 'false';
        
        // Restaurar valores originais se cancelar
        restoreOriginalValues(form);
    } else {
        // Não está editando, habilitar edição
        saveOriginalValues(form);
        setFormReadonly(form, false);
        btnEditar.innerHTML = '<i class="fas fa-times"></i> Cancelar';
        btnSalvar.style.display = 'inline-block';
        form.dataset.editing = 'true';
    }
}

function setFormReadonly(form, readonly) {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (readonly) {
            input.setAttribute('readonly', 'readonly');
            // NÃO usar disabled para não afetar o FormData, apenas visual
            input.classList.add('readonly-field');
            if (input.tagName === 'SELECT') {
                input.style.pointerEvents = 'none';
                input.setAttribute('tabindex', '-1');
            }
        } else {
            input.removeAttribute('readonly');
            input.classList.remove('readonly-field');
            if (input.tagName === 'SELECT') {
                input.style.pointerEvents = 'auto';
                input.removeAttribute('tabindex');
            }
        }
    });
    
    // Para checkboxes das especialidades - usar pointer-events em vez de disabled
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (readonly) {
            checkbox.style.pointerEvents = 'none';
            checkbox.classList.add('readonly-field');
            checkbox.setAttribute('tabindex', '-1');
        } else {
            checkbox.style.pointerEvents = 'auto';
            checkbox.classList.remove('readonly-field');
            checkbox.removeAttribute('tabindex');
        }
    });
}

function saveOriginalValues(form) {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            input.dataset.originalValue = input.checked;
        } else {
            input.dataset.originalValue = input.value;
        }
    });
}

function restoreOriginalValues(form) {
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.dataset.originalValue !== undefined) {
            if (input.type === 'checkbox') {
                input.checked = input.dataset.originalValue === 'true';
            } else {
                input.value = input.dataset.originalValue;
            }
        }
    });
}

function enableEditModeAfterSave(formId, btnEditarId, btnSalvarId) {
    const form = document.getElementById(formId);
    const btnEditar = document.getElementById(btnEditarId);
    const btnSalvar = document.getElementById(btnSalvarId);
    
    // Voltar ao modo readonly após salvar
    setFormReadonly(form, true);
    btnEditar.innerHTML = '<i class="fas fa-edit"></i> Editar';
    btnEditar.style.display = 'inline-block';
    btnSalvar.style.display = 'none';
    form.dataset.editing = 'false';
}

// Upload de Avatar
function abrirUploadAvatar() {
    // Limpar estado anterior
    avatarSelecionado = null;
    tipoAvatar = null;
    
    // Limpar preview de imagem
    const preview = document.getElementById('avatarPreview');
    const placeholder = document.querySelector('.avatar-placeholder');
    const fileInput = document.getElementById('avatarFile');
    
    preview.style.display = 'none';
    preview.src = '';
    placeholder.style.display = 'flex';
    fileInput.value = '';
    
    // Limpar seleção de avatars padrão
    document.querySelectorAll('.avatar-padrao').forEach(av => av.classList.remove('selecionado'));
    
    document.getElementById('modalUploadAvatar').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

let avatarSelecionado = null;
let tipoAvatar = null; // 'upload' ou 'default'

function selecionarAvatarPadrao(letra) {
    document.querySelectorAll('.avatar-padrao').forEach(av => av.classList.remove('selecionado'));
    event.target.classList.add('selecionado');
    avatarSelecionado = letra;
    tipoAvatar = 'default';
}

function salvarAvatar() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    
    // Detectar contexto (admin ou user)
    const isAdminContext = window.location.pathname.includes('/admin/');
    const baseUrl = isAdminContext ? '/admin/profile' : '/profile';
    
    if (tipoAvatar === 'upload') {
        // Upload de arquivo
        const fileInput = document.getElementById('avatarFile');
        if (!fileInput.files[0]) {
            mostrarAlerta('Selecione uma imagem primeiro', 'erro');
            btn.disabled = false;
            btn.innerHTML = originalText;
            return;
        }
        
        const formData = new FormData();
        formData.append('avatar', fileInput.files[0]);
        
        fetch(baseUrl + '/upload-avatar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'sucesso');
                // Atualizar avatar na tela
                const avatarElement = document.getElementById('avatarAtual');
                avatarElement.innerHTML = `<img src="${data.avatar_url}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                fecharModal('modalUploadAvatar');
            } else {
                mostrarAlerta(data.message, 'erro');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
        
    } else if (tipoAvatar === 'default' && avatarSelecionado) {
        // Avatar padrão selecionado
        const formData = new FormData();
        formData.append('avatar_letter', avatarSelecionado);
        
        fetch(baseUrl + '/select-default-avatar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'sucesso');
                // Atualizar avatar na tela
                const avatarElement = document.getElementById('avatarAtual');
                avatarElement.innerHTML = data.avatar_letter;
                fecharModal('modalUploadAvatar');
            } else {
                mostrarAlerta(data.message, 'erro');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
        
    } else {
        mostrarAlerta('Selecione uma imagem ou avatar padrão', 'erro');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Buscar CEP
async function buscarCep() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        try {
            mostrarAlerta('Buscando endereço...', 'info');
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();
            
            if (!data.erro) {
                document.getElementById('endereco').value = data.logradouro;
                document.getElementById('bairro').value = data.bairro;
                document.getElementById('cidade').value = data.localidade;
                document.getElementById('estado').value = data.uf;
                mostrarAlerta('Endereço encontrado!', 'sucesso');
            } else {
                mostrarAlerta('CEP não encontrado', 'aviso');
            }
        } catch (error) {
            mostrarAlerta('Erro ao buscar CEP', 'erro');
        }
    }
}

// Funções de Segurança
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function ativar2FA() {
    mostrarModal2FA();
}

function desativar2FA() {
    if (confirm('Tem certeza que deseja desativar a autenticação de dois fatores?\n\nSua conta ficará menos segura.')) {
        fetch('<?= BASE_URL ?>/profile/disable2FA', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?? '' ?>'
            },
            body: 'csrf_token=<?= $_SESSION['csrf_token'] ?? '' ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('2FA desativado com sucesso!', 'sucesso');
                // Recarregar a página para atualizar o status
                setTimeout(() => window.location.reload(), 1500);
            } else {
                mostrarAlerta(data.message || 'Erro ao desativar 2FA', 'erro');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro ao desativar 2FA. Tente novamente.', 'erro');
        });
    }
}

// ================ GESTÃO DE SESSÕES ================

function carregarSessoes() {
    const loading = document.getElementById('sessoes-loading');
    const lista = document.getElementById('sessoesLista');
    
    loading.style.display = 'flex';
    lista.innerHTML = '';
    
    const profilePath = window.location.pathname.includes('/admin/') ? '/admin/profile' : '/profile';
    fetch('<?= BASE_URL ?>' + profilePath + '/getSessions', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        loading.style.display = 'none';
        
        if (data.success) {
            renderizarSessoes(data.sessions);
        } else {
            lista.innerHTML = '<div class="sessao-empty"><i class="fas fa-exclamation-triangle"></i><p>Erro ao carregar sessões</p></div>';
        }
    })
    .catch(error => {
        console.error('Erro ao carregar sessões:', error);
        loading.style.display = 'none';
        lista.innerHTML = '<div class="sessao-empty"><i class="fas fa-wifi"></i><p>Erro de conexão</p></div>';
    });
}

function renderizarSessoes(sessions) {
    const lista = document.getElementById('sessoesLista');
    
    if (sessions.length === 0) {
        lista.innerHTML = '<div class="sessao-empty"><i class="fas fa-laptop"></i><p>Nenhuma sessão ativa encontrada</p></div>';
        return;
    }
    
    lista.innerHTML = sessions.map(session => {
        const statusText = session.status === 'online' ? 'Online' : 
                          session.status === 'recent' ? 'Recente' : 'Offline';
        
        return `
            <div class="sessao-item ${session.is_current ? 'atual' : ''}">
                <div class="sessao-info">
                    <div class="sessao-device">
                        <i class="${session.device_icon}"></i>
                        <span>${session.os_name} - ${session.browser_name}</span>
                    </div>
                    <div class="sessao-detalhes">
                        <span>IP: ${session.ip_address}</span>
                        <span>${session.location}</span>
                        <span data-format-date="${session.created_at}" data-include-time="true">Criado: ${session.formatted_created}</span>
                        <span>Atividade: ${session.formatted_activity}</span>
                        <div class="sessao-status ${session.status_class}">
                            <div class="sessao-status-dot"></div>
                            ${statusText}
                        </div>
                    </div>
                </div>
                
                ${session.is_current ? 
                    '<div class="sessao-badge atual">Sessão Atual</div>' :
                    `<div class="sessao-actions">
                        <button class="btn-revogar" onclick="revogarSessao('${session.id}')">
                            Revogar
                        </button>
                    </div>`
                }
            </div>
        `;
    }).join('');
    
    // Aplicar formatação de data/hora nas sessões renderizadas
    if (window.formatoDataHora) {
        window.formatoDataHora.aplicarFormatos();
    }
}

function revogarSessao(sessionId) {
    if (!confirm('Deseja revogar esta sessão? O usuário será desconectado imediatamente.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('session_id', sessionId);
    formData.append('csrf_token', '<?= $_SESSION["csrf_token"] ?? "" ?>');
    
    const profilePath = window.location.pathname.includes('/admin/') ? '/admin/profile' : '/profile';
    fetch('<?= BASE_URL ?>' + profilePath + '/revokeSession', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            carregarSessoes(); // Recarregar lista
        } else {
            mostrarAlerta(data.message, 'erro');
        }
    })
    .catch(error => {
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    });
}

function revogarTodasSessoes() {
    if (!confirm('Deseja revogar todas as outras sessões? Todos os outros dispositivos serão desconectados.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('csrf_token', '<?= $_SESSION["csrf_token"] ?? "" ?>');
    
    const profilePath = window.location.pathname.includes('/admin/') ? '/admin/profile' : '/profile';
    fetch('<?= BASE_URL ?>' + profilePath + '/revokeAllSessions', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            carregarSessoes(); // Recarregar lista
        } else {
            mostrarAlerta(data.message, 'erro');
        }
    })
    .catch(error => {
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    });
}

// Preferências
function selecionarTemaUsuario(tema) {
    document.querySelectorAll('.tema-opcao').forEach(opcao => opcao.classList.remove('ativa'));
    event.target.closest('.tema-opcao').classList.add('ativa');
    mostrarAlerta(`Tema ${tema} selecionado`, 'info');
}

function salvarPreferencias() {
    const formData = new FormData();
    
    // Capturar dados de idioma e região
    const idioma = document.getElementById('idioma').value;
    const fusoHorario = document.getElementById('fusoHorario').value;
    const formatoData = document.getElementById('formatoData').value;
    
    // Capturar tema selecionado
    const temaSelecionado = document.querySelector('.tema-opcao.ativa');
    let tema = 'claro';
    if (temaSelecionado) {
        if (temaSelecionado.onclick.toString().includes('escuro')) tema = 'escuro';
        else if (temaSelecionado.onclick.toString().includes('auto')) tema = 'auto';
    }
    
    // Capturar notificações
    const emailNotifications = document.querySelector('input[type="checkbox"]').checked;
    const systemNotifications = document.querySelectorAll('input[type="checkbox"]')[1].checked;
    const aiUpdates = document.querySelectorAll('input[type="checkbox"]')[2].checked;
    const newsletter = document.querySelectorAll('input[type="checkbox"]')[3].checked;
    
    // Capturar configurações de interface
    const interfaceCompacta = document.querySelectorAll('input[type="checkbox"]')[4].checked;
    const animacoesReduzidas = document.querySelectorAll('input[type="checkbox"]')[5].checked;
    
    // Adicionar dados ao FormData
    formData.append('idioma', idioma);
    formData.append('fuso_horario', fusoHorario);
    formData.append('formato_data', formatoData);
    formData.append('tema', tema);
    
    if (emailNotifications) formData.append('email_notifications', '1');
    if (systemNotifications) formData.append('system_notifications', '1');
    if (aiUpdates) formData.append('ai_updates', '1');
    if (newsletter) formData.append('newsletter', '1');
    if (interfaceCompacta) formData.append('interface_compacta', '1');
    if (animacoesReduzidas) formData.append('animacoes_reduzidas', '1');
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    
    fetch(profileBase + '/save-preferences', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
        } else {
            mostrarAlerta(data.message || 'Erro ao salvar preferências', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

// Atividade
function exportarDados() {
    mostrarAlerta('Preparando exportação de dados...', 'info');
    
    setTimeout(() => {
        mostrarAlerta('Dados exportados com sucesso!', 'sucesso');
    }, 2000);
}

function abrirModalExclusao() {
    if (confirm('ATENÇÃO: Esta ação é irreversível!\n\nDeseja realmente excluir sua conta?')) {
        mostrarAlerta('Solicitação de exclusão enviada', 'info');
    }
}

// Inicialização da página
document.addEventListener('DOMContentLoaded', function() {
    // Deixar formulários em modo readonly por padrão
    setFormReadonly(document.getElementById('formDadosPessoais'), true);
    setFormReadonly(document.getElementById('formDadosProfissionais'), true);
    
    // Marcar especialidades secundárias já salvas
    initializeSecondarySpecialties();
});

function initializeSecondarySpecialties() {
    <?php if (!empty($userProfile['secondary_specialties_array'])): ?>
    const savedSpecialties = <?= json_encode($userProfile['secondary_specialties_array']) ?>;
    savedSpecialties.forEach(specialty => {
        const checkbox = document.querySelector(`input[name="especialidades[]"][value="${specialty}"]`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });
    <?php endif; ?>
}

// Detectar se está no contexto admin
const isAdminContext = window.location.pathname.includes('/admin/');
const profileBase = isAdminContext ? '/admin/profile' : '/profile';

// Submissão dos formulários
document.getElementById('formDadosPessoais').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Verificar se está em modo de edição
    if (this.dataset.editing !== 'true') {
        mostrarAlerta('Clique em Editar primeiro para modificar os dados', 'aviso');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Debug: verificar se os dados estão sendo coletados
    console.log('Dados do formulário:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    
    fetch(profileBase + '/save-personal-data', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            // Voltar ao modo readonly após salvar
            enableEditModeAfterSave('formDadosPessoais', 'btnEditarPessoais', 'btnSalvarPessoais');
        } else {
            mostrarAlerta(data.message || 'Erro ao salvar dados', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

document.getElementById('formDadosProfissionais').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Verificar se está em modo de edição
    if (this.dataset.editing !== 'true') {
        mostrarAlerta('Clique em Editar primeiro para modificar os dados', 'aviso');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    
    fetch(profileBase + '/save-professional-data', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            // Voltar ao modo readonly após salvar
            enableEditModeAfterSave('formDadosProfissionais', 'btnEditarProfissionais', 'btnSalvarProfissionais');
        } else {
            mostrarAlerta(data.message || 'Erro ao salvar dados', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

document.getElementById('formAlterarSenha').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const senhaAtual = document.getElementById('senhaAtual').value;
    const novaSenha = document.getElementById('novaSenha').value;
    const confirmarSenha = document.getElementById('confirmarSenha').value;
    
    if (novaSenha !== confirmarSenha) {
        mostrarAlerta('As senhas não coincidem', 'erro');
        return;
    }
    
    if (novaSenha.length < 8) {
        mostrarAlerta('A senha deve ter pelo menos 8 caracteres', 'erro');
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Alterando...';
    
    fetch(profileBase + '/change-password', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'sucesso');
            this.reset();
        } else {
            mostrarAlerta(data.message || 'Erro ao alterar senha', 'erro');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro de conexão. Tente novamente.', 'erro');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Upload de arquivo
document.addEventListener('click', function(e) {
    if (e.target.closest('.upload-avatar-area')) {
        document.getElementById('avatarFile').click();
    }
});

document.getElementById('avatarFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validar tipo de arquivo
        if (!file.type.startsWith('image/')) {
            mostrarAlerta('Selecione apenas arquivos de imagem', 'erro');
            this.value = '';
            return;
        }
        
        // Validar tamanho (2MB)
        if (file.size > 2 * 1024 * 1024) {
            mostrarAlerta('Arquivo muito grande. Máximo 2MB.', 'erro');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.querySelector('.avatar-placeholder');
            
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            
            // Limpar seleção de avatar padrão
            document.querySelectorAll('.avatar-padrao').forEach(av => av.classList.remove('selecionado'));
            avatarSelecionado = null;
            tipoAvatar = 'upload';
        };
        reader.readAsDataURL(file);
    }
});

// Máscara para CEP
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 5) {
        value = value.substring(0, 5) + '-' + value.substring(5, 8);
    }
    e.target.value = value;
});

// Máscara para telefone
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
        value = value.replace(/(\d)(\d{4})$/, '$1-$2');
    }
    e.target.value = value;
});

// ================ MODAL 2FA ================

function mostrarModal2FA() {
    // Criar overlay simples
    const overlay = document.createElement('div');
    overlay.id = 'modal2fa';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
        box-sizing: border-box;
    `;
    
    // Criar conteúdo do modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    `;
    
    modal.innerHTML = `
        <div style="padding: 20px; border-bottom: 1px solid #eee; background: #1e3a8a; color: white; border-radius: 12px 12px 0 0;">
            <h3 style="margin: 0; display: flex; align-items: center; justify-content: space-between;">
                🔐 Ativar 2FA
                <button onclick="fecharModal2FA()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px;">&times;</button>
            </h3>
        </div>
        <div style="padding: 30px; text-align: center;">
            <h4>Configurar Autenticação de Dois Fatores</h4>
            <p>Este processo irá configurar a autenticação de dois fatores para sua conta.</p>
            <div style="margin: 20px 0;">
                <p><strong>Você precisará de:</strong></p>
                <ul style="text-align: left; display: inline-block;">
                    <li>Google Authenticator, Authy ou app similar</li>
                    <li>Acesso ao seu smartphone</li>
                </ul>
            </div>
            <div style="margin-top: 30px;">
                <button onclick="iniciarConfiguração2FA()" style="background: #1e3a8a; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; margin-right: 10px;">Continuar</button>
                <button onclick="fecharModal2FA()" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer;">Cancelar</button>
            </div>
        </div>
    `;
    
    // Fechar ao clicar no overlay
    overlay.onclick = function(e) {
        if (e.target === overlay) {
            fecharModal2FA();
        }
    };
    
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
}

// ================ FUNÇÕES DO MODAL 2FA ================

function fecharModal2FA() {
    const modal = document.getElementById('modal2fa');
    if (modal) {
        modal.remove();
    }
    document.body.style.overflow = '';
}

function iniciarConfiguração2FA() {
    // Atualizar modal para mostrar QR Code
    const modal = document.querySelector('#modal2fa div:last-child');
    modal.innerHTML = `
        <div style="padding: 30px;">
            <h4>Escaneie o QR Code</h4>
            <p>Use seu app autenticador para escanear o código:</p>
            
            <div style="text-align: center; margin: 20px 0;">
                <div id="qrLoading" style="padding: 40px;">
                    <div style="border: 4px solid #f3f3f3; border-top: 4px solid #1e3a8a; border-radius: 50%; width: 40px; height: 40px; animation: spin 2s linear infinite; margin: 0 auto;"></div>
                    <p style="margin-top: 15px;">Gerando QR Code...</p>
                </div>
                <div id="qrContent" style="display: none;">
                    <img id="qrImage" src="" alt="QR Code" style="max-width: 200px; border: 1px solid #ddd; padding: 10px;" />
                    <div style="margin-top: 15px;">
                        <p><strong>Ou digite manualmente:</strong></p>
                        <div style="background: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace; word-break: break-all; margin: 10px 0;">
                            <span id="manualSecret"></span>
                            <button onclick="copiarSegredo()" style="margin-left: 10px; padding: 5px 10px; border: none; background: #1e3a8a; color: white; border-radius: 3px; cursor: pointer;">📋</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="verificacaoStep" style="display: none; margin-top: 20px;">
                <h5>Confirme o código:</h5>
                <input type="text" id="codigoVerificacao" placeholder="000000" maxlength="6" style="width: 120px; padding: 10px; text-align: center; font-size: 18px; border: 2px solid #ddd; border-radius: 5px; margin: 10px;" />
                <div style="margin-top: 15px;">
                    <button onclick="confirmar2FA()" id="btnConfirmar" style="background: #1e3a8a; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; margin-right: 10px;">Ativar 2FA</button>
                    <button onclick="voltarParaQR()" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer;">Voltar</button>
                </div>
            </div>
            
            <div style="margin-top: 30px;">
                <button onclick="mostrarVerificacao()" id="btnContinuar" style="background: #1e3a8a; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; margin-right: 10px;">Continuar</button>
                <button onclick="fecharModal2FA()" style="background: #6b7280; color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer;">Cancelar</button>
            </div>
        </div>
        
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    `;
    
    // Gerar QR Code
    gerarQRCode();
}

function handleEscKey(e) {
    if (e.key === 'Escape') {
        fecharModal2FA();
    }
}

function mostrarVerificacao() {
    document.getElementById('verificacaoStep').style.display = 'block';
    document.getElementById('btnContinuar').style.display = 'none';
}

function voltarParaQR() {
    document.getElementById('verificacaoStep').style.display = 'none';
    document.getElementById('btnContinuar').style.display = 'inline-block';
}

let currentSecret = '';
let currentBackupCodes = [];

function gerarQRCode() {
    const qrLoading = document.getElementById('qrLoading');
    const qrContent = document.getElementById('qrContent');
    
    qrLoading.style.display = 'block';
    qrContent.style.display = 'none';
    
    fetch('<?= BASE_URL ?>/profile/enable2FA', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Response text:', text);
        try {
            const data = JSON.parse(text);
            if (data.success) {
                currentSecret = data.secret;
                
                document.getElementById('qrImage').src = data.qrCodeURL;
                document.getElementById('manualSecret').textContent = data.secret;
                
                qrLoading.style.display = 'none';
                qrContent.style.display = 'block';
            } else {
                alert('Erro: ' + (data.message || 'Erro desconhecido'));
                fecharModal2FA();
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            alert('Erro de resposta do servidor: ' + text);
            fecharModal2FA();
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Erro de rede: ' + error.message);
        fecharModal2FA();
    });
}

function copiarSegredo() {
    const secret = document.getElementById('manualSecret').textContent;
    navigator.clipboard.writeText(secret).then(() => {
        const btn = event.target;
        const originalText = btn.textContent;
        btn.textContent = '✓';
        setTimeout(() => {
            btn.textContent = originalText;
        }, 2000);
    });
}

function confirmar2FA() {
    const codigo = document.getElementById('codigoVerificacao').value.trim();
    const btnConfirmar = document.getElementById('btnConfirmar');
    
    if (!codigo || codigo.length !== 6 || !/^\d{6}$/.test(codigo)) {
        alert('Digite um código de 6 dígitos válido');
        return;
    }
    
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<div class="spinner"></div> Verificando...';
    
    fetch('<?= BASE_URL ?>/profile/confirm2FA', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'code': codigo,
            'csrf_token': '<?= $_SESSION["csrf_token"] ?? "" ?>'
        })
    })
    .then(response => {
        console.log('Confirm response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Confirm response text:', text);
        try {
            const data = JSON.parse(text);
            if (data.success) {
                alert('2FA ativado com sucesso! Códigos de backup: ' + data.backupCodes.join(', '));
                fecharModal2FA();
                // Recarregar página para atualizar status
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                alert('Erro na verificação: ' + (data.message || 'Código incorreto'));
            }
        } catch (e) {
            console.error('Error parsing confirm JSON:', e);
            alert('Erro de resposta na verificação: ' + text);
        }
    })
    .catch(error => {
        console.error('Confirm fetch error:', error);
        alert('Erro de rede na verificação: ' + error.message);
    })
    .finally(() => {
        btnConfirmar.disabled = false;
        btnConfirmar.innerHTML = 'Ativar 2FA';
    });
}

function mostrarBackupCodes() {
    const grid = document.getElementById('backupCodesGrid');
    grid.innerHTML = currentBackupCodes.map(code => 
        `<div class="backup-code-item">${code}</div>`
    ).join('');
}

function copiarBackupCodes() {
    const text = currentBackupCodes.join('\n');
    navigator.clipboard.writeText(text).then(() => {
        mostrarAlerta('Códigos copiados para a área de transferência!', 'sucesso');
    });
}

function finalizarModal2FA() {
    fecharModal2FA();
    mostrarAlerta('2FA ativado com sucesso! Sua conta agora está mais segura.', 'sucesso');
    
    // Recarregar página para atualizar status
    setTimeout(() => {
        location.reload();
    }, 2000);
}

// Formatação automática do código de verificação
document.addEventListener('click', function(e) {
    if (e.target.id === 'codigoVerificacao') {
        e.target.addEventListener('input', function(event) {
            event.target.value = event.target.value.replace(/\D/g, '');
        });
    }
});

function baixarBackupCodes() {
    const text = `CÓDIGOS DE BACKUP - MEGAFISIO IA
Gerados em: ${new Date().toLocaleString('pt-BR')}

${currentBackupCodes.join('\n')}

IMPORTANTE:
- Guarde estes códigos em local seguro
- Cada código só pode ser usado uma vez
- Use-os se perder acesso ao seu app autenticador`;
    
    const blob = new Blob([text], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `backup-codes-megafisio-${new Date().toISOString().split('T')[0]}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function copiarBackupCodes() {
    const text = currentBackupCodes.join('\n');
    navigator.clipboard.writeText(text).then(() => {
        mostrarAlerta('Códigos copiados para a área de transferência!', 'sucesso');
    });
}

function finalizarModal2FA() {
    fecharModal2FA();
    mostrarAlerta('2FA ativado com sucesso! Sua conta agora está mais segura.', 'sucesso');
    
    // Recarregar página para atualizar status
    setTimeout(() => {
        location.reload();
    }, 2000);
}

// Formatação automática do código de verificação
document.addEventListener('click', function(e) {
    if (e.target.id === 'codigoVerificacao') {
        e.target.addEventListener('input', function(event) {
            event.target.value = event.target.value.replace(/\D/g, '');
        });
    }
});

// =================== SISTEMA DE PREFERÊNCIAS ===================

// Variáveis globais para preferências
let preferencasOriginais = {};
let temaAtual = '<?= $userProfile['theme'] ?? 'claro' ?>';
let idiomaAtual = '<?= $userProfile['language'] ?? 'pt-BR' ?>';

// Sistema de traduções simples
const traducoes = {
    'pt-BR': {
        'Notificações': 'Notificações',
        'Notificações por email': 'Notificações por email',
        'Notificações do sistema': 'Notificações do sistema',
        'Atualizações de IA': 'Atualizações de IA',
        'Newsletter mensal': 'Newsletter mensal',
        'Idioma e Região': 'Idioma e Região',
        'Idioma': 'Idioma',
        'Fuso Horário': 'Fuso Horário',
        'Formato de Data': 'Formato de Data',
        'Tema e Aparência': 'Tema e Aparência',
        'Claro': 'Claro',
        'Escuro': 'Escuro',
        'Automático': 'Automático',
        'Interface compacta': 'Interface compacta',
        'Animações reduzidas': 'Animações reduzidas',
        'Salvar Preferências': 'Salvar Preferências',
        'Preferências salvas com sucesso!': 'Preferências salvas com sucesso!'
    },
    'en-US': {
        'Notificações': 'Notifications',
        'Notificações por email': 'Email notifications',
        'Notificações do sistema': 'System notifications',
        'Atualizações de IA': 'AI updates',
        'Newsletter mensal': 'Monthly newsletter',
        'Idioma e Região': 'Language & Region',
        'Idioma': 'Language',
        'Fuso Horário': 'Time Zone',
        'Formato de Data': 'Date Format',
        'Tema e Aparência': 'Theme & Appearance',
        'Claro': 'Light',
        'Escuro': 'Dark',
        'Automático': 'Auto',
        'Interface compacta': 'Compact interface',
        'Animações reduzidas': 'Reduced animations',
        'Salvar Preferências': 'Save Preferences',
        'Preferências salvas com sucesso!': 'Preferences saved successfully!'
    },
    'es-ES': {
        'Notificações': 'Notificaciones',
        'Notificações por email': 'Notificaciones por email',
        'Notificações do sistema': 'Notificaciones del sistema',
        'Atualizações de IA': 'Actualizaciones de IA',
        'Newsletter mensal': 'Newsletter mensual',
        'Idioma e Região': 'Idioma y Región',
        'Idioma': 'Idioma',
        'Fuso Horário': 'Zona Horaria',
        'Formato de Data': 'Formato de Fecha',
        'Tema e Aparência': 'Tema y Apariencia',
        'Claro': 'Claro',
        'Escuro': 'Oscuro',
        'Automático': 'Automático',
        'Interface compacta': 'Interfaz compacta',
        'Animações reduzidas': 'Animaciones reducidas',
        'Salvar Preferências': 'Guardar Preferencias',
        'Preferências salvas com sucesso!': '¡Preferencias guardadas exitosamente!'
    }
};

// Inicializar preferências ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    carregarPreferenciasUsuario();
    
    // Adicionar listener para mudança de idioma em tempo real
    const selectIdioma = document.getElementById('idioma');
    if (selectIdioma) {
        selectIdioma.addEventListener('change', function() {
            aplicarIdioma(this.value);
        });
    }
    
    // Adicionar listeners para formato de data e fuso horário
    const selectFormato = document.getElementById('formatoData');
    if (selectFormato) {
        selectFormato.addEventListener('change', function() {
            aplicarFormatoData(this.value);
        });
    }
    
    const selectFuso = document.getElementById('fusoHorario');
    if (selectFuso) {
        selectFuso.addEventListener('change', function() {
            aplicarFusoHorario(this.value);
        });
    }
});

function carregarPreferenciasUsuario() {
    // Carregar dados das preferências do usuário logado
    const preferenciasUsuario = {
        email_notifications: <?= json_encode((bool)($userProfile['email_notifications'] ?? true)) ?>,
        system_notifications: <?= json_encode((bool)($userProfile['system_notifications'] ?? true)) ?>,
        ai_updates: <?= json_encode((bool)($userProfile['ai_updates'] ?? false)) ?>,
        newsletter: <?= json_encode((bool)($userProfile['newsletter'] ?? false)) ?>,
        language: '<?= $userProfile['language'] ?? 'pt-BR' ?>',
        timezone: '<?= $userProfile['timezone'] ?? 'America/Sao_Paulo' ?>',
        date_format: '<?= $userProfile['date_format'] ?? 'dd/mm/yyyy' ?>',
        theme: '<?= $userProfile['theme'] ?? 'claro' ?>',
        compact_interface: <?= json_encode((bool)($userProfile['compact_interface'] ?? false)) ?>,
        reduced_animations: <?= json_encode((bool)($userProfile['reduced_animations'] ?? false)) ?>
    };
    
    // Aplicar valores nos elementos da interface
    aplicarPreferenciasInterface(preferenciasUsuario);
    
    // Salvar como valores originais
    preferencasOriginais = {...preferenciasUsuario};
    
    // Aplicar tema atual
    aplicarTema(preferenciasUsuario.theme);
    
    // Aplicar configurações de interface
    aplicarConfiguracaoInterface(preferenciasUsuario);
    
    // Aplicar idioma se diferente do padrão
    if (preferenciasUsuario.language !== 'pt-BR') {
        aplicarIdioma(preferenciasUsuario.language);
    }
}

function aplicarPreferenciasInterface(prefs) {
    // Notificações
    document.querySelector('.notif-item:nth-child(1) input[type="checkbox"]').checked = prefs.email_notifications;
    document.querySelector('.notif-item:nth-child(2) input[type="checkbox"]').checked = prefs.system_notifications;
    document.querySelector('.notif-item:nth-child(3) input[type="checkbox"]').checked = prefs.ai_updates;
    document.querySelector('.notif-item:nth-child(4) input[type="checkbox"]').checked = prefs.newsletter;
    
    // Idioma e região
    document.getElementById('idioma').value = prefs.language;
    document.getElementById('fusoHorario').value = prefs.timezone;
    document.getElementById('formatoData').value = prefs.date_format;
    
    // Tema e aparência
    document.querySelectorAll('.tema-opcao').forEach(opcao => opcao.classList.remove('ativa'));
    document.querySelector(`.tema-opcao[onclick*="${prefs.theme}"]`).classList.add('ativa');
    
    document.querySelector('.config-item:nth-child(1) input[type="checkbox"]').checked = prefs.compact_interface;
    document.querySelector('.config-item:nth-child(2) input[type="checkbox"]').checked = prefs.reduced_animations;
}

function selecionarTemaUsuario(tema) {
    // Remover classe ativa de todas as opções
    document.querySelectorAll('.tema-opcao').forEach(opcao => opcao.classList.remove('ativa'));
    
    // Adicionar classe ativa na opção selecionada
    const opcaoSelecionada = document.querySelector(`.tema-opcao[onclick*="${tema}"]`);
    if (opcaoSelecionada) {
        opcaoSelecionada.classList.add('ativa');
    }
    
    // Atualizar tema atual
    temaAtual = tema;
    
    // Aplicar tema imediatamente para preview
    aplicarTema(tema);
}

function aplicarTema(tema) {
    const body = document.body;
    
    // Remover classes de tema existentes
    body.classList.remove('tema-claro', 'tema-escuro', 'tema-auto');
    
    // Aplicar novo tema
    switch(tema) {
        case 'escuro':
            body.classList.add('tema-escuro');
            break;
        case 'auto':
            // Detectar preferência do sistema
            const temaSistema = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'escuro' : 'claro';
            body.classList.add(`tema-${temaSistema}`);
            break;
        default:
            body.classList.add('tema-claro');
    }
}

function salvarPreferencias() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Salvando...';
    
    // Coletar dados das preferências
    const preferencias = {
        // Notificações
        email_notifications: document.querySelector('.notif-item:nth-child(1) input[type="checkbox"]').checked,
        system_notifications: document.querySelector('.notif-item:nth-child(2) input[type="checkbox"]').checked,
        ai_updates: document.querySelector('.notif-item:nth-child(3) input[type="checkbox"]').checked,
        newsletter: document.querySelector('.notif-item:nth-child(4) input[type="checkbox"]').checked,
        
        // Idioma e região
        language: document.getElementById('idioma').value,
        timezone: document.getElementById('fusoHorario').value,
        date_format: document.getElementById('formatoData').value,
        
        // Tema e aparência
        theme: temaAtual,
        compact_interface: document.querySelector('.config-item:nth-child(1) input[type="checkbox"]').checked,
        reduced_animations: document.querySelector('.config-item:nth-child(2) input[type="checkbox"]').checked
    };
    
    // Enviar para o servidor
    fetch('<?= BASE_URL ?>/profile/save-preferences', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            ...preferencias,
            'csrf_token': '<?= $_SESSION["csrf_token"] ?? "" ?>'
        })
    })
    .then(response => response.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.success) {
                // Atualizar valores originais
                preferencasOriginais = {...preferencias};
                
                // Aplicar tema e configurações de interface
                aplicarTema(preferencias.theme);
                aplicarConfiguracaoInterface(preferencias);
                
                // Aplicar idioma se mudou
                if (preferencias.language !== idiomaAtual) {
                    aplicarIdioma(preferencias.language);
                }
                
                // Mostrar mensagem de sucesso no idioma correto
                const traducao = traducoes[preferencias.language] || traducoes['pt-BR'];
                mostrarAlerta(traducao['Preferências salvas com sucesso!'], 'sucesso');
                
            } else {
                mostrarAlerta('Erro ao salvar preferências: ' + (data.message || 'Erro desconhecido'), 'erro');
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            mostrarAlerta('Erro de resposta do servidor', 'erro');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        mostrarAlerta('Erro de rede: ' + error.message, 'erro');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function aplicarConfiguracaoInterface(prefs) {
    const body = document.body;
    
    // Interface compacta
    if (prefs.compact_interface) {
        body.classList.add('interface-compacta');
    } else {
        body.classList.remove('interface-compacta');
    }
    
    // Animações reduzidas
    if (prefs.reduced_animations) {
        body.classList.add('animacoes-reduzidas');
    } else {
        body.classList.remove('animacoes-reduzidas');
    }
}

function aplicarIdioma(idioma) {
    try {
        const traducao = traducoes[idioma];
        if (!traducao) return;
        
        idiomaAtual = idioma;
        
        // Traduzir elementos específicos da aba Preferências
        const elementos = {
            '.card-titulo span': [
                'Notificações',
                'Idioma e Região', 
                'Tema e Aparência'
            ],
            '.notif-item span': [
                'Notificações por email',
                'Notificações do sistema',
                'Atualizações de IA',
                'Newsletter mensal'
            ],
            'label[for="idioma"]': 'Idioma',
            'label[for="fusoHorario"]': 'Fuso Horário',
            'label[for="formatoData"]': 'Formato de Data',
            '.tema-opcao span': ['Claro', 'Escuro', 'Automático'],
            '.config-item span': ['Interface compacta', 'Animações reduzidas'],
            'button[onclick="salvarPreferencias()"]': 'Salvar Preferências'
        };
        
        // Aplicar traduções
        Object.keys(elementos).forEach(seletor => {
            const textos = elementos[seletor];
            const elementosDOM = document.querySelectorAll(seletor);
            
            if (Array.isArray(textos)) {
                elementosDOM.forEach((el, index) => {
                    if (textos[index] && traducao[textos[index]]) {
                        el.textContent = traducao[textos[index]];
                    }
                });
            } else {
                elementosDOM.forEach(el => {
                    if (traducao[textos]) {
                        el.textContent = traducao[textos];
                    }
                });
            }
        });
        
        console.log('Idioma aplicado:', idioma);
    } catch (e) {
        console.error('Erro ao aplicar idioma:', e);
    }
}

function formatarDataComPreferencia(data, formato) {
    try {
        const dataObj = new Date(data);
        if (isNaN(dataObj)) return data;
        
        const dia = String(dataObj.getDate()).padStart(2, '0');
        const mes = String(dataObj.getMonth() + 1).padStart(2, '0');
        const ano = dataObj.getFullYear();
        
        switch (formato) {
            case 'mm/dd/yyyy':
                return `${mes}/${dia}/${ano}`;
            case 'yyyy-mm-dd':
                return `${ano}-${mes}-${dia}`;
            default: // dd/mm/yyyy
                return `${dia}/${mes}/${ano}`;
        }
    } catch (e) {
        return data;
    }
}

function aplicarFormatoData(formato) {
    try {
        // Buscar todos os elementos de data na página e reformatar
        const elementosData = document.querySelectorAll('[data-date]');
        elementosData.forEach(el => {
            const dataOriginal = el.getAttribute('data-date');
            if (dataOriginal) {
                el.textContent = formatarDataComPreferencia(dataOriginal, formato);
            }
        });
        
        console.log('Formato de data aplicado:', formato);
    } catch (e) {
        console.error('Erro ao aplicar formato de data:', e);
    }
}

function aplicarFusoHorario(fusoHorario) {
    try {
        // Aplicar fuso horário aos elementos de data/hora
        const elementosHora = document.querySelectorAll('[data-datetime]');
        elementosHora.forEach(el => {
            const dataHoraOriginal = el.getAttribute('data-datetime');
            if (dataHoraOriginal) {
                const dataObj = new Date(dataHoraOriginal);
                const dataFormatada = dataObj.toLocaleString('pt-BR', {
                    timeZone: fusoHorario,
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                el.textContent = dataFormatada;
            }
        });
        
        console.log('Fuso horário aplicado:', fusoHorario);
    } catch (e) {
        console.error('Erro ao aplicar fuso horário:', e);
    }
}

function atualizarElementosDataHora() {
    // Atualizar elementos de data/hora com as preferências atuais
    const formatoAtual = document.getElementById('formatoData')?.value || 'dd/mm/yyyy';
    const fusoAtual = document.getElementById('fusoHorario')?.value || 'America/Sao_Paulo';
    
    aplicarFormatoData(formatoAtual);
    aplicarFusoHorario(fusoAtual);
}

// Detectar mudanças no tema do sistema para tema automático
if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        if (temaAtual === 'auto') {
            aplicarTema('auto');
        }
    });
}

// Função auxiliar para mostrar alertas
function mostrarAlerta(mensagem, tipo) {
    // Criar elemento de alerta
    const alerta = document.createElement('div');
    alerta.className = `alerta alerta-${tipo}`;
    alerta.innerHTML = `
        <i class="fas fa-${tipo === 'sucesso' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${mensagem}</span>
    `;
    
    // Adicionar ao DOM
    document.body.appendChild(alerta);
    
    // Remover após 5 segundos
    setTimeout(() => {
        if (alerta.parentNode) {
            alerta.parentNode.removeChild(alerta);
        }
    }, 5000);
}

</script>