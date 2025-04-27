<?php 
include("connect.php");
include("modun/header.php");
include("modun/menu.php");

// Thiết lập phân trang
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
    $key = trim($_GET['keyword']);
    $key_esc = mysqli_real_escape_string($conn, $key);
    $search_term = '%' . $key_esc . '%'; // Bọc % đúng cách

    // Chỉ tìm kiếm trong tiêu đề
    $sql = "SELECT * FROM news WHERE tieude LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sii', $search_term, $limit, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Đếm tổng số kết quả (chỉ tiêu đề)
    $count_sql = "SELECT COUNT(*) AS total FROM news WHERE tieude LIKE ?";
    $count_stmt = mysqli_prepare($conn, $count_sql);
    mysqli_stmt_bind_param($count_stmt, 's', $search_term);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_results = $count_row['total'];
    $total_pages = ceil($total_results / $limit);

    echo "<h2>Kết quả tìm kiếm theo tiêu đề cho: <i>" . htmlspecialchars($key) . "</i></h2>";

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="news-item">';
            echo '<h3><a href="chitiettintuc.php?id=' . $row['id'] . '">' . htmlspecialchars($row['tieude']) . '</a></h3>';
            echo '<p>' . substr(strip_tags($row['noidung']), 0, 200) . '...</p>';
            echo '</div><hr>';
        }

        // Phân trang
        echo '<div style="text-align:center;">';
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a href="timkiem.php?keyword=' . urlencode($key) . '&page=' . $i . '" style="margin:0 5px;">' . $i . '</a>';
        }
        echo '</div>';
    } else {
        echo "<p>Không tìm thấy bài viết nào có tiêu đề phù hợp.</p>";
    }
} else {
    echo "<p>Vui lòng nhập từ khóa để tìm kiếm.</p>";
}
?>

<?php include("modun/footer.php"); ?>
