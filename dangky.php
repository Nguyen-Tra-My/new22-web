<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash đơn giản
    $email = $_POST['email'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        echo "Tên đăng nhập đã tồn tại.";
    } else {
        $sql = "INSERT INTO users(username, password, email) VALUES('$username', '$password', '$email')";
        if (mysqli_query($conn, $sql)) {
            echo "Đăng ký thành công. <a href='dangnhap.php'>Đăng nhập</a>";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    }
}
?>

<h2>Đăng ký</h2>
<form method="post">
    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Mật khẩu: <input type="password" name="password" required><br><br>
    <button type="submit">Đăng ký</button>
</form>
