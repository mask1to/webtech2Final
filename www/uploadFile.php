<?php

if(isset($_POST['upload']) && isset($_SESSION['studentName']) && isset($_SESSION['studentSurname']) && isset($_SESSION['testCode'])){
    $file_name = $_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode']."_";
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];
    $file_store = "images/".$file_name.$_FILES['file']['name'];
    $file_tem_loc = $_FILES['file']['tmp_name'];
    move_uploaded_file($file_tem_loc, $file_store);
}