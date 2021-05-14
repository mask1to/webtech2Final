<?php

include "../config/config.php";

$id = $_POST['id'];

$sql = "DELETE FROM test WHERE id = '$id'";

$conn->query($sql);

if($conn->query($sql))
{
    echo 1;
} 
else {
    echo 0;
}