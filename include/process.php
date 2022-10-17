<?php
include_once('../include/functions.php');
$commonFunction= new functions();
require('../razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
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
				$qualification_status='ok';
				$interest_with_status='ok';
			}else if($_POST['page']=='manager_page'){
				$parts = explode('-', $_POST['dob']);
				$password='Welcome@'.$parts[0].mt_rand(10,99);
				if($_SESSION['user_type']==1){
					$added_by='district_managers';
				}else if($_SESSION['user_type']==2){
					$added_by='distributor';
				}else if($_SESSION['user_type']==3){
					$added_by='retailer';
				}
				
				$added_id=$_SESSION['user_id'];
				$phone_verification=1;
				$otp='';
				$subscription_status=1;

				if(!empty($_POST['qualification'])){
					$qualification = implode(',', $_POST['qualification']);
					$qualification_status='ok';
				}else{
					$qualification_status='notok';
				}

				if(!empty($_POST['interest_with'])){
					$interest_with = implode(',', $_POST['interest_with']);
					$interest_with_status='ok';
				}else{
					$interest_with_status='notok';
				}
			}
			
			
			
			if($qualification_status=='ok'){
				
				if($interest_with_status=='ok'){
					
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
					}else if($_POST['user_type']==4){
						$data2=array("consumer_plan"=>$_POST['consumer_plan'],"qualification"=>$qualification,"interest_with"=>$interest_with,"subscription_status"=>$subscription_status);
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
							$output['portal']=   'main'; 
							$output['show_by']=   $_SESSION['user_id'];
						}
		
						
						$output['status']=1;
					}else{
						
						$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
						$output['status']=0;
					}

				}else{
				//error message
			    $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Please select at least one Interest !!</div>';
			    $output['status']=0;
			    }

			}else{
				//error message
			    $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Please select at least one qualification !!</div>';
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
				$contact_number=$_SESSION['contact_number'];
				$qualification_status='ok';
				$interest_with_status='ok';
				if($user_type==4){
					if(!empty($_POST['qualification'])){
						$qualification = implode(',', $_POST['qualification']);
						$qualification_status='ok';
					}else{
						$qualification_status='notok';
					}
	
					if(!empty($_POST['interest_with'])){
						$interest_with = implode(',', $_POST['interest_with']);
						$interest_with_status='ok';
					}else{
						$interest_with_status='notok';
					}
				}
			}else if($_POST['page']=='manager_page'){
				$row_id=$_POST['row_id'];
				$user_type=$_POST['user_type'];
				$email=$_POST['email'];
				$contact_number=$_POST['contact_number'];
				if(!empty($_POST['qualification'])){
					$qualification = implode(',', $_POST['qualification']);
					$qualification_status='ok';
				}else{
					$qualification_status='notok';
				}

				if(!empty($_POST['interest_with'])){
					$interest_with = implode(',', $_POST['interest_with']);
					$interest_with_status='ok';
				}else{
					$interest_with_status='notok';
				}
			}

			if($qualification_status=='ok'){
				if($interest_with_status=='ok'){
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
					}else if($user_type==4){
						$data2=array("qualification"=>$qualification,"interest_with"=>$interest_with);
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
							$output['portal']=   'main'; 
							$output['show_by']=   $_SESSION['user_id'];
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
					$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Please select at least one Interest !!</div>';
					$output['status']=0;
				}

			}
			else{
				//error message
			    $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Please select at least one qualification !!</div>';
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


/*verify payment action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'verify_payment')
{ 
	
	$get_main_portal_detail=$commonFunction->get_main_portal_detail();
	$portal_detail=$get_main_portal_detail->data;

	$success = true;

	$error = "Payment Failed";

	if (empty($_POST['razorpay_payment_id']) === false)
	{
		$api = new Api($portal_detail->keyId, $portal_detail->keySecret);

		try
		{
			// Please note that the razorpay order ID must
			// come from a trusted source (session here, but
			// could be database or something else)
			$attributes = array(
				'razorpay_order_id' => $_SESSION['razorpay_order_id'],
				'razorpay_payment_id' => $_POST['razorpay_payment_id'],
				'razorpay_signature' => $_POST['razorpay_signature']
			);

			$api->utility->verifyPaymentSignature($attributes);
		}
		catch(SignatureVerificationError $e)
		{
			$success = false;
			$error = 'Razorpay Error : ' . $e->getMessage();
		}
	}

	if ($success === true)
	{   
		
		$url=SSOAPI.'get_plan_by_user_type';
		$data=array(
			'user_type' => $_SESSION['user_type'],
			'api_key' => API_KEY
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		$plan_data=$result->data;

		$user_detail=$commonFunction->user_detail($_SESSION['user_id']);
        $user_data=$user_detail->data;

		$district_manager_commission_percentage=0;
		$district_manager_commission_amount=0;
		$district_manager_id='';
		$distributor_commission_percentage=0;
		$distributor_commission_amount=0;
		$distributor_id='';
		$admin_amount= $plan_data->plan_amount;

		if($_SESSION['user_type']==1){
			
			$district_manager_commission_percentage=0;
			$district_manager_commission_amount=0;
			$district_manager_id='';
			$distributor_commission_percentage=0;
			$distributor_commission_amount=0;
			$distributor_id='';
			$admin_amount= $plan_data->plan_amount;

		}else if($_SESSION['user_type']==2){
			$added_by=$user_data->added_by;
			if($added_by=='admin' || $added_by=='self'){
				$district_manager_commission_percentage=0;
				$district_manager_commission_amount=0;
				$district_manager_id='';
				$distributor_commission_percentage=0;
				$distributor_commission_amount=0;
				$distributor_id='';
				$admin_amount= $plan_data->plan_amount;

			}else{
				
                //DM Commission for distributer
				$district_manager_commission_percentage=$plan_data->district_manager_commission;
				$district_manager_commission_amount = ($district_manager_commission_percentage / 100) * $plan_data->plan_amount;
				$district_manager_id=$user_data->added_id;
                $admin_amount= $plan_data->plan_amount - $district_manager_commission_amount;

				$distributor_commission_percentage=0;
				$distributor_commission_amount=0;
				$distributor_id='';
				

			}
		}else if($_SESSION['user_type']==3){
			$added_by=$user_data->added_by;
			if($added_by=='admin' || $added_by=='self'){
				$district_manager_commission_percentage=0;
				$district_manager_commission_amount=0;
				$district_manager_id='';
				$distributor_commission_percentage=0;
				$distributor_commission_amount=0;
				$distributor_id='';
				$admin_amount= $plan_data->plan_amount;

			}else{
				//D Commission for retailer
				$distributor_commission_percentage=$plan_data->distributor_commission;
				$distributor_commission_amount=($distributor_commission_percentage / 100) * $plan_data->plan_amount;
				$distributor_id=$user_data->added_id;
                $admin_amount= $plan_data->plan_amount - $distributor_commission_amount;

				$district_manager_commission_percentage=0;
				$district_manager_commission_amount = 0;
				$district_manager_id='';

				//DM Commission for retailer
				$d_user_detail=$commonFunction->user_detail($user_data->added_id);
                $d_user_data=$d_user_detail->data;
				$d_added_by=$d_user_data->added_by;
				if($d_added_by=='admin' || $d_added_by=='self'){
					$district_manager_commission_percentage=0;
				    $district_manager_commission_amount = 0;
				    $district_manager_id='';
				}else{
					$district_manager_commission_percentage=$plan_data->district_manager_commission;
				    $district_manager_commission_amount = ($district_manager_commission_percentage / 100) * $plan_data->plan_amount;
				    $district_manager_id=$d_user_data->added_id;
					$admin_amount=$admin_amount-$district_manager_commission_amount;
				}


                
                
			}

		}

		$url=SSOAPI.'subscription_payment_process';
		$data=array(
			'api_key' => API_KEY,
			'portal' => 'manager',
			'razorpay_payment_id' => $_POST['razorpay_payment_id'],
			'razorpay_order_id' => $_SESSION['razorpay_order_id'],
			'razorpay_signature' => $_POST['razorpay_signature'],
			'plan_amount' => $plan_data->plan_amount,
			'currency' => 'INR',
			'payment_date' => date('Y-m-d H:i:s'),
			'manager_id' => $_SESSION['user_id'],
			'manager_type' => $_SESSION['user_type'],
			'manager_name' => $user_data->fname.' '.$user_data->lname,
			'manager_email' => $user_data->email,
			'manager_contact_number' => $user_data->contact_number,
			'manager_address' => $user_data->address,
			'subscription_date' => date('Y-m-d H:i:s'),
			'plan_id' => $plan_data->plan_id,
			'plan_heading' => $plan_data->plan_heading,
            'district_manager_commission_percentage' => $district_manager_commission_percentage,
			'district_manager_commission_amount' => $district_manager_commission_amount,
			'district_manager_id' => $district_manager_id,
			'distributor_commission_percentage' => $distributor_commission_percentage,
			'distributor_commission_amount' => $distributor_commission_amount,
			'distributor_id' => $distributor_id,
			'admin_amount' => $admin_amount,
			'payment_mode' => 'online'
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		
		if($result->status != 0){
			
		    $_SESSION['message']='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
		}else{
		   
		    $_SESSION['message']='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
		}
		
		
		
	}
	else
	{
		        
		$_SESSION['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Your payment failed '.$error.' !!</div>';			
	}
	$commonFunction->redirect('../dashboard.php');

	
} 
/*verify payment action end*/


/*update password action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'recharge_request')
{ 
	 /*rozarpay setup start*/
	 $get_main_portal_detail=$commonFunction->get_main_portal_detail();
     $portal_detail=$get_main_portal_detail->data;

	 $user_detail=$commonFunction->user_detail($_SESSION['user_id']);
     $user_data=$user_detail->data;
     $user_type=$user_data->user_type;

	 $api = new Api($portal_detail->keyId, $portal_detail->keySecret);
	 $fourRandomDigit = rand(1000,9999);

	 $orderData = [
		 'receipt'         => 'recharge_'.$fourRandomDigit,
		 'amount'          => $_POST['request_amount'] * 100, // 2000 rupees in paise
		 'currency'        => 'INR',
		 'payment_capture' => 1 // auto capture
	 ];

	 $razorpayOrder = $api->order->create($orderData);

	 $razorpayOrderId = $razorpayOrder['id'];

	 $_SESSION['razorpay_order_id'] = $razorpayOrderId;
	 $_SESSION['recharge_amount'] = $_POST['request_amount'];

	 $displayAmount = $amount = $orderData['amount'];

	 if ($portal_detail->displayCurrency !== 'INR')
	 {
		 $url = "https://api.fixer.io/latest?symbols=$portal_detail->displayCurrency&base=INR";
		 $exchange = json_decode(file_get_contents($url), true);

		 $displayAmount = $exchange['rates'][$portal_detail->displayCurrency] * $amount / 100;
	 }
	 $merchant_order_id= $razorpayOrderId.'_'.rand(100000,999999);
	 $data = [
		 "key"               => $portal_detail->keyId,
		 "amount"            => $amount,
		 "name"              => $portal_detail->PROJECT,
		 "description"       => 'Please Recharge your wallet',
		 "image"             => $portal_detail->LOGO,
		 "prefill"           => [
		 "name"              => $user_data->fname.' '.$user_data->lname,
		 "email"             => $user_data->email,
		 "contact"           => $user_data->contact_number,
		 ],
		 "notes"             => [
		 "address"           => $user_data->address,
		 "merchant_order_id" => $merchant_order_id,
		 ],
		 "theme"             => [
		 "color"             => "#6C55F9"
		 ],
		 "order_id"          => $razorpayOrderId,
	 ];

	 if ($portal_detail->displayCurrency !== 'INR')
	 {
		 $data['display_currency']  = $portal_detail->displayCurrency;
		 $data['display_amount']    = $displayAmount;
	 }

	 echo $json = json_encode($data);
	
}
/*update password action end*/


