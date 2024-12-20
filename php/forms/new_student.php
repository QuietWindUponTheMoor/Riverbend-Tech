<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST, function($value) {
    return $value !== "" && $value !== false;
});

// Get values
$sid = $data["sid"];
$first = $data["first"];
$last = $data["last"];
$grade = $data["grade"];
$homeroom = $data["homeroom"] ?? null;
$homeroom = $homeroom ? strtoupper($homeroom) : null;
$email = $data["email"] ?? "UNSET";
$asset = $data["asset"] ?? null;
$asset = $asset ? strtoupper($asset) : null;
$loaner = $data["loaner"] ?? null;
$loaner = $loaner ? strtoupper($loaner) : null;

// Update device record
$insert = new Query(
    $conn,
    "i",
    "INSERT INTO students (`sid`, `first`, `last`, grade, homeroom, email, device, loaner)
    VALUES (?, ?, ?, UPPER(?), ?, ?, (SELECT IFNULL(deviceID, NULL) FROM devices WHERE UPPER(asset) = UPPER(?) LIMIT 1), (SELECT IFNULL(deviceID, NULL) FROM devices WHERE UPPER(asset) = UPPER(?) LIMIT 1))
    ON DUPLICATE KEY UPDATE
        `sid`=VALUES(`sid`),
        `first`=VALUES(`first`),
        `last`=VALUES(`last`),
        grade=VALUES(grade),
        homeroom=VALUES(homeroom),
        email=VALUES(email),
        device=VALUES(device),
        loaner=VALUES(loaner);",
    "ssssssss",
    $sid,
    ucfirst($first),
    ucfirst($last),
    $grade,
    $homeroom,
    $email,
    $asset,
    $loaner
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;