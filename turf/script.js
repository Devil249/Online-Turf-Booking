
$ = (queryname) => { return document.querySelector(queryname) }
const validateEmail = (email) => {
    return String(email).toLowerCase().match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
};
let headersList = {
    "Accept": "*/*",
}

$("#endbooktime").addEventListener("click", () => {
    let endTime = parseInt($("#endbooktime").value)
    let startTime = parseInt($("#startBookTime").value)
    console.log(startTime);
    console.log(endTime);

    if (endTime < startTime) {
        alert("EndTime Must be greater than start time")
    } else if (endTime == startTime) {
        alert("End time and start time cannot be same")
    }
})
$("#startBookTime").addEventListener("click", () => {
    let endTime = parseInt($("#endbooktime").value)
    let startTime = parseInt($("#startBookTime").value)
    console.log(startTime);
    console.log(endTime);
    if (endTime < startTime) {
        alert("EndTime Must be greater than start time")
    } else if (endTime == startTime) {
        alert("End time and start time cannot be same")
    }
})

async function register() {
    let emailId = $('#regemail').value
    let username = $('#reguser').value
    let pass = $('#regpass').value
    let cpass = $('#regconfirmPass').value
    if (emailId.length == 0 || username.length == 0 || pass.length == 0) {
        alert("None of the field can be left as blank")
    } else if (!validateEmail(emailId)) {
        alert("Enter a valid email id" + emailId)
    } else if (pass.length < 8 || pass.length > 12) {
        alert("Password Length must be greater then 8 and less then 12")
    }
    else if (pass != cpass) {
        alert("Password and confirm password must match");
    } else {

        let bodyContent = new FormData();
        bodyContent.append('action', 'register');
        bodyContent.append("email", emailId);
        bodyContent.append("name", username);
        bodyContent.append("pass", pass);
        bodyContent.append("cpass", cpass);
        // bodyContent.append("pass", "123456789A");
        let response = await fetch("http://localhost/backend.php", {
            method: "POST",
            body: bodyContent,
            headers:  {
    "Accept": "*/*",
}
        });
        let data = await response.text();

        if (data == 'ue') {
            alert("Email id already register, contact admin for password reset");
        } else if (data == 'ucs') {
            alert("Account created successfully. Use login page to login into your account");
        } else {
            alert(data);
        }
    }

}

async function login() {
    let emailId = $("#email").value;
    let pass = $("#password").value
    let bodyContent = new FormData();
    bodyContent.append('action', 'login');
    bodyContent.append("email", emailId);
    bodyContent.append("pass", pass);

    if (validateEmail(emailId)) {
        let response = await fetch("http://localhost/backend.php", {
            method: "POST",
            body: bodyContent,
            headers: headersList
        })
        let res = await response.text()
        if (res == 'LS' || res=='AL') {
            alert("Logged in successfully");
            window.location.href = "http://localhost/turfbooking/turf/userpage.html";
        } else{
            alert(res)
        }
    } else {
        alert("Please enter valid email address");
    }
    ;



}

async function submitBooking() {
    $("#endbooktime").addEventListener("click", () => {
        let endTime = parseInt($("#endbooktime").value)
        let startTime = parseInt($("#startBookTime").value)
        console.log(startTime);
        console.log(endTime);

        if (endTime < startTime) {
            alert("EndTime Must be greater than start time")
        } else if (endTime == startTime) {
            alert("End time and start time cannot be same")
        }
    })
    $("#startBookTime").addEventListener("click", () => {
        let endTime = parseInt($("#endbooktime").value)
        let startTime = parseInt($("#startBookTime").value)
        console.log(startTime);
        console.log(endTime);
        if (endTime < startTime) {
            alert("EndTime Must be greater than start time")
        } else if (endTime == startTime) {
            alert("End time and start time cannot be same")
        }
    })
    let phone = $("#phone").value
    let endTime = parseInt($("#endbooktime").value)
    let startTime = parseInt($("#startBookTime").value)
    let date = $("#date").value
    let name = $("#name").value
    let turf = $("#Turfs").value
    let amount = (endTime - startTime) * 100
    let useremail = getCookieByName("userEmail").replace("%40", "@").toString().toLowerCase();
    let username = getCookieByName("userName");
    let inid = new Date().getTime().toString()

    let bookingContent = new FormData();
    bookingContent.append('action', 'book');
    bookingContent.append("phone", phone);
    bookingContent.append("endTime", endTime);
    bookingContent.append("startTime", startTime);
    bookingContent.append("date", date);
    bookingContent.append("name", name);
    bookingContent.append("turf", turf);
    bookingContent.append("amount", amount);
    bookingContent.append("email", useremail);
    bookingContent.append("invoiceid", inid);



    let bodyContent = JSON.stringify({
        "app_name": "The Turf",
        "service": "Turf",
        "customer_email": useremail,
        "card_type  ": "VISA",
        "card_holder_name": username,
        "card_number": "4242424242424242",
        "expiryMonth": "01",
        "expiryYear": "2020",
        "cvv": "123",
        "amount": amount,
        "currency": "INR",
        "invoiceId": inid
    });

    let today = new Date()
    let inputDate = new Date(date)

    if (endTime < startTime) {
        alert("EndTime Must be greater than start time")
    } else if (endTime == startTime) {
        alert("End time and start time cannot be same")
    }
    else if (phone.length < 10 || phone.length > 10) {
        alert("Length of phone number must be 10")
    } else if (date == "" || date == null) {
        alert("Date field is blank");
    } else if (inputDate < today) {
        alert("Cannot be booked for past dates");
    } else {
        let paymentgatewayres = await fetch("http://localhost:5100/api/v1/payment/card", {
            method: "POST",
            body: bodyContent,
            headers: {
                "Content-Type": "application/json",
                "Accept": "*/*"
            }
        });


        let response = await fetch("http://localhost/backend.php", {
            method: "POST",
            body: bookingContent,
            headers: {
                "Accept": "*/*"
            }
        })
        let res = await response.text();

        if (res == 'AB') {
            alert("This slot is already booked, try another")
        } else if (res == 'BC') {
            alert('Your Slot has been booked, you will get email confirmation shortly.');
        } else {
            alert(res);
        }



    }
}

function viewBooking() {
    // Implement view booking functionality
}

function editBooking() {
    // Implement edit booking functionality
}

function deleteBooking() {
    // Implement delete booking functionality
}

// document.getElementById("addTurf").addEventListener("submit", function (event) {
//     event.preventDefault();
//     const TurfName = document.getElementById("TurfName").value;
//     const Location = document.getElementById("Location").value;
//     // Send data to the server to add a new movie screen
//     // Handle the response accordingly
// });


