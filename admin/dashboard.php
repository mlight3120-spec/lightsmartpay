<?php
// admin/dashboard.php
session_start();

// require config-based DB connection
$config = include __DIR__ . '/../config.php';
$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";

try {
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("DB connection failed: " . $e->getMessage());
}

// --- AUTH: ensure admin logged in ---
// Expect that create_admin_user.php created a user with is_admin = true and that your app's login sets $_SESSION['user_id'].
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

// Verify that current user is admin
$stmt = $pdo->prepare("SELECT id, email, full_name, is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$current || !$current['is_admin']) {
    echo "⛔ Access denied. Admin only.";
    exit;
}

// --- Handle admin credit/debit actions (POST) ---
$actionMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_action'])) {
    $admin_action = $_POST['admin_action'];
    $target_id = (int) ($_POST['target_user_id'] ?? 0);
    $amount = (float) ($_POST['amount'] ?? 0);

    if ($target_id > 0 && $amount > 0) {
        if ($admin_action === 'credit') {
            $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?")
                ->execute([$amount, $target_id]);

            // record transaction
            $pdo->prepare("INSERT INTO transactions (user_id, service, amount, status, created_at) VALUES (?, 'admin_credit', ?, 'success', NOW())")
                ->execute([$target_id, $amount]);

            $actionMsg = "✅ Credited ₦" . number_format($amount,2) . " to user #{$target_id}.";
        } elseif ($admin_action === 'debit') {
            $pdo->prepare("UPDATE users SET wallet_balance = GREATEST(wallet_balance - ?, 0) WHERE id = ?")
                ->execute([$amount, $target_id]);

            $pdo->prepare("INSERT INTO transactions (user_id, service, amount, status, created_at) VALUES (?, 'admin_debit', ?, 'success', NOW())")
                ->execute([$target_id, $amount]);

            $actionMsg = "✅ Debited ₦" . number_format($amount,2) . " from user #{$target_id}.";
        }
    } else {
        $actionMsg = "⚠️ Invalid action or amount.";
    }
}

