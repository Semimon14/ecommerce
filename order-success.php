<?php
session_start();
include 'config.php';

if (!isset($_SESSION['order_placed']) || !$_SESSION['order_placed']) {
    header('Location: index.php');
    exit();
}

$order_no = $_SESSION['order_no'];
$_SESSION['order_placed'] = false;

$title = "Order Success";
ob_start();
?>

<div class="container mt-5 order-success-page">
    <h1 class="text-center">สั่งซื้อสำเร็จ</h1>
    <p class="text-center">ขอบคุณสำหรับการสั่งซื้อของคุณ! หมายเลขคำสั่งซื้อของคุณคือ <strong><?php echo $order_no; ?></strong>. เราจะดำเนินการจัดส่งสินค้าให้เร็วที่สุด.</p>
    <div class="text-center">
        <a href="index.php" class="btn btn-primary">กลับไปหน้าแรก</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
