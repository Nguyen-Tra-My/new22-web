<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // thêm dòng này
        header("Location: index.php");
        exit();
    } else {
        echo "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>

<h2>Đăng nhập</h2>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Mật khẩu: <input type="password" name="password" required><br><br>
    <button type="submit">Đăng nhập</button>
</form>
