<?php
session_start();
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "❌ Invalid email or password.";
        }
    } catch (PDOException $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🔐 Login - LightSmartPay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#eef2f7; }
    .card { border-radius:15px; }
    .password-toggle { cursor:pointer; }
  </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="max-width:400px; width:100%;">
    <h3 class="text-center mb-3">🔐 Login</h3>
    <?php if($message): ?>
      <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3 position-relative">
        <label class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
        <span class="position-absolute top-50 end-0 translate-middle-y me-3 password-toggle">👁</span>
      </div>
      <button type="submit" class="btn btn-success w-100">Login</button>
    </form>
    <p class="text-center mt-3">Don’t have an account? <a href="register.php">Register here</a></p>
  </div>
</div>
<script>
document.querySelector('.password-toggle').addEventListener('click', function(){
  let pwd = document.getElementById('password');
  pwd.type = (pwd.type === 'password') ? 'text' : 'password';
});
</script>
</body>
</html>
