<?php
include 'dp.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$amount, $user_id]);

    $message = "✅ Wallet funded with ₦$amount successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fund Wallet - LightSmartPay</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>💰 Fund Wallet</h2>
    <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>
    <form method="POST">
        <label>Enter Amount:</label>
        <input type="number" name="amount" required>
        <button type="submit">Fund Wallet</button>
    </form>
</div>
</body>
</html>
