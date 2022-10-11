    <?php
      include_once('include/header.php');
      if($_SESSION['user_type']==4){
        $commonFunction->redirect('dashboard.php');
      }
    ?>
    <style>
      .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('assets/img/loader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
        background-size: 300px;
      }
    </style>
    <div class="container">
      <div class="page-banner">
        <div class="row justify-content-center align-items-center h-100">
          <div class="col-md-6">
            <nav aria-label="Breadcrumb">
              <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Blog</li>
              </ul>
            </nav>
            <h1 class="text-center">Blog</h1>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="page-section">
    <div class="container">
      
      <form id="blogFetch" onsubmit="return get_blogs();" class="form-search-blog">
      <div class="row">
        <div class="col-sm-10">
          
            <div class="input-group">
              <div class="input-group-prepend">
                <select id="qualification" name="qualification[]" class="custom-select bg-light" multiple>
                  <?php
                  
                  $qualification_list=$commonFunction->qualification_list(0);
                  $qualification_status=$qualification_list->status;
                  $qualification_message=$qualification_list->message;
                  $qualification_data=$qualification_list->data;
                  $qualification_option='';
                  if($qualification_status == 0){
                    $qualification_option='<option value="">'.$qualification_message.'</option>';
                    $qualification_disbale='disabled';
                  }else{
                    $qualification_disbale='';
                    //$qualification_option='<option value="">All Qualifications</option>';
                    foreach($qualification_data  as $qualification){
                        
                        $qualification_option.= '<option value="'.$qualification->term_id.'">'.$qualification->name.'</option>';
                    }
                  }
                  echo $qualification_option;
                  ?>
                  
                </select>
              </div>
              <div class="input-group-prepend">
                <select id="interest_with" name="interest_with[]" class="custom-select bg-light" multiple>
                  <?php
                  $interest_list=$commonFunction->interest_list(0);
                  $interest_status=$interest_list->status;
                  $interest_message=$interest_list->message;
                  $interest_data=$interest_list->data;
            
                  $interest_with_option='';
                  if($interest_status == 0){
                    $interest_with_option='<option value="">'.$qualification_message.'</option>';
                    $interest_with_disbale='disabled';
                  }else{
                    $interest_with_disbale='';
                    //$interest_with_option='<option value="">All Interests</option>';
                    foreach($interest_data  as $interest){
                      
                        $interest_with_option.= '<option value="'.$interest->term_id.'">'.$interest->name.'</option>';
                    }
                  }
                  echo $interest_with_option;
                  ?>
                  
                </select>
              </div>
              <div class="input-group-prepend">
                <select id="states" name="states" class="custom-select bg-light">
                  <?php
                  $state_list=$commonFunction->state_list();
                  $state_status=$state_list->status;
                  $state_message=$state_list->message;
                  $state_data=$state_list->data;
            
                  if($state_status == 0){
                    $state_option='<option value="">'.$state_message.'</option>';
                    $states_disbale='disabled';
                  }else{
                    $states_disbale='';
                    $state_option='<option value="">All States</option>';
                    foreach($state_data as $state){
                        $state_option.= '<option value="'.$state->state_id.'">'.$state->state_title.'</option>';
                    }
                  }
                  echo $state_option;
                  ?>
                  
                </select>
              </div>
              <input name="keyword" type="text" class="form-control" placeholder="Enter keyword..">
            </div>
          
        </div>
        <div class="col-sm-2 text-sm-right">
          <button type="submit" class="btn btn-secondary" id="searchBtn">Filter <span class="mai-filter"></span></button>
          
        </div>
        </div>
        </form>
      
      <div id="blog_list">
          
      </div>
      <div class="loader"></div>

      <!-- <nav aria-label="Page Navigation">
        <ul class="pagination justify-content-center">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item active" aria-current="page">
            <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
          </li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">Next</a>
          </li>
        </ul>
      </nav> -->

    </div>
  </div>

  <?php
      include_once('include/footer.php');
  ?>
<script>
jQuery(document).ready(function() {
  get_blogs();
});
</script>
  