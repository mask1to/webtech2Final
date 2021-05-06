<?php

$servername = "db";
$username = "root";
$password = "admin";
$database = "finalne";

$conn = new mysqli($servername, $username, $password, $database, 3306);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>