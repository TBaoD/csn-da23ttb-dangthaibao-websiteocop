<?php
include "connection.php"; // file kết nối MySQL
session_start();
// Lấy id sản phẩm từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn DB
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Nếu không tìm thấy sản phẩm
if (!$product) {
    die("Sản phẩm không tồn tại!");
}

// Lấy tọa độ, mặc định Vĩnh Long nếu chưa có
$lat = !empty($product['lat']) ? $product['lat'] : 9.9343400;
$lng = !empty($product['lng']) ? $product['lng'] : 106.3098000;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Chi tiết sản phẩm OCOP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    <script src="bootstrap cdn/KT2/js/bootstrap.bundle.js"></script>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
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
                        <img src="images/logowebocop.png" alt="logo" style="height: 40px;"> Trang chủ
                    </a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="order.php">Giỏ hàng</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="order_history.php">Lịch sử đặt hàng</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <form class="d-flex" role="search" method="GET" action="search.php">
                    <input class="form-control me-2" type="search" name="q" placeholder="Tìm sản phẩm..."
                        aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Tìm</button>
                </form>
                <?php if (isset($_SESSION['username'])) { ?>
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

    <!-- Content -->
    <div class="container my-4">
        <h2 class="text-center mb-4">Chi tiết sản phẩm</h2>
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo $product['name']; ?>">
                <h5 class="mt-3"><?php echo $product['name']; ?></h5>
                <p><?php echo $product['description']; ?></p>
                <p><strong>Đánh giá:</strong>
                    <?php $rating = round($product['rating']);
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $rating ? "★" : "☆";
                    } ?>
                </p>
                <p><strong>Xuất xứ:</strong> <?php echo $product['origin']; ?></p>
                <p><strong>Cơ sở sản xuất:</strong> <?php echo $product['producer']; ?></p>
                <p><strong>Giá:</strong> <?php echo number_format($product['price'], 0, ',', '.'); ?> VND</p>
                <!-- Form chọn số lượng và thêm vào giỏ -->
                <form method="GET" action="add_to_cart.php" class="d-flex mb-2">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" class="form-control me-2"
                        style="width:100px;">
                    <button type="submit" class="btn btn-secondary">Thêm vào giỏ</button>
                </form>
            </div>
            <div class="col-md-6">
                <!-- Bản đồ -->
                <div id="map" style="height:400px; width:100%;"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
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

    <!-- Script khởi tạo Leaflet -->
    <script>
        var lat = <?php echo $lat; ?>;
        var lng = <?php echo $lng; ?>;

        var map = L.map('map').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup(<?php echo json_encode($product['producer']); ?>)
            .openPopup();

    </script>
</body>

</html>