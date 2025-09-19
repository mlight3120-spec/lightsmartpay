<?php
require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['pin_verified']) || $_SESSION['pin_verified'] !== true) {
    header("Location: pin_verify.php?redirect=cable.php");
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $provider = $_POST['provider'];
    $card = $_POST['card'];
    $amount = (int)$_POST['amount'];

    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user['wallet_balance'] < $amount) {
        $message = "❌ Insufficient wallet balance.";
    } else {
        $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?")
            ->execute([$amount, $_SESSION['user_id']]);
        $pdo->prepare("INSERT INTO transactions_cable (user_id, provider, card_number, amount) VALUES (?, ?, ?, ?)")
            ->execute([$_SESSION['user_id'], $provider, $card, $amount]);

        $message = "✅ Cable subscription successful for $card ($provider)";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cable Subscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="content">
    <h2>📺 Cable Subscription</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post">
        <select name="provider" required>
            <option value="">--Select Provider--</option>
            <option value="DSTV">DSTV</option>
            <option value="GOTV">GOTV</option>
            <option value="Startimes">Startimes</option>
        </select>
        <input type="text" name="card" placeholder="Smartcard / IUC Number" required>
        <input type="number" name="amount" placeholder="Amount" required>
        <button type="submit">Subscribe</button>
    </form>
</div>
</body>
</html>
