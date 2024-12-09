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