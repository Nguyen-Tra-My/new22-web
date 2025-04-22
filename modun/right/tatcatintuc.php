<?php
include("connect.php");
$sql = "SELECT id, tieude FROM news ORDER BY id DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

echo "<h3>Tin tức mới</h3><ul>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<li><a href='chitiettintuc.php?id=" . $row['id'] . "'>" . $row['tieude'] . "</a></li>";
}
echo "</ul>";
<h2>Danh sách tin tức</h2>
<?php
while ($row = mysqli_fetch_assoc($result)) {
    echo "<h3><a href='chitiettintuc.php?id={$row['id']}'>{$row['tieude']}</a></h3>";
    if (!empty($row['image'])) {
        echo "<img src='uploads/{$row['image']}' width='150'><br>";
    }
    echo "<p>" . substr(strip_tags($row['noidung']), 0, 100) . "...</p><hr>";
}
?>
<div style="text-align:center;">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" style="margin:0 5px;<?= ($i == $page) ? 'font-weight:bold;' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
echo "<p>Số lượt xem: {$row['views']}</p>";

?>
