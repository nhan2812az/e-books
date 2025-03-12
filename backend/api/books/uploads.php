<?php
require_once '../../config/db.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu có file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['ebook'])) {
    // Đọc thông tin file
    $file = $_FILES['ebook'];
    $filename = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    // Kiểm tra nếu không có lỗi trong quá trình upload
    if ($fileError === 0) {
        // Đặt đường dẫn để lưu file
        $fileDestination = '../../uploads/' . $filename;
        
        // Di chuyển file đến thư mục đích
        move_uploaded_file($fileTmpName, $fileDestination);
        
        // Lưu thông tin sách vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO books (title, file_path, user_id) VALUES (?, ?, ?)");
        $stmt->execute(['Tên sách', $fileDestination, $userId]);  // userId lấy từ session hoặc token
        
        echo json_encode(['message' => 'Sách đã được tải lên thành công']);
    } else {
        echo json_encode(['error' => 'Lỗi trong quá trình tải lên file']);
    }
}
?>
