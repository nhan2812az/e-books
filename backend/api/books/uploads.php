<?php
require_once '../../config/db.php';  // Kết nối cơ sở dữ liệu
require_once '../../config/jwt_utils.php';  // Tạo và xác thực JWT

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

        // Phân tách file thành các chapters (giả sử là ePub hoặc PDF)
        $chapters = extractChapters($fileDestination);

        // Lưu thông tin sách vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO books (title, file_path, user_id) VALUES (?, ?, ?)");
        $stmt->execute(['Tên sách', $fileDestination, $userId]);  // userId lấy từ session hoặc token
        
        // Lấy ID của sách mới
        $book_id = $pdo->lastInsertId();

        // Lưu thông tin chapters vào cơ sở dữ liệu
        foreach ($chapters as $chapter) {
            $stmt = $pdo->prepare("INSERT INTO chapters (book_id, title, content) VALUES (?, ?, ?)");
            $stmt->execute([$book_id, $chapter['title'], $chapter['content']]);
        }

        echo json_encode(['message' => 'Sách đã được tải lên và phân tách thành các chương']);
    } else {
        echo json_encode(['error' => 'Lỗi trong quá trình tải lên file']);
    }
}

// Hàm phân tách e-book thành các chapters
function extractChapters($filePath) {
    // Tùy thuộc vào định dạng file, sử dụng thư viện phù hợp để phân tách chapters
    // Ví dụ: sử dụng thư viện epub hoặc pdf để đọc và tách các chapter
    
    // Giả sử bạn sử dụng thư viện epub.js hoặc pdf-parse để lấy thông tin chapters
    // Sau đó trả về một mảng các chapter với title và content của từng chapter.
    
    return [
        ['title' => 'Chapter 1', 'content' => 'Nội dung của Chapter 1'],
        ['title' => 'Chapter 2', 'content' => 'Nội dung của Chapter 2'],
        // Thêm các chapter tương ứng
    ];
}
?>
