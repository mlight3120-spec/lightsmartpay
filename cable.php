<?php
require "db.php";
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $provider = $_POST['provider'];
    $smartcard = $_POST['smartcard'];
    $plan = $_POST['plan'];

    $stmt = $pdo->prepare("INSERT INTO cable (user_id, provider, smartcard, plan) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $provider, $smartcard, $plan]);

    $pdo->prepare("INSERT INTO transactions (user_id, service, details, amount) VALUES (?, 'cable', ?, 0)")
        ->execute([$_SESSION['user_id'], "$provider - $plan - $smartcard"]);

    $success = "Cable request submitted!";
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
    <h2>Cable Subscription</h2>
    <?php if (!empty($success)) echo "<p style='color:green'>$success</p>"; ?>
    <form method="post">
        <input type="text" name="provider" placeholder="Provider (e.g DSTV)" required>
        <input type="text" name="smartcard" placeholder="Smartcard Number" required>
        <input type="text" name="plan" placeholder="Plan (e.g Compact)" required>
        <button type="submit">Subscribe</button>
    </form>
</div>
</body>
</html>
