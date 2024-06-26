<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'layouts/head-main.php';

// client 6LfwFEAaAAAAACkQPwBwAr-40egeneHcc8Yq2LGX
define("RECAPTCHA_V3_SECRET_KEY", '6LfwFEAaAAAAAKdZFG3KHeI_H-oCdX9In8wK_5IC');

// Include config file
require_once "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

// Define variables and initialize with empty values
$name = $email = $message = "";
$captcha_err = $name_err = $email_err = $message_err = "";
$success = "";

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if name is empty
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";
    } else{
        $name = trim($_POST["name"]);
    }

    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Check if email is message
    if(empty(trim($_POST["message"]))){
        $message_err = "Please type your message.";
    } else{
        $message = strip_tags($_POST["message"]);
    }

    $token = $_POST['token'];
    $action = $_POST['action'];

    // call curl to POST request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $token)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $arrResponse = json_decode($response, true);

    // verify the response
    if (!($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5)) {
        $captcha_err = "Invalid CAPTCHA: please try again.";
    }

    // Validate credentials
    if(empty($name_err) && empty($email_err) && empty($message_err) && empty($captcha_err)) {
        $subject = "[Contact] Pitman Bot Inquiry from ".$name;

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
                                                '.nl2br($message).'
                                            </td>
                                        </tr>

                                        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                <b>'.$name.'</b>
                                                <p>&lt;'.$email.'&gt;</p>
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
        $body_nohtml = $message.' - from '.$name.' <'.$email.'>';

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
            $mail->addAddress('alurosu@gmail.com', 'alurosu');
            $mail->addAddress('gabitza.sas@gmail.com', 'Gabriel Sas');
            $mail->addReplyTo($email, $name); // to set the reply to

            // Setting the email content
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $body_nohtml;

            $mail->send();
            // echo "We have emailed your password reset link!";
            $success = "We received your message. Expect an answer in a few business days.";
            $name = $email = $message = "";

        } catch (Exception $e) {
            $email_err = "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    // Close connection
    mysqli_close($link);
}
?>

<head>
    <title>Contact | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
    <?php include 'layouts/head.php'; ?>
    <?php include 'layouts/head-style.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LfwFEAaAAAAACkQPwBwAr-40egeneHcc8Yq2LGX"></script>
</head>

<body data-topbar="dark" data-layout="horizontal" data-layout-size="boxed">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'layouts/horizontal-menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content mt-4">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">Get in Touch</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="/">Pitman Bot</a></li>
                                        <li class="breadcrumb-item active">Contact</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?php if ($captcha_err) { ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-human-greeting mr-2"></i>
                            <?php echo $captcha_err; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php } elseif ($success) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-email-check mr-2"></i>
                            <?php echo $success; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title">Contact Form</h4>
                                    <p class="card-title-desc">All fields are required</p>

                                    <form method="post" action="contact.php" id="contactForm">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                                    <label for="name">Name</label>
                                                    <input class="form-control" id="name" type="text" name="name" placeholder="John Doe" value="<?php echo $name; ?>">
                                                    <span class="text-danger"><?php echo $name_err; ?></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                                    <label for="email">Email</label>
                                                    <input class="form-control" type="email" name="email" id="email" placeholder="john@doe.com" value="<?php echo $email; ?>">
                                                    <span class="text-danger"><?php echo $email_err; ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group <?php echo (!empty($message_err)) ? 'has-error' : ''; ?>">
                                                    <label for="message">Message</label>
                                                    <textarea class="form-control" name="message" id="message" rows="5" placeholder="Hi, you offer an interesting service and I have a few questions. Can you help me out?"><?php echo $message; ?></textarea>
                                                    <span class="text-danger"><?php echo $message_err; ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary float-sm-right mr-1 waves-effect waves-light">Send</button>
                                    </form>

                                    <script>
                                    $('#contactForm').submit(function(event) {
                                        event.preventDefault();

                                        grecaptcha.ready(function() {
                                            grecaptcha.execute('6LfwFEAaAAAAACkQPwBwAr-40egeneHcc8Yq2LGX', {action: 'contact'}).then(function(token) {
                                                $('#contactForm').prepend('<input type="hidden" name="token" value="' + token + '">');
                                                $('#contactForm').prepend('<input type="hidden" name="action" value="contact">');
                                                $('#contactForm').unbind('submit').submit();
                                            });;
                                        });
                                    });
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include 'layouts/footer-large.php'; ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <?php include 'layouts/footer-scripts.php'; ?>

    <script src="assets/js/app.js"></script>
</body>

</html>
