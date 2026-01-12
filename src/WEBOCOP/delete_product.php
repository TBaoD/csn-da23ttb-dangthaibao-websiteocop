<?php
include "connection.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập trang này.");
}

$id = $_GET['id'] ?? null;
$message = "";

// Nếu chưa chọn sản phẩm → hiển thị combo box để chọn
if (!$id) {
    $products = $conn->query("SELECT id, name FROM products");
    ?>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <title>Xóa sản phẩm</title>
        <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
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
                            <a class="nav-link text-warning fw-bold" href="manage_product.php">Quản lý sản phẩm</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <div class="container my-5">
            <h2 class="mb-4">Chọn sản phẩm cần xóa</h2>
            <form method="GET" action="delete_product.php">
                <div class="mb-3">
                    <label class="form-label">Danh sách sản phẩm</label>
                    <select name="id" class="form-select" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Tiếp tục</button>
            </form>
        </div>
    </body>

    </html>
    <?php
    exit;
}

// Nếu đã chọn sản phẩm → lấy dữ liệu
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$product) {
    die("<div class='alert alert-danger text-center'>Không tìm thấy sản phẩm với ID $id</div>");
}

// Nếu xác nhận xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Sau khi xóa quay về trang chủ
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xóa sản phẩm</title>
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
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
    <div class="container my-5">
        <h2 class="mb-4">Xóa sản phẩm</h2>
        <div class="alert alert-warning">
            <p>Bạn có chắc muốn xóa sản phẩm sau?</p>
            <ul>
                <li><strong>Tên:</strong> <?php echo htmlspecialchars($product['name']); ?></li>
                <li><strong>Mô tả:</strong> <?php echo htmlspecialchars($product['description']); ?></li>
                <li><strong>Giá:</strong> <?php echo number_format($product['price']); ?> VND</li>
                <li><strong>Tồn kho:</strong> <?php echo $product['stock']; ?></li>
                <li><strong>Xuất xứ:</strong> <?php echo htmlspecialchars($product['origin']); ?></li>
                <li><strong>Cơ sở sản xuất:</strong> <?php echo htmlspecialchars($product['producer']); ?></li>
                <li><strong>Vĩ độ (lat):</strong> <?php echo $product['lat']; ?></li>
                <li><strong>Kinh độ (lng):</strong> <?php echo $product['lng']; ?></li>
                <?php if (!empty($product['image'])): ?>
                    <li><strong>Ảnh:</strong><br>
                        <img src="<?php echo $product['image']; ?>" alt="" style="max-height:150px;">
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
    <!--bắt đầu footer-->
    <footer class="bg-primary text-white text-center p-3">
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