/*recharge payment action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rechange_payment')
{ 
	$get_main_portal_detail=$commonFunction->get_main_portal_detail();
	$portal_detail=$get_main_portal_detail->data;

	$user_detail=$commonFunction->user_detail($_SESSION['user_id']);
    $user_data=$user_detail->data;

	$success = true;

	$error = "Payment Failed";

	if (empty($_POST['razorpay_payment_id']) === false)
	{
		$api = new Api($portal_detail->keyId, $portal_detail->keySecret);

		try
		{
			// Please note that the razorpay order ID must
			// come from a trusted source (session here, but
			// could be database or something else)
			$attributes = array(
				'razorpay_order_id' => $_SESSION['razorpay_order_id'],
				'razorpay_payment_id' => $_POST['razorpay_payment_id'],
				'razorpay_signature' => $_POST['razorpay_signature']
			);

			$api->utility->verifyPaymentSignature($attributes);
		}
		catch(SignatureVerificationError $e)
		{
			$success = false;
			$error = 'Razorpay Error : ' . $e->getMessage();
		}
	}

	if ($success === true)
	{
		$url=SSOAPI.'recharge_payment_process';
		$data=array(
			'api_key' => API_KEY,
			'portal' => 'main',
			'razorpay_payment_id' => $_POST['razorpay_payment_id'],
			'razorpay_order_id' => $_SESSION['razorpay_order_id'],
			'razorpay_signature' => $_POST['razorpay_signature'],
			'recharge_amount' => $_SESSION['recharge_amount'],
			'currency' => 'INR',
			'recharge_date' => date('Y-m-d H:i:s'),
			'user_id' => $_SESSION['user_id']
		);
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		
		if($result->status != 0){
			
		    $_SESSION['message']='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
		}else{
		   
		    $_SESSION['message']='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
		}
	}
	else
	{
		        
		$_SESSION['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Your recharge failed '.$error.' !!</div>';			
	}
	$commonFunction->redirect('../recharge.php');


}
/*recharge payment action end*/

