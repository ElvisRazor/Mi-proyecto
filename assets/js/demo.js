var themeOptionArr = {
    typography: '',
    version: '',
    layout: '',
    primary: '',
    headerBg: '',
    navheaderBg: '',
    sidebarBg: '',
    sidebarStyle: '',
    sidebarPosition: '',
    headerPosition: '',
    containerLayout: '',
    direction: '',
};

/* Cookies Function */
function setCookie(cname, cvalue, exhours) {
    var d = new Date();
    d.setTime(d.getTime() + (30 * 60 * 1000)); /* 30 Minutes */
    var expires = "expires=" + d.toString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function deleteCookie(cname) {
    var d = new Date();
    d.setTime(d.getTime() + (1)); // 1/1000 second
    var expires = "expires=" + d.toString();
    document.cookie = cname + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT" + ";path=/";
}

function deleteAllCookie(reload = true) {
    jQuery.each(themeOptionArr, function (optionKey, optionValue) {
        deleteCookie(optionKey);
    });
    if (reload) {
        location.reload();
    }
}

/* Cookies Function END */


(function ($) {

    "use strict"

    var direction = getUrlParams('dir');
    var theme = getUrlParams('theme');

    /* Default Light Theme */
    var dezThemeSetLight = {
        typography: "poppins",
        version: "light",
        layout: "vertical",
        headerBg: "color_1",
        primary: "color_1",
        navheaderBg: "color_1",
        sidebarBg: "color_1",
        sidebarStyle: "full",
        sidebarPosition: "fixed",
        headerPosition: "fixed",
        containerLayout: "full",
        direction: direction
    };

    function themeChange(themeSettings) {
        dezSettingsOptions = themeSettings; /* For Screen Resize */
        new dezSettings(themeSettings);
        setThemeInCookie(themeSettings);
    }

    function setThemeInCookie(themeSettings) {
        jQuery.each(themeSettings, function (optionKey, optionValue) {
            setCookie(optionKey, optionValue);
        });
    }

    function setThemeOptionOnPage() {
        jQuery.each(themeOptionArr, function (optionKey, optionValue) {
            var optionData = getCookie(optionKey);
            themeOptionArr[optionKey] = (optionData != '') ? optionData : dezSettingsOptions[optionKey];
        });
        dezSettingsOptions = themeOptionArr;
        new dezSettings(dezSettingsOptions);
    }

    jQuery(window).on('load', function () {
        direction = (direction != undefined) ? direction : 'ltr';
        themeChange(dezThemeSetLight); // Siempre activa el tema claro
        setThemeOptionOnPage();
    });

})(jQuery);