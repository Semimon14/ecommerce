<?php
include '../config.php';
include 'auth.php';

$id = $_GET['id'];

// Delete the order details
$order_details_query = "DELETE FROM order_details WHERE order_id = $id";
mysqli_query($conn, $order_details_query);

// Delete the order
$order_query = "DELETE FROM orders WHERE id = $id";
mysqli_query($conn, $order_query);

header('Location: ' . $admin_url . '/order-list.php');
?>
