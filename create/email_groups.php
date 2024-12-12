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

    <div class="email-groups-form">
        <h3>Create Email Groups</h3>

        <div class="form">


            <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

            <div class="available-students drop-zone">
                <div class="student" id="sid-1">
                    <div class="info">
                        <p class="name">John Doe</p>
                        <p class="sid">2030118</p>
                    </div>
                    <div class="img-container"><img src="/assets/icons/add.png"/></div>
                </div>
            </div>

            <div class="new-group drop-zone">

            </div>


        </div>
    </div>

    <script type="text/javascript" src="/src/scripts/confirmation.js"></script>
    <script type="text/javascript" src="/src/scripts/modals.js"></script>
    <script type="text/javascript" src="/src/scripts/create/email_groups.js"></script>
 
</body>
</html>