<?php include 'layouts/session.php'; ?>
<?php
require_once "config.php";
$email = $subscription_name = $graph_time = "";
$subscription_end_timestamp = $max_bots = $active_bots = $inactive_bots = 0;
$user_id = mysqli_real_escape_string($link, $_SESSION['id']);

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
$sql = 'SELECT status, count(*) as c FROM bots WHERE is_deleted = 0 AND user_id = '.$user_id.' GROUP BY status';
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['status']==1)
            $active_bots = $row['c'];
        else
            $inactive_bots = $row['c'];
    }
}

// select graph info
$graph_labels = $graph_active_bots = $graph_total_transactions = $graph_profits = [];
$graph_time = $_GET['time'];
$graph_where = "WHERE bot_id IN ( SELECT id FROM bots WHERE user_id = ".$user_id." )";
if (is_numeric($_GET['bot']))
    $graph_where = "WHERE bot_id = ".mysqli_real_escape_string($link, $_GET['bot']);

$sql = "SELECT DATE_FORMAT(timestamp, '%Y %m %d') as olabel, DATE_FORMAT(timestamp, '%d %b') as label, COUNT(id) as total_transactions, COUNT(DISTINCT bot_id) as active_bots, SUM(transaction_profit) as profit FROM transactions ".$graph_where." GROUP BY DAY(timestamp) ORDER BY olabel LIMIT 0,14";

if ($graph_time == "months")
    $sql = "SELECT DATE_FORMAT(timestamp, '%Y %m %d') as olabel, DATE_FORMAT(timestamp, '%b') as label, COUNT(id) as total_transactions, COUNT(DISTINCT bot_id) as active_bots, SUM(transaction_profit) as profit FROM transactions ".$graph_where." GROUP BY MONTH(timestamp) ORDER BY olabel LIMIT 0,12";
else if ($graph_time == "years")
    $sql = "SELECT DATE_FORMAT(timestamp, '%Y %m %d') as olabel, DATE_FORMAT(timestamp, '%Y') as label, COUNT(id) as total_transactions, COUNT(DISTINCT bot_id) as active_bots, SUM(transaction_profit) as profit FROM transactions ".$graph_where." GROUP BY YEAR(timestamp) ORDER BY olabel";

