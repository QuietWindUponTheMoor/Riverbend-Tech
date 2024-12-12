$(function () {
    // Make the element draggable
    $(".student").draggable({
        revert: "invalid", // Return to original position if not dropped
        start: function () {
            // Add a visual effect to the droppable container
            $(".drop-zone").not(this).addClass("student-drop-hover");
        },
        stop: function () {
            // Remove the visual effect
            $(".drop-zone").removeClass("student-drop-hover");
        }
    });

    // Make the containers droppable
    $(".drop-zone").droppable({
        accept: ".student", // Only accept elements with class 'draggable'
        drop: function (event, ui) {
            // Append the draggable element to the new container
            $(this).append(ui.draggable.css({ top: "auto", left: "auto" }));
        }
    });
});