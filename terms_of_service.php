<?php
   include_once('include/header.php');
   $page='182';
   $get_page_detail_by_id=$commonFunction->get_page_detail_by_id($page);
   $page_title=$get_page_detail_by_id->title;
   $page_detail=$get_page_detail_by_id->data;
   
   
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
      <div class="row">
      <div class="col-md-12">
        <?php echo $page_detail;?>
     </div>
      </div>
    </div> <!-- .container -->
  </div> <!-- .page-section -->
<?php
   include_once('include/footer.php');
?>