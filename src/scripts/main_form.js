// Reset
reset();

let $searchedStudents = $("#searched-students");

// Listeners
$("#student").on("keyup", function () {
    // Get the value
    let studentID = $(this).val();

    // Search for student
    if (studentID == "") {
        $searchedStudents.empty();
    }
    if (studentID !== "" || parseInt(studentID)) {
        $.ajax({  
            type: "POST",  
            url: "/php/forms/search_student_by_id.php", 
            data: {
                sid: studentID,
            },
            success: function (response) {
                if (response && response.trim() !== "") {
                    // Clear any current records from #searched-students
                    $searchedStudents.empty();

                    // Append responses
                    let students = JSON.parse(response);
                    students.forEach(student => {
                        $searchedStudents.append(
                            `
                            <div class="student">
                                <p class="name">${student.last}, ${student.first}</p>
                                <p class="sid">${student.sid}</p>
                                <p class="email">${student.email}</p>
                            </div>
                            `
                        );
                    });
                }
            }
        });
    }
});
$(document).on("click",".student", function () {
    // Get SID
    let sid = $(this).find(".sid").text();
    
    // Fill value
    $(this)
        .parent(".searched-students")
        .parent()
        .children(".cb-input")
        .val(sid);

    // Reset search
    $searchedStudents.empty();
});

// Functions
function showEx() {
    $(".asset-tag-examples").fadeIn();
}
function hideEx() {
    $(".asset-tag-examples").fadeOut();
}
function next() {
    // Hide current step
    $(`[data-step=${step}]`).fadeOut(() => {
        // Increment
        step = step + 1;

        // Show next step
        $(`[data-step=${step}]`).fadeIn();

        // Handle progression buttons
        progression_buttons();

        // Handle grades
        if (step === 2) {
            handle_grades();
        } else if (step === 7) {
            $("#back").hide();
        }
    });
}
function back() {
    // Hide current step
    $(`[data-step=${step}]`).fadeOut(() => {
        // Decrement
        step = step - 1;

        // Show prev step
        $(`[data-step=${step}]`).fadeIn();

        // Handle progression buttons
        progression_buttons();

        // Handle grades
        if (step === 2) {
            handle_grades();
        } else if (step === 7) {
            $("#back").hide();
        }
    });
}
function handle_grades() {
    switch (settings.school) {
        case "FES":
            $(".fhs").hide();
            $(".rbms").hide();
            $(".fes").show();
            break;
        case "RBMS":
            $(".fhs").hide();
            $(".fes").hide();
            $(".rbms").show();
            break;
        case "FHS":
            $(".fes").hide();
            $(".rbms").hide();
            $(".fhs").show();
            break;
        default:
            break;
    }
}
function checks(value) {
    // Fetch step
    let current_step = step;

    switch (current_step) {
        case 1:
            settings.school = value;
            next();
            break;
        case 2:
            settings.grade = value;
            if (value !== "" & value !== null) {
                next();
            } else {
                alert("Please enter a value!");
            }
            break;
        case 3:
            settings.cb_num = value;
            if (value !== "" & value !== null) {
                next();
            } else {
                alert("Please enter a value!");
            }
            break;
        case 4:
            settings.loaner_num = value;
            if (value !== "" & value !== null) {
                next();
            } else {
                alert("Please enter a value!");
            }
            break;
        case 5:
            settings.student = value;
            if (value !== "" & value !== null) {
                next();
            } else {
                alert("Please enter a value!");
            }
            break;
        case 6:
            settings.brief_issue = value;
            if (value !== "" & value !== null) {
                const selected_settings = settings;

                // Send data to the database
                $.ajax({  
                    type: "POST",  
                    url: "/php/forms/new_checkout.php", 
                    data: {
                        settings: JSON.stringify(selected_settings),
                    },
                    success: function (response) {
                        if (parseInt(response) === 1) {
                            // If successfully inserted data
                            next();

                            // Reset form after finishing
                            setTimeout(() => {reset();}, 6000);
                        } else {
                            console.error(response);
                        }
                    }
                });
            } else {
                alert("Please enter a value!");
            }
            break;
        case 7:
            
            break;
        default:
            break;
    }
}
function progression_buttons() {
    if (step === 1) {
        $("#back").hide();
    } else {
        $("#back").show();
    }
}
function reset() {
    // Reset settings
    settings = {
        school: null,
        grade: null,
        cb_num: null,
        loaner_num: null,
        student: null,
        brief_issue: null,
    };

    // Set back to step 1
    step = 1;

    // Hide next/back buttons
    $("#back").hide();
    $("#next").hide();

    // Get count of steps
    const steps = $("[data-step]").length;
    
    // Hide all except first step
    for (let i = 2; i <= steps; i++) {
        // Hide this step
        $(`[data-step=${i}]`).hide();
    }

    // Show initial step
    $("[data-step=1]").fadeIn();

    // Reset text boxes
    $(".cb-input").val("");
}