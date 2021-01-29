<?php
include 'layouts/session.php';
require_once "config.php";
$user_id = mysqli_real_escape_string($link, $_SESSION['id']);

$subscription_end_timestamp = "";
$max_bots = $used_bots = 0;
// check if subscription expanded
$sql = 'SELECT subscription_end_timestamp, max_bots FROM users WHERE id = '.$user_id;
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $subscription_end_timestamp = $row["subscription_end_timestamp"];
    $max_bots = $row["max_bots"];
}

// check if all bot slots are used
$sql = 'SELECT count(*) as c FROM bots WHERE is_deleted = 0 AND  user_id = '.$user_id;
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $used_bots = $row['c'];
}

$success = "";
$exchange_err = $exchange = $trading_pair_err = $trading_pair = $transaction_limit_err = $transaction_limit = $timeframe_err = $timeframe = $total_limit_err = $total_limit = $api_key_err = $api_key = $api_secret_err = $api_secret = $iagree_err = $iagree2_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate exchange
    if(empty(trim($_POST["exchange"]))){
        $exchange_err = "Please select an exchange.";
    } else {
        $exchange = mysqli_real_escape_string($link, trim($_POST["exchange"]));
    }

    // Validate trading_pair
    if(empty(trim($_POST["trading_pair"]))){
        $trading_pair_err = "Please enter a trading pair.";
    } else {
        $trading_pair = mysqli_real_escape_string($link, trim($_POST["trading_pair"]));
    }

    // Validate transaction_limit
    if(!is_numeric($_POST["transaction_limit"])){
        $transaction_limit_err = "Please set a numeric transaction limit.";
    } else {
        $transaction_limit = $_POST["transaction_limit"];
    }

    // Validate timeframe
    if(empty(trim($_POST["timeframe"]))){
        $timeframe_err = "Please enter a time frame.";
    } else {
        $timeframe = mysqli_real_escape_string($link, trim($_POST["timeframe"]));
    }

    // Validate total_limit
    if(!is_numeric($_POST["total_limit"])){
        $total_limit_err = "Please set a numeric total limit.";
    } else {
        $total_limit = $_POST["total_limit"];
    }

    // Validate api_key
    if(empty(trim($_POST["api_key"]))){
        $api_key_err = "Please enter an exchange API key.";
    } else {
        $api_key = mysqli_real_escape_string($link, trim($_POST["api_key"]));
    }

    // Validate api_secret
    if(empty(trim($_POST["api_secret"]))){
        $api_secret_err = "Please enter the API secret for your API key.";
    } else {
        $api_secret = mysqli_real_escape_string($link, trim($_POST["api_secret"]));
    }

    // Validate iagree_err
    if(!isset($_POST["iagree"])){
        $iagree_err = "This field is required.";
    }

    // Validate iagree2_err
    if(!isset($_POST["iagree2"])){
        $iagree2_err = "This field is required.";
    }

    // Check input errors before update
    if(empty($exchange_err) && empty($trading_pair_err) && empty($transaction_limit_err) && empty($timeframe_err) && empty($total_limit_err) && empty($api_key_err) && empty($api_secret_err) && empty($iagree_err) && empty($iagree2_err)){
        $sql = 'INSERT INTO `bots`(`user_id`, `exchange_name`, `exchange_key`, `exchange_secret`, `transaction_pair`, `transaction_timeframe`, `transaction_limit`, `transaction_limit_total`) VALUES ("'.$user_id.'", "'.$exchange.'", "'.$api_key.'", "'.$api_secret.'", "'.$trading_pair.'", "'.$timeframe.'", "'.$transaction_limit.'", "'.$total_limit.'")';

        $result = mysqli_query($link, $sql);
        $success = "You have successfully added a new bot.";
        $exchange_err = $exchange = $trading_pair_err = $trading_pair = $transaction_limit_err = $transaction_limit = $timeframe_err = $timeframe = $total_limit_err = $total_limit = $api_key_err = $api_key = $api_secret_err = $api_secret = $iagree_err = $iagree2_err = "";
    }

    // Close connection
    mysqli_close($link);
}

