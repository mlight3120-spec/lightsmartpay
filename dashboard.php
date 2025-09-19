<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$config = include __DIR__ . '/config.php';

try {
    $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("❌ DB connection failed: " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT full_name, wallet_balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - LightSmartPay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f9f9f9; }
    .card-action {
        transition: 0.3s;
        cursor: pointer;
    }
    .card-action:hover {
        transform: scale(1.05);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">💡 LightSmartPay</a>
    <div>
      <span class="text-white me-3">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard -->
<div class="container py-5">
  <div class="row mb-4">
    <div class="col-12">
      <div class="card shadow-sm p-4">
        <h4 class="mb-2">Wallet Balance</h4>
        <h2 class="text-success">₦<?php echo number_format($user['wallet_balance'], 2); ?></h2>
        <button class="btn btn-sm btn-success mt-2">+ Fund Wallet</button>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card card-action shadow-sm text-center p-4">
        <h5>📶 Buy Data</h5>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-action shadow-sm text-center p-4">
        <h5>📱 Airtime</h5>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-action shadow-sm text-center p-4">
        <h5>📺 Cable TV</h5>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-action shadow-sm text-center p-4">
        <h5>💡 Electricity</h5>
      </div>
    </div>
  </div>
</div>

</body>
</html>
