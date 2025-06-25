-- Comandos para configurar o MySQL
-- Execute como root: sudo mysql < setup/mysql_setup.sql

-- Criar usuário jean se não existir
CREATE USER IF NOT EXISTS 'jean'@'localhost' IDENTIFIED BY 'MegaFisio123!';

-- Dar todas as permissões ao usuário jean
GRANT ALL PRIVILEGES ON *.* TO 'jean'@'localhost' WITH GRANT OPTION;

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS megafisio_ia;

-- Dar permissões específicas no banco
GRANT ALL PRIVILEGES ON megafisio_ia.* TO 'jean'@'localhost';

-- Aplicar alterações
FLUSH PRIVILEGES;

-- Verificar se o usuário foi criado
SELECT User, Host FROM mysql.user WHERE User = 'jean';