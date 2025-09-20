<?php
session_start();
$config = include __DIR__ . "/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['pin_verified']) || $_SESSION['pin_verified'] !== true) {
    header("Location: pin_verify.php?redirect=data.php");
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $network = $_POST['network'];
    $phone = $_POST['phone'];
    $plan = $_POST['plan'];
    $price = (int)$_POST['price'];

    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user['wallet_balance'] < $price) {
        $message = "❌ Insufficient wallet balance.";
    } else {
        $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?")
            ->execute([$price, $_SESSION['user_id']]);
        $pdo->prepare("INSERT INTO transactions_data (user_id, network, phone, plan, amount) VALUES (?, ?, ?, ?, ?)")
            ->execute([$_SESSION['user_id'], $network, $phone, $plan, $price]);

        $message = "✅ Data plan $plan on $network to $phone successful!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Buy Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="content">
    <h2>🌐 Data Top-up</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post">
        <select name="network" required>
            <option value="">--Select Network--</option>
            <option value="MTN">MTN SME 1GB - ₦250</option>
            <option value="GLO">GLO 1GB - ₦300</option>
            <option value="Airtel">Airtel 1.5GB - ₦500</option>
            <option value="9mobile">9mobile 1GB - ₦350</option>
        </select>
        <input type="text" name="phone" placeholder="Recipient Phone" required>
        <input type="hidden" name="plan" value="1GB">
        <input type="hidden" name="price" value="250">
        <button type="submit">Buy Data</button>
    </form>
</div>
</body>
</html>
