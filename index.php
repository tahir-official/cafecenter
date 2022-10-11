    <?php
      include_once('include/header.php');
      $home_page='205';
      $about_page='230';
      
    ?>
    
    <div class="container">
      <div class="page-banner home-banner">
        <div class="row align-items-center flex-wrap-reverse h-100">
          <div class="col-md-6 py-5 wow fadeInLeft">
            <?php
            $get_page_detail_by_id=$commonFunction->get_page_detail_by_id($home_page);
            $page_title=$get_page_detail_by_id->title;
            $page_detail=$get_page_detail_by_id->data;
            $meta=$get_page_detail_by_id->meta;
            $slider_image=$commonFunction->get_attachment_image_src($meta->slider_image[0]);
            ?>
            <h1 class="mb-4"><?php echo $meta->slider_heading[0];?></h1>
            
            <p class="text-lg text-grey mb-5"><?php echo $meta->slider_detail[0];?></p>
            
          </div>
          <div class="col-md-6 py-5 wow zoomIn">
            <div class="img-fluid text-center">
              
              <img src="<?php echo $slider_image->image_src;?>" alt="<?php echo $page_title;?>">
            </div>
          </div>
        </div>
        <a href="#about" class="btn-scroll" data-role="smoothscroll"><span class="mai-arrow-down"></span></a>
      </div>
    </div>
  </header>

  <div class="page-section" id="about">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 py-3 wow fadeInUp">
        <?php
            $get_page_detail_by_id=$commonFunction->get_page_detail_by_id($about_page);
            $page_title=$get_page_detail_by_id->title;
            $page_detail=$get_page_detail_by_id->data;
            $meta=$get_page_detail_by_id->meta;
            $about_us_image=$commonFunction->get_attachment_image_src($meta->about_us_image[0]);
            ?>
          <span class="subhead"><?=$page_title?></span>
          <h2 class="title-section"><?php echo $meta->about_us_heading[0];?></h2>
          <div class="divider"></div>
          
          <?php echo substr(strip_tags($page_detail), 0, 215);?>
          <a href="about.php" class="btn btn-primary mt-3">Read More</a>
        </div>
        <div class="col-lg-6 py-3 wow fadeInRight">
          <div class="img-fluid py-3 text-center">
            <img src="<?php echo $about_us_image->image_src;?>" alt="<?php echo $page_title;?>">
          </div>
        </div>
      </div>
    </div> <!-- .container -->
  </div>
   <!-- .page-section -->
 
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
  <!-- .page-section -->

  <!-- Blog -->
  <?php
    if(isset($_SESSION['is_user_logged_in'])){ 
      if($_SESSION['user_type']==3){
          ?>
               <div class="page-section">
                  <div class="container">
                    <div class="text-center wow fadeInUp">
                      <div class="subhead">Our Blog</div>
                      <h2 class="title-section">Check Latest Blog</h2>
                      <div class="divider mx-auto"></div>
                    </div>

                    <div class="row mt-5">
                      <?php
                      $blog_list=$commonFunction->blog_list(0,'home');
                     // print_r($blog_list);
                      if($blog_list->status==1){
                        foreach($blog_list->data  as $blog_lists){
						
                          ?>
                            <div class="col-lg-4 py-3 wow fadeInUp">
                                <div class="card-blog">
                                  <div class="header">
                                    <div class="post-thumb">
                                      <img src="<?=$blog_lists->blog_image?>" alt="">
                                    </div>
                                  </div>
                                  <div class="body">
                                    <h5 class="post-title"><a href="blog-details.php?id=<?=$blog_lists->id?>"><?=$blog_lists->post_title?></a></h5>
                                    <div class="post-date">Posted on <a href="blog-details.php?id=<?=$blog_lists->id?>"><?=$blog_lists->post_date?></a></div>
                                  </div>
                                </div>
                              </div>
                          <?php
                        }
                        
                      }else{
                        ?>
                            <div class="col-lg-12 py-3 wow fadeInUp">
                            <div class="card-blog">
                              
                              <div class="body">
                                <h5 class="post-title"><a href="#">No blog found !!</a></h5>
                              
                              </div>
                            </div>
                          </div>
                        <?php
                      }
                      ?>
                      
                       <div class="col-12 mt-4 text-center wow fadeInUp">
                        <a href="blog.php" class="btn btn-primary">View More</a>
                      </div>
                    </div>
                  </div>
                </div>
          <?php
      }
          
    }
  ?>
 

  <?php
      include_once('include/footer.php');
   ?>

  