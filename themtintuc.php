<?php
session_start();
include('connect.php');

// Kiểm tra quyền admin
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Lấy danh sách chuyên mục
$chuyenmuc_result = mysqli_query($conn, "SELECT * FROM chuyenmuc");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $chuyenmuc_id = $_POST['chuyenmuc_id'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Upload ảnh
    if ($image) {
        move_uploaded_file($image_tmp, "uploads/$image");
    }

    // Thêm bài viết vào CSDL
    $query = "INSERT INTO news (tieude, noidung, image, chuyenmuc_id) VALUES ('$tieude', '$noidung', '$image', '$chuyenmuc_id')";
    mysqli_query($conn, $query);

    // Quay lại trang chủ sau khi thêm bài viết
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm bài viết</title>
</head>
<body>
    <h2>Thêm bài viết mới</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="tieude">Tiêu đề:</label>
        <input type="text" name="tieude" required><br><br>
        
        <label for="noidung">Nội dung:</label><br>
        <textarea name="noidung" rows="5" cols="40" required></textarea><br><br>

        <label for="chuyenmuc_id">Chuyên mục:</label>
        <select name="chuyenmuc_id" required>
            <?php while ($row = mysqli_fetch_assoc($chuyenmuc_result)): ?>
                <option value="<?= $row['id'] ?>"><?= $row['ten'] ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="image">Ảnh:</label>
        <input type="file" name="image"><br><br>

        <button type="submit">Thêm bài viết</button>
    </form>
</body>
</html>
