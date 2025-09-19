<?php
session_start();
$config = include __DIR__ . '/config.php';

$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
$pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, wallet_balance) VALUES (?, ?, ?, 0.00)");
    try {
        $stmt->execute([$full_name, $email, $password]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header("Location: dashboard.php");
        exit;
    } catch (PDOException $e) {
        $error = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - LightSmartPay</title>
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
    <h2 class="text-center">🚀 Create Account</h2>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full name</label>
        <input type="text" name="full_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" required>
          <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">👁</button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <p class="mt-3 text-center">Already registered? <a href="login.php">Login</a></p>
  </div>

  <script>
    function togglePassword() {
      const pass = document.getElementById('password');
      pass.type = pass.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
