<?php
session_start();
require "dp.php";

// If user never login, redirect back to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$stmt = $pdo->prepare("SELECT full_name, email, wallet_balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // If user not found, logout them
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body {
          background-color: #f8f9fa;
      }
      .sidebar {
          height: 100vh;
          background: #343a40;
          padding-top: 20px;
          position: fixed;
          top: 0;
          left: 0;
          width: 250px;
      }
      .sidebar a {
          color: #fff;
          padding: 10px;
          text-decoration: none;
          display: block;
      }
      .sidebar a:hover {
          background: #495057;
      }
      .content {
          margin-left: 260px;
          padding: 20px;
      }
  </style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-center text-white">My Dashboard</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="airtime.php">📱 Airtime</a>
    <a href="data.php">🌐 Data</a>
    <a href="pins.php">🎟️ E-Pins</a>
    <a href="transactions.php">💳 Transactions</a>
    <a href="profile.php">👤 Profile</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?> 👋</h2>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <h4>Wallet Balance: ₦<?php echo number_format($user['wallet_balance'], 2); ?></h4>
    <hr>
    <p>Select a service from the sidebar to continue.</p>
</div>

</body>
</html>
