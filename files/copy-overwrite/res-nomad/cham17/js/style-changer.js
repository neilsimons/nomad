jQuery(document).ready(function ($) {
    var waitingForPageRefresh = false;
    var styleCookieName = style_cookie_name || 'style';
    var styles = site_styles || ['light'];
    $('.style-toggle-btn').attr('href','');
    $('.style-toggle-btn').click(function () {
        if (waitingForPageRefresh)
            return;
        var currentStyle = readCookie(styleCookieName) || 'light';
        var newStyle = styles[(styles.indexOf(currentStyle)+1) % styles.length];
        createCookie(styleCookieName, newStyle, 365);
        if (readCookie(styleCookieName) != newStyle) {
            alert("Cookies must be enabled to change page style");
            return false;
        }
         //alert('The page will refresh changing style '+currentStyle+' to '+newStyle);
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
