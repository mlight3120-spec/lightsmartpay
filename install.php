<?php
$config = include __DIR__ . '/config.php';

try {
    $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Drop all tables (CASCADE so dependencies no block)
    $pdo->exec("DROP TABLE IF EXISTS transactions_cable CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS transactions_airtime CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS transactions_data CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS transactions CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS pins CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS users CASCADE");

    // ✅ Users table
    $pdo->exec("
        CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            wallet_balance DECIMAL(12,2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // ✅ Pins table
    $pdo->exec("
        CREATE TABLE pins (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            pin VARCHAR(6) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // ✅ Transactions (general)
    $pdo->exec("
        CREATE TABLE transactions (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            type VARCHAR(50) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // ✅ Transactions: Data
    $pdo->exec("
        CREATE TABLE transactions_data (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            network VARCHAR(50) NOT NULL,
            plan VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // ✅ Transactions: Airtime
    $pdo->exec("
        CREATE TABLE transactions_airtime (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            network VARCHAR(50) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // ✅ Transactions: Cable
    $pdo->exec("
        CREATE TABLE transactions_cable (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            provider VARCHAR(50) NOT NULL,
            smartcard VARCHAR(50) NOT NULL,
            plan VARCHAR(100) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "✅ Tables created successfully!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
