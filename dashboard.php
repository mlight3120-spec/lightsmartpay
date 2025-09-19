<?php
session_start();
$pdo = include "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - LightSmartPay</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar">
    <h2>💡 LightSmartPay</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="profile.php">👤 Profile</a>
    <a href="transactions.php">📜 Transactions</a>
    <a href="data.php">📶 Buy Data</a>
    <a href="airtime.php">📞 Airtime</a>
    <a href="cable.php">📺 Cable</a>
    <a href="pins.php">🎫 Pins</a>
    <a href="logout.php">🚪 Logout</a>
</div>
<div class="content">
    <h2>Welcome, <?php echo htmlspecialchars($user["full_name"]); ?> 🎉</h2>
    <p>Wallet Balance: ₦<?php echo number_format($user["wallet_balance"], 2); ?></p>
</div>
</body>
</html>
