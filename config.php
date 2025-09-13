<?php
// Database connection settings
$host = "dpg-d326jbadbo4c73a6urog-a"; 
$dbname = "lightsmartpay";    
$user = "lightsmartpay_user"; 
$pass = "ikPevBhb9xHModBeJYsGIvtcmY6rmPuH";
$port = "5432";  

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
