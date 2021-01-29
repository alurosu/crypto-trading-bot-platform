<?php
// Include config file
require_once "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

$email_err = "";

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_POST['submit'])){

    // Validate useremail
    if(empty(trim($_POST["useremail"]))){
        $email_err = "Please enter your email.";
    } elseif (!filter_var($_POST["useremail"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else {
        $useremail = mysqli_real_escape_string($link, $_POST['useremail'] );
        $sql = "SELECT * FROM users WHERE email = '$useremail'";
        $query = mysqli_query($link, $sql);
        $emailcount = mysqli_num_rows($query);

        if($emailcount) {
            $userdata = mysqli_fetch_array($query);
            $username= $userdata['user'];
            // add token to db
            $token = md5(uniqid());
            $sql = "UPDATE users SET token = '$token' WHERE email = '$useremail'";
            $result = mysqli_query($link, $sql);

            $subject = "Password Reset";
            $body = "Hi, $username. Click here to reset your password ".$actual_link."/auth-reset-password.php?token=$token ";

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
                                                    Reset your password by clicking the button below.
                                                </td>
                                            </tr>
                                            <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                    If you did not request a password change, please ignore this email.
                                                </td>
                                            </tr>
                                            <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px; text-align:center;" valign="top">
                                                    <a href="https://pitmanbot.com/auth-reset-password.php?token='.$token.'" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #34c38f; margin: 0; border-color: #34c38f; border-style: solid; border-width: 8px 16px;">Reset my password</a>
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
            $body_nohtml = "Reset your password by copying the following link in your browser: https://pitmanbot.com/auth-reset-password.php?token=".$token." .If you did not request a password change, please ignore this email.";
            $sender_email = "From: $SMTPuser";

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
                $mail->addAddress($useremail, $username);
                $mail->addReplyTo($SMTPuser, $SMTPname); // to set the reply to

                // Setting the email content
                $mail->IsHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->AltBody = $body_nohtml;

                $mail->send();
                // echo "We have emailed your password reset link!";
                header("location:auth-login.php?n=1");
            } catch (Exception $e) {
                $email_err = "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
            }
        }
        else $email_err = "We didn't find your email in our database.";
    }

}
?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Recover Password | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
    <?php include 'layouts/head.php'; ?>
    <?php include 'layouts/head-style.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>
<div class="home-btn d-none d-sm-block">
    <a href="index.php" class="text-dark"><i class="fas fa-home h2"></i></a>
</div>
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-soft-primary">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Reset Password</h5>
                                    <p>Recover your Pitman Bot account.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div>
                            <a href="/">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="assets/images/logo.svg" alt="" class="rounded-circle"
                                                height="34">
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="p-2">
                            <div class="alert alert-success text-center mb-4" role="alert">
                                Enter your Email and instructions will be sent to you!
                            </div>
                            <form class="form-horizontal" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="post">

                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <label for="useremail">Email</label>
                                    <input type="email" class="form-control" id="useremail" name="useremail" placeholder="Enter email">
                                    <span class="text-danger"><?php echo $email_err; ?></span>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-primary w-md waves-effect waves-light" type='submit' name='submit' value='Submit'>
                                            Reset
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                <div class="mt-5 text-center">
                    <p>Remember It ? <a href="auth-login.php" class="font-weight-medium text-primary"> Sign In here</a>
                    </p>
                    <p><?php echo date('Y', time());?> © pitmanbot.com</p>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT -->
<?php include 'layouts/footer-scripts.php'; ?>

<!-- App js -->
<script src="assets/js/app.js"></script>
</body>
</html>
