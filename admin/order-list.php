<?php
include '../config.php';
include 'auth.php';
$page_title = 'รายการสั่งซื้อ';
ob_start();

// Handle search and filter
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';
$order_status_filter = isset($_GET['order_status']) ? $_GET['order_status'] : '';

// Handle sorting
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc';
$next_sort_order = $sort_order == 'asc' ? 'desc' : 'asc';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build query
$query = "SELECT * FROM orders WHERE 1=1";
if ($search_keyword) {
    $query .= " AND (order_no LIKE '%$search_keyword%' OR fullname LIKE '%$search_keyword%' OR email LIKE '%$search_keyword%' OR tel LIKE '%$search_keyword%' OR tracking_no LIKE '%$search_keyword%')";
}
if ($order_status_filter) {
    $query .= " AND order_status = '$order_status_filter'";
}
$query .= " ORDER BY $sort_by $sort_order LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get total records for pagination
$total_query = "SELECT COUNT(*) as total FROM orders WHERE 1=1";
if ($search_keyword) {
    $total_query .= " AND (order_no LIKE '%$search_keyword%' OR fullname LIKE '%$search_keyword%' OR email LIKE '%$search_keyword%' OR tel LIKE '%$search_keyword%' OR tracking_no LIKE '%$search_keyword%')";
}
if ($order_status_filter) {
    $total_query .= " AND order_status = '$order_status_filter'";
}
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Determine sort icon
function get_sort_icon($current_sort_by, $current_sort_order, $column) {
    if ($current_sort_by == $column) {
        return $current_sort_order == 'asc' ? '<i class="bi bi-sort-alpha-down"></i>' : '<i class="bi bi-sort-alpha-down-alt"></i>';
    }
    return '';
}
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการสั่งซื้อ</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search_keyword" class="form-control" placeholder="ค้นหารายการสั่งซื้อ" value="<?php echo $search_keyword; ?>">
                </div>
                <div class="col-md-4">
                    <select name="order_status" class="form-select">
                        <option value="">ทุกสถานะ</option>
                        <option value="1" <?php echo $order_status_filter == '1' ? 'selected' : ''; ?>>รอดำเนินการ</option>
                        <option value="2" <?php echo $order_status_filter == '2' ? 'selected' : ''; ?>>ชำระเงินเรียบร้อย</option>
                        <option value="3" <?php echo $order_status_filter == '3' ? 'selected' : ''; ?>>จัดส่งเรียบร้อย</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><a href="?sort_by=id&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>">ID <?php echo get_sort_icon($sort_by, $sort_order, 'id'); ?></a></th>
                    <th><a href="?sort_by=order_no&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>">หมายเลขสั่งซื้อ <?php echo get_sort_icon($sort_by, $sort_order, 'order_no'); ?></a></th>
                    <th><a href="?sort_by=order_date&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>">วันที่สั่งซื้อ <?php echo get_sort_icon($sort_by, $sort_order, 'order_date'); ?></a></th>
                    <th>สถานะ</th>
                    <th><a href="?sort_by=fullname&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>">ชื่อผู้สั่งซื้อ <?php echo get_sort_icon($sort_by, $sort_order, 'fullname'); ?></a></th>
                    <th><a href="?sort_by=email&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>">อีเมล์ <?php echo get_sort_icon($sort_by, $sort_order, 'email'); ?></a></th>
                    <th><a href="?sort_by=tel&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>">เบอร์โทรศัพท์ <?php echo get_sort_icon($sort_by, $sort_order, 'tel'); ?></a></th>
                    <th>ยอดรวม</th>
                    <th><a href="?sort_by=tracking_no&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>">หมายเลขพัสดุติดตาม <?php echo get_sort_icon($sort_by, $sort_order, 'tracking_no'); ?></a></th>
                    <th style="width: 200px;">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php $row['order_date'] = date('d/m/Y H:i:s', strtotime($row['order_date'])); ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['order_no']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['order_status'] == 1 ? 'รอดำเนินการ' : ($row['order_status'] == 2 ? 'ชำระเงินเรียบร้อย' : 'จัดส่งเรียบร้อย'); ?></td>
                        <td><?php echo $row['fullname']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['tel']; ?></td>
                        <td><?php echo number_format($row['grand_total'], 2); ?></td>
                        <td><?php echo $row['tracking_no']; ?></td>
                        <td>
                            <a href="<?php echo $admin_url . '/order-view.php?id=' . $row['id']; ?>" class='btn btn-info'>
                            <i class="bi bi-eye me-1"></i>View
                            </a>
                            <a href="<?php echo $admin_url . '/order-delete.php?id=' . $row['id']; ?>" class='btn btn-danger' onclick="return confirm('ยืนยันการลบรายการสั่งซื้อนี้?');">
                            <i class="bi bi-trash me-1"></i>Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-end">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search_keyword=<?php echo $search_keyword; ?>&order_status=<?php echo $order_status_filter; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</div>
<?php
    $content = ob_get_clean();
    $js_script = '';
    include 'template_master.php';
?>
