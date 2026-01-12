<!--bắt đầu get started-->
<?php
ini_set('session.cookie_lifetime', 0);
include "connection.php";
session_start();
?>
<!--bắt đầu get started-->
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Website Giới Thiệu Sản Phẩm OCOP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    <script src="bootstrap cdn/KT2/js/bootstrap.bundle.js"></script>
</head>
<!--kết thúc get started-->

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
                        <!-- sửa action thành index.php -->
                        <form method="GET" action="index.php" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Giá tối thiểu</label>
                                <input type="number" name="min_price" class="form-control" placeholder="VD: 50000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Giá tối đa</label>
                                <input type="number" name="max_price" class="form-control" placeholder="VD: 200000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Loại sản phẩm</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    <option value=1>Thực phẩm</option>
                                    <option value=2>Thức uống</option>
                                </select>
                            </div>
                            <!-- rating để ghi chú, chưa dùng -->
                            <div class="col-md-3">
                                <label class="form-label">Số sao (ghi chú)</label>
                                <select name="rating" class="form-select">
                                    <option value="">-- Tất cả --</option>
                                    <option value="5">5 sao</option>
                                    <option value="4">4 sao trở lên</option>
                                    <option value="3">3 sao trở lên</option>
                                </select>
                            </div>
                            <div class="col-md-12 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- Xử lý PHP lọc sản phẩm -->
                <?php
                $where = "WHERE 1=1";

                // lọc theo giá
                if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) {
                    $min = (int) $_GET['min_price'];
                    $max = (int) $_GET['max_price'];
                    $where .= " AND price BETWEEN $min AND $max";
                } elseif (!empty($_GET['max_price'])) {
                    $max = (int) $_GET['max_price'];
                    $where .= " AND price <= $max";
                } elseif (!empty($_GET['min_price'])) {
                    $min = (int) $_GET['min_price'];
                    $where .= " AND price >= $min";
                }

                // lọc theo loại
                if (!empty($_GET['category_id'])) {
                    $category_id = (int) $_GET['category_id'];
                    $where .= " AND category_id = $category_id";
                }

                // lọc theo số sao 
                if (!empty($_GET['rating'])) {
                    $rating = (int) $_GET['rating'];
                    $where .= " AND rating >= $rating";
                }

                $sql = "SELECT * FROM products $where ORDER BY id DESC";
                $result = $conn->query($sql);
                ?>
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
                        <a class="nav-link" href="login.php">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Đăng ký</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
    <!--end navs-->
    <!--bắt đầu content-->
    <div class="container my-4">
        <?php
        // Kiểm tra có lọc không
        $hasFilter = !empty($_GET['min_price']) || !empty($_GET['max_price']) || !empty($_GET['category_id']) || !empty($_GET['rating']);

        if ($hasFilter) {
            echo "<h4 class='mb-3 text-primary'>Sản phẩm đã lọc:</h4>";

            if ($result && $result->num_rows > 0) {
                echo "<div class='row'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-md-3 mb-4'>
                        <div class='card'>
                            <img src='{$row['image']}' class='card-img-top'>
                            <div class='card-body'>
                                <h5>{$row['name']}</h5>
                                <p>Đánh giá: {$row['rating']} ⭐</p>
                                <p>" . number_format($row['price']) . " VND</p>
                            </div>
                        </div>
                      </div>";
                }
                echo "</div>";
            } else {
                echo "<div class='alert alert-info'>Không tìm thấy sản phẩm phù hợp.</div>";
            }
        }
        ?>
    </div>
    <div class="container my-2">
        <h2 class="text-center mb-4">Những sản phẩm OCOP nổi bậc</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="card">
                    <img src="<?php echo $row['image']; ?>" class="card-img-top product-img"
                        alt="<?php echo $row['name']; ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <div class="product-rating">
                            <p class="card-text">Rating
                                <?php $rating = round($row['rating']); // làm tròn số sao 
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? "★" : "☆";
                                    } ?>
                            </p>
                        </div>
                        <p class="card-text">Xuất xứ: <?php echo $row['origin']; ?></p>
                        <p class="text-danger fw-bold"> Giá: <?php echo number_format($row['price'], 0, ',', '.'); ?>
                            VND </p>
                        <div ms-auto>
                            <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Xem thông tin
                                ngay</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <style>
            .product-img {
                height: 200px;
                /* chiều cao cố định cho tất cả ảnh */
                object-fit: cover;
                /* cắt ảnh cho vừa khung */
                width: 100%;
                /* chiếm toàn bộ chiều rộng card */
            }
        </style>
    </div>

    <!--end content-->
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