<?php
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



function sendEmail($user, $userEmail, $verCode, $condition) //?condition will define if the email is for registration or reset
//!condition will have either reset or register only
{
    try {
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'e3164a389e87c2';
        $phpmailer->Password = '0badda9b684f53';

        

        $phpmailer->setFrom('no-reply@bookmyturf.com', 'bookmyturf');
        $phpmailer->addAddress("$userEmail", "$user");
        $phpmailer->isHTML(true);                                  //Set email format to HTML
        $phpmailer->Subject = 'Here is the subject';
        if ($condition == "register") {
            $phpmailer->Body    = '
            <body style="height: 20rem; width:100%;max-width:30rem;margin:auto">
                        Hello User.
                <div>This is to confirm that you have been registered with us.</div>
                <div>Verify your account to proceed further</div>
                <a href="http://localhost/ver.php/?action=register&code=' . $verCode . '">Verify</a>
            </body>';
        } elseif ($condition == "reset") {
            $phpmailer->Body    = '
            <body style="height: 20rem; width:100%;max-width:30rem;margin:auto">
                Hello ' . $user . '.
                <div>This is regarding the password reset you have requested</div>
                <div>Use below link to reset your password. It is valid for only once</div>
                <a href="http://localhost/ver.php/?action=reset&code=' . $verCode . '">Verify</a>
            </body>';
        }
        $phpmailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $phpmailer->send();
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$e}";
    }
}

function sendSlotConfirmation($user, $userEmail, $slotDate, $slotStartTime, $slotEndTime, $amount) //?condition will define if the email is for registration or reset
//!condition will have either reset or register only
{
    try {
        $phpmailer = new PHPMailer(true);
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'e452f7a5282001';
        $phpmailer->Password = 'ee790e5dcf5eba';
        $phpmailer->setFrom('no-reply@bookmyturf.com', 'bookmyturf');
        $phpmailer->addAddress("$userEmail", "$user");
        $phpmailer->isHTML(true);                                  //Set email format to HTML
        $phpmailer->Subject = 'Here is the subject';
        $phpmailer->Body =
            '
            <body style="height: 20rem; width:90%;max-width:30rem;margin:auto">
                        Hello User.
                <div>This is to confirm that the turf has been booked successfully.</div>
                <table>
                    <tr>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End time</th>
                    <th>Hours</th>
                    <th>Total Amount(Rs)</th>
                    </tr>
                    <tr>
                    <td style="text-align:center">' . $slotDate . '</td>
                    <td style="text-align:center">' . $slotStartTime . '</td>
                    <td style="text-align:center">' . $slotEndTime . '</td>
                    <td style="text-align:center">' . $slotEndTime - $slotStartTime . '</td>
                    <td style="text-align:center">' . $amount . '</td>
                    </tr>
                </table>
            </body>';
        $phpmailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $phpmailer->send();
       
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$e}";
    }
}

//sendSlotConfirmation('shivam', 'Shivam@gamil.com', '28/12/2024', '12:00', '13:00', '100');
