<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);

if (isset($data["settings"])) {
    $settings = json_decode($data["settings"], true /* Get list instead of stdClass object */);
    createCheckout(
        $conn,
        $settings["student"],
        $settings["cb_num"],
        $settings["loaner_num"],
        $settings["school"],
        $settings["grade"],
        $settings["brief_issue"]
    );
} else {
    $d = $_POST;
    updateCheckout(
        $conn,
        $d["rid"],
        $d["sid"],
        $d["assignedCB"],
        $d["loanerCB"],
        $d["school"],
        $d["grade"],
        $d["issue"],
        $d["started"],
        $d["finished"],
        $d["softDeleted"],
    );
}



echo 1;

// Function
function updateCheckout($conn, $rid, $sid, $cb_num, $loaner_num, $school, $grade, $issue, $started, $finished, $softDeleted) {
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
}
function createCheckout($conn, $sid, $cb_num, $loaner_num, $school, $grade, $issue) {
    // Create new checkout record
    $insert = new Query(
        $conn,
        "i",
        "INSERT INTO Checkouts (studentID, assignedCB, loanerCB, school, grade, issue) VALUES (?, ?, ?, ?, ?, ?);",
        "ssssss",
        $sid,
        $cb_num,
        $loaner_num,
        $school,
        $grade,
        $issue
    ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

    // Update device record
    $building = "FHS";
    if ($grade === "K" || $grade < 6) {
        $building = "FES";
    }
    if ($grade > 5 && $grade < 9) {
        $building = "RBMS";
    }
    $insert = new Query(
        $conn,
        "i",
        "INSERT INTO cbinventory (asset, `serial`, PO, model, building, assignment, person)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            assignment=VALUES(assignment),
            person=VALUES(person),
            asset=VALUES(asset),
            `serial`=VALUES(`serial`),
            PO=VALUES(PO),
            model=VALUES(model),
            building=VALUES(building);",
        "sssssss",
        strtoupper($loaner_num),
        "UNSET",
        "NULL",
        "UNKNOWN",
        $building,
        "STUDENT",
        $sid
    ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

    // Update student record
    $insert = new Query(
        $conn,
        "i",
        "INSERT INTO students (`sid`, `first`, `last`, grade, homeroom, email, device_asset, loaner_asset)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            loaner_asset=VALUES(loaner_asset),
            `first`=VALUES(`first`),
            `last`=VALUES(`last`),
            grade=VALUES(grade),
            homeroom=VALUES(homeroom),
            email=VALUES(email),
            device_asset=VALUES(device_asset);",
        "ssssssss",
        $sid,
        "-",
        "-",
        $grade,
        "UNKNOWN",
        "NULL",
        $cb_num,
        $loaner_num
    ) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");
}