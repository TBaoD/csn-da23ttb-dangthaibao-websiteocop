<?php
include "connection.php";
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập trang này.");
}

// Lấy danh sách sản phẩm
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản trị sản phẩm</title>
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    <script src="bootstrap cdn/KT2/js/bootstrap.bundle.js"></script>
</head>

<body>
    <!-- Header -->
    <div class="container-fluid p-5 my-5 bg-white text-dark text-center">
        <img src="images/logowebocop.png" alt="Logo website" width="100">
        <h1>Trang quản trị sản phẩm OCOP</h1>
        <h2>Quản lý sản phẩm</h2>
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
                    <a class="nav-link text-light" href="add_product.php"> Thêm sản phẩm</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="edit_product.php"> Sửa sản phẩm</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="delete_product.php"> Xóa sản phẩm</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
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

    <!-- Content -->
    <div class="container my-5">
        <h2 class="mb-4">Danh sách sản phẩm</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Rating</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Danh mục</th>
                    <th>Xuất xứ</th>
                    <th>Cơ sở sản xuất</th>
                    <th>Tọa độ</th>
                    <th>Ảnh</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <?php $rating = round($row['rating']);
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? "★" : "☆";
                            } ?>
                        </td>
                        <td><?php echo number_format($row['price']); ?> VND</td>
                        <td><?php echo $row['stock']; ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['origin']); ?></td>
                        <td><?php echo htmlspecialchars($row['producer']); ?></td>
                        <td><?php echo $row['lat'] . ", " . $row['lng']; ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="<?php echo $row['image']; ?>" alt="" style="max-height:60px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>"
                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
                                class="btn btn-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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