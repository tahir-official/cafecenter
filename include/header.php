<?php
include_once('include/functions.php');
$commonFunction= new functions();
$get_main_portal_detail=$commonFunction->get_main_portal_detail();
$portal_detail=$get_main_portal_detail->data;
if(ENV=='prod'){
$site_url=$portal_detail->PORTAL_URL;
}else{
$site_url=LOCAL_URL;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="cafe,cafe center,cyber cafe,manager,">
  <meta content="" name="description">
  <?php
  if($get_main_portal_detail->status==1){
    echo '<title>'.$portal_detail->PROJECT.'</title>';
  }else{
    echo '<title>Error</title>';

  }
  ?>
  <link rel="stylesheet" href="assets/css/maicons.css">

  <link rel="stylesheet" href="assets/css/bootstrap.css">

  <link rel="stylesheet" href="assets/vendor/animate/animate.css">

  <link rel="stylesheet" href="assets/css/theme.css">
  <!-- Favicons -->
  <link rel="icon" type="image/x-icon" href="<?=$portal_detail->SITE_ICON?>">
  <link href="assets/css/style.css" rel="stylesheet">
  <script src="assets/js/jquery-3.5.1.min.js"></script>

  <script>
    <?php
   
    if($get_main_portal_detail->status==0){
        $portal_error_html='<img src="'.$portal_detail->ERROR_500.'" />'; 
        ?>
        $(document).ready(function() {
            $(".body-area").html('<?=$portal_error_html?>'); 
            $(".body-area").css('text-align','center');
        });
    <?php
    }
    ?>
  </script>
</head>
<body class="body-area">

  <!-- Back to top button -->
  <div class="back-to-top"></div>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky" data-offset="500">
      <div class="container">
        <!-- <a href="#" class="navbar-brand">Seo<span class="text-primary">Gram.</span></a> -->
        <a href="<?=$site_url?>" ><img style="width:150px" src="<?=$portal_detail->LOGO?>" alt="<?=$portal_detail->PROJECT?>" class="img-fluid"></a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="navbarContent">
          <ul class="navbar-nav ml-auto" id="nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="service.php">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blog.php">Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-primary ml-lg-2" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-success ml-lg-2" href="signup.php">Sign Up</a>
            </li>
          </ul>
        </div>

      </div>
    </nav>