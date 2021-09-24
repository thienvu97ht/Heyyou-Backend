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
$start = $_GET["_start"];
$limit = $_GET["_limit"];
$category = $_GET["_category"];

// Đếm số lượng sản phẩm
if ($category === 'allitems') {
    $sql = "SELECT COUNT(*) AS total
    FROM products JOIN categories on products.id_loai = categories.id";

    $result = executeResult($sql, true);
    echo json_encode($result);
} else {
    $sql = "SELECT COUNT(*) AS total
    FROM products JOIN categories on products.id_loai = categories.id
    WHERE categories.name = '$category'";

    $result = executeResult($sql, true);
    echo json_encode($result);
}