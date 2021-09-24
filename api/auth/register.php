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
$fullname = $data->fullName;
$email = $data->email;
$password = $data->passwordRegister;


// Kiểu tra xem username và email có tồn tại trong hệ thống
$sql = "select * from users where email = '$email'";

// Lấy dữ liệu ra
$result = executeResult($sql);

if ($result == null || count($result) == 0) {
    // Tài khoản không tồn tại trong hệ thống
    $hash_password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "insert into users(fullname, email, hash_password) values
        ('$fullname', '$email', '$hash_password')";
    execute($sql);

    $res = [
        "status" => 1,
        "msg" => "Tạo tài khoản thành công!!!",
    ];

    echo json_encode($res);
} else {
    // Tài khoản đã tồn tại trong hệ thống

    $res = [
        "status" => -1,
        "msg" => "Email đã tồn tại!!!",
    ];

    echo json_encode($res);
}