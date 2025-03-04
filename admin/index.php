<?php
    include '../config.php';
    include 'auth.php';

    $page_title = 'Dashboard';
    ob_start();

    // Get total orders this month
    $total_orders_query = "SELECT COUNT(*) as total_orders FROM orders WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
    $total_orders_result = mysqli_query($conn, $total_orders_query);
    $total_orders = mysqli_fetch_assoc($total_orders_result)['total_orders'];

    // Get grand total orders this month
    $grand_total_query = "SELECT SUM(grand_total) as grand_total FROM orders WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())";
    $grand_total_result = mysqli_query($conn, $grand_total_query);
    $grand_total = mysqli_fetch_assoc($grand_total_result)['grand_total'];

    // Get total customers
    $total_customers_query = "SELECT COUNT(*) as total_customers FROM customers";
    $total_customers_result = mysqli_query($conn, $total_customers_query);
    $total_customers = mysqli_fetch_assoc($total_customers_result)['total_customers'];

    // Get total products
    $total_products_query = "SELECT COUNT(*) as total_products FROM products";
    $total_products_result = mysqli_query($conn, $total_products_query);
    $total_products = mysqli_fetch_assoc($total_products_result)['total_products'];

    // Get latest 10 orders
    $latest_orders_query = "SELECT order_no, order_date, order_status, fullname, grand_total FROM orders ORDER BY order_date DESC LIMIT 10";
    $latest_orders_result = mysqli_query($conn, $latest_orders_query);

    // Get latest 6 product orders
    $latest_product_orders_query = "SELECT p.profile_image, p.product_name, od.price, od.quantity FROM order_details od JOIN products p ON od.product_id = p.id ORDER BY od.order_id DESC LIMIT 6";
    $latest_product_orders_result = mysqli_query($conn, $latest_product_orders_query);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                  <div class="inner">
                    <h3><?php echo $total_orders; ?></h3>
                    <p>ยอดสั่งซื้อเดือนนี้</p>
                  </div>
                  <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                  </svg>
                  <a href="order-list.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>฿<?php echo number_format($grand_total, 2); ?></h3>
                    <p>ยอดขายรวมเดือนนี้</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 1.5a.75.75 0 01.75.75v.75h2.25a.75.75 0 010 1.5H12v1.5h3.75a.75.75 0 010 1.5H12v1.5h2.25a.75.75 0 010 1.5H12v1.5h3.75a.75.75 0 010 1.5H12v1.5h2.25a.75.75 0 010 1.5H12v.75a.75.75 0 01-1.5 0v-.75H8.25a.75.75 0 010-1.5H10.5v-1.5H6.75a.75.75 0 010-1.5H10.5v-1.5H8.25a.75.75 0 010-1.5H10.5v-1.5H6.75a.75.75 0 010-1.5H10.5v-1.5H8.25a.75.75 0 010-1.5H10.5V3H8.25a.75.75 0 010-1.5H10.5V.75A.75.75 0 0112 0v.75h2.25a.75.75 0 010 1.5H12v.75a.75.75 0 01-.75.75H9.75a.75.75 0 010-1.5H11.25V1.5H12z"></path>
                </svg>
                <a href="order-list.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3><?php echo $total_customers; ?></h3>
                    <p>ลูกค้าทั้งหมด</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 2.25a5.25 5.25 0 100 10.5 5.25 5.25 0 000-10.5zM3.75 12a8.25 8.25 0 0116.5 0v.75a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V12zM12 15a8.25 8.25 0 00-8.25 8.25v.75a.75.75 0 00.75.75h15a.75.75 0 00.75-.75v-.75A8.25 8.25 0 0012 15z"></path>
                </svg>
                <a href="customer-list.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3><?php echo $total_products; ?></h3>
                    <p>สินค้าทั้งหมด</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 1.5a.75.75 0 01.75.75v.75h2.25a.75.75 0 010 1.5H12v1.5h3.75a.75.75 0 010 1.5H12v1.5h2.25a.75.75 0 010 1.5H12v1.5h3.75a.75.75 0 010 1.5H12v1.5h2.25a.75.75 0 010 1.5H12v.75a.75.75 0 01-1.5 0v-.75H8.25a.75.75 0 010-1.5H10.5v-1.5H6.75a.75.75 0 010-1.5H10.5v-1.5H8.25a.75.75 0 010-1.5H10.5v-1.5H6.75a.75.75 0 010-1.5H10.5v-1.5H8.25a.75.75 0 010-1.5H10.5V3H8.25a.75.75 0 010-1.5H10.5V.75A.75.75 0 0112 0v.75h2.25a.75.75 0 010 1.5H12v.75a.75.75 0 01-.75.75H9.75a.75.75 0 010-1.5H11.25V1.5H12z"></path>
                </svg>
                <a href="product-list.php" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    More info <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">รายการสั่งซื้อล่าสุด</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                        <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive table-orders">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                <th>หมายเลขสั่งซื้อ</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>สถานะ</th>
                                <th>ลูกค้า</th>
                                <th>ยอดรวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($latest_orders_result)): ?>
                                <tr>
                                    <td><?php echo $order['order_no']; ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo $order['order_status'] == 1 ? 'รอดำเนินการ' : ($order['order_status'] == 2 ? 'ชำระเงินเรียบร้อย' : 'จัดส่งเรียบร้อย'); ?></td>
                                    <td><?php echo $order['fullname']; ?></td>
                                    <td>฿<?php echo number_format($order['grand_total'], 2); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <a href="order-list.php" class="btn btn-sm btn-primary float-start">
                        ดูรายการสั่งซื้อทั้งหมด
                    </a>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">รายการสินค้าขายล่าสุด</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive table-orders">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>รูปภาพ</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>ราคา</th>
                                    <th>จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($product_order = mysqli_fetch_assoc($latest_product_orders_result)): ?>
                                    <tr>
                                        <td><img src="<?php echo $base_url . '/upload_image/' . $product_order['profile_image']; ?>" alt="<?php echo $product_order['product_name']; ?>" style="height: 50px;"></td>
                                        <td><?php echo $product_order['product_name']; ?></td>
                                        <td>฿<?php echo number_format($product_order['price'], 2); ?></td>
                                        <td><?php echo $product_order['quantity']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

<?php
    $content = ob_get_clean();
    $js_script = '';
    include 'template_master.php';
?>
