<?php
session_start();
include 'config.php';

// Pagination settings
$limit = 9;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Category filter
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 0;
$category_filter = $category_id ? "WHERE p.category_id = $category_id" : "";

// Fetch categories
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);

// Fetch products
$product_query = "SELECT p.product_name, p.price, p.profile_image, c.category_name as category, p.id 
                  FROM products p 
                  JOIN categories c ON p.category_id = c.id 
                  $category_filter
                  ORDER BY p.created_at DESC 
                  LIMIT $limit OFFSET $offset";
$product_result = mysqli_query($conn, $product_query);

// Fetch total products for pagination
$total_query = "SELECT COUNT(*) as total FROM products p $category_filter";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

$title = "Products";
ob_start();
?>

<div class="container mt-5 products-page">
    <div class="row">
        <div class="col-md-3">
            <h4 class="category-header">หมวดหมู่สินค้า</h4>
            <ul class="list-group">
                <li class="list-group-item"><a href="products.php">ทั้งหมด</a></li>
                <?php while ($category = mysqli_fetch_assoc($category_result)) : ?>
                    <li class="list-group-item">
                        <a href="products.php?category_id=<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
        <div class="col-md-9">
            <h2>สินค้า</h2>
            <div class="row">
                <?php while ($product = mysqli_fetch_assoc($product_result)) : ?>
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
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="products.php?page=<?php echo $i; ?>&category_id=<?php echo $category_id; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
