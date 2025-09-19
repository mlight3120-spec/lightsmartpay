<?php
require "db.php";
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transactions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="content">
    <h2>Transaction History</h2>
    <table>
        <tr><th>Service</th><th>Details</th><th>Amount</th><th>Status</th><th>Date</th></tr>
        <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t['service']); ?></td>
            <td><?= htmlspecialchars($t['details']); ?></td>
            <td>₦<?= number_format($t['amount'],2); ?></td>
            <td><?= htmlspecialchars($t['status']); ?></td>
            <td><?= $t['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
