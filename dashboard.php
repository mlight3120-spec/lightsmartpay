<?php
session_start();
$pdo = include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT full_name, wallet_balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - LightSmartPay</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <h2>Welcome, <?= htmlspecialchars($user["full_name"]); ?> 👋</h2>
    <div class="card">
        <h3>💰 Wallet Balance</h3>
        <p>₦<?= number_format($user["wallet_balance"], 2); ?></p>
        <small>Commission: ₦50 on signup ✅</small>
    </div>
</div>
</body>
</html>
