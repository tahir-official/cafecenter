<?php
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

if(!isset($_SESSION['is_user_logged_in'])){ 
    $commonFunction->redirect('login.php'); 
}else{
    $load_paywall=false;
    if($_SESSION['user_type']==3){
        $url=SSOAPI.'get_plan_by_user_type';
        $data=array(
            'user_type' => $_SESSION['user_type'],
            'api_key' => API_KEY
        );
        $method='POST';
        $response=$commonFunction->curl_call($url,$data,$method);
        $result = json_decode($response);
        $plan_data=$result->data;
        if($user_data->subscription_status==0){
            

            /*rozarpay setup start*/

            $api = new Api($portal_detail->keyId, $portal_detail->keySecret);
            $fourRandomDigit = rand(1000,9999);

            $orderData = [
                'receipt'         => 'rcptid_'.$fourRandomDigit,
                'amount'          => $plan_data->plan_amount * 100, // 2000 rupees in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];

            $razorpayOrder = $api->order->create($orderData);

            $razorpayOrderId = $razorpayOrder['id'];

            $_SESSION['razorpay_order_id'] = $razorpayOrderId;

            $displayAmount = $amount = $orderData['amount'];

            if ($portal_detail->displayCurrency !== 'INR')
            {
                $url = "https://api.fixer.io/latest?symbols=$portal_detail->displayCurrency&base=INR";
                $exchange = json_decode(file_get_contents($url), true);

                $displayAmount = $exchange['rates'][$portal_detail->displayCurrency] * $amount / 100;
            }
            $merchant_order_id= $razorpayOrderId.'_'.rand(100000,999999);
            $data = [
                "key"               => $keyId,
                "amount"            => $amount,
                "name"              => $portal_detail->PROJECT,
                "description"       => $plan_data->plan_heading,
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

            $json = json_encode($data);
            /*rozarpay setup end*/
            $load_paywall=true;
            ?>
            <script>
            $(document).ready(function() {
             load_paywall('<?=$_SESSION["user_id"]?>');
            });
            </script>
            <?php
            
        }
    }
    
}
if($load_paywall==true){
    ?>
    <button id="rzp-button1" class="btn btn-info btn-lg second" style="display:none" >Pay with Razorpay</button>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <form name='razorpayform' action="include/process.php" method="POST">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" >
        <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
        <input type="hidden" name="action"  value="verify_payment" >
        
    </form>
    <script>
    // Checkout details as a json
    var options = <?php echo $json?>;
  
    /**
     * The entire list of Checkout fields is available at
     * https://docs.razorpay.com/docs/checkout-form#checkout-fields
     */
    options.handler = function (response){
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.razorpayform.submit();
    };
  
    // Boolean whether to show image inside a white frame. (default: true)
    options.theme.image_padding = false;
  
    options.modal = {
        ondismiss: function() {
            console.log("This code runs when the popup is closed");
        },
        // Boolean indicating whether pressing escape key 
        // should close the checkout form. (default: true)
        escape: true,
        // Boolean indicating whether clicking translucent blank
        // space outside checkout form should close the form. (default: false)
        backdropclose: false
    };
  
    var rzp = new Razorpay(options);
  
    document.getElementById('rzp-button1').onclick = function(e){
        rzp.open();
        e.preventDefault();
    }
    </script>
    <?php
}
?>
