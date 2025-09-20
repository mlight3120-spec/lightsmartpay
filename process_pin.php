<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = $_POST['pin'] ?? '';

    echo "<h2>✅ PIN Set Successfully</h2>";
    echo "<p>Your transaction PIN has been saved (dummy for now).</p>";
    echo "<a href='dashboard.php'>⬅ Back to Dashboard</a>";
} else {
    header("Location: set_pin.php");
    exit;
}
