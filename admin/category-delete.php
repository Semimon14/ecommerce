<?php
include '../config.php';
include 'auth.php';

$id = $_GET['id'];

// Check if the category is referenced in the products table
$products_query = "SELECT COUNT(*) as total FROM products WHERE category_id = $id";
$products_result = mysqli_query($conn, $products_query);
$products_row = mysqli_fetch_assoc($products_result);

if ($products_row['total'] > 0) {
    // Category is referenced in products, cannot delete
    $_SESSION['error_message'] = 'ไม่สามารถลบหมวดหมู่ได้ เนื่องจากมีการอ้างอิงถึงในสินค้า';
    header('Location: ' . $admin_url . '/category-list.php');
    exit();
}

// Delete the category
$query = "DELETE FROM categories WHERE id = $id";
mysqli_query($conn, $query);

header('Location: ' . $admin_url . '/category-list.php');
?>
