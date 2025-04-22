<?php
include("connect.php");
session_start();

if ($_SESSION['role'] == 'admin' && isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);
    $news_id = intval($_GET['news_id']);
    mysqli_query($conn, "DELETE FROM comments WHERE id = $comment_id");
    header("Location: chitiettintuc.php?id=$news_id");
}
?>
