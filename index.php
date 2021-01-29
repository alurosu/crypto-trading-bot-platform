<?php
// Include config file
require_once "config.php";
$total_transactions = $total_traded_value = $total_profit = $total_users = $total_bots = 0;

$sql = 'SELECT option_value FROM website WHERE option_name = "total_transactions"';
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total_transactions = $row["option_value"];
}

$sql = 'SELECT option_value FROM website WHERE option_name = "total_traded_value"';
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total_traded_value = $row["option_value"];
}

$sql = 'SELECT option_value FROM website WHERE option_name = "total_profit"';
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total_profit = $row["option_value"];
}

$sql = 'SELECT count(id) as total_users FROM users';
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total_users = $row["total_users"];
}

$sql = 'SELECT count(id) as total_bots FROM bots';
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $total_bots = $row["total_bots"];
}

// Close connection
mysqli_close($link);
?>

<?php include 'layouts/head-main.php'; ?>
<head>
    <title>Pitman Bot - Automated Cryptocurrency Tranding Bot</title>
    <?php include 'layouts/head.php'; ?>
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="assets/libs/owl.carousel/assets/owl.carousel.min.css">

    <link rel="stylesheet" href="assets/libs/owl.carousel/assets/owl.theme.default.min.css">
    <?php include 'layouts/head-style.php'; ?>
</head>

<body data-spy="scroll" data-target="#topnav-menu" data-offset="60">

<nav class="navbar navbar-expand-lg navigation fixed-top sticky">
    <div class="container">
        <a class="navbar-logo" href="/">
            <img src="assets/images/logo-dark.png" alt="" height="19" class="logo logo-dark">
            <img src="assets/images/logo-light.png" alt="" height="19" class="logo logo-light">
        </a>

        <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                data-toggle="collapse" data-target="#topnav-menu-content">
            <i class="fa fa-fw fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="topnav-menu-content">
            <ul class="navbar-nav ml-auto" id="topnav-menu">
                <li class="nav-item">
                    <a class="nav-link active" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#pricing">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#faqs">FAQs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
            </ul>

            <div class="ml-lg-2 mb-2 mb-lg-0">
                <a href="dashboard.php" class="btn btn-outline-success w-xs">Sign in</a>
            </div>
        </div>
    </div>
</nav>

<!-- hero section start -->
<section class="section hero-section bg-ico-hero" id="home">
    <div class="bg-overlay bg-primary"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="text-white-50">
                    <h1 class="text-white font-weight-semibold mb-3 hero-title">Automated Cryptocurrency Trading Bot</h1>
                    <p class="font-size-14">Use a smart and self-trading bot that will make money based on market trends.</p>
                    <p class="font-size-14">It's easy to set up and we'll give you 3 months free trial.</p>

                    <div class="button-items mt-4">
                        <a href="auth-register.php" class="btn btn-success">Get Started</a>
                        <a href="#about" class="smooth-scroll btn btn-light">Learn more</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-8 col-sm-10 ml-lg-auto pt-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h5 class="mb-0">ETH-USD simulation</h5>
                    </div>
                    <div class="card-body">
                        <div>

                            <div id="overview-chart" class="apex-charts" dir="ltr">
                                <div class="toolbar button-items text-center">
                                    <button type="button" class="btn btn-light btn-sm" id="one_month">
                                        1M
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm" id="six_months">
                                        6M
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm active" id="one_year">
                                        1Y
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm" id="all">
                                        ALL
                                    </button>
                                </div>
                                <div id="overview-chart-timeline"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- hero section end -->

<!-- currency price section start -->
<section class="section bg-white p-0">
    <div class="container">
        <div class="currency-price">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18">
                                        <i class="bx bx-copy-alt"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <p class="text-muted">Transactions</p>
                                    <h5><?php echo $total_transactions; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18">
                                        <i class="bx bx-archive-in"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <p class="text-muted">Traded Value</p>
                                    <h5>$ <?php echo $total_traded_value; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18">
                                        <i class="bx bx-purchase-tag-alt"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <p class="text-muted">Total Profit</p>
                                    <h5>$ <?php echo $total_profit; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end container -->
