<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $zipcode = $_POST['zipcode'];
    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $sub_district_id = $_POST['sub_district_id'];
    $customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 0;

    // Generate order number in the format ym00001
    $current_year_month = 'INV' . date('ym');
    $prefix = $current_year_month;
    $query = "SELECT * FROM running_numbers WHERE prefix = '$prefix'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_number = $row['current_number'] + 1;
        $update_query = "UPDATE running_numbers SET current_number = $current_number WHERE prefix = '$prefix'";
        mysqli_query($conn, $update_query);
    } else {
        $current_number = 1;
        $insert_query = "INSERT INTO running_numbers (prefix, current_number) VALUES ('$prefix', $current_number)";
        mysqli_query($conn, $insert_query);
    }
    $order_no = $prefix . str_pad($current_number, 5, '0', STR_PAD_LEFT);

    $order_date = date('Y-m-d H:i:s');
    $order_status = 1; // 1=รอดำเนินการ
    $grand_total = array_sum(array_map(function($item) {
        return $item['price'] * $item['quantity'];
    }, $_SESSION['cart']));

    $query = "INSERT INTO orders (order_no, order_date, order_status, grand_total, fullname, email, tel, address, zipcode, province_id, district_id, sub_district_id, customer_id, created_at) 
              VALUES ('$order_no', '$order_date', '$order_status', '$grand_total', '$fullname', '$email', '$tel', '$address', '$zipcode', '$province_id', '$district_id', '$sub_district_id', '$customer_id', NOW())";
    if (mysqli_query($conn, $query)) {
        $order_id = mysqli_insert_id($conn);
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $product_name = $product['product_name'];
            $product_code = $product['product_code'];
            $price = $product['price'];
            $cost_price = $product['cost_price'];
            $quantity = $product['quantity'];
            $total = $price * $quantity;
            $query = "INSERT INTO order_details (order_id, product_id, product_name, product_code, price, cost_price, quantity, total) 
                      VALUES ('$order_id', '$product_id', '$product_name', '$product_code', '$price', '$cost_price', '$quantity', '$total')";
            mysqli_query($conn, $query);
        }
        unset($_SESSION['cart']);
        $_SESSION['order_placed'] = true;
        $_SESSION['order_no'] = $order_no;
        $_SESSION['success'] = "Order placed successfully.";
        header('Location: order-success.php');
        exit();
    } else {
        $_SESSION['error'] = "Failed to place order. Please try again.";
    }
}

$customer = null;
if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];
    $query = "SELECT * FROM customers WHERE id = $customer_id";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);
}

// Fetch provinces
$province_query = "SELECT * FROM provinces";
$province_result = mysqli_query($conn, $province_query);

// Fetch districts if customer is logged in
$districts = [];
if ($customer && $customer['province_id']) {
    $district_query = "SELECT * FROM districts WHERE province_id = " . $customer['province_id'];
    $district_result = mysqli_query($conn, $district_query);
    while ($district = mysqli_fetch_assoc($district_result)) {
        $districts[] = $district;
    }
}

// Fetch sub-districts if customer is logged in
$sub_districts = [];
if ($customer && $customer['district_id']) {
    $sub_district_query = "SELECT * FROM sub_districts WHERE district_id = " . $customer['district_id'];
    $sub_district_result = mysqli_query($conn, $sub_district_query);
    while ($sub_district = mysqli_fetch_assoc($sub_district_result)) {
        $sub_districts[] = $sub_district;
    }
}

$title = "Checkout";
ob_start();
?>

<div class="container checkout-page">
    <main>
        <div class="py-5 text-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                    <li class="breadcrumb-item"><a href="cart.php">ตะกร้าสินค้า</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ชำระเงิน</li>
                </ol>
            </nav>
            <h2>ชำระเงิน</h2>
            <p class="lead">กรุณากรอกข้อมูลเพื่อดำเนินการสั่งซื้อ</p>
        </div>

        <div class="row g-5">
            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">ตะกร้าของคุณ</span>
                    <span class="badge bg-primary rounded-pill"><?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?></span>
                </h4>
                <ul class="list-group mb-3">
                    <?php foreach ($_SESSION['cart'] as $product_id => $product) : ?>
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0"><?php echo $product['product_name']; ?></h6>
                                <small class="text-muted">จำนวน: <?php echo $product['quantity']; ?></small>
                                <img src="<?php echo $base_url . '/upload_image/' . $product['profile_image']; ?>" alt="<?php echo $product['product_name']; ?>" style="height: 50px;">
                            </div>
                            <span class="text-muted">฿<?php echo number_format($product['price'] * $product['quantity'], 0); ?></span>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>รวมทั้งหมด</span>
                        <strong>฿<?php echo number_format(array_sum(array_map(function($item) {
                            return $item['price'] * $item['quantity'];
                        }, $_SESSION['cart'])), 0); ?></strong>
                    </li>
                </ul>
            </div>
            <div class="col-md-7 col-lg-8">
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="fullname" class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $customer['fullname'] ?? ''; ?>" required>
                        </div>

                        <div class="col-sm-6">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['email'] ?? ''; ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="tel" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" class="form-control" id="tel" name="tel" value="<?php echo $customer['tel'] ?? ''; ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">ที่อยู่</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $customer['address'] ?? ''; ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="province_id" class="form-label">จังหวัด</label>
                            <select class="form-select" id="province_id" name="province_id" required>
                                <option value="">เลือกจังหวัด</option>
                                <?php while ($province = mysqli_fetch_assoc($province_result)) : ?>
                                    <option value="<?php echo $province['id']; ?>" <?php echo isset($customer['province_id']) && $customer['province_id'] == $province['id'] ? 'selected' : ''; ?>><?php echo $province['name_th']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="district_id" class="form-label">อำเภอ</label>
                            <select class="form-select" id="district_id" name="district_id" required>
                                <option value="">เลือกอำเภอ</option>
                                <?php foreach ($districts as $district) : ?>
                                    <option value="<?php echo $district['id']; ?>" <?php echo isset($customer['district_id']) && $customer['district_id'] == $district['id'] ? 'selected' : ''; ?>><?php echo $district['name_th']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="sub_district_id" class="form-label">ตำบล</label>
                            <select class="form-select" id="sub_district_id" name="sub_district_id" required>
                                <option value="">เลือกตำบล</option>
                                <?php foreach ($sub_districts as $sub_district) : ?>
                                    <option value="<?php echo $sub_district['id']; ?>" <?php echo isset($customer['sub_district_id']) && $customer['sub_district_id'] == $sub_district['id'] ? 'selected' : ''; ?>><?php echo $sub_district['name_th']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="zipcode" class="form-label">รหัสไปรษณีย์</label>
                            <input type="text" class="form-control" id="zipcode" name="zipcode" value="<?php echo $customer['zipcode'] ?? ''; ?>" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-100 btn btn-primary btn-lg" type="submit"><i class="fa-solid fa-basket-shopping me-1"></i>ยืนยันการสั่งซื้อ</button>
                </form>
            </div>
        </div>
    </main>
</div>

<?php
$js_script = '<script src="assets/js/register.js"></script>';
$content = ob_get_clean();
include 'master_template.php';
?>
