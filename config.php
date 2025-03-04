<?php
// var url
$base_url = 'http://localhost/ecommerce';
$admin_url = $base_url . '/admin';

// var database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ecommerce';

// connect db
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('connection failed');

// Start session with different names for admin and customer
if (session_status() == PHP_SESSION_NONE) {
    if (strpos($_SERVER['REQUEST_URI'], '/admin') !== false) {
        session_name('admin_session');
    } else {
        session_name('customer_session');
    }
    session_start();
}
?>