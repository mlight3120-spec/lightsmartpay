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

// Credit/Debit actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $user_id = (int)$_POST['user_id'];
    $amount = (float)$_POST['amount'];
    if ($action === 'credit') {
        $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?")->execute([$amount, $user_id]);
    } elseif ($action === 'debit') {
        $pdo->prepare("UPDATE users SET wallet_balance = GREATEST(wallet_balance - ?, 0) WHERE id = ?")->execute([$amount, $user_id]);
    }
}

$users = $pdo->query("SELECT id, full_name, email, wallet_balance, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Users</h3>
    <a href="dashboard.php" class="btn btn-outline-secondary">⬅ Back</a>
  </div>

  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Balance</th><th>Joined</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo htmlspecialchars($u['full_name']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td>₦<?php echo number_format($u['wallet_balance'],2); ?></td>
        <td><?php echo $u['created_at']; ?></td>
        <td>
          <button class="btn btn-sm btn-success" onclick="openModal(<?php echo $u['id']; ?>,'credit')">Credit</button>
          <button class="btn btn-sm btn-danger" onclick="openModal(<?php echo $u['id']; ?>,'debit')">Debit</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Modal (simple) -->
  <div id="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);">
    <div style="width:420px; margin:80px auto; background:#fff; border-radius:8px; padding:20px;">
      <h5 id="modal-title">Action</h5>
      <form method="post">
        <input type="hidden" name="user_id" id="m_user">
        <input type="hidden" name="action" id="m_action">
        <div class="mb-3">
          <label>Amount (₦)</label>
          <input type="number" step="0.01" name="amount" id="m_amount" class="form-control" required>
        </div>
        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </div>
      </form>
    </div>
  </div>

<script>
function openModal(id,action){
  document.getElementById('modal').style.display='block';
  document.getElementById('m_user').value = id;
  document.getElementById('m_action').value = action;
  document.getElementById('modal-title').innerText = action === 'credit' ? 'Credit User Wallet' : 'Debit User Wallet';
}
function closeModal(){
  document.getElementById('modal').style.display='none';
}
</script>

</body>
</html>
