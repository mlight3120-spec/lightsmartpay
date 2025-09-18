<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <img src="logo.png" alt="LightSmartPay Logo" class="logo">
      <h1>Welcome to <span class="brand">LightSmartPay</span></h1>
      <p class="tagline">Smart, Fast & Secure Payments for Data, Airtime, and Bills</p>
    </header>

    <nav class="nav">
      <a href="login.php" class="btn">Login</a>
      <a href="register.php" class="btn secondary">Register</a>
    </nav>
  </div>

  <footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> LightSmartPay. All rights reserved.</p>
  </footer>
</body>
</html>
