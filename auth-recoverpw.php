<?php
// Include config file
require_once "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_POST['submit'])){

    $useremail = mysqli_real_escape_string($link, $_POST['useremail'] );
    $sql = "SELECT * FROM users WHERE useremail = '$useremail'";
    $query = mysqli_query($link, $sql);
    $emailcount = mysqli_num_rows($query);

    if($emailcount) {
        $userdata = mysqli_fetch_array($query);  
        $username= $userdata['username'];
        $token= $userdata['token'];    

        $subject = "Password Reset";
        $body = "Hi, $username. Click here to reset your password ".$actual_link."/auth-reset-password.php?token=$token ";
        // $body = "Hi, $username. Clicl here to reset your password http://localhost/Skote-PHP/auth-reset-password.php?token=$token ";
        $sender_email = "From: $gmailid";

        try {
            // Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
        
            $mail->Username = $gmailid;
            $mail->Password = $gmailpassword;
        
            // Sender and recipient settings
            $mail->setFrom($gmailid, $gmailusername);
            $mail->addAddress($useremail, $username);
            $mail->addReplyTo($gmailid, $gmailusername); // to set the reply to
        
            // Setting the email content
            $mail->IsHTML(true);
            $mail->Subject = $subject; 
            $mail->Body = $body;
        
            $mail->send();
            // echo "We have emailed your password reset link!";
            header("location:auth-login.php");
        } catch (Exception $e) {
            echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    else {
        echo "No Email Found";
    }

}
?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Recover Password | Skote - Responsive Bootstrap 4 Admin Dashboard</title>
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
                                    <h5 class="text-primary"> Reset Password</h5>
                                    <p>Re-Password with Skote.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div>
                            <a href="index.php">
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

                                <div class="form-group">
                                    <label for="useremail">Email</label>
                                    <input type="email" class="form-control" id="useremail" name="useremail" placeholder="Enter email">
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
                    <p>© 2020 Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
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