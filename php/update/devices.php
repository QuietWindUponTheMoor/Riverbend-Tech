<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);
$whereValue = $data["whereValue"]; // `serial`
$colToUpdate = $data["colToUpdate"];
$newValue = $data["newValue"] ?? null;

// Main update query
$Query = new Query(
    $conn,
    "i",
    "UPDATE cbinventory SET `$colToUpdate`=? WHERE `serial`=?;",
    "ss",
    $newValue,
    $whereValue
) or die("There was an issue collecting data from the database, please try again or contact an administrator.");

echo 1;