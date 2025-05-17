CREATE TABLE IF NOT EXISTS password_reset_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    identifier VARCHAR(255) NOT NULL,
    email_otp VARCHAR(6),
    phone_otp VARCHAR(6),
    email_otp_expires_at DATETIME,
    phone_otp_expires_at DATETIME,
    reset_token VARCHAR(255),
    token_expires_at DATETIME,
    attempts INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_identifier (identifier),
    INDEX idx_reset_token (reset_token)
); 