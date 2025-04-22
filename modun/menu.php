<nav class="main-nav">
    <ul>
        <li><a href='index.php'>Trang chủ</a></li>
        <li><a href='chuyenmuc.php'>Chuyên mục</a></li>
        <?php if (isset($_SESSION['username'])): ?>
            <li><a href='themtintuc.php'>Thêm tin</a></li>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <li><a href='admin.php'>Quản trị</a></li>
            <?php endif; ?>
            <li><a href='dangxuat.php'>Đăng xuất</a></li>
        <?php else: ?>
            <li><a href='dangky.php'>Đăng ký</a></li>
            <li><a href='dangnhap.php'>Đăng nhập</a></li>
        <?php endif; ?>
    </ul>
</nav>
