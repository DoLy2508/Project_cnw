<?php
$host = "localhost:3306";
$user = "root";   // user mặc định Laragon/XAMPP
$pass = "";       // mật khẩu thường để trống
$db   = "spck";

$conn = new mysqli($host, $user, $pass, $db);
// // Thiết lập charset
// $conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
