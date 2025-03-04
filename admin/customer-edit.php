<?php
include '../config.php';
include 'auth.php';
$page_title = 'แก้ไขลูกค้า';
ob_start();

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM customers WHERE id = $id");
$customer = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $customer['password'];
    $fullname = $_POST['fullname'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $sub_district_id = $_POST['sub_district_id'];
    $updated_at = date('Y-m-d H:i:s');

    $query = "UPDATE customers SET email = '$email', password = '$password', fullname = '$fullname', tel = '$tel', address = '$address', zipcode = '$zipcode', province_id = '$province_id', district_id = '$district_id', sub_district_id = '$sub_district_id', updated_at = '$updated_at' WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: ' . $admin_url . '/customer-list.php');
}
?>
<form method="POST">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo $customer['fullname']; ?></h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="email">อีเมล์</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $customer['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน (leave blank to keep current password)</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="fullname">ชื่อ</label>
                <input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo $customer['fullname']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tel">เบอร์โทรศัพท์</label>
                <input type="text" name="tel" id="tel" class="form-control" value="<?php echo $customer['tel']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">ที่อยู่</label>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo $customer['address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="province_id">จังหวัด</label>
                <select name="province_id" id="province_id" class="form-select" required>
                    <option value="">เลือกจังหวัด</option>
                    <?php
                    $result = mysqli_query($conn, "SELECT id, name_th FROM provinces");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = $row['id'] == $customer['province_id'] ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['name_th']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="district_id">อำเภอ</label>
                <select name="district_id" id="district_id" class="form-select" required>
                    <?php
                    $result = mysqli_query($conn, "SELECT id, name_th FROM districts WHERE province_id = {$customer['province_id']}");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = $row['id'] == $customer['district_id'] ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['name_th']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sub_district_id">ตำบล</label>
                <select name="sub_district_id" id="sub_district_id" class="form-select" required>
                    <?php
                    $result = mysqli_query($conn, "SELECT id, name_th, zip_code FROM sub_districts WHERE district_id = {$customer['district_id']}");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = $row['id'] == $customer['sub_district_id'] ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected data-zipcode='{$row['zip_code']}'>{$row['name_th']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="zipcode">รหัสไปรษณีย์</label>
                <input type="text" name="zipcode" id="zipcode" class="form-control" value="<?php echo $customer['zipcode']; ?>" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>บันทึกข้อมูล</button>
            <a href="<?php echo $admin_url . '/customer-list.php'; ?>" class='btn btn-secondary'>ย้อนกลับ</a>
        </div>
    </div>
</form>
<?php
$content = ob_get_clean();
$js_script = '<script src="assets/js/customer.js"></script>';
include 'template_master.php';
?>
