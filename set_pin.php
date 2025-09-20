<?php
session_start();
$config = include __DIR__ . "/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Set Transaction PIN - LightSmartPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {font-family:'Segoe UI',sans-serif;background:#f4f6f9;}
        .sidebar {height:100vh;background:#1a1a2e;color:#fff;padding-top:30px;position:fixed;width:250px;}
        .sidebar a {display:block;padding:12px 20px;color:#fff;text-decoration:none;margin:5px 0;border-radius:8px;}
        .sidebar a:hover {background:#16213e;}
        .content {margin-left:260px;padding:30px;}
        .card {border-radius:15px;}
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center mb-4">💡 LightSmartPay</h3>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="buyairtime.php">📱 Buy Airtime</a>
        <a href="buydata.php">🌐 Buy Data</a>
        <a href="cable.php">📺 Cable Subscription</a>
        <a href="profile.php">👤 Profile</a>
        <a href="set_pin.php" class="bg-success">🔑 Setup PIN</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="card shadow p-4">
            <h4 class="mb-3">🔑 Setup Transaction PIN</h4>
            <form method="POST" action="process_pin.php">
                <div class="mb-3">
                    <label class="form-label">Enter New PIN</label>
                    <input type="password" name="pin" class="form-control" maxlength="4" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm PIN</label>
                    <input type="password" name="confirm_pin" class="form-control" maxlength="4" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Set PIN</button>
            </form>
        </div>
    </div>
</body>
</html>
