<?php
include 'config.php';

$district_id = isset($_GET['district_id']) ? $_GET['district_id'] : 0;
$query = "SELECT * FROM sub_districts WHERE district_id = $district_id";
$result = mysqli_query($conn, $query);

$sub_districts = [];
while ($sub_district = mysqli_fetch_assoc($result)) {
    $sub_districts[] = $sub_district;
}

echo json_encode($sub_districts);
?>
