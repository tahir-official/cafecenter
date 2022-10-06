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
                       
                        <div class="row gx-5">
                        <?php
                        if($_SESSION['user_type']==3){
                           ?>
                              <div class="col">
                                 <div class="card text-white p-3 border bg-success">
                                    <div class="card-header">Wallet</div>
                                    <div class="card-body">
                                          <h5 class="card-title"><?=$portal_detail->CURRENCY.' '.$user_data->wallet?></h5>
                                          <a href="wallet.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                              </div>
                              <div class="col">
                                 <div class="card text-white p-3 border bg-danger">
                                    <div class="card-header">Consumer</div>
                                    <div class="card-body">
                                          <h5 class="card-title"><?=$user_data->added_by_you?></h5>
                                          <a href="consumer-management.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                              </div>
                           <?php
                        }else{
                           $plan_detail_json=json_decode($user_data->consumer_plan_json);
                           if(date('Y-m-d') > $user_data->subscription_end){
                              $plan_text='<h5 class="card-title">Your plan is expired on <span style="color: red;">'.$user_data->subscription_end.'</span>, Please connect with retailers to renew your plan.</h5>';
                           }else{
                              $plan_text='<h5 class="card-title">'.$portal_detail->CURRENCY.' '.$plan_detail_json->plan_amount.'</h5><small>For '.$plan_detail_json->plan_days.' Days</small><br>';
                           }
                           ?>
                              <div class="col">
                                 <div class="card text-white p-3 border bg-success">
                                    <div class="card-header">Subscription Plan</div>
                                    <div class="card-body">
                                          <?=$plan_text;?>

                                          <a href="subscription-plan.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                 </div>
                              </div>
                              
                           <?php
                        }
                        ?>
                           
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
