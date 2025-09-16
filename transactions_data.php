<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND type = 'data' ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Transactions - LightSmartPay</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>📶 Data Transaction History</h2>
    <table>
        <tr>
            <th>Network</th>
            <th>Phone</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php foreach ($transactions as $tx): ?>
        <tr>
            <td><?= htmlspecialchars($tx['network']) ?></td>
            <td><?= htmlspecialchars($tx['phone']) ?></td>
            <td>₦<?= number_format($tx['amount'], 2) ?></td>
            <td><?= htmlspecialchars($tx['status']) ?></td>
            <td><?= $tx['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
