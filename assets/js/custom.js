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

$("#agreeTerms").click(function () {
  if ($(this).is(":checked")) {
    $("#sdbtn").prop("disabled", false);
  } else {
    $("#sdbtn").prop("disabled", true);
  }
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

  
/*load Distric list start*/
function loadDistric(state_id) {
  $.ajax({
    method: "POST",
    url: baseUrl + "include/process.php?action=get_distric",
    data: { state_id: state_id },
    dataType: "JSON",
    beforeSend: function () {
      $("#district").html("<option>Please wait</option>");
    },
  })

    .fail(function (response) {
      alert("Try again later.");
    })

    .done(function (response) {
      $("#district").html(response.html);
    });
  return false;
}
/*load Distric list end*/


/*signup form start*/
$(document).ready(function () {
  $("#signupFrom").validate({
    rules: {
      fname: {
        required: true,
      },
      lname: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      contact_number: {
        required: true,
        number: true,
        maxlength: 10,
        minlength: 10,
      },
      shopname: {
        required: true,
      },
      address: {
        required: true,
      },
      state: {
        required: true,
      },
      district: {
        required: true,
      },
      city: {
        required: true,
      },
      zipcode: {
        required: true,
        number: true,
      },
      gender: {
        required: true,
      },
      dob: {
        required: true,
      },
      password: {
        required: true,
      },
      c_password: {
        required: true,
        equalTo: "#password",
      },
    },
    submitHandler: function (form) {
      let formData = new FormData($("#signupFrom")[0]);
      $.ajax({
        method: "POST",
        url: baseUrl + "include/process.php",
        data: formData,
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $(".btnsbt").html('<i class="fa fa-spinner"></i> Processing...');
          $(".btnsbt").prop("disabled", true);
          $("#alert").hide();
        },
      })

        .fail(function (response) {
          alert("Try again later.");
        })

        .done(function (response) {
          $(".btnsbt").html("Register");
          $(".btnsbt").prop("disabled", false);
          if (response.status == 0) {
            $("#alert").show();
            $("#alert").html(response.message);
            $("#alert").focus();
            $('html').animate({
              scrollTop: 0
            }, 1000);
          } else {
            location.href = response.url;
          }
        })
        .always(function () {
          $(".btnsbt").html("Register");
          $(".btnsbt").prop("disabled", false);
        });
      return false;
    },
  });
});

/*signup form end*/

/*edit user profile form start*/

$(document).ready(function () {
  $("#profile_image").on("change", function () {
    let formData = new FormData($("#profile_form")[0]);
    $.ajax({
      method: "POST",
      url: baseUrl + "include/process.php?action=edit_profile_image",
      data: formData,
      dataType: "JSON",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        $(".loader").css("display", "block");
        $(".profile_form").prop("disabled", true);
        $("#alert").hide();
      },
    })

      .fail(function (response) {
        alert("Try again later.");
      })

      .done(function (response) {
        $(".loader").css("display", "none");
        $(".profile_form").prop("disabled", false);
        $("#alert").show();
        $("#alert").html(response.message);
        if (response.status == 1) {
          
          $(".outer").css(
            "background-image",
            "url(" + response.profile_url + ")"
          );
        }
      })
      .always(function () {
        $(".loader").css("display", "none");
        $(".profile_form").prop("disabled", false);
      });
    return false;
  });
});
/*edit user profile form end*/


/*edit user form start*/
$(document).ready(function () {
  $("#edit_form").validate({
    rules: {
      fname: {
        required: true,
      },
      lname: {
        required: true,
      },
      shopname: {
        required: true,
      },
      address: {
        required: true,
      },
      state: {
        required: true,
      },
      district: {
        required: true,
      },
      city: {
        required: true,
      },
      zipcode: {
        required: true,
        number: true,
      },
      gender: {
        required: true,
      },
      dob: {
        required: true,
      },
    },
    submitHandler: function (form) {
      let formData = new FormData($("#edit_form")[0]);
      $.ajax({
        method: "POST",
        url: baseUrl + "include/process.php?action=edit_users",
        data: formData,
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $(".btnsbt").html('<i class="fa fa-spinner"></i> Processing...');
          $(".btnsbt").prop("disabled", true);
          $("#alert").hide();
        },
      })

        .fail(function (response) {
          alert("Try again later.");
        })

        .done(function (response) {
          $(".btnsbt").html("Submit");
          $(".btnsbt").prop("disabled", false);
          $("#alert").show();
          $("#alert").html(response.message);
          if (response.status == 1) {
            $("#profile_name").html(response.manager_name);
            $("#user_name").html('Hi '+response.manager_name);
          }
          $("#alert").focus();
          $('html').animate({
              scrollTop: 0
          }, 1000);

        })
        .always(function () {
          $(".btnsbt").html("Submit");
          $(".btnsbt").prop("disabled", false);
        });
      return false;
    },
  });
});

/*edit user form end*/

/*reset password form start*/
function resetPasswordFrom() {
  $("#updatePassword")[0].reset();
  return false;
}
/*reset password form end*/


/*update password script start*/
$(document).ready(function () {
  $("#updatePassword").validate({
    rules: {
      current_password: {
        required: true,
      },
      new_password: {
        required: true,
      },
      confirm_password: {
        required: true,
        equalTo: "#new_password",
      },
    },
    submitHandler: function (form) {
      let formData = new FormData($("#updatePassword")[0]);
      $.ajax({
        method: "POST",
        url: baseUrl + "include/process.php?action=changePassword",
        data: formData,
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $("#updatePassBtn").html(
            '<i class="fa fa-spinner"></i> Processing...'
          );
          $("#updatePassBtn").prop("disabled", true);
          $("#alert").hide();
        },
      })

        .fail(function (response) {
          alert("Try again later.");
        })

        .done(function (response) {
          $("#updatePassBtn").prop("disabled", false);
          $("#updatePassBtn").html("Change");
          $("#alert").show();
          $("#alert").html(response.message);
          if (response.status == 1) {
            $("#updatePassword")[0].reset();
          }
        })
        .always(function () {
          $("#updatePassBtn").html("Change");
          $("#updatePassBtn").prop("disabled", false);
        });
      return false;
    },
  });
});

/*update password script end*/


/*load paywall script start*/
function load_paywall(user_id) {

  $.ajax({
    method: "POST",
    url: baseUrl + "include/process.php?action=load_paywall",
    data: { user_id: user_id },
    dataType: "JSON",
    beforeSend: function () {
      $("#pagelayout_area").html('<div id="loader"></div>');
      $("#pagelayout_area").css("text-align", "center");
    },
  })

    .fail(function (response) {
      alert("Try again later.");
    })

    .done(function (response) {
      $.getScript(baseUrl + "dist/js/custom.js");
      if (response.status == 0) {
        $("#pagelayout_area").html(response.html);
        $("#pagelayout_area").css("text-align", "center");
      } else {
        $("#pagelayout_area").html(response.html);
        $("#pagelayout_area").css("text-align", "center");
        $("#pagelayout_area").css("background-image", "url(" + response.img + ")");
        $("#pagelayout_area").css("height", "100vh");
        $("#pagelayout_area").css("background-size", "cover");
        $("#pagelayout_area").css("justify-content", "center");
        $("#pagelayout_area").css("display", "flex");
        $("#pagelayout_area").css("align-items", "center");
        
      }
    });

  return false;
}
/*load paywall script end*/




  