</section>
<!-- currency price section end -->

<!-- about section start -->
<section class="section pt-4 bg-white" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <div class="small-title">About us</div>
                    <h4>What is Pitman bot?</h4>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-5">

                <div class="text-muted">
                    <h4>Automated Cryptocurrency bot</h4>
                    <p>If several languages coalesce, the grammar of the resulting that of the individual new common
                        language will be more simple and regular than the existing.</p>
                    <p class="mb-4">It would be necessary to have uniform pronunciation.</p>

                    <div class="button-items">
                        <a href="auth-register.php" class="btn btn-success">Sign Up</a>
                        <a href="#faqs" class="smooth-scroll btn btn-outline-primary">How It Works</a>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-4 col-6">
                            <div class="mt-4">
                                <h4><?php echo $total_users; ?></h4>
                                <p>Accounts</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="mt-4">
                                <h4><?php echo $total_bots; ?></h4>
                                <p>Bots</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 ml-auto">
                <div class="mt-4 mt-lg-0">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="mdi mdi-bitcoin h2 text-success"></i>
                                    </div>
                                    <h5>Lending</h5>
                                    <p class="text-muted mb-0">At vero eos et accusamus et iusto blanditiis</p>

                                </div>
                                <div class="card-footer bg-transparent border-top text-center">
                                    <a href="#" class="text-primary">Learn more</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card border mt-lg-5">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="mdi mdi-wallet-outline h2 text-success"></i>
                                    </div>
                                    <h5>Wallet</h5>
                                    <p class="text-muted mb-0">Quis autem vel eum iure reprehenderit</p>

                                </div>
                                <div class="card-footer bg-transparent border-top text-center">
                                    <a href="#" class="text-primary">Learn more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <hr class="my-5">

        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel owl-theme clients-carousel" id="clients-carousel">
                    <div class="item">
                        <div class="client-images">
                            <img src="assets/images/clients/bitfinex.png" alt="client-img" class="mx-auto img-fluid d-block">
                        </div>
                    </div>
                    <div class="item">
                        <div class="client-images">
                            <img src="assets/images/clients/2.png" alt="client-img" class="mx-auto img-fluid d-block">
                        </div>
                    </div>
                    <div class="item">
                        <div class="client-images">
                            <img src="assets/images/clients/3.png" alt="client-img" class="mx-auto img-fluid d-block">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- about section end -->

<!-- Features start -->
<section class="section" id="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <div class="small-title">Features</div>
                    <h4>Key features of the product</h4>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row align-items-center pt-4">
            <div class="col-md-6 col-sm-8">
                <div>
                    <img src="assets/images/crypto/features-img/img-1.png" alt="" class="img-fluid mx-auto d-block">
                </div>
            </div>
            <div class="col-md-5 ml-auto">
                <div class="mt-4 mt-md-auto">
                    <div class="d-flex align-items-center mb-2">
                        <div class="features-number font-weight-semibold display-4 mr-3">01</div>
                        <h4 class="mb-0">Lending</h4>
                    </div>
                    <p class="text-muted">If several languages coalesce, the grammar of the resulting language is more
                        simple and regular than of the individual will be more simple and regular than the existing.</p>
                    <div class="text-muted mt-4">
                        <p class="mb-2"><i class="mdi mdi-circle-medium text-success mr-1"></i>Donec pede justo vel
                            aliquet</p>
                        <p><i class="mdi mdi-circle-medium text-success mr-1"></i>Aenean et nisl sagittis</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row align-items-center mt-5 pt-md-5">
            <div class="col-md-5">
                <div class="mt-4 mt-md-0">
                    <div class="d-flex align-items-center mb-2">
                        <div class="features-number font-weight-semibold display-4 mr-3">02</div>
                        <h4 class="mb-0">Wallet</h4>
                    </div>
                    <p class="text-muted">It will be as simple as Occidental; in fact, it will be Occidental. To an
                        English person, it will seem like simplified English, as a skeptical Cambridge friend.</p>
                    <div class="text-muted mt-4">
                        <p class="mb-2"><i class="mdi mdi-circle-medium text-success mr-1"></i>Donec pede justo vel
                            aliquet</p>
                        <p><i class="mdi mdi-circle-medium text-success mr-1"></i>Aenean et nisl sagittis</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6  col-sm-8 ml-md-auto">
                <div class="mt-4 mr-md-0">
                    <img src="assets/images/crypto/features-img/img-2.png" alt="" class="img-fluid mx-auto d-block">
                </div>
            </div>

        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- Features end -->