$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $graph_labels[] = $row['label'];
        $graph_total_transactions[] = $row['total_transactions'];
        $graph_active_bots[] = $row['active_bots'];
        $graph_profits[] = $row['profit'];
    }
}
?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Dashboard | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
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
                            <h4 class="mb-0 font-size-18">Dashboard</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/">Pitman Bot</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div>
                                        <h5><?php echo $_SESSION["username"]; ?></h5>
                                        <p class="text-muted mb-1"><?php echo $email; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div>
                                            <div id="bots_piechart_options" class="apex-charts"></div>
                                            <script type="text/javascript">
                                                // Pie chart
                                                var bots_piechart_options = {
                                                    series: [<?php echo $active_bots;?>, <?php echo $inactive_bots;?><?php if ($max_bots <= 500) echo ", ".($max_bots-$active_bots-$inactive_bots);?>],
                                                    chart: {
                                                        type: 'donut',
                                                        height: 200,
                                                    },
                                                    labels: ['Active', 'Inactive', 'Unused'],
                                                    colors: ['#34c38f', '#f46a6a', '#556ee6'],
                                                    legend: {
                                                        show: false,
                                                    },
                                                    dataLabels: {
                                                        enabled: false
                                                    },
                                                    plotOptions: {
                                                        pie: {
                                                            donut: {
                                                                size: '70%',
                                                            }
                                                        }
                                                    }
                                                };
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mt-4 mt-sm-0">
                                            <p class="font-weight-medium mb-2">Subscription :</p>
                                            <h4><?php echo $subscription_name; ?></h4>
                                            <?php
                                            $call_to_action = "Upgrade";
                                            if (time()>$subscription_end_timestamp) {
                                                $call_to_action = "Renew";
                                                ?>
                                                <p>Expired</p>
                                            <?php } else { ?>
                                                <p>Expires on: <?php echo date("d M Y",$subscription_end_timestamp); ?></p>
                                            <?php } ?>
                                            <p class="">
                                                <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-success mr-1"></i> <strong><?php echo $active_bots;?></strong>  Active</p>
                                                <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-danger mr-1"></i> <strong><?php echo $inactive_bots;?></strong>  Inactive</p>
                                                <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-primary mr-1"></i>
                                                    <strong>
                                                        <?php
                                                        if ($max_bots > 10000)
                                                            echo "Unlimited";
                                                        else
                                                            echo $max_bots-$active_bots-$inactive_bots;
                                                        ?>
                                                    </strong>
                                                 Unused</p>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-top">
                                <div class="text-center">
                                    <a href="bots.php" class="btn btn-outline-light mr-2 w-md">View bots</a>
                                    <a href="billing.php" class="btn btn-primary mr-2 w-md"><?php echo $call_to_action; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-body">
                                <script type="text/javascript">
                                var stacked_column_options = {
                                    chart: {
                                        height: 276,
                                        type: 'bar',
                                        stacked: true,
                                        toolbar: {
                                            show: false
                                        },
                                        zoom: {
                                            enabled: true
                                        }
                                    },

                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: '15%'
                                        },
                                    },

                                    dataLabels: {
                                        enabled: false
                                    },
                                    series: [{
                                        name: 'Active Bots',
                                        data: <?php echo json_encode($graph_active_bots);?>
                                    },{
                                        name: 'Transactions',
                                        data: <?php echo json_encode($graph_total_transactions);?>
                                    },{
                                        name: 'Profit',
                                        data: <?php echo json_encode($graph_profits);?>
                                    }],
                                    xaxis: {
                                        categories: <?php echo json_encode($graph_labels);?>,
                                    },
                                    colors: ['#f1b44c', '#556ee6', '#34c38f'],
                                    legend: {
                                        position: 'bottom',
                                    },
                                    fill: {
                                        opacity: 1
                                    },
                                }
                                </script>
                                <?php
                                // forwarding variables with aux
                                $aux = "bot=all";
                                $description = "<p>Showing stats for all your bots</p>";
                                if (is_numeric($_GET['bot'])) {
                                    $aux = "bot=".$_GET['bot'];
                                    $description = '<p>Showing stats for bot <a href="dashboard.php" class="btn btn-primary btn-sm">B#'.$_GET['bot'].' <i class="mdi mdi-close ml-1"></i></a></p>';
                                }
                                ?>
                                <div class="float-sm-right">
                                    <ul class="nav nav-pills mb-4 mb-lg-0">
                                        <li class="nav-item">
                                            <a class="nav-link<?php if ($graph_time == "years") echo " active"; ?>" href="dashboard.php?<?php echo $aux."&"; ?>time=years">Years</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link<?php if ($graph_time == "months") echo " active"; ?>" href="dashboard.php?<?php echo $aux."&"; ?>time=months">Months</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link<?php if ($graph_time == "") echo " active"; ?>" href="dashboard.php?<?php echo $aux; ?>">Days</a>
                                        </li>
                                    </ul>
                                </div>
                                <h5 class="text-primary">Analytics</h5>
                                <?php echo $description;?>
                                <div class="clearfix"></div>
                                <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Transactions</h4>
                                <p class="card-title-desc">This is your most recent activity.</p>

                                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Bot ID</th>
                                        <th>Time</th>
                                        <th>Details</th>
                                        <th>Exchange</th>
                                        <th>Pair</th>
                                        <th>Amount</th>
                                        <th>Price</th>
                                        <th>P/L</th>
                                        <th>P/L %</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // forwarding variables
                                    $aux = "";
                                    if ($graph_time) $aux = "&time=".$graph_time;

                                    $sql = 'SELECT * FROM transactions WHERE bot_id IN ( SELECT id FROM bots WHERE user_id = '.$user_id.' )';
                                    $result = mysqli_query($link, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td>T#<?php echo $row['id'];?></td>
                                                <td><a href="dashboard.php?bot=<?php echo $row['bot_id'].$aux; ?>">B#<?php echo $row['bot_id'];?></a></td>
                                                <td><?php echo date('d M Y - H:i',strtotime($row['timestamp']));?></td>
                                                <td><?php echo $row['transaction_message'];?></td>
                                                <td><?php echo $row['transaction_exchange'];?></td>
                                                <td><?php echo $row['transaction_pair'];?></td>
                                                <td><?php echo $row['transaction_amount'];?></td>
                                                <td>$<?php echo $row['transaction_price'];?></td>
                                                <td>$<?php echo $row['transaction_profit'];?></td>
                                                <td><?php echo $row['transaction_profit_percentage'];?>%</td>
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
