<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<div class="sidebar">
    <h2>💡 LightSmartPay</h2>
    <ul>
        <li><a href="dashboard.php">🏠 Dashboard</a></li>
        <li><a href="data.php">📶 Buy Data</a></li>
        <li><a href="airtime.php">📞 Buy Airtime</a></li>
        <li><a href="cable.php">📺 Cable TV</a></li>
        <li><a href="electricity.php">💡 Electricity</a></li>
        <li><a href="transactions_data.php">📑 Data History</a></li>
        <li><a href="transactions_airtime.php">📑 Airtime History</a></li>
        <li><a href="transactions_cable.php">📑 Cable History</a></li>
        <li><a href="transactions_electricity.php">📑 Electricity History</a></li>
        <li><a href="profile.php">👤 Profile</a></li>
        <li><a href="set_pin.php">🔑 Setup PIN</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
</div>

<style>
.sidebar {
    width: 220px;
    height: 100vh;
    background: #1e1e2f;
    color: white;
    padding: 20px;
    position: fixed;
    top: 0;
    left: 0;
}
.sidebar h2 {
    font-size: 20px;
    margin-bottom: 20px;
}
.sidebar ul {
    list-style: none;
    padding: 0;
}
.sidebar ul li {
    margin: 15px 0;
}
.sidebar ul li a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    display: block;
}
.sidebar ul li a:hover {
    background: #333;
    padding: 8px;
    border-radius: 5px;
}
</style>
