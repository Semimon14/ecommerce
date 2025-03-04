<?php
include '../config.php';
include 'auth.php';

$id = $_GET['id'];

// Get the image filename
$result = mysqli_query($conn, "SELECT profile_image FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($result);
$profile_image = $user['profile_image'];

// Delete the user
$query = "DELETE FROM users WHERE id = $id";
mysqli_query($conn, $query);

// Delete the image file
if ($profile_image) {
    $image_path = "../upload_image/" . $profile_image;
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

header('Location: ' . $admin_url . '/user-list.php');
?>
