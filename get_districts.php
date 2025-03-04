<?php
include 'config.php';

$province_id = isset($_GET['province_id']) ? $_GET['province_id'] : 0;
$query = "SELECT * FROM districts WHERE province_id = $province_id";
$result = mysqli_query($conn, $query);

$districts = [];
while ($district = mysqli_fetch_assoc($result)) {
    $districts[] = $district;
}

echo json_encode($districts);
?>
