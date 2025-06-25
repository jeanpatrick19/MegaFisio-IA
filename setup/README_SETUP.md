# 📦 GUIA DE INSTALAÇÃO - MEGA FISIO IA

## 🚀 Deploy Rápido (3 passos)

### 1️⃣ Upload dos Arquivos (FTP)
```
1. Faça upload de TODOS os arquivos para seu servidor
2. Mova o conteúdo da pasta /public/ para public_html/
3. Os demais arquivos devem ficar FORA de public_html/
```

### 2️⃣ Configurar Banco de Dados
```
1. Crie um banco MySQL no painel da hospedagem
2. Anote as credenciais (host, usuário, senha, nome do banco)
3. Acesse: https://seu-dominio.com/setup/auto_install.php
```

### 3️⃣ Primeiro Acesso
```
- O sistema criará automaticamente todas as tabelas
- Login padrão: admin@megafisio.com / admin123
- IMPORTANTE: Mude a senha após o primeiro login
```

---

## 📁 Estrutura de Pastas

### Para Hospedagem Compartilhada:
```
/home/usuario/
├── megafisio/              (pasta principal - fora de public_html)
│   ├── config/             (configurações)
│   ├── src/                (código-fonte)
│   ├── migrations/         (banco de dados)
│   ├── logs/               (logs do sistema)
│   ├── backups/            (backups automáticos)
│   └── setup/              (instalação - deletar após usar)
│
└── public_html/            (pasta pública do servidor)
    ├── index.php           (entrada do sistema)
    ├── .htaccess           (configurações Apache)
    └── assets/             (CSS, JS, imagens)
```

---

## ⚙️ Configurações de Produção

### Arquivos para Editar:

1. **`/setup/configs/config_production.php`**
   - Altere `HASH_SALT` (gere uma chave aleatória)
   - Configure API de IA (OpenAI, Claude, etc)
   - Configure SMTP para emails

2. **`/setup/configs/database_production.php`**
   - Insira as credenciais do MySQL
   - Host: geralmente 'localhost'
   - Banco, usuário e senha: fornecidos pela hospedagem

3. **`/setup/configs/.htaccess_production`**
   - Substitua 'seu-dominio.com' pelo seu domínio real
   - Descomente a linha de HTTPS se tiver SSL

---

## 🔒 Segurança Pós-Instalação

### Obrigatório:
- [ ] Deletar pasta `/setup/` completa
- [ ] Mudar senha do admin
- [ ] Configurar SSL/HTTPS
- [ ] Testar sistema de backup

### Recomendado:
- [ ] Configurar firewall
- [ ] Ativar monitoramento
- [ ] Configurar alertas por email
- [ ] Backup diário automático

---

## 🛠️ Scripts Úteis

### Deploy Automático:
```bash
php setup/scripts/deploy.php
```

### Verificar Sistema:
```bash
php setup/scripts/health_check.php
```

### Backup Manual:
```bash
php setup/scripts/backup.php
```

---

## ❓ Problemas Comuns

### Erro 500:
- Verifique permissões (pastas: 755, arquivos: 644)
- Confirme versão do PHP (8.0+)
- Verifique .htaccess

### Banco não conecta:
- Confirme credenciais
- Teste conexão no painel da hospedagem
- Verifique se o usuário tem todas permissões

### Página em branco:
- Ative DEBUG_MODE temporariamente
- Verifique logs em /logs/error.log
- Confirme mod_rewrite ativo

---

## 📞 Suporte

Em caso de problemas:
1. Verifique /logs/error.log
2. Consulte a documentação em /docs/
3. Execute o health_check.php

---

✅ **Após instalação bem-sucedida, delete toda a pasta /setup/ por segurança!**