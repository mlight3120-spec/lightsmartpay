<?php
session_start();
include 'config.php'; // config.php must create $pdo (PDO connection

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pin = $_POST["pin"];
    if (strlen($pin) === 4) {
        $stmt = $pdo->prepare("UPDATE users SET pin = ? WHERE id = ?");
        $stmt->execute([$pin, $_SESSION["user_id"]]);
        $msg = "✅ PIN set successfully!";
    } else {
        $msg = "❌ PIN must be 4 digits!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Setup PIN</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <h2>🔑 Setup Transaction PIN</h2>
    <?php if ($msg): ?><p><?= $msg ?></p><?php endif; ?>
    <form method="POST">
        <input type="password" name="pin" maxlength="4" placeholder="Enter 4-digit PIN" required>
        <button type="submit">Save PIN</button>
    </form>
</div>
</body>
</html>
