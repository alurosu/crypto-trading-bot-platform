<?php include 'layouts/session.php'; ?>
<?php
require_once "config.php";
$user_id = mysqli_real_escape_string($link, $_SESSION['id']);

// do payment redirect
require_once("vendor/autoload.php");
use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;
$payment_message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $amount = 499;
    $subscription = mysqli_real_escape_string($link, $_POST['subscription']);
    if ($subscription == "Standard")
        $amount = 19;
    elseif ($subscription == "Enterprise")
        $amount = 49;

    $time = 1;
    $percentoff = 0;
    $s="";
    if (is_numeric($_POST['time']) && $_POST['time'] > 1) {
        $s = "s";
        $time = $_POST['time'];
        if ($time==3)
            $percentoff = 10;
        elseif ($time==6)
            $percentoff = 15;
        elseif ($time==12)
            $percentoff = 20;
    }

    $amount = $amount*$time;
    $amount = $amount - $amount*$percentoff/100;

    $description = $subscription.': '.$time.' month'.$s;

    $sql = "INSERT INTO `payments` (`user_id`, `description`, `amount`, `currency`, `type`, `subscription`, `time`) VALUES ($user_id, '$description', $amount, 'USD', 'Crypto', '".$subscription."', ".$time.")";
    if (mysqli_query($link, $sql) === TRUE) {
        $payment_id = mysqli_insert_id($link);
        // create checkout in coinbase
        ApiClient::init('08a910f3-5809-4409-958a-586877a8f7b2');

        $chargeObj=new Charge([
            'name'=>'Subscription',
            'description'=>$description,
            'local_price'=>['amount'=>$amount,'currency'=>"USD"],
            'metadata'=>['payment_id'=>$payment_id],
            'pricing_type'=>'fixed_price',
            "redirect_url" => 'https://pitmanbot.com/billing.php?success=true', // thank you
            "cancel_url" => 'https://pitmanbot.com/billing.php?cancel='.$payment_id // cancel
        ]);

        try {
            $chargeObj->save();

            // return donation url
            $sql = "UPDATE payments SET coinbase_id = '". $chargeObj->id ."' WHERE id = ".$payment_id;
            mysqli_query($link, $sql);
            header("location: ".$chargeObj->hosted_url);
            exit;
        } catch (\Exception $exception) {
            $payment_message = "Could not generate your payment link. ".$exception->getMessage();
        }
    } else $payment_message = "Could not save your information in our database. Try again later.";
}

// cancel payments if user returns from coinbase
if (is_numeric($_GET['cancel'])) {
    $sql = "UPDATE payments SET status = 'canceled' WHERE id = ".$_GET['cancel'];
    mysqli_query($link, $sql);
    $payment_message = "The current payment was canceled. You can try again anytime.";
}

// load subscription info
$email = $subscription_name = $graph_time = "";
$subscription_end_timestamp = $max_bots = $active_bots = $inactive_bots = 0;

// select user info
$sql = 'SELECT email, subscription_name, subscription_end_timestamp, max_bots FROM users WHERE id = '.$user_id;
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $email = $row["email"];
    $subscription_name = $row["subscription_name"];
    $subscription_end_timestamp = $row["subscription_end_timestamp"];
    $max_bots = $row["max_bots"];
}

