<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Buy Airtime | LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include "navbar.php"; ?>
<div class="content">
  <h2>Buy Airtime</h2>
  <form method="POST" action="verify_pin.php">
    
    <label>Network</label>
    <select name="network" required>
      <option value="">-- Select Network --</option>
      <option value="MTN">MTN</option>
      <option value="Airtel">Airtel</option>
      <option value="Glo">Glo</option>
      <option value="9mobile">9mobile</option>
    </select>

    <label>Phone Number</label>
    <input type="number" name="phone" placeholder="Enter phone number" required>

    <label>Amount (₦)</label>
    <input type="number" name="amount" placeholder="Enter amount e.g 100" required>

    <label>Transaction PIN</label>
    <input type="password" name="pin" placeholder="Enter 4-digit PIN" maxlength="4" required>

    <button type="submit">Buy Airtime</button>
  </form>
</div>
</body>
</html>