/*load users popup action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_users_popup')
{ 
	//method check statement
	if ($_SERVER["REQUEST_METHOD"] == "POST"){

	if($_POST['user_type']==4){
		$user_title='Consumer';
		
    }
	$result_alert='';
	$get_main_portal_detail=$commonFunction->get_main_portal_detail();
	$portal_detail=$get_main_portal_detail->data;

	if($_POST['row_id']==0){
		    $readonly='';
			$popup_title='Add '.$user_title;
			$fname='';
			$lname='';
			$email='';
			$contact_number='';
			$address='';
			

			$state_list=$commonFunction->state_list();
			$state_status=$state_list->status;
			$state_message=$state_list->message;
			$state_data=$state_list->data;

			if($state_status == 0){
				$state_option='<option value="">'.$state_message.'</option>';
				$states_disbale='disabled';
			}else{
				$states_disbale='';
				$state_option='<option value="">Select State</option>';
				foreach($state_data as $state){
						$state_option.= '<option value="'.$state->state_id.'">'.$state->state_title.'</option>';
				}
			}
			
            $district_disbale='';
			$district_option='<option value="">Select District</option>';

			$city='';
			$zipcode='';
			$gender_option='<option value="male">Male</option>
										 <option value="female">Female</option>
										 <option value="Other">other</option>';

			$dob=''; 
			$download_link='';
			
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
				
				foreach($qualification_data  as $qualification){
						
						$qualification_option.= '<option value="'.$qualification->term_id.'">'.$qualification->name.'</option>';
				}
			}

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
				
				foreach($interest_data  as $interest){
					
						$interest_with_option.= '<option value="'.$interest->term_id.'">'.$interest->name.'</option>';
				}
			}

			$consumer_plan=$commonFunction->consumer_plan(0);
			$consumer_plan_status=$consumer_plan->status;
			$consumer_plan_message=$consumer_plan->message;
			$consumer_plan_data=$consumer_plan->data;

			if($consumer_plan_status == 0){
				$consumer_plan_option='<option value="">'.$consumer_plan_message.'</option>';
				$consumer_plan_disbale='disabled';
			}else{
				$consumer_plan_disbale='';
				$consumer_plan_option='<option value="">Select Plan</option>';
				foreach($consumer_plan_data  as $consumer_plan){
						
						$consumer_plan_option.= '<option value="'.$consumer_plan->id.'">'.$consumer_plan->post_title.' for '.$consumer_plan->plan_days.' Days only in '.$portal_detail->CURRENCY.$consumer_plan->plan_amount.'</option>';
				}
			}

			$consumer_plan_html='<div class="row">
					<div class="form-group col-md-12">
							<label for="consumer_plan">Select Consumer Plan</label>
							<select '.$consumer_plan_disbale.' id="consumer_plan" name="consumer_plan" class="form-control" >
									
									'.$consumer_plan_option.'
								
							</select>
					</div>
					
			</div>';

			$action='add_user';            
			
	}else{
			$popup_title='Edit '.$user_title;
			$readonly='readonly';
			
			$url=SSOAPI.'get_user_detail';
			$data=array(
					'user_id' => $_POST['row_id'],
					'api_key' => API_KEY
			);
			$method='POST';
			$response=$commonFunction->curl_call($url,$data,$method);
			$result = json_decode($response);
			if($result->status==0){
					$result_alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
												<strong>Error!</strong>  '.$result->message.' !!
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>';
			}else{
				    $action='edit_users';
					$result_alert='';
					$response_result=$result->data;
					$fname=$response_result->fname;
					$lname=$response_result->lname;
					$email=$response_result->email;
					$contact_number=$response_result->contact_number;
					$address=$response_result->address;
					

					
					$state_list=$commonFunction->state_list();
					$state_status=$state_list->status;
					$state_message=$state_list->message;
					$state_data=$state_list->data;

					if($state_status == 0){
						$states_disbale='disabled';
						$state_option='<option value="">'.$state_message.'</option>';
						
					}else{
						$states_disbale='';
						$state_option='<option value="">Select State</option>';
						foreach($state_data as $state){
						$state_selected='';
						if($response_result->state == $state->state_id){
						$state_selected='selected';    
						}
						$state_option .= '<option value="'.$state->state_id.'" '.$state_selected.'>'.$state->state_title.'</option>';
					  }
					}

					

					$district_list=$commonFunction->distric_list($response_result->state);
					$district_status=$district_list->status;
					$district_message=$district_list->message;
					$district_data=$district_list->data;
					

					if($district_status == 0){
						$district_disbale='disabled';
						$district_option='<option value="">'.$district_message.'</option>';
					}else{
						$district_disbale='';
						$district_option ='<option value="">Select District</option>';
						foreach($district_data as $district){
								$district_selected='';
								if($response_result->district==$district->districtid){
								$district_selected='selected';    
								}
								$district_option .= '<option value="'.$district->districtid.'" '.$district_selected.'>'.$district->district_title.'</option>';
						}
					}



					

					$city=$response_result->city;
					$zipcode=$response_result->zipcode;
					$gender=$response_result->gender;
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
					$gender_option='<option value="male" '.$male_selected.'>Male</option>
												 <option value="female" '.$female_selected.'>Female</option>
												 <option value="other" '.$other_selected.'>Other</option>';
					$dob=$response_result->dob;
					$download_link='<a href="'.$response_result->document.'" target="_blank">Download</a>';

					$consumer_plan_html='';

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
						$user_qualifications=explode (",", $response_result->qualification);
						foreach($qualification_data  as $qualification){
							    $qualification_selected='';
							    if(in_array($qualification->term_id, $user_qualifications)){ 
									$qualification_selected= 'selected'; 
								}
								
								$qualification_option.= '<option '.$qualification_selected.' value="'.$qualification->term_id.'">'.$qualification->name.'</option>';
						}
					}

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
						$user_intrest_withs=explode (",", $response_result->additional_qualification);
						foreach($interest_data  as $interest){
								$interest_selected='';
								if(in_array($interest->term_id, $user_intrest_withs)){ 
									$interest_selected= 'selected'; 
								}
								$interest_with_option.= '<option '.$interest_selected.' value="'.$interest->term_id.'">'.$interest->name.'</option>';
						}
					}



			}
			
	}

	$output['html']='<div class="modal-header pmd-modal-border">
							<h3 class="modal-title">'.$popup_title.'</h3>
							<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
					</div>
					
					<form name="users_form" id="users_form" method="post">
          <input type="hidden" name="page" value="manager_page">
          <input type="hidden" name="action" value="'.$action.'">
					<div class="modal-body">
							        <div id="popupalert">'.$result_alert.'</div>
									<input type="hidden" name="user_type" value="'.$_POST['user_type'].'">
									<input type="hidden" name="row_id" value="'.$_POST['row_id'].'">
									'.$consumer_plan_html.'
									<div class="row">
											<div class="form-group col-md-6">
													<label for="fname">First Name</label>
													<input id="fname" name="fname" class="form-control" type="text" value="'.$fname.'">
											</div>
											<div class="form-group col-md-6">
													<label for="lname">Last Name</label>
													<input id="lname" name="lname" class="form-control" type="text" value="'.$lname.'">
											</div>

									</div>
									<div class="row">
											<div class="form-group col-md-6">
													<label for="email">Email Address</label>
													<input '.$readonly.' type="email" class="mat-input form-control" id="email" name="email" value="'.$email.'">
											</div>
											<div class="form-group col-md-6">
													<label for="contact_number">Mobile No.</label>
													<input '.$readonly.' type="Text" class="form-control" id="contact_number" name="contact_number" maxlength="10" value="'.$contact_number.'">
											</div>

									</div>
									<div class="row">
											<div class="form-group col-md-12">
													<label for="address">Address</label>
													<input id="address" name="address" class="form-control" type="text" value="'.$address.'">
											</div>
											
									</div>
									
									<div class="row">
											<div class="form-group col-md-6">
													<label for="state">Select State</label>
													<select '.$states_disbale.' id="state" name="state" class="form-control" onchange="return loadDistric(this.value)">
															
															'.$state_option.'
														 
													</select>
											</div>
											<div class="form-group col-md-6">
													<label for="district">Select District</label>
													<select id="district" name="district" class="form-control" '.$district_disbale.'>
															'.$district_option.'
														 
													</select>
											</div>

									</div>
									
									<div class="row">
											<div class="form-group col-md-6">
													<label for="city">City</label>
													<input id="city" name="city" class="form-control" type="text" value="'.$city.'">
											</div>
											<div class="form-group col-md-6">
													<label for="zipcode">Zipcode</label>
													<input id="zipcode" name="zipcode" maxlength="6" class="form-control" type="text" value="'.$zipcode.'">
											</div>

									</div>


									<div class="row">
											<div class="form-group col-md-6">
													<label for="gender">Gender</label>
													<select id="gender" name="gender" class="form-control" >
															<option value="">Select Gender</option>
															'.$gender_option.'
														 
													</select>
											</div>

										 <div class="form-group col-md-4">
										 <label for="dob">Dob</label>
											<input type="date" class="form-control" id="dob" name="dob" max="'.date('Y-m-d').'" value="'.$dob.'"> 
										 </div>

										<div class="form-group col-md-2">
											<label for="document">Resume</label>
											<input type="file" class="form-control" id="document" name="document" accept=".jpg, .jpeg, .pdf">
											'.$download_link.'
										</div>
									</div>

									<div class="row">
											<div class="form-group col-md-6">
													<label for="qualification">Select Qualification</label>
													<select '.$qualification_disbale.' id="qualification" name="qualification[]" class="form-control" multiple required>
															
															'.$qualification_option.'
														 
													</select>
											</div>
											<div class="form-group col-md-6">
													<label for="interest_with">Select Interest with</label>
													<select '.$interest_with_disbale.' id="interest_with" name="interest_with[]" class="form-control" multiple required>
															
															'.$interest_with_option.'
														
													</select>
											</div>

									</div>
									
								 
							
					</div>
					<div class="modal-footer">
							<button data-dismiss="modal" class="btn pmd-ripple-effect btn-dark pmd-btn-flat" type="button">Cancel</button>
							<button  class="btn btnsbt pmd-ripple-effect btn-primary pmd-btn-flat" type="submit">Submit</button>
					</div>
					</form>'; 
	}else{
		//error message
		$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
		
}
echo json_encode($output);	
}
/*load users popup action end*/


