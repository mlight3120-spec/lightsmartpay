<?php
require 'config.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullname, $email, $password]);
        header("Location: login.php?success=1");
        exit;
    } catch (PDOException $e) {
        $error = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - LightSmartPay</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <div class="logo">
            <img src="logo.png" alt="LightSmartPay Logo">
            <h2>Create Account</h2>
        </div>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group password-wrapper">
                <label>Password</label>
                <input type="password" name="password" id="regPass" required minlength="6">
                <span class="toggle-password" onclick="togglePassword('regPass')">👁</span>
            </div>

            <button type="submit">Register</button>
        </form>

        <p class="switch">Already have an account? <a href="login.php">Login</a></p>
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
