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
                  <li class="breadcrumb-item active">Dashboard</li>
               </ul>
            </nav>
            <h1 class="text-center">Dashboard</h1>
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
                        <h2 class="h2_hedddin">Dashboard</h2>
                        <div class="rightt">

                           <!-- <form method="post" action="http://localhost/wordpress/wedding/wp-admin/admin-post.php">
                              <div class="form-group">
                                 <label>Name</label>
                                 <input type="text" name="username" id="username" placeholder="Your Name" class="form-control" value="admin" required="">
                              </div>
                              <div class="form-group">
                                 <label>Phone</label>
                                 <input name="phone" id="phone" placeholder="Phone" class="form-control" type="text" required="" value="">
                              </div>
                              <div class="form-group">
                                 <label>Email</label>
                                 <input name="email" id="email" placeholder="Email" class="form-control" type="email" readonly="" value="tahirk.official@gmail.com">
                              </div>
                              <div class="form-group">
                                 <input type="hidden" name="action" value="user_profile_update_act">
                                 <button class="btn btn_theme btn-lg" type="submit">Update</button>
                              </div>
                           </form> -->
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
