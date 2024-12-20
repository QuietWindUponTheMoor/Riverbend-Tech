<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);

$Query = new Query(
    $conn,
    "sa",
    "SELECT 
        checkouts.*, 
        students.first, 
        students.last,
        students.grade,
        devices.asset AS loaner
    FROM 
        checkouts
    LEFT JOIN 
        students ON checkouts.studentID = students.sid
    LEFT JOIN 
        devices ON students.loaner = devices.deviceID;",
) or die("There was an issue collecting data from the database, please try again or contact an administrator.");
$result = $Query->result;

$records = [];

if ($result->num_rows > 0) {
    while ($r = mysqli_fetch_assoc($result)) {
        array_push($records, $r);
    }
}

echo json_encode($records, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);