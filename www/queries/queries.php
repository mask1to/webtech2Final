<?php

function insertNewTeacher($conn, $type, $name, $surname, $password, $email)
{
    $insertquery = "INSERT INTO user(type, name, surname, password, email)
                     VALUES('$type', '$name', '$surname', '$password','$email')";
    $result = $conn->query($insertquery) or die("Chyba vo vykonávaní query" . $conn->error);
    return $result;
}

function insertNewStudent($conn, $type, $name, $surname, $isWriting, $currentTestCode)
{
    $insertquery = "INSERT INTO user(type, name, surname, isWritingExam, currentTestCode)
                     VALUES('$type', '$name', '$surname', '$isWriting', '$currentTestCode')";
    $result = $conn->query($insertquery) or die("Chyba vo vykonávaní query" . $conn->error);
    return $result;
}

function insertNewTest($conn, $name, $time, $points, $code, $user_id)
{
    $insertQuery = "INSERT INTO test(user_id, test_code, isActive, total_time, name, total_points) 
    VALUES ('$user_id', '$code', 0, '$time', '$name', '$points')";

    $result = $conn->query($insertQuery) or die("Chyba vo vykonávaní query" . $conn->error);
    if ($result) {
        return $conn->insert_id;
    }
    return 0;
}

function insertNewQuestion($conn, $testId, $type, $points, $question)
{
    $insertQuery = "INSERT INTO question(test_id, type, total_points, name) 
    VALUES ('$testId', '$type', '$points', '$question')";

    $result = $conn->query($insertQuery) or die("Chyba vo vykonávaní query" . $conn->error);

    if ($result) {
        return $conn->insert_id;
    }
    return 0;
}

function insertNewOption($conn, $questionId, $correct, $option)
{
    $insertQuery = "INSERT INTO questionOption(question_id, isCorrect, name) 
    VALUES ('$questionId', '$correct', '$option')";

    $result = $conn->query($insertQuery) or die("Chyba vo vykonávaní query" . $conn->error);

    if ($result) {
        return $conn->insert_id;
    }
    return 0;
}

function insertNewPair($conn, $questionId, $questionOptionId, $option)
{
    $insertQuery = "INSERT INTO OptionsPair(question_id,questionOption_id, name) 
    VALUES ('$questionId', '$questionOptionId', '$option')";

    $result = $conn->query($insertQuery) or die("Chyba vo vykonávaní query" . $conn->error);

    if ($result) {
        return 1;
    }
    return 0;
}

function getTestTime($conn, $test_code){
    $getTest = "SELECT total_time FROM test WHERE test_code='$test_code'";

    $result = $conn->query($getTest) or die("Chyba vo vykonávaní query" . $conn->error);

    return $result;
}
function getUserId($conn, $name, $surname, $code){
    $getTest = "SELECT id FROM user WHERE name='$name' and surname='$surname' AND currentTestCode = '$code'";

    $result = $conn->query($getTest) or die("Chyba vo vykonávaní query" . $conn->error);

    return $result;
}
function insertAnswer($conn, $questionId, $text, $isCorrect, $userId, $points)
{
    $insertQuery = "INSERT INTO answer(question_id , text , isCorrect , user_id, points) 
    VALUES ('$questionId', '$text', '$isCorrect', '$userId', '$points')";

    $result = $conn->query($insertQuery) or die("Chyba vo vykonávaní query" . $conn->error);
    if ($result) {
        return $conn->insert_id;
    }
    return 0;
}


