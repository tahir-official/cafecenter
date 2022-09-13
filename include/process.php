<?php
include_once('../include/functions.php');
$commonFunction= new functions();
/*login action start*/
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'login'){  
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//username and password check statement
		if($_POST['username'] == NULL && $_POST['password'] == NULL) {
		
			$output['message'] = '<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Enter Username or Password !!</div>';
			$output['status'] = 0;
		} else{

			$username=$_POST['username'];
			$password=$_POST['password'];
            $url=SSOAPI.'user_login';
            $data=array(
                'api_key' => API_KEY,
                'portal' => 'main',
                'username' => $username,
                'password' => $password
            );
            $method='POST';
            $response=$commonFunction->curl_call($url,$data,$method);
            $result = json_decode($response);
			
            if($result->status != 0){
				$_SESSION['is_user_logged_in'] = true;
				$_SESSION['user_id'] =$user_id= $result->user_id;
				$_SESSION['user_type'] = $result->user_type;
				$_SESSION['user_email'] = $result->user_email;
				$_SESSION['contact_number'] = $result->user_contact_number;
				$_SESSION['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
		        $output['status']=1;
				$get_main_portal_detail=$commonFunction->get_main_portal_detail();
				$portal_detail=$get_main_portal_detail->data;
				if(ENV=='prod'){
					$site_url=$portal_detail->PORTAL_URL;
				}else{
					$site_url=LOCAL_URL;
				}
				$output['url']=$site_url.'dashboard.php';
				
				if(!empty($_POST["remember"])) {
					setcookie ("loginId", $username, time()+ (10 * 365 * 24 * 60 * 60));  
					setcookie ("loginPass",	$password,	time()+ (10 * 365 * 24 * 60 * 60));
				} else {
					setcookie ("loginId",""); 
					setcookie ("loginPass","");
				}
				
				
			}else{
				//error message
		        $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
		        $output['status']=0;
			}

    }

	}else{
		//error message
		$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
	}
echo json_encode($output);
}
/*login action end*/

/*signup action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_user')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		    if($_FILES["document"]["error"] == 0) {
			$image_status=1;
			$document = new CurlFile($_FILES['document']['tmp_name'], $_FILES['document']['type'], $_FILES['document']['name']);
			} else {
			$image_status=0;
			$document = '';
			}

			if($_POST['page']=='signup'){
				$password=$_POST['password'];
				$added_by='self';
				$added_id=1;
				$phone_verification=0;
				$otp=rand(100000,999999);
			}else if($_POST['page']=='manager_page'){
				$parts = explode('-', $_POST['dob']);
				$password='Welcome@'.$parts[0].mt_rand(10,99);
				if($_SESSION['manager_type']==1){
					$added_by='district_managers';
				}else if($_SESSION['manager_type']==2){
					$added_by='distributor';
				}
				
				$added_id=$_SESSION['manager_id'];
				$phone_verification=1;
				$otp='';
			}

			$url=SSOAPI.'add_user';
			
			$data=array(
				'user_type' => $_POST['user_type'],
				'fname' => $_POST['fname'],
				'lname' => $_POST['lname'],
				'email' => $_POST['email'],
				'password' => $password,
				'contact_number' => $_POST['contact_number'],
				'address' => $_POST['address'],
				'state' => $_POST['state'],
				'district' => $_POST['district'],
				'city' => $_POST['city'],
				'zipcode' => $_POST['zipcode'],
				'gender' => $_POST['gender'],
				'dob' => $_POST['dob'],
				'image_status'=> $image_status,
				'document'=> $document,
				'added_by' => $added_by,
				'added_id' => $added_id,
				'email_verification' => '1',
				'phone_verification' => $phone_verification,
				'status' => '1',
				'cdate' => date('Y-m-d H:i:s'),
				'api_key' => API_KEY,
				'otp' => $otp
		  );
			if($_POST['user_type']==3){
				$data2=array("shopname"=>$_POST['shopname']);
				$data=array_merge($data,$data2);
		  }
			$method='POST';
			$response=$commonFunction->curl_call($url,$data,$method);
            $result = json_decode($response);
			if($result->status != 0){
				
				if($_POST['page']=='signup'){
					$_SESSION['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.', Please enter OTP and verify your number !!</div>';
					$get_main_portal_detail=$commonFunction->get_main_portal_detail();
					$portal_detail=$get_main_portal_detail->data;
					if(ENV=='prod'){
						$site_url=$portal_detail->PORTAL_URL;
					}else{
						$site_url=LOCAL_URL;
					}
					$output['url']=$site_url.'otpverification.php?number='.$_POST['contact_number'].'&page='.$_POST['page'];
				}else if($_POST['page']=='manager_page'){
					$output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
					$output['fetchTableurl']= SSOAPI.'get_user_table_list';  
                    $output['user_type']=   $_POST['user_type'];
					$output['portal']=   'manager'; 
					$output['show_by']=   $_SESSION['manager_id'];
				}

				
				$output['status']=1;
			}else{
				//error message
		        $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
		        $output['status']=0;
			}

	}else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
			$output['status']=0;
			
	}
	echo json_encode($output);	
}
/*signup action end*/

