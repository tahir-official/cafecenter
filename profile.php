<?php
   include_once('include/header.php');
   include ("include/redirectcondtion.php");
         
?>
<div class="paywall_r">
<div class="container">
   <div class="page-banner">
      <div class="row justify-content-center align-items-center h-100">
         <div class="col-md-6">
            <nav aria-label="Breadcrumb">
               <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                  <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                  <li class="breadcrumb-item active">Profile</li>
               </ul>
            </nav>
            <h1 class="text-center">Profile</h1>
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
                        <h2 class="h2_hedddin">Profile</h2>
                        <div class="rightt">
                        <form class="form-horizontal" name="edit_form" id="edit_form" method="post" >

                              
                              <input type="hidden" name="page" value="edit_user">
                              <div class="form-group row">
                                 <label for="fname" class="col-sm-2 col-form-label">First Name</label>
                                 <div class="col-sm-10">
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" value="<?=$user_data->fname?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="lname" class="col-sm-2 col-form-label">Last Name</label>
                                 <div class="col-sm-10">
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" value="<?=$user_data->lname?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="email" class="col-sm-2 col-form-label">Email</label>
                                 <div class="col-sm-10">
                                    <input readonly type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?=$user_data->email?>">
                                 </div>
                              </div>

                              <div class="form-group row">
                                 <label for="contact_number" class="col-sm-2 col-form-label">Mobile No.</label>
                                 <div class="col-sm-10">
                                    <input readonly type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Mobile No." value="<?=$user_data->contact_number?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="address" class="col-sm-2 col-form-label">Address</label>
                                 <div class="col-sm-10">
                                 <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?=$user_data->address?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="shopname" class="col-sm-2 col-form-label">Shopname</label>
                                 <div class="col-sm-10">
                                 <input type="text" class="form-control" id="shopname" name="shopname" placeholder="Shopname" value="<?=$user_data->shopname?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="state" class="col-sm-2 col-form-label">State</label>
                                 <div class="col-sm-10">
                                 <?php
                                 $state_list=$commonFunction->state_list();
                                 $state_status=$state_list->status;
                                 $state_message=$state_list->message;
                                 $state_data=$state_list->data;
                                 ?>
                                 <select class="form-control" name="state" id="state" <?php if($state_status == 0){ echo 'disabled'; }?> onchange="return loadDistric(this.value)">
                                    <?php 
                                    if($state_status == 0){
                                    echo '<option value="">'.$state_message.'</option>';
                                    }else{
                                    echo '<option value="">Select State</option>';
                                    foreach($state_data as $state){
                                       $state_selected='';
                                       if($user_data->state==$state->state_id){
                                       $state_selected='selected';    
                                       }
                                    echo '<option '.$state_selected.' value="'.$state->state_id.'">'.$state->state_title.'</option>';
                                    }
                                    }
                                    ?>
                                    
                                 </select>
                                 </div>
                              </div>
                              

                              <div class="form-group row">
                                 <label for="district" class="col-sm-2 col-form-label">District</label>
                                 <div class="col-sm-10">
                                 
                                 
                                 <?php
                                 $distric_list=$commonFunction->distric_list($user_data->state);
                                 $distric_status=$distric_list->status;
                                 $distric_message=$distric_list->message;
                                 $distric_data=$distric_list->data;
                                 ?>
                                 <select class="form-control" name="district" id="district" <?php if($distric_status == 0){ echo 'disabled'; }?>>
                                    <?php 
                                    if($distric_status == 0){
                                    echo '<option value="">'.$distric_message.'</option>';
                                    }else{
                                    echo '<option value="">Select District</option>';
                                    foreach($distric_data as $distric){
                                       $district_selected='';
                                       if($user_data->district==$distric->districtid){
                                       $district_selected='selected';    
                                       }
                                    echo '<option '.$district_selected.' value="'.$distric->districtid.'">'.$distric->district_title.'</option>';
                                    }
                                    }
                                    ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="city" class="col-sm-2 col-form-label">City</label>
                                 <div class="col-sm-10">
                                    <input  type="text" class="form-control" id="city" name="city" placeholder="City" value="<?=$user_data->city?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="zipcode" class="col-sm-2 col-form-label">Zipcode</label>
                                 <div class="col-sm-10">
                                    <input  type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Zipcode" value="<?=$user_data->zipcode?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                                 <div class="col-sm-10">
                                 <select id="gender" name="gender" class="form-control" >
                                    <option value="">Select Gender</option>
                                    <?php
                                    $gender=$user_data->gender;
                                    $male_selected='';
                                    $female_selected='';
                                    $other_selected='';
                                    if($gender=='male'){
                                       $male_selected='selected';
                                    }
                                    if($gender=='female'){
                                       $female_selected='selected';
                                    }
                                    if($gender=='other'){
                                       $other_selected='selected';
                                    }
                                    echo '<option value="male" '.$male_selected.'>Male</option>
                                                   <option value="female" '.$female_selected.'>Female</option>
                                                   <option value="other" '.$other_selected.'>Other</option>';
                                    
                                    ?>
                                       
                                 </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="dob" class="col-sm-2 col-form-label">Dob</label>
                                 <div class="col-sm-10">
                                    <input  type="date" class="form-control" id="dob" name="dob" placeholder="DOB" max="<?php echo date('Y-m-d');?>" value="<?=$user_data->dob?>">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="document" class="col-sm-2 col-form-label">ID Proof</label>
                                 <div class="col-sm-10">
                                 <input type="file" class="form-control" id="document" name="document" accept=".jpg, .jpeg, .pdf">
                                 </div>
                              </div>
                              
                              <div class="form-group row">
                                 <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn_theme btn-lg btnsbt">Submit</button>
                                 </div>
                              </div>
                              </form>
                           
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>
</div>
<?php
   //print_r($_SESSION);
   include_once('include/footer.php');
?>
