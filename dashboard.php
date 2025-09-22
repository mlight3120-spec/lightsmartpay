<?php
session_start();
require __DIR__ . "/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get wallet balance
$stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get recent transactions
$stmt = $pdo->prepare("SELECT service, amount, status, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f4f7fa;
    }
    .sidebar {
      width: 230px;
      background: #1e293b;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px 0;
      color: #fff;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 22px;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #fff;
      text-decoration: none;
      font-weight: bold;
    }
    .sidebar a:hover {
      background: #334155;
    }
    .main {
      margin-left: 230px;
      padding: 20px;
    }
    .wallet-card {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      color: #fff;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      margin-bottom: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .wallet-card h1 {
      font-size: 42px;
      margin: 0;
      font-weight: bold;
    }
    .wallet-card p {
      margin: 5px 0 15px;
      font-size: 18px;
    }
    .btn {
      display: inline-block;
      padding: 12px 20px;
      font-size: 16px;
      font-weight: bold;
      color: #2563eb;
      background: #fff;
      border-radius: 6px;
      text-decoration: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .btn:hover {
      background: #f1f5f9;
    }
    .transactions {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .transactions h2 {
      margin-bottom: 15px;
      font-size: 20px;
      color: #1e293b;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table th, table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
    }
    table th {
      background: #f1f5f9;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Dashboard</h2>
    <a href="dashboard.php">🏠 Home</a>
    <a href="fund_wallet.php">💳 Fund Wallet</a>
    <a href="buy_airtime.php">📱 Buy Airtime</a>
    <a href="buy_data.php">🌐 Buy Data</a>
    <a href="cable.php">📺 Cable Subscription</a>
    <a href="pins.php">🔑 Pin Setup</a>
    <a href="profile.php">👤 Profile</a>
    <a href="logout.php">🚪 Logout</a>
  </div>

  <div class="main">
    <div class="wallet-card">
      <p>Wallet Balance</p>
      <h1>₦<?php echo number_format($user['wallet_balance'], 2); ?></h1>
      <a href="fund_wallet.php" class="btn">+ Fund Wallet</a>
    </div>

    <div class="transactions">
      <h2>Recent Transactions</h2>
      <table>
        <thead>
          <tr>
            <th>Service</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($transactions): ?>
            <?php foreach ($transactions as $txn): ?>
              <tr>
                <td><?php echo htmlspecialchars($txn['service']); ?></td>
                <td>₦<?php echo number_format($txn['amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($txn['status']); ?></td>
                <td><?php echo $txn['created_at']; ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4">No transactions found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
