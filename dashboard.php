<?php
session_start();
require_once "config.php";

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT fullname, wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="brand">LightSmartPay</h2>
    <ul>
      <li><a href="dashboard.php">🏠 Dashboard</a></li>
      <li><a href="buydata.php">📡 Buy Data</a></li>
      <li><a href="buyairtime.php">📞 Buy Airtime</a></li>
      <li><a href="cable.php">📺 Cable TV</a></li>
      <li><a href="profile.php">⚙️ Profile</a></li>
      <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <header class="dashboard-header">
      <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?> 👋</h1>
    </header>

    <!-- Wallet Banner -->
    <section class="wallet-banner">
      <h2>Wallet Balance</h2>
      <p class="balance">₦<?php echo number_format($user['wallet_balance'], 2); ?></p>
      <a href="fundwallet.php" class="btn">+ Fund Wallet</a>
    </section>

    <!-- Quick Actions -->
    <section class="quick-actions">
      <h2>Quick Actions</h2>
      <div class="actions-grid">
        <a href="buydata.php" class="card">📡 Buy Data</a>
        <a href="buyairtime.php" class="card">📞 Buy Airtime</a>
        <a href="cable.php" class="card">📺 Cable TV</a>
        <a href="transactions_data.php" class="card">📜 Data History</a>
        <a href="transactions_airtime.php" class="card">📜 Airtime History</a>
      </div>
    </section>
  </main>
</body>
</html>
