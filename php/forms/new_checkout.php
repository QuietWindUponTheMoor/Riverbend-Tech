<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);

$sid = $data["sid"];
$loaner = $data["asset"];
$issue = $data["issue"];

$insert = new Query(
    $conn,
    "i",
    "INSERT INTO checkouts (`sid`, issue) VALUES (?, ?, ?);",
    "ss",
    $sid,
    $issue,
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

$insert = new Query(
    $conn,
    "i",
    "UPDATE devices SET assignment = 'LOANER' WHERE UPPER(asset) = UPPER(?);",
    "s",
    $loaner
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

$insert = new Query(
    $conn,
    "i",
    "UPDATE students SET loaner_asset = UPPER(?) WHERE `sid` = ?;",
    "ss",
    $loaner,
    $sid
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;