<?php
require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar">
    <h2>My Panel</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="airtime.php">Airtime</a></li>
        <li><a href="data.php">Data</a></li>
        <li><a href="cable.php">Cable TV</a></li>
        <li><a href="transactions.php">Transactions</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <h1>Welcome, <?php echo htmlspecialchars($user['full_name']); ?> 👋</h1>
    <p><b>Wallet Balance:</b> ₦<?php echo number_format($user['wallet_balance'], 2); ?></p>
    <p>Select any service from the menu.</p>
</div>
</body>
</html>
