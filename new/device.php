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

    <div class="new-device-form">
        <h3>Submit A New Device</h3>

        <div class="submissions">
            <!-- Label section -->
            <span class="record-section" id="label-section">
                <p class="record-label item-number">Record #</p>
                <p class="record-label asset">Asset #</p>
                <p class="record-label serial">Serial #</p>
                <p class="record-label PO">PO #</p>
                <p class="record-label model">Model Type</p>
                <p class="record-label building">Building</p>
                <p class="record-label assignment">Assignment</p>
                <p class="record-label person">Person</p>
            </span>

            <!-- Default record -->
            <span class="record-section" id="record-1">
                <p class="record-label item-number">1</p>
                <input class="record-input asset" placeholder="01HP21"/>
                <input class="record-input serial" placeholder="5CD113****"/>
                <input class="record-input PO" placeholder="2304000***"/>
                <select class="record-input model">
                    <option value="HPG320">HPG320</option>
                    <option value="HPG720">HPG720</option>
                    <option value="HP19">HP19</option>
                    <option value="HPT19">HPT19</option>
                    <option value="G8" selected="selected">G8/HP21/HPT21</option>
                    <option value="HPTG9">HPTG9</option>
                    <option value="23HPTG9">23HPTG9</option>
                    <option value="G9">G9</option>
                </select>
                <select class="record-input building">
                    <option value="FES">FES</option>
                    <option value="RBMS" selected="selected">RBMS</option>
                    <option value="FHS">FHS</option>
                </select>
                <select class="record-input assignment">
                    <option value="STUDENT" selected="selected">Student Assigned</option>
                    <option value="LOANER">Loaner Device</option>
                    <option value="STAFF">Staff Device</option>
                    <option value="DEPROVISIONED">Deprovisioned/Donor/Dead</option> <!-- Results of deprovisioned is the same as donor -->
                </select>
                <input class="record-input person" placeholder="2030***"/>
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
    <script type="text/javascript" src="/src/scripts/forms/new_device.js"></script>
 
</body>
</html>