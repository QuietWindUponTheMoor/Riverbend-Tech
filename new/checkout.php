<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit New Checkout or Device Issue</title>
    <link rel="stylesheet" href="/stylesheets/compiled/main.css">
    <script type="text/javascript" src="/src/scripts/jquery/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="/src/scripts/navbar.js" defer></script>
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
</head>
<body id="main">
    <?php require($_SERVER["DOCUMENT_ROOT"]."/inc/navbar.php"); ?>

    <div class="new-device-form">
        <h3>Submit New Checkout or Device Issue</h3>

        <div class="submissions">
            <!-- Label section -->
            <span class="record-section" id="label-section">
                <p class="record-label item-number">Record #</p>
                <p class="record-label sid">Student ID</p>
                <p class="record-label asset">Loaner Asset #</p>
                <p class="record-label issue">Brief Issue</p>
            </span>

            <!-- Default record -->
            <span class="record-section" id="record-1">
                <p class="record-label item-number">1</p>
                <input class="record-input sid" placeholder="2030***"/>
                <input class="record-input asset" placeholder="01HP21"/>
                <input class="record-input issue" placeholder="Display is cracked"/>
            </span>
        </div>

        <div class="form-controls">
            <a class="button bg-green" id="submit-records">Submit Records</a>
            <a class="button bg-yellow" id="reset-form">Reset Form</a>
            <a class="button bg-blue" id="new-record">Add New Record</a>
        </div>
        
    </div>

    <script type="text/javascript" src="/src/scripts/confirmation.js"></script>
    <script type="text/javascript" src="/src/scripts/modals.js"></script>
    <script type="text/javascript" src="/src/scripts/forms/new_checkout.js"></script>
 
</body>
</html>