<?php

$host = 'localhost';
$port = 3306;
$database = "riverbendtech";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $database, $port);

if ($conn->connect_error) {
    die("Connection to database failed: " + $conn->connect_error);
}