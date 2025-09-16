<?php
// navbar.php - simple top navbar, include this at top of pages
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<div class="topbar">
  <div class="brand"><a href="index.php">LightSmartPay</a></div>
  <div class="navlinks">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="fundwallet.php">Fund Wallet</a>
      <a href="profile.php">Profile</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="index.php">Home</a>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </div>
</div>
