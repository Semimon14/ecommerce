<?php
include '../config.php';
include 'auth.php';

$id = $_GET['id'];

// Check if the customer is referenced in the orders table
$orders_query = "SELECT COUNT(*) as total FROM orders WHERE customer_id = $id";
$orders_result = mysqli_query($conn, $orders_query);
$orders_row = mysqli_fetch_assoc($orders_result);

if ($orders_row['total'] > 0) {
    // Customer is referenced in orders, cannot delete
    $_SESSION['error_message'] = 'ไม่สามารถลบลูกค้าได้ เนื่องจากมีการอ้างอิงถึงในรายการสั่งซื้อ';
    header('Location: ' . $admin_url . '/customer-list.php');
    exit();
}

// Delete the customer
$query = "DELETE FROM customers WHERE id = $id";
mysqli_query($conn, $query);

header('Location: ' . $admin_url . '/customer-list.php');
?>
