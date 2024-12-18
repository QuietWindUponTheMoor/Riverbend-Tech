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
    "INSERT INTO checkouts (studentID, loanerCB, issue) VALUES (?, ?, ?);",
    "sss",
    $sid,
    strtoupper($loaner),
    $issue,
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;