    <?php
      include_once('include/header.php');
      $page='230';
      $get_page_detail_by_id=$commonFunction->get_page_detail_by_id($page);
      $page_title=$get_page_detail_by_id->title;
      $page_detail=$get_page_detail_by_id->data;
      $meta=$get_page_detail_by_id->meta;
      $about_us_image=$commonFunction->get_attachment_image_src($meta->about_us_image[0]);
    ?>

    <div class="container">
      <div class="page-banner">
        <div class="row justify-content-center align-items-center h-100">
          <div class="col-md-6">
            <nav aria-label="Breadcrumb">
              <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                <li class="breadcrumb-item active"><?php echo $page_title;?></li>
              </ul>
            </nav>
            <h1 class="text-center"><?php echo $page_title;?></h1>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="page-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 py-3">
          <h2 class="title-section"><?php echo $meta->about_us_heading[0];?></h2>
          <div class="divider"></div>

          <?php echo $page_detail;?>
        </div>
        <div class="col-lg-6 py-3">
          <div class="img-fluid py-3 text-center">
            <img src="<?php echo $about_us_image->image_src;?>" alt="<?php echo $page_title;?>">
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
      include_once('include/footer.php');
  ?>