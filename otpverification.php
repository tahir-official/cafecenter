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
                  <li class="breadcrumb-item active">OTP Verification</li>
               </ul>
            </nav>
            <h1 class="text-center">OTP Verification</h1>
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
                     <div id="alert" ></div>
                     <?php
                        if (isset($_SESSION['message'])){ echo $_SESSION['message'];  unset($_SESSION['message']);}
                        ?>
                     <form method="post" id="otpverifyFrom">
                        <input type="hidden" name="page" value="<?php if(isset($_REQUEST['page'])){echo $_REQUEST['page'];}?>">
                        <div class="form-group">
                           <input type="text" class="form-control"  name="number" id="number" value="<?php if(isset($_REQUEST['number'])){echo $_REQUEST['number'];}?>" readonly>
                        </div>
                        <div class="form-group">
                           <input type="text" class="form-control"  name="otp" id="otp"  placeholder="OTP">
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <a href="login.php" class="btn btn-primary btn-block">Cancel</a>
                           </div>
                           <!-- /.col -->
                           <div class="col-6">
                              <button type="submit" class="btn btn-primary btn-block btnOtpverify">Submit</button>
                           </div>
                           <!-- /.col -->
                        </div>
                     </form>
                     <p class="mb-1" id="vload">
                        <a href="javascript:void(0)" onclick="return sent_otp(<?php if(isset($_REQUEST['number'])){echo $_REQUEST['number'];}?>,'<?php if(isset($_REQUEST['page'])){echo $_REQUEST['page'];}?>')">Send again</a>
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
