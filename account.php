<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

$customer_id = $_SESSION['customer_id'];
$query = "SELECT * FROM customers WHERE id = $customer_id";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

$title = "Manage Account";
ob_start();
?>

<div class="container mt-5 account-page">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="?page=profile" class="list-group-item list-group-item-action"><i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว</a>
                <a href="?page=change-password" class="list-group-item list-group-item-action"><i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน</a>
                <a href="?page=orders" class="list-group-item list-group-item-action"><i class="fas fa-list me-2"></i>รายการคำสั่งซื้อ</a>
                <a href="logout.php" class="list-group-item list-group-item-action"><i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ</a>
            </div>
        </div>
        <div class="col-md-9">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'profile';
            switch ($page) {
                case 'profile':
                    include 'account_profile.php';
                    break;
                case 'change-password':
                    include 'account_change_password.php';
                    break;
                case 'orders':
                    include 'account_orders.php';
                    break;
                default:
                    include 'account_profile.php';
                    break;
            }
            ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
