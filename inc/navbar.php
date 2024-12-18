<?php require $_SERVER["DOCUMENT_ROOT"]."/php/DotEnv.php"; ?>

<div class="navbar">

    <div class="section">
        <span class="subsection">
            <span class="subsection-button" id="nav-home-trigger" onclick="window.location.assign('/');">
                <div class="subsection-button-image-container" id="nav-home-trigger-image-container"><img id="nav-home-icon" src="/assets/techteamlogo.png"/></div>
            </span>
        </span>

        <div class="subsection _manager" id="nav-dropdown-main-container">
            <span class="subsection-button" id="admin-dropdown-trigger">
                <div class="subsection-button-image-container" id="nav-menu-trigger-image-container"><img id="nav-menu-icon" src="/assets/icons/menu_3f3fda.png"/></div>
                <a class="nav-button" id="nav-menu-button">Riverbend Tech Team</a>
            </span>
            <div id="admin-dropdown">
                <div class="dropdown-section">

                    <div class="section-title-container _manager">
                        <div class="dropdown-section-title-icon"><img src="/assets/icons/list_dark.png"/></div>
                        <p class="section-title">Lists</p>
                    </div>

                    <div class="section-link-container _admin">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/boy_dark.png"/></div>
                        <a class="section-link" href="/0/lists/students">Students</a>
                    </div>

                    <div class="section-link-container _admin">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/chromebook_dark.png"/></div>
                        <a class="section-link" href="/0/lists/devices">Devices</a>
                    </div>

                    <div class="section-link-container _manager">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/repair_dark.png"/></div>
                        <a class="section-link" href="/0/lists/device-issues">Device Issues</a>
                    </div>

                </div>
                <div class="dropdown-section">

                    <div class="section-title-container _manager">
                        <div class="dropdown-section-title-icon"><img src="/assets/icons/add_circle_solid_dark.png"/></div>
                        <p class="section-title">New</p>
                    </div>

                    <div class="section-link-container _admin">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/boy_dark.png"/></div>
                        <a class="section-link" href="/0/new/student">Students</a>
                    </div>

                    <div class="section-link-container _admin">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/chromebook_dark.png"/></div>
                        <a class="section-link" href="/0/new/device">Devices</a>
                    </div>

                    <div class="section-link-container _manager">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/repairs_dark.png"/></div>
                        <a class="section-link" href="/0/new/checkout">Checkout</a>
                    </div>

                    <div class="section-link-container _admin">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/admins_dark.png"/></div>
                        <a class="section-link" href="/0/new/administrator">Admins</a>
                    </div>

                    <div class="section-link-container _admin">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/vip_dark.png"/></div>
                        <a class="section-link" href="/0/new/manager">Managers</a>
                    </div>

                    <div class="section-link-container _admin">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/allowlist_user_dark.png"/></div>
                        <a class="section-link" href="/0/new/allowlist_user">Allowlist User</a>
                    </div>

                    <div class="section-link-container _manager">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/email_groups_dark.png"/></div>
                        <a class="section-link" href="/0/create/email_groups">Email Groups</a>
                    </div>

                </div>
                <div class="dropdown-section">

                    <div class="section-title-container _manager">
                        <div class="dropdown-section-title-icon"><img src="/assets/icons/export_dark.png"/></div>
                        <p class="section-title">Export</p>
                    </div>

                    <div class="section-link-container _manager">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/boy_dark.png"/></div>
                        <a class="section-link" href="/0/export/students">Students</a>
                    </div>

                    <div class="section-link-container _manager">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/chromebook_dark.png"/></div>
                        <a class="section-link" href="/0/export/devices">Devices</a>
                    </div>

                    <div class="section-link-container _manager">
                        <div class="dropdown-section-link-icon"><img src="/assets/icons/users_dark.png"/></div>
                        <a class="section-link" href="/0/export/users">Users</a>
                    </div>

                </div>
            </div>
        </div>
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
$("#nav-dropdown-main-container").on("click", function () {
    if (!adminDropdownToggled) {
        $("#admin-dropdown").css("display", "flex");
        adminDropdownToggled = true;
    } else {
        $("#admin-dropdown").hide();
        adminDropdownToggled = false;
    }
});

// Nav button icons
$("#admin-dropdown-trigger").hover(
    // On hover
    function () {
        $("#nav-menu-icon").attr("src", "/assets/icons/menu.png");
    },
    // On not-hover
    function () {
        $("#nav-menu-icon").attr("src", "/assets/icons/menu_3f3fda.png");
    }
);

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