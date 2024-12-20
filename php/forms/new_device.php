<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

// Process data
$data = array_filter($_POST, function($value) {
    return $value !== "" && $value !== false;
});


// Get values
$asset = $data["asset"];
$serial = $data["serial"];
$PO = $data["PurchaseOrder"] ?? null;
$model = $data["model"];
$building = $data["building"];

// Update device record
$insert = new Query(
    $conn,
    "i",
    "INSERT INTO devices (asset, `serial`, PO, model)
    VALUES (UPPER(?), UPPER(?), UPPER(?), UPPER(?))
    ON DUPLICATE KEY UPDATE
        asset=VALUES(asset),
        `serial`=VALUES(`serial`),
        PO=VALUES(PO),
        model=VALUES(model);",
    "ssss",
    $asset,
    $serial,
    $PO,
    $model,
) or die("There was an issue inserting the data into the database, please try again or contact an administrator.");

echo 1;