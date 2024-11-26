// Hide admin mode by default
$("#admin-page").hide();

// Functions
function adminpage() {
    const admin_pw = "123456";
    pw = prompt("Please enter an admin password.");

    // If password was incorrect
    if (pw !== null && pw !== admin_pw) {
        alert("You've entered an incorrect password. Please try again or cancel.");
        return adminpage();
    }

    // If password was cancelled
    if (pw === null) {
        return;
    }

    // If signing out
    let admin_btn = $("#admin-button");
    if (admin_btn.attr("data-admin-open") === "true") {
        // Set admin-open to false
        admin_btn.attr("data-admin-open", "false");

        // Re-display the last items & hide admin menu
        $("#admin-page").hide();
        $(`[data-step=${step}]`).show();

        // Change admin button to sign out
        admin_btn.text("Admin Sign-In");

        return;
    }

    // Set admin-open to true
    admin_btn.attr("data-admin-open", "true");

    // If all else is good, show admin page & hide all steps
    $("#admin-page").show();
    $(`[data-step=${step}]`).hide();

    // Change admin button to sign out
    admin_btn.text("Admin Sign-Out");
}
function checkouts() {
    $(".options-list, .loaners").hide();
    $(".checkouts").show();
}
function loaners() {
    $(".options-list, .checkouts").hide();
    $(".loaners").show();
}
function show_records(type) {
    // Set defaults
    let groupby = "grade";
    let orderby = "ASC";

    // Perform AJAX request
    $.ajax({  
        type: "POST",  
        url: "", 
        data: {
            recordtype: type,
            groupby: groupby,
            orderby: orderby,
        },
        success: function (response) {
            process_response(response);
        }
    });
}
function process_response(response) {
    
}