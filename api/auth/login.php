<?php

// Import thư viện
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
$email = $data->identifier;
$password = $data->password;


// Check xem username có tồn tại trong hệ thống
$sql = "SELECT * FROM users WHERE email = '$email'";
$user = executeResult($sql, true);

if ($user) {

    // $hash = password_hash($password, PASSWORD_BCRYPT);
    $is_password_valid = password_verify($password, $user['hash_password']);

    if ($is_password_valid) {
        // Tạo token khi đăng nhập thành công
        $token = array(
            "name" => $user['fullname'],
            "email" => $user['email'],
            "role" => $user['role']
        );

        $sql = "SELECT  u.fullname, u.email, u.address, u.phone, u.avatar 
            FROM users u WHERE email = '$email'";
        $user = executeResult($sql, true);


        // Mã hóa token
        $access_token = JWT::encode($token, $MY_SECRET_KEY);

        // Sever trả về
        http_response_code(200);
        echo json_encode(array(
            'access_token' => $access_token,
            'user' => $user
        ));
        // echo json_encode($user);
    } else {
        http_response_code(400);
        echo json_encode(array('message' => "Mật khẩu không chính xác"));
        // echo "Username and Password do not match";
    }
} else {
    http_response_code(404);
    echo json_encode(array('message' => "Người dùng không tồn tại"));
}