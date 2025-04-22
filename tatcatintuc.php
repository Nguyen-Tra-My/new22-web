<?php
include("connect.php");

// Lấy 10 tin tức mới nhất
$sql_latest = "SELECT id, tieude FROM news ORDER BY created_at DESC LIMIT 10";
$result_latest = mysqli_query($conn, $sql_latest);

echo "<h3>Tin tức mới</h3><ul>";
if (mysqli_num_rows($result_latest) > 0) {
    while ($row_latest = mysqli_fetch_assoc($result_latest)) {
        echo "<li><a href='chitiettintuc.php?id=" . $row_latest['id'] . "'>" . htmlspecialchars($row_latest['tieude']) . "</a></li>";
    }
} else {
    echo "<li>Không có tin tức nào.</li>";
}
echo "</ul>";

echo "<h2>Danh sách tin tức</h2>";

// Lấy tất cả tin tức (Cần phân trang ở đây)
$sql_all = "SELECT id, tieude, noidung, image, views FROM news ORDER BY created_at DESC";
$result_all = mysqli_query($conn, $sql_all);

if (mysqli_num_rows($result_all) > 0) {
    while ($row_all = mysqli_fetch_assoc($result_all)) {
        echo "<h3><a href='chitiettintuc.php?id=" . $row_all['id'] . "'>" . htmlspecialchars($row_all['tieude']) . "</a></h3>";
        if (!empty($row_all['image'])) {
            echo "<img src='uploads/" . htmlspecialchars($row_all['image']) . "' width='150'><br>"; // Chống XSS
        }
        echo "<p>" . substr(strip_tags(htmlspecialchars($row_all['noidung'])), 0, 100) . "...</p><hr>"; // Chống XSS
    }
} else {
    echo "<p>Không có tin tức nào.</p>";
}

// Phân trang (Cần logic phân trang đầy đủ)
$total_rows = mysqli_num_rows($result_all); // Tổng số tin tức
$per_page = 10; // Số tin tức trên mỗi trang
$total_pages = ceil($total_rows / $per_page); // Tổng số trang

$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Trang hiện tại
$start = ($page - 1) * $per_page; // Vị trí bắt đầu lấy tin tức

// Truy vấn có LIMIT và OFFSET để phân trang
$sql_paged = "SELECT id, tieude, noidung, image, views FROM news ORDER BY created_at DESC LIMIT $start, $per_page";
$result_paged = mysqli_query($conn, $sql_paged);

echo "<div style='text-align:center;'>";
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=" . $i . "' style='margin:0 5px;" . (($i == $page) ? 'font-weight:bold;' : '') . "'>" . $i . "</a>";
}
echo "</div>";

// Hiển thị số lượt xem (Cần đặt đúng vị trí và xử lý từng tin)
//echo "<p>Số lượt xem: " . htmlspecialchars($row['views']) . "</p>"; // Sai vị trí và biến
?>