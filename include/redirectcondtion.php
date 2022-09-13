<?php
if(!isset($_SESSION['is_user_logged_in'])){ 
    $commonFunction->redirect('login.php'); 
}else{
    $load_paywall=false;
    if($user_data->subscription_status==0){
        $url=SSOAPI.'get_plan_by_user_type';
        $data=array(
            'user_type' => $_SESSION['user_type'],
            'api_key' => API_KEY
        );
        $method='POST';
        $response=$commonFunction->curl_call($url,$data,$method);
        $result = json_decode($response);
        $plan_data=$result->data;
        ?>
        <script>
        $(document).ready(function() {
       //load_paywall('<?=$_SESSION["user_id"]?>');
        });
        </script>
        <?php
        $load_paywall=true;
    }
}
?>