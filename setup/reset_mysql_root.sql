-- Reset MySQL root password
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'MegaFisio123!';
FLUSH PRIVILEGES;