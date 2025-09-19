<?php
session_start();
$pdo = include "config.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "❌ Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - LightSmartPay</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>🔐 Login</h2>
    <?php if ($message): ?><p class="error"><?php echo $message; ?></p><?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span onclick="togglePass()">👁</span>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register</a></p>
</div>
<script>
function togglePass() {
  var x = document.getElementById("password");
  x.type = (x.type === "password") ? "text" : "password";
}
</script>
</body>
</html>
