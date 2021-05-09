<?php

include "../config/config.php";
include "../queries/queries.php";

$option = $_POST['option'];
$questionId = $_POST['questionId'];
$correct = $_POST['correct'];

$result = insertNewOption($conn, $questionId, $correct, $option);
if ($result > 0) {
    echo $result;
} else {
    echo 0;
}
