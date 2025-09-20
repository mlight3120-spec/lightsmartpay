<?php
session_start();
$config = include __DIR__ . "/config.php";

$dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
$pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, wallet_balance) VALUES (?, ?, ?, 50)");
        $stmt->execute([$full_name, $email, $password]);
        $message = "✅ Registration successful! Please login.";
    } catch (PDOException $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - LightSmartPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .card { border-radius: 15px; }
        .eye-icon { cursor: pointer; position: absolute; right: 15px; top: 38px; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4 col-md-6">
        <h2 class="text-center mb-4">🚀 Create Account</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password (min 6)</label>
                <input type="password" name="password" id="password" class="form-control" minlength="6" required>
                <span class="eye-icon" onclick="togglePassword()">👁</span>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="text-center mt-3">Already registered? <a href="login.php">Login</a></p>
    </div>
</div>
<script>
function togglePassword() {
    const passwordField = document.getElementById("password");
    passwordField.type = passwordField.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
