
-- Tạo bảng Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('admin', 'user') DEFAULT 'user',  -- Quyền admin hoặc người dùng
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng Books
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT,
    user_id INT NOT NULL,  -- Nhà cung cấp nội dung (ID của người dùng)
    file_path VARCHAR(255) NOT NULL,  -- Đường dẫn đến file e-book
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng Chapters
CREATE TABLE chapters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,  -- Liên kết đến bảng books
    title VARCHAR(255) NOT NULL,
    content TEXT,
    status ENUM('locked', 'unlocked') DEFAULT 'locked',  -- Tình trạng chương (có mở khóa hay không)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Tạo bảng Payments
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,  -- Người dùng đã thanh toán
    chapter_id INT NOT NULL,  -- Chương đã được thanh toán
    amount DECIMAL(10, 2) NOT NULL,  -- Số tiền thanh toán
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
);

-- Tạo bảng Content Providers (Nhà cung cấp nội dung)
CREATE TABLE content_providers (
    user_id INT PRIMARY KEY,  -- Liên kết với bảng users
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tạo bảng User Chapters (Theo dõi việc mở khóa các chương)
CREATE TABLE user_chapters (
    user_id INT NOT NULL,  -- ID của người dùng
    book_id INT NOT NULL,  -- ID của sách
    chapter_id INT NOT NULL,  -- ID của chương
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Thời gian mở khóa
    status ENUM('unlocked', 'locked') DEFAULT 'unlocked',  -- Trạng thái (đã mở khóa hay chưa)
    PRIMARY KEY (user_id, book_id, chapter_id),  -- Khoá chính (composite key)
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
);

-- Tạo Index cho bảng user_chapters để tăng hiệu suất truy vấn
CREATE INDEX idx_user_chapters_user_book_chapter ON user_chapters (user_id, book_id, chapter_id);
