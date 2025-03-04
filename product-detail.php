<?php
session_start();
include 'config.php';

$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Prepare the SQL statement
$query = "SELECT p.product_name, p.price, p.profile_image, p.detail, c.category_name as category 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    header('Location: products.php');
    exit();
}

$title = $product['product_name'];
ob_start();
?>

<div class="container mt-5 product-detail-page">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
            <li class="breadcrumb-item"><a href="products.php">สินค้า</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $product['product_name']; ?></li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $base_url . '/upload_image/' . $product['profile_image']; ?>" class="img-fluid" alt="<?php echo $product['product_name']; ?>">
        </div>
        <div class="col-md-6">
            <h1><?php echo $product['product_name']; ?></h1>
            <p class="text-success fw-bold">ราคา: ฿<?php echo number_format($product['price'], 0); ?></p>
            <p>หมวดหมู่: <?php echo $product['category']; ?></p>
            <p><?php echo nl2br($product['detail']); ?></p>
            <div class="d-flex align-items-center mb-3">
                <label for="quantity" class="form-label me-2">จำนวน</label>
                <input type="number" id="quantity" class="form-control me-2" value="1" min="1" style="width: 80px;">
                <button type="button" class="btn btn-primary add-to-cart" data-product-id="<?php echo $product_id; ?>"><i class="fa-solid fa-cart-shopping me-1"></i>หยิบใส่ตะกร้า</button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
