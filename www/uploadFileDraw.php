<?php
session_start();

//file_put_contents("images/math_questions/".$_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'].".jpg", $_POST['file']);
$file_name = $_SESSION['studentName'] . "_" . $_SESSION['studentSurname'] . "_" . $_SESSION['testCode'] . '_' . $_POST['id'];
$_FILES['file']["type"] = "image/jpg";
$file_type = $_FILES['file']['type'];
$file_size = $_FILES['file']['size'];

$new_name = strtolower(substr($_FILES['file']['name'], strpos($_FILES['file']['name'], '.')));

$file_store = "images/drawing_questions/" . $file_name . ".jpg";
$file_tem_loc = $_FILES['file']['tmp_name'];
move_uploaded_file($file_tem_loc, $file_store);