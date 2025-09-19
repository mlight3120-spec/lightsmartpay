<?php
require "db.php";
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="content">
    <h2>My Profile</h2>
    <p><b>Name:</b> <?= htmlspecialchars($user['full_name']); ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($user['email']); ?></p>
    <p><b>Wallet Balance:</b> ₦<?= number_format($user['wallet_balance'],2); ?></p>
</div>
</body>
</html>
