<?php
   include_once('include/header.php');
   include ("include/redirectcondtion.php");
         
?>
<div class="container">
   <div class="page-banner">
      <div class="row justify-content-center align-items-center h-100">
         <div class="col-md-6">
            <nav aria-label="Breadcrumb">
               <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                  <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                  <li class="breadcrumb-item active">Change Password</li>
               </ul>
            </nav>
            <h1 class="text-center">Change Password</h1>
         </div>
      </div>
   </div>
</div>
</header>
<!--dashboard-->
<div class="page-section">
   <div class="container">
      <div id="pagelayout_area">
      <?php
        if (isset($_SESSION['message'])){ echo $_SESSION['message'];  unset($_SESSION['message']);}
        ?>
        <div id="alert"></div>
         <section class="site-maincontentarea fullwidth">
            <div class="entry-content">
               <!--filter-->
               <div class="filteruus main_page">
                  <div class="row">
                     <div class="col-sm-4">
                        <?php
                           include ("include/dashboard-sidebar.php");
                        ?>
                     </div>
                     <div class="col-sm-8">
                        <h2 class="h2_hedddin">Change Password</h2>
                        <div class="rightt">

                            <form class="form-horizontal" name="updatePassword" id="updatePassword" method="post" >
                                 <div id="alert_change_pass"></div>
                                    <div class="form-group row">
                                       <label for="current_password" class="col-sm-2 col-form-label">Current Password</label>
                                       <div class="col-sm-10">
                                       <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password">
                                       </div>
                                    </div>
                                    <div class="form-group row">
                                       <label for="new_password" class="col-sm-2 col-form-label">New Password</label>
                                       <div class="col-sm-10">
                                       <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
                                       </div>
                                    </div>
                                    <div class="form-group row">
                                       <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                                       <div class="col-sm-10">
                                       <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                                       </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                       <div class="offset-sm-2 col-sm-10">
                                       <button type="submit" class="btn btn-primary" id="updatePassBtn">Change</button>
                                       <button class="btn btn-warning" id="cancelPassBtn" onclick="return resetPasswordFrom();">Cancel</button>
                                       </div>
                                    </div>
                                 </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>
<?php
   //print_r($_SESSION);
   include_once('include/footer.php');
?>
