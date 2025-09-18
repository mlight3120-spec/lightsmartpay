<?php
require 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, wallet_balance) VALUES (?, ?, ?, 0)");
        $stmt->execute([$fullname, $email, $password]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body">

<div class="auth-container">
  <h1 class="brand">LightSmartPay</h1>
  <div class="auth-card">
    <h2>Create Account</h2>
    <?php if ($message): ?>
      <p class="error"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Full name</label>
        <input type="text" name="fullname" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group password-group">
        <label>Password (min 6)</label>
        <input type="password" name="password" id="password" minlength="6" required>
        <span class="toggle-password" onclick="togglePassword()">👁</span>
      </div>

      <button type="submit" class="btn-primary">Register</button>
    </form>
    <p class="switch-auth">Already registered? <a href="login.php">Login</a></p>
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
