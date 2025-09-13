<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Buy Data | LightSmartPay</title>
  <link rel="stylesheet" href="styles.css">
  <script>
    function updatePlans() {
      const network = document.getElementById("network").value;
      const type = document.getElementById("datatype").value;
      const planSelect = document.getElementById("plan");
      planSelect.innerHTML = "";

      let plans = [];

      if (network === "MTN" && type === "SME") {
        plans = [
          {value: "500MB", text: "500MB - ₦150"},
          {value: "1GB", text: "1GB - ₦280"},
          {value: "2GB", text: "2GB - ₦550"}
        ];
      } else if (network === "MTN" && type === "Corporate") {
        plans = [
          {value: "1GB", text: "1GB - ₦300"},
          {value: "2GB", text: "2GB - ₦600"}
        ];
      } else if (network === "Airtel" && type === "SME") {
        plans = [
          {value: "500MB", text: "500MB - ₦200"},
          {value: "1GB", text: "1GB - ₦350"}
        ];
      } else if (network === "Glo") {
        plans = [
          {value: "1GB", text: "1GB - ₦450"},
          {value: "2GB", text: "2GB - ₦800"}
        ];
      } else if (network === "9mobile") {
        plans = [
          {value: "500MB", text: "500MB - ₦250"},
          {value: "1GB", text: "1GB - ₦450"}
        ];
      }

      plans.forEach(plan => {
        const option = document.createElement("option");
        option.value = plan.value;
        option.text = plan.text;
        planSelect.appendChild(option);
      });
    }
  </script>
</head>
<body>
<?php include "navbar.php"; ?>
<div class="content">
  <h2>Buy Data</h2>
  <form method="POST" action="verify_pin.php">
    <label>Network</label>
    <select name="network" id="network" onchange="updatePlans()" required>
      <option value="">-- Select Network --</option>
      <option value="MTN">MTN</option>
      <option value="Airtel">Airtel</option>
      <option value="Glo">Glo</option>
      <option value="9mobile">9mobile</option>
    </select>

    <label>Data Type</label>
    <select name="datatype" id="datatype" onchange="updatePlans()" required>
      <option value="">-- Select Type --</option>
      <option value="SME">SME</option>
      <option value="Corporate">Corporate</option>
      <option value="Gift">Gift</option>
    </select>

    <label>Plan</label>
    <select name="plan" id="plan" required>
      <option value="">-- Select Plan --</option>
    </select>

    <label>Phone Number</label>
    <input type="number" name="phone" placeholder="Enter phone number" required>

    <label>Transaction PIN</label>
    <input type="password" name="pin" placeholder="Enter 4-digit PIN" maxlength="4" required>

    <button type="submit">Buy Data</button>
  </form>
</div>
</body>
</html>