<!-- Pricing start -->
<section class="section" id="pricing">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <div class="small-title">Pricing</div>
                    <h4>We have deals for everyone</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card plan-box">
                    <div class="card-body p-4">
                        <div class="media">
                            <div class="media-body">
                                <h5>Starter</h5>
                                <p class="text-muted">Test it out</p>
                            </div>
                            <div class="ml-3">
                                <i class="bx bx-walk h1 text-primary"></i>
                            </div>
                        </div>
                        <div class="py-4">
                            <h2></sup> FREE <span class="font-size-13">first 3 months</span></h2>
                        </div>
                        <div class="text-center plan-btn">
                            <a href="auth-register.php" class="btn btn-primary btn-sm waves-effect waves-light">Sign up Now</a>
                        </div>

                        <div class="plan-features mt-5">
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> 5 Bots</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Use with any Trading Pair</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Use with any Exchange</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Unlimited Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card plan-box">
                    <div class="card-body p-4">
                        <div class="media">
                            <div class="media-body">
                                <h5>Standard</h5>
                                <p class="text-muted">Basic users</p>
                            </div>
                            <div class="ml-3">
                                <i class="bx bx-run h1 text-primary"></i>
                            </div>
                        </div>
                        <div class="py-4">
                            <h2><sup><small>$</small></sup> 19/ <span class="font-size-13">Per month</span></h2>
                        </div>
                        <div class="text-center plan-btn">
                            <a href="auth-register.php" class="btn btn-primary btn-sm waves-effect waves-light">Sign up Now</a>
                        </div>

                        <div class="plan-features mt-5">
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> 20 Bots</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Use with any Trading Pair</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Multiple Exchanges</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Unlimited Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card plan-box">
                    <div class="card-body p-4">
                        <div class="media">
                            <div class="media-body">
                                <h5>Enterprise</h5>
                                <p class="text-muted">Advanced users</p>
                            </div>
                            <div class="ml-3">
                                <i class="bx bx-cycling h1 text-primary"></i>
                            </div>
                        </div>
                        <div class="py-4">
                            <h2><sup><small>$</small></sup> 49/ <span class="font-size-13">Per month</span></h2>
                        </div>
                        <div class="text-center plan-btn">
                            <a href="auth-register.php" class="btn btn-primary btn-sm waves-effect waves-light">Sign up Now</a>
                        </div>

                        <div class="plan-features mt-5">
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> 100 Bots</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Use with any Trading Pair</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Multiple Exchanges</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Unlimited Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card plan-box">
                    <div class="card-body p-4">
                        <div class="media">
                            <div class="media-body">
                                <h5>Unlimited</h5>
                                <p class="text-muted">Take it to the max</p>
                            </div>
                            <div class="ml-3">
                                <i class="bx bx-car h1 text-primary"></i>
                            </div>
                        </div>
                        <div class="py-4">
                            <h2><sup><small>$</small></sup> 499/ <span class="font-size-13">Per month</span></h2>
                        </div>
                        <div class="text-center plan-btn">
                            <a href="auth-register.php" class="btn btn-primary btn-sm waves-effect waves-light">Sign up Now</a>
                        </div>

                        <div class="plan-features mt-5">
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Unlimited Bots</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Unlimited Trading Pairs</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Multiple Exchanges</p>
                            <p><i class="bx bx-checkbox-square text-primary mr-2"></i> Unlimited Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</section>
<!-- Pricing end -->