/*contact request action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'contact_request')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$url=SSOAPI.'contact_request';
		$data=array(
			
			'full_name' => $_POST['full_name'],
			'email' => $_POST['email'],
            'subject' => $_POST['subject'],
            'message' => $_POST['message'],
			'api_key' => API_KEY
			
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		if($result->status != 0){
			$output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
			$output['status']=1;
			

		}else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
			$output['status']=0;
	    }
	}
	else{
		//error message
		$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
		
	}
  echo json_encode($output);
} 
/*contact request action end*/

/*send otp action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'send_otp')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$url=SSOAPI.'send_otp';
		$data=array(
			
			'page' => $_POST['page'],
			'number' => $_POST['cnumber'],
			'api_key' => API_KEY
			
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		if($result->status != 0){

			    $_SESSION['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
					
					$get_main_portal_detail=$commonFunction->get_main_portal_detail();
					$portal_detail=$get_main_portal_detail->data;
					if(ENV=='prod'){
						$site_url=$portal_detail->PORTAL_URL;
					}else{
						$site_url=LOCAL_URL;
					}
					$output['url']=$site_url.'otpverification.php?number='.$_POST['cnumber'].'&page='.$_POST['page'];
				    $output['status']=1;
		}else{
				//error message
		    $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
		    $output['status']=0;
		}
	}
  else{
		//error message
		$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
		
  }
  echo json_encode($output);
}
/*send otp action end*/

/*otpverify action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'otp_verify')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$url=SSOAPI.'otp_verify';
		$data=array(
			
			'page' => $_POST['page'],
			'number' => $_POST['number'],
			'otp' => $_POST['otp'],
			'api_key' => API_KEY
			
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		if($result->status != 0){
			 if($result->page != 'forget'){

					$_SESSION['is_user_logged_in'] = true;
					$_SESSION['user_id'] =$manager_id= $result->user_id;
					$_SESSION['user_type'] = $result->user_type;
					$_SESSION['user_email'] = $result->user_email;
					$_SESSION['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
					$output['status']=1;
					$get_main_portal_detail=$commonFunction->get_main_portal_detail();
					$portal_detail=$get_main_portal_detail->data;
					if(ENV=='prod'){
						$site_url=$portal_detail->PORTAL_URL;
					}else{
						$site_url=LOCAL_URL;
					}
					$output['url']=$site_url.'dashboard.php';

			 }else{
					$output['status']=1;
					$get_main_portal_detail=$commonFunction->get_main_portal_detail();
					$portal_detail=$get_main_portal_detail->data;
					if(ENV=='prod'){
						$site_url=$portal_detail->PORTAL_URL;
					}else{
						$site_url=LOCAL_URL;
					}
					$output['url']=$site_url.'reset.php?token='.$result->token;
			 }
		}else{
				//error message
		    $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
		    $output['status']=0;
		}

	}else{
		//error message
		$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
		
}
  echo json_encode($output);
}
/*otpverify action end*/


/*reset password action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'reset_password')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$url=SSOAPI.'reset_password';
		$data=array(
			
			'token' => $_POST['token'],
			'password' => $_POST['password'],
			'api_key' => API_KEY
			
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		if($result->status != 0){
			$_SESSION['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
			$output['status']=1;
			$get_main_portal_detail=$commonFunction->get_main_portal_detail();
			$portal_detail=$get_main_portal_detail->data;
			if(ENV=='prod'){
				$site_url=$portal_detail->PORTAL_URL;
			}else{
				$site_url=LOCAL_URL;
			}
			$output['url']=$site_url.'login.php';

		}else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
			$output['status']=0;
	    }
	}
	else{
		//error message
		$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
		
	}
  echo json_encode($output);
} 
/*reset password action end*/
/*get distric action start*/

