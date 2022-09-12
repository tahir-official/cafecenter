jQuery(function ($) {
    var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
    $('ul a').each(function () {
        if (this.href === path) {
            $(this).addClass('active');
        }
    });
});
$(".toggle-password").click(function () {
    $(this).children().toggleClass("mai-lock-closed mai-lock-open");
    let input = $(this).prev();
    console.log(input);
    input.attr("type", input.attr("type") === "password" ? "text" : "password");
  });

/*contact form start*/
$(document).ready(function () {
    $("#contactFrom").validate({
      rules: {
        full_name: {
          required: true,
        },
        email: {
          required: true,
        },
        subject: {
            required: true,
        },
        message: {
            required: true,
        },
      },
      submitHandler: function (form) {
        let formData = new FormData($("#contactFrom")[0]);
        $.ajax({
          method: "POST",
          url: baseUrl + "include/process.php?action=contact_request",
          data: formData,
          dataType: "JSON",
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $(".btnContact").html(
              '<i class="fa fa-spinner"></i> Processing...'
            );
            $(".btnContact").prop("disabled", true);
            $("#alert").hide();
          },
        })
  
          .fail(function (response) {
            alert("Try again later.");
          })
  
          .done(function (response) {
            $(".btnContact").html("Submit");
            $(".btnContact").prop("disabled", false);
            if (response.status == 0) {
              $("#alert").show();
              $("#alert").html(response.message);
            } else {
                $("#contactFrom")[0].reset();
                $("#alert").show();
                $("#alert").html(response.message);
            }
          })
          .always(function () {
            $(".btnContact").html("Submit");
            $(".btnContact").prop("disabled", false);
          });
        return false;
      },
    });
  });
  
  /*contact form end*/

  
/*login script start*/
$("#alert").hide();
$("#loginFrom").submit(function (e) {
  e.preventDefault();
  let formData = $("#loginFrom").serialize();
  $.ajax({
    method: "POST",
    url: baseUrl + "include/process.php?action=login",
    data: formData,
    dataType: "JSON",
    beforeSend: function () {
      $(".btnLogin").html('Login <i class="fa fa-spinner"></i>');
      $(".btnLogin").prop("disabled", true);
      $("#alert").hide();
    },
  })

    .fail(function (response) {
      alert("Try again later.");
    })

    .done(function (response) {
      if (response.status == 0) {
        $("#alert").html(response.message);
        $("#alert").show();
      } else {
        location.href = response.url;
      }
    })
    .always(function () {
      $(".btnLogin").html("Login");
      $(".btnLogin").prop("disabled", false);
    });
  return false;
});
/*login script end*/


/*forget form start*/
$(document).ready(function () {
    $("#forgetpassFrom").validate({
      rules: {
        number: {
          required: true,
          number: true,
          maxlength: 10,
        },
      },
      submitHandler: function (form) {
        var number = $("#number").val();
        var page = $("#page").val();
        sent_otp(number, page);
      },
    });
  });
  
  /*forget form end*/
  /*send otp start*/
  function sent_otp(cnumber, page) {
    $.ajax({
      method: "POST",
      url: baseUrl + "include/process.php?action=send_otp",
      data: { cnumber: cnumber, page: page },
      dataType: "JSON",
      beforeSend: function () {
        $("#alert").html(
          '<i class="fa fa-spinner fa-spin" style="font-size:27px;margin-bottom: 15px;color: brown;"></i>'
        );
        $("#vload").html(
          '<i class="fa fa-spinner fa-spin" style="font-size:27px;margin-bottom: 15px;color: brown;margin-top: 5px;"></i>'
        );
  
        if (page == "forget") {
          $(".btnForgetpass").html('Processing <i class="fa fa-spinner"></i>');
          $(".btnForgetpass").prop("disabled", true);
          $("#alert").hide();
        }
      },
    })
  
      .fail(function (response) {
        alert("Try again later.");
      })
  
      .done(function (response) {
        if (response.status == 0) {
          $("#alert").html(response.message);
          $("#alert").show();
          $("#vload").html('<a href="' + baseUrl + 'login.php">Login</a>');
          if (page == "forget") {
            $(".btnForgetpass").html("Request OTP");
            $(".btnForgetpass").prop("disabled", false);
          }
        } else {
          location.href = response.url;
        }
      });
  
    return false;
  }  

/*send otp end*/
  
/*otpverify form start*/
$(document).ready(function () {
    $("#otpverifyFrom").validate({
      rules: {
        number: {
          required: true,
          number: true,
          maxlength: 10,
        },
        otp: {
          required: true,
          number: true,
        },
      },
      submitHandler: function (form) {
        let formData = new FormData($("#otpverifyFrom")[0]);
        $.ajax({
          method: "POST",
          url: baseUrl + "include/process.php?action=otp_verify",
          data: formData,
          dataType: "JSON",
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $(".btnOtpverify").html(
              '<i class="fa fa-spinner"></i> Processing...'
            );
            $(".btnOtpverify").prop("disabled", true);
            $("#alert").hide();
          },
        })
  
          .fail(function (response) {
            alert("Try again later.");
          })
  
          .done(function (response) {
            $(".btnOtpverify").html("Submit");
            $(".btnOtpverify").prop("disabled", false);
            if (response.status == 0) {
              $("#alert").show();
              $("#alert").html(response.message);
            } else {
              location.href = response.url;
            }
          })
          .always(function () {
            $(".btnOtpverify").html("Submit");
            $(".btnOtpverify").prop("disabled", false);
          });
        return false;
      },
    });
  });
  
  /*otpverify form end*/


  
/*reset password form start*/
$(document).ready(function () {
    $("#resetFrom").validate({
      rules: {
        password: {
          required: true,
        },
        cpassword: {
          required: true,
          equalTo: "#password",
        },
      },
      submitHandler: function (form) {
        let formData = new FormData($("#resetFrom")[0]);
        $.ajax({
          method: "POST",
          url: baseUrl + "include/process.php?action=reset_password",
          data: formData,
          dataType: "JSON",
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $(".btnResetpass").html(
              '<i class="fa fa-spinner"></i> Processing...'
            );
            $(".btnResetpass").prop("disabled", true);
            $("#alert").hide();
          },
        })
  
          .fail(function (response) {
            alert("Try again later.");
          })
  
          .done(function (response) {
            $(".btnResetpass").html("Submit");
            $(".btnResetpass").prop("disabled", false);
            if (response.status == 0) {
              $("#alert").show();
              $("#alert").html(response.message);
            } else {
              location.href = response.url;
            }
          })
          .always(function () {
            $(".btnResetpass").html("Submit");
            $(".btnResetpass").prop("disabled", false);
          });
        return false;
      },
    });
  });
  
  /*reset password form end*/
  