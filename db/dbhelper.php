<?php
// require_once 'config.php';

/**
 * Su dung voi cau lenh query: insert, update, delete -> ko tra ve ket qua.
 */
function execute($sql)
{
    $severname = "127.0.0.1:3308";
    $username = "root";
    $pass = "";
    $dbname = "shopbee";

    //Mo ket noi toi database
    $conn = mysqli_connect($severname, $username, $pass, $dbname);
    mysqli_set_charset($conn, 'utf8');

    //Xu ly cau query
    mysqli_query($conn, $sql);

    //Dong ket noi database
    mysqli_close($conn);
}

/**
 * Su dung voi cau lenh query: select.
 */
function executeResult($sql, $isSingleRecord = false)
{
    $severname = "127.0.0.1:3308";
    $username = "root";
    $pass = "";
    $dbname = "shopbee";

    //Mo ket noi toi database
    $conn = mysqli_connect($severname, $username, $pass, $dbname);
    mysqli_set_charset($conn, 'utf8');

    // echo $sql;
    //Xu ly cau query
    $resultset = mysqli_query($conn, $sql);
    // var_dump($resultset);
    // die();
    if ($isSingleRecord) {
        $data = mysqli_fetch_array($resultset, 1);
    } else {
        $data = [];
        while (($row = mysqli_fetch_array($resultset, 1)) != null) {
            $data[] = $row;
        }
    }

    //Dong ket noi database
    mysqli_close($conn);

    return $data;
}