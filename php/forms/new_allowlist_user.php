<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);

// Get values
$email = $data["email"];

// Update device record
$insert = new Query(
    $conn,
    "i",
    "INSERT INTO external_whitelist (email) VALUES (?);",
    "s",
    strtolower($email),
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;