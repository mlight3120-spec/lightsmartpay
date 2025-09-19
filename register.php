<?php
$pdo = include "config.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        header("Location: login.php");
        exit;
    } catch (Exception $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - LightSmartPay</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <h2>🚀 Create Account</h2>
    <?php if ($message): ?><p class="error"><?php echo $message; ?></p><?php endif; ?>
    <form method="POST">
        <input type="text" name="full_name" placeholder="Full name" required>
        <input type="email" name="email" placeholder="Email" required>
        <div class="password-wrapper">
            <input type="password" id="password" name="password" placeholder="Password (min 6)" required>
            <span onclick="togglePass()">👁</span>
        </div>
        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Login</a></p>
</div>
<script>
function togglePass() {
  var x = document.getElementById("password");
  x.type = (x.type === "password") ? "text" : "password";
}
</script>
</body>
</html>
