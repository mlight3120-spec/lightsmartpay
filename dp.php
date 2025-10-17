<?php
$host = "dpg-d3p3smu3jp1c739taqf0-a";   
$port = "5432";        
$dbname = "lightsmartpay_86o4";
$user = "lightsmartpay_86o4_user";
$pass = "B7zSfYVi0RWA0zKmLwXMXOZHxeeGiSz2";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
