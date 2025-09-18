<?php
session_start();
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, wallet_balance) VALUES (?, ?, ?, 0)");
        $stmt->execute([$fullname, $email, $password]);
        $message = "✅ Account created successfully. Please login.";
    } catch (PDOException $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🚀 Create Account - LightSmartPay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .card { border-radius:15px; }
    .password-toggle { cursor:pointer; }
  </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="max-width:400px; width:100%;">
    <h3 class="text-center mb-3">🚀 Create Account</h3>
    <?php if($message): ?>
      <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full name</label>
        <input type="text" name="fullname" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3 position-relative">
        <label class="form-label">Password (min 6)</label>
        <input type="password" id="password" name="password" class="form-control" minlength="6" required>
        <span class="position-absolute top-50 end-0 translate-middle-y me-3 password-toggle">👁</span>
      </div>
      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <p class="text-center mt-3">Already registered? <a href="login.php">Login</a></p>
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
