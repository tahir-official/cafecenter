<?php
   include_once('include/header.php');
   if(isset($_SESSION['is_user_logged_in'])){ $commonFunction->redirect('dashboard.php'); }      
?>
<div class="container">
   <div class="page-banner">
      <div class="row justify-content-center align-items-center h-100">
         <div class="col-md-6">
            <nav aria-label="Breadcrumb">
               <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                  <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                  <li class="breadcrumb-item active">Sign Up</li>
               </ul>
            </nav>
            <h1 class="text-center">Sign Up</h1>
         </div>
      </div>
   </div>
</div>
</header>
<div class="page-section">
   <div class="container">
      <div class="row">
         
         <div class="col-lg-12">
            <div class="register-box" style="width: auto;">
               <?php
                  if($get_main_portal_detail->status==1){
                  ?>
               
               <div class="card">
                  <div class="card-body register-card-body">
                     <p class="login-box-msg" style="text-align:center">Register a new <b>Retailer</b></p>
                     <div id="alert" ></div>
                     <form method="post" id="signupFrom">
                        <input type="hidden" name="page" value="signup">
                        <input type="hidden" name="action" value="add_user">
                        <input type="hidden" name="user_type" value="3">
                        
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="text" class="form-control" placeholder="First Name" name="fname" id="fname">
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="text" class="form-control" placeholder="Last Name" name="lname" id="lname">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="email" class="form-control" placeholder="Email Address" name="email" id="email">
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="text" class="form-control" placeholder="Mobile No." name="contact_number" maxlength="10" id="contact_number">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group">
                                
                                 <input id="shopname" name="shopname" class="form-control" type="text" placeholder="Shop Name">
                              </div>
                           </div>
                           <div class="col-sm-6">
                           <div class="form-group">
                                 <input type="text" class="form-control" placeholder="Address" name="address" id="address">
                            </div>
                           </div>
                        </div>
                        
                        <?php
                           $state_list=$commonFunction->state_list();
                           $state_status=$state_list->status;
                           $state_message=$state_list->message;
                           $state_data=$state_list->data;
                           ?>
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <select class="form-control" name="state" id="state" <?php if($state_status == 0){ echo 'disabled'; }?> onchange="return loadDistric(this.value)">
                                 <?php 
                                    if($state_status == 0){
                                      echo '<option value="">'.$state_message.'</option>';
                                    }else{
                                      echo '<option value="">Select State</option>';
                                      foreach($state_data as $state){
                                        echo '<option value="'.$state->state_id.'">'.$state->state_title.'</option>';
                                    }
                                    }
                                    ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <select class="form-control" name="district" id="district">
                                    <option value="">Select District</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="text" class="form-control" placeholder="City" name="city" id="city">
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="text" class="form-control" placeholder="Zipcode" name="zipcode" id="zipcode">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="form-group col-md-6">
                              <select id="gender" name="gender" class="form-control" >
                                 <option value="">Select Gender</option>
                                 <option value="male">Male</option>
                                 <option value="female">Female</option>
                                 <option value="Other">other</option>
                              </select>
                           </div>
                           <div class="form-group col-md-4">
                              <input type="text" class="form-control" id="dob" name="dob" max="<?php echo date('Y-m-d')?>" value="" placeholder="Dob" onfocus="(this.type='date')"
                                 onblur="(this.type='text')"> 
                           </div>
                           <div class="form-group col-md-2">
                              <input type="file" class="form-control" id="document" name="document" accept=".jpg, .jpeg, .pdf" placeholder="ID Proof" title="ID Proof (50 to 200 kb)">
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="form-group">
                                 <input type="password" class="form-control" placeholder="Confirm Password " name="c_password" id="c_password">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-8">
                              <div class="icheck-primary">
                                 <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                 <label for="agreeTerms">
                                 I agree to the <a href="terms_of_service.php" target="_blank" >terms</a>
                                 </label>
                              </div>
                           </div>
                           <!-- /.col -->
                           <div class="col-4">
                              <button type="submit" class="btn btn-primary btn-block btnsbt" id="sdbtn" disabled>Register</button>
                           </div>
                           <!-- /.col -->
                        </div>
                     </form>
                     <a href="login.php" class="text-center">I already have a <b>Retailer</b></a>
                  </div>
                  <!-- /.form-box -->
               </div>
               <!-- /.card -->
               <?php
                  }
                  else{
                  ?>
               <div class="card">
                  <div class="card-body register-card-body">
                     <div class="alert alert-danger" role="alert">
                        <?php echo $get_main_portal_detail->message?>
                     </div>
                  </div>
                  <!-- /.form-box -->
               </div>
               <!-- /.card -->
               <?php
                  }
                  ?>  
            </div>
         </div>
         
      </div>
   </div>
</div>
<?php
   include_once('include/footer.php');
?>
