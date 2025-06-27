# MegaFisio IA - Sistema de Gestão Fisioterapêutica com IA

Sistema completo de gestão para fisioterapeutas com integração OpenAI para análise de casos clínicos, geração de relatórios e sugestões de exercícios.

## 🚀 Instalação Rápida

### 1. Upload dos Arquivos
Faça upload de todos os arquivos para o diretório raiz da sua hospedagem.

### 2. Configuração do Banco de Dados
No painel da sua hospedagem:
- Crie um banco de dados MySQL
- Anote: nome do banco, usuário e senha

### 3. Executar Instalador
Acesse: `https://seudominio.com/install.php`

O instalador vai:
- ✅ Configurar conexão com banco
- ✅ Criar todas as tabelas automaticamente
- ✅ Configurar usuário administrador
- ✅ Instalar prompts padrão de IA
- ✅ Configurar sistema de segurança

### 4. Primeiro Acesso
Após instalação, acesse o sistema com as credenciais que você definiu.

## 🛡️ Segurança

O sistema inclui:
- Autenticação segura com hash de senhas
- Proteção CSRF em formulários
- Rate limiting para login
- Logs de auditoria completos
- Sanitização de dados

## 🤖 Integração OpenAI

### Configurar API Key
1. Acesse `/admin/prompts`
2. Configure a chave da API OpenAI
3. Teste os prompts pré-configurados

### Prompts Inclusos
- **Análise de Caso Clínico** (30 req/dia)
- **Geração de Relatório** (20 req/dia)
- **Sugestão de Exercícios** (40 req/dia)
- **Classificação CID-10** (25 req/dia)

## 📁 Estrutura de Arquivos

```
/
├── install.php              # Instalador automático
├── public/                  # Pasta pública (document root)
│   ├── index.php           # Ponto de entrada
│   ├── .htaccess           # Configurações Apache
│   └── assets/             # CSS, JS, imagens
├── config/                 # Configurações
│   ├── config.php          # Configurações gerais
│   ├── database.php        # Classe de banco
│   └── db_config.php       # Credenciais (criado na instalação)
├── src/                    # Código fonte
│   ├── controllers/        # Controladores
│   ├── models/            # Modelos e migrations
│   ├── services/          # Serviços (OpenAI)
│   └── views/             # Templates
└── migrations/            # Arquivos SQL
```

## 🎯 Funcionalidades

### Administração
- Gestão completa de usuários
- Configuração de prompts IA
- Logs de sistema e auditoria
- Controle de permissões

### Inteligência Artificial
- Análise automática de casos clínicos
- Geração de relatórios personalizados
- Sugestões de exercícios terapêuticos
- Cache inteligente para economia de tokens

### Interface
- Design responsivo (mobile-first)
- Cores: preto, dourado, branco, cinza
- Dashboard administrativo completo
- Formulários com validação em tempo real

## ⚙️ Configuração Avançada

### 🔄 Deploy Automático (Desenvolvimento → Produção)

O sistema detecta automaticamente o ambiente e configura URLs, debug e segurança:

**Desenvolvimento (Localhost):**
- URLs: `/megafisio-ia/public/`
- Debug: ON (erros visíveis)
- Sessão: Cookies não-seguros

**Produção (Hospedagem):**
- URLs: Raiz do domínio
- Debug: OFF (erros ocultos)
- Sessão: Cookies seguros (HTTPS)

#### Configuração do .htaccess para Localhost
Se usar `localhost:8080` ou subdiretório, edite `.htaccess`:
```apache
# Para desenvolvimento (localhost) - DESCOMENTE esta linha:
RewriteBase /megafisio-ia/

# Para produção - mantenha comentado:
# RewriteBase /
```

#### Detecção Automática
O sistema detecta produção quando:
- Domain ≠ `localhost`, `127.0.0.1`, `::1`
- Sem `.local` no domínio
- Sem `:8080` na URL

### Variáveis de Ambiente (Opcional)
Crie arquivo `.env` na raiz:
```env
OPENAI_API_KEY=sk-proj-sua-chave-aqui
DB_HOST=localhost
DB_NAME=megafisio_ia
DB_USER=seu_usuario
DB_PASS=sua_senha
```

### Configurações do Servidor
- PHP 7.4+ (recomendado 8.0+)
- MySQL 5.7+ ou MariaDB 10.3+
- mod_rewrite habilitado
- HTTPS recomendado

## 🚀 Deploy para Hospedagem (FTP)

### Preparação para Produção

**1. Configuração do Banco de Dados**
Edite `config/db_config.php` com os dados da hospedagem:
```php
// PRODUÇÃO - Altere com os dados da sua hospedagem
return [
    'host' => 'localhost',                    // Ex: 'mysql.hostinger.com'
    'database' => 'megafisio_ia',             // Ex: 'u123456_megafisio'
    'username' => 'seu_usuario_mysql',        // Ex: 'u123456_admin'
    'password' => 'sua_senha_mysql',          // Senha do MySQL
    'charset' => 'utf8mb4'
];
```

**2. Upload via FTP**
- Faça upload de **TODOS** os arquivos para o diretório raiz
- Mantenha a estrutura de pastas exata
- Verifique se o `.htaccess` foi transferido

**3. Primeiro Acesso**
- Sistema detecta automaticamente que está em produção
- URLs serão configuradas baseadas no seu domínio
- Login padrão: admin@megafisio.com / admin123

**4. Recursos Automáticos**
- ✅ Auto-detecção localhost vs produção
- ✅ Criação automática do banco e tabelas
- ✅ URLs dinâmicas baseadas no domínio
- ✅ Configuração automática HTTPS/HTTP

## 🔧 Problemas Comuns

### URLs/CSS não carregam (localhost:8080)
**Solução 1 - Editar .htaccess:**
```apache
# Descomente esta linha no .htaccess:
RewriteBase /megafisio-ia/
```

**Solução 2 - Verificar detecção automática:**
- Sistema detecta localhost automaticamente
- Verifique `config/environment.php`
- Se necessário,force: `'is_production' => false`

### Erro de Conexão MySQL na Hospedagem
- Verifique credenciais em `config/db_config.php`
- Certifique-se que o banco existe no painel da hospedagem
- Verifique se o usuário MySQL tem permissões CREATE
- Teste conexão via phpMyAdmin da hospedagem

### Erro 500 na Hospedagem
- Verifique permissões dos arquivos (644 para arquivos, 755 para pastas)
- Consulte error_log no painel da hospedagem
- Certifique-se que PHP >= 7.4 está ativo
- Verifique se mod_rewrite está habilitado

### Deploy em Produção via FTP
- ✅ Sistema detecta automaticamente produção
- ✅ Apenas edite `config/db_config.php` com dados da hospedagem
- ✅ URLs e configurações são automáticas
- ✅ HTTPS detectado e configurado automaticamente

### Login não funciona
- Execute novamente o instalador
- Verifique se as tabelas foram criadas
- Limpe cache do navegador
- Verifique configurações de sessão no painel da hospedagem

## 📞 Suporte

Para problemas de instalação ou uso:
1. Verifique os logs em `/logs/`
2. Consulte este README
3. Entre em contato com suporte técnico

## 🚀 Próximos Passos

Após instalação:
1. Configure API Key OpenAI
2. Teste os prompts inclusos
3. Customize conforme necessidade
4. Treine usuários no sistema

---

**Sistema MegaFisio IA v1.0**  
*Desenvolvido para facilitar o trabalho de fisioterapeutas com tecnologia de ponta.*