<?php
$query = "SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>

<h3>รายการคำสั่งซื้อ</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>หมายเลขคำสั่งซื้อ</th>
            <th>วันที่สั่งซื้อ</th>
            <th>สถานะ</th>
            <th>ยอดรวม</th>
            <th>รายละเอียด</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($order = mysqli_fetch_assoc($result)) : ?>
            <?php $order['order_date'] = date('d/m/Y H:i:s', strtotime($order['order_date'])); ?>
            <tr>
                <td><?php echo $order['order_no']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo $order['order_status'] == 1 ? 'รอดำเนินการ' : ($order['order_status'] == 2 ? 'ชำระเงินเรียบร้อย' : 'จัดส่งเรียบร้อย'); ?></td>
                <td>฿<?php echo number_format($order['grand_total'], 2); ?></td>
                <td><a href="account_order_details.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">ดูรายละเอียด</a></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
