<?php
session_start();
$config = include __DIR__ . '/../config.php';
$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
$pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // adjust admin check as you prefer (email or admin flag in users table)
    $stmt = $pdo->prepare("SELECT id, password, is_admin FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $admin['is_admin'] == true && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials";
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Login - LightSmartPay</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container" style="max-width:420px;margin-top:80px">
  <div class="card shadow">
    <div class="card-body">
      <h4 class="mb-3">Admin Login</h4>
      <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3"><input name="email" class="form-control" placeholder="Admin email" required></div>
        <div class="mb-3"><input name="password" type="password" class="form-control" placeholder="Password" required></div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
