<?php require $_SERVER["DOCUMENT_ROOT"]."/php/DotEnv.php"; ?>

<div class="navbar">

    <div class="section">
        <span class="subsection">
            <a class="nav-title" href="/">Riverbend Tech Team</a>
        </span>
        <span class="subsection">
            <a class="nav-button" href="/devices.php">Devices</a>
            <a class="nav-button" href="/device-issues.php">Device Issues</a>
        </span>
        <span class="subsection">
            <a class="nav-button-secondary" href="/new/device.php">Add Devices</a>
            <a class="nav-button-secondary" href="/new/student.php">Add Students</a>
        </span>
    </div>

    <span class="section account-section">
        <span class="subsection">
            <a class="nav-button-secondary" href="/account/register.php">Register</a>
            <a class="nav-button-secondary" href="/account/signin.php">Sign In</a>
            <div id="g_id_onload"
                data-client_id="<?php echo $_ENV["gClientID"]; ?>"
                data-context="signin"
                data-ux_mode="popup"
                data-login_uri="http://localhost:8081/php/account/auth.php"
                data-callback="handleCredentialResponse">
            </div>
            <div class="g_id_signin" data-type="standard"></div>
        </span>
    </span>

</div>

<script src="https://accounts.google.com/gsi/client" async defer></script>
<script type="text/javascript">
function handleCredentialResponse(response) {
    $.ajax({  
        type: "POST",  
        url: "/php/account/auth.php", 
        data: JSON.stringify({ id_token: response.credential }),
        contentType: "application/json",
        success: function (data) {
            if (data.success) {
                console.log("User authenticated:", data.user);
            } else {
                console.error("Authentication failed.");
            }
        },
        error: function(xhr, status, error) {
            console.error("Request failed:", status, error);
        }
    });
}
</script>