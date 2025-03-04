<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/fontawesome/css/solid.min.css">
    <link rel="stylesheet" href="assets/fontawesome/css/brands.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="cover-spin"></div>
    <header class="bg-primary py-3 header">
        <div class="container">
            <div class="d-flex align-items-center">
                <a href="index.php" class="text-decoration-none text-white">
                    <img src="<?php echo $base_url; ?>/assets/image/logo.png" alt="Logo" class="me-3" style="height: 50px;">                    
                </a>                
                <h1 class="text-white">คอมพิวเตอร์มาร์ท</h1>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> หน้าแรก</a></li>
                            <li class="nav-item"><a class="nav-link" href="products.php"><i class="fas fa-box-open"></i> สินค้า</a></li>
                            <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> ตะกร้า (<?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>)</a></li>
                            <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope"></i> ติดต่อเรา</a></li>
                            <?php if (isset($_SESSION['customer_id'])) : ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user"></i> บัญชีของฉัน
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                                        <li><a class="dropdown-item" href="account.php?page=profile"><i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว</a></li>
                                        <li><a class="dropdown-item" href="account.php?page=change-password"><i class="fas fa-key me-2"></i>เปลี่ยนรหัสผ่าน</a></li>
                                        <li><a class="dropdown-item" href="account.php?page=orders"><i class="fas fa-list me-2"></i>รายการคำสั่งซื้อ</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                                    </ul>
                                </li>
                            <?php else : ?>
                                <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ</a></li>
                                <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> สมัครสมาชิก</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <?php echo $content; ?>
    </main>
    <footer class="py-3 mt-5 footer">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <img src="<?php echo $base_url; ?>/assets/image/logo.png" alt="Logo" style="height: 60px;">
                <ul class="navbar-nav d-flex flex-row ms-3">
                    <li class="nav-item"><a class="nav-link" href="index.php">หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">สินค้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">ตะกร้า</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">ติดต่อเรา</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <?php echo $js_script ?? ''; ?>
</body>
</html>
