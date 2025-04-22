<?php
session_start();
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Truy cập bị từ chối. Trang này chỉ dành cho Admin.";
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users");
echo "<h2>Quản lý người dùng</h2>";
echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Email</th><th>Vai trò</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['email']}</td><td>{$row['role']}</td></tr>";
}
echo "</table>";
// Tổng số bài viết
$total_news_result = mysqli_query($conn, "SELECT COUNT(*) AS total_news FROM news");
$total_news_row = mysqli_fetch_assoc($total_news_result);

// Tổng số bình luận
$total_comments_result = mysqli_query($conn, "SELECT COUNT(*) AS total_comments FROM comments");
$total_comments_row = mysqli_fetch_assoc($total_comments_result);

// Tổng số người dùng
$total_users_result = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$total_users_row = mysqli_fetch_assoc($total_users_result);
?>

<h2>Thống kê hệ thống</h2>
<ul>
    <li>Tổng số bài viết: <?= $total_news_row['total_news'] ?></li>
    <li>Tổng số bình luận: <?= $total_comments_row['total_comments'] ?></li>
    <li>Tổng số người dùng: <?= $total_users_row['total_users'] ?></li>
</ul>
