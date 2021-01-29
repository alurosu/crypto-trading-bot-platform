<?php include 'layouts/session.php'; ?>
<?php
require_once "config.php";
$user_id = mysqli_real_escape_string($link, $_SESSION['id']);
$mark_as_read = "";
if ($_GET['mark_as_read']=='true') {
    $sql = 'UPDATE notifications SET is_read = 1 WHERE user_id = '.$user_id;
    $result = mysqli_query($link, $sql);
    $mark_as_read = "You successfully marked all notifications as read.";
}
?>
<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Notifications | Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
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
                            <h4 class="mb-0 font-size-18">Notifications</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="/">Pitman Bot</a></li>
                                    <li class="breadcrumb-item active">Notifications</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <?php if ($mark_as_read) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all mr-2"></i>
                        <?php echo $mark_as_read; ?>
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
                                    <ul class="nav mb-4 mb-lg-0">
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-primary mr-2 w-md" href="notifications.php?mark_as_read=true"><i class="mdi mdi-email-open-multiple mr-1"></i>Mark All As Read</a>
                                        </li>
                                    </ul>
                                </div>
                                <h4 class="card-title">Overview</h4>
                                <p class="card-title-desc">The description of each notification is a link with more info.</p>
                                <div class="clearfix"></div>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                       style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Time</th>
                                        <th style="width: 100%;">Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = 'SELECT * FROM notifications WHERE user_id = '.$user_id;
                                    $result = mysqli_query($link, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td>
                                                    <div class="avatar-xs mr-3">
                                                        <span class="avatar-title bg-<?php echo $row['type'];?> rounded-circle font-size-16">
                                                            <i class="bx bx-<?php echo $row['icon'];?>"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td><?php echo $row['title'];?></td>
                                                <td><?php echo date('d M Y - H:i',strtotime($row['timestamp']));?></td>
                                                <td><a href="<?php echo $row['url'];?>" target="_blank"><?php echo $row['description'];?></a></td>
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
