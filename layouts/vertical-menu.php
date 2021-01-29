<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="dashboard.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo.svg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-dark.png" alt="" height="17">
                    </span>
                </a>

                <a href="dashboard.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-light.svg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="19">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">

            <?php if ($_SESSION['id']) { ?>
            <div class="dropdown d-inline-block">
                <?php
                $sql = 'SELECT * FROM notifications WHERE user_id = '.$user_id.' AND is_read = 0';
                $result = mysqli_query($link, $sql);
                ?>
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <i class="bx bx-bell bx-tada"></i>
                        <span class="badge badge-danger badge-pill"><?php echo mysqli_num_rows($result); ?></span>
                    <?php } else { ?>
                        <i class="bx bx-bell"></i>
                    <?php } ?>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0">Notifications</h6>
                            </div>
                            <div class="col-auto">
                                <a href="notifications.php" class="small">View All</a>
                            </div>
                        </div>
                    </div>
                    <?php if (mysqli_num_rows($result) > 0) {?>
                        <div data-simplebar style="max-height: 230px;">
                            <?php while ($row = mysqli_fetch_assoc($result)) {?>
                                <a href="<?php echo $row['url'];?>" class="text-reset notification-item">
                                    <div class="media">
                                        <div class="avatar-xs mr-3">
                                            <span class="avatar-title bg-<?php echo $row['type'];?> rounded-circle font-size-16">
                                                <i class="bx bx-<?php echo $row['icon'];?>"></i>
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-1"><?php echo $row['title'];?></h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1"><?php echo $row['description'];?></p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <?php echo date('d M Y - H:i', strtotime($row['timestamp']));?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="p-2 border-top">
                            <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="notifications.php?mark_as_read=true">
                                <i class="mdi mdi-email-open-multiple mr-1"></i> Mark as read
                            </a>
                        </div>
                    <?php } else { ?>
                        <div data-simplebar style="max-height: 230px;">
                            <a href="javascript: void(0);" class="text-reset notification-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-1">You don't have any new notifications.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-inline-block ml-1"><?php echo ucfirst($_SESSION["username"]); ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item d-block" href="settings.php"><i class="bx bx-wrench font-size-16 align-middle mr-1"></i>Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="logout.php"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> Logout</a>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
</header> <!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title"><?php echo $language["Menu"]; ?></li>


                <li>
                    <a href="dashboard.php" class=" waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="bots.php" class="waves-effect">
                        <i class="bx bx-bot"></i>
                        <span>Bots</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="bots.php">Show all</a></li>
                        <li><a href="bots-add.php">Add new</a></li>
                    </ul>
                </li>
                <li>
                    <a href="billing.php" class="waves-effect">
                        <i class="bx bx-money"></i>
                        <span>Billing</span>
                    </a>
                </li>
                <li>
                    <a href="notifications.php" class="waves-effect">
                        <i class="bx bx-bell"></i>
                        <span>Notifications</span>
                        <?php if (mysqli_num_rows($result) > 0) {?>
                            <span class="badge badge-pill badge-danger float-right"><?php echo mysqli_num_rows($result); ?></span>
                        <?php } ?>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
