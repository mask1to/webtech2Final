<?php
session_start();
//file_put_contents("images/math_questions/".$_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'].".jpg", $_POST['file']);
$file_name = $_SESSION['studentName'] . "_" . $_SESSION['studentSurname'] . "_" . $_SESSION['testCode'];
$_FILES["file-math"]["type"] = "image/jpg";
$file_type = $_FILES['file-math']['type'];
$file_size = $_FILES['file-math']['size'];

$new_name = strtolower(substr($_FILES['file-math']['name'], strpos($_FILES['file-math']['name'], '.')));

$file_store = "images/math_questions/" . $file_name . ".jpg";
$file_tem_loc = $_FILES['file-math']['tmp_name'];
move_uploaded_file($file_tem_loc, $file_store);
