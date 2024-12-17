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
    "INSERT INTO checkouts (studentID, loanerCB, issue, assignedCB, school, grade)
    VALUES (
        ?, ?, ?,
        (SELECT device_asset FROM students WHERE `sid`=?),
        (CASE 
            WHEN (SELECT grade FROM students WHERE `sid`=?) IN ('K', '1', '2', '3', '4', '5') THEN 'FES'
            WHEN (SELECT grade FROM students WHERE `sid`=?) IN ('6', '7', '8') THEN 'RBMS'
            WHEN (SELECT grade FROM students WHERE `sid`=?) IN ('9', '10', '11', '12') THEN 'FHS'
            ELSE NULL
        END),
        (SELECT grade FROM students WHERE `sid`=?)
    );",
    "ssssssss",
    $sid,
    strtoupper($loaner),
    $issue,
    $sid,
    $sid,
    $sid,
    $sid,
    $sid,
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

$insert = new Query(
    $conn,
    "i",
    "UPDATE students SET loaner_asset=? WHERE `sid`=?;",
    "ss",
    strtoupper($loaner),
    $sid,
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

$insert = new Query(
    $conn,
    "i",
    "UPDATE cbinventory SET PERSON=? WHERE UPPER(asset)=UPPER(?);",
    "ss",
    $sid,
    strtoupper($loaner),
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;