<?php
include_once('config.php');
class Functions
{
    function curl_call($url,$data,$method){
        $client = curl_init($url);
        if($method=='POST'){
        curl_setopt($client, CURLOPT_POST, true);  
        }
        curl_setopt($client, CURLOPT_POSTFIELDS, $data);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($client, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($client);
        curl_close($client);
        return $response;
        
    
    }

    function get_main_portal_detail(){
        $url=SSOAPI.'get_manager_portal_detail';
        $data=array(
            'api_key' => API_KEY
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }

    function user_detail($user_id){
        $url=SSOAPI.'get_user_detail';
        $data=array(
            'user_id' => $user_id,
            'api_key' => API_KEY
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }

    function state_list(){
        $url=SSOAPI.'get_state_list';
        $data=array(
             'api_key' => API_KEY
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }
    
    function distric_list($state_id){
        $url=SSOAPI.'get_distric_list_by_state';
        $data=array(
            'api_key' => API_KEY,
            'state_id' => $state_id,
            
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }
    function qualification_list($qualification_id){
        $url=SSOAPI.'get_qualification_list';
        $data=array(
             'api_key' => API_KEY,
             'qualification_id' => $qualification_id
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }
    function interest_list($interest_id){
        $url=SSOAPI.'get_additional_qualification_list';
        $data=array(
             'api_key' => API_KEY,
             'interest_id' => $interest_id
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }

    function consumer_plan($plan_id){
        $url=SSOAPI.'get_consumer_plan';
        $data=array(
             'api_key' => API_KEY,
             'plan_id' => $plan_id
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }
    function services_list($service_id){
        $url=SSOAPI.'get_services';
        $data=array(
             'api_key' => API_KEY,
             'service_id' => $service_id
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }
    function blog_list($blog_id,$page=NULL){
        $url=SSOAPI.'get_blog_list';
        $data=array(
             'api_key' => API_KEY,
             'blog_id' => $blog_id,
             'page' => $page
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }
    function redirect($location){ 
          echo '<script>window.location.href="'.$location.'"</script>';
          die(); 
    }
  
    function get_page_detail_by_id($page_id){
            $url=SSOAPI.'get_page_detail_by_id';
            $data=array(
                'page_id' => $page_id,
                'api_key' => API_KEY
            );
            $method='POST';
            $response=$this->curl_call($url,$data,$method);
            return $result = json_decode($response);
        
    }

    function get_attachment_image_src($image_id){
        $url=SSOAPI.'get_attachment_image_src';
        $data=array(
            'image_id' => $image_id,
            'api_key' => API_KEY
        );
        $method='POST';
        $response=$this->curl_call($url,$data,$method);
        return $result = json_decode($response);
    
    }
}