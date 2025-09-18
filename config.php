<?php
// config.php

$host = "dpg-d326jbadbo4c73a6urog-a";
$port = "5432";
$dbname = "lightsmartpay";
$user = "lightsmartpay_user";
$pass = "ikPevBhb9xHModBeJYsGIvtcmY6rmPuH";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("❌ DB Connection failed: " . $e->getMessage());
}
