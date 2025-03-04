<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['order_id'];
$query = "SELECT * FROM orders WHERE id = $order_id AND customer_id = " . $_SESSION['customer_id'];
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header('Location: account.php?page=orders');
    exit();
}

$order['order_date'] = date('d/m/Y H:i:s', strtotime($order['order_date']));

$query = "SELECT od.*, p.profile_image FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = $order_id";
$result = mysqli_query($conn, $query);

// Fetch province, district, and sub-district names
$province_query = "SELECT name_th FROM provinces WHERE id = " . $order['province_id'];
$province_result = mysqli_query($conn, $province_query);
$province = mysqli_fetch_assoc($province_result)['name_th'];

$district_query = "SELECT name_th FROM districts WHERE id = " . $order['district_id'];
$district_result = mysqli_query($conn, $district_query);
$district = mysqli_fetch_assoc($district_result)['name_th'];

$sub_district_query = "SELECT name_th FROM sub_districts WHERE id = " . $order['sub_district_id'];
$sub_district_result = mysqli_query($conn, $sub_district_query);
$sub_district = mysqli_fetch_assoc($sub_district_result)['name_th'];

$title = "Order Details";
ob_start();
?>

<div class="container mt-5 order-details-page">
    <div class="row">
        <div class="col-md-6">
            <h3>ข้อมูลลูกค้า</h3>
            <p><strong>ชื่อ-นามสกุล:</strong> <?php echo $order['fullname']; ?></p>
            <p><strong>อีเมล:</strong> <?php echo $order['email']; ?></p>
            <p><strong>เบอร์โทรศัพท์:</strong> <?php echo $order['tel']; ?></p>
            <p><strong>ที่อยู่:</strong> <?php echo $order['address']; ?>, <?php echo $sub_district; ?>, <?php echo $district; ?>, <?php echo $province; ?> <?php echo $order['zipcode']; ?></p>            
        </div>
        <div class="col-md-6">
        <h3>รายละเอียดคำสั่งซื้อ</h3>
            <p><strong>หมายเลขคำสั่งซื้อ:</strong> <?php echo $order['order_no']; ?></p>
            <p><strong>วันที่สั่งซื้อ:</strong> <?php echo $order['order_date']; ?></p>
            <p><strong>สถานะ:</strong> <?php echo $order['order_status'] == 1 ? 'รอดำเนินการ' : ($order['order_status'] == 2 ? 'ชำระเงินเรียบร้อย' : 'จัดส่งเรียบร้อย'); ?></p>
            <p><strong>ยอดรวม:</strong> ฿<?php echo number_format($order['grand_total'], 2); ?></p>
        </div>
    </div>

    <h4>รายการสินค้า</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รูปภาพ</th>
                <th>ชื่อสินค้า</th>
                <th>รหัสสินค้า</th>
                <th>ราคา</th>
                <th>จำนวน</th>
                <th>รวม</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><img src="<?php echo $base_url . '/upload_image/' . $item['profile_image']; ?>" alt="<?php echo $item['product_name']; ?>" style="height: 50px;"></td>
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['product_code']; ?></td>
                    <td>฿<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>฿<?php echo number_format($item['total'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="account.php?page=orders" class="btn btn-secondary">กลับไปยังรายการคำสั่งซื้อ</a>
    <button class="btn btn-primary" onclick="printOrder()"><i class="fa-solid fa-print me-1"></i>พิมพ์</button>
</div>

<script>
function printOrder() {
    const printContents = document.querySelector('.order-details-page').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = `<div style="margin: 20px;">${printContents}</div>`;
    document.querySelectorAll('button, a').forEach(element => element.style.display = 'none');
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
