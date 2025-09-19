<?php
session_start();
$pdo = include "config.php";

// Admin email
$admin_email = "admin@lightsmartpay.com";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user["email"] !== $admin_email) {
    die("⛔ Access denied. Admin only.");
}

$users = $pdo->query("SELECT id, full_name, email, wallet_balance, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="content">
    <h2>👥 Manage Users</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Balance</th><th>Joined</th>
        </tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>₦<?= number_format($u['wallet_balance'], 2) ?></td>
            <td><?= $u['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">⬅ Back</a>
</div>
</body>
</html>
