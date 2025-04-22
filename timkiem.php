<?php
include("connect.php");
include("modun/header.php");
include("modun/menu.php");

if (isset($_GET['keyword'])) {
    $key = mysqli_real_escape_string($conn, $_GET['keyword']);
    $sql = "SELECT * FROM news WHERE tieude LIKE '%$key%' OR noidung LIKE '%$key%'";
    $result = mysqli_query($conn, $sql);

    echo "<h2>Kết quả tìm kiếm cho: <i>$key</i></h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="news-item">';
        echo '<h3><a href="chitiettintuc.php?id=' . $row['id'] . '">' . $row['tieude'] . '</a></h3>';
        echo '<p>' . substr($row['noidung'], 0, 200) . '...</p>';
        echo '</div>';
    }
}
?>

<form method="get">
    <input type="text" name="keyword" placeholder="Tìm kiếm...">
    <button type="submit">Tìm</button>
</form>

<?php include("modun/footer.php"); ?>
