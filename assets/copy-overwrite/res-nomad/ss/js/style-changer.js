jQuery(document).ready(function ($) {
    var waitingForPageRefresh = false;
    var style = readCookie("styleDefCon");

    $('.style-toggle-btn').attr('href','');
    $('.style-toggle-btn').click(function () {
        var newStyle;
        var currentStyle;
        var styles;
        if (waitingForPageRefresh)
            return;
        currentStyle = readCookie("styleDefCon");
        styles = site_styles || ['light'];
        newStyle = styles[(currentStyle?(styles.indexOf(currentStyle) + 1):1) % styles.length];
        createCookie("styleDefCon", newStyle, 365);
        if (readCookie("styleDefCon") != newStyle) {
            alert("Cookies must be enabled to change page style");
            return false;
        }
        // alert('The page will refresh changing style '+currentStyle+' to '+newStyle);
    });

    function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

});
