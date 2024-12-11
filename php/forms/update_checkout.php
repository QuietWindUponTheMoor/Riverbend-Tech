<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$d = array_filter($_POST);

$rid = $d["rid"];
$sid = $d["sid"];
$cb_num = $d["assignedCB"];
$loaner_num = $d["loanerCB"];
$school = $d["school"];
$grade = $d["grade"];
$issue = $d["issue"];
$started = $d["started"];
$finished = $d["finished"];
$softDeleted = $d["softDeleted"];

$insert = new Query(
    $conn,
    "i",
    "UPDATE Checkouts
        SET 
            studentID = CASE WHEN ? IS NOT NULL THEN ? ELSE studentID END,
            assignedCB = CASE WHEN ? IS NOT NULL THEN ? ELSE assignedCB END,
            loanerCB = CASE WHEN ? IS NOT NULL THEN ? ELSE loanerCB END,
            school = CASE WHEN ? IS NOT NULL THEN ? ELSE school END,
            grade = CASE WHEN ? IS NOT NULL THEN ? ELSE grade END,
            issue = CASE WHEN ? IS NOT NULL THEN ? ELSE issue END,
            `started` = CASE WHEN ? IS NOT NULL THEN ? ELSE `started` END,
            `finished` = CASE WHEN ? IS NOT NULL THEN ? ELSE `finished` END,
            softDeleted = CASE WHEN ? IS NOT NULL THEN ? ELSE softDeleted END
    WHERE recordID=?;
    ",
    "sssssssssssssssssss",
    $sid,
    $sid,
    $cb_num,
    $cb_num,
    $loaner_num,
    $loaner_num,
    $school,
    $school,
    $grade,
    $grade,
    $issue,
    $issue,
    $started,
    $started,
    $finished,
    $finished,
    $softDeleted,
    $softDeleted,
    $rid
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;