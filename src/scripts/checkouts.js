

// Listeners
$(".row-one input").on("click", function() {
    let recordElement = $(this).closest(".row-one").parent();
    recordElement.find(".row-two, .row-three, .row-four").css("display", "flex");
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