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
   