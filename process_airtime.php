<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $network = $_POST['network'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $phone = $_POST['phone'] ?? '';

    echo "<h2>✅ Airtime Purchase Successful</h2>";
    echo "<p>Network: $network</p>";
    echo "<p>Amount: ₦$amount</p>";
    echo "<p>Phone: $phone</p>";
    echo "<a href='dashboard.php'>⬅ Back to Dashboard</a>";
} else {
    header("Location: buyairtime.php");
    exit;
}