/*update password action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'change_user_status')
{ 
	 //method check statement
	 if($_SERVER["REQUEST_METHOD"] == "POST"){
      $url=SSOAPI.'change_user_status';
			$data=array(
					'user_id' => $_POST['user_id'],
					'status' => $_POST['status'],
					'user_type' => $_POST['user_type'],
					'api_key' => API_KEY
			);
			$method='POST';
			$response=$commonFunction->curl_call($url,$data,$method);
			$result = json_decode($response);
			if($result->status != 0){
				
				$output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
				$output['status']=1;
				$output['fetchTableurl']= SSOAPI.'get_user_table_list';  
                $output['user_type']=   $_POST['user_type']; 
				$output['portal']=   'main'; 
				$output['show_by']=   $_SESSION['user_id'];

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

/*update password action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'detail_popup_user')
{ 
	 //method check statement
	 if($_SERVER["REQUEST_METHOD"] == "POST"){
				$url=SSOAPI.'get_user_detail';
				$data=array(
						'user_id' => $_POST['user_id'],
						'api_key' => API_KEY
				);
				$method='POST';
				$response=$commonFunction->curl_call($url,$data,$method);
				$result = json_decode($response);
				
				
				
				
				if($result->status==0){
						$title='Error';
						$html='<div class="alert alert-danger" role="alert">
																	'.$result->message.'
																</div>';
						$output['status']=0;										

				}else{
					  $output['status']=1;
						$response_result=$result->data;
						if($response_result->user_type==1){
								$title='District Manager Detail';
								$shopname='';
						}else if($response_result->user_type==2){
								$title='Distributor Detail';
								$shopname='';
						}else if($response_result->user_type==3){
								$title='Retailer Detail';
								$shopname=$response_result->shopname;
						}else if($response_result->user_type==4){
								$title='Consumer Detail';
								$shopname='';
						}
						
						if($response_result->status==1){
								$status='<button class="btn btn-success" disabled>Active</button>';
						}else{
								$status='<button class="btn btn-danger" disabled>Deactive</button>';
						}
				
						if($response_result->email_verification==1){
								$email_verification='<label style="color: white;cursor: pointer;" class="badge badge-success">Completed</label>';
						}else{
								$email_verification='<label style="color: white;cursor: pointer;" class="badge badge-warning">Pending</label>';
						}
						if($response_result->phone_verification==1){
								$phone_verification='<label style="color: white;cursor: pointer;" class="badge badge-success">Completed</label>';
						}else{
								$phone_verification='<label style="color: white;cursor: pointer;" class="badge badge-warning">Pending</label>';
						}
				
						if($response_result->subscription_status==1){
								$subscription_status='<label style="color: white;cursor: pointer;" class="badge badge-success">Completed</label>';
						}else{
								$subscription_status='<label style="color: white;cursor: pointer;" class="badge badge-warning">Pending</label>';
						}
				
						if($response_result->user_type!=4){
								$download_text = '<div class="card mt-3">
												<ul class="list-group list-group-flush">
													<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
														<h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe mr-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>ID Proof</h6>
															<span class="text-secondary"><a href="'.$response_result->document.'" download>Download</a></span>
													</li>
													
												</ul>
											</div>';  
								$other_status='<h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Other Status</i></h6>
															<small style="font-style: italic;font-weight: bold;">Email Verification</small><br>
															'.$email_verification.'<br>
															<small style="font-style: italic;font-weight: bold;">Phone verification</small><br>
															'.$phone_verification.'<br>
															<small style="font-style: italic;font-weight: bold;">Subscription Status</small><br>
															'.$subscription_status.'<br>';  
				        
								$manager_portal_detail=$commonFunction->get_main_portal_detail();
					            $portal_detail=$manager_portal_detail->data;
								$other_information='<h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Other Information</i></h6>
															<small style="font-style: italic;font-weight: bold;">Wallet</small><br>
															'.$portal_detail->CURRENCY.$response_result->wallet.'<br>
															<small style="font-style: italic;font-weight: bold;">Added By</small><br>
															'.$response_result->added_name.'<br>
															<small style="font-style: italic;font-weight: bold;">Created Date</small><br>
															'.$response_result->cdate.'<br>';                  
						}else{
							    $plan_detail_json=json_decode($response_result->consumer_plan_json);
							    
								$download_text = '<div class="card mt-3">
													<ul class="list-group list-group-flush">
														<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
															<h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe mr-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Resume</h6>
																<span class="text-secondary"><a href="'.$response_result->document.'" download>Download</a></span>
														</li>
														<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
															<h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
															<path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
															<path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
														    </svg> Current Plan</h6>
																<span class="text-secondary">'.$plan_detail_json->post_title.'</span>
														</li>
														
													</ul>
												</div>';

								$user_qualifications=explode (",", $response_result->qualification);	
								$qualification='';
								foreach($user_qualifications as $user_qualification)
                                {
									$qualification_list=$commonFunction->qualification_list($user_qualification);
								    $qualification_data=$qualification_list->data;
									$qualification .= ", $qualification_data->name";
								}
								$qualification = substr($qualification, 1);	
								
								$user_intrest_withs=explode (",", $response_result->additional_qualification);	
								$intrest_with='';
								foreach($user_intrest_withs as $user_intrest_with)
                                {
									$interest_with_list=$commonFunction->interest_list($user_intrest_with);
								    $interest_with_data=$interest_with_list->data;
									$intrest_with .= ", $interest_with_data->name";
								}
								$intrest_with = substr($intrest_with, 1);

								$subscription_end=date("Y-m-d", strtotime($response_result->subscription_end));
								if(date('Y-m-d') > $subscription_end){
									$subscription_status='<label style="color: white;cursor: pointer;" class="badge badge-danger">Need to renew</label>'; 
								}else{
									$subscription_status='<label style="color: white;cursor: pointer;" class="badge badge-success">Completed</label>'; 
								}
				
								$other_status='<h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Other Detail</i></h6>
															<small style="font-style: italic;font-weight: bold;">Phone verification</small><br>
															'.$phone_verification.'<br>
															<small style="font-style: italic;font-weight: bold;">Subscription Status</small><br>
															'.$subscription_status.'<br>
															<small style="font-style: italic;font-weight: bold;">Qualification</small><br>
															<small>'.$qualification.'</small><br>
															<small style="font-style: italic;font-weight: bold;">Intrest With</small><br>
															<small>'.$intrest_with.'</small><br>';
				
								$other_information='<h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">Other Information</i></h6>
															
															<small style="font-style: italic;font-weight: bold;">Added By</small><br>
															'.$response_result->added_name.'<br>
															<small style="font-style: italic;font-weight: bold;">Created Date</small><br>
															'.$response_result->cdate.'<br>
															<small style="font-style: italic;font-weight: bold;">Subscription Start Date</small><br>
															'.$response_result->subscription_start.'<br>
															<small style="font-style: italic;font-weight: bold;">Subscription End Date</small><br>
															'.$response_result->subscription_end.'<br>';               
						}
						
						$html='<nav aria-label="breadcrumb" class="main-breadcrumb">
									<div class="row gutters-sm">
										<div class="col-md-4 mb-3">
											<div class="card">
												<div class="card-body">
													<div class="d-flex flex-column align-items-center text-center">
														<img src="'.$response_result->profile.'" alt="Admin" class="rounded-circle" width="150">
														<div class="mt-3">
															<h4>'.ucfirst($response_result->fname).' '.ucfirst($response_result->lname).'</h4>
															<p class="text-secondary mb-1">'.$response_result->email.'</p>
															<p class="text-muted font-size-sm" style="font-weight: bold;font-style: italic;">'.ucfirst($shopname).'</p>
															'.$status.'
														</div>
													</div>
												</div>
											</div>
											'.$download_text.'
										</div>
										<div class="col-md-8">
											<div class="card mb-3">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">Contact Number</h6>
														</div>
														<div class="col-sm-9 text-secondary">
															'.$response_result->contact_number.'
														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">City</h6>
														</div>
														<div class="col-sm-9 text-secondary">
															'.$response_result->city.'
														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">District</h6>
														</div>
														<div class="col-sm-9 text-secondary">
															'.$response_result->district_title.'
														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">State</h6>
														</div>
														<div class="col-sm-9 text-secondary">
															'.$response_result->state_title.'
														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">Address</h6>
														</div>
														<div class="col-sm-9 text-secondary">
														'.$response_result->address.'
														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">Zipcode</h6>
														</div>
														<div class="col-sm-9 text-secondary">
														'.$response_result->zipcode.'
														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">DOB</h6>
														</div>
														<div class="col-sm-9 text-secondary">
														'.$response_result->dob.'
														</div>
													</div>
													<hr>
													<div class="row">
														<div class="col-sm-3">
															<h6 class="mb-0">Gender</h6>
														</div>
														<div class="col-sm-9 text-secondary">
														'.ucfirst($response_result->gender).'
														</div>
													</div>
													
												</div>
											</div>
				
											<div class="row gutters-sm">
												<div class="col-sm-6 mb-3">
													<div class="card h-100">
														<div class="card-body">
															'.$other_status.'
															
														</div>
													</div>
												</div>
												<div class="col-sm-6 mb-3">
													<div class="card h-100">
														<div class="card-body">
															'.$other_information.'
															
														</div>
													</div>
												</div>
											</div>
				
				
				
										</div>
									</div>
				
				';
						
				}
				
				$output['html']=   '<div class="modal-header">
																<h5 class="modal-title">'.$title.'</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																'.$html.'
															</div>
															<div class="modal-footer">
																
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>';
	 }else{
		  //error message
	  	$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
		$output['status']=0;
		
}
echo json_encode($output);	
}


/*get blog list  action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_blogs')
{ 
	 //method check statement
	 if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(isset($_POST['qualification'])){
			if(!empty($_POST['qualification'])){
				$qualification = implode(',', $_POST['qualification']);
				
			}else{
				$qualification ='';
			}
		}else{
			    $qualification ='';
		}

		if(isset($_POST['interest_with'])){
			if(!empty($_POST['interest_with'])){
				$interest_with = implode(',', $_POST['interest_with']);
				
			}else{
				$interest_with ='';
			}
		}else{
			    $interest_with ='';
		}
		
		$url=SSOAPI.'get_blog_list';
		$data=array(
				'api_key' => API_KEY,
				'blog_id' => 0,
                'page' => 'blog',
				'states' => $_POST['states'],
				'keyword' => $_POST['keyword'],
				'qualification' => $qualification,
				'interest_with' => $interest_with,
				'paged' => $_POST['paged'],
		);
		
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		$html='<div class="row my-5">';
		if($result->status==1){
			foreach($result->data  as $blog_lists){
				$html .='<div class="col-lg-4 py-3">
							<div class="card-blog">
							<div class="header">
								<div class="post-thumb">
								<img src="'.$blog_lists->blog_image.'" alt="">
								</div>
							</div>
							<div class="body">
								<h5 class="post-title"><a href="blog-details.php?id='.$blog_lists->id.'">'.$blog_lists->post_title.'</a></h5>
								<div class="post-date">Posted on <a href="blog-details.php?id='.$blog_lists->id.'">'.$blog_lists->post_date.'</a></div>
							</div>
							</div>
						</div>';
			}
			$pages_list='';
			if($result->max_pages > 0){
				for ($i=1; $i <= $result->max_pages ; $i++) { 
					$active='';
					$aria_current='';
					$sr_only='';
					$previous_disable_true='';
					$previous_disable='';
					$previous_link='#';
					$next_disable='';
					$next_link='#';
					if($_POST['paged']!=''){
						if($i==$_POST['paged']){
							$active='active';
							$aria_current='aria-current="page"';
							$sr_only='<span class="sr-only">(current)</span>';
						}
						if($_POST['paged']==1){
							$previous_disable_true='aria-disabled="true"';
						    $previous_disable='disabled';
							$next_link="return change_url('blog.php?paged=".(1+1)."','".(1+1)."')";
							//$next_link='blog.php?paged='.(1+1);
						}else{
							//$previous_link='blog.php?paged='.$_POST['paged']-1;
							$previous_link="return change_url('blog.php?paged=".($_POST['paged']-1)."','".($_POST['paged']-1)."')";
							if($_POST['paged']==$i){
								$next_disable='disabled';
							}else{
								//$next_link='blog.php?paged='.($_POST['paged']+1);
								$next_link="return change_url('blog.php?paged=".($_POST['paged']+1)."','".($_POST['paged']+1)."')";
							}
							
						}
				    }else{
						if($i==1){
							$active='active';
							$aria_current='aria-current="page"';
							$sr_only='<span class="sr-only">(current)</span>';
						}
						$previous_disable_true='aria-disabled="true"';
						$previous_disable='disabled';
						//$next_link='blog.php?paged='.(1+1);
						$next_link="return change_url('blog.php?paged=".(1+1)."','".(1+1)."')";
					}
					
					$click="return change_url('blog.php?paged=".$i."','".$i."')";
					$pages_list .='<li class="page-item '.$active.'" '.$aria_current.'><a class="page-link" href="javascript:void(0)" onClick="'.$click.'">'.$i.' '.$sr_only.'</a></li>';
				}
			}
			$pagination='<ul class="pagination justify-content-center">
							<li class="page-item '.$previous_disable.'">
							<a class="page-link" href="javascript:void(0)" onClick="'.$previous_link.'" tabindex="-1" '.$previous_disable_true.'>Previous</a>
							</li>
							'.$pages_list.'
							<li class="page-item '.$next_disable.'">
							<a class="page-link" href="javascript:void(0)" onClick="'.$next_link.'">Next</a>
							</li>
						</ul>';

		}else{
			$html .='<div class="col-lg-12 py-3">
						<div class="card-blog">
						
						<div class="body">
							<h5 class="post-title"><a href="">No blog found !!</a></h5>
							
						</div>
						</div>
					</div>';
		    $pagination='';			
		}
		$html .='</div>';


		$output['html'] =$html;
		$output['pagination'] =$pagination;
	    $output['status']=1;

	 }else{
		//error message
		$output['html'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
	    $output['status']=0;
	  
    }  
echo json_encode($output);	
}
/*get blog list  end*/

