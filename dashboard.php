<?php
session_start();
$config = include __DIR__ . "/config.php";

// ✅ DB Connection
$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
$pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Fetch user info
$stmt = $pdo->prepare("SELECT full_name, wallet_balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Recent Transactions (last 5)
$tx = $pdo->prepare("SELECT service, amount, status, created_at 
                     FROM transactions 
                     WHERE user_id = ? 
                     ORDER BY created_at DESC LIMIT 5");
$tx->execute([$_SESSION['user_id']]);
$transactions = $tx->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - LightSmartPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }
        .sidebar {
            height: 100vh;
            background: #1a1a2e;
            color: #fff;
            padding-top: 30px;
            position: fixed;
            width: 250px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            margin: 5px 0;
            border-radius: 8px;
        }
        .sidebar a:hover {
            background: #16213e;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
        }
        .wallet-banner {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
        }
        .wallet-banner h1 {
            font-size: 50px;
            margin: 0;
        }
        .card {
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center mb-4">💡 LightSmartPay</h3>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="buyairtime.php">📱 Buy Airtime </a>
        <a href="buydata.php">🌐 Buy Data </a>
        <a href="cable.php">📺 Cable Subscription</a>
        <a href="transactions_airtime.php">📑 Airtime Transactions</a>
        <a href="transactions_data.php">📑 Data Transactions</a>
        <a href="transactions_cable.php">📑 Cable Transactions</a>
        <a href="profile.php">👤 Profile</a>
        <a href="set_pin.php">🔑 Setup PIN</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Wallet Banner -->
        <div class="wallet-banner shadow">
            <h4>Welcome, <?php echo htmlspecialchars($user['full_name']); ?> 👋</h4>
            <h1>₦<?php echo number_format($user['wallet_balance'], 2); ?></h1>
            <p>Your Wallet Balance</p>
        </div>

        <!-- Wallet Summary -->
        <div class="card shadow p-4">
            <h4 class="mb-3">📊 Wallet Summary (Recent Transactions)</h4>
            <table class="table table-bordered table-striped">
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
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td><?php echo ucfirst($t['service']); ?></td>
                                <td>₦<?php echo number_format($t['amount'], 2); ?></td>
                                <td><?php echo ucfirst($t['status']); ?></td>
                                <td><?php echo $t['created_at']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No transactions yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
