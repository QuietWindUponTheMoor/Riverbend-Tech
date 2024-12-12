<?php require $_SERVER["DOCUMENT_ROOT"]."/php/DotEnv.php"; ?>

<div class="navbar">

    <div class="section">
        <span class="subsection">
            <a class="nav-title" href="/">Riverbend Tech Team</a>
        </span>
        <span class="subsection" id="admin-dropdown-trigger">
            <a class="nav-button">Management Menu</a>
            <div id="admin-dropdown">
                <div class="dropdown-section">
                    <p class="section-title">Add New</p>
                    <a class="section-link" href="/new/checkout.php">Checkout</a>
                    <a class="section-link _manager" href="/new/device.php">Devices</a>
                    <a class="section-link _admin" href="/new/student.php">Students</a>
                    <a class="section-link _admin" href="/new/administrator.php">Admins</a>
                    <a class="section-link _admin" href="/new/manager.php">Managers</a>
                    <a class="section-link _admin" href="/new/allowlist_user.php">Allowlist User</a>
                </div>
                <div class="dropdown-section">
                    <p class="section-title">Export</p>
                    <a class="section-link _manager" href="">Students</a>
                </div>
            </div>
        </span>
        <span class="subsection">
            <a class="nav-button" href="/devices.php">Devices</a>
            <a class="nav-button" href="/device-issues.php">Chromebook Checkouts</a>
        </span>
    </div>

    <span class="section account-section">
        <span class="subsection">
            <span class="nav-user">
                <div class="image-container" id="user-image-container"><img src=""/></div>
                <p class="name" id="user-fullname"></p>
            </span>
            <a class="nav-button-secondary" id="signout-button" onclick="signout();">Sign Out</a>
        </span>
    </span>

</div>

<script src="https://accounts.google.com/gsi/client" async defer></script>
<script type="text/javascript">
// Navbar
let adminDropdownToggled = false;
$("#admin-dropdown-trigger").on("click", function () {
    if (!adminDropdownToggled) {
        $("#admin-dropdown").css("display", "flex");
        adminDropdownToggled = true;
    } else {
        $("#admin-dropdown").hide();
        adminDropdownToggled = false;
    }
});

// User
let user = null;
let rank = null;
// Check if user is logged in
if (isCookieValid("user")) {
    $("#g_id_onload, .g_id_signin").remove();

    // Get user
    user = JSON.parse((getCookie("user")));
    $("#user-fullname").text(user.full_name);
    let d = new Date();
    $("#user-image-container").find("img").attr("src", `${user.image}?${d.getTime()}`);
    rank = user.perm_level;
} else {
    $(".nav-user, #signout-button").hide();
    $(".account-section").find(".subsection").append(`
        <div id="g_id_onload"
            data-client_id="<?php echo $_ENV["gClientID"]; ?>"
            data-context="signin"
            data-ux_mode="popup"
            data-login_uri="http://localhost:8081/php/account/auth.php"
            data-callback="handleCredentialResponse">
        </div>
        <div class="g_id_signin" data-type="standard"></div>
    `);
}

function handleCredentialResponse(response) {
    $.ajax({  
        type: "POST",  
        url: "/php/account/auth.php", 
        data: JSON.stringify({ id_token: response.credential }),
        contentType: "application/json",
        success: function (data) {
            if (data.success) {
                console.log("User authenticated");
                // Get data
                let u = data.user;
                let id = u.id;
                let email = u.email;
                let image = u.picture;
                let first = u.first;
                let last = u.last;
                let fullName = u.name;
                let permLevel = u.perm_level;

                // Get cookies
                const date = new Date();
                date.setTime(date.getTime() + (24 * 60 * 60 * 1000)); // Convert to milliseconds

                // Create user object
                let user = {
                    id: id,
                    email: email,
                    perm_level: permLevel,
                    image: image,
                    first: first,
                    last: last,
                    full_name: fullName
                };

                let userObjString = JSON.stringify(user);
                let encodedUser = encodeURIComponent(userObjString);

                // Set cookie
                document.cookie = `user=${encodedUser};expires=${date.toUTCString()};path=/`;
                window.location.reload()

            } else {
                console.error("Authentication failed:", data.error);
                console.error("Additional info on fail:", data);
            }
        },
        error: function(xhr, status, error) {
            console.error("Request failed:", status, error, xhr);
        }
    });
}
function signout() {
    let confirmation = confirm("Are you sure you want to sign out?");
    if (confirmation) {
        document.cookie = `user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
        window.location.reload()
    }
}
function getCookie(name) {
    let cookies = document.cookie.split('; ');
    for (let i = 0; i < cookies.length; i++) {
        const [cookieName, cookieValue] = cookies[i].split('=');
        if (cookieName === name) {
            return decodeURIComponent(cookieValue);
        }
    }
    return null;
}
function isCookieValid(cookieName) {
    let cookies = document.cookie.split("; ");
    for (let i = 0; i < cookies.length; i++) {
        const [name, value] = cookies[i].split('=');
        if (name === cookieName) {
            return true; // Exists & is valid
        }
    }
    return false; // Doesn't exist or is invalid
}
</script>