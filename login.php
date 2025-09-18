<?php
session_start();
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid login details!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body">

<div class="auth-container">
  <h1 class="brand">LightSmartPay</h1>
  <div class="auth-card">
    <h2>Login</h2>
    <?php if ($message): ?>
      <p class="error"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group password-group">
        <label>Password</label>
        <input type="password" name="password" id="password" required>
        <span class="toggle-password" onclick="togglePassword()">👁</span>
      </div>

      <button type="submit" class="btn-primary">Login</button>
    </form>
    <p class="switch-auth">Don’t have an account? <a href="register.php">Register here</a></p>
  </div>
</div>

<script>
function togglePassword() {
  let pwd = document.getElementById("password");
  pwd.type = pwd.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
