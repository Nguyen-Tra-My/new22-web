<?php
session_start();
include('connect.php');

// Kiểm tra quyền admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

// Lấy danh sách chuyên mục
$chuyenmuc_result = mysqli_query($conn, "SELECT * FROM chuyenmuc");

// Lấy số trang từ GET (nếu có), nếu không thì mặc định là trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;  // Số lượng bài viết mỗi trang
$offset = ($page - 1) * $limit;  // Xác định offset bắt đầu lấy bài viết

// Lấy danh sách bài viết với phân trang
$sql = "SELECT * FROM news ORDER BY id DESC LIMIT $limit OFFSET $offset";
if (isset($_GET['chuyenmuc_id'])) {
    $chuyenmuc_id = $_GET['chuyenmuc_id'];
    $sql = "SELECT * FROM news WHERE chuyenmuc_id = $chuyenmuc_id ORDER BY id DESC LIMIT $limit OFFSET $offset";
}
$result = mysqli_query($conn, $sql);

// Xóa bài viết nếu yêu cầu
if (isset($_GET['delete_id']) && $is_admin) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM news WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Bài viết đã được xóa!');</script>";
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Lỗi khi xóa bài viết.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('modun/header.php'); ?>
    <?php include('modun/menu.php'); ?>
    <style>
    input[name="keyword"]:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    button[type="submit"]:hover {
        background-color: #45a049;
    }
    </style>
    <!-- Form tìm kiếm -->
    <form method="GET" action="timkiem.php" style="text-align: center; margin: 30px 0;">
        <input 
            type="text" 
            name="keyword" 
            placeholder="Nhập từ khóa..." 
            required
            style="width: 300px; padding: 10px; border: 1px solid #ccc; border-radius: 20px; font-size: 16px; outline: none; transition: 0.3s;">
        <button 
            type="submit" 
            style="padding: 10px 20px; margin-left: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 20px; font-size: 16px; cursor: pointer; transition: 0.3s;">
            Tìm kiếm
        </button>
    </form>

    <!-- Hiển thị các chuyên mục -->
    <div class="chuyenmuc">
        <h3>Chuyên mục</h3>
        <ul>
            <?php while ($chuyenmuc = mysqli_fetch_assoc($chuyenmuc_result)): ?>
                <li><a href="?chuyenmuc_id=<?= $chuyenmuc['id'] ?>"><?= $chuyenmuc['ten'] ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
    
    <?php if ($is_admin): ?>
        <div class="admin-functions">
            <a href="themtintuc.php">Thêm bài viết</a> |
            <a href="tatcatintuc.php">Quản lý bài viết</a> |
            <a href="thongke.php">Thống kê</a>
        </div>
        <hr>
    <?php endif; ?>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h3><a href='chitiettintuc.php?id={$row['id']}'>{$row['tieude']}</a></h3>";
            if (!empty($row['image'])) {
                echo "<img src='uploads/{$row['image']}' width='150'><br>";
            }
            echo "<p>" . substr(strip_tags($row['noidung']), 0, 100) . "...</p><hr>";
            // Thêm liên kết xóa bài viết
            if ($is_admin) {
                echo "<a href='?delete_id={$row['id']}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa bài viết này?\")'>Xóa bài viết</a><br><hr>";
            }
        }
    } else {
        echo "<p>Không có bài viết nào.</p>";
    }
    ?>

    <div style="text-align:center;">
        <!-- Phân trang -->
        <?php
        $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM news");
        $total_row = mysqli_fetch_assoc($total_result);
        $total_pages = ceil($total_row['total'] / $limit); // Tính tổng số trang
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=$i' style='margin:0 5px;'>$i</a>";
        }
        ?>
    </div>

    <?php include('modun/footer.php'); ?>
</body>
</html>
