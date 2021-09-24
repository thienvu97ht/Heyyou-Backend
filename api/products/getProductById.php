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

$sql = "SELECT sp.id, sp.sale_price AS salePrice, sp.quantity, sp.origin_price AS originPrice, 
        sp.name AS nameProduct, sp.content, sp.created_at, lsp.id as categoryId, lsp.name 
        AS nameCategory FROM products sp JOIN categories lsp 
        ON sp.id_loai = lsp.id WHERE sp.id = $id";
$product = executeResult($sql, true);

echo json_encode($product);