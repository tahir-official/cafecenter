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
                  <li class="breadcrumb-item active">Wallet Management</li>
               </ul>
            </nav>
            <h1 class="text-center">Wallet Management</h1>
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
                        <h2 class="h2_hedddin">Wallet & Wallet History</h2>
                        <div class="rightt">
                           <div class="card card-primary">
                              <div class="card-body">
                                 <div class="form-group">
                                    <label for="inputName">Total Wallet Amount</label>
                                    <input type="text" id="total_wallet_amount" class="form-control" disabled value="<?=$portal_detail->CURRENCY.''.$user_data->wallet?>">
                                 </div>
                                 <div class="form-group">
                                   <a href="recharge.php" class="btn btn-primary" >Recharge</a><br>
                                   <span style="color: chocolate;">You want to recharge your wallet, Please click on this button.</span>
                                 </div>   
                              </div>
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
