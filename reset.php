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
                  <li class="breadcrumb-item active">Reset Password</li>
               </ul>
            </nav>
            <h1 class="text-center">Reset Password</h1>
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
                     <p class="login-box-msg">Reset password</p>
                     <div id="alert" ></div>
                     <?php
                        if (isset($_SESSION['message'])){ echo $_SESSION['message'];  unset($_SESSION['message']);}
                        ?>
                     <form method="post" id="resetFrom">
                        <input type="hidden" name="token" value="<?php if(isset($_REQUEST['token'])){echo $_REQUEST['token'];}?>" id="token">
                        <div class="form-group">
                           <input type="password" name="password" id="password" class="form-control" placeholder="Password" >
                        </div>
                        <div class="form-group">
                           <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password" >
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <button type="submit" class="btn btn-primary btn-block btnResetpass">Submit</button>
                           </div>
                           <!-- /.col -->
                        </div>
                     </form>
                     <p class="mt-3 mb-1">
                        <a href="login.php">Login</a>
                     </p>
                     <p class="mb-0">
                        <a href="signup.php" class="text-center">Register a new membership</a>
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
