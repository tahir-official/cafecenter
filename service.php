    <?php
      include_once('include/header.php');
    ?>

    <div class="container">
      <div class="page-banner">
        <div class="row justify-content-center align-items-center h-100">
          <div class="col-md-6">
            <nav aria-label="Breadcrumb">
              <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Services</li>
              </ul>
            </nav>
            <h1 class="text-center">Our Services</h1>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="page-section">
    <div class="container">
      <div class="row">
          <?php
  
          $services_list=$commonFunction->services_list(0);
          $services_list_status=$services_list->status;
          $services_list_message=$services_list->message;
          $services_list_data=$services_list->data;
          if($services_list_status == 0){
            ?>
            <div class="col-lg-12">
                <div class="card-service wow fadeInUp">
                  <div class="header">
                    <img src="assets/img/services/service-1.svg" alt="">
                  </div>
                  <div class="body">
                    <h5 class="text-secondary">Error</h5>
                    <?=$services_list_message?>
                  </div>
                </div>
              </div>
            <?php
          }else{
            
            foreach($services_list_data  as $services){
                ?>
                <div class="col-lg-4">
                    <div class="card-service wow fadeInUp">
                      <div class="header">
                        <img src="<?=$services->service_image?>" alt="">
                      </div>
                      <div class="body">
                        <h5 class="text-secondary"><?=$services->post_title?></h5>
                        
                        <?=$services->post_content?>
                        <?php
                        if(isset($_SESSION['is_user_logged_in'])){ 
                          if($_SESSION['user_type']==3){
                             ?>
                             <a href="<?=$services->page_url?>" class="btn btn-primary">Read More</a>
                             <?php
                          }else{
                               ?>
                              <a href="javascript:void(0)" onclick="showServiceAlert()" class="btn btn-primary">Read More</a>
                              <?php
                          }
                             
                        }else{
                              ?>
                              <a href="javascript:void(0)" onclick="showServiceAlert()" class="btn btn-primary">Read More</a>
                              <?php
                        }
                        ?>
                        
                      </div>
                    </div>
                  </div>
                <?php
                
            }
          }
          ?>
        
        
      </div>
    </div> 
  </div>

  

  <?php
      include_once('include/footer.php');
  ?>