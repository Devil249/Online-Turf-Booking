async function register() {
    let emailId = document.getElementById('regemail').value
    let username = document.getElementById('reguser').value
    let pass = document.getElementById('regpass').value
    let cpass = document.getElementById('regconfirmPass').value
    let bodyContent = new FormData();
    bodyContent.append('action', 'register');
    bodyContent.append("email", emailId);
    bodyContent.append("name", username);
    bodyContent.append("pass", pass);
    bodyContent.append("cpass", cpass);
    // bodyContent.append("pass", "123456789A");
    let response = await fetch("http://localhost/test.php", {
        method: "POST",
        body: bodyContent,
        headers: headersList
    });
    let data = await response.text();
    if (data == 'ue') {
        alert("Email id already register, contact admin for password reset");
    } else if (data == 'ucs') {
        alert("Account created successfully. Use login page to logim into your account");
    } else {
        alert('Account creation failed. Contact admin for details');
    }
}



let headersList = {
    "Accept": "*/*",
}

async function loginSubmit() {
    let emailId = document.getElementById('user').value
    let pass = document.getElementById('pass').value
    let bodyContent = new FormData();
    bodyContent.append('action', 'login');
    bodyContent.append("email", emailId);
    bodyContent.append("pass", pass);
    // bodyContent.append("pass", "123456789A");

    let response = await fetch("http://localhost/test.php", {
        method: "POST",
        body: bodyContent,
        headers: headersList
    });
    let data = await response.text();
    if (data == 'LS') {
        alert("Logged in successfully. You will be redirected to you home page...");
        window.location.href = "http://localhost/afterlogin/"
    } else {
        alert(data);
    }
}

async function register() {
    let emailId = document.getElementById('regemail').value
    let username = document.getElementById('reguser').value
    let pass = document.getElementById('regpass').value
    let cpass = document.getElementById('regconfirmPass').value
    let bodyContent = new FormData();
    bodyContent.append('action', 'register');
    bodyContent.append("email", emailId);
    bodyContent.append("name", username);
    bodyContent.append("pass", pass);
    bodyContent.append("cpass", cpass);
    // bodyContent.append("pass", "123456789A");
    let response = await fetch("http://localhost/test.php", {
        method: "POST",
        body: bodyContent,
        headers: headersList
    });
    let data = await response.text();
    if (data == 'ue') {
        alert("Email id already register, contact admin for password reset");
    } else if (data == 'ucs') {
        alert("Account created successfully. Use login page to logim into your account");
    } else {
        alert('Account creation failed. Contact admin for details');
    }
}

function getCookieByName(cookieName) {
    let cookieList = document.cookie.split(";");
    for (let index = 0; index < cookieList.length; index++) {
        let cookiename = cookieList[index].split("=")[0].trim();
        let cookieValue = cookieList[index].split("=")[1];
        if (cookiename === cookieName) {
            return cookieValue;
        }
    }
    return false
}

async function logout() {
    console.log('logout called');
    let bodyContent = new FormData();
    bodyContent.append('action', 'logout');
    // bodyContent.append("pass", "123456789A");
    let response = await fetch("http://localhost/test.php", {
        method: "POST",
        body: bodyContent,
        headers: headersList
    });
    let data = await response.text();
    if (data == '202') {
        alert('Logged out successfully, you will be redirected to home page.');
        window.location.href = "http://localhost"
    }

}

if (getCookieByName('userName')) {
    console.log('Logged in'); //this is just for timepaas
}