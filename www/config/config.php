<?php

const servername = "db";
const username = "root";
const password = "admin";
const database = "finalne";

$conn = new mysqli(servername, username, password, database, 3306);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>