<?php
include '../config.php';
include 'auth.php';

$id = $_GET['id'];

// Check if the product is referenced in the order_details table
$order_details_query = "SELECT COUNT(*) as total FROM order_details WHERE product_id = $id";
$order_details_result = mysqli_query($conn, $order_details_query);
$order_details_row = mysqli_fetch_assoc($order_details_result);

if ($order_details_row['total'] > 0) {
    // Product is referenced in order_details, cannot delete
    $_SESSION['error_message'] = 'ไม่สามารถลบสินค้าได้ เนื่องจากมีการอ้างอิงถึงในรายการสั่งซื้อ';
    header('Location: ' . $admin_url . '/product-list.php');
    exit();
}

// Get the image filename
$result = mysqli_query($conn, "SELECT profile_image FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($result);
$profile_image = $product['profile_image'];

// Delete the product
$query = "DELETE FROM products WHERE id = $id";
mysqli_query($conn, $query);

// Delete the image file
if ($profile_image) {
    $image_path = "../upload_image/" . $profile_image;
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

header('Location: ' . $admin_url . '/product-list.php');
?>
