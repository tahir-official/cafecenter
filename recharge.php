<?php
   include_once('include/header.php');
   include ("include/redirectcondtion.php");
?>
<div class="container">
   <div class="page-banner">
      <div class="row justify-content-center align-items-center h-100">
         <div class="col-md-6">
            <nav aria-label="Breadcrumb">
               <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                  <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                  <li class="breadcrumb-item active">Recharge Management</li>
               </ul>
            </nav>
            <h1 class="text-center">Recharge Management</h1>
         </div>
      </div>
   </div>
</div>
</header>
<!--dashboard-->
<div class="page-section">
   <div class="container">
      <div id="pagelayout_area">
         <?php
            if (isset($_SESSION['message'])){ echo $_SESSION['message'];  unset($_SESSION['message']);}
            ?>
         <div id="alert"></div>
         <section class="site-maincontentarea fullwidth">
            <div class="entry-content">
               <!--filter-->
               <div class="filteruus main_page">
                  <div class="row">
                     <div class="col-sm-4">
                        <?php
                           include ("include/dashboard-sidebar.php");
                        ?>
                     </div>
                     <div class="col-sm-8">
                        <h2 class="h2_hedddin">Recharge & Recharge History</h2>
                        <div class="rightt">
                           <div class="card card-primary">
                              <form class="form-horizontal" name="rechargeRequst_form" id="rechargeRequst_form" method="post" >
                                 <div class="card-body">
                                    
                                    
                                    <div class="form-group">
                                       <label for="request_amount">Please Enter Recharge Amount</label>
                                       <input type="number" id="request_amount" name="request_amount" class="form-control" value="" step="1">
                                    </div>
                                    <div class="form-group">
                                       <button  type="submit" id="rechargeRequstBtn" class="btn btn-primary" >Submit</button>
                                       <!-- <button type="button" onclick="checkout_slot(event);" class="btn btn_theme btn-lg submit-btn" id="buyBtn">Process to checkout</button> -->
                                    </div>
                                    
                                 </div>
                              </form>
                              <!-- /.card-body -->
                           </div>
                           <hr>
                           <div class="table-responsive">
                              <table class="table">
                                 <thead class="thead-dark">
                                    <tr>
                                       <th scope="col">S.N.</th>
                                       <th scope="col">Profile</th>
                                       <th scope="col">Vendor</th>
                                       <th scope="col">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td scope="row">No Data Found</td>
                                       <td scope="row">No Data Found</td>
                                       <td scope="row">No Data Found</td>
                                       <td scope="row">No Data Found</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>
<?php
   
   include_once('include/footer.php');
?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="include/process.php" method="POST">
   <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" >
   <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
   <input type="hidden" name="action"  value="rechange_payment" >
</form>
<script>
var options;

$(document).ready(function () {
  $("#rechargeRequst_form").validate({
    rules: {
      request_amount: {
        required: true,
        min: 50,
      },
      
    },
    messages: {
      
      request_amount: {
            required: "Recharge amount is required for this recharge process, Please enter amount.",
            min: "Enter at least {0} Rs recharge amount."
        }
    },
    submitHandler: function (form) {
      let formData = new FormData($("#rechargeRequst_form")[0]);
      $.ajax({
        method: "POST",
        url: baseUrl + "include/process.php?action=recharge_request",
        data: formData,
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $("#rechargeRequstBtn").html(
            '<i class="fa fa-spinner"></i> Processing...'
          );
          $("#rechargeRequstBtn").prop("disabled", true);
          $("#alert").hide();
        },
      })

        .fail(function (response) {
          alert("Try again later.");
        })

        .done(function (response) {
          
            options=response;
          
            options.handler = function (response){
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.razorpayform.submit();
          };
      
          options.theme.image_padding = false;
      
          options.modal = {
            ondismiss: function() {
                  console.log("This code runs when the popup is closed");
                  $("#rechargeRequstBtn").prop("disabled", false);
                  $("#rechargeRequstBtn").html("Submit");
                  $("#alert").show();
                  $("#alert").html('<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error!</strong> Your recharge process cancel !!</div>');
            },
            
            escape: true,
            backdropclose: false
          };
      
          var rzp = new Razorpay(options);
          rzp.open();
           
          
        })
        .always(function () {
          $("#rechargeRequstBtn").html("Submit");
          $("#rechargeRequstBtn").prop("disabled", false);
        });
      return false;
    },
  });
});
</script>

