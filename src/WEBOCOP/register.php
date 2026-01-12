<?php include "connection.php"; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
</head>

<body>
    <!-- Header -->
    <div class="container-fluid p-5 my-5 bg-white text-dark text-center">
        <img src="images/logowebocop.png" alt="Logo website" width="100">
        <h1>Chào mừng đến với website giới thiệu sản phẩm OCOP!</h1>
        <h2>Sản phẩm xanh với chất lượng cao</h2>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm bg-primary">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand text-light" href="index.php">
                        <img src="images/logowebocop.png" alt="logo" style="height: 40px;">Trang chủ
                    </a>
                </li>
                <li class="nav-item ms-auto">
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
    <div class="container p-5">
        <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
        <form method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Họ và tên" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Mật khẩu" required>
            <button type="submit" name="register" class="btn btn-success w-100">Đăng ký</button>
        </form>
        <p class="mt-3 text-center">Đã có tài khoản? <a href="dangnhap.php">Đăng nhập</a></p>
        <?php
        if (isset($_POST['register'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Đăng ký thành công'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Email đã tồn tại');</script>";
            }
        }
        ?>
    </div>
    <!--bắt đầu footer-->
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