function modal(type, text="") {
    // Detect type of modal
    let modal_type = "";
    switch (type) {
        case "success":
            modal_type = "success-modal";
            break;
        case "error":
            modal_type = "error-modal";
            break;
        default:
            modal_type = "success-modal";
            break;
    }

    // Create the DOM element for the modal
    const modal = $("<div>", {class: `msg-modal ${modal_type}`});
    const info = $("<p>", {class: "modal-info", text: text});
    modal.append(info);

    // Append to body, then jease in
    $("body").append(modal);
    modal.css({
        opacity: 0,
        transition: "opacity 200ms ease-in"
    });

    setTimeout(() => {
        modal.css({opacity: 1});
    }, 10);

    setTimeout(() => {
        modal.css({
            transition: "opacity 4s ease-out",
            opacity: 0,
        });


        setTimeout(() => modal.remove(), 4000)
    }, 10000);
}