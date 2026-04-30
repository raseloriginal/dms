<?php
require_once 'c:/xampp/htdocs/dms/config/config.php';
$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create Users table
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Create Staff table
$pdo->exec("CREATE TABLE IF NOT EXISTS staff (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Insert default staff if not exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM staff WHERE username = 'staff'");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $hashed = password_hash('123456', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO staff (username, password) VALUES ('staff', '$hashed')");
}

echo "Tables created successfully.\n";
