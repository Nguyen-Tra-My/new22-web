<?php
session_start();
include('connect.php');

// Lấy ID chuyên mục từ GET
$chuyenmuc_id = isset($_GET['chuyenmuc_id']) ? $_GET['chuyenmuc_id'] : 'all'; // mặc định là 'all'

// Kiểm tra quyền admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

// Lấy danh sách chuyên mục có sẵn từ cơ sở dữ liệu (bao gồm các chuyên mục mặc định và thêm sau này)
$default_categories = [
    'Thời sự',
    'Chính trị',
    'Y tế',
    'Giáo dục',
    'Khoa học',
    'Giải trí'
];

// Lấy danh sách chuyên mục từ cơ sở dữ liệu (trừ các chuyên mục mặc định)
$additional_categories_result = mysqli_query($conn, "SELECT * FROM chuyenmuc WHERE ten NOT IN ('Thời sự', 'Chính trị', 'Y tế', 'Giáo dục', 'Khoa học', 'Giải trí')");

// Phân trang: lấy số trang từ GET, mặc định là trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Số lượng bài viết mỗi trang
$offset = ($page - 1) * $limit; // Xác định offset bắt đầu lấy bài viết

// Lấy bài viết của chuyên mục nếu có
$sql = "SELECT * FROM news ORDER BY id DESC LIMIT $limit OFFSET $offset"; // Lấy tất cả bài viết

if ($chuyenmuc_id != 'all') {
    // Nếu chọn chuyên mục cụ thể
    if (in_array($chuyenmuc_id, $default_categories)) {
        $sql = "SELECT * FROM news WHERE chuyenmuc_id = (SELECT id FROM chuyenmuc WHERE ten = '$chuyenmuc_id') ORDER BY id DESC LIMIT $limit OFFSET $offset";
    } else {
        // Lấy bài viết theo chuyên mục ID từ cơ sở dữ liệu
        $sql = "SELECT * FROM news WHERE chuyenmuc_id = '$chuyenmuc_id' ORDER BY id DESC LIMIT $limit OFFSET $offset";
    }
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyên mục</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('modun/header.php'); ?>
    <?php include('modun/menu.php'); ?>

    <!-- Hiển thị các chuyên mục có sẵn -->
    <div class="chuyenmuc">
        <h3>Chuyên mục</h3>
        <ul>
            <?php
            // Hiển thị các chuyên mục mặc định
            foreach ($default_categories as $category) {
                echo "<li><a href='chuyenmuc.php?chuyenmuc_id=$category'>$category</a></li>";
            }

            // Kiểm tra và hiển thị chuyên mục "Xem tất cả" nếu có chuyên mục thêm
            if (mysqli_num_rows($additional_categories_result) > 0) {
                echo "<li><a href='chuyenmuc.php?chuyenmuc_id=all'>Xem tất cả</a></li>";
            }
            ?>
        </ul>
    </div>

    <h2>Bài viết trong chuyên mục</h2>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h3><a href='chitiettintuc.php?id={$row['id']}'>{$row['tieude']}</a></h3>";
            if (!empty($row['image'])) {
                echo "<img src='uploads/{$row['image']}' width='150'><br>";
            }
            echo "<p>" . substr(strip_tags($row['noidung']), 0, 100) . "...</p><hr>";
        }
    } else {
        echo "<p>Không có bài viết nào trong chuyên mục này.</p>";
    }
    ?>

    <div style="text-align:center;">
        <!-- Phân trang -->
        <?php
        // Nếu có 'chuyenmuc_id', lấy tổng số bài viết trong chuyên mục đó
        if ($chuyenmuc_id != 'all') {
            $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM news WHERE chuyenmuc_id = '$chuyenmuc_id'");
        } else {
            // Nếu chọn "Xem tất cả", lấy tổng số bài viết
            $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM news");
        }
        
        $total_row = mysqli_fetch_assoc($total_result);
        $total_pages = ceil($total_row['total'] / $limit);

        for ($i = 1; $i <= $total_pages; $i++) {
            $chuyenmuc_link = isset($_GET['chuyenmuc_id']) ? "chuyenmuc_id=" . $_GET['chuyenmuc_id'] . "&" : "";
            echo "<a href='?{$chuyenmuc_link}page=$i' style='margin:0 5px;'>$i</a>";
        }
        ?>
    </div>

    <?php if ($is_admin): ?>
        <div class="admin-functions">
            <a href="themtintuc.php">Thêm bài viết</a> |
            <a href="tatcatintuc.php">Quản lý bài viết</a> |
            <a href="thongke.php">Thống kê</a>
        </div>
        <hr>
    <?php endif; ?>

    <?php include('modun/footer.php'); ?>
</body>
</html>
