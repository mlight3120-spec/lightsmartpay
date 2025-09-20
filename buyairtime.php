<?php
$config = include __DIR__ . "/config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Check if PIN already verified this session
if (!isset($_SESSION['pin_verified']) || $_SESSION['pin_verified'] !== true) {
    header("Location: pin_verify.php?redirect=airtime.php");
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $network = $_POST['network'];
    $phone = $_POST['phone'];
    $amount = (int)$_POST['amount'];

    // Deduct from wallet
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user['wallet_balance'] < $amount) {
        $message = "❌ Insufficient wallet balance.";
    } else {
        // Deduct + record
        $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?")
            ->execute([$amount, $_SESSION['user_id']]);
        $pdo->prepare("INSERT INTO transactions_airtime (user_id, network, phone, amount) VALUES (?, ?, ?, ?)")
            ->execute([$_SESSION['user_id'], $network, $phone, $amount]);

        $message = "✅ Airtime of ₦$amount to $phone ($network) successful!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Buy Airtime</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="content">
    <h2>📱 Airtime Top-up</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post">
        <select name="network" required>
            <option value="">--Select Network--</option>
            <option value="MTN">MTN</option>
            <option value="GLO">GLO</option>
            <option value="Airtel">Airtel</option>
            <option value="9mobile">9mobile</option>
        </select>
        <input type="text" name="phone" placeholder="Recipient Phone" required>
        <input type="number" name="amount" placeholder="Amount" required>
        <button type="submit">Buy Airtime</button>
    </form>
</div>
</body>
</html>
