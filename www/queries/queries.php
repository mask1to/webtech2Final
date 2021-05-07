<?php

function insertNewTeacher($conn, $type, $name, $surname, $password, $email)
{
    $insertquery = "INSERT INTO user(type, name, surname, password, email)
                     VALUES('$type', '$name', '$surname', '$password','$email')";
    $result = $conn->query($insertquery) or die("Chyba vo vykonávaní query".$conn->error);
    return $result;
}