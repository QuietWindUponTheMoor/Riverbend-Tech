<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices</title>
    <link rel="stylesheet" href="/stylesheets/compiled/main.css">
    <script type="text/javascript" src="/src/scripts/jquery/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="/src/scripts/navbar.js" defer></script>
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
</head>
<body id="main">
    <?php require($_SERVER["DOCUMENT_ROOT"]."/inc/navbar.php"); ?>

    <?php
    $ordering = "ASC";
    $grouping = "assignment";
    // GET variables
    if (!isset($_GET["grouping"])) {
        $_GET["grouping"] = "assignment";
        $grouping = "assignment";
    } else {
        $grouping = $_GET["grouping"];
    }
    if (!isset($_GET["ordering"])) {
        $_GET["ordering"] = "ASC";
        $ordering = "ASC";
    } else {
        $ordering = $_GET["ordering"];
    }

    // Init all records
    $records = [
        "all" => [],
        "DEPROVISIONED" => [],
        "FES" => [],
        "RBMS" => [],
        "FHS" => [],
        "STUDENT" => [],
        "LOANER" => [],
        "STAFF" => [],
        "HPG320" => [],
        "HPG720" => [],
        "HP19" => [],
        "HPT19" => [],
        "G8" => [],
        "HPTG9" => [],
        "23HPTG9" => [],
        "G9" => [],
    ];

    // Fetch all records, then organize
    require($_SERVER["DOCUMENT_ROOT"]."/php/fetch_devices.php");

    // Set JavaScript object
    $jsJSON = json_encode($records, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    echo
    '
    <script type="text/javascript">
    let records = '.$jsJSON.';

    </script>
    ';
    ?>

    <div class="data-section">
        <h2>Devices</h2>
        <a class="button bg-blue" href="/">Main Page</a>
        <div class="filters col">
            <h3>Group By</h3>
            <div class="section row">
                <a class="filter-button button bg-orange <?php if ($_GET["grouping"] === "assignment") {echo "selected";} ?>" href="/devices.php<?php echo '?grouping=assignment&ordering='.$_GET["ordering"]; ?>">Assignment</a>
                <a class="filter-button button bg-orange <?php if ($_GET["grouping"] === "building") {echo "selected";} ?>" href="/devices.php<?php echo '?grouping=building&ordering='.$_GET["ordering"]; ?>">Building</a>
                <a class="filter-button button bg-orange <?php if ($_GET["grouping"] === "model") {echo "selected";} ?>" href="/devices.php<?php echo '?grouping=model&ordering='.$_GET["ordering"]; ?>">Model</a>
                <a class="filter-button button bg-orange <?php if ($_GET["grouping"] === "serial") {echo "selected";} ?>" href="/devices.php<?php echo '?grouping=serial&ordering='.$_GET["ordering"]; ?>">Deprovisioned</a>
            </div>
            <h3>Order By</h3>
            <div class="section row">
                <a class="filter-button button bg-green <?php if ($_GET["ordering"] === "ASC") {echo "selected";} ?>" href="/devices.php<?php echo '?grouping='.$_GET["grouping"].'&ordering=ASC'; ?>">Ascending [a-Z] [1-9]</a>
                <a class="filter-button button bg-green <?php if ($_GET["ordering"] === "DESC") {echo "selected";} ?>" href="/devices.php<?php echo '?grouping='.$_GET["grouping"].'&ordering=DESC'; ?>">Descending [Z-a] [9-1]</a>
            </div>
        </div>

        <p class="tooltips">Click on a record to edit. Press enter to save the new value.</p>

        <?php
        // Display records based on grouping and ordering
        $grouping = $_GET["grouping"];
        $ordering = $_GET["ordering"];

        echo '<div class="list col">';

        switch ($grouping) {
            case "assignment":
                if ($ordering === "ASC") {
                    echo '<h3>LOANER</h3>';
                    foreach ($records["LOANER"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>STAFF</h3>';
                    foreach ($records["STAFF"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>STUDENT</h3>';
                    foreach ($records["STUDENT"] as $r) {
                        displayRecord($r);
                    }
                } else if ($ordering === "DESC") {
                    echo '<h3>STUDENT</h3>';
                    foreach ($records["STUDENT"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>STAFF</h3>';
                    foreach ($records["STAFF"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>LOANER</h3>';
                    foreach ($records["LOANER"] as $r) {
                        displayRecord($r);
                    }
                }
                
                break;
            case "building":
                if ($ordering === "ASC") {
                    echo '<h3>FES</h3>';
                    foreach ($records["FES"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>RBMS</h3>';
                    foreach ($records["RBMS"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>FHS</h3>';
                    foreach ($records["FHS"] as $r) {
                        displayRecord($r);
                    }
                } else if ($ordering === "DESC") {
                    echo '<h3>FHS</h3>';
                    foreach ($records["FHS"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>RBMS</h3>';
                    foreach ($records["RBMS"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>FES</h3>';
                    foreach ($records["FES"] as $r) {
                        displayRecord($r);
                    }
                }
                break;
            case "model":
                if ($ordering === "ASC") {
                    echo '<h3>HPG320</h3>';
                    foreach ($records["HPG320"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HPG720</h3>';
                    foreach ($records["HPG720"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HP19</h3>';
                    foreach ($records["HP19"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HPT19</h3>';
                    foreach ($records["HPT19"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>G8</h3>';
                    foreach ($records["G8"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HPTG9</h3>';
                    foreach ($records["HPTG9"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>23HPTG9</h3>';
                    foreach ($records["23HPTG9"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>G9</h3>';
                    foreach ($records["G9"] as $r) {
                        displayRecord($r);
                    }
                } else if ($ordering === "DESC") {
                    echo '<h3>G9</h3>';
                    foreach ($records["G9"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>23HPTG9</h3>';
                    foreach ($records["23HPTG9"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HPTG9</h3>';
                    foreach ($records["HPTG9"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>G8</h3>';
                    foreach ($records["G8"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HPT19</h3>';
                    foreach ($records["HPT19"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HP19</h3>';
                    foreach ($records["HP19"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HPG720</h3>';
                    foreach ($records["HPG720"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>HPG320</h3>';
                    foreach ($records["HPG320"] as $r) {
                        displayRecord($r);
                    }
                }
                break;
            case "serial": // Pseudo grouping for "DEPROVISIONED"
                echo '<h3>DEPROVISIONED DEVICES</h3>';
                foreach ($records["DEPROVISIONED"] as $r) {
                    displayRecord($r);
                }
                break;
            default:
                break;
        }

        echo '</div>';

        // Helper functions
        function displayRecord($r) {
            echo
            '
            <div class="record col" id="record-'.$r["serial"].'">
                <div class="row-one row">
                    <input id="serial" type="text" value="'.$r["serial"].'" placeholder="Serial #"/>
                    <input id="asset" type="text" value="'.$r["asset"].'" placeholder="Asset Tag"/>
                    <input id="PO" type="text" value="'.$r["PO"].'" placeholder="PO #"/>
                    <input id="model" type="text" value="'.$r["model"].'" placeholder="Model"/>
                    <input id="building" type="text" value="'.$r["building"].'" placeholder="Building"/>
                    <input id="assignment" type="text" value="'.$r["assignment"].'" placeholder="Assignment"/>
                    <input id="person" type="text" value="'.$r["person"].'" placeholder="Person/Student"/>
                </div>
                <div class="row-two row">
                    <label>Serial #</label>
                    <label>Asset Tag</label>
                    <label>PO #</label>
                    <label>Model</label>
                    <label>Building</label>
                    <label>Assignment</label>
                    <label>Person/Student</label>
                </div>
                <div class="row-four row record-controls">
                    <div class="button bg-red" onclick="markAs(\'deprovisioned\', \''.$r["serial"].'\');">Deprovision Device</div>
                    <div class="button bg-yellow disabled" onclick="resetRecord(\''.$r["serial"].'\');" id="reset-form-trigger">Reset Form</div>
                    <div class="button bg-blue" onclick="submitChanges(\''.$r["serial"].'\');">Submit Changes</div>
                    <div class="button bg-normal" onclick="collapseRecord(this);">Collapse</div>
                </div>
            </div>
            ';
        }
        ?>
    </div>

    <script type="text/javascript" src="/src/scripts/devices.js"></script>
    <script type="text/javascript" src="/src/scripts/confirmation.js"></script>
    <script type="text/javascript" src="/src/scripts/modals.js"></script>
 
</body>
</html>