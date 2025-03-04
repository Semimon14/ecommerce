<?php
include '../config.php';
include 'auth.php';
$page_title = 'ดูรายการสั่งซื้อ';
ob_start();

$id = $_GET['id'];
$order_query = "SELECT * FROM orders WHERE id = $id";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

$order['order_date'] = date('d/m/Y H:i:s', strtotime($order['order_date']));

$order_details_query = "SELECT * FROM order_details WHERE order_id = $id";
$order_details_result = mysqli_query($conn, $order_details_query);

$province_query = "SELECT name_th FROM provinces WHERE id = {$order['province_id']}";
$province_result = mysqli_query($conn, $province_query);
$province = mysqli_fetch_assoc($province_result)['name_th'];

$district_query = "SELECT name_th FROM districts WHERE id = {$order['district_id']}";
$district_result = mysqli_query($conn, $district_query);
$district = mysqli_fetch_assoc($district_result)['name_th'];

$sub_district_query = "SELECT name_th FROM sub_districts WHERE id = {$order['sub_district_id']}";
$sub_district_result = mysqli_query($conn, $sub_district_query);
$sub_district = mysqli_fetch_assoc($sub_district_result)['name_th'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_status = $_POST['order_status'];
    $tracking_no = $_POST['tracking_no'];

    $update_query = "UPDATE orders SET order_status = '$order_status', tracking_no = '$tracking_no' WHERE id = $id";
    mysqli_query($conn, $update_query);

    header("Location: order-view.php?id=$id");
    exit();
}
?>
<form method="POST" class="form-horizontal">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">ดูรายการสั่งซื้อ</h3>
        </div>
        <div class="card-body">        
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>ชื่อผู้สั่งซื้อ:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo $order['fullname']; ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>อีเมล์:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo $order['email']; ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>เบอร์โทรศัพท์:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo $order['tel']; ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>ที่อยู่:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo $order['address']; ?>, <?php echo $sub_district; ?>, <?php echo $district; ?>, <?php echo $province; ?>, <?php echo $order['zipcode']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>หมายเลขสั่งซื้อ:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo $order['order_no']; ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>วันที่สั่งซื้อ:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo $order['order_date']; ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>สถานะ:</strong></label>
                        <div class="col-sm-8">
                            <select name="order_status" class="form-select">
                                <option value="1" <?php echo $order['order_status'] == 1 ? 'selected' : ''; ?>>รอดำเนินการ</option>
                                <option value="2" <?php echo $order['order_status'] == 2 ? 'selected' : ''; ?>>ชำระเงินเรียบร้อย</option>
                                <option value="3" <?php echo $order['order_status'] == 3 ? 'selected' : ''; ?>>จัดส่งเรียบร้อย</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>ยอดรวม:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?php echo number_format($order['grand_total'], 2); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>หมายเลขพัสดุติดตาม:</strong></label>
                        <div class="col-sm-8">
                            <input type="text" name="tracking_no" class="form-control" value="<?php echo $order['tracking_no']; ?>">
                        </div>
                    </div>
                </div>
            </div>        
            <h4>รายละเอียดสินค้า</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รหัสสินค้า</th>
                        <th>ราคา</th>
                        <th>จำนวน</th>
                        <th>ยอดรวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($order_details_result)): ?>
                        <tr>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['product_code']; ?></td>
                            <td><?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo number_format($row['total'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            <a href="<?php echo $admin_url . '/order-list.php'; ?>" class='btn btn-secondary'>ย้อนกลับ</a>
        </div>
    </div>
</form>
<?php
$content = ob_get_clean();
$js_script = '';
include 'template_master.php';
?>
