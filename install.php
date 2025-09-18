<?php
$config = include __DIR__ . "/config.php";

try {
    $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // ✅ Create users table with fullname
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            fullname VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password TEXT NOT NULL,
            wallet_balance NUMERIC(12,2) DEFAULT 0,
            pin VARCHAR(10),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "✅ Tables created successfully!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
