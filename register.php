<?php
require 'config.php';
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account | LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="form-container">
    <h2>🚀 Create Account</h2>
    <?php if (!empty($message)): ?>
      <p class="error"><?= $message ?></p>
    <?php endif; ?>
    <form method="POST">
      <div class="input-group">
        <label>Full name</label>
        <input type="text" name="fullname" required>
      </div>
      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="input-group">
        <label>Password (min 6)</label>
        <div class="password-wrapper">
          <input type="password" name="password" id="password" required minlength="6">
          <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>
      </div>
      <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Login</a></p>
  </div>

  <script>
    function togglePassword() {
      const pass = document.getElementById("password");
      pass.type = (pass.type === "password") ? "text" : "password";
    }
  </script>
</body>
</html>
