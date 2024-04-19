function getCookieByName(cookieName) {
    let cookieList = document.cookie.split(";");

    for (let index = 0; index < cookieList.length; index++) {
        let cookiename = cookieList[index].split("=")[0].trim();
        let cookieValue = cookieList[index].split("=")[1];

        if (cookiename == cookieName && cookieValue != "") {
            return cookieValue;
        }
    }
    return false;
}

$ = (queryname) => {
    return document.querySelector(queryname);
};

function validatelogin() {
    if (!getCookieByName("sessionID")) {
        alert("You have not logged in. You will be redirected to login page to login");
        window.location.href = "http://localhost/turf/login.html";
    }
}