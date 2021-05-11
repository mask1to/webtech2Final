<?php

if(isset($_POST['upload']) && isset($_SESSION['studentName']) && isset($_SESSION['studentSurname']) && isset($_SESSION['testCode'])){
    $file_name = $_SESSION['studentName']."_".$_SESSION['studentSurname']."_".$_SESSION['testCode'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];

    $new_name = strtolower(substr($_FILES['file']['name'], strpos($_FILES['file']['name'], '.')));

    $file_store = "images/".$file_name.$new_name;
    $file_tem_loc = $_FILES['file']['tmp_name'];
    move_uploaded_file($file_tem_loc, $file_store);
}