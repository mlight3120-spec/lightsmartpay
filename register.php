<?php
// register.php
session_start();
include 'config.php'; // config.php must create $pdo (PDO)

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a valid email.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        try {
            // check existing
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $error = "Email already registered.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, wallet_balance, created_at) VALUES (:fullname, :email, :pass, 0, NOW())");
                $stmt->execute([
                    ':fullname' => $fullname,
                    ':email' => $email,
                    ':pass' => $hash
                ]);
                header("Location: login.php?registered=1");
                exit;
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - LightSmartPay</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="center-box">
  <div class="card">
    <h2>Create Account</h2>
    <?php if ($error): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif; ?>
    <form method="post" action="register.php">
      <input type="text" name="fullname" placeholder="Full name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password (min 6)" required>
      <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Login</a></p>
  </div>
</div>
</body>
</html>
