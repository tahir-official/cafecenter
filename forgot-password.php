<?php
   include_once('include/header.php');
   if(isset($_SESSION['is_user_logged_in'])){ $commonFunction->redirect('dashboard.php'); }      
?>
<div class="container">
   <div class="page-banner">
      <div class="row justify-content-center align-items-center h-100">
         <div class="col-md-6">
            <nav aria-label="Breadcrumb">
               <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                  <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                  <li class="breadcrumb-item active">Forgot Password</li>
               </ul>
            </nav>
            <h1 class="text-center">Forgot Password</h1>
         </div>
      </div>
   </div>
</div>
</header>
<div class="page-section">
   <div class="container">
      <div class="row">
         <div class="col-lg-3">
         </div>
         <div class="col-lg-6">
            <div class="login-box">
               <?php
                  if($get_main_portal_detail->status==1){
                ?>
               
               <div class="card">
                  <div class="card-body login-card-body">
                     <p class="login-box-msg" style="text-align:center">You forgot your password? Here you can easily retrieve a new password.</p>
                     <div id="alert" ></div>
                     <?php
                        if (isset($_SESSION['message'])){ echo $_SESSION['message'];  unset($_SESSION['message']);}
                        ?>
                     <form method="post" id="forgetpassFrom">
                        <input type="hidden" name="page" value="forget" id="page">
                        <div class="input-group mb-3">
                           <input type="text" class="form-control" placeholder="Mobile Number" name="number" id="number" maxlength="10">
                           <div class="input-group-append">
                              <div class="input-group-text">
                              <span class="mai-phone-portrait">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <button type="submit" class="btn btn-primary btn-block btnForgetpass">Request OTP</button>
                           </div>
                           <!-- /.col -->
                        </div>
                     </form>
                     <p class="mt-3 mb-1">
                        <a href="login.php">Login</a>
                     </p>
                     <p class="mb-0">
                     <a href="signup.php" class="text-center">Register as a <b>Retailer</b></a>
                     </p>
                  </div>
                  <!-- /.login-card-body -->
               </div>
               <?php
                  }
                  else{
                  ?>
               
               <!-- /.login-logo -->
               <div class="card">
                  <div class="card-body login-card-body">
                     <div class="alert alert-danger" role="alert">
                        <?php echo $get_main_portal_detail->message?>
                     </div>
                  </div>
                  <!-- /.login-card-body -->
               </div>
               <?php
                  }
                  ?>
            </div>
         </div>
         <div class="col-lg-3">
         </div>
      </div>
   </div>
</div>
<?php
   include_once('include/footer.php');
?>
