<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chromebook Checkout</title>
    <link rel="stylesheet" href="/stylesheets/compiled/main.css">
    <script src="/src/scripts/jquery/jquery-3.7.1.js"></script>
</head>
<body id="main">

    <?php
    // Init all records
    $records = [
        "8g" => [],
        "7g" => [],
        "6g" => [],
        "FES" => [],
        "RBMS" => [],
        "FHS" => [],
    ];

    // Fetch all records, then organize
    require($_SERVER["DOCUMENT_ROOT"]."/php/fetch_checkouts.php");

    // GET variables
    if (!isset($_GET["grouping"])) {
        $_GET["grouping"] = "grade";
    }
    if (!isset($_GET["ordering"])) {
        $_GET["ordering"] = "ASC";
    }
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
        <h1 class="grouped-by">Grouped By: Grade Level, ASC</h1>
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
            echo
            '
            <div class="record col">
                <div class="row-one row">
                    <input type="text" value="'.$r["rid"].'" placeholder="Record ID"/>
                    <input type="text" value="'.$r["sid"].'" placeholder="Student ID"/>
                    <input type="text" value="'.$r["assignedCB"].'" placeholder="Assigned Chromebook Number"/>
                    <input type="text" value="'.$r["loanerCB"].'" placeholder="Loaner Chromebook number"/>
                    <input type="text" value="'.$r["school"].'" placeholder="School"/>
                    <input type="text" value="'.$r["grade"].'" placeholder="Grade Level"/>
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
                    <textarea class="issue-textbox" type="text" maxlength="1200" placeholder="Brief Issue Here">'.$r["issue"].'</textarea>
                </div>
                <div class="row-four row record-controls">
                    <div class="button bg-orange" onclick="markAs(\'started\', '.$r["rid"].');">Mark Started</div>
                    <div class="button bg-green" onclick="markAs(\'finished\', '.$r["rid"].');">Mark Finished</div>
                    <div class="button bg-red" onclick="markAs(\'deleted\', '.$r["rid"].');">Delete Record</div>
                    <div class="button bg-yellow disabled" onclick="resetRecord('.$r["rid"].');">Reset Record</div>
                    <div class="button bg-normal" onclick="collapseRecord(this);">Collapse</div>
                </div>
            </div>
            ';
        }
        ?>
    </div>

    <script type="text/javascript" src="/src/scripts/checkouts.js"></script>

    <div class="loaners">
        testing loaners
    </div>
 
</body>
</html>