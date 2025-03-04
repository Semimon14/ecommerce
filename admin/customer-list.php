<?php
include '../config.php';
include 'auth.php';
$page_title = 'ลูกค้า';
ob_start();

// Handle search
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';

// Handle sorting
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build query
$query = "SELECT * FROM customers WHERE 1=1";
if ($search_keyword) {
    $query .= " AND (email LIKE '%$search_keyword%' OR fullname LIKE '%$search_keyword%' OR tel LIKE '%$search_keyword%')";
}
$query .= " ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get total records for pagination
$total_query = "SELECT COUNT(*) as total FROM customers WHERE 1=1";
if ($search_keyword) {
    $total_query .= " AND (email LIKE '%$search_keyword%' OR fullname LIKE '%$search_keyword%' OR tel LIKE '%$search_keyword%')";
}
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Toggle sort order
$toggle_sort_order = $sort_order == 'asc' ? 'desc' : 'asc';

// Function to display sort icon
function get_sort_icon($column, $sort_column, $sort_order) {
    if ($column == $sort_column) {
        return $sort_order == 'asc' ? 'bi bi-sort-alpha-down' : 'bi bi-sort-alpha-down-alt';
    }
    return '';
}
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">ลูกค้า</h3>
        <div class="card-tools">
        <a href="<?php echo $admin_url; ?>/customer-add.php" class="btn btn-primary">เพิ่มลูกค้า</a>
        </div>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" name="search_keyword" class="form-control" placeholder="ค้นหาลูกค้า" value="<?php echo $search_keyword; ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 100px;"><a href="?sort_column=id&sort_order=<?php echo $toggle_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>">ID <i class="<?php echo get_sort_icon('id', $sort_column, $sort_order); ?>"></i></a></th>
                    <th><a href="?sort_column=email&sort_order=<?php echo $toggle_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>">อีเมล์ <i class="<?php echo get_sort_icon('email', $sort_column, $sort_order); ?>"></i></a></th>
                    <th><a href="?sort_column=fullname&sort_order=<?php echo $toggle_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>">ชื่อ <i class="<?php echo get_sort_icon('fullname', $sort_column, $sort_order); ?>"></i></a></th>
                    <th><a href="?sort_column=tel&sort_order=<?php echo $toggle_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>">เบอร์โทรศัพท์ <i class="<?php echo get_sort_icon('tel', $sort_column, $sort_order); ?>"></i></a></th>
                    <th style="width: 200px;">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['fullname']; ?></td>
                        <td><?php echo $row['tel']; ?></td>
                        <td>
                            <a href="<?php echo $admin_url . '/customer-edit.php?id=' . $row['id']; ?>" class='btn btn-warning'>
                            <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                            <a href="<?php echo $admin_url . '/customer-delete.php?id=' . $row['id']; ?>" class='btn btn-danger' onclick="return confirm('ยืนยันการลบลูกค้านี้?');">
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
                    <a class="page-link" href="?page=<?php echo $i; ?>&search_keyword=<?php echo $search_keyword; ?>&sort_column=<?php echo $sort_column; ?>&sort_order=<?php echo $sort_order; ?>"><?php echo $i; ?></a>
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
