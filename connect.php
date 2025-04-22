<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "new22";

// Kết nối đến MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