include 'layouts/head-main.php';
?>
<head>
    <title>Add Bot | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
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
                            <h4 class="mb-0 font-size-18">Add Bot</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/">Pitman Bot</a></li>
                                    <li class="breadcrumb-item active">Bots</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <?php if($success){ ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-check-all mr-2"></i>
                                        <?php echo $success; ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                <?php }

                                if( $subscription_end_timestamp <= time() ) { ?>
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-alert-outline mr-2"></i>
                                        Your subscription has ended. <a href="billing.php">Renew Now</a>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                <?php } elseif( $used_bots == $max_bots ) { ?>
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <i class="mdi mdi-alert-outline mr-2"></i>
                                        Your subscription allows you to use a total of <b> <?= $max_bots ?> </b> bots. <a href="billing.php">Upgrade Now</a>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                <?php } else { ?>
                                    <h4 class="card-title">Bot Setup</h4>
                                    <p class="card-title-desc">Configure multiple bots on the same exchange and they will work together increasing your profits. <a href="get-started.php">Learn more</a> </p>

                                    <form method="post">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group <?php echo (!empty($exchange_err)) ? 'has-error' : ''; ?>">
                                                    <label class="control-label">Exchange</label>
                                                    <select class="form-control" name="exchange">
                                                        <option value="">Select</option>
                                                        <option value="bitfinex" <?php if ($exchange == 'bitfinex') echo 'selected="selected"'; ?>>Bitfinex</option>
                                                        <option value="binance" <?php if ($exchange == 'binance') echo 'selected="selected"'; ?>>Binance</option>
                                                        <option value="kraken" <?php if ($exchange == 'kraken') echo 'selected="selected"'; ?>>Kraken</option>
                                                    </select>
                                                    <span class="text-danger"><?php echo $exchange_err; ?></span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group <?php echo (!empty($trading_pair_err)) ? 'has-error' : ''; ?>">
                                                            <label for="price">Trading Pair</label>
                                                            <input class="form-control" type="text" placeholder="ETHUSD" name="trading_pair" value="<?php echo $trading_pair; ?>">
                                                            <span class="text-danger"><?php echo $trading_pair_err; ?></span>
                                                        </div>
                                                        <div class="form-group <?php echo (!empty($transaction_limit_err)) ? 'has-error' : ''; ?>">
                                                            <label for="price">Transaction Limit</label>
                                                            <input class="form-control" type="number" placeholder="10" name="transaction_limit"  value="<?php echo $transaction_limit; ?>">
                                                            <span class="text-danger"><?php echo $transaction_limit_err; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group <?php echo (!empty($timeframe_err)) ? 'has-error' : ''; ?>">
                                                            <label class="control-label">Timeframe</label>
                                                            <input class="form-control" type="text" placeholder="1H" name="timeframe"  value="<?php echo $timeframe; ?>">
                                                            <span class="text-danger"><?php echo $timeframe_err; ?></span>
                                                        </div>
                                                        <div class="form-group <?php echo (!empty($total_limit_err)) ? 'has-error' : ''; ?>">
                                                            <label for="price">Total Limit</label>
                                                            <input class="form-control" type="number" placeholder="100" name="total_limit"  value="<?php echo $total_limit; ?>">
                                                            <span class="text-danger"><?php echo $total_limit_err; ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group <?php echo (!empty($api_key_err)) ? 'has-error' : ''; ?>">
                                                    <label for="productdesc">API key</label>
                                                    <textarea class="form-control" rows="4" name="api_key"><?php echo $api_key; ?></textarea>
                                                    <span class="text-danger"><?php echo $api_key_err; ?></span>
                                                </div>
                                                <div class="form-group <?php echo (!empty($api_secret_err)) ? 'has-error' : ''; ?>">
                                                    <label for="productdesc">API secret</label>
                                                    <textarea class="form-control" rows="4" name="api_secret"><?php echo $api_secret; ?></textarea>
                                                    <span class="text-danger"><?php echo $api_secret_err; ?></span>
                                                </div>

                                                <div class="form-group <?php echo (!empty($iagree_err)) ? 'has-error' : ''; ?>">
                                                    <div class="custom-control mb-3 custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="iagree" name="iagree">
                                                        <label class="custom-control-label" for="iagree">
                                                            I hereby understand and acknowledge the risk factors related to trading. I recognize that the cryptocurrency market is highly volatile and involves a high degree of risk, including a risk of total loss of the investment.
                                                        </label>
                                                    </div>
                                                    <span class="text-danger"><?php echo $iagree_err; ?></span>
                                                </div>
                                                <div class="form-group <?php echo (!empty($iagree2_err)) ? 'has-error' : ''; ?>">
                                                    <div class="custom-control mb-3 custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="iagree2" name="iagree2">
                                                        <label class="custom-control-label" for="iagree2">
                                                            I understand that Pitman Bot is not resposible and cannot be held accountable for any financial losses.
                                                        </label>
                                                    </div>
                                                </div>
                                                <span class="text-danger"><?php echo $iagree2_err; ?></span>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary mr-1 mt-3 waves-effect waves-light">Add Bot</button>
                                    </form>
                                <?php } ?>
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

<!-- JAVASCRIPT -->
<?php include 'layouts/footer-scripts.php'; ?>

<!-- App js -->
<script src="assets/js/app.js"></script>

</body>
</html>
