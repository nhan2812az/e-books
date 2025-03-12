<?php
require_once '../../config/db.php';  // Kết nối cơ sở dữ liệu
require_once '../../config/jwt_utils.php';  // Đảm bảo bạn đã tạo hàm tạo và xác thực JWT trong file này

// Kiểm tra xem có dữ liệu POST gửi lên không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->username) && isset($data->password)) {
        $username = $data->username;
        $password = $data->password;

        // Kiểm tra người dùng có tồn tại trong cơ sở dữ liệu không
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công, tạo JWT
            $user_id = $user['id'];
            $jwt = createJWT($user_id);
            echo json_encode(['message' => 'Login successful', 'jwt' => $jwt]);
        } else {
            echo json_encode(['error' => 'Invalid username or password']);
        }
    } else {
        echo json_encode(['error' => 'Invalid input']);
    }
}
?>
