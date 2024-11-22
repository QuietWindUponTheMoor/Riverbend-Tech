$(document).ready(function() {
    let clickCount = 0;
    const clickThreshold = 3;
    const admin_pw = "RBTT11**";

    function isFullscreen() {
        return !!(
            document.fullscreenElement ||
            document.mozFullScreenElement ||
            document.webkitFullscreenElement ||
            document.msFullscreenElement
        );
    }

    function requestFullscreen(previouslyAborted=false) {
        // Log actions
        console.log("Entering fullscreen");

        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) { // Firefox
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) { // Chrome, Safari and Opera
            document.documentElement.webkitRequestFullscreen();
        } else if (document.documentElement.msRequestFullscreen) { // IE/Edge
            document.documentElement.msRequestFullscreen();
        }
    }

    function exitFullscreen() {
        // Log actions
        console.log("Exiting fullscreen");

        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
        }
    }

    // Handle entering/exiting of fullscreen mode via clicks/taps
    $(":not(.options-list)").on("click", function() {
        // Log click
        console.log("Clicked!");

        clickCount++;
        if (clickCount >= clickThreshold) {
            if (isFullscreen()) {
                exitFullscreen();
            } else {
                requestFullscreen();
            }
            clickCount = 0; // Reset click count after toggling fullscreen
        }
    });
});