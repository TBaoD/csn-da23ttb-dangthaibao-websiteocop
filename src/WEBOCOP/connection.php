<?php
$servername = "127.0.0.1";
$username = "root"; // mặc định XAMPP
$password = ""; // để trống
$dbname = "db_website_ocop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Chỉ lưu session trong thời gian mở trình duyệt
ini_set('session.cookie_lifetime', 0);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>