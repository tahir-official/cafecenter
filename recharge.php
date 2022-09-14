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
                  <li class="breadcrumb-item active">Recharge Management</li>
               </ul>
            </nav>
            <h1 class="text-center">Recharge Management</h1>
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
                        <h2 class="h2_hedddin">Recharge & Recharge History</h2>
                        <div class="rightt">
                           <div class="card card-primary">
                              <form class="form-horizontal" name="rechargeRequst_form" id="rechargeRequst_form" method="post" >
                                 <div class="card-body">
                                    
                                    <input type="hidden" name="action" value="rechargeRequst" >
                                    <div class="form-group">
                                       <label for="request_amount">Please Enter Recharge Amount</label>
                                       <input type="number" id="request_amount" name="request_amount" class="form-control" value="" step="1">
                                    </div>
                                    <div class="form-group">
                                       <button  type="submit" id="rechargeRequstBtn" class="btn btn-primary" >Submit</button>
                                    
                                    </div>
                                    
                                 </div>
                              </form>
                              <!-- /.card-body -->
                           </div>
                           <hr>
                           <div class="table-responsive">
                              <table class="table">
                                 <thead class="thead-dark">
                                    <tr>
                                       <th scope="col">S.N.</th>
                                       <th scope="col">Profile</th>
                                       <th scope="col">Vendor</th>
                                       <th scope="col">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td scope="row">No Data Found</td>
                                       <td scope="row">No Data Found</td>
                                       <td scope="row">No Data Found</td>
                                       <td scope="row">No Data Found</td>
                                    </tr>
                                 </tbody>
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
