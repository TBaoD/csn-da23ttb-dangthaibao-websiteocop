<?php
include "connection.php";
session_start();
$user_id = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Giỏ hàng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    <script src="bootstrap cdn/KT2/js/bootstrap.bundle.js"></script>
</head>

<body>
    <!--bắt đầu header-->
    <div class="container-fluid p-5 my-5 bg-white text-dark text-center">
        <img src="images/logowebocop.png" alt="Logo website" width="100">
        <h1>Chào mừng đến với website giới thiệu sản phẩm OCOP!</h1>
        <h2>Sản phẩm xanh với chất lượng cao</h2>
    </div>
    <!--end header-->
    <!--bắt đầu navs(thanh menu)-->
    <nav class="navbar navbar-expand-sm bg-primary">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand text-light " href="index.php">
                        <img src="images/logowebocop.png" alt="logo" style="height: 40px;">Trang chủ</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="cart.php">Giỏ hàng</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <form class="d-flex" role="search" method="GET" action="search.php">
                    <input class="form-control me-2" type="search" name="q" placeholder="Tìm sản phẩm..."
                        aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Tìm</button>
                </form>
                <!-- Form lọc (ẩn/hiện bằng collapse) -->
                <li class="nav-item">
                    <a class="nav-link text-light" href="#" data-bs-toggle="collapse" data-bs-target="#filterForm">
                        Lọc sản phẩm
                    </a>
                </li>
                <div class="collapse bg-light p-4" id="filterForm">
                    <div class="container">
                        <h4 class="mb-3 text-primary">Lọc sản phẩm</h4>
                        <form method="GET" action="products.php" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Giá tối đa</label>
                                <input type="number" name="max_price" class="form-control" placeholder="VD: 200000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Xuất xứ</label>
                                <select name="origin" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    <option value="Việt Nam">Việt Nam</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Loại sản phẩm</label>
                                <select name="category" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    <option value="Thực phẩm">Thực phẩm</option>
                                    <option value="Thức uống">Thức uống</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['username'])) { ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">Xin chào, <?php echo $_SESSION['username']; ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Đăng xuất</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="login.php">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="register.php">Đăng ký</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
    <!--end nav-->
    <div class="container my-4">
        <h3>Đơn hàng của bạn</h3>

        <?php
        if ($user_id) {
            $sql = "SELECT o.id AS order_id, o.order_date, o.status,
                       p.name, od.quantity, od.price
                FROM orders o
                JOIN order_details od ON o.id = od.order_id
                JOIN products p ON od.product_id = p.id
                WHERE o.user_id = '$user_id'
                ORDER BY o.order_date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $current_order = null;
                $order_total = 0;

                while ($row = $result->fetch_assoc()) {
                    // Nếu sang đơn hàng mới thì in header
                    if ($current_order !== $row['order_id']) {
                        // Nếu có đơn trước đó thì in tổng cộng
                        if ($current_order !== null) {
                            echo "<tr><td colspan='4' class='text-end'><b>Tổng cộng: "
                                . number_format($order_total) . " VND</b></td></tr>";
                            echo "</tbody></table><br>";
                        }

                        // Reset cho đơn mới
                        $current_order = $row['order_id'];
                        $order_total = 0;

                        echo "<h5>Đơn hàng #{$row['order_id']} ({$row['order_date']}) - Trạng thái: {$row['status']}</h5>";
                        echo "<table class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>";
                    }

                    $subtotal = $row['price'] * $row['quantity'];
                    $order_total += $subtotal;

                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>" . number_format($row['price']) . " VND</td>
                        <td>" . number_format($subtotal) . " VND</td>
                      </tr>";
                }

                // In tổng cộng cho đơn cuối cùng
                echo "<tr><td colspan='4' class='text-end'><b>Tổng cộng: "
                    . number_format($order_total) . " VND</b></td></tr>";
                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-info'>Bạn chưa có đơn hàng nào.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Bạn cần đăng nhập để xem đơn hàng.</div>";
        }
        ?>
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