<?php
include '../config.php';
include 'auth.php';
$page_title = 'แก้ไขผู้ใช้';
ob_start();

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $updated_at = date('Y-m-d H:i:s');

    // Check for duplicate username
    $username_check_query = "SELECT id FROM users WHERE username = '$username' AND id != $id";
    $username_check_result = mysqli_query($conn, $username_check_query);
    if (mysqli_num_rows($username_check_result) > 0) {
        $error_message = 'Username นี้มีอยู่ในระบบแล้ว';
    }

    // Check for duplicate email
    $email_check_query = "SELECT id FROM users WHERE email = '$email' AND id != $id";
    $email_check_result = mysqli_query($conn, $email_check_query);
    if (mysqli_num_rows($email_check_result) > 0) {
        $error_message = 'Email นี้มีอยู่ในระบบแล้ว';
    }

    if (!isset($error_message)) {
        // Handle image upload
        $profile_image = $user['profile_image'];
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $target_dir = "../upload_image/";
            $file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
            $unique_file_name = 'user_' . date('YmdHis') . rand(1000, 9999) . '.' . $file_extension;
            $target_file = $target_dir . $unique_file_name;
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // Remove old image
                if ($profile_image && file_exists($target_dir . $profile_image)) {
                    unlink($target_dir . $profile_image);
                }
                $profile_image = $unique_file_name;
            }
        }

        $query = "UPDATE users SET username = '$username', password = '$password', email = '$email', fullname = '$fullname', profile_image = '$profile_image', updated_at = '$updated_at' WHERE id = $id";
        mysqli_query($conn, $query);
        header('Location: ' . $admin_url . '/user-list.php');
    }
}
?>
<form method="POST" enctype="multipart/form-data">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo $user['fullname']; ?></h3>
        </div>
        <div class="card-body">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">password (leave blank to keep current password)</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">อีเมล์</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="fullname">ชื่อ</label>
                <input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo $user['fullname']; ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_image">รูปภาพ</label>
                <input type="file" name="profile_image" id="profile_image" class="form-control">
                <?php if ($user['profile_image']): ?>
                    <img src="<?php echo $base_url . '/upload_image/' . $user['profile_image']; ?>" alt="User Image" class="img-thumbnail mt-2" width="150">
                <?php endif; ?>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>บันทึกข้อมูล</button>
            <a href="<?php echo $admin_url . '/user-list.php'; ?>" class='btn btn-secondary'>ย้อนกลับ</a>
        </div>
    </div>
</form>
<?php
$content = ob_get_clean();
$js_script = '';
include 'template_master.php';
?>
