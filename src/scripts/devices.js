/*
 * recordID = serial number or "record-<ser#>"
 */

// Listeners
let isResettingForm = false;
$(".row-one input").on("click", function () {
    // Toggle visibility of record element details
    let recordElement = $(this).closest(".row-one").parent();
    recordElement.find(".row-two, .row-three, .row-four").css("display", "flex");
});
$(".row-one input, .row-three textarea").on("keydown", function () {
    // Toggle enabled/disabled of "Reset Form" button
    resetFormButtonVisibility(this);
});
$("#reset-form-trigger").on("mousedown", () => {
    // Set the flag immediately on mousedown
    isResettingForm = true;
});
$("#reset-form-trigger").on("click", () => {
    // Reset isResettingForm flag after the reset form process is complete
    // Allow the reset process to complete
    setTimeout(() => {
        isResettingForm = false;
    }, 0);
});
$(".row-one input, .row-three textarea").on("change", function () {
    // Cancel if reset form button is clicked
    if (isResettingForm) return;
    
    // Submit changes to record
    console.log("Form is not being reset, so submit changes.");
    let recordID = $(this)
        .closest(".row-one")
        .length
        ? $(this).closest(".row-one").parent().attr("id").replace("record-", "")
        : $(this).closest(".row-three").parent().attr("id").replace("record-", "");
    
    submitChanges(recordID);
});

// Functions
function collapseRecord(triggerCollapseButton) {
    let $btn = $(triggerCollapseButton);
    let recordElement = $btn.closest(".record-controls").parent();
    recordElement.find(".row-two, .row-three, .row-four").slideUp("fast");
}
function markAs(mark, recordID) {
    switch (mark) {
        case "deprovisioned":
            deprovisionRecord(recordID);
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
function getRecordDataBySerial(recordID) {
    return Object.values(records.all).find(obj => obj.serial === recordID);
}
function resetRecord(recordID) {
    // Get record elements
    let $recordElement = $(`#record-${recordID}`);
    let $serial = $recordElement.find("#serial");
    let $asset = $recordElement.find("#asset");
    let $PO = $recordElement.find("#PO");
    let $model = $recordElement.find("#model");
    let $building = $recordElement.find("#building");
    let $assignment = $recordElement.find("#assignment");
    let $person = $recordElement.find("#person");

    // Reset values
    let recordData = getRecordDataBySerial(recordID);
    let serial = $serial.val(recordData.serial);
    let asset = $asset.val(recordData.asset);
    let PO = $PO.val(recordData.PO);
    let model = $model.val(recordData.model);
    let building = $building.val(recordData.building);
    let assignment = $assignment.val(recordData.assignment);
    let person = $person.val(recordData.person);

    // Re-disable reset button
    $recordElement.find(".record-controls").find("#reset-form-trigger").addClass("disabled");
}
function submitChanges(recordID) {
    submitChangesTrigger(recordID);
}
function submitChangesTrigger(recordID) {
    // Get record elements
    let $recordElement = $(`#record-${recordID}`);
    let $serial = $recordElement.find("#serial");
    let $asset = $recordElement.find("#asset");
    let $PO = $recordElement.find("#PO");
    let $model = $recordElement.find("#model");
    let $building = $recordElement.find("#building");
    let $assignment = $recordElement.find("#assignment");
    let $person = $recordElement.find("#person");

    // Reset values
    let serial = $serial.val();
    let asset = $asset.val();
    let PO = $PO.val();
    if (PO == "") {
        PO = "NULL";
    }
    let model = $model.val();
    let building = $building.val();
    let assignment = $assignment.val();
    let person = $person.val();

    // Confirm
    confirmUpdate = confirmation("Are you sure you want to make changes to this record?");
    if (confirmUpdate === false) return;

    // Send to database for changes
    $.ajax({  
        type: "POST",  
        url: "/php/forms/new_device.php",
        data: {
            asset: asset,
            serial: serial,
            PurchaseOrder: PO,
            model: model,
            building: building,
            assignment: assignment,
            person: person,
        },
        success: function (response) {
            if (parseInt(response) === 1) {
                modal("success", `Successfully updated device ${serial}:${asset}!`);
            } else {
                console.error(response);
            }
        }
    });
}
function deprovisionRecord(recordID) {
    // Confirm
    confirmUpdate = confirmation("Are you sure you want to delete this record? This can only be undone by someone with access to the database.");
    if (confirmUpdate === false) return;

    // Soft delete
    $.ajax({  
        type: "POST",  
        url: "/php/soft_delete.php",
        data: {
            type: "devices", // Type can either be "checkouts" or "device" for soft deletion
            rid: recordID,
        },
        success: function (response) {
            if (parseInt(response) === 1) {
                modal("success", `Successfully deprovisioned device ${recordID}!`);

                $(`#record-${recordID}`).removeClass("started-record").removeClass("finished-record").addClass("deleted-record");
            } else {
                console.error(response);
            }
        }
    });
}