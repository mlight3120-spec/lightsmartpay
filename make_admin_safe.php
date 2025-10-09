<?php
// make_admin_safe.php  — ONE TIME USE. Delete file after success.

// Usage:
// 1) Upload to server (next to config.php).
// 2) Open in browser: https://yourdomain.com/make_admin_safe.php
// 3) Fill the form with the email to promote (or create new admin).
// 4) Delete this file immediately after success.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name  = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        die("Provide email.");
    }

    $config = include __DIR__ . '/config.php';
    try {
        $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
        $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (Exception $e) {
        die("DB connect failed: " . $e->getMessage());
    }

    // 1) Add column if not exists
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin boolean DEFAULT false");
    } catch (Exception $e) {
        // ignore errors but show message
        echo "Could not add column automatically: " . $e->getMessage() . "<br>";
    }

    // 2) If password provided -> create user and set admin
    if (!empty($password) && !empty($name)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, wallet_balance, created_at, is_admin) VALUES (?, ?, ?, 0, NOW(), true)");
        try {
            $stmt->execute([$name, $email, $hash]);
            echo "✅ Created new admin user: {$email}<br>";
        } catch (Exception $e) {
            echo "Could not create user (maybe already exists): " . $e->getMessage() . "<br>";
        }
    } else {
        // 3) Otherwise try to promote existing user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$u) {
            echo "User not found. To create admin, fill name & password and submit.<br>";
        } else {
            $upd = $pdo->prepare("UPDATE users SET is_admin = true WHERE id = ?");
            $upd->execute([$u['id']]);
            echo "✅ Success: {$email} promoted to admin (id={$u['id']}).<br>";
        }
    }

    echo "<br>👉 IMPORTANT: Delete make_admin_safe.php now.";
    exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Make Admin (One-time)</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="p-4">
<div class="container" style="max-width:640px">
  <h3 class="mb-3">Make Admin (One-time)</h3>
  <p class="text-muted">Either promote an existing user by email, or create a new admin by providing name & password.</p>
  <form method="post">
    <div class="mb-2"><label>Email</label><input name="email" class="form-control" required></div>
    <div class="mb-2"><label>Full Name (only if creating)</label><input name="name" class="form-control"></div>
    <div class="mb-2"><label>Password (only if creating)</label><input name="password" type="password" class="form-control"></div>
    <button class="btn btn-primary">Run</button>
  </form>
</div>
</body></html>
