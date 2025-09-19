<?php
// db.php
$config = require "config.php";

try {
    $pdo = new PDO(
        "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']}",
        $config['DB_USER'],
        $config['DB_PASS']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("❌ DB Connection failed: " . $e->getMessage());
}
