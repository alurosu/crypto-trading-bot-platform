<!-- Footer start -->
<div class="alert alert-dismissible text-center cookiealert" role="alert">
  <div class="cookiealert-container">
      <b>Do you like cookies?</b> &#x1F36A; We use cookies to ensure you get the best experience on our website. <a href="https://pitmanbot.com/privacy.php" target="_blank">Learn more</a>

      <button type="button" class="btn btn-primary btn-sm acceptcookies" aria-label="Close">
          I agree
      </button>
  </div>
</div>
<footer class="landing-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="mb-4">
                    <img src="assets/images/logo-light.png" alt="" height="20">
                </div>

                <p class="mb-2"><?php echo date('Y', time());?> © pitmanbot.com</p>
                <p>A smart bot that trades cryptocurrency based on market trends.</p>
                <?php include 'social-media.php'; ?>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="mb-4 mb-lg-0">
                    <h5 class="mb-3 footer-list-title">Resources</h5>
                    <ul class="list-unstyled footer-list-menu">
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms & Conditions</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end container -->
</footer>
<!-- Footer end -->
