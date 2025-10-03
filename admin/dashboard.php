<?php
session_start();
$config = include __DIR__ . '/../config.php';
$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
$pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// stats
$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$txCount = $pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$commissionSum = $pdo->query("SELECT COALESCE(SUM(amount),0) FROM commissions")->fetchColumn();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container-fluid p-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Admin Dashboard</h3>
    <div><a href="users.php" class="btn btn-outline-primary">Manage Users</a> <a href="logout.php" class="btn btn-danger">Logout</a></div>
  </div>

  <div class="row g-3">
    <div class="col-md-3"><div class="p-3 bg-white shadow-sm rounded"><h5>Total Users</h5><h2><?php echo number_format($userCount); ?></h2></div></div>
    <div class="col-md-3"><div class="p-3 bg-white shadow-sm rounded"><h5>Total Transactions</h5><h2><?php echo number_format($txCount); ?></h2></div></div>
    <div class="col-md-3"><div class="p-3 bg-white shadow-sm rounded"><h5>Commission (₦)</h5><h2>₦<?php echo number_format($commissionSum,2); ?></h2></div></div>
  </div>

  <div class="mt-4">
    <h5>Recent Transactions</h5>
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>User</th><th>Service</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
        <?php
        $stmt = $pdo->query("SELECT t.id, u.email, t.service, t.amount, t.status, t.created_at FROM transactions t LEFT JOIN users u ON u.id = t.user_id ORDER BY t.created_at DESC LIMIT 10");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['service']); ?></td>
          <td>₦<?php echo number_format($row['amount'],2); ?></td>
          <td><?php echo htmlspecialchars($row['status']); ?></td>
          <td><?php echo $row['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
