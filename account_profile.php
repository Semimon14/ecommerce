<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $sub_district_id = $_POST['sub_district_id'];

    $query = "UPDATE customers SET fullname = '$fullname', email = '$email', tel = '$tel', address = '$address', zipcode = '$zipcode', province_id = '$province_id', district_id = '$district_id', sub_district_id = '$sub_district_id' WHERE id = $customer_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "ข้อมูลส่วนตัวถูกอัปเดตเรียบร้อยแล้ว";
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูลส่วนตัว";
    }
}
?>

<h3>ข้อมูลส่วนตัว</h3>
<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<form method="post" action="">
    <div class="mb-3">
        <label for="fullname" class="form-label">ชื่อ-นามสกุล</label>
        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $customer['fullname']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">อีเมล</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['email']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="tel" class="form-label">เบอร์โทรศัพท์</label>
        <input type="text" class="form-control" id="tel" name="tel" value="<?php echo $customer['tel']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">ที่อยู่</label>
        <input type="text" class="form-control" id="address" name="address" value="<?php echo $customer['address']; ?>" required>
    </div>    
    <div class="mb-3">
        <label for="province_id" class="form-label">จังหวัด</label>
        <select class="form-select" id="province_id" name="province_id" required>
            <option value="">เลือกจังหวัด</option>
            <?php
            $province_query = "SELECT * FROM provinces";
            $province_result = mysqli_query($conn, $province_query);
            while ($province = mysqli_fetch_assoc($province_result)) : ?>
                <option value="<?php echo $province['id']; ?>" <?php echo $customer['province_id'] == $province['id'] ? 'selected' : ''; ?>><?php echo $province['name_th']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="district_id" class="form-label">อำเภอ</label>
        <select class="form-select" id="district_id" name="district_id" required>
            <option value="">เลือกอำเภอ</option>
            <?php
            $district_query = "SELECT * FROM districts WHERE province_id = " . $customer['province_id'];
            $district_result = mysqli_query($conn, $district_query);
            while ($district = mysqli_fetch_assoc($district_result)) : ?>
                <option value="<?php echo $district['id']; ?>" <?php echo $customer['district_id'] == $district['id'] ? 'selected' : ''; ?>><?php echo $district['name_th']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="sub_district_id" class="form-label">ตำบล</label>
        <select class="form-select" id="sub_district_id" name="sub_district_id" required>
            <option value="">เลือกตำบล</option>
            <?php
            $sub_district_query = "SELECT * FROM sub_districts WHERE district_id = " . $customer['district_id'];
            $sub_district_result = mysqli_query($conn, $sub_district_query);
            while ($sub_district = mysqli_fetch_assoc($sub_district_result)) : ?>
                <option value="<?php echo $sub_district['id']; ?>" <?php echo $customer['sub_district_id'] == $sub_district['id'] ? 'selected' : ''; ?>><?php echo $sub_district['name_th']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="zipcode" class="form-label">รหัสไปรษณีย์</label>
        <input type="text" class="form-control" id="zipcode" name="zipcode" value="<?php echo $customer['zipcode']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button>
</form>

<script>
document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const districtSelect = document.getElementById('district_id');
    const subDistrictSelect = document.getElementById('sub_district_id');
    const zipcodeInput = document.getElementById('zipcode');

    districtSelect.innerHTML = '<option value="">เลือกอำเภอ</option>';
    subDistrictSelect.innerHTML = '<option value="">เลือกตำบล</option>';
    zipcodeInput.value = '';

    if (provinceId) {
        fetch('get_districts.php?province_id=' + provinceId)
            .then(response => response.json())
            .then(data => {
                data.forEach(district => {
                    districtSelect.innerHTML += `<option value="${district.id}">${district.name_th}</option>`;
                });
            });
    }
});

document.getElementById('district_id').addEventListener('change', function() {
    const districtId = this.value;
    const subDistrictSelect = document.getElementById('sub_district_id');
    const zipcodeInput = document.getElementById('zipcode');

    subDistrictSelect.innerHTML = '<option value="">เลือกตำบล</option>';
    zipcodeInput.value = '';

    if (districtId) {
        fetch('get_sub_districts.php?district_id=' + districtId)
            .then(response => response.json())
            .then(data => {
                data.forEach(subDistrict => {
                    subDistrictSelect.innerHTML += `<option value="${subDistrict.id}" data-zipcode="${subDistrict.zip_code}">${subDistrict.name_th}</option>`;
                });
            });
    }
});

document.getElementById('sub_district_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const zipcode = selectedOption.getAttribute('data-zipcode');
    document.getElementById('zipcode').value = zipcode;
});
</script>
