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
                  <li class="breadcrumb-item active">Subscription Plan</li>
               </ul>
            </nav>
            <h1 class="text-center">Subscription Plan</h1>
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
                        <h2 class="h2_hedddin">Subscription Plan</h2>
                        <div class="rightt">
                        <div class="row">
                          <div class="col-xs-12 col-md-12">
                              <div class="panel panel-success">
                                  <div class="cnrflash">
                                      <div class="cnrflash-inner">
                                          <span class="cnrflash-label">CURRENT
                                              <br>
                                              PLAN</span>
                                      </div>
                                  </div>
                                  
                                  <div class="panel-heading">
                                      <h3 class="panel-title">
                                          <?=$plan_data->plan_title?></h3>
                                  </div>
                                  <div class="panel-body">
                                      <div class="the-price">
                                          <h1><?php echo $portal_detail->CURRENCY.' '.$plan_data->plan_amount?></h1>
                                          <small>For lifetime</small>
                                      </div>
                                      <table class="table">
                                          <tr>
                                              <td>
                                              <?=$plan_data->plan_heading?>
                                              </td>
                                          </tr>
                                          <tr class="active">
                                              <td>
                                              <?=$plan_data->plan_description?>
                                              </td>
                                          </tr>
                                          
                                      </table>
                                  </div>
                                  
                              </div>
                          </div>
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
