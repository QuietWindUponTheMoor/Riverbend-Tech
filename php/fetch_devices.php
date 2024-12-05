<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

try {
    $Query = new Query(
        $conn,
        "sa",
        "SELECT * FROM cbinventory ORDER BY $grouping $ordering;",
        null,
        null,
    );

    $result = $Query->result;
    if ($result->num_rows > 0) {

        // Fetch the data
        while ($r = mysqli_fetch_assoc($result)) {
            $asset = $r["asset"];
            $serial = $r["serial"];
            $PO = $r["PO"];
            $model = $r["model"];
            $building = $r["building"];
            $assignment = $r["assignment"];
            $person = $r["person"];

            // Object to push
            $record = [
                "asset" => $asset,
                "serial" => $serial,
                "PO" => $PO,
                "model" => $model,
                "building" => $building,
                "assignment" => $assignment,
                "person" => $person,
            ];

            // Push to all
            array_push($records["all"], $record);

            // Assignment
            switch ($assignment) {
                case "STUDENT":
                    array_push($records["STUDENT"], $record);
                    break;
                case "STAFF":
                    array_push($records["STAFF"], $record);
                    break;
                case "LOANER":
                    array_push($records["LOANER"], $record);
                    break;
                default:
                    break;
            }
            
            // School/building
            switch ($building) {
                case "FES":
                    array_push($records["FES"], $record);
                    break;
                case "RBMS":
                    array_push($records["RBMS"], $record);
                    break;
                case "FHS":
                    array_push($records["FHS"], $record);
                    break;
                default:
                    break;
            }
            
            // Models
            switch ($model) {
                case "HPG320":
                    array_push($records["HPG320"], $record);
                    break;
                case "HPG720":
                    array_push($records["HPG720"], $record);
                    break;
                case "HP19":
                    array_push($records["HP19"], $record);
                    break;
                case "HPT19":
                    array_push($records["HPT19"], $record);
                    break;
                case "G8":
                    array_push($records["G8"], $record);
                    break;
                case "HP21":
                    array_push($records["G8"], $record);
                    break;
                case "HPTG9":
                    array_push($records["HPTG9"], $record);
                    break;
                case "23HPTG9":
                    array_push($records["23HPTG9"], $record);
                    break;
                case "G9":
                    array_push($records["G9"], $record);
                    break;
                default:
                    break;
            }
        }
    } else {
        // If no records were found
        echo "No records were found.";
    }

    // Select deprovisioned devices
    $Query = new Query(
        $conn,
        "sa",
        "SELECT * FROM cbinventory_deprovisioned ORDER BY $grouping $ordering;",
        null,
        null,
    );

    $result = $Query->result;
    if ($result->num_rows > 0) {

        // Fetch the data
        while ($r = mysqli_fetch_assoc($result)) {
            $asset = $r["asset"];
            $serial = $r["serial"];
            $PO = $r["PO"];
            $model = $r["model"];
            $building = $r["building"];
            $assignment = $r["assignment"];
            $person = $r["person"];

            // Object to push
            $record = [
                "asset" => $asset,
                "serial" => $serial,
                "PO" => $PO,
                "model" => $model,
                "building" => $building,
                "assignment" => $assignment,
                "person" => $person,
            ];

            // Push to all and deprovisioned
            array_push($records["all"], $record);
            array_push($records["DEPROVISIONED"], $record);
        }
    }
} catch (mysqli_exception $error) {
    echo "Something went wrong fetching data. Error: " + $error;
}

// Close the connection
$conn->close();