<!-- Faqs start -->
<section class="section" id="faqs">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <div class="small-title">FAQs</div>
                    <h4>Frequently asked questions</h4>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="vertical-nav">
                    <div class="row">
                        <div class="col-lg-2 col-sm-4">
                            <div class="nav flex-column nav-pills" role="tablist">
                                <a class="nav-link active" id="v-pills-gen-ques-tab" data-toggle="pill"
                                   href="#v-pills-gen-ques" role="tab">
                                    <i class="bx bx-help-circle nav-icon d-block mb-2"></i>
                                    <p class="font-weight-bold mb-0">General Questions</p>
                                </a>
                                <a class="nav-link" id="v-pills-token-sale-tab" data-toggle="pill"
                                   href="#v-pills-token-sale" role="tab">
                                    <i class="bx bx-receipt nav-icon d-block mb-2"></i>
                                    <p class="font-weight-bold mb-0">Account Security</p>
                                </a>
                                <a class="nav-link" id="v-pills-roadmap-tab" data-toggle="pill" href="#v-pills-roadmap"
                                   role="tab">
                                    <i class="bx bx-timer d-block nav-icon mb-2"></i>
                                    <p class="font-weight-bold mb-0">Bots Setup</p>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-10 col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="v-pills-gen-ques" role="tabpanel">
                                            <h4 class="card-title mb-4">General Questions</h4>

                                            <div>
                                                <div id="gen-ques-accordion" class="accordion custom-accordion">
                                                    <div class="mb-3">
                                                        <a href="#general-collapseOne" class="accordion-list"
                                                           data-toggle="collapse"
                                                           aria-expanded="true"
                                                           aria-controls="general-collapseOne">

                                                            <div>What is Lorem Ipsum ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>

                                                        </a>

                                                        <div id="general-collapseOne" class="collapse show"
                                                             data-parent="#gen-ques-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">Everyone realizes why a new common
                                                                    language would be desirable: one could refuse to pay
                                                                    expensive translators. To achieve this, it would be
                                                                    necessary to have uniform grammar, pronunciation and
                                                                    more common words.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <a href="#general-collapseTwo" class="accordion-list collapsed"
                                                           data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="general-collapseTwo">
                                                            <div>Why do we use it ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="general-collapseTwo" class="collapse"
                                                             data-parent="#gen-ques-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">If several languages coalesce, the
                                                                    grammar of the resulting language is more simple and
                                                                    regular than that of the individual languages. The
                                                                    new common language will be more simple and regular
                                                                    than the existing European languages.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <a href="#general-collapseThree"
                                                           class="accordion-list collapsed" data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="general-collapseThree">
                                                            <div>Where does it come from ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="general-collapseThree" class="collapse"
                                                             data-parent="#gen-ques-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">It will be as simple as Occidental; in
                                                                    fact, it will be Occidental. To an English person,
                                                                    it will seem like simplified English, as a skeptical
                                                                    Cambridge friend of mine told me what
                                                                    Occidental.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <a href="#general-collapseFour" class="accordion-list collapsed"
                                                           data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="general-collapseFour">
                                                            <div>Where can I get some ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="general-collapseFour" class="collapse"
                                                             data-parent="#gen-ques-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">To an English person, it will seem like
                                                                    simplified English, as a skeptical Cambridge friend
                                                                    of mine told me what Occidental is. The European
                                                                    languages are members of the same family. Their
                                                                    separate existence is a myth.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-token-sale" role="tabpanel">
                                            <h4 class="card-title mb-4">Token sale</h4>

                                            <div>
                                                <div id="token-accordion" class="accordion custom-accordion">
                                                    <div class="mb-3">
                                                        <a href="#token-collapseOne" class="accordion-list collapsed"
                                                           data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="token-collapseOne">
                                                            <div>Why do we use it ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="token-collapseOne" class="collapse"
                                                             data-parent="#token-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">If several languages coalesce, the
                                                                    grammar of the resulting language is more simple and
                                                                    regular than that of the individual languages. The
                                                                    new common language will be more simple and regular
                                                                    than the existing European languages.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <a href="#token-collapseTwo" class="accordion-list"
                                                           data-toggle="collapse"
                                                           aria-expanded="true"
                                                           aria-controls="token-collapseTwo">

                                                            <div>What is Lorem Ipsum ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>

                                                        </a>

                                                        <div id="token-collapseTwo" class="collapse show"
                                                             data-parent="#token-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">Everyone realizes why a new common
                                                                    language would be desirable: one could refuse to pay
                                                                    expensive translators. To achieve this, it would be
                                                                    necessary to have uniform grammar, pronunciation and
                                                                    more common words.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <a href="#token-collapseThree" class="accordion-list collapsed"
                                                           data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="token-collapseThree">
                                                            <div>Where can I get some ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="token-collapseThree" class="collapse"
                                                             data-parent="#token-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">To an English person, it will seem like
                                                                    simplified English, as a skeptical Cambridge friend
                                                                    of mine told me what Occidental is. The European
                                                                    languages are members of the same family. Their
                                                                    separate existence is a myth.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <a href="#token-collapseFour" class="accordion-list collapsed"
                                                           data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="token-collapseFour">
                                                            <div>Where does it come from ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="token-collapseFour" class="collapse"
                                                             data-parent="#token-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">It will be as simple as Occidental; in
                                                                    fact, it will be Occidental. To an English person,
                                                                    it will seem like simplified English, as a skeptical
                                                                    Cambridge friend of mine told me what
                                                                    Occidental.</p>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="v-pills-roadmap" role="tabpanel">
                                            <h4 class="card-title mb-4">Roadmap</h4>

                                            <div>
                                                <div id="roadmap-accordion" class="accordion custom-accordion">

                                                    <div class="mb-3">
                                                        <a href="#roadmap-collapseOne" class="accordion-list"
                                                           data-toggle="collapse"
                                                           aria-expanded="true"
                                                           aria-controls="roadmap-collapseOne">


                                                            <div>Where can I get some ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>

                                                        </a>

                                                        <div id="roadmap-collapseOne" class="collapse show"
                                                             data-parent="#roadmap-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">Everyone realizes why a new common
                                                                    language would be desirable: one could refuse to pay
                                                                    expensive translators. To achieve this, it would be
                                                                    necessary to have uniform grammar, pronunciation and
                                                                    more common words.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <a href="#roadmap-collapseTwo" class="accordion-list collapsed"
                                                           data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="roadmap-collapseTwo">
                                                            <div>What is Lorem Ipsum ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="roadmap-collapseTwo" class="collapse"
                                                             data-parent="#roadmap-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">If several languages coalesce, the
                                                                    grammar of the resulting language is more simple and
                                                                    regular than that of the individual languages. The
                                                                    new common language will be more simple and regular
                                                                    than the existing European languages.</p>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="mb-3">
                                                        <a href="#roadmap-collapseThree"
                                                           class="accordion-list collapsed" data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="roadmap-collapseThree">
                                                            <div>Why do we use it ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="roadmap-collapseThree" class="collapse"
                                                             data-parent="#roadmap-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">To an English person, it will seem like
                                                                    simplified English, as a skeptical Cambridge friend
                                                                    of mine told me what Occidental is. The European
                                                                    languages are members of the same family. Their
                                                                    separate existence is a myth.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <a href="#roadmap-collapseFour" class="accordion-list collapsed"
                                                           data-toggle="collapse"
                                                           aria-expanded="false"
                                                           aria-controls="roadmap-collapseFour">
                                                            <div>Where does it come from ?</div>
                                                            <i class="mdi mdi-minus accor-plus-icon"></i>
                                                        </a>
                                                        <div id="roadmap-collapseFour" class="collapse"
                                                             data-parent="#roadmap-accordion">
                                                            <div class="card-body">
                                                                <p class="mb-0">It will be as simple as Occidental; in
                                                                    fact, it will be Occidental. To an English person,
                                                                    it will seem like simplified English, as a skeptical
                                                                    Cambridge friend of mine told me what
                                                                    Occidental.</p>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end vertical nav -->
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- Faqs end -->


<?php include 'layouts/footer-large.php'; ?>

<!-- JAVASCRIPT -->
<?php include 'layouts/footer-scripts.php'; ?>

<script src="assets/libs/jquery.easing/jquery.easing.min.js"></script>

<!-- owl.carousel js -->
<script src="assets/libs/owl.carousel/owl.carousel.min.js"></script>

<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<script src="assets/js/app.js"></script>

<script src="assets/js/main.js"></script>

</body>
</html>
