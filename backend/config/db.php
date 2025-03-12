<?php
$host = 'localhost';
$dbname = 'ebooks_db';
$username = 'root';  // Thay đổi theo thông tin kết nối của bạn
$password = '';

// Kết nối tới cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
