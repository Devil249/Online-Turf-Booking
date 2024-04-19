<?php

session_start();

require 'dbconfig.php';
require 'emails.php';

header("Access-Control-Allow-Origin: *");


try {
    $connection = new mysqli($host, $user, $password, $database);
} catch (Exception $e) {
    echo $e->getMessage();
}

function register($username, $upassword, $uemail, $connection,)
{ //funtion to regsiter users
    
    $hashedVerKey = password_hash(date('Y/m/d H:i:s'), PASSWORD_DEFAULT);
    
    $hashedPass = password_hash($upassword, PASSWORD_DEFAULT);
    $checkExissing = "SELECT email FROM user WHERE email='$uemail'";
    $registerUserQuery = "INSERT INTO user(`name`,`email`,`pass`,`verificationCode`,`createTime`,`verified`) VALUES ('$username','$uemail','$hashedPass','$hashedVerKey',NOW(),0);";
    $_COOKIE[$uemail] = $hashedPass;
    $result = $connection->query($checkExissing);
    if ($result->num_rows > 0) {
        echo 'ue';
    } else {
        if ($connection->query($registerUserQuery)) {
            echo 'ucs'; //usercreation success
            sendEmail($username, $uemail, $hashedVerKey, "register");
        } else {
            echo 'ucf'; //usercreation Failed
        }
    };
}

function login($email, $password, $connection)
{ //function for login verification
    $session_id = session_id();
    $verifyLogonQuery = "SELECT userid,pass,email,name FROM user WHERE email='$email'"; //query to get userid and ecrypted password from database
    $isLoginExcisted = "SELECT session_id, isLoggedin FROM login_management WHERE session_id='$session_id'";

    $isLoginExcistedResult = $connection->query($isLoginExcisted); //validate if given session id is already logged in
    $result = $connection->query($verifyLogonQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['pass'];
        if (password_verify($password, $hashedPassword)) {
            $userId = $row['userid'];
            $uName = $row['name'];
            $uemail = $row['email'];
            setcookie('userid', $userId, time() + 3600 * 24, "/");
            setcookie('sessionID', $session_id, time() + 3600 * 24, "/");
            setcookie('userName', $uName, time() + 3600 * 24, "/");
            setcookie('userEmail', $uemail, time() + 3600 * 24, "/");
            if ($isLoginExcistedResult->num_rows <= 0) {
                $recordLogin = "INSERT INTO login_management(`session_id`,`isLoggedin`,`loggedinat`,`userid`) VALUES ('$session_id',1,NOW(),$userId)"; //query to insert login history
                $connection->query($recordLogin);
                echo ("LS");
            } else {
                echo "AL"; //already login successfully
            }
        } else {
            echo "Email address and password not matched"; //incorrect login will be send for failed login i.e display incorrect email or password
        }
    } else {
        // No user found with the provided email
        echo "No user found with the provided email.";
    }
}

function bookturf($connection)
{
    $date = $_POST["date"];
    $name = $_POST['name'];
    $starttime = (int) $_POST["startTime"];
    $endtime = (int) $_POST["endTime"];
    $transId = $_POST["endTime"];
    $userid = $_COOKIE['userid'];
    $amount = $_POST["amount"];
    $turfName = $_POST["turf"];
    $turfName = $_POST["turf"];
    $userEmail = $_POST["email"];
    $amount = $_POST["amount"];


    $checkExissing = "SELECT id FROM booking WHERE slot_date='$date' AND slot_from=$starttime AND slot_to=$endtime;";

    $addbooking = "INSERT INTO booking(slot_from,slot_to,slot_date,`booking_status`,`payment_status`,`transaction_id`,`booked_by`,`transaction_amount`,`bookingdatetime`,`turn_name`) VALUES ($starttime,$endtime,'$date',1,1,'$transId',$userid,$amount,NOW(),'$turfName');";

    $result = $connection->query($checkExissing);
    // print_r($result);
    if ($result->num_rows > 0) {
        echo 'AB';
    } else {
        // echo $starttime . '<br/>' . $endtime . '<br/>';
        // echo $addbooking;
        // $connection->query($addbooking);
        sendSlotConfirmation($name, $userEmail, $date, $starttime, $endtime, $amount);
        echo 'BC';
    };
}

function logout($connection)
{    //function to logout loggedin users
    $sessionrecordId = $_COOKIE['sessionID'];
    $updaseLoginRecords = "UPDATE login_management SET isLoggedin=0,loggedoutat=NOW() WHERE session_id='$sessionrecordId'";
    $connection->query($updaseLoginRecords);
    setcookie('sessionID', '', time(), "/");
    setcookie('userid', '', time(), "/");
    setcookie('PHPSESSID', '', time(), "/");
    setcookie('userEmail', '', time(), "/");
    setcookie('userName', '', time(), "/");
    session_unset();
    echo '202'; //logout done
}



function loadhome($connection)
{
    $name = $_COOKIE['userName'];
    $email = $_COOKIE['userEmail'];
    $id = $_COOKIE['userid'];
}



if ($_POST['action'] == 'register') {
    if (!$_POST['name'] || !$_POST['cpass'] || !$_POST['pass'] || !$_POST['email']) {
        echo ('Fields cannot left empty');
    } elseif (strlen($_POST['pass']) < 8) {
        echo ('pass Length must be greater than 8');
    } elseif ($_POST['pass'] != $_POST['cpass']) {
        echo ('password and confirm password must match');
    } else {
        
        register($_POST['name'], $_POST['pass'], $_POST['email'], $connection);
    }
} elseif ($_POST['action'] == 'login') {
    login($_POST['email'], $_POST['pass'], $connection);
} elseif ($_POST['action'] == 'logout') {
    logout($connection);
} elseif ($_POST['action'] == 'book') {
    bookturf($connection);
} else {
    echo "Invalid Request";
}

$connection->close();