// select bot count info
$sql = 'SELECT status, count(*) as c FROM bots WHERE is_deleted = 0 AND  user_id = '.$user_id.' GROUP BY status';
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['status']==1)
            $active_bots = $row['c'];
        else
            $inactive_bots = $row['c'];
    }
}
?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Billing | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <?php include 'layouts/head.php'; ?>
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
                            <h4 class="mb-0 font-size-18">Billing</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/">Pitman Bot</a></li>
                                    <li class="breadcrumb-item active">Billing</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <?php if ($payment_message){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-close-circle-outline mr-2"></i>
                        <?php echo $payment_message; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } elseif (isset($_GET['success'])){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-checkbox-marked-circle-outline mr-2"></i>
                        Thank you for your payment. The subscription will be updated once we confirm the transaction.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <?php
                                        echo $payment_message;
                                        $call_to_action = "Upgrade";
                                        $color = "success";
                                        $subscription_info = '<p>Expired</p>';
                                        if (time()>$subscription_end_timestamp) {
                                            $call_to_action = "Renew";
                                            $color = "danger";
                                        } else  $subscription_info = '<p>Expires on: '.date("d M Y",$subscription_end_timestamp).'</p>';
                                        ?>
                                        <div class="media">
                                            <div class="avatar-md mr-3 rounded-circle img-thumbnail">
                                                <span class="avatar-title rounded-circle bg-soft-<?php echo $color; ?> text-<?php echo $color; ?>" style="font-size: 40px;">
                                                    <i class="bx bxs-user-detail"></i>
                                                </span>
                                            </div>
                                            <div class="media-body align-self-center">
                                                <div class="text-muted">
                                                    <p class="mb-2">Subscription:</p>
                                                    <h5 class="mb-1"><?php echo $subscription_name; ?></h5>
                                                    <p class="mb-0"><?php echo $subscription_info; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 align-self-center">
                                        <div class="text-lg-center mt-4 mt-lg-0">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div>
                                                        <p class="text-muted text-truncate mb-2">Active Bots</p>
                                                        <h5 class="mb-0"><?php echo $active_bots;?></h5>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div>
                                                        <p class="text-muted text-truncate mb-2">Inactive Bots</p>
                                                        <h5 class="mb-0"><?php echo $inactive_bots;?></h5>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div>
                                                        <p class="text-muted text-truncate mb-2">Unused Bots</p>
                                                        <h5 class="mb-0">
                                                            <?php
                                                            if ($max_bots > 10000)
                                                                echo "Unlimited";
                                                            else
                                                                echo $max_bots-$active_bots-$inactive_bots;
                                                            ?>
                                                        </h5>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 d-lg-block">
                                        <div class="clearfix mt-4 mt-lg-0">
                                            <button type="button" class="btn btn-primary float-sm-right waves-effect btn-label waves-light" data-toggle="modal" data-target="#billingModal" data-backdrop="static" data-keyboard="false">
                                                <i class="bx bx-money label-icon"></i> <?php echo $call_to_action; ?>
                                            </button>

                                            <div class="modal fade" id="billingModal" tabindex="-1" role="dialog"
                                                 aria-labelledby="billingModalScrollableTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mt-0" id="billingModalScrollableTitle">
                                                                <?php echo $call_to_action; ?> Your Subscription</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="media align-self-center">
                                                                <div class="avatar-sd mr-3 rounded-circle img-thumbnail">
                                                                    <span class="avatar-title rounded-circle font-size-24 p-1 bg-soft-<?php echo $color; ?> text-<?php echo $color; ?>">
                                                                        <i class="bx bxs-user-detail"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="media-body align-self-center">
                                                                    <div class="text-muted">
                                                                        <h5 class="mb-1"><?php echo $subscription_name; ?></h5>
                                                                        <p class="mb-0"><?php echo $subscription_info; ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="mb-3 mt-0">
                                                            <p>Update your current subsctiption plan by selecting one of the available options in the form below.</strong></p>
                                                            <form method="post" action="billing.php" id="billingForm">
                                                                <div class="form-group row mb-4">
                                                                    <label for="subscription" class="col-sm-3 col-form-label">Subscription:</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control" id="subscription" name="subscription">
                                                                            <?php if ($subscription_name == "Starter" || $subscription_name == "Standard") { ?>
                                                                                <option value="Standard" data-price="19">Standard - 19 USD / Month</option>
                                                                            <?php } ?>
                                                                            <?php if ($subscription_name == "Starter" || $subscription_name == "Standard" || $subscription_name == "Enterprise") { ?>
                                                                                <option value="Enterprise" data-price="49">Enterprise - 49 USD / Month</option>
                                                                            <?php } ?>
                                                                            <option value="Unlimited" data-price="499">Unlimited - 499 USD / Month</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-4">
                                                                    <label for="time" class="col-sm-3 col-form-label">Time:</label>
                                                                    <div class="col-sm-9">
                                                                        <select class="form-control" id="time" name="time">
                                                                            <option value="1" data-percentoff="0">1 Month</option>
                                                                            <option value="3" data-percentoff="10">3 Months &nbsp;- 10% off</option>
                                                                            <option value="6" data-percentoff="15">6 Months &nbsp;- 15% off</option>
                                                                            <option value="12" data-percentoff="20">12 Months - 20% off</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <hr class="mb-4">

                                                                <div class="form-group row mb-0">
                                                                    <label for="time" class="col-sm-3 col-form-label">Total:</label>
                                                                    <div class="col-sm-9">
                                                                        <p class="pl-0 ml-0 font-size-24"><span id="total">0</span> USD <span class="text-success font-size-16" id="showOffer"></span></p>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row justify-content-end mb-0">
                                                                    <div class="col-sm-9">
                                                                        <div class="custom-control custom-checkbox mb-4">
                                                                            <input type="checkbox" class="custom-control-input" id="iagree">
                                                                            <label class="custom-control-label" for="iagree">I understand that cryptocurrency payments are non refundable</label>
                                                                            <span class="text-danger" id="iagree_err"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary" id="billingSubmit">Checkout</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->

                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Payment History</h4>
                                <p class="card-title-desc">This is your most recent activity.</p>

                                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Description</th>
                                        <th>Time</th>
                                        <th>Price</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // forwarding variables
                                    $aux = "";
                                    if ($graph_time) $aux = "&time=".$graph_time;

                                    $sql = 'SELECT * FROM payments WHERE user_id = '.$user_id;
                                    $result = mysqli_query($link, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td>P#<?php echo $row['id'];?></td>
                                                <td><?php echo $row['description'];?></td>
                                                <td><?php echo date('d M Y - H:i',strtotime($row['timestamp']));?></td>
                                                <td><?php echo $row['amount'] . ' ' . $row['currency'];?></td>
                                                <td><?php echo $row['type'];?></td>
                                                <td>
                                                    <?php if ($row['status'] == 'confirmed') { ?>
                                                        <span class="badge badge-pill badge-soft-success font-size-12"><?php echo ucfirst($row['status']); ?></span>
                                                    <?php } else if ($row['status'] == 'canceled' || $row['status'] == 'failed') { ?>
                                                        <span class="badge badge-pill badge-soft-danger font-size-12"><?php echo ucfirst($row['status']); ?></span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-pill badge-soft-warning font-size-12"><?php echo ucfirst($row['status']); ?></span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                    ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?php include 'layouts/footer.php'; ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- JAVASCRIPT -->
<?php include 'layouts/footer-scripts.php'; ?>

<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Required datatable js -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- Responsive examples -->
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<script src="assets/js/app.js"></script>

<script src="assets/js/main.js"></script>

</body>
</html>
<?php
mysqli_close($link);
?>
