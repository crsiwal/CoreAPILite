<?php
/**
 * Database Tables Creation Script
 * 
 * This script creates all necessary tables for the Photo Album System.
 * Make sure to configure your database credentials before running this script.
 */

// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'localdb',
    'charset' => 'utf8mb4'
];

$pdo = null;
try {
    // Create database connection
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};charset={$dbConfig['charset']}",
        $dbConfig['username'],
        $dbConfig['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    // Start transaction
    $pdo->beginTransaction();

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbConfig['database']}`");

    echo "Database connection established successfully.\n";

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(150),
        profile_photo VARCHAR(255),
        phone VARCHAR(20),
        domain_name VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user', 'moderator') DEFAULT 'user',
        status ENUM('active', 'inactive') DEFAULT 'active',
        last_login DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    echo "Users table created successfully.\n";

    // Add indexes for better performance
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_users_username ON users(username)");

    echo "All indexes created successfully.\n";

    // Commit transaction
    $pdo->commit();
    echo "\nDatabase setup completed successfully!\n";

} catch (PDOException $e) {
    // Rollback transaction if it was started
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Error: " . $e->getMessage() . "\n");
} finally {
    // Close connection
    $pdo = null;
} 