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
		    }else if($_POST['user_type']==4){
				$data2=array("consumer_plan"=>$_POST['consumer_plan'],"qualification"=>$_POST['qualification'],"interest_with"=>$_POST['interest_with'],"subscription_status"=>$subscription_status);
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
				$contact_number=$_SESSION['contact_number'];
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
			
			$qualification_list=$commonFunction->qualification_list();
			$qualification_status=$qualification_list->status;
			$qualification_message=$qualification_list->message;
			$qualification_data=$qualification_list->data;

			if($qualification_status == 0){
				$qualification_option='<option value="">'.$qualification_message.'</option>';
				$qualification_disbale='disabled';
			}else{
				$qualification_disbale='';
				$qualification_option='<option value="">Select Qualification</option>';
				foreach($qualification_data  as $qualification){
						
						$qualification_option.= '<option value="'.$qualification->term_id.'">'.$qualification->name.'</option>';
				}
			}

			$interest_list=$commonFunction->interest_list();
			$interest_status=$interest_list->status;
			$interest_message=$interest_list->message;
			$interest_data=$interest_list->data;

			if($interest_status == 0){
				$interest_with_option='<option value="">'.$qualification_message.'</option>';
				$interest_with_disbale='disabled';
			}else{
				$interest_with_disbale='';
				$interest_with_option='<option value="">Select Interest with</option>';
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
						
						$consumer_plan_option.= '<option value="'.$consumer_plan->id.'">'.$consumer_plan->post_title.' for '.$consumer_plan->plan_days.' Days only in '.$portal_detail->CURRENCY.$consumer_plan->plan_amount.', Retailer Commission '.$portal_detail->CURRENCY.$consumer_plan->retailer_commissions.'</option>';
				}
			}

			$action='add_user';            
			
	}else{
			$popup_title='Edit '.$user_title;
			
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
					$shopname=$response_result->shopname;

					
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
									<div class="row">
											<div class="form-group col-md-12">
													<label for="consumer_plan">Select Consumer Plan</label>
													<select '.$consumer_plan_disbale.' id="consumer_plan" name="consumer_plan" class="form-control" >
															
															'.$consumer_plan_option.'
														 
													</select>
											</div>
											
									</div>
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
													<input type="email" class="mat-input form-control" id="email" name="email" value="'.$email.'">
											</div>
											<div class="form-group col-md-6">
													<label for="contact_number">Mobile No.</label>
													<input type="Text" class="form-control" id="contact_number" name="contact_number" maxlength="10" value="'.$contact_number.'">
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
													<input id="zipcode" name="zipcode" class="form-control" type="text" value="'.$zipcode.'">
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
													<label for="state">Select Qualification</label>
													<select '.$qualification_disbale.' id="qualification" name="qualification" class="form-control" >
															
															'.$qualification_option.'
														 
													</select>
											</div>
											<div class="form-group col-md-6">
													<label for="state">Select Interest with</label>
													<select '.$interest_with_disbale.' id="interest_with" name="interest_with" class="form-control" >
															
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




   