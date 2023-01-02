function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookieVal(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookies(c) {
    // console.log("Cookie Arr: " + document.cookie);
    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires= Thu, 01 Jan 1970 00:00:01 GMT;" + ";path=/");
    // console.log("Deleted cookie: " + c);
    // console.log("new cookie Arr: " + document.cookie);
}

function cookieStr(name, val) {
    return name + "=" + val + ";";
} 