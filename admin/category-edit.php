<?php
include '../config.php';
include 'auth.php';
$page_title = 'แก้ไขหมวดหมู่';
ob_start();

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM categories WHERE id = $id");
$category = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $updated_at = date('Y-m-d H:i:s');

    $query = "UPDATE categories SET category_name = '$category_name', updated_at = '$updated_at' WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: ' . $admin_url . '/category-list.php');
}
?>
<form method="POST">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo $category['category_name']; ?></h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="category_name">ชื่อหมวดหมู่</label>
                <input type="text" name="category_name" id="category_name" class="form-control" value="<?php echo $category['category_name']; ?>" required>
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
