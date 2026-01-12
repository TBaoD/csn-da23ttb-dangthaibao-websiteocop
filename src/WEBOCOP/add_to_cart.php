<?php
session_start();
include "connection.php";

// Giả sử user đã đăng nhập, nếu chưa thì tạm gán user_id = 1
$user_id = $_SESSION['user_id'] ?? 1;

// Lấy cart_id từ session
$cart_id = $_SESSION['cart_id'] ?? null;

// Nếu chưa có giỏ hàng thì tạo mới
if (!$cart_id) {
    $stmt = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_id = $conn->insert_id;
    $_SESSION['cart_id'] = $cart_id;
}

// Lấy id sản phẩm và số lượng từ GET
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$quantity = isset($_GET['quantity']) ? (int) $_GET['quantity'] : 1;

// Kiểm tra tham số hợp lệ
if ($product_id <= 0 || $quantity <= 0) {
    die("Thiếu id hoặc số lượng sản phẩm.");
}

// Kiểm tra sản phẩm có tồn tại trong bảng products
$stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Sản phẩm không tồn tại trong bảng products.");
}

// Kiểm tra sản phẩm đã có trong giỏ chưa
$stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
$stmt->bind_param("ii", $cart_id, $product_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Nếu có rồi thì cập nhật số lượng
    $row = $res->fetch_assoc();
    $newQty = $row['quantity'] + $quantity;

    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newQty, $row['id']);
    $stmt->execute();
} else {
    // Nếu chưa có thì thêm mới
    $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
    $stmt->execute();
}

// Quay lại trang giỏ hàng
header("Location: cart.php");
exit;
?>