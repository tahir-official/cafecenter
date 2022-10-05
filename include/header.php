<?php
include_once('include/functions.php');
$commonFunction= new functions();
$get_main_portal_detail=$commonFunction->get_main_portal_detail();
$portal_detail=$get_main_portal_detail->data;

if(isset($_SESSION['is_user_logged_in'])){ 
  $user_detail=$commonFunction->user_detail($_SESSION['user_id']);
  $user_data=$user_detail->data;
  $user_type=$user_data->user_type;   
}
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
   <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link href="assets/css/multiple-select.css" rel="stylesheet" type="text/css">
  
  <script src="assets/js/jquery-3.5.1.min.js"></script>
  <script src="assets/js/jquery.multiple.select.js"></script>
  

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
  <style>
        #loader{
        height: 400px;
        background: url("<?=$portal_detail->LOADER_IMG?>") no-repeat center;
                    
        }
        
  </style>
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
            <!-- <li class="nav-item">
              <a class="nav-link" href="service.php">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blog.php">Blog</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
            <?php
            if(isset($_SESSION['is_user_logged_in'])){ 
            ?>
            <li class="nav-item">
              <a class="btn btn-primary ml-lg-2" href="dashboard.php" id="user_name">Hi <?=ucfirst($user_data->fname).' '.ucfirst($user_data->lname)?></a>
            </li>
            <li class="nav-item">
              <a class="btn btn-success ml-lg-2" href="logout.php">Logout</a>
            </li>
            <?php   
            
            }else{
              ?>
            <li class="nav-item">
              <a class="btn btn-primary ml-lg-2" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-success ml-lg-2" href="signup.php">Sign Up</a>
            </li>
            <?php
            }
            ?>
            
          </ul>
        </div>

      </div>
    </nav>