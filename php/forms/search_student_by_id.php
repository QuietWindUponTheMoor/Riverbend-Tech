<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);

$sid = $data["sid"];

$Query = new Query(
    $conn,
    "s",
    "SELECT `sid`, `first`, `last`, email FROM students WHERE `sid` LIKE CONCAT('%', ?, '%') LIMIT 5;",
    "s",
    $sid
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

$result = $Query->result;
if ($result->num_rows > 0) {

    $records = [];

    while ($r = mysqli_fetch_assoc($result)) {

        array_push($records, $r);

    }

    echo json_encode($records, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

}