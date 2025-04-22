<?php
include("connect.php");
session_start();

if (!isset($_SESSION['username'])) {
    echo "Bạn cần đăng nhập để xoá tin.";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Lọc ID để đảm bảo là số nguyên

    // Sử dụng prepared statement để xóa tin tức
    $delete_sql = "DELETE FROM news WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        // Xóa file ảnh (nếu có)
        $select_image_sql = "SELECT image FROM news WHERE id = ?";
        $select_image_stmt = $conn->prepare($select_image_sql);
        $select_image_stmt->bind_param("i", $id);
        $select_image_stmt->execute();
        $image_result = $select_image_stmt->get_result();
        $image_row = $image_result->fetch_assoc();

        if ($image_row && !empty($image_row['image'])) {
            $image_path = __DIR__ . "/uploads/" . $image_row['image']; // Đường dẫn tuyệt đối
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $select_image_stmt->close();

        echo "Đã xoá thành công. <a href='index.php'>Quay lại</a>";
    } else {
        echo "Lỗi xoá tin: " . $conn->error;
    }

    $delete_stmt->close();
} else {
    echo "Không có ID để xoá.";
}
?>