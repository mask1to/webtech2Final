<?php

if (!isset($_SESSION)) {
    session_start();
}

$student_name = $_SESSION['studentName'];
$student_surname = $_SESSION['studentSurname'];
$test_code = $_SESSION['testCode'];

if(isset($_POST['img_draw']) && $student_name && $student_surname && $test_code){
    file_put_contents("images/drawing_questions/".$_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'] . '.png', file_get_contents($_POST['img_draw']));
}



if(isset($_POST['upload']) && isset($_SESSION['studentName']) && isset($_SESSION['studentSurname']) && isset($_SESSION['testCode'])){
    $file_name = $_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];

    $new_name = strtolower(substr($_FILES['file']['name'], strpos($_FILES['file']['name'], '.')));

    $file_store = "images/math_questions/".$file_name.$new_name;
    $file_tem_loc = $_FILES['file']['tmp_name'];
    move_uploaded_file($file_tem_loc, $file_store);
}