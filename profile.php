<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT full_name, email, wallet_balance, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile - LightSmartPay</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <h2>👤 Profile</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($user["full_name"]); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]); ?></p>
    <p><strong>Wallet:</strong> ₦<?= number_format($user["wallet_balance"], 2); ?></p>
    <p><strong>Joined:</strong> <?= $user["created_at"]; ?></p>
</div>
</body>
</html>
