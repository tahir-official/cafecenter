<footer class="page-footer bg-image" style="background-image: url(assets/img/world_pattern.svg);">
    <div class="container">
      <div class="row mb-5">
        <div class="col-lg-3 py-3">
        <a href="<?=$site_url?>" class="scrollto"><img style="width:150px" src="<?=$portal_detail->LOGO?>" alt="<?=$portal_detail->PROJECT?>" class="img-fluid"></a>
          <p><?=$portal_detail->FOOTER_ONE?></p>

          
        </div>
        <div class="col-lg-3 py-3">
          <h5>Company</h5>
          <ul class="footer-menu">
            <li><a href="about.php">About Us</a></li>
            <li><a href="service.php">Services</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="terms_of_service.php">Terms of Service</a></li>
            <li><a href="privacy_policy.php">Privacy Policy</a></li>
          </ul>
        </div>
        <div class="col-lg-3 py-3">
          <h5>Contact Us</h5>
          <a href="tel:<?=$portal_detail->SUPPORT_NUMBER?>" class="footer-link"><?=$portal_detail->SUPPORT_NUMBER?></a><br>
          <a href="mailto:<?=$portal_detail->SUPPORT_EMAIL?>" class="footer-link"><?=$portal_detail->SUPPORT_EMAIL?></a>
        </div>
        <div class="col-lg-3 py-3">
          <h5>Social</h5>
          <div class="social-media-button">
            <a href="<?=$portal_detail->FACEBOOK?>"><span class="mai-logo-facebook-f"></span></a>
            <a href="<?=$portal_detail->TWITTER?>"><span class="mai-logo-twitter"></span></a>
            <a href="<?=$portal_detail->INSTAGRAM?>"><span class="mai-logo-instagram"></span></a>
            <a href="<?=$portal_detail->YOUTUBE?>"><span class="mai-logo-youtube"></span></a>
          </div>
        </div>
      </div>

      <p class="text-center" id="copyright"> <?=$portal_detail->FOOTER_TEXT?></p>
    </div>
  </footer>



<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/google-maps.js"></script>

<script src="assets/vendor/wow/wow.min.js"></script>

<script src="assets/js/theme.js"></script>
<script src="assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
      let baseUrl = '<?=$site_url;?>';
</script>
<script src="assets/js/custom.js"></script>

  
</body>
</html>