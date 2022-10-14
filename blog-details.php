<?php
      include_once('include/header.php');
      include ("include/redirectcondtion.php");
      if($_SESSION['user_type']==4){
        $commonFunction->redirect('dashboard.php');
      }else if($_SESSION['user_type']==3){
        if(!isset($_REQUEST['id'])){
          $commonFunction->redirect('blog.php');
        }else if($_REQUEST['id']==''){
          $commonFunction->redirect('blog.php');
        }else{
          $blog_list=$commonFunction->blog_list($_REQUEST['id']);
          if($blog_list->status==0){
            $commonFunction->redirect('blog.php');
          }else{
            $blog_data=$blog_list->data;
            if(date('Ymd') > $blog_data->end_date){
              $commonFunction->redirect('blog.php');
            }
          }
        }
      }

      $blog_data=$blog_list->data;
?>
  </header>

  <div class="page-section pt-5">
    <div class="container">
      <nav aria-label="Breadcrumb">
        <ul class="breadcrumb p-0 mb-0 bg-transparent">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
          <li class="breadcrumb-item active"><?=$blog_data->post_title?></li>
        </ul>
      </nav>
      
      <div class="row">
        <div class="col-lg-8">
          <div class="blog-single-wrap">
            <div class="header">
              <div class="post-thumb">
                <img src="<?=$blog_data->blog_image?>" alt="">
              </div>
              <div class="meta-header">
                <div class="post-author">
                  <div class="avatar">
                    <img src="assets/img/person/person_1.jpg" alt="">
                  </div>
                  <!-- by <a href="#">Stephen Doe</a> -->
                </div>

                <!-- <div class="post-sharer">
                  <a href="#" class="btn social-facebook"><span class="mai-logo-facebook-f"></span></a>
                  <a href="#" class="btn social-twitter"><span class="mai-logo-twitter"></span></a>
                  <a href="#" class="btn social-linkedin"><span class="mai-logo-linkedin"></span></a>
                  <a href="#" class="btn"><span class="mai-mail"></span></a>
                </div> -->
              </div>
            </div>
            <h1 class="post-title"><?=$blog_data->post_title?></h1>
            <div class="post-meta">
              <?php
              if($blog_data->apply_link!=''){
                ?>
                <div class="post-date">
                  <span class="icon">
                    <span class="mai-lock-closed-outline"></span>
                  </span> <a target="_blank" href="<?=$blog_data->apply_link?>">Apply</a>
                </div>

                <?php
              }

              if($blog_data->syllables!=''){
                ?>
                <div class="post-comment-count ml-2">
                  <span class="icon">
                    <span class="mai-book-outline"></span>
                  </span> <a target="_blank" href="<?=$blog_data->syllables?>">Syllables</a>
                </div>

                <?php
              }

              if($blog_data->notification!=''){
                ?>
                <div class="post-comment-count ml-2">
                  <span class="icon">
                    <span class="mai-notifications-outline"></span>
                  </span> <a target="_blank" href="<?=$blog_data->notification?>">Notification</a>
                </div>

                <?php
              }

              ?>
              
              
            </div>
            <div class="post-content">
              <?=$blog_data->post_content?>
              <?php
              
              if($blog_data->send_message=='yes'){
                ?>

                  <button id="loadBtn" type="button" onclick="return load_job_form(<?=$blog_data->id?>)" class="btn btn-primary">Send Job Notification <span class="mai-notifications-circle"></span></button>
                <?php
              }
              ?>
              
              </div>
            </div>
         
          <div id="alert_div"></div>
          <div class="comment-form-wrap" id="job_form_div">
            
          </div>

        </div>
        <div class="col-lg-4">
          <div class="widget">
            <!-- Widget search -->
            <!-- <div class="widget-box">
              <form action="#" class="search-widget">
                <input type="text" class="form-control" placeholder="Enter keyword..">
                <button type="submit" class="btn btn-primary btn-block">Search</button>
              </form>
            </div> -->

            <!-- Widget Categories -->
            <!-- <div class="widget-box">
              <h4 class="widget-title">Category</h4>
              <div class="divider"></div>

              <ul class="categories">
                <li><a href="#">LifeStyle</a></li>
                <li><a href="#">Food</a></li>
                <li><a href="#">Healthy</a></li>
                <li><a href="#">Sports</a></li>
                <li><a href="#">Entertainment</a></li>
              </ul>
            </div> -->

            <!-- Widget recent post -->
            <div class="widget-box">
              <h4 class="widget-title">Recent Post</h4>
              <div class="divider"></div>
              <?php
              $blog_list_loop=$commonFunction->blog_list(0,'home');
              if($blog_list_loop->status==1){
                foreach($blog_list_loop->data  as $blog_list){
						       ?>
                   <div class="blog-item">
                      <a class="post-thumb" href="blog-details.php?id=<?=$blog_list->id?>">
                        <img src="<?=$blog_list->blog_image?>" alt="">
                      </a>
                      <div class="content">
                        <h6 class="post-title"><a href="blog-details.php?id=<?=$blog_list->id?>"><?=$blog_list->post_title?></a></h6>
                        <div class="meta">
                          <a href="blog-details.php?id=<?=$blog_list->id?>"><span class="mai-calendar"></span> <?=$blog_list->post_date?></a>
                          <a href="blog-details.php?id=<?=$blog_list->id?>"><span class="mai-person"></span> Admin</a>
                          <!-- <a href="#"><span class="mai-chatbubbles"></span> 19</a> -->
                        </div>
                      </div>
                  </div>
                  <?php
                }

              }else{
                ?>
                <div class="blog-item">
                  <div class="content">
                    <h6 class="post-title">No blog found !!</h6>
                   </div>
                </div>
                <?php
              }
              ?>
              

              

            </div>

            <!-- Widget Tag Cloud -->
            <!-- <div class="widget-box">
              <h4 class="widget-title">Tag Cloud</h4>
              <div class="divider"></div>

              <div class="tag-clouds">
                <a href="#" class="tag-cloud-link">Projects</a>
                <a href="#" class="tag-cloud-link">Design</a>
                <a href="#" class="tag-cloud-link">Travel</a>
                <a href="#" class="tag-cloud-link">Brand</a>
                <a href="#" class="tag-cloud-link">Trending</a>
                <a href="#" class="tag-cloud-link">Knowledge</a>
                <a href="#" class="tag-cloud-link">Food</a>
              </div>
            </div> -->

          </div>
        </div>
      </div>

    </div>
  </div>

  <?php
      include_once('include/footer.php');
  ?>