<?php

include "../config/config.php";
include "../queries/queries.php";

session_start();

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$name = $_POST['name'];
$time = $_POST['time'];
$points = $_POST['points'];
$code = generateRandomString(5);

$sqlCode = "SELECT test_code FROM test WHERE test_code = '$code'";

if($conn->query($sqlCode)->num_rows == 0) {
    $result = insertNewTest($conn, $name, $time, $points, $code, $_SESSION['id']);
    if($result > 0) {
        echo $result;
    }
    else {
        echo 0;
    }
}

?>