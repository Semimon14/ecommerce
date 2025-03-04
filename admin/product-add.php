<?php
    include '../config.php';
    include 'auth.php';
    $page_title = 'เพิ่มสินค้า';
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_name = $_POST['product_name'];
        $product_code = $_POST['product_code'];
        $price = $_POST['price'];
        $cost_price = $_POST['cost_price'];
        $category_id = $_POST['category_id'];
        $detail = $_POST['detail'];
        $created_at = date('Y-m-d H:i:s');

        // Handle image upload
        $profile_image = '';
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $target_dir = "../upload_image/";
            $file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
            $unique_file_name = date('YmdHis') . rand(1000, 9999) . '.' . $file_extension;
            $target_file = $target_dir . $unique_file_name;
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $unique_file_name;
            }
        }

        $query = "INSERT INTO products (product_name, product_code, price, cost_price, category_id, profile_image, detail, created_at) VALUES ('$product_name', '$product_code', '$price', '$cost_price', '$category_id', '$profile_image', '$detail', '$created_at')";
        mysqli_query($conn, $query);
        header('Location: ' . $admin_url . '/product-list.php');
    }
?>
<form method="POST" enctype="multipart/form-data">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">เพิ่มสินค้า</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="product_name">ชื่อสินค้า</label>
                <input type="text" name="product_name" id="product_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="product_code">รหัสสินค้า</label>
                <input type="text" name="product_code" id="product_code" class="form-control">
            </div>
            <div class="form-group">
                <label for="price">ราคา</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cost_price">ราคาทุน</label>
                <input type="number" name="cost_price" id="cost_price" class="form-control">
            </div>
            <div class="form-group">
                <label for="category_id">หมวดหมู่</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <?php
                        $result = mysqli_query($conn, "SELECT id, category_name FROM categories");
                    ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['category_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="profile_image">รูปภาพ</label>
                <input type="file" name="profile_image" id="profile_image" class="form-control">
            </div>
            <div class="form-group">
                <label for="detail">รายละเอียด</label>
                <textarea name="detail" id="detail" class="form-control"></textarea>
            </div>                    
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>บันทึกข้อมูล</button>
            <a href="<?php echo $admin_url . '/product-list.php'; ?>" class='btn btn-secondary'>ย้อนกลับ</a>
        </div>
    </div>
</form>
<?php
    $content = ob_get_clean();
    $js_script = '';
    include 'template_master.php';
?>
