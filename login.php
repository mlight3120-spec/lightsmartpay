<?php
// login.php
session_start();
include 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Enter email and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid credentials.";
            }
        } catch (Exception $e) {
            $error = "DB error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - LightSmartPay</title>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="center-box">
  <div class="card">
    <h2>Login</h2>
    <?php if ($error): ?><p class="error"><?=htmlspecialchars($error)?></p><?php endif; ?>
    <?php if (isset($_GET['registered'])): ?><p class="success">Registration successful. Please login.</p><?php endif; ?>
    <form method="post" action="login.php">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p><a href="register.php">Create an account</a></p>
  </div>
</div>
</body>
</html>
