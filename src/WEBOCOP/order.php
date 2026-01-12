<?php
include "connection.php";
session_start();

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenkh = $_POST['TenKh'];
    $sdt = $_POST['SDT'];
    $diachi = $_POST['slQueQuan'];
    $product = $_POST['product_id'];
    $soluong = $_POST['SLDH'];

    // Lưu vào bảng orders (đảm bảo bảng orders có các cột này)
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, address, product_id, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $tenkh, $sdt, $diachi, $product, $soluong);
    $stmt->execute();

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Web Giới Thiệu Sản Phẩm OCOP - Đặt hàng</title>
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
        <div class="justify-content-start">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand text-light " href="index.php">
                        <img src="images/logowebocop.png" alt="logo" style="height: 40px;">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="cart.php">Giỏ hàng</a>
                </li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">Xin chào,
                            <?php echo $_SESSION['username']; ?>!
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Đăng xuất</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Đăng ký</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Alert thành công -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Đặt hàng thành công!</strong> Chúng tôi sẽ liên hệ cho bạn sớm nhất có thể.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form đặt hàng -->
    <div class="container my-4">
        <h3>Thông tin đặt hàng</h3>
        <form method="POST" action="">
            <div class="mb-3 mt-3">
                <label for="TenKh" class="form-label">Tên khách hàng:</label>
                <input type="text" class="form-control" id="TenKh" name="TenKh" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="SDT" class="form-label">Số điện thoại:</label>
                <input type="text" class="form-control" id="SDT" name="SDT" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="slQueQuan" class="form-label">Địa chỉ đặt hàng (chọn tỉnh/thành):</label>
                <select name="slQueQuan" class="form-control" id="slQueQuan" required>
                    <option value="">Chọn tỉnh/thành</option>
                    <option value="AG">An Giang</option>
                    <option value="BRVT">Bà Rịa - Vũng Tàu</option>
                    <option value="BG">Bắc Giang</option>
                    <option value="BK">Bắc Kạn</option>
                    <option value="BL">Bạc Liêu</option>
                    <option value="BN">Bắc Ninh</option>
                    <option value="BT">Bến Tre</option>
                    <option value="BD">Bình Định</option>
                    <option value="BDU">Bình Dương</option>
                    <option value="BP">Bình Phước</option>
                    <option value="BTN">Bình Thuận</option>
                    <option value="CM">Cà Mau</option>
                    <option value="CT">Cần Thơ</option>
                    <option value="CB">Cao Bằng</option>
                    <option value="DN">Đà Nẵng</option>
                    <option value="DL">Đắk Lắk</option>
                    <option value="DNO">Đắk Nông</option>
                    <option value="DB">Điện Biên</option>
                    <option value="DT">Đồng Tháp</option>
                    <option value="GL">Gia Lai</option>
                    <option value="HG">Hà Giang</option>
                    <option value="HN">Hà Nội</option>
                    <option value="HT">Hà Tĩnh</option>
                    <option value="HD">Hải Dương</option>
                    <option value="HP">Hải Phòng</option>
                    <option value="HG">Hậu Giang</option>
                    <option value="HB">Hòa Bình</option>
                    <option value="HY">Hưng Yên</option>
                    <option value="KH">Khánh Hòa</option>
                    <option value="KG">Kiên Giang</option>
                    <option value="KT">Kon Tum</option>
                    <option value="LC">Lai Châu</option>
                    <option value="LS">Lạng Sơn</option>
                    <option value="LD">Lâm Đồng</option>
                    <option value="LA">Long An</option>
                    <option value="ND">Nam Định</option>
                    <option value="NA">Nghệ An</option>
                    <option value="NB">Ninh Bình</option>
                    <option value="NT">Ninh Thuận</option>
                    <option value="PT">Phú Thọ</option>
                    <option value="PY">Phú Yên</option>
                    <option value="QB">Quảng Bình</option>
                    <option value="QN">Quảng Nam</option>
                    <option value="QNG">Quảng Ngãi</option>
                    <option value="QNI">Quảng Ninh</option>
                    <option value="QT">Quảng Trị</option>
                    <option value="ST">Sóc Trăng</option>
                    <option value="SL">Sơn La</option>
                    <option value="TN">Tây Ninh</option>
                    <option value="TB">Thái Bình</option>
                    <option value="TN">Thái Nguyên</option>
                    <option value="TH">Thanh Hóa</option>
                    <option value="TTH">Thừa Thiên Huế</option>
                    <option value="TG">Tiền Giang</option>
                    <option value="TV">Trà Vinh</option>
                    <option value="TQ">Tuyên Quang</option>
                    <option value="VL">Vĩnh Long</option>
                    <option value="VP">Vĩnh Phúc</option>
                    <option value="YB">Yên Bái</option>
                    <!-- thêm các tỉnh/thành khác -->
                </select>
            </div>
            <div class="mb-3 mt-3">
                <label for="product_id" class="form-label">Chọn sản phẩm đặt mua:</label>
                <select name="product_id" class="form-control" id="product_id" required>
                    <option value="">Tên sản phẩm đặt hàng</option>
                    <?php
                    // Lấy danh sách sản phẩm từ bảng products
                    $products = $conn->query("SELECT id, name FROM products ORDER BY id ASC");
                    while ($row = $products->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3 mt-3">
                <label for="SLDH" class="form-label">Số lượng sản phẩm đặt mua:</label>
                <input type="number" class="form-control" id="SLDH" name="SLDH" value="1" min="1" required>
            </div>
            <div class="form-check mb-3 mt-3">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="remember"> Nhớ cho những lần đăng nhập sau.
                </label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="myCheck" name="agree" required>
                <label class="form-check-label" for="myCheck">Tôi đồng ý với những điều khoản của shop.</label>
            </div>
            <button type="submit" class="btn btn-primary">Đặt hàng</button>
        </form>
    </div>
</body>

</html>