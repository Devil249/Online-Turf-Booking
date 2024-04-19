<?php
require 'dbconfig.php';

try {
    $connection = new mysqli($host, $user, $password, $database);
} catch (Exception $e) {
    return $e->getMessage();
}

if (isset($_GET['action']) && isset($_GET['code'])) {
    if ($_GET['action'] == 'register') {
        try {
            $code = $_GET['code'];
            $stmt = $connection->prepare("UPDATE user SET verified = 1, verificationTime = NOW() WHERE verificationCode = ?");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $stmt->close();
            // $updateVerfication = "update user set verified=1,verificationTime=NOW() WHERE verificationCode='$code'";
            // $connection->query($updateVerfication);
            $fetchVerfication = "select * from user WHERE verificationCode='$code'";
            echo $code . "<br>";
            $result = $connection->query($fetchVerfication);
            print_r($result);
            echo "<h1> Verified </h1> ";
            echo "Email id has been verified you can now login from login page";
        } catch (Exception $exception) {
            echo $exception;
        }
    }
} else {
    echo "<h1> Invalid request</h1>";
    return 0;
}
