<?php
// set_pin.php
session_start();
include 'config.php'; // config.php must create $pdo (PDO connection)

// ensure user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = isset($_POST['pin']) ? trim($_POST['pin']) : '';
    $confirm = isset($_POST['confirm_pin']) ? trim($_POST['confirm_pin']) : '';

    // validation
    if ($pin === '' || $confirm === '') {
        $error = "❌ Please enter PIN and confirm it.";
    } elseif (!preg_match('/^\d{4}$/', $pin)) {
        $error = "❌ PIN must be exactly 4 digits.";
    } elseif ($pin !== $confirm) {
        $error = "❌ PINs do not match.";
    } else {
        try {
            // hash and save
            $hashed = password_hash($pin, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET pin = :pin WHERE id = :id");
            $stmt->execute([
                ':pin' => $hashed,
                ':id'  => $user_id
            ]);

            $message = "✅ Transaction PIN set successfully.";
        } catch (PDOException $e) {
            $error = "❌ Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Set Transaction PIN - LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .form-box{max-width:420px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.06)}
    .msg{padding:10px;border-radius:6px;margin-bottom:12px}
    .msg.success{background:#e6ffed;color:#085f26}
    .msg.error{background:#ffecec;color:#7a1414}
    input{width:100%;padding:10px;margin:8px 0;border:1px solid #ddd;border-radius:6px}
    button{width:100%;padding:10px;background:#0066ff;color:#fff;border:none;border-radius:6px;cursor:pointer}
  </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="form-box">
  <h2>Set / Change Transaction PIN</h2>

  <?php if ($message): ?>
    <div class="msg success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <label>Enter 4-digit PIN</label>
    <input type="password" name="pin" maxlength="4" pattern="\d{4}" inputmode="numeric" required>

    <label>Confirm PIN</label>
    <input type="password" name="confirm_pin" maxlength="4" pattern="\d{4}" inputmode="numeric" required>

    <button type="submit">Save PIN</button>
  </form>
  <p style="margin-top:12px;font-size:14px;color:#666">Tip: Use a PIN you can remember but others can't guess.</p>
</div>

</body>
</html>
