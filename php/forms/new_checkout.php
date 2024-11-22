<?php

// Import required modules
require("../connector.php");
require("../database/queries.php");

// Process data
$data = array_filter($_POST);
$settings = json_decode($data["settings"], true /* Get list instead of stdClass object */);

$insert = new Query(
    $conn,
    "i",
    "INSERT INTO Checkouts (studentID, assignedCB, loanerCB, school, grade, issue) VALUES (?, ?, ?, ?, ?, ?);",
    "ssssss",
    $settings["student"],
    $settings["cb_num"],
    $settings["loaner_num"],
    $settings["school"],
    $settings["grade"],
    $settings["brief_issue"],
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

//echo var_dump($settings["student"]);

echo 1;