else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_distric')
{ 
   //method check statement
	 if ($_SERVER["REQUEST_METHOD"] == "POST"){
			$result=$commonFunction->distric_list($_POST['state_id']);
			if($result->status != 0){
			 $district_data=$result->data;
       $distric_html='<option value="">Select District</option>';
			 foreach($district_data as $district){
				$distric_html .='<option value="'.$district->districtid.'">'.$district->district_title.'</option>';
			 }

			  $output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
			  $output['status']=1;
			  $output['html']=$distric_html;

			}else{
				$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
			  $output['status']=0;
			  $output['html']='<option value="">Select District</option>';
			}

	 }
	 else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
			$output['status']=0;
			$output['html']='<option value="">Select District</option>';
	}
	echo json_encode($output);
}
/*get distric action end*/


/*edit profile action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_profile_image')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){

	  	if($_FILES["profile_image"]["error"] == 0) {
			
			    $profile = new CurlFile($_FILES['profile_image']['tmp_name'], $_FILES['profile_image']['type'], $_FILES['profile_image']['name']);
				  $url=SSOAPI.'edit_profile_image';
					$data=array(
						'row_id' => $_SESSION['user_id'],
						'user_type' => $_SESSION['user_type'],
						'profile'=> $profile,
						'api_key' => API_KEY
					);
					$method='POST';
					$response=$commonFunction->curl_call($url,$data,$method);
					$result = json_decode($response);
					if($result->status != 0){
						
						$output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
						$output['status']=1;
						$output['profile_url']=$result->profile_url;

					}else{
						//error message
						$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
						$output['status']=0;
					}
			} else {
				$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
				$output['status']=0;
			}
		  
	}else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
			$output['status']=0;
			
	}
	echo json_encode($output);	
}
/*edit profile action end*/



/*edit user action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_users')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
			if($_FILES["document"]["error"] == 0) {
				$image_status=1;
				$document = new CurlFile($_FILES['document']['tmp_name'], $_FILES['document']['type'], $_FILES['document']['name']);
			} else {
					$image_status=0;
					$document = '';
					
			}

			if($_POST['page']=='edit_user'){
				$row_id= $_SESSION['user_id'];
				$user_type=$_SESSION['user_type'];
				$email=$_SESSION['user_email'];
				$contact_number=$_SESSION['user_number'];
			}else if($_POST['page']=='manager_page'){
				$row_id=$_POST['row_id'];
				$user_type=$_POST['user_type'];
				$email=$_POST['email'];
				$contact_number=$_POST['contact_number'];
			}


			$url=SSOAPI.'edit_user';
			$data=array(
				'row_id' => $row_id,
				'user_type' => $user_type,
				'fname' => $_POST['fname'],
				'lname' => $_POST['lname'],
				'email' => $email,
				'contact_number' => $contact_number,
				'address' => $_POST['address'],
				'state' => $_POST['state'],
				'district' => $_POST['district'],
				'city' => $_POST['city'],
				'zipcode' => $_POST['zipcode'],
				'gender' => $_POST['gender'],
				'dob' => $_POST['dob'],
				'image_status'=> $image_status,
				'document'=> $document,
				'api_key' => API_KEY
			);
			if($user_type==3){
				$data2=array("shopname"=>$_POST['shopname']);
				$data=array_merge($data,$data2);
		  }
			$method='POST';
			$response=$commonFunction->curl_call($url,$data,$method);
            $result = json_decode($response);
			if($result->status != 0){
				if($_POST['page']=='edit_user'){
					$alert_msg='Your profile updated Successfully';
					$output['manager_name']=ucfirst($_POST['fname']).' '.ucfirst($_POST['lname']);
				}else if($_POST['page']=='manager_page'){
					$alert_msg=$result->message;
					$output['fetchTableurl']= SSOAPI.'get_user_table_list';  
                    $output['user_type']=   $_POST['user_type'];
					$output['portal']=   'manager'; 
					$output['show_by']=   $_SESSION['manager_id'];
				}
				$output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$alert_msg.' !!</div>';
				$output['status']=1;
				

			}else{
				//error message
		    $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
		    $output['status']=0;
			}

	}else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
			$output['status']=0;
			
	}
	echo json_encode($output);	
}
/*edit user action end*/


