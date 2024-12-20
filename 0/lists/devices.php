<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices List</title>
    <link rel="stylesheet" href="/stylesheets/compiled/main.css">
    <script type="text/javascript" src="/src/scripts/jquery/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="/src/scripts/navbar.js" defer></script>
    <link rel="shortcut icon" href="/assets/favicon_transparent.ico" type="image/x-icon">
</head>
<body id="main">
    <?php require($_SERVER["DOCUMENT_ROOT"]."/inc/navbar.php"); ?>

    <div class="list-container">
        <h3>Devices List</h3>

        <script type="text/javascript" src="/src/scripts/list/_recordConfig.js"></script>
        <script type="text/javascript">
        // Globals
        firstSort = true;

        // Configure file here
        let sortBy = "ASC";
        let groupBy = "serial";
        let hardFilterBy = null;
        let searchFilterBy = null;
        let recordType = recordConfig.devices;
        let headers = [ // Identifier must be exactly the same as table column name
            {identifier: "serial", value: "Serial #", placeholder: ""},
            {identifier: "asset", value: "Asset Tag", placeholder: ""},
            {identifier: "model", value: "Model #", placeholder: ""},
            {identifier: "PO", value: "Purchase Order", placeholder: ""},
            {identifier: "assignment", value: "Assignment", placeholder: ""},
            {identifier: "isLoanedOut", value: "Is Loaned Out", placeholder: ""}
        ];
        let selectFileURL = "/php/select/devices.php";
        let updateFileURL = "/php/update/devices.php";
        let firstColIsEditable = true;
        </script>

        <div class="sorting-and-grouping">
            <div class="section">
                <h4>Search:</h4>
                <div class="search-container">
                    <div class="img-container"><img src="/assets/icons/search.png"/></div>
                    <input class="search-input" id="list-search" placeholder=":column:<search>, or <search>"/>
                </div>
            </div>
        </div>

        <p class="tooltip">Click on a column header to sort either ascending or descending.</p>
        
        <div class="list" id="list"></div>

    </div>

    <script type="text/javascript" src="/src/scripts/confirmation.js"></script>
    <script type="text/javascript" src="/src/scripts/modals.js"></script>
    <script type="text/javascript" src="/src/scripts/list/list.js"></script>
 
</body>
</html>