<?php
include("connect.php");

// Phân trang: lấy số trang từ GET, mặc định là trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Số lượng bài viết mỗi trang
$offset = ($page - 1) * $limit; // Xác định offset bắt đầu lấy bài viết

// Lấy tổng số bài viết để tính phân trang
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM news");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit); // Tổng số trang

// Lấy 10 bài viết cho trang hiện tại (phân trang)
$sql_news = "SELECT id, tieude, image, noidung, views FROM news ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result_news = mysqli_query($conn, $sql_news);

// Hiển thị phần tin tức mới
$sql_newest = "SELECT id, tieude FROM news ORDER BY id DESC LIMIT 10";
$result_newest = mysqli_query($conn, $sql_newest);
echo "<h3>Tin tức mới</h3><ul>";
while ($row = mysqli_fetch_assoc($result_newest)) {
    echo "<li><a href='chitiettintuc.php?id=" . $row['id'] . "'>" . $row['tieude'] . "</a></li>";
}
echo "</ul>";

// Hiển thị danh sách tin tức (phân trang)
echo "<h2>Danh sách tin tức</h2>";
if (mysqli_num_rows($result_news) > 0) {
    while ($row = mysqli_fetch_assoc($result_news)) {
        echo "<h3><a href='chitiettintuc.php?id={$row['id']}'>{$row['tieude']}</a></h3>";
        if (!empty($row['image'])) {
            echo "<img src='uploads/{$row['image']}' width='150'><br>";
        }
        echo "<p>" . substr(strip_tags($row['noidung']), 0, 100) . "...</p>";
        echo "<p>Số lượt xem: {$row['views']}</p><hr>";
    }
} else {
    echo "<p>Không có bài viết nào trong danh sách này.</p>";
}

// Phân trang
echo "<div style='text-align:center;'>";
for ($i = 1; $i <= $total_pages; $i++) {
    // Hiển thị các trang phân trang, bôi đậm trang hiện tại
    echo "<a href='?page=$i' style='margin:0 5px;" . ($i == $page ? 'font-weight:bold;' : '') . "'>$i</a>";
}
echo "</div>";
?>
