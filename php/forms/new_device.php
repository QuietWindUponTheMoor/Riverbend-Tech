<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST);

// Get values
$asset = $data["asset"];
$serial = $data["serial"];
$PO = $data["PurchaseOrder"];
$model = $data["model"];
$building = $data["building"];
$assignment = $data["assignment"];
$person = $data["person"];

// Update device record
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
    strtoupper($asset),
    strtoupper($serial),
    strtoupper($PO),
    strtoupper($model),
    strtoupper($building),
    strtoupper($assignment),
    $person
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;