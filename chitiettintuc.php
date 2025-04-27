<?php
session_start();
include("connect.php");
include("modun/header.php");
include("modun/menu.php");
?>
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
<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Lọc ID để đảm bảo là số nguyên

    // Tăng lượt xem
    mysqli_query($conn, "UPDATE news SET views = views + 1 WHERE id = $id");

    // Xử lý thêm bình luận
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment']) && isset($_SESSION['username'])) {
        $comment_content = trim($_POST['comment_content']);
        if (!empty($comment_content)) {
            $comment_content = htmlspecialchars($comment_content); // Chống XSS

            $insert_sql = "INSERT INTO comments (news_id, username, content) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iss", $id, $_SESSION['username'], $comment_content);
            $insert_stmt->execute();
            $insert_stmt->close();
        } else {
            echo "<p class='error'>Vui lòng nhập nội dung bình luận.</p>";
        }
    }

    // Lấy tin tức
    $sql = "SELECT * FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        ?>

        <div class='container'>
            <div class='main-content'>
                <h2><?= htmlspecialchars($row['tieude']) ?></h2>
                
                <!-- Hiển thị hình ảnh nếu có -->
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['tieude']) ?>" style="width:100%; max-width:600px; margin-bottom:20px;">
                <?php endif; ?>

                <p><?= nl2br(htmlspecialchars($row['noidung'])) ?></p>
                <!-- Hiển thị tên tác giả ở góc dưới bên phải -->
                <div class="author">
                    <p>Tác giả: <?= htmlspecialchars($row['author']) ?></p>
                </div>
                <?php if (isset($_SESSION['username'])): ?>
                    <h3>Bình luận</h3>
                    <form method="post" action="chitiettintuc.php?id=<?= $id ?>">
                        <textarea name="comment_content" rows="4" cols="50" required placeholder="Nhập bình luận..."></textarea><br>
                        <button type="submit" name="submit_comment">Gửi bình luận</button>
                    </form>

                    <h3>Danh sách bình luận:</h3>
                    <?php
                    $comment_sql = "SELECT * FROM comments WHERE news_id = ? ORDER BY created_at DESC";
                    $comment_stmt = $conn->prepare($comment_sql);
                    $comment_stmt->bind_param("i", $id);
                    $comment_stmt->execute();
                    $comment_result = $comment_stmt->get_result();

                    if ($comment_result->num_rows > 0) {
                        while ($comment = $comment_result->fetch_assoc()) {
                            echo "<div style='border-bottom:1px solid #ccc;margin-bottom:10px;'>";
                            echo "<strong>" . htmlspecialchars($comment['username']) . "</strong> - <i>" . $comment['created_at'] . "</i><br>";
                            echo nl2br(htmlspecialchars($comment['content']));

                            if ($_SESSION['role'] == 'admin') {
                                echo " <a href='xoabinhluan.php?id=" . $comment['id'] . "&news_id=$id' onclick='return confirm(\"Xoá bình luận này?\");'>[Xoá]</a>";
                            }

                            echo "</div>";
                        }
                    } else {
                        echo "<p>Chưa có bình luận nào.</p>";
                    }
                    $comment_stmt->close();
                    ?>
                <?php else: ?>
                    <p><a href="dangnhap.php">Đăng nhập</a> để bình luận.</p>
                <?php endif; ?>
            </div>
        </div>

        

        <?php
    } else {
        echo "Tin tức không tồn tại.";
    }

    $stmt->close();
} else {
    echo "Không có ID tin tức được chọn.";
}

include("modun/footer.php");
?>
