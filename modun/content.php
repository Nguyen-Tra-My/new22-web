<?php
$sql = "SELECT * FROM news ORDER BY id DESC LIMIT 5";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="news-item">';
    echo '<h2><a href="chitiettintuc.php?id=' . $row['id'] . '">' . $row['tieude'] . '</a></h2>';
    echo '<p>' . substr($row['noidung'], 0, 200) . '...</p>';
    echo '</div>';
}
?>
