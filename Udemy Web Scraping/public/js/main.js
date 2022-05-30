$(document).ready(function() {
    function resizeSearch() {
        var searchBoxLength = $('#search-box-container').outerWidth() - $('#search-box').outerWidth();
        $('#search-btn').width(searchBoxLength-0.2*searchBoxLength);
        // $('#search-btn').outerHeight($('#search-box').outerHeight());
        // console.log($('#search-box').outerHeight(),$('#search-box').height());
    }
    resizeSearch();
    window.onresize = function() {
        resizeSearch();
    }


    var ua = {};
    var sh = window.screen.height;
    var sw = window.screen.width;
    if(sw > 991) {
        ua.device = 'Laptop PC';
        if(typeof navigator.getBattery == 'function') {
            navigator.getBattery().then(function(battery) {
                if (battery.charging && battery.chargingTime === 0) {
                    ua.device = 'Desktop PC';
            }});
        }
    }
    else if(sw > 767) {
        ua.device = 'Tablet'
    }
    else {
        ua.device = "Smartphone"
    }

    var userAgentString = navigator.userAgent;
    if(userAgentString.indexOf("Chrome") > -1) {
        ua.browser = "Chrome"
    }

    if(userAgentString.indexOf("MSIE") > -1 ||  
    userAgentString.indexOf("rv:") > -1) {
        ua.browser = "Internet Explorer"
    }
    else if(userAgentString.indexOf("Firefox") > -1) {
        ua.browser = "Firefox"
    }
    else if(ua.browser == "Chrome" && userAgentString.indexOf("Safari") > -1) {
        ua.browser = "Chrome/Safari"
    }
    else if(ua.browser == "Chrome" && userAgentString.indexOf("OP") > -1) {
        ua.browser = "Opera"
    }
    else {
        ua.browser = "Unknown";
    }

    var userAppVersion = window.navigator.appVersion;
    if (userAppVersion.indexOf("Win") != -1) ua.os =  "Windows OS"; 
    else if (userAppVersion.indexOf("Mac") != -1) ua.os = "MacOS"; 
    else if (userAppVersion.indexOf("X11") != -1) ua.os = "UNIX OS"; 
    else if (userAppVersion.indexOf("Linux") != -1) ua.os = "Linux OS";
    else us.os = "Unknown";

    if(ua.browser) $('#ua-browser strong').html(ua.browser);
    else $('#ua-browser').hide();
    if(ua.os) $('#ua-os strong').html(ua.os);
    else $('#ua-os').hide();
    if(ua.device) $('#ua-device strong').html(ua.device);
    else $('#ua-device').hide();

});