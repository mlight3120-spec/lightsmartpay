<?php
$config = include __DIR__ . '/config.php';

try {
    $dsn = "pgsql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // ✅ Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(150) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        wallet_balance DECIMAL(10,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    $message = "✅ Tables created successfully!";
} catch (PDOException $e) {
    $message = "❌ Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Install - LightSmartPay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f4f6f9; }
    .install-box {
      max-width: 500px;
      margin: 100px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="install-box text-center">
    <h2>⚙️ LightSmartPay Installer</h2>
    <hr>
    <div class="alert <?php echo str_contains($message, '✅') ? 'alert-success' : 'alert-danger'; ?>">
      <?php echo $message; ?>
    </div>
    <a href="register.php" class="btn btn-primary">Proceed to Register</a>
    <a href="login.php" class="btn btn-success">Go to Login</a>
  </div>
</body>
</html>
