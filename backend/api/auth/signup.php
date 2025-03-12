<?php
require_once '../../config/db.php';  // Kết nối cơ sở dữ liệu
require_once '../../config/jwt_utils.php';  // Đảm bảo bạn đã tạo hàm tạo và xác thực JWT trong file này

// Kiểm tra xem có dữ liệu POST gửi lên không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->username) && isset($data->email) && isset($data->password)) {
        $username = $data->username;
        $email = $data->email;
        $password = $data->password;

        // Kiểm tra xem tên đăng nhập đã tồn tại chưa
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            echo json_encode(['error' => 'Username already exists']);
            exit;
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Thêm người dùng mới vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashedPassword])) {
            // Lấy ID của người dùng mới để tạo JWT
            $user_id = $pdo->lastInsertId();
            $jwt = createJWT($user_id);  // Tạo JWT

            echo json_encode(['message' => 'User registered successfully', 'jwt' => $jwt]);
        } else {
            echo json_encode(['error' => 'Error registering user, please try again.']);
        }
    } else {
        echo json_encode(['error' => 'Invalid input']);
    }
}
?>
