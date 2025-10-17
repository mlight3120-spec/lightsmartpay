<?php
$config = include __DIR__ . "/config.php";

try {
    // Drop existing tables safely
    $pdo->exec("DROP TABLE IF EXISTS transactions, airtime, data, cable, users CASCADE");

    // Users table
    $pdo->exec("
        CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            wallet_balance NUMERIC(10,2) DEFAULT 50.00, -- default ₦50 commission
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Airtime
    $pdo->exec("
        CREATE TABLE airtime (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            network VARCHAR(20),
            phone VARCHAR(20),
            amount NUMERIC(10,2),
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Data
    $pdo->exec("
        CREATE TABLE data (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            network VARCHAR(20),
            plan VARCHAR(50),
            phone VARCHAR(20),
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Cable
    $pdo->exec("
        CREATE TABLE cable (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            provider VARCHAR(20),
            smartcard VARCHAR(30),
            plan VARCHAR(50),
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Transactions (general)
    $pdo->exec("
        CREATE TABLE transactions (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            service VARCHAR(20),
            details TEXT,
            amount NUMERIC(10,2),
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "✅ Tables created successfully!";
} catch (Exception $e) {
    die("❌ Error: " . $e->getMessage());
}
