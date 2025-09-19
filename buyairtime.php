<?php
require "db.php";
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $network = $_POST['network'];
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];

    $stmt = $pdo->prepare("INSERT INTO airtime (user_id, network, phone, amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $network, $phone, $amount]);

    $pdo->prepare("INSERT INTO transactions (user_id, service, details, amount) VALUES (?, 'airtime', ?, ?)")
        ->execute([$_SESSION['user_id'], "$network - $phone", $amount]);

    $success = "Airtime request submitted!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Airtime</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="content">
    <h2>Airtime Topup</h2>
    <?php if (!empty($success)) echo "<p style='color:green'>$success</p>"; ?>
    <form method="post">
        <input type="text" name="network" placeholder="Network (e.g MTN)" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="number" name="amount" placeholder="Amount" required>
        <button type="submit">Buy Airtime</button>
    </form>
</div>
</body>
</html>
