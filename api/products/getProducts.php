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
$sort = $_GET["_sort"];

$sortArr = explode(":", $sort);
$sortBy = $sortArr[0];
$order = $sortArr[1];

if ($category === 'allitems') {
    $sql = "SELECT products.id, products.sale_price as salePrice, products.origin_price as originPrice, products.quantity, 
    products.sold, products.name as nameProduct, products.thumbnail, products.content, 
    categories.name as nameCategory, products.created_at
    FROM products JOIN categories on products.id_loai = categories.id
    ORDER BY products.$sortBy $order LIMIT $start, $limit";

    $productList = executeResult($sql);

    echo json_encode($productList);
} else {
    $sql = "SELECT products.id, products.sale_price as salePrice, products.origin_price as originPrice, products.quantity, 
    products.sold, products.name as nameProduct, products.thumbnail, products.content, 
    categories.name as nameCategory, products.created_at 
    FROM products JOIN categories on products.id_loai = categories.id 
    WHERE categories.name = '$category'
    ORDER BY products.$sortBy $order LIMIT $start, $limit";

    $productList = executeResult($sql);

    echo json_encode($productList);
}