<?php

include "../config/config.php";
include "../queries/queries.php";

$questionId = $_POST['questionId'];
$userId = $_POST['userId'];
$points = $_POST['points'];

$result = insertPoints($conn, $questionId, $userId, $points);
echo $result;
