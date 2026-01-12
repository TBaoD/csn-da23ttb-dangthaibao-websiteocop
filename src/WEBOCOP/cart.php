<?php
include "connection.php";
session_start();

$cart_id = $_SESSION['cart_id'] ?? null;
$success = false;

// Khi xác nhận đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order']) && $cart_id) {
    // Lấy username từ session
    $username = $_SESSION['username'];

    // Tìm user_id theo username
    $stmtUserId = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmtUserId->bind_param("s", $username);
    $stmtUserId->execute();
    $resultUser = $stmtUserId->get_result();
    $user = $resultUser->fetch_assoc();
    $user_id = $user['id'];

    // Lấy dữ liệu từ form
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    // Cập nhật phone và address cho user
    $stmtUpdate = $conn->prepare("UPDATE users SET phone=?, address=? WHERE id=?");
    $stmtUpdate->bind_param("ssi", $phone, $address, $user_id);
    $stmtUpdate->execute();

    // Tạo đơn hàng
    $stmtOrder = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
    $stmtOrder->bind_param("i", $user_id);
    $stmtOrder->execute();
    $order_id = $stmtOrder->insert_id;

    // Lấy sản phẩm trong giỏ
    $sql = "SELECT p.id, p.price, ci.quantity 
            FROM cart_items ci 
            JOIN products p ON ci.product_id = p.id 
            WHERE ci.cart_id = ?";
    $stmtCart = $conn->prepare($sql);
    $stmtCart->bind_param("i", $cart_id);
    $stmtCart->execute();
    $result = $stmtCart->get_result();

    // Ghi chi tiết sản phẩm
    while ($row = $result->fetch_assoc()) {
        $stmtDetail = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmtDetail->bind_param("iiid", $order_id, $row['id'], $row['quantity'], $row['price']);
        $stmtDetail->execute();
    }

    // Xóa giỏ hàng
    $conn->query("DELETE FROM cart_items WHERE cart_id = $cart_id");
    unset($_SESSION['cart_id']);

    $success = true;
}

// Khi bấm nút checkout → hiển thị form nhập thông tin
if (isset($_POST['checkout']) && $cart_id) {
    ?>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <title>Đặt hàng</title>
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
                    <li class="nav-item ms-auto">
                        <a class="nav-link text-light" href="order.php">Đặt hàng</a>
                    </li>
                    <li class="nav-item ms-auto">
                        <a class="nav-link text-light" href="order_history.php">Lịch sử đặt hàng</a>
                    </li>
                    <!-- Chỉ hiện khi là admin -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item ms-auto">
                            <a class="nav-link text-warning fw-bold" href="manage_product.php">Quản lý</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <!--end navs(thanh menu)-->
        <div class="container my-4">
            <h3>Thông tin đặt hàng</h3>
            <?php if ($success): ?>
                <div class="alert alert-success">Đặt hàng thành công!</div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên khách hàng:</label>
                    <input type="text" name="fullname" class="form-control"
                        value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại:</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ:</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
                <button type="submit" name="order" class="btn btn-success">Xác nhận đặt hàng</button>
            </form>

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
    </body>

    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
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
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="order.php">Đặt hàng</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="order_history.php">Lịch sử đặt hàng</a>
                </li>
                <!-- Chỉ hiện khi là admin -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item ms-auto">
                        <a class="nav-link text-warning fw-bold" href="manage_product.php">Quản lý</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <!--end navs(thanh menu)-->
    <div class="container my-4">
        <?php
        if (!$cart_id) {
            echo "<div class='text-center'>
                    <h3>Giỏ hàng của bạn đang trống</h3>
                    <a href='index.php' class='btn btn-primary mt-3'>Về trang chủ</a>
                  </div>";
        } else {
            $sql = "SELECT p.name, p.price, ci.quantity 
                    FROM cart_items ci 
                    JOIN products p ON ci.product_id = p.id 
                    WHERE ci.cart_id = $cart_id";
            $result = $conn->query($sql);
            echo "<h3>Giỏ hàng của bạn</h3>";
            echo "<form method='POST'>";
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
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                $subtotal = $row['price'] * $row['quantity'];
                $total += $subtotal;
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>" . number_format($row['price']) . " VND</td>
                        <td>" . number_format($subtotal) . " VND</td>
                      </tr>";
            }
            echo "<tr>
                    <td colspan='4' class='text-end'><strong>Tổng cộng: " . number_format($total) . " VND</strong></td>
                  </tr>";
            echo "</tbody></table>";
            echo "<button type='submit' name='checkout' class='btn btn-primary'>Đặt hàng</button>";
            echo "</form>";
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
</body>

</html>