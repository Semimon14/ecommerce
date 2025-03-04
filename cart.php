<?php
session_start();
include 'config.php';

$title = "Cart";
ob_start();
?>

<div class="container mt-5 cart-page">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
            <li class="breadcrumb-item active" aria-current="page">ตะกร้าสินค้า</li>
        </ol>
    </nav>
    <h1 class="text-center">ตะกร้าสินค้า</h1>
    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) : ?>
        <div class="row mt-4">
            <div class="col-md-12">
                <form method="post" action="cart-update.php">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">รูปภาพ</th>
                                <th>สินค้า</th>
                                <th class="text-center">ราคา</th>
                                <th class="text-center" style="width: 120px;">จำนวน</th>
                                <th class="text-center">รวม</th>
                                <th class="text-center" style="width: 100px;">การกระทำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $product_id => $product) : ?>
                                <tr>
                                    <td class="text-center">
                                        <img src="<?php echo $base_url . '/upload_image/' . $product['profile_image']; ?>" alt="<?php echo $product['product_name']; ?>" style="height: 50px;">
                                    </td>
                                    <td><?php echo $product['product_name']; ?></td>
                                    <td class="text-end">฿<?php echo number_format($product['price'], 0); ?></td>
                                    <td class="text-center">
                                        <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $product['quantity']; ?>" min="1" class="form-control text-end mx-auto" style="width: 90px;">
                                    </td>
                                    <td class="text-end">฿<?php echo number_format($product['price'] * $product['quantity'], 0); ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm delete-item" data-product-id="<?php echo $product_id; ?>"><i class="fa-solid fa-trash-can me-1"></i>ลบ</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <a href="products.php" class="btn btn-secondary">เลือกซื้อสินค้าต่อ</a>
                        <button type="submit" class="btn btn-primary">อัปเดตตะกร้า</button>
                        <a href="checkout.php" class="btn btn-success"><i class="fa-solid fa-basket-shopping me-1"></i>ชำระเงิน</a>
                    </div>
                </form>
            </div>
        </div>
    <?php else : ?>
        <p class="text-center">ตะกร้าของคุณว่างเปล่า</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
