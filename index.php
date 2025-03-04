<?php
session_start();
include 'config.php';

// Fetch the latest 5 products from the database
$query = "SELECT p.product_name, p.price, p.profile_image, c.category_name as category, p.id 
FROM products p 
JOIN categories c ON p.category_id = c.id 
ORDER BY p.created_at DESC 
LIMIT 6";
$result = mysqli_query($conn, $query);

$title = "Home";
ob_start();
?>

<div class="container mt-5 index-page">
    <h1 class="text-center">ยินดีต้อนรับสู่ร้าน คอมพิวเตอร์มาร์ท</h1>
    <div class="row mt-4">
        <div class="col-md-12">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="<?php echo $base_url . '/assets/image/slider-01.jpg'; ?>" alt="First slide" style="height: 500px;">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="<?php echo $base_url . '/assets/image/slider-02.jpg'; ?>" alt="Second slide" style="height: 500px;">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="<?php echo $base_url . '/assets/image/slider-03.jpg'; ?>" alt="Third slide" style="height: 500px;">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <h2>สินค้าเข้าใหม่</h2>
        <div class="row">
            <?php while ($product = mysqli_fetch_assoc($result)) : ?>                
                <div class="col-md-4 mb-4">
                    <div class="card card-product border-secondary">
                        <a href="<?php echo $base_url . '/product-detail.php?id=' . $product['id']; ?>">
                            <img src="<?php echo $base_url . '/upload_image/' . $product['profile_image']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo $base_url . '/product-detail.php?id=' . $product['id']; ?>">
                                    <?php echo $product['product_name']; ?>
                                </a>
                            </h5>
                            <p class="card-text">หมวดหมู่: <?php echo $product['category']; ?></p>
                            <p class="card-text fw-bold text-success">ราคา: ฿<?php echo number_format($product['price'], 0); ?></p>
                            <button type="button" class="btn btn-primary w-100 add-to-cart" data-product-id="<?php echo $product['id']; ?>"><i class="fa-solid fa-cart-shopping me-1"></i>หยิบใส่ตะกร้า</button>                                
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
