<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$useremail = $username =  $password = $confirm_password = "";
$useremail_err = $username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate useremail
    if(empty(trim($_POST["useremail"]))){
        $useremail_err = "Please enter an email.";
    } elseif (!filter_var($_POST["useremail"], FILTER_VALIDATE_EMAIL)) {
        $useremail_err = "Invalid email format";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_useremail);

            // Set parameters
            $param_useremail = trim($_POST["useremail"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $useremail_err = "This email is already taken.";
                } else{
                    $useremail = trim($_POST["useremail"]);
                }
            } else $useremail_err = "Oops! Something went wrong. Please try again later.";

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($useremail_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (email, user, pass, subscription_end_timestamp) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            $trial_time = strtotime("+3 months", time());
            mysqli_stmt_bind_param($stmt, "sssd", $param_useremail, $param_username, $param_password, $trial_time);

            // Set parameters
            $param_useremail = $useremail;
            $param_username = $username;
            $param_password = md5($password);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: auth-login.php?n=3");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Register | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
    <?php include 'layouts/head.php'; ?>
    <?php include 'layouts/head-style.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>
<div class="home-btn d-none d-sm-block">
    <a href="/" class="text-dark"><i class="fas fa-home h2"></i></a>
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
                                    <h5 class="text-primary">Free Register</h5>
                                    <p>Get your free Pitman Bot account now.</p>
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
                            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                                <div class="form-group <?php echo (!empty($useremail_err)) ? 'has-error' : ''; ?>">
                                    <label for="useremail">Email</label>
                                    <input type="email" class="form-control" name="useremail" id="useremail" placeholder="Enter email" value="<?php echo $useremail; ?>">
                                    <span class="text-danger"><?php echo $useremail_err; ?></span>
                                </div>

                                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" value="<?php echo $username; ?>">
                                    <span class="text-danger"><?php echo $username_err; ?></span>
                                </div>

                                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                    <label for="userpassword">Password</label>
                                    <input type="password" class="form-control" id="userpassword" name="password" placeholder="Enter password" value="<?php echo $password; ?>">
                                    <span class="text-danger"><?php echo $password_err; ?></span>
                                </div>

                                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                    <label for="userpassword">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter password" value="<?php echo $confirm_password; ?>">
                                    <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                                </div>

                                <div class="mt-4">
                                    <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">
                                        Register
                                    </button>
                                </div>

                                <div class="mt-4 text-center">
                                    <p class="mb-0">By registering you agree to Pitman Bot <a href="terms.php" class="text-primary">Terms & Conditions</a></p>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="mt-5 text-center">

                    <div>
                        <p>Already have an account ? <a href="auth-login.php" class="font-weight-medium text-primary">
                                Login</a></p>
                        <p><?php echo date('Y', time());?> © pitmanbot.com</p>
                    </div>
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
