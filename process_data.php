<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $network = $_POST['network'] ?? '';
    $type = $_POST['type'] ?? '';
    $plan = $_POST['plan'] ?? '';
    $phone = $_POST['phone'] ?? '';

    echo "<h2>✅ Data Purchase Successful</h2>";
    echo "<p>Network: $network</p>";
    echo "<p>Type: $type</p>";
    echo "<p>Plan: $plan</p>";
    echo "<p>Phone: $phone</p>";
    echo "<a href='dashboard.php'>⬅ Back to Dashboard</a>";
} else {
    header("Location: buydata.php");
    exit;
}
