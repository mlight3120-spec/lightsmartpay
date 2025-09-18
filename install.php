<?php
// install.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Create tables
    $queries = [

        // Users table
        "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            wallet_balance DECIMAL(12,2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );",

        // PIN table
        "CREATE TABLE IF NOT EXISTS pins (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            pin VARCHAR(4) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );",

        // Transactions table
        "CREATE TABLE IF NOT EXISTS transactions (
            id SERIAL PRIMARY KEY,
            user_id INT REFERENCES users(id) ON DELETE CASCADE,
            type VARCHAR(20) NOT NULL, -- data, airtime, cable, fund
            amount DECIMAL(12,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            details TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );"
    ];

    foreach ($queries as $sql) {
        $pdo->exec($sql);
    }

    echo "✅ All tables created successfully in PostgreSQL.<br>";
    echo "👉 For security, delete install.php after running it once.";

} catch (PDOException $e) {
    die("❌ Error creating tables: " . $e->getMessage());
}
?>
