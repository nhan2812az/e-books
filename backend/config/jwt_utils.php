<?php
use Firebase\JWT\JWT;

define('JWT_SECRET_KEY', 'your_secret_key');  // Thay đổi secret key của bạn
define('JWT_ALGORITHM', 'HS256');  // Thuật toán mã hóa

// Hàm tạo JWT
function createJWT($user_id) {
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // JWT hết hạn sau 1 giờ
    $payload = array(
        'iat' => $issuedAt,
        'exp' => $expirationTime,
        'sub' => $user_id
    );

    // Mã hóa JWT với secret key và thuật toán
    $jwt = JWT::encode($payload, JWT_SECRET_KEY, JWT_ALGORITHM);
    return $jwt;
}

// Hàm xác thực JWT
function validateJWT($jwt) {
    try {
        $decoded = JWT::decode($jwt, JWT_SECRET_KEY, array(JWT_ALGORITHM));
        return (object) $decoded;  // Trả về dữ liệu đã giải mã
    } catch (Exception $e) {
        return null;  // Trả về null nếu JWT không hợp lệ
    }
}
?>