/*update password action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'changePassword')
{ 
	 //method check statement
	 if($_SERVER["REQUEST_METHOD"] == "POST"){
		$url=SSOAPI.'change_password';
		$data=array(
			'api_key' => API_KEY,
			'current_password' => $_POST['current_password'],
			'new_password' => $_POST['new_password'],
			'row_id' => $_SESSION['user_id'],
			'user_type' => $_SESSION['user_type'],
			
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		if($result->status != 0){
    	$output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
			$output['status']=1;
		}else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
			$output['status']=0;
	  }

	}else{
			//error message
			$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
			$output['status']=0;
			
	}
	echo json_encode($output);
}
/*update password action end*/


/*get paywal action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_paywall')
{ 
	 //method check statement
	 if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		        $url=SSOAPI.'get_plan_by_user_type';
				$data=array(
						'user_type' => $_SESSION['user_type'],
						'api_key' => API_KEY
				);
				$method='POST';
				$response=$commonFunction->curl_call($url,$data,$method);
				$result = json_decode($response);
				if($result->status != 0){
					$plan_data=$result->data;
					$get_main_portal_detail=$commonFunction->get_main_portal_detail();
					$portal_detail=$get_main_portal_detail->data;
					if(ENV=='prod'){
						$site_url=$portal_detail->PORTAL_URL;
					}else{
						$site_url=LOCAL_URL;
					}
					$logout=$site_url.'logout.php';
					$payurl=$site_url.'pay.php';
					$content ='<div style="background: white;border: 2px solid black;border-radius: 10px;padding: 50px 30px 50px 30px;"  class="col-md-6 col-md-offset-3">
					<hgroup>
						<h2>
						'.$plan_data->plan_heading.'
						</h2>
						<h1 class="free">Only in '.$portal_detail->CURRENCY.' '.$plan_data->plan_amount.'</h1>
					</hgroup>
					
					<div class="well">
					        <button type="button" class="btn btn-info btn-lg first" >Subscribe</button><br>
							
							<br>
							<a class="btn btn-danger btn-lg" href="'.$logout.'">Logout</a>
							
					</div>
					
					</div>';
					$status=$result->status;
					$output['img']=$plan_data->plan_bg;

				}else{
					//error message
					$msg=$result->message;
					$content ='<div style="background: white;border: 2px solid black;border-radius: 10px;padding: 50px 30px 50px 30px;"  class="col-md-6 col-md-offset-3">
					<hgroup>
						<h2>
						'.$msg.'
						</h2>
						
					</hgroup>
					
					
					
					</div>';
					$status=$result->status;
				}
				$html='<style>
				select.frecuency {
					border: none;
					font-style: italic;
					background-color: transparent;
					cursor: pointer;
					-webkit-transform: translateY(0);
					transform: translateY(0);
					-webkit-transition: -webkit-transform .35s ease-in;
					transition: -webkit-transform .35s ease-in;
					border-bottom: none;
				}
				select.frecuency:focus {
					outline: none;
					border-bottom: 5px solid #39b3d7;
					-webkit-transform: translateY(-5px);
					transform: translateY(-5px);
					-webkit-transition: -webkit-transform .35s ease-in;
					transition: -webkit-transform .35s ease-in;
				}
				.free {
					text-transform: uppercase;
				}
				.input-group {
					margin: 20px auto;
					width: 100%;
				}
				input.btn.btn-lg,
				input.btn.btn-lg:focus {
					outline: none;
					width: 60%;
					height: 60px;
					border-top-right-radius: 0;
					border-bottom-right-radius: 0;
				}
				button.btn {
					width: 40%;
					height: 60px;
					border-top-left-radius: 0;
					border-bottom-left-radius: 0;
				}
				.promise {
					color: #999;
				}</style>
				<div class="container" style="padding-top: 100px;">
					
					<div class="row">
						<div class="col-md-3 col-md-offset-3">
						
						</div>
						'.$content.'
						<div class="col-md-3 col-md-offset-3">
						
						</div>
					</div>
				</div>
				';
			$output['html'] =$html;
			$output['status']=$status;
	 }else{
		  //error message
	  	$output['html'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
		
   }
echo json_encode($output);	
}



   