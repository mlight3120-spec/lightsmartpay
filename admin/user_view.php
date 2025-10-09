<?php
// admin/user_view.php
session_start();
$config = include __DIR__ . "/../config.php";
$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
$pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

if (!isset($_SESSION['user_id'])) { http_response_code(403); echo "Not allowed"; exit; }
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cur = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cur || !$cur['is_admin']) { http_response_code(403); echo "Access denied"; exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { echo "Invalid user"; exit; }

$user = $pdo->prepare("SELECT id, full_name, email, wallet_balance, virtual_account, created_at FROM users WHERE id = ?");
$user->execute([$id]);
$u = $user->fetch(PDO::FETCH_ASSOC);
if (!$u) { echo "User not found"; exit; }

$tx = $pdo->prepare("SELECT id, service, amount, status, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$tx->execute([$id]);
$txs = $tx->fetchAll(PDO::FETCH_ASSOC);
?>
<div>
  <h4><?php echo htmlspecialchars($u['full_name']); ?> <small class="text-muted">(<?php echo htmlspecialchars($u['email']); ?>)</small></h4>
  <p>Balance: <strong>₦<?php echo number_format($u['wallet_balance'],2); ?></strong></p>
  <p>Virtual Account: <?php echo htmlspecialchars($u['virtual_account'] ?? '-'); ?></p>
  <p>Joined: <?php echo $u['created_at']; ?></p>

  <h5 class="mt-3">Recent Transactions</h5>
  <table class="table table-sm">
    <thead><tr><th>#</th><th>Service</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
      <?php if ($txs): foreach ($txs as $t): ?>
        <tr>
          <td><?php echo $t['id']; ?></td>
          <td><?php echo htmlspecialchars($t['service']); ?></td>
          <td>₦<?php echo number_format($t['amount'],2); ?></td>
          <td><?php echo htmlspecialchars($t['status']); ?></td>
          <td><?php echo $t['created_at']; ?></td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="5">No transactions</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
