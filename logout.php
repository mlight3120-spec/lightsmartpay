<?php
// logout.php
session_start();

// destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// redirect to homepage or login
header("Location: index.php");
exit;
