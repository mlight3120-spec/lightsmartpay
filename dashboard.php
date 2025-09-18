<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT fullname, wallet_balance FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🏠 Dashboard - LightSmartPay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f5f6fa; }
    .card { border-radius:15px; }
    .wallet { font-size:1.5rem; font-weight:bold; }
  </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">💡 LightSmartPay</a>
    <div class="d-flex">
      <span class="navbar-text me-3 text-light">Hi, <?php echo htmlspecialchars($user['fullname']); ?></span>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard Content -->
<div class="container my-5">
  <div class="row">
    <div class="col-md-4">
      <div class="card shadow text-center p-4">
        <p class="mb-1">Wallet Balance</p>
        <p class="wallet text-success">₦<?php echo number_format($user['wallet_balance'],2); ?></p>
        <a href="#" class="btn btn-primary btn-sm mt-2">💳 Fund Wallet</a>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card shadow p-4">
        <h5 class="mb-3">📌 Quick Actions</h5>
        <div class="d-grid gap-3">
          <a href="#" class="btn btn-outline-primary">📱 Buy Data</a>
          <a href="#" class="btn btn-outline-success">📞 Buy Airtime</a>
          <a href="#" class="btn btn-outline-warning">💡 Pay Bills</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
