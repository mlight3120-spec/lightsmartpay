<?php
require 'config.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT id, fullname, password FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["fullname"] = $user["fullname"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - LightSmartPay</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <div class="logo">
            <img src="logo.png" alt="LightSmartPay Logo">
            <h2>Login</h2>
        </div>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group password-wrapper">
                <label>Password</label>
                <input type="password" name="password" id="loginPass" required>
                <span class="toggle-password" onclick="togglePassword('loginPass')">👁</span>
            </div>

            <button type="submit">Login</button>
        </form>

        <p class="switch">Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<script>
function togglePassword(id) {
    let input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
