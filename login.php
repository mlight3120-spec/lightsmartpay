<?php
session_start();
$config = include __DIR__ . '/config.php';

$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
$pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid login details";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - LightSmartPay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f9f9f9; }
    .form-card {
      max-width: 420px;
      margin: 60px auto;
      padding: 30px;
      border-radius: 15px;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .form-card h2 { margin-bottom: 20px; }
  </style>
</head>
<body>
  <div class="form-card">
    <h2 class="text-center">🔑 Login</h2>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" id="loginpass" class="form-control" required>
          <button class="btn btn-outline-secondary" type="button" onclick="toggleLoginPass()">👁</button>
        </div>
      </div>
      <button type="submit" class="btn btn-success w-100">Login</button>
    </form>
    <p class="mt-3 text-center">Don’t have an account? <a href="register.php">Register</a></p>
  </div>

  <script>
    function toggleLoginPass() {
      const pass = document.getElementById('loginpass');
      pass.type = pass.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
