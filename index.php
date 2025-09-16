<?php
// index.php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>LightSmartPay</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="center-box">
  <h1>Welcome to LightSmartPay</h1>

  <div class="card">
    <h3>Login</h3>
    <form method="post" action="login.php">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</div>
</body>
</html>
