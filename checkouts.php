<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chromebook Checkout</title>
    <link rel="stylesheet" href="/stylesheets/compiled/main.css">
    <script type="text/javascript" src="/src/scripts/jquery/jquery-3.7.1.js"></script>
</head>
<body id="main">

    <?php
    $ordering = "ASC";
    $grouping = "grade";
    // GET variables
    if (!isset($_GET["grouping"])) {
        $_GET["grouping"] = "grade";
        $grouping = "grade";
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
        "8g" => [],
        "7g" => [],
        "6g" => [],
        "FES" => [],
        "RBMS" => [],
        "FHS" => [],
    ];

    // Fetch all records, then organize
    require($_SERVER["DOCUMENT_ROOT"]."/php/fetch_checkouts.php");

    // Set JavaScript object
    $jsJSON = json_encode($records, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    echo
    '
    <script type="text/javascript">
    let records = '.$jsJSON.';

    </script>
    ';
    ?>

    <div class="checkouts">
        <div class="filters col">
            <h3>Group By</h3>
            <div class="section row">
                <a class="filter-button button bg-orange <?php if ($_GET["grouping"] === "grade") {echo "selected";} ?>" href="/checkouts.php<?php echo '?grouping=grade&ordering='.$_GET["ordering"]; ?>">Grade</a>
                <a class="filter-button button bg-orange <?php if ($_GET["grouping"] === "school") {echo "selected";} ?>" href="/checkouts.php<?php echo '?grouping=school&ordering='.$_GET["ordering"]; ?>">School</a>
            </div>
            <h3>Order By</h3>
            <div class="section row">
                <a class="filter-button button bg-green <?php if ($_GET["ordering"] === "ASC") {echo "selected";} ?>" href="/checkouts.php<?php echo '?grouping='.$_GET["grouping"].'&ordering=ASC'; ?>">Ascending [a-Z] [1-9]</a>
                <a class="filter-button button bg-green <?php if ($_GET["ordering"] === "DESC") {echo "selected";} ?>" href="/checkouts.php<?php echo '?grouping='.$_GET["grouping"].'&ordering=DESC'; ?>">Descending [Z-a] [9-1]</a>
            </div>
        </div>
        <p class="tooltips">Click on a record to edit. Press enter to save the new value.</p>

        <?php
        // Display records based on grouping and ordering
        $grouping = $_GET["grouping"];
        $ordering = $_GET["ordering"];

        echo '<div class="list col">';

        switch ($grouping) {
            case "grade":
                if ($ordering === "ASC") {
                    echo '<h3>6th Grade</h3>';
                    foreach ($records["6g"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>7th Grade</h3>';
                    foreach ($records["7g"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>8th Grade</h3>';
                    foreach ($records["8g"] as $r) {
                        displayRecord($r);
                    }
                } else if ($ordering === "DESC") {
                    echo '<h3>8th Grade</h3>';
                    foreach ($records["8g"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>7th Grade</h3>';
                    foreach ($records["7g"] as $r) {
                        displayRecord($r);
                    }
                    echo '<h3>6th Grade</h3>';
                    foreach ($records["6g"] as $r) {
                        displayRecord($r);
                    }
                }
                
                break;
            case "school":
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
                }
                break;
            default:
                # code...
                break;
        }

        echo '</div>';

        // Helper functions
        function displayRecord($r) {
            $color = "";
            if ($r["softDeleted"] == 1) {
                $color = "deleted-record";
            }
            
            if ($r["started"] == 1) {
                $color = "started-record";
            }
            
            if ($r["finished"] == 1) {
                $color = "finished-record";
            }

            echo
            '
            <div class="record '.$color.' col" id="record-'.$r["rid"].'">
                <div class="row-one row">
                    <p class="recordID-input" id="rid">'.$r["rid"].'</p>
                    <input id="sid" type="text" value="'.$r["sid"].'" placeholder="Student ID"/>
                    <input id="assignedCB" type="text" value="'.$r["assignedCB"].'" placeholder="Assigned Chromebook Number"/>
                    <input id="loanerCB" type="text" value="'.$r["loanerCB"].'" placeholder="Loaner Chromebook number"/>
                    <input id="school" type="text" value="'.$r["school"].'" placeholder="School"/>
                    <input id="grade" type="text" value="'.$r["grade"].'" placeholder="Grade Level"/>
                </div>
                <div class="row-two row">
                    <label>Record ID</label>
                    <label>Student ID</label>
                    <label>Assigned Asset</label>
                    <label>Loaner Asset</label>
                    <label>School/Building</label>
                    <label>Grade</label>
                </div>
                <div class="row-three row">
                    <textarea id="issue" class="issue-textbox" type="text" maxlength="1200" placeholder="Brief Issue Here">'.$r["issue"].'</textarea>
                </div>
                <div class="row-four row record-controls">
                    <div class="button bg-orange" onclick="markAs(\'started\', '.$r["rid"].');">Mark Started</div>
                    <div class="button bg-green" onclick="markAs(\'finished\', '.$r["rid"].');">Mark Finished</div>
                    <div class="button bg-red" onclick="markAs(\'deleted\', '.$r["rid"].');">Delete Record</div>
                    <div class="button bg-yellow disabled" onclick="resetRecord('.$r["rid"].');" id="reset-form-trigger">Reset Form</div>
                    <div class="button bg-blue" onclick="submitChanges('.$r["rid"].');">Submit Changes</div>
                    <div class="button bg-normal" onclick="collapseRecord(this);">Collapse</div>
                </div>
            </div>
            ';
        }
        ?>
    </div>

    <script type="text/javascript" src="/src/scripts/checkouts.js"></script>
    <script type="text/javascript" src="/src/scripts/confirmation.js"></script>
    <script type="text/javascript" src="/src/scripts/modals.js"></script>

    <div class="loaners">
        testing loaners
    </div>
 
</body>
</html>