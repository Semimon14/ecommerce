<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (password_verify($current_password, $customer['password'])) {
        if ($new_password == $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $query = "UPDATE customers SET password = '$hashed_password' WHERE id = $customer_id";
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Password changed successfully.";
            } else {
                $_SESSION['error'] = "Failed to change password. Please try again.";
            }
        } else {
            $_SESSION['error'] = "New passwords do not match.";
        }
    } else {
        $_SESSION['error'] = "Current password is incorrect.";
    }
}
?>

<h3>เปลี่ยนรหัสผ่าน</h3>
<?php if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])) : ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<form method="post" action="">
    <div class="mb-3">
        <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน</label>
        <input type="password" class="form-control" id="current_password" name="current_password" required>
    </div>
    <div class="mb-3">
        <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label">ยืนยันรหัสผ่านใหม่</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn btn-primary">เปลี่ยนรหัสผ่าน</button>
</form>
