<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $query = "SELECT * FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $query);
        $product = mysqli_fetch_assoc($result);
        $_SESSION['cart'][$product_id] = [
            'product_name' => $product['product_name'],
            'product_code' => $product['product_code'],
            'price' => $product['price'],
            'cost_price' => $product['cost_price'],
            'quantity' => $quantity,
            'profile_image' => $product['profile_image']
        ];
    }
    echo json_encode(['status' => 'success', 'message' => 'หยิบใส่ตะกร้าสำเร็จ']);
    exit();
}
?>