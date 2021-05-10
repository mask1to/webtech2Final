<?php

include "../config/config.php";

$check = $_POST['check'];
$id = $_POST['id'];

$sql = "UPDATE test SET isActive = '$check' WHERE id = '$id'";

$conn->query($sql);