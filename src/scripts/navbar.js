$navbar = $(".navbar");
$body = $("body");

bodyPadding();
$(window).on("resize", () => {
    bodyPadding();
});

function bodyPadding() {
    // Control padding-top of body
    let navHeight = $navbar.height();
    $body.css({
        paddingTop: (navHeight + 40) + "px",
    });
}

// Hide elements based on permission level
if (rank === 0 || rank === null) {
    $("._manager, ._admin").remove();
}
if (rank === 1) {
    $("._admin").remove();
}