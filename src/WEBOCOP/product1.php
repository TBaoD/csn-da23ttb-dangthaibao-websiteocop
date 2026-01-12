<?php
session_start();
$lat = isset($_GET['lat']) ? $_GET['lat'] : 9.9343400;   // mặc định Vĩnh Long
$lng = isset($_GET['lng']) ? $_GET['lng'] : 106.3098000;
?>,
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
                <img src="images/ruouNepThanLongHo.png" class="img-fluid" alt="Rượu nếp than Long Hồ">
                <h5 class="mt-3">Rượu nếp than Long Hồ (Cô 5)</h5>
                <p>Sản phẩm kết tinh từ phương pháp ủ rượu truyền thống và quy trình sản xuất kiểm soát chặt chẽ.</p>
                <p><strong>Xuất xứ:</strong> Việt Nam</p>
                <p><strong>Cơ sở sản xuất:</strong> 131/1 đường Nguyễn Đáng nối dài, khóm Sóc Thát, Phường Nguyệt Hóa,
                    tỉnh Vĩnh Long.</p>
                <p><strong>Giá:</strong> 110.000 VND/chai (500ml)</p>
                <a href="order1.php" class="btn btn-primary">Đặt hàng ngay</a>
            </div>
            <div class="col-md-6">
                <!-- Bản đồ -->
                <div id="map" style="height:400px; width:100%;"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-primary text-white text-center p-3">
        <p>Web Giới Thiệu Sản Phẩm OCOP &copy; 2025</p>
    </div>

    <!-- Script khởi tạo Leaflet -->
    <script>
        // Tọa độ cơ sở sản xuất (ví dụ Vĩnh Long)
        var lat = <?php echo $lat; ?>;
        var lng = <?php echo $lng; ?>;

        var map = L.map('map').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker(location).addTo(map)
            .bindPopup('Cơ sở sản xuất Rượu nếp than Long Hồ')
            .openPopup();
    </script>
</body>

</html>