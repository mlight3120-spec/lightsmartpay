<?php
// verify_pin.php
session_start();
include 'config.php'; // $pdo connection

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$error = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = isset($_POST['pin']) ? trim($_POST['pin']) : '';

    if ($pin === '') {
        $error = "❌ Please enter your PIN.";
    } elseif (!preg_match('/^\d{4}$/', $pin)) {
        $error = "❌ Invalid PIN format.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT pin FROM users WHERE id = :id");
            $stmt->execute([':id' => $user_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($pin, $row['pin'])) {
                $success = true;
                // mark pin verified for this session
                $_SESSION['pin_verified'] = true;
            } else {
                $error = "❌ Incorrect PIN.";
            }
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
  <title>Verify PIN - LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .form-box{max-width:400px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.06)}
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
  <h2>Confirm Transaction</h2>

  <?php if ($success): ?>
    <div class="msg success">✅ PIN verified. Proceed with your transaction.</div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!$success): ?>
    <form method="post" autocomplete="off">
      <label>Enter your 4-digit PIN</label>
      <input type="password" name="pin" maxlength="4" pattern="\d{4}" inputmode="numeric" required>
      <button type="submit">Verify PIN</button>
    </form>
  <?php endif; ?>
</div>

</body>
</html>