/*get blog list  action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'load_job_form')
{ 
	 //method check statement
	 if($_SERVER["REQUEST_METHOD"] == "POST"){
		$url=SSOAPI.'get_job_notification_form_detail';
		$data=array(
				'api_key' => API_KEY,
				'blog_id' => $_POST['blog_id'],
                'user_id' => $_SESSION['user_id'],
				'portal' => 'main'
		);
		
		$method='POST';
		$response=$commonFunction->curl_call($url,$data,$method);
		$result = json_decode($response);
		$html='';
		if($result->status==1){
			$users_option='';
			foreach($result->user_list as $user_list){
			$users_option.= '<option  value="'.$user_list->id.'">'.$user_list->fullname.'</option>';
			}
			$extra_text_btn='';
			if($result->send_type==1){
				$extra_text_btn='<span id="rscount" style="color: crimson;"></span>';
			}
			$html='<h3 class="mb-5">Compose Notification</h3>
			
			<form id="sendJobNotification" onsubmit="return send_notification();">
			  <input type="hidden" name="blog_id" value="'.$_POST['blog_id'].'" >
			  <input type="hidden" name="send_type" value="'.$result->send_type.'" >
			  
			  <div class="form-group">
				<label for="users_list">Select Users</label><br>
				<select  id="users_list" name="users_list[]" class="form-control" multiple>
				'.$users_option.'
				 						
				</select>
				
			  </div>
	
			  <div class="form-group">
				<label for="message">Notification</label>
				<textarea name="msg" id="message" cols="30" rows="4" class="form-control" readonly>'.$result->blog_message.'</textarea>
			  </div>
			  <div class="form-group">
				<button id="sendBtn" type="submit"  class="btn btn-primary">Send Notification</button><br>
				'.$extra_text_btn.'
			  </div>
	
			</form>';
			$output['html'] =$html;
	        $output['status']=1;
		}else{
			$output['html'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
	        $output['status']=0;
		}
		
		
	 }else{
		//error message
		$output['html'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Something Went Wrong !!</div>';
	    $output['status']=0;
	  
    }  
echo json_encode($output);	
}

/*send job notification action start*/
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'send_job_notification')
{ 
	//method check statement
	if($_SERVER["REQUEST_METHOD"] == "POST"){

		if(!empty($_POST['users_list'])){
			$users_list = implode(',', $_POST['users_list']);
			$url=SSOAPI.'send_job_notification';
			$data=array(
					'api_key' => API_KEY,
					'user_id' => $_SESSION['user_id'],
					'blog_id' => $_POST['blog_id'],
					'send_type' => $_POST['send_type'],
					'users_list' => $users_list,
					'portal' => 'main'
			);
			
			$method='POST';
			$response=$commonFunction->curl_call($url,$data,$method);
			$result = json_decode($response);
			if($result->status==1){
				$output['message'] ='<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '.$result->message.' !!</div>';
				$output['status']=1;

			}else{
				$output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> '.$result->message.' !!</div>';
	            $output['status']=0;
			}
			
		}else{
			
			//error message
		    $output['message'] ='<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Please select at least one user for send a notification !!</div>';
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



   