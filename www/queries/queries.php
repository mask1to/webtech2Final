<?php

function insertNewTeacher($conn, $type, $name, $surname, $password, $email)
{
    $insertquery = "INSERT INTO user(type, name, surname, password, email)
                     VALUES('$type', '$name', '$surname', '$password','$email')";
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
