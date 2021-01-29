<?php
include 'layouts/session.php';
require_once "config.php";
$user_id = mysqli_real_escape_string($link, $_SESSION['id']);

$old_password = $password = $confirm_password = "";
$success = $old_password_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate useremail
    if(empty(trim($_POST["old_password"]))){
        $old_password_err = "Please enter your current password.";
    } else {
        // Prepare a select statement
        $sql = "SELECT pass FROM users WHERE id = ".$user_id;
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (md5(trim($_POST["old_password"])) == $row["pass"])
                $old_password = trim($_POST["old_password"]);
            else
                $old_password_err = "You entered the wrong password.";
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a new password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the new password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before update
    if(empty($old_password_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = 'UPDATE users SET pass = "'.md5(trim($_POST["password"])).'" WHERE id = '.$user_id;
        $result = mysqli_query($link, $sql);
        $success = "You successfully changed your account password.";
    }

    // Close connection
    mysqli_close($link);
}

include 'layouts/head-main.php';
?>
<head>
    <title>Account Settings | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
    <?php include 'layouts/head.php'; ?>
    <!-- select2 css -->
    <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <!-- dropzone css -->
    <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css"/
    <?php include 'layouts/head-style.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?php include 'layouts/menu.php'; ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">Settings</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/">Pitman Bot</a></li>
                                    <li class="breadcrumb-item active">Account</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <?php if (!empty($success)) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-lock-check mr-2"></i>
                        <?php echo $success; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Change Password</h4>
                                <p class="card-title-desc">Fill all information below</p>

                                <form method="post">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group <?php echo (!empty($old_password_err)) ? 'has-error' : ''; ?>">
                                                <label for="price">Current Password</label>
                                                <input class="form-control" type="password" name="old_password" value="<?php echo $old_password; ?>">
                                                <span class="text-danger"><?php echo $old_password_err; ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                                <label for="price">New Password</label>
                                                <input class="form-control" type="password" name="password" value="<?php echo $password; ?>">
                                                <span class="text-danger"><?php echo $password_err; ?></span>
                                            </div>
                                            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                                <label for="price">Repeat New Password</label>
                                                <input class="form-control" type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                                                <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary float-sm-right mr-1 waves-effect waves-light">Save
                                        Changes
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include 'layouts/footer.php'; ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- Right Sidebar -->
<?php include 'layouts/right-sidebar.php'; ?>
<!-- Right-bar -->

<!-- JAVASCRIPT -->
<?php include 'layouts/footer-scripts.php'; ?>

<!-- select 2 plugin -->
<script src="assets/libs/select2/js/select2.min.js"></script>

<!-- init js -->
<script src="assets/js/pages/ecommerce-select2.init.js"></script>

<!-- App js -->
<script src="assets/js/app.js"></script>

</body>
</html>
