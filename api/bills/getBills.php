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
            $sql2 = "SELECT * FROM bills
            WHERE id_user = (SELECT id from users WHERE email = '$email')
            ORDER BY created_at desc";

            $listBill = executeResult($sql2);

            $billDetail = [];
            for ($i = 0; $i < count($listBill); $i++) {
                $idBill = $listBill[$i]['id'];
                $sql3 = "SELECT ct.id_bill, ct.id_product, ct.quantity, sp.name
                FROM bill_detail ct JOIN products sp ON ct.id_product = sp.id
                WHERE id_bill = $idBill";

                $data = executeResult($sql3);
                array_push($billDetail, $data);
            }

            echo json_encode(array(
                'listBill' => $listBill,
                'billDetail' => $billDetail,
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