<?php
    include '../config.php';
    include 'auth.php';
    $page_title = 'รายการสินค้า';
    ob_start();

    // Handle search and filter
    $search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

    // Handle sorting
    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
    $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc';
    $next_sort_order = $sort_order == 'asc' ? 'desc' : 'asc';

    // Pagination settings
    $limit = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Build query
    $query = "SELECT p.id, p.product_name, p.price, c.category_name FROM products p INNER JOIN categories c ON p.category_id = c.id WHERE 1=1";
    if ($search_keyword) {
        $query .= " AND p.product_name LIKE '%$search_keyword%'";
    }
    if ($category_id) {
        $query .= " AND p.category_id = $category_id";
    }
    $query .= " ORDER BY $sort_by $sort_order";
    $query .= " LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $query);

    // Get total records for pagination
    $total_query = "SELECT COUNT(*) as total FROM products p WHERE 1=1";
    if ($search_keyword) {
        $total_query .= " AND p.product_name LIKE '%$search_keyword%'";
    }
    if ($category_id) {
        $total_query .= " AND p.category_id = $category_id";
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
        <h3 class="card-title">รายการสินค้า</h3>
        <div class="card-tools">
        <a href="<?php echo $admin_url; ?>/product-add.php" class="btn btn-primary">เพิ่มสินค้า</a>
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
                <div class="col-md-4">
                    <input type="text" name="search_keyword" class="form-control" placeholder="ค้นหาสินค้า" value="<?php echo $search_keyword; ?>">
                </div>
                <div class="col-md-4">
                    <select name="category_id" class="form-select">
                        <option value="">เลือกหมวดหมู่</option>
                        <?php
                            $categories_result = mysqli_query($conn, "SELECT id, category_name FROM categories");
                            while ($category = mysqli_fetch_assoc($categories_result)) {
                                $selected = $category['id'] == $category_id ? 'selected' : '';
                                echo "<option value='{$category['id']}' $selected>{$category['category_name']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                </div>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 100px;"><a href="?sort_by=id&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&category_id=<?php echo $category_id; ?>">ID <?php echo get_sort_icon($sort_by, $sort_order, 'id'); ?></a></th>
                    <th><a href="?sort_by=product_name&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&category_id=<?php echo $category_id; ?>">สินค้า <?php echo get_sort_icon($sort_by, $sort_order, 'product_name'); ?></a></th>
                    <th style="width: 200px;">ราคา</th>
                    <th><a href="?sort_by=category_name&sort_order=<?php echo $next_sort_order; ?>&search_keyword=<?php echo $search_keyword; ?>&category_id=<?php echo $category_id; ?>">หมวดหมู่ <?php echo get_sort_icon($sort_by, $sort_order, 'category_name'); ?></a></th>
                    <th style="width: 200px;">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo number_format($row['price'], 2) ?></td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td>
                            <a href="<?php echo $admin_url . '/product-edit.php?id=' . $row['id']; ?>" class='btn btn-warning'>
                            <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                            <a href="<?php echo $admin_url . '/product-delete.php?id=' . $row['id']; ?>" class='btn btn-danger' onclick="return confirm('ยืนยันการลบสินค้านี้?');">
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
                    <a class="page-link" href="?page=<?php echo $i; ?>&search_keyword=<?php echo $search_keyword; ?>&category_id=<?php echo $category_id; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>"><?php echo $i; ?></a>
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
