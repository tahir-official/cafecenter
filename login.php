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
                  <li class="breadcrumb-item active">Login</li>
               </ul>
            </nav>
            <h1 class="text-center">Login</h1>
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
               <!-- /.login-logo -->
               <div class="card">
                  <div class="card-body login-card-body">
                     <p class="login-box-msg" style="text-align:center">Sign in to start your session</p>
                     <div id="alert" ></div>
                     <?php
                        if (isset($_SESSION['message'])){ echo $_SESSION['message'];  unset($_SESSION['message']);}
                        ?>
                     <form method="post" id="loginFrom">
                        <div class="input-group mb-3">
                           <input type="text" name="username" id="username" class="form-control" placeholder="Username (Phone or Email)" value="<?php if(isset($_COOKIE["loginId"])) { echo $_COOKIE["loginId"]; } ?>">
                           <div class="input-group-append input-group-text">
                              <span class="mai-mail">
                           </div>
                        </div>
                        <div class="input-group mb-3">
                           <input type="password" name="password" id="password" class="form-control" placeholder="Password" value="<?php if(isset($_COOKIE["loginPass"])) { echo $_COOKIE["loginPass"]; } ?>">
                           <div class="input-group-append input-group-text toggle-password">
                              <span class="mai-lock-closed">
                           </div>
                        </div>
                        <div class="row">
                           
                           <div class="col-4">
                              <button type="submit" class="btn btn-primary btn-block btnLogin">Sign In</button>
                           </div>
                           <!-- /.col -->
                        </div>
                     </form>
                     <p class="mb-1">
                        <a href="forgot-password.php">I forgot my password</a>
                     </p>
                     <p class="mb-0">
                        <a href="signup.php" class="text-center">Register as a <b>Retailer</b></a>
                     </p>
                  </div>
                  <!-- /.login-card-body -->
               </div>
               <?php
                  }else{
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
