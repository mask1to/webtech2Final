<?php

include "../config/config.php";
include "../queries/queries.php";

$testId = $_POST['testId'];
$question = $_POST['question'];
$type = $_POST['type'];
$points = $_POST['points'];

$result = insertNewQuestion($conn, $testId, $type, $points, $question);
if ($result > 0)
{
    echo $result;
}
else {
    echo 0;
}
