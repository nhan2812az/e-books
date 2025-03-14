<?php
require_once '../../config/db.php';  // Kết nối cơ sở dữ liệu
require_once '../../config/jwt_utils.php';  // Để xác thực JWT nếu cần

// Kiểm tra nếu có file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['ebook'])) {
    $file = $_FILES['ebook'];
    $filename = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];
    $fileSize = $file['size'];
    $fileType = $file['type'];

    // Kiểm tra lỗi trong quá trình upload
    if ($fileError !== 0) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File quá lớn (quá kích thước tối đa trong php.ini)',
            UPLOAD_ERR_FORM_SIZE => 'File vượt quá giới hạn trong HTML form',
            UPLOAD_ERR_PARTIAL => 'File tải lên bị gián đoạn',
            UPLOAD_ERR_NO_FILE => 'Không có file nào được tải lên',
            UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm thời',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi vào đĩa',
            UPLOAD_ERR_EXTENSION => 'Lỗi do phần mở rộng PHP',
        ];
        echo json_encode(['error' => $errorMessages[$fileError] ?? 'Lỗi không xác định']);
        exit;
    }

    // Kiểm tra kích thước file (giới hạn tối đa 50MB)
    if ($fileSize > 50 * 1024 * 1024) {
        echo json_encode(['error' => 'File quá lớn, vui lòng tải lên file dưới 50MB']);
        exit;
    }

    // Kiểm tra loại file (chỉ cho phép ePub hoặc PDF)
    $allowedTypes = ['application/epub+zip', 'application/pdf'];
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['error' => 'Chỉ chấp nhận các file ePub hoặc PDF']);
        exit;
    }

    // Đặt đường dẫn để lưu file
    $fileDestination = '../../uploads/' . $filename;

    // Kiểm tra nếu file đã tồn tại
    if (file_exists($fileDestination)) {
        echo json_encode(['error' => 'File đã tồn tại']);
        exit;
    }

    // Di chuyển file đến thư mục đích
    if (move_uploaded_file($fileTmpName, $fileDestination)) {
        // Phân tách file thành các chapters
        $chapters = extractChapters($fileDestination);

        // Lưu thông tin sách vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO books (title, file_path, user_id) VALUES (?, ?, ?)");
        $stmt->execute(['Tên sách', $fileDestination, 1]);  // Giả sử user_id = 1

        // Lấy ID của sách vừa thêm
        $book_id = $pdo->lastInsertId();

        // Lưu các chapters vào cơ sở dữ liệu
        foreach ($chapters as $chapter) {
            $stmt = $pdo->prepare("INSERT INTO chapters (book_id, title, content) VALUES (?, ?, ?)");
            $stmt->execute([$book_id, $chapter['title'], $chapter['content']]);
        }

        echo json_encode(['message' => 'Sách đã được tải lên và phân tách thành các chương']);
    } else {
        echo json_encode(['error' => 'Lỗi trong quá trình tải lên file']);
    }
} else {
    echo json_encode(['error' => 'Không có file được gửi']);
}

// Hàm phân tách e-book thành các chapters (giả sử là ePub hoặc PDF)
function extractChapters($filePath) {
    // Ví dụ, giả sử bạn dùng thư viện ePub.js hoặc pdf-parse để phân tách các chapters
    if (strpos($filePath, '.epub') !== false) {
        return extractChaptersFromEpub($filePath);
    } elseif (strpos($filePath, '.pdf') !== false) {
        return extractChaptersFromPdf($filePath);
    }

    return [];
}

// Hàm giả lập phân tách ePub thành chapters
function extractChaptersFromEpub($filePath) {
    return [
        ['title' => 'Chapter 1', 'content' => 'Nội dung Chapter 1'],
        ['title' => 'Chapter 2', 'content' => 'Nội dung Chapter 2'],
    ];
}

// Hàm giả lập phân tách PDF thành chapters
function extractChaptersFromPdf($filePath) {
    return [
        ['title' => 'Chapter 1', 'content' => 'Nội dung Chapter 1'],
        ['title' => 'Chapter 2', 'content' => 'Nội dung Chapter 2'],
    ];
}
?>