// --- Filters & search ---
$searchUser = trim($_GET['q'] ?? '');
$serviceFilter = trim($_GET['service'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

// --- Top stats ---
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalTx = (int)$pdo->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$totalCommission = (float)$pdo->query("SELECT COALESCE(SUM(amount),0) FROM commissions")->fetchColumn();

// --- Users list (searchable) ---
$params = [];
$where = "1=1";
if ($searchUser !== '') {
    $where .= " AND (email ILIKE :q OR full_name ILIKE :q)";
    $params[':q'] = '%' . $searchUser . '%';
}
$userSql = "SELECT id, full_name, email, wallet_balance, virtual_account, created_at FROM users WHERE $where ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($userSql);
foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// total matching users for pagination
$countSql = "SELECT COUNT(*) FROM users WHERE $where";
$stmtc = $pdo->prepare($countSql);
foreach ($params as $k=>$v) $stmtc->bindValue($k, $v);
$stmtc->execute();
$totalMatchingUsers = (int)$stmtc->fetchColumn();
$totalPages = max(1, ceil($totalMatchingUsers / $perPage));

// --- Recent transactions (filterable by service) ---
$txParams = [];
$txWhere = "1=1";
if ($serviceFilter !== '') {
    $txWhere .= " AND service = :svc";
    $txParams[':svc'] = $serviceFilter;
}
$txSql = "SELECT t.id, t.user_id, u.email, t.service, t.amount, t.status, t.created_at
          FROM transactions t
          LEFT JOIN users u ON u.id = t.user_id
          WHERE $txWhere
          ORDER BY t.created_at DESC
          LIMIT 30";
$st = $pdo->prepare($txSql);
foreach ($txParams as $k=>$v) $st->bindValue($k, $v);
$st->execute();
$recentTx = $st->fetchAll(PDO::FETCH_ASSOC);

// simple CSRF token for admin actions (basic)
if (!isset($_SESSION['admin_csrf'])) $_SESSION['admin_csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['admin_csrf'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard — Evylite / LightSmartPay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f4f6f9; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .sidebar { width:240px; position:fixed; height:100vh; background:#0f172a; color:#fff; padding:24px; }
    .main { margin-left:260px; padding:28px; }
    .small-muted { color:#6b7280; font-size:0.9rem; }
    .user-row:hover { background:#fff8; }
    .stat-card { border-radius:12px; }
    .table-sm td, .table-sm th { padding: .45rem .6rem; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h4>Evylite — Admin</h4>
    <p class="small-muted">Signed in as: <?php echo htmlspecialchars($current['email']); ?></p>
    <hr style="border-color:#1f2937">
    <a class="d-block text-white mb-2" href="/admin/dashboard.php">Dashboard</a>
    <a class="d-block text-white mb-2" href="/admin/dashboard.php?service=">All Transactions</a>
    <a class="d-block text-white mb-2" href="/admin/dashboard.php?service=fund_wallet">Fund Wallet</a>
    <a class="d-block text-white mb-2" href="/admin/dashboard.php?service=airtime">Airtime</a>
    <a class="d-block text-white mb-2" href="/admin/dashboard.php?service=data">Data</a>
    <a class="d-block text-white mb-2" href="/admin/dashboard.php?service=cable">Cable</a>
    <hr style="border-color:#1f2937">
    <a class="d-block text-white" href="/logout.php">Logout</a>
  </div>

  <div class="main">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Admin Dashboard</h3>
      <div class="text-end small-muted">
        <?php echo date('l, d M Y H:i'); ?><br>
        Total Users: <strong><?php echo number_format($totalUsers); ?></strong>
      </div>
    </div>

    <?php if ($actionMsg): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($actionMsg); ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <div class="p-3 bg-white stat-card shadow-sm">
          <div class="small-muted">Total Users</div>
          <h4><?php echo number_format($totalUsers); ?></h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 bg-white stat-card shadow-sm">
          <div class="small-muted">Total Transactions</div>
          <h4><?php echo number_format($totalTx); ?></h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 bg-white stat-card shadow-sm">
          <div class="small-muted">Commission (₦)</div>
          <h4>₦<?php echo number_format($totalCommission,2); ?></h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-3 bg-white stat-card shadow-sm">
          <div class="small-muted">Admin</div>
          <h4><?php echo htmlspecialchars($current['full_name'] ?? $current['email']); ?></h4>
        </div>
      </div>
    </div>

    <!-- Search / Filters -->
    <div class="card mb-3 p-3">
      <form class="row g-2" method="get" action="dashboard.php">
        <div class="col-md-6">
          <input name="q" value="<?php echo htmlspecialchars($searchUser); ?>" class="form-control" placeholder="Search users by name or email...">
        </div>
        <div class="col-md-3">
          <select name="service" class="form-control">
            <option value="">All services</option>
            <option value="fund_wallet" <?php if($serviceFilter==='fund_wallet') echo 'selected'; ?>>Fund Wallet</option>
            <option value="data" <?php if($serviceFilter==='data') echo 'selected'; ?>>Data</option>
            <option value="airtime" <?php if($serviceFilter==='airtime') echo 'selected'; ?>>Airtime</option>
            <option value="cable" <?php if($serviceFilter==='cable') echo 'selected'; ?>>Cable</option>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-primary w-100">Search</button>
          <a class="btn btn-outline-secondary" href="dashboard.php">Reset</a>
        </div>
      </form>
    </div>

    <div class="row g-3">
      <!-- LEFT: Users list -->
      <div class="col-lg-7">
        <div class="card p-0">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Users (<?php echo number_format($totalMatchingUsers); ?>)</strong>
            <small class="text-muted">Page <?php echo $page; ?> / <?php echo $totalPages; ?></small>
          </div>
          <div class="table-responsive" style="max-height:540px; overflow:auto;">
            <table class="table table-sm mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th><th>Name</th><th>Email</th><th>Balance</th><th>VA</th><th>Joined</th><th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $u): ?>
                  <tr class="user-row">
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td>₦<?php echo number_format($u['wallet_balance'],2); ?></td>
                    <td><?php echo htmlspecialchars($u['virtual_account'] ?? '-'); ?></td>
                    <td><?php echo $u['created_at']; ?></td>
                    <td>
                      <button class="btn btn-sm btn-info" onclick="viewUser(<?php echo $u['id']; ?>)">View</button>
                      <button class="btn btn-sm btn-success" onclick="openAdminAction(<?php echo $u['id']; ?>,'credit')">Credit</button>
                      <button class="btn btn-sm btn-danger" onclick="openAdminAction(<?php echo $u['id']; ?>,'debit')">Debit</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="card-footer">
            <!-- pagination -->
            <nav>
              <ul class="pagination pagination-sm mb-0">
                <?php for ($p=1;$p<=$totalPages;$p++): ?>
                  <li class="page-item <?php if($p==$page) echo 'active'; ?>"><a class="page-link" href="?q=<?php echo urlencode($searchUser); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a></li>
                <?php endfor; ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>

      <!-- RIGHT: Transactions -->
      <div class="col-lg-5">
        <div class="card p-3 mb-3">
          <h5>Recent Transactions</h5>
          <div style="max-height:420px; overflow:auto;">
            <table class="table table-sm">
              <thead class="table-light"><tr><th>#</th><th>User</th><th>Service</th><th>Amt</th><th>Status</th></tr></thead>
              <tbody>
                <?php foreach ($recentTx as $r): ?>
                  <tr>
                    <td><?php echo $r['id']; ?></td>
                    <td><?php echo htmlspecialchars($r['email']); ?></td>
                    <td><?php echo htmlspecialchars($r['service']); ?></td>
                    <td>₦<?php echo number_format($r['amount'],2); ?></td>
                    <td><?php echo htmlspecialchars($r['status']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card p-3">
          <h6>Quick Actions</h6>
          <p class="small-muted">Use the buttons in the users table to credit or debit wallets. Every action recorded in transactions table.</p>
          <form method="post" onsubmit="return confirm('Are you sure?');">
            <input type="hidden" name="admin_action" id="admin_action">
            <input type="hidden" name="target_user_id" id="target_user_id">
            <input type="hidden" name="csrf" value="<?php echo $csrf; ?>">
            <div class="mb-2"><input step="0.01" name="amount" id="admin_amount" class="form-control" placeholder="Amount (₦)"></div>
            <div class="d-grid gap-2">
              <button type="submit" onclick="document.getElementById('admin_action').value='credit';" class="btn btn-success">Credit</button>
              <button type="submit" onclick="document.getElementById('admin_action').value='debit';" class="btn btn-danger">Debit</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

  <!-- user modal -->
  <div id="userModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5);">
    <div style="max-width:920px; margin:60px auto; background:#fff; border-radius:10px; padding:18px;">
      <div id="userModalBody">Loading…</div>
      <div class="text-end mt-3"><button class="btn btn-secondary" onclick="closeUserModal()">Close</button></div>
    </div>
  </div>

<script>
function viewUser(id){
  // fetch user details via AJAX to a small endpoint (admin/user_view.php)
  fetch('/admin/user_view.php?id=' + id).then(r=>r.text()).then(html=>{
    document.getElementById('userModalBody').innerHTML = html;
    document.getElementById('userModal').style.display='block';
  }).catch(e=>{
    alert('Could not load user details.');
  });
}
function closeUserModal(){ document.getElementById('userModal').style.display='none'; }
function openAdminAction(id,act){
  document.getElementById('target_user_id').value = id;
  document.getElementById('admin_action').value = act;
  document.getElementById('admin_amount').focus();
}
</script>
</body>
</html>
