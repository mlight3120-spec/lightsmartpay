<?php
session_start();
$config = include __DIR__ . '/config.php';

try {
    $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("DB Connection failed: " . $e->getMessage());
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$full_name, $email, $password]);
        header("Location: login.php");
        exit;
    } catch (Exception $e) {
        $error = "❌ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - LightSmartPay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">🚀 Create Account</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Password (min 6)</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" onclick="togglePassword()">👁</button>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3">Already registered? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>
<script>
function togglePassword() {
    const input = document.getElementById("password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
