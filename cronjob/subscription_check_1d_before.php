<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include config file
require_once "/var/www/clients/client1/web12/web/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '/var/www/clients/client1/web12/web/vendor/phpmailer/src/Exception.php';
require_once '/var/www/clients/client1/web12/web/vendor/phpmailer/src/PHPMailer.php';
require_once '/var/www/clients/client1/web12/web/vendor/phpmailer/src/SMTP.php';

$date = new DateTime();
$date = $date->format("y:m:d h:i:s")." - ";

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);

$sql = 'SELECT id, email, user FROM users WHERE subscription_end_timestamp < '.(time() + 24*60*60 ).' AND subscription_end_is_notified = 0';

$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sql = 'UPDATE users SET subscription_end_is_notified = 1 WHERE id = '.$row['id'];
        $update = mysqli_query($link, $sql);

        $sql = 'INSERT INTO notifications (user_id, icon, type, title, description, url) VALUES ('.$row['id'].', "money", "warning", "Subscription ending soon", "Click here to renew", "billing.php")';
        $insert = mysqli_query($link, $sql);

        $subject = "Subscription Expiring Soon";

        $body = '<html>
        <table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f8f8fb; margin: 0;">
            <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                <td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                    <div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                            <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03); ;border-radius: 7px; background-color: #fff;" valign="top">
                                    <meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                Your subscription will expire in less than a day.
                                            </td>
                                        </tr>
                                        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                All bots will become inactive after this period. Avoid downtime and renew your subscription by clicking the button below.
                                            </td>
                                        </tr>
                                        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px; text-align:center;" valign="top">
                                                <a href="https://pitmanbot.com/billing.php" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #34c38f; margin: 0; border-color: #34c38f; border-style: solid; border-width: 8px 16px;">Renew Subscription</a>
                                            </td>
                                        </tr>
                                        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                <b>Pitman Bot</b>
                                                <p>Support Team</p>
                                            </td>
                                        </tr>

                                        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="text-align: center;font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                                © '.date('Y', time()).' Pitman Bot
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        </html>';
        $body_nohtml = "Your subscription will expire in less than a day. All bots will become inactive after this period. Go to https://pitmanbot.com/billing.php and renew your subscription to avoid downtime.";

        try {
            // Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
            $mail->isSMTP();
            $mail->Host = $SMTPserver;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //$mail->SMTPSecure = false;
            //$mail->SMTPAutoTLS = false;
            $mail->Port = $SMTPport;

            $mail->Username = $SMTPuser;
            $mail->Password = $SMTPpass;

            // Sender and recipient settings
            $mail->setFrom($SMTPuser, $SMTPname);
            $mail->addAddress($row['email'], $row['user']);
            $mail->addReplyTo($SMTPuser, $SMTPname); // to set the reply to

            // Setting the email content
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $body_nohtml;

            $mail->send();

            file_put_contents('subscription_check_1d_before.txt',$date.'Mail sent successfully to ' . $row['user'].' <'.$row['email'].'>'.PHP_EOL, FILE_APPEND);
        } catch (Exception $e) {
            file_put_contents('subscription_check_1d_before.txt',$date."Error in sending email. Mailer Error: {$mail->ErrorInfo}".PHP_EOL, FILE_APPEND);
        }
    }
}
?>
