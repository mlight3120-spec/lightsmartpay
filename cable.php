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
    <title>Cable Subscription - LightSmartPay</title>
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
        <a href="cable.php" class="bg-success">📺 Cable Subscription</a>
        <a href="profile.php">👤 Profile</a>
        <a href="set_pin.php">🔑 Setup PIN</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="content">
        <div class="card shadow p-4">
            <h4 class="mb-3">📺 Cable Subscription (+₦50 commission)</h4>
            <form method="POST" action="process_cable.php">
                <div class="mb-3">
                    <label class="form-label">Cable Provider</label>
                    <select name="provider" class="form-control" required>
                        <option value="">-- Select Provider --</option>
                        <option value="dstv">DSTV</option>
                        <option value="gotv">GOtv</option>
                        <option value="startimes">Startimes</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Smartcard Number</label>
                    <input type="text" name="smartcard" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Package</label>
                    <input type="text" name="package" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Subscribe</button>
            </form>
        </div>
    </div>
</body>
</html>
