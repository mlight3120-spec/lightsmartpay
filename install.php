<?php
// install.php
$config = include(__DIR__ . '/config.php');

try {
    $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✅ Connected to Render PostgreSQL successfully.<br>";

    // Create tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            fullname VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            wallet_balance DECIMAL(12,2) DEFAULT 0,
            pin VARCHAR(10),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS transactions (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL REFERENCES users(id),
            type VARCHAR(20) NOT NULL, -- data, airtime, cable
            amount DECIMAL(12,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            details TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    echo "✅ All tables created successfully in PostgreSQL.<br>";
    echo "👉 Now you can delete install.php for security.";

} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
