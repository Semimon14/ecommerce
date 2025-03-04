<?php
include '../config.php';
include 'auth.php';
$page_title = 'เพิ่มหมวดหมู่';
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO categories (category_name, created_at) VALUES ('$category_name', '$created_at')";
    mysqli_query($conn, $query);
    header('Location: ' . $admin_url . '/category-list.php');
}
?>
<form method="POST">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">เพิ่มหมวดหมู่</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="category_name">ชื่อหมวดหมู่</label>
                <input type="text" name="category_name" id="category_name" class="form-control" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>บันทึกข้อมูล</button>
            <a href="<?php echo $admin_url . '/category-list.php'; ?>" class='btn btn-secondary'>ย้อนกลับ</a>
        </div>
    </div>
</form>
<?php
$content = ob_get_clean();
$js_script = '';
include 'template_master.php';
?>
