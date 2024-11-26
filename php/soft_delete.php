<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);


/*
 * This file allows a soft delete of a loaner record, or a checkout record. "type" must be set as POST.
 * types: "checkouts", "loaners"
 */


// Check what type of record to delete
$from = "";
$to = "";
$toCols = "";
$selectCols = "";
$uniqueKey = "";
switch ($data["type"]) {
    case "checkouts":
        // Soft delete
        $insert = new Query(
            $conn,
            "i",
            "INSERT INTO checkouts_del (recordID, studentID, assignedCB, loanerCB, school, grade, issue, `started`, finished, softDeleted)
            SELECT recordID, studentID, assignedCB, loanerCB, school, grade, issue, `started`, finished, TRUE FROM checkouts
            WHERE recordID=?;",
            "s",
            $data["rid"]
        ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

        // Make sure main record has soft delete value as true
        $insert = new Query(
            $conn,
            "i",
            "UPDATE checkouts SET softDeleted=? WHERE recordID=?",
            "ss",
            TRUE,
            $data["rid"]
        ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

        break;
    case "checkouts":
        // Soft delete
        $insert = new Query(
            $conn,
            "i",
            "INSERT INTO loaners_del (loaner, `serial`, assignment)
            SELECT loaner, `serial`, assignment FROM loaners
            WHERE loaner=?;",
            "s",
            $data["loaner"] // This will be the loaner column value of this record since the "loaners" table does not have an auto_increment key
        ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

        break;
    default:
        break;
}

echo 1;