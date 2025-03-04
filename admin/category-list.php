<?php
include '../config.php';
include 'auth.php';
$page_title = 'หมวดหมู่สินค้า';
ob_start();

// Handle search
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build query
$query = "SELECT * FROM categories WHERE 1=1";
if ($search_keyword) {
    $query .= " AND category_name LIKE '%$search_keyword%'";
}
$query .= " LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get total records for pagination
$total_query = "SELECT COUNT(*) as total FROM categories WHERE 1=1";
if ($search_keyword) {
    $total_query .= " AND category_name LIKE '%$search_keyword%'";
}
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">หมวดหมู่สินค้า</h3>
        <div class="card-tools">
        <a href="<?php echo $admin_url; ?>/category-add.php" class="btn btn-primary">เพิ่มหมวดหมู่</a>
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
                    <input type="text" name="search_keyword" class="form-control" placeholder="ค้นหาหมวดหมู่" value="<?php echo $search_keyword; ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>หมวดหมู่</th>
                    <th style="width: 200px;">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td>
                            <a href="<?php echo $admin_url . '/category-edit.php?id=' . $row['id']; ?>" class='btn btn-warning'>
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                            <a href="<?php echo $admin_url . '/category-delete.php?id=' . $row['id']; ?>" class='btn btn-danger' onclick="return confirm('ยืนยันการลบหมวดหมู่นี้?');">
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
                    <a class="page-link" href="?page=<?php echo $i; ?>&search_keyword=<?php echo $search_keyword; ?>"><?php echo $i; ?></a>
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
