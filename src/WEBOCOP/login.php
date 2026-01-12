<?php
include "connection.php";
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn user theo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin vào session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];

        // 🔑 Nếu username là admin thì gán quyền admin
        if ($user['username'] === 'admin') {
            $_SESSION['role'] = 'admin';
        } else {
            $_SESSION['role'] = 'user';
        }

        echo "<script>window.location='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Sai email hoặc mật khẩu'); window.location='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
</head>

<body>
    <!-- Header -->
    <div class="container-fluid p-5 my-5 bg-white text-dark text-center">
        <img src="images/logowebocop.png" alt="Logo website" width="100">
        <h1>Chào mừng đến với website giới thiệu sản phẩm OCOP!</h1>
        <h2>Sản phẩm xanh với chất lượng cao</h2>
    </div>

    <!-- Nav -->
    <nav class="navbar navbar-expand-sm bg-primary">
        <div class="justify-content-start">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand text-light" href="index.php">
                        <img src="images/logowebocop.png" alt="logo" style="height: 40px;">Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="cart.php">Giỏ hàng</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="order.php">Đặt hàng</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="order_history.php">Lịch sử đặt hàng</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Form login -->
    <div class="container p-5">
        <h2 class="text-center mb-4">Đăng nhập</h2>
        <form method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Mật khẩu" required>
            <button type="submit" name="login" class="btn btn-danger w-100">Đăng nhập</button>
        </form>
        <p class="mt-3 text-center">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>
    <!--bắt đầu footer-->
    <footer class="bg-primary text-white text-center p-3 fixed-bottom">
        <div class="container">

            <div class="row">

                <!-- Cột 1: Thông tin liên hệ -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-warning">📞 Thông tin liên hệ</h5>
                    <p>Hotline: <b>0917072927</b></p>
                    <p>Email: <b>webocop@gmail.com</b></p>
                </div>

                <!-- Cột 3: Mạng xã hội -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-warning">🌐 Kết nối với chúng tôi</h5>
                    <!--<a href="#" class="text-white me-3">Facebook</a>
                    <a href="#" class="text-white me-3">Instagram</a>-->
                </div>

            </div>

            <hr class="border-secondary">

            <!-- Bản quyền -->
            <div class="text-center">
                <p class="mb-0">
                    © 2025 WebOcopShop. All rights reserved.
                </p>
            </div>

    </footer>
    <!--end footer-->
</body>

</html>