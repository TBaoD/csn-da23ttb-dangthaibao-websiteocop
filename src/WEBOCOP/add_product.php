<?php
include "connection.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập trang này.");
}

// Lấy danh sách danh mục
$categories = $conn->query("SELECT id, name FROM categories");

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : NULL;
    $lat = !empty($_POST['lat']) ? $_POST['lat'] : NULL;
    $lng = !empty($_POST['lng']) ? $_POST['lng'] : NULL;
    $origin = !empty($_POST['origin']) ? $_POST['origin'] : NULL;
    $producer = !empty($_POST['producer']) ? $_POST['producer'] : NULL;
    $rating = !empty($_POST['rating']) ? (float) $_POST['rating'] : 0;
    // Xử lý upload ảnh
    $target_dir = "uploads/"; // thư mục lưu ảnh
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // tạo thư mục nếu chưa có
    }

    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category_id, image, lat, lng, origin, producer, rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiissddsd", $name, $description, $price, $stock, $category_id, $target_file, $lat, $lng, $origin, $producer, $rating);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success text-center'>Sản phẩm <strong>" . htmlspecialchars($name) . "</strong> đã được thêm thành công!</div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>Lỗi SQL: " . $stmt->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
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
                    <a class="nav-link text-light" href="cart.php">Giỏ hàng</a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link text-light" href="order_history.php">Lịch sử đặt hàng</a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item ms-auto">
                        <a class="nav-link text-warning fw-bold" href="manage_product.php">Quản lý</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <div class="container my-5">
        <h2 class="mb-4">Thêm sản phẩm mới</h2>
        <?php if (!empty($message))
            echo $message; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3"> <label class="form-label">Số sao (1–5)</label> <input type="number" name="rating"
                    class="form-control" min="0" max="5" step="0.5" value="0"> </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá (VND)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Số lượng tồn kho</label>
                <input type="number" name="stock" class="form-control" value="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ảnh sản phẩm</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Vĩ độ (lat)</label>
                <input type="text" name="lat" class="form-control" placeholder="Ví dụ: 9.934340">
            </div>
            <div class="mb-3">
                <label class="form-label">Kinh độ (lng)</label>
                <input type="text" name="lng" class="form-control" placeholder="Ví dụ: 106.309800">
            </div>
            <div class="mb-3">
                <label class="form-label">Xuất xứ</label>
                <input type="text" name="origin" class="form-control" placeholder="Ví dụ: Việt Nam">
            </div>
            <div class="mb-3">
                <label class="form-label">Cơ sở sản xuất</label>
                <input type="text" name="producer" class="form-control"
                    placeholder="Ví dụ: 131/1 Nguyễn Đáng, Vĩnh Long">
            </div>
            <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
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