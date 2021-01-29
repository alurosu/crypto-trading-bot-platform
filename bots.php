<?php
include 'layouts/session.php';
require_once "config.php";
$user_id = mysqli_real_escape_string($link, $_SESSION['id']);

include 'layouts/head-main.php'; ?>
<head>
    <title>Bot List | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
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
                            <h4 class="mb-0 font-size-18">Bot List</h4>

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
                <?php
                if ($_GET['action'] == "start") {
                    // check if subscription expired
                    $subscription_end_timestamp = 0;
                    // select user info
                    $sql = 'SELECT subscription_end_timestamp FROM users WHERE id = '.$user_id;
                    $result = mysqli_query($link, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $subscription_end_timestamp = $row["subscription_end_timestamp"];
                    }
                    if (time()>$subscription_end_timestamp) { ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-cash mr-2"></i>
                             Can't start any bots. Your subscription expired. <a href="billing.php">Renew</a>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php } else {
                        $sql = 'UPDATE bots SET status = 1 WHERE user_id = '.$user_id;
                        $start_notification = "You started all your configured bots.";
                        if (is_numeric($_GET['id'])) {
                            $sql = 'UPDATE bots SET status = 1 WHERE id = '.mysqli_real_escape_string($link, $_GET['id']).' AND user_id = '.$user_id;
                            $start_notification = "You started Bot #".$_GET['id'].".";
                        }
                        $result = mysqli_query($link, $sql);
                        ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-play mr-2"></i>
                            <?php echo $start_notification; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                <?php }
                } else if ($_GET['action'] == "stop") {
                    $sql = 'UPDATE bots SET status = 0 WHERE user_id = '.$user_id;
                    $stop_notification = "You stopped all your bots.";
                    if (is_numeric($_GET['id'])) {
                        $sql = 'UPDATE bots SET status = 0 WHERE id = '.mysqli_real_escape_string($link, $_GET['id']).' AND user_id = '.$user_id;
                        $stop_notification = "You stopped Bot #".$_GET['id'].".";
                    }
                    $result = mysqli_query($link, $sql);
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-stop mr-2"></i>
                        <?php echo $stop_notification; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } else if ($_GET['action'] == "delete" && is_numeric($_GET['id'])) {
                    $sql = 'UPDATE bots SET is_deleted = 1, status = 0 WHERE id = '.mysqli_real_escape_string($link, $_GET['id']).' AND user_id = '.$user_id;
                    $result = mysqli_query($link, $sql);
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-trash-can mr-2"></i>
                        You deleted Bot #<?php echo $_GET['id'];?>.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-sm-right">
                                    <ul class="nav mb-2 mb-lg-0">
                                        <li class="nav-item mb-2 mb-lg-0">
                                            <a class="nav-link btn btn-outline-light mr-2 w-md" href="bots.php?action=start&id=all"><i class="mdi mdi-play text-success mr-1"></i>Start All</a>
                                        </li>
                                        <li class="nav-item mb-2 mb-lg-0">
                                            <a class="nav-link btn btn-outline-light mr-2 w-md" href="bots.php?action=stop&id=all"><i class="mdi mdi-stop text-danger mr-1"></i>Stop All</a>
                                        </li>
                                        <li class="nav-item mb-2 mb-lg-0">
                                            <a class="nav-link btn btn-primary mr-2 w-md" href="bots-add.php"><i class="mdi mdi-plus mr-1"></i>Add Bot</a>
                                        </li>
                                    </ul>
                                </div>
                                <h4 class="card-title">Overview</h4>
                                <p class="card-title-desc">Inactive bots are shown at the top.</p>
                                <div class="clearfix"></div>

                                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Bot ID</th>
                                        <th style="width: 50%;">Details</th>
                                        <th>Status</th>
                                        <th>Timeframe</th>
                                        <th>Transaction Limit</th>
                                        <th>Total Limit</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = 'SELECT * FROM bots WHERE is_deleted = 0 AND user_id = '.$user_id;
                                    $result = mysqli_query($link, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td><a href="dashboard.php?bot=<?php echo $row['id']; ?>">B#<?php echo $row['id'];?></a></td>
                                                <td><?php echo ucfirst($row['exchange_name'])." - ".$row['transaction_pair'];?></td>
                                                <td>
                                                    <?php if ($row['status'] == 0) { ?>
                                                        <span class="badge badge-pill badge-soft-danger font-size-12">Stopped</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-pill badge-soft-success font-size-12">Running</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $row['transaction_timeframe'];?></td>
                                                <td><?php echo $row['transaction_limit'];?></td>
                                                <td><?php echo $row['transaction_limit_total'];?></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="">
                                                            <?php if ($row['status'] == 0) { ?>
                                                                <li><a href="bots.php?action=start&id=<?php echo $row['id'];?>" class="dropdown-item"><i class="mdi mdi-play font-size-16 text-success mr-1"></i>
                                                                        Start</a></li>
                                                            <?php } else { ?>
                                                                <li><a href="bots.php?action=stop&id=<?php echo $row['id'];?>" class="dropdown-item"><i class="mdi mdi-stop font-size-16 text-warning mr-1"></i>
                                                                        Stop</a></li>
                                                            <?php } ?>
                                                            <li><a href="bots-edit.php?id=<?php echo $row['id'];?>" class="dropdown-item"><i class="mdi mdi-pencil font-size-16 text-info mr-1"></i>
                                                                    Edit</a></li>
                                                            <li><a href="bots.php?action=delete&id=<?php echo $row['id'];?>" class="dropdown-item"><i class="mdi mdi-trash-can font-size-16 text-danger mr-1"></i>
                                                                    Delete</a></li>
                                                        </ul>
                                                    </div>
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
