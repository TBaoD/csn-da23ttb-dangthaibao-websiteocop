<?php
include "connection.php";
session_start();

$keyword = isset($_GET['q']) ? $_GET['q'] : "";

$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
$searchTerm = "%" . $keyword . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Kết quả tìm kiếm</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    <script src="bootstrap cdn/KT2/js/bootstrap.bundle.js"></script>
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
        <h2 class="text-center mb-4">Kết quả tìm kiếm cho: <?php echo htmlspecialchars($keyword); ?></h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="images/<?php echo $row['image']; ?>" class="card-img-top"
                                alt="<?php echo $row['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text"><?php echo $row['description']; ?></p>
                                <p class="text-danger fw-bold"><?php echo number_format($row['price'], 0, ',', '.'); ?> VND</p>
                                <a href="order.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Xem thông tin ngay</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-center">Không tìm thấy sản phẩm nào.</p>
            <?php } ?>
        </div>
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