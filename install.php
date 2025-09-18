<?php
require 'config.php';

try {
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        wallet_balance NUMERIC(12,2) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS pins (
        id SERIAL PRIMARY KEY,
        user_id INT REFERENCES users(id) ON DELETE CASCADE,
        pin VARCHAR(6) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS transactions_data (
        id SERIAL PRIMARY KEY,
        user_id INT REFERENCES users(id) ON DELETE CASCADE,
        network VARCHAR(50) NOT NULL,
        plan VARCHAR(50) NOT NULL,
        amount NUMERIC(12,2) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS transactions_airtime (
        id SERIAL PRIMARY KEY,
        user_id INT REFERENCES users(id) ON DELETE CASCADE,
        network VARCHAR(50) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        amount NUMERIC(12,2) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS transactions_cable (
        id SERIAL PRIMARY KEY,
        user_id INT REFERENCES users(id) ON DELETE CASCADE,
        provider VARCHAR(50) NOT NULL,
        smartcard VARCHAR(20) NOT NULL,
        plan VARCHAR(50) NOT NULL,
        amount NUMERIC(12,2) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ";

    $pdo->exec($sql);
    echo "✅ Database tables created successfully!";
} catch (PDOException $e) {
    echo "❌ Error creating tables: " . $e->getMessage();
}
?>
