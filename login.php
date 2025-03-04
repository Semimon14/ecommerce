<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $customer = mysqli_fetch_assoc($result);

    if ($customer && password_verify($password, $customer['password'])) {
        $_SESSION['customer_id'] = $customer['id'];
        $_SESSION['customer_name'] = $customer['fullname'];
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    }
}

$title = "Customer Login";
ob_start();
?>

<div class="container mt-5 login-page">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="text-center">เข้าสู่ระบบ</h1>
                    <?php if (isset($_SESSION['error'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
