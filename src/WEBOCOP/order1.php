<?php
include "connection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Đặt hàng - OCOP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    <script src="bootstrap cdn/KT2/js/bootstrap.bundle.js"></script>
</head>

<body>
    <!-- Header -->
    <div class="container-fluid p-4 bg-white text-dark text-center">
        <img src="images/logowebocop.png" alt="Logo website" width="100">
        <h1>Website giới thiệu sản phẩm OCOP</h1>
        <h4>Sản phẩm xanh - Chất lượng cao</h4>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-sm bg-primary">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand text-light" href="index.php">
                        <img src="images/logowebocop.png" alt="logo" style="height: 40px;"> Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="cart.php">Giỏ hàng</a>
                </li>
                <?php if (isset($_SESSION['username'])) { ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">Xin chào, <?php echo $_SESSION['username']; ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Đăng xuất</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item"><a class="nav-link text-light" href="login.php">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link text-light" href="register.php">Đăng ký</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <!-- Nội dung -->
    <div class="container my-4">
        <h3 class="mb-4">Thông tin đặt hàng</h3>

        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer_name = $_POST['TenKh'];
            $product_id = $_POST['Tensp'];
            $quantity = $_POST['SLDH'];

            // 1. Kiểm tra khách hàng đã tồn tại chưa
            $sql_check = "SELECT id FROM users WHERE username = '$customer_name' LIMIT 1";
            $result = $conn->query($sql_check);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $customer_id = $row['id'];
                // 2. Tạo đơn hàng trong bảng orders
                $conn->query("INSERT INTO orders (user_id, status) VALUES ('$customer_id', 'pending')");
                $order_id = $conn->insert_id;

                // 3. Thêm chi tiết đơn hàng vào order_details
                $sql_product = "SELECT price FROM products WHERE id = '$product_id' LIMIT 1";
                $result_product = $conn->query($sql_product);
                $price = 0;
                if ($result_product->num_rows > 0) {
                    $row_p = $result_product->fetch_assoc();
                    $price = $row_p['price'];
                }

                $conn->query("INSERT INTO order_details (order_id, product_id, quantity, price) 
                      VALUES ('$order_id', '$product_id', '$quantity', '$price')");

                // 4. Cập nhật tổng tiền đơn hàng
                $total = $quantity * $price;
                $conn->query("UPDATE orders SET total = '$total' WHERE id = '$order_id'");

                echo "<div class='alert alert-success'>Đặt hàng thành công! 
              ID khách hàng: $customer_id, Mã đơn hàng: $order_id</div>";
            }
        }
        ?>

        <form method="POST" action="order1.php">
            <div class="mb-3">
                <label class="form-label">Tên khách hàng</label>
                <input type="text" class="form-control" name="TenKh" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="SDT" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Địa chỉ (Tỉnh/Thành)</label>
                <select name="slQueQuan" class="form-select" required>
                    <option value="">-- Chọn tỉnh/thành --</option>
                    <option value="AG">An Giang</option>
                    <option value="BRVT">Bà Rịa - Vũng Tàu</option>
                    <option value="BG">Bắc Giang</option>
                    <option value="BK">Bắc Kạn</option>
                    <option value="BL">Bạc Liêu</option>
                    <option value="BL">Bạc Liêu</option>
                    <option value="BN">Bắc Ninh</option>
                    <option value="BT">Bến Tre</option>
                    <option value="BD">Bình Định</option>
                    <option value="BDU">Bình Dương</option>
                    <option value="BP">Bình Phước</option>
                    <option value="BTN">Bình Thuận</option>
                    <option value="CM">Cà Mau</option>
                    <option value="CT">Cần Thơ</option>
                    <option value="CB">Cao Bằng</option>
                    <option value="DN">Đà Nẵng</option>
                    <option value="DL">Đắk Lắk</option>
                    <option value="DNO">Đắk Nông</option>
                    <option value="DB">Điện Biên</option>
                    <option value="DN">Đồng Nai</option>
                    <option value="DT">Đồng Tháp</option>
                    <option value="GL">Gia Lai</option>
                    <option value="HG">Hà Giang</option>
                    <option value="HN">Hà Nội</option>
                    <option value="HT">Hà Tĩnh</option>
                    <option value="HD">Hải Dương</option>
                    <option value="HP">Hải Phòng</option>
                    <option value="HG">Hậu Giang</option>
                    <option value="HB">Hòa Bình</option>
                    <option value="HY">Hưng Yên</option>
                    <option value="KH">Khánh Hòa</option>
                    <option value="KG">Kiên Giang</option>
                    <option value="KT">Kon Tum</option>
                    <option value="LC">Lai Châu</option>
                    <option value="LS">Lạng Sơn</option>
                    <option value="LC">Lào Cai</option>
                    <option value="LD">Lâm Đồng</option>
                    <option value="LA">Long An</option>
                    <option value="ND">Nam Định</option>
                    <option value="NA">Nghệ An</option>
                    <option value="NB">Ninh Bình</option>
                    <option value="NT">Ninh Thuận</option>
                    <option value="PT">Phú Thọ</option>
                    <option value="PY">Phú Yên</option>
                    <option value="QB">Quảng Bình</option>
                    <option value="QN">Quảng Nam</option>
                    <option value="QNG">Quảng Ngãi</option>
                    <option value="QNI">Quảng Ninh</option>
                    <option value="QT">Quảng Trị</option>
                    <option value="ST">Sóc Trăng</option>
                    <option value="SL">Sơn La</option>
                    <option value="TN">Tây Ninh</option>
                    <option value="TB">Thái Bình</option>
                    <option value="TN">Thái Nguyên</option>
                    <option value="TH">Thanh Hóa</option>
                    <option value="TTH">Thừa Thiên Huế</option>
                    <option value="TG">Tiền Giang</option>
                    <option value="TV">Trà Vinh</option>
                    <option value="TQ">Tuyên Quang</option>
                    <option value="VL">Vĩnh Long</option>
                    <option value="VP">Vĩnh Phúc</option>
                    <option value="YB">Yên Bái</option>
                    <!-- thêm các tỉnh khác -->
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Sản phẩm</label>
                <select name="Tensp" class="form-select" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    <option value="1">Rượu nếp than Long Hồ (Cô 5)</option>
                    <option value="2">Cam Sành Tam Bình</option>
                    <option value="3">Khoai Lang Tím Sấy</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" class="form-control" name="SLDH" value="1" min="1" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" required>
                <label class="form-check-label">Tôi đồng ý với điều khoản của shop</label>
            </div>
            <button type="submit" class="btn btn-primary">Đặt hàng</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="bg-primary text-white text-center p-3">
        <p>Web Giới Thiệu Sản Phẩm OCOP &copy; 2025</p>
    </div>
</body>

</html>