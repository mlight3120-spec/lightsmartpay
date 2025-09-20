<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $provider = $_POST['provider'] ?? '';
    $package = $_POST['package'] ?? '';
    $card_number = $_POST['card_number'] ?? '';

    echo "<h2>✅ Cable Subscription Successful</h2>";
    echo "<p>Provider: $provider</p>";
    echo "<p>Package: $package</p>";
    echo "<p>Card Number: $card_number</p>";
    echo "<a href='dashboard.php'>⬅ Back to Dashboard</a>";
} else {
    header("Location: cable.php");
    exit;
}
