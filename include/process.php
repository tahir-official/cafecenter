<?php
include_once('../include/functions.php');
$commonFunction= new functions();
/*login action start*/
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'login'){ 
    $output['status']=0;

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
   