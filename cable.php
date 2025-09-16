<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user balance
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$balance = $user['balance'];

// Handle cable subscription
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cable_type = $_POST['cable_type'];
    $smartcard_number = $_POST['smartcard_number'];
    $amount = $_POST['amount'];
    $pin = $_POST['pin'];

    // Verify PIN
    $stmt = $pdo->prepare("SELECT pin FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || $user['pin'] !== $pin) {
        $message = "❌ Invalid transaction PIN!";
    } elseif ($balance < $amount) {
        $message = "❌ Insufficient wallet balance!";
    } else {
        // Deduct balance
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);

        // Log transaction
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, network, phone, amount, status) VALUES (?, 'cable', ?, ?, ?, 'success')");
        $stmt->execute([$user_id, $cable_type, $smartcard_number, $amount]);

        $message = "✅ $cable_type subscription successful for Smartcard $smartcard_number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cable Subscription - LightSmartPay</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h2>📺 Cable TV Subscription</h2>

    <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

    <form method="POST">
        <label>Choose Cable Provider:</label>
        <select name="cable_type" required>
            <option value="DSTV">DSTV</option>
            <option value="GOTV">GOTV</option>
            <option value="STARTIMES">Startimes</option>
        </select>

        <label>Smartcard Number:</label>
        <input type="text" name="smartcard_number" required>

        <label>Amount:</label>
        <input type="number" name="amount" required>

        <label>Enter PIN:</label>
        <input type="password" name="pin" required>

        <button type="submit">Subscribe</button>
    </form>
</div>
</body>
</html>
