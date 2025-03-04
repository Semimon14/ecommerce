<?php
include '../config.php';
include 'auth.php';
$page_title = 'เพิ่มลูกค้า';
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $fullname = $_POST['fullname'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $sub_district_id = $_POST['sub_district_id'];
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO customers (email, password, fullname, tel, address, zipcode, province_id, district_id, sub_district_id, created_at) VALUES ('$email', '$password', '$fullname', '$tel', '$address', '$zipcode', '$province_id', '$district_id', '$sub_district_id', '$created_at')";
    mysqli_query($conn, $query);
    header('Location: ' . $admin_url . '/customer-list.php');
}
?>
<form method="POST">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">เพิ่มลูกค้า</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="email">อีเมล์</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="fullname">ชื่อ</label>
                <input type="text" name="fullname" id="fullname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tel">เบอร์โทรศัพท์</label>
                <input type="text" name="tel" id="tel" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">ที่อยู่</label>
                <input type="text" name="address" id="address" class="form-control" required>
            </div>            
            <div class="form-group">
                <label for="province_id">จังหวัด</label>
                <select name="province_id" id="province_id" class="form-select" required>
                    <option value="">เลือกจังหวัด</option>
                    <?php
                    $result = mysqli_query($conn, "SELECT id, name_th FROM provinces");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id']}'>{$row['name_th']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="district_id">อำเภอ</label>
                <select name="district_id" id="district_id" class="form-select" required>
                    <option value="">เลือกอำเภอ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sub_district_id">ตำบล</label>
                <select name="sub_district_id" id="sub_district_id" class="form-select" required>
                    <option value="">เลือกตำบล</option>
                </select>
            </div>
            <div class="form-group">
                <label for="zipcode">รหัสไปรษณีย์</label>
                <input type="text" name="zipcode" id="zipcode" class="form-control" required>
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
