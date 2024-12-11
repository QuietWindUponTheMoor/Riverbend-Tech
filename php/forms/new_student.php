<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);

// Get values
$sid = $data["sid"];
$first = $data["first"];
$last = $data["last"];
$grade = $data["grade"];
$homeroom = $data["homeroom"];
$email = $data["email"];
$asset = $data["asset"];

// Update device record
$insert = new Query(
    $conn,
    "i",
    "INSERT INTO students (`sid`, `first`, `last`, grade, homeroom, email, device_asset)
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        `sid`=VALUES(`sid`),
        `first`=VALUES(`first`),
        `last`=VALUES(`last`),
        grade=VALUES(grade),
        homeroom=VALUES(homeroom),
        email=VALUES(email),
        device_asset=VALUES(device_asset);",
    "sssssss",
    $sid,
    ucfirst($first),
    ucfirst($last),
    strtoupper($grade),
    strtoupper($homeroom),
    strtolower($email),
    strtoupper($asset)
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;