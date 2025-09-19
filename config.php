<?php
$DB_HOST = "dpg-d326jbadbo4c73a6urog-at";   
$DB_PORT = "5432";           
$DB_NAME = "lightsmartpay";   
$DB_USER = "lightsmartpay_user";   
$DB_PASS = "ikPevBhb9xHModBeJYsGIvtcmY6rmPuH";   

$dsn = "pgsql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME";

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ DB Connection failed: " . $e->getMessage());
}

return $pdo; // ✅ Any file wey require config.php go get $pdo directly
