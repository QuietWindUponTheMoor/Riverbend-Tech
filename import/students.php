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

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.1/papaparse.min.js"></script>

    <div class="import-export-form">
        <h3>Import Students</h3>

        <input type="file" id="import-csv" accept=".csv", multiple/>

        <div class="progress-container">
            <label for="upload-progress" id="progress-percent">-</label>
            <progress id="upload-progress" value="0" max="100"></progress>
            <label for="upload-progress" id="progress-filename">-</label>
            <p id="upload-result">Success!</p>
        </div>
        
    </div>

    <script type="text/javascript" src="/src/scripts/confirmation.js"></script>
    <script type="text/javascript" src="/src/scripts/modals.js"></script>
    <script type="text/javascript" src="/src/scripts/import/student.js"></script>
 
</body>
</html>