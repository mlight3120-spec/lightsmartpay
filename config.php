<?php
$host = "dpg-d326jbadbo4c73a6urog-a";
$dbname = "llightsmartpay"; 
$user = "lightsmartpay_user";
$pass = "ikPevBhb9xHModBeJYsGIvtcmY6rmPuH";
$port = 5432;

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
 
