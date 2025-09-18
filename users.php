<?php
require 'config.php';

$stmt = $pdo->query("SELECT id, fullname, email, wallet_balance, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users - LightSmartPay</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
    <h2>Registered Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Fullname</th>
            <th>Email</th>
            <th>Wallet Balance</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['fullname']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>₦<?= number_format($user['wallet_balance'], 2) ?></td>
            <td><?= $user['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
