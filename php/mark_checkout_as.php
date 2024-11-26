<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);


/*
 * This file allows a "mark as" for marking checkouts as started or finished. "type" must be set as POST.
 * types: "started", "finished"
 */

switch ($data["type"]) {
    case "started":
        $insert = new Query(
            $conn,
            "i",
            "UPDATE checkouts SET started=? WHERE recordID=?;",
            "ss",
            TRUE,
            $data["rid"]
        ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");
        break;
    case "finished":
        $insert = new Query(
            $conn,
            "i",
            "UPDATE checkouts SET finished=? WHERE recordID=?;",
            "ss",
            TRUE,
            $data["rid"]
        ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");
        break;
    default:
        echo 0;
        break;
}

echo 1;