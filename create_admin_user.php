<?php
// ONE-TIME: Create an admin user. DELETE this file after success for security.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        die("⚠️ All fields are required.");
    }

    $config = include __DIR__ . "/config.php";

    try {
        $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
        $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (Exception $e) {
        die("DB connection failed: " . $e->getMessage());
    }

    // Ensure is_admin column exists
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin BOOLEAN DEFAULT FALSE");

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, wallet_balance, created_at, is_admin) 
                           VALUES (?, ?, ?, 0, NOW(), true)");

    $stmt->execute([$name, $email, $hash]);

    echo "✅ Admin account created successfully for $email.<br>";
    echo "👉 Now DELETE create_admin_user.php from your server for security.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="mb-3">👑 Create Admin User</h3>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Admin</button>
            </form>
        </div>
    </div>
</body>
</html>
