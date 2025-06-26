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
                    <div class="status-indicator <?= $user['two_factor_enabled'] ?? false ? 'ativo' : 'inativo' ?>">
                        <i class="fas fa-<?= $user['two_factor_enabled'] ?? false ? 'shield-alt' : 'shield' ?>"></i>
                    </div>
                    <div class="status-info">
                        <h4><?= $user['two_factor_enabled'] ?? false ? '2FA Ativo' : '2FA Inativo' ?></h4>
                        <p><?= $user['two_factor_enabled'] ?? false ? 'Sua conta está protegida' : 'Adicione uma camada extra de segurança' ?></p>
                    </div>
                </div>
                
                <?php if ($user['two_factor_enabled'] ?? false): ?>
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
            </div>
            
            <div class="sessoes-lista">
                <div class="sessao-item atual">
                    <div class="sessao-info">
                        <div class="sessao-device">
                            <i class="fas fa-desktop"></i>
                            <span>Windows - Chrome</span>
                        </div>
                        <div class="sessao-detalhes">
                            <span>IP: 192.168.1.100</span>
                            <span>Atual</span>
                        </div>
                    </div>
                    <span class="sessao-badge atual">Sessão Atual</span>
                </div>
                
                <div class="sessao-item">
                    <div class="sessao-info">
                        <div class="sessao-device">
                            <i class="fas fa-mobile-alt"></i>
                            <span>iPhone - Safari</span>
                        </div>
                        <div class="sessao-detalhes">
                            <span>IP: 192.168.1.101</span>
                            <span>Há 2 horas</span>
                        </div>
                    </div>
                    <button class="btn-fisio btn-secundario btn-pequeno" onclick="revogarSessao(2)">
                        Revogar
                    </button>
                </div>
            </div>
            
            <button class="btn-fisio btn-secundario btn-pequeno" onclick="revogarTodasSessoes()">
                <i class="fas fa-sign-out-alt"></i>
                Revogar Todas as Sessões
            </button>
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
                        <option value="America/Sao_Paulo" selected>Brasília (UTC-3)</option>
                        <option value="America/Manaus">Manaus (UTC-4)</option>
                        <option value="America/Rio_Branco">Rio Branco (UTC-5)</option>
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
    mostrarAlerta('Modal de ativação 2FA será implementado', 'info');
}

function desativar2FA() {
    if (confirm('Tem certeza que deseja desativar o 2FA?')) {
        mostrarAlerta('2FA desativado', 'sucesso');
    }
}

function revogarSessao(id) {
    if (confirm('Deseja revogar esta sessão?')) {
        mostrarAlerta('Sessão revogada', 'sucesso');
    }
}

function revogarTodasSessoes() {
    if (confirm('Deseja revogar todas as outras sessões?')) {
        mostrarAlerta('Todas as sessões foram revogadas', 'sucesso');
    }
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
</script>