<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $sub_district_id = $_POST['sub_district_id'];

    // Check if email already exists
    $query = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Email already exists. Please use a different email.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO customers (fullname, email, password, tel, address, zipcode, province_id, district_id, sub_district_id, created_at) 
                  VALUES ('$fullname', '$email', '$hashed_password', '$tel', '$address', '$zipcode', '$province_id', '$district_id', '$sub_district_id', NOW())";
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Registration successful. Please login.";
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
        }
    }
}

// Fetch provinces
$province_query = "SELECT * FROM provinces";
$province_result = mysqli_query($conn, $province_query);

$title = "Register";
ob_start();
?>

<div class="container mt-5 register-page">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="text-center">สมัครสมาชิก</h1>
                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">รหัสผ่าน</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tel" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" class="form-control" id="tel" name="tel" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">ที่อยู่</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>        
                                <div class="mb-3">
                                    <label for="province_id" class="form-label">จังหวัด</label>
                                    <select class="form-select" id="province_id" name="province_id" required>
                                        <option value="">เลือกจังหวัด</option>
                                        <?php while ($province = mysqli_fetch_assoc($province_result)) : ?>
                                            <option value="<?php echo $province['id']; ?>"><?php echo $province['name_th']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">อำเภอ</label>
                                    <select class="form-select" id="district_id" name="district_id" required>
                                        <option value="">เลือกอำเภอ</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="sub_district_id" class="form-label">ตำบล</label>
                                    <select class="form-select" id="sub_district_id" name="sub_district_id" required>
                                        <option value="">เลือกตำบล</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="zipcode" class="form-label">รหัสไปรษณีย์</label>
                                    <input type="text" class="form-control" id="zipcode" name="zipcode" required>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$js_script = '<script src="assets/js/register.js"></script>';
$content = ob_get_clean();
include 'master_template.php';
?>
