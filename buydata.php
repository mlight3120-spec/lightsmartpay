<?php
require "db.php";
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $network = $_POST['network'];
    $plan = $_POST['plan'];
    $phone = $_POST['phone'];

    $stmt = $pdo->prepare("INSERT INTO data (user_id, network, plan, phone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $network, $plan, $phone]);

    $pdo->prepare("INSERT INTO transactions (user_id, service, details, amount) VALUES (?, 'data', ?, 0)")
        ->execute([$_SESSION['user_id'], "$network - $plan - $phone"]);

    $success = "Data request submitted!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Bundle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="content">
    <h2>Data Bundle</h2>
    <?php if (!empty($success)) echo "<p style='color:green'>$success</p>"; ?>
    <form method="post">
        <input type="text" name="network" placeholder="Network" required>
        <input type="text" name="plan" placeholder="Plan (e.g 1GB)" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <button type="submit">Buy Data</button>
    </form>
</div>
</body>
</html>
