// Listeners
$(".row-one input").on("click", function () {
    // Toggle visibility of record element details
    let recordElement = $(this).closest(".row-one").parent();
    recordElement.find(".row-two, .row-three, .row-four").css("display", "flex");
});
$(".row-one input, .row-three textarea").on("keydown", function () {
    // Toggle enabled/disabled of "Reset Form" button
    resetFormButtonVisibility(this);
});
$(".row-one input, .row-three textarea").on("change", function () {
    // Submit changes to record
    let recordID = $(this)
        .closest(".row-one")
        .length
        ? $(this).closest(".row-one").parent().attr("id").replace("record-", "")
        : $(this).closest(".row-three").parent().attr("id").replace("record-", "");
    
    submitChangesTrigger(recordID);
});

// Functions
function collapseRecord(triggerCollapseButton) {
    let $btn = $(triggerCollapseButton);
    let recordElement = $btn.closest(".record-controls").parent();
    recordElement.find(".row-two, .row-three, .row-four").slideUp("fast");
}
function markAs(mark, recordID) {
    let confirmation;
    switch (mark) {
        case "started":
            confirmation = confirm("Are you sure you want to mark this record as started?");
            break;
        case "finished":
            confirmation = confirm("Are you sure you want to mark this record as finished?");
            break;
        case "deleted":
            confirmation = confirm("Are you sure you want to delete this record? This cannot be undone.");
            break;
        default:
            break;
    }
}
function resetFormButtonVisibility(recordElementThis) {
    let recordElement = $(recordElementThis)
        .closest(".row-one")
        .length
        ? $(recordElementThis).closest(".row-one").parent()
        : $(recordElementThis).closest(".row-three").parent();
    recordElement.find(".record-controls").find("#reset-form-trigger").removeClass("disabled");
}
function getRecordDataByID(recordID) {
    return Object.values(records.all).find(obj => obj.rid === recordID);
}
function resetRecord(recordID) {
    // Get record elements
    let $recordElement = $(`#record-${recordID}`);
    let $rid = $recordElement.find("#rid");
    let $sid = $recordElement.find("#sid");
    let $assignedCB = $recordElement.find("#assignedCB");
    let $loanerCB = $recordElement.find("#loanerCB");
    let $school = $recordElement.find("#school");
    let $grade = $recordElement.find("#grade");
    let $issue = $recordElement.find("#issue");

    // Reset values
    let recordData = getRecordDataByID(recordID);
    let rid = $rid.val(recordData.rid);
    let sid = $sid.val(recordData.sid);
    let assignedCB = $assignedCB.val(recordData.assignedCB);
    let loanerCB = $loanerCB.val(recordData.loanerCB);
    let school = $school.val(recordData.school);
    let grade = $grade.val(recordData.grade);
    let issue = $issue.val(recordData.issue);

    // Re-disable reset button
    $recordElement.find(".record-controls").find("#reset-form-trigger").addClass("disabled");
}
function submitChanges(recordID) {
    submitChangesTrigger(recordID);
}
function submitChangesTrigger(recordID) {
    // Get record elements
    let $recordElement = $(`#record-${recordID}`);
    let $rid = $recordElement.find("#rid");
    let $sid = $recordElement.find("#sid");
    let $assignedCB = $recordElement.find("#assignedCB");
    let $loanerCB = $recordElement.find("#loanerCB");
    let $school = $recordElement.find("#school");
    let $grade = $recordElement.find("#grade");
    let $issue = $recordElement.find("#issue");

    // Get values
    let rid = $rid.text();
    let sid = $sid.val();
    let assignedCB = $assignedCB.val();
    let loanerCB = $loanerCB.val();
    let school = $school.val();
    let grade = $grade.val();
    let issue = $issue.val();

    // Confirm
    confirmUpdate = confirmation("Are you sure you want to make changes to this record?");
    if (confirmUpdate === false) return;

    // Send to database for changes
    $.ajax({  
        type: "POST",  
        url: "/php/forms/new_checkout.php", 
        data: {
            rid: rid,
            sid: sid,
            assignedCB: assignedCB,
            loanerCB: loanerCB,
            school: school,
            grade: grade,
            issue: issue,
            started: null,
            finished: null,
            softDeleted: null,
        },
        success: function (response) {
            if (parseInt(response) === 1) {
                modal("success", `Successfully updated record #${rid}!`);
            } else {
                console.error(response);
            }
        }
    });
}