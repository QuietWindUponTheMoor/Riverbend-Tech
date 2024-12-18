<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit New Students</title>
    <link rel="stylesheet" href="/stylesheets/compiled/main.css">
    <script type="text/javascript" src="/src/scripts/jquery/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="/src/scripts/navbar.js" defer></script>
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
</head>
<body id="main">
    <?php require($_SERVER["DOCUMENT_ROOT"]."/inc/navbar.php"); ?>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.1/papaparse.min.js"></script>

    <div class="new-device-form import-export-form">

        <h3>Bulk Upload Students</h3>

        <input type="file" id="import-csv" accept=".csv", multiple/>

        <div class="progress-container">
            <label for="upload-progress" id="progress-percent">-</label>
            <progress id="upload-progress" value="0" max="100"></progress>
            <label for="upload-progress" id="progress-filename">-</label>
            <p id="upload-result"></p>
        </div>

        <h3>Manually Submit New Students</h3>

        <div class="submissions">
            <!-- Label section -->
            <span class="record-section" id="label-section">
                <p class="record-label item-number">Record #</p>
                <p class="record-label sid">Student ID</p>
                <p class="record-label first">First</p>
                <p class="record-label last">Last</p>
                <p class="record-label grade">Grade</p>
                <p class="record-label homeroom">Homeroom</p>
                <p class="record-label email">Email</p>
                <p class="record-label asset">Chromebook Asset</p>
            </span>

            <!-- Default record -->
            <span class="record-section" id="record-1">
                <p class="record-label item-number">1</p>
                <input class="record-input sid" placeholder="2030***"/>
                <input class="record-input first" placeholder="John"/>
                <input class="record-input last" placeholder="Doe"/>
                <select class="record-input grade">
                    <option value="K" selected="selected">Kindergarten</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
                <input class="record-input homeroom" placeholder="KA, 7B, etc"/>
                <input class="record-input email" placeholder="johnd@riverbendschools.net"/>
                <input class="record-input asset" placeholder="01HP21"/>
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
    <script type="text/javascript" src="/src/scripts/forms/new_student.js"></script>
    <script type="text/javascript" src="/src/scripts/import/student.js"></script>
 
</body>
</html>