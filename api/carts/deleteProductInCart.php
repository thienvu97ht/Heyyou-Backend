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
            $sql1 = "DELETE FROM carts WHERE carts.id_product = $id AND carts.id_user = 
            (SELECT id FROM users u WHERE u.email = '$email')";
            execute($sql1);

            $sql2 = "SELECT p.id, p.name as nameProduct, p.sale_price AS salePrice, 
            p.origin_price AS originPrice, p.thumbnail, c.quantity
            FROM products p JOIN carts c ON p.id = c.id_product 
            WHERE c.id_user = (SELECT id FROM users u WHERE u.email = '$email')";

            $productList = executeResult($sql2);

            echo json_encode($productList);
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