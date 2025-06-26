<?php

class CreateUserSessionsTable {
    
    public static function up($db) {
        $sql = "
        CREATE TABLE IF NOT EXISTS user_sessions (
            id VARCHAR(128) PRIMARY KEY,
            user_id INT NOT NULL,
            user_agent TEXT,
            ip_address VARCHAR(45),
            location VARCHAR(255),
            device_type VARCHAR(50),
            browser VARCHAR(100),
            os VARCHAR(100),
            is_current BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NULL,
            is_active BOOLEAN DEFAULT TRUE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_sessions_user_id (user_id),
            INDEX idx_user_sessions_active (is_active),
            INDEX idx_user_sessions_expires (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        return $db->exec($sql);
    }
    
    public static function down($db) {
        return $db->exec("DROP TABLE IF EXISTS user_sessions");
    }
}