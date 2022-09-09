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
  <meta charset="utf-8">
  <?php
  if($get_main_portal_detail->status==1){
    echo '<title>'.$portal_detail->PROJECT.'</title>';
  }else{
    echo '<title>Error</title>';

  }
  ?>
  
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="cafe,cafe center,cyber cafe,manager,">
  <meta content="" name="description">

  <!-- Favicons -->
  <link rel="icon" type="image/x-icon" href="<?=$portal_detail->SITE_ICON?>">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">
  <script src="lib/jquery/jquery.min.js"></script>

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
    
  <!--==========================
  Header
  ============================-->
  <header id="header">

    <div id="topbar">
      <div class="container">
        <div class="social-links">
          <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
          <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
          <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
          <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
        </div>
      </div>
    </div>

    <div class="container">

      <div class="logo float-left">
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <h1 class="text-light"><a href="#intro" class="scrollto"><span>Rapid</span></a></h1> -->
        <a href="<?=$site_url?>" class="scrollto"><img style="width:150px" src="<?=$portal_detail->LOGO?>" alt="<?=$portal_detail->PROJECT?>" class="img-fluid"></a>
      </div>

      <nav class="main-nav float-right d-none d-lg-block">
        <ul>
          <li class="active"><a href="#intro">Home</a></li>
          <li><a href="#about">About Us</a></li>
          <li><a href="#services">Services</a></li>
          <!-- <li><a href="#portfolio">Portfolio</a></li>
          <li><a href="#team">Team</a></li>
          <li class="drop-down"><a href="">Drop Down</a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="drop-down"><a href="#">Drop Down 2</a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
              <li><a href="#">Drop Down 5</a></li>
            </ul>
          </li> -->
          <li><a href="#footer">Contact Us</a></li>
          <li><a href="#">Login</a></li>
          <li><a href="#">Sign Up</a></li>
        </ul>
      </nav><!-- .main-nav -->
      
    </div>
  </header><!-- #header -->