<?php
// auth_middleware.php
require_once '../../config/jwt_utils.php';

function checkJWT() {
    $headers = getallheaders();
    
    if (isset($headers['Authorization'])) {
        $jwt = str_replace('Bearer ', '', $headers['Authorization']);
        $decoded = validateJWT($jwt);

        if ($decoded) {
            return $decoded;
        } else {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(array('error' => 'Invalid or expired JWT'));
            exit();
        }
    } else {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(array('error' => 'Authorization token missing'));
        exit();
    }
}
?>
