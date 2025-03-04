<?php
include '../config.php';

$district_id = $_GET['district_id'];
$query = "SELECT id, name_th, zip_code FROM sub_districts WHERE district_id = $district_id";
$result = mysqli_query($conn, $query);

$sub_districts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sub_districts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($sub_districts);
?>
