<?php

// Import thư viện
include_once '../../helpers/getBearerToken.php';
include_once '../../configs/core.php';
include_once '../../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../../libs/php-jwt-master/src/ExpiredException.php';
include_once '../../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../libs/php-jwt-master/src/JWT.php';
include_once '../../helpers/cors.php';
include_once '../../db/dbhelper.php';

cors();

use Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"));
$id = $data->id;

// Lấy token từ header
$access_token = getBearerToken();

// Kiểm tra xem access token có tồn tại
if ($access_token) {
    try {
        // Giải mã token
        $decoded = JWT::decode($access_token, $MY_SECRET_KEY, array('HS256'));
        http_response_code(200);
        $email = $decoded->email;

        if ($decoded) {
            $sql1 = "DELETE FROM addresses WHERE addresses.id = $id";

            execute($sql1);

            $sql2 = "SELECT  u.fullname, u.email, u.avatar 
            FROM users u WHERE email = '$email'";
            $user = executeResult($sql2, true);

            $sql3 = "SELECT * FROM addresses WHERE id_user = (SELECT id FROM users u WHERE u.email = '$email')";
            $addresses = executeResult($sql3);

            echo json_encode(array(
                'user' => $user,
                'addresses' => $addresses
            ));
        } else {
            echo json_encode(array(
                'message' => "Access denied"
            ));
        }
    } catch (Exception $e) {
        echo json_encode(array(
            'message' => "Access denied",
            "error" => $e->getMessage()
        ));
    }
} else {
    echo json_encode(array('message' => "Access denied"));
}