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

    <div class="list-container">
        <h3>Students List</h3>

        <div class="list" id="list">
            <!-- Label section -->
            <span class="record-section" id="label-section">
                <p class="record-label item-number">Student ID</p>
                <p class="record-label">Last Name</p>
                <p class="record-label">First Name</p>
                <p class="record-label">Grade</p>
                <p class="record-label">Homeroom</p>
                <p class="record-label">Email</p>
                <p class="record-label">Chromebook Asset</p>
                <p class="record-label">Loaner Chromebook</p>
            </span>

            
            <span class="record-section" id="record-1">
                <p class="record-label item-number sid">2030018</p>
                <input class="record-input last" placeholder="Doe"/>
                <input class="record-input first" placeholder="John"/>
                <input class="record-input grade" placeholder="5"/>
                <input class="record-input homeroom" placeholder="5C"/>
                <input class="record-input email" placeholder="johndoe@riverbendschools.net"/>
                <input class="record-input asset" placeholder="23HPTG9-0000000001-23"/>
                <input class="record-input loaner" placeholder="20HP21"/>
            </span>
        </div>

    </div>

    <script type="text/javascript" src="/src/scripts/confirmation.js"></script>
    <script type="text/javascript" src="/src/scripts/modals.js"></script>
    <script type="text/javascript" src="/src/scripts/list/list.js"></script>
 
</body>
</html>