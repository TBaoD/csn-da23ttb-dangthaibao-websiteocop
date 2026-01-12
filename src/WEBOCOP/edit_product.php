<?php
include "connection.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn không có quyền truy cập trang này.");
}

$id = $_GET['id'] ?? null;
$message = "";

// Nếu chưa chọn sản phẩm → hiển thị combo box
if (!$id) {
    $products = $conn->query("SELECT id, name FROM products");
    ?>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <title>Chọn sản phẩm để sửa</title>
        <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container my-5">
            <h2 class="mb-4">Chọn sản phẩm cần sửa</h2>
            <form method="GET" action="edit_product.php">
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
                <button type="submit" class="btn btn-primary">Sửa</button>
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

// Nếu submit form → cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = str_replace('.', '', $_POST['price']); // chuyển "110.000" thành "110000"
    $stock = $_POST['stock'];
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : NULL;
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $origin = $_POST['origin'];
    $producer = $_POST['producer'];
    $rating = $_POST['rating'];
    // Nếu có upload ảnh mới
    $image_path = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir))
            mkdir($target_dir, 0777, true);
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=?, lat=?, lng=?, origin=?, producer=?, rating=? WHERE id=?");
    $stmt->bind_param("ssdiisddssdi", $name, $description, $price, $stock, $category_id, $image_path, $lat, $lng, $origin, $producer, $rating, $id);
    $stmt->execute();
    $message = "<div class='alert alert-success text-center'>Cập nhật sản phẩm thành công!</div>";

    // Cập nhật lại dữ liệu để hiển thị
    $product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
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
        <h2 class="mb-4">Sửa sản phẩm</h2>
        <?php if (!empty($message))
            echo $message; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3"><label>Tên sản phẩm</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"
                    class="form-control">
            </div>
            <div class="mb-3"><label>Số sao (1–5)</label>
                <input type="number" name="rating" value="<?php echo $product['rating']; ?>" class="form-control"
                    min="0" max="5" step="0.5">
            </div>
            <div class="mb-3"><label>Mô tả</label>
                <textarea name="description"
                    class="form-control"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-3"><label>Giá (VND)</label>
                <input type="text" name="price" value="<?php echo number_format($product['price'], 0, ',', '.'); ?>"
                    class="form-control">
            </div>
            <div class="mb-3"><label>Số lượng tồn kho</label>
                <input type="number" name="stock" value="<?php echo $product['stock']; ?>" class="form-control">
            </div>
            <div class="mb-3"><label>Danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    <?php
                    $cats = $conn->query("SELECT id,name FROM categories");
                    while ($cat = $cats->fetch_assoc()):
                        ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $product['category_id'])
                               echo "selected"; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3"><label>Ảnh sản phẩm</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo $product['image']; ?>" alt="" style="max-height:100px;margin-top:10px;">
                <?php endif; ?>
            </div>
            <div class="mb-3"><label>Vĩ độ (lat)</label>
                <input type="text" name="lat" value="<?php echo $product['lat']; ?>" class="form-control">
            </div>
            <div class="mb-3"><label>Kinh độ (lng)</label>
                <input type="text" name="lng" value="<?php echo $product['lng']; ?>" class="form-control">
            </div>
            <div class="mb-3"><label>Xuất xứ</label>
                <input type="text" name="origin" value="<?php echo htmlspecialchars($product['origin']); ?>"
                    class="form-control">
            </div>
            <div class="mb-3"><label>Cơ sở sản xuất</label>
                <input type="text" name="producer" value="<?php echo htmlspecialchars($product['producer']); ?>"
                    class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
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