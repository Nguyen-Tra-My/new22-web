<?php
include("connect.php");
session_start();

if (!isset($_SESSION['username'])) {
    echo "Bạn cần đăng nhập để sửa tin.";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM news WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $sql_update = "UPDATE news SET tieude='$tieude', noidung='$noidung' WHERE id=$id";
    if (mysqli_query($conn, $sql_update)) {
        echo "Sửa thành công. <a href='index.php'>Về trang chủ</a>";
        exit();
    } else {
        echo "Lỗi sửa.";
    }
}
?>

<h2>Sửa tin tức</h2>
<form method="post">
    Tiêu đề: <input type="text" name="tieude" value="<?= $row['tieude'] ?>" required><br><br>
    Nội dung:<br>
    <textarea name="noidung" rows="10" cols="50"><?= $row['noidung'] ?></textarea><br><br>
    <button type="submit">Cập nhật</button>
</form>
