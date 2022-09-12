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
   