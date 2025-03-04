<?php
include '../config.php';
session_start();

// Clear session
session_unset();
session_destroy();

// Clear cookies
setcookie('user_id', '', time() - 3600, "/");
setcookie('username', '', time() - 3600, "/");

// Redirect to login page
header('Location: login.php');
exit();
?>
