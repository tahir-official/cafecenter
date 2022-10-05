<?php
   include_once('include/header.php');
   include ("include/redirectcondtion.php");
   if($_SESSION['user_type']==4){
      $commonFunction->redirect('dashboard.php');
   }
?>
<div class="container">
   <div class="page-banner">
      <div class="row justify-content-center align-items-center h-100">
         <div class="col-md-6">
            <nav aria-label="Breadcrumb">
               <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                  <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                  <li class="breadcrumb-item active">Consumers Management</li>
               </ul>
            </nav>
            <h1 class="text-center">Consumers Management</h1>
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
                        <h2 class="h2_hedddin">Consumers Management <button style="float: right;margin-top: -6px;margin-right: -15px;" type="button" class="btn btn-success" onClick="load_users_popup(0,4)"><span class="mai-add-circle-outline"></span> </button></h2>
                        
                        <div class="rightt">
                           
                           <div class="card-body table-responsive">
                              <table id="mytable" class="table table-bordered table-striped">
                                 <thead class="thead-light ">
                                 
                                 <tr>
                                       <th>S.N.</th>
                                       <th>Profile</th>
                                       <th>First Name</th>
                                       <th>Last Name</th>
                                       <th>Email</th>
                                       <th>Contact Number</th>
                                       <th>Created Date</th>
                                       <th>Subscription End Date</th>
                                       <th>Subscription Status</th>
                                       <th>Status</th>
                                       <th>Action</th>
                                       
                                 </tr>
                                       
                                 
                                 </thead>
                           
                                 
                           </table>
                           </div> 
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
<script>
    $(document).ready(function(){
      tableLoad("<?=SSOAPI?>get_user_table_list",4,'main',<?php echo $_SESSION['user_id']?>);
    });
</script>
