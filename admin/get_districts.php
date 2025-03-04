<?php
include '../config.php';

$province_id = $_GET['province_id'];
$query = "SELECT id, name_th FROM districts WHERE province_id = $province_id";
$result = mysqli_query($conn, $query);

$districts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $districts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($districts);
?>
