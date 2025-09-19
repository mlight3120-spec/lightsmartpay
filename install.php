<?php
$pdo = include "config.php";

try {
    // Drop old tables
    $pdo->exec("DROP TABLE IF EXISTS transactions_cable, transactions_airtime, transactions_data, transactions, pins, users CASCADE");

    // Create users
    $pdo->exec("
        CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            wallet_balance NUMERIC(12,2) DEFAULT 50.00,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create transactions
    $pdo->exec("
        CREATE TABLE transactions (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            type VARCHAR(50) NOT NULL,
            amount NUMERIC(12,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "✅ Tables created successfully!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
