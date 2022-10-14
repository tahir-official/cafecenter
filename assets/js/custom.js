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
      qualification: {
        required: true,
      },
      interest_with: {
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
      $.getScript(baseUrl + "assets/js/custom.js");
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
$(".first").click(function(){
  $(".second").click(); 
  return false;
});
/*load paywall script end*/
/*load table data start*/
function tableLoad(loadurl, user_type, portal, show_by) {
  var dataTable = $("#mytable").DataTable({
    processing: true,
    serverSide: true,
    order: [],
    ajax: {
      url: loadurl,
      type: "POST",
      data: {
        user_type: user_type,
        portal: portal,
        show_by: show_by,
      },
    },
    columnDefs: [
      {
        targets: "_all" /* column index */,

        orderable: false /* true or false */,
      },
    ],
  });
}
/*load table data end*/
/*load other table data start*/
function tableLoad_other(loadurl, portal, user_id) {
  var dataTable = $("#mytable").DataTable({
    processing: true,
    serverSide: true,
    order: [],
    ajax: {
      url: loadurl,
      type: "POST",
      data: {
        portal: portal,
        user_id: user_id,
      },
    },
    columnDefs: [
      {
        targets: "_all" /* column index */,

        orderable: false /* true or false */,
      },
    ],
  });
}
/*load  othertable data end*/

/*Load Users Popup start*/
function load_users_popup(row_id, user_type) {
  $.ajax({
    method: "POST",
    url: baseUrl + "include/process.php?action=load_users_popup",
    data: { row_id: row_id, user_type: user_type },
    dataType: "JSON",
    beforeSend: function () {
      $("#form-dialog-other").modal("show");
      $("#popupcontent").html('<div id="loader"></div>');
    },
  })

    .fail(function (response) {
      alert("Try again later.");
    })

    .done(function (response) {
      $.getScript(baseUrl + "assets/js/custom.js");

      $("#popupcontent").html(response.html);
    })
    .always(function () {
      $("#form-dialog-other").modal("show");
    });

  return false;
}
/*Load Users Popup end*/


/*add edit user form start*/
$(document).ready(function () {
  $("#users_form").validate({
    rules: {
      consumer_plan: {
        required: true,
      },
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
      qualification: {
        required: true,
      },
      interest_with: {
        required: true,
      },
    },
    submitHandler: function (form) {
      let formData = new FormData($("#users_form")[0]);
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
          $("#popupalert").hide();
        },
      })

        .fail(function (response) {
          alert("Try again later.");
        })

        .done(function (response) {
          $(".btnsbt").html("Submit");
          $(".btnsbt").prop("disabled", false);
          if (response.status == 0) {
            $("#popupalert").show();
            $("#popupalert").html(response.message);
          } else {
            $("#form-dialog-other").trigger("click");
            $("#mytable").DataTable().destroy();
            tableLoad(
              response.fetchTableurl,
              response.user_type,
              response.portal,
              response.show_by
            );
            $("#alert").show();
            $("#alert").html(response.message);
            
            $("#users_form")[0].reset();
          }
        })
        .always(function () {
          $(".btnsbt").html("Submit");
          $(".btnsbt").prop("disabled", false);
        });
      return false;
    },
  });
});

/*add edit district_manager_form form end*/

$(function() { 
  $("#qualification").multipleSelect({
    placeholder: 'Select Qualification',
    
  });
  $("#interest_with").multipleSelect({
    placeholder: 'Select Interest with',
  });
});

$(function() { 
  $("#qualification_blog").multipleSelect({
    placeholder: 'All Qualification',
    onOpen: function () {
    remove_page()  
    return false;
      
    },
    width: 150,
    
    
  });
  $("#interest_with_blog").multipleSelect({
    placeholder: 'All Interest',
    onOpen: function () {
      remove_page()  
      return false;
        
    },
    width: 150,
  });

  $("#users_list").multipleSelect({
    placeholder: 'All Users',
    // onOpen: function () {
    //   remove_page()  
    //   return false;
        
    // },
    width: 400,
  });
});


/*change user status start*/
function changeUserStatus(user_id, status, user_type) {
  if (status == 1) {
    var alert = "active";
  } else {
    var alert = "deactive";
  }
  if (user_type == 1) {
    alertmessage =
      "Are you sure you want to " + alert + " this District Manager?";
  } else if (user_type == 2) {
    alertmessage = "Are you sure you want to " + alert + " this Distributor?";
  } else if (user_type == 3) {
    alertmessage = "Are you sure you want to " + alert + " this Retailer?";
  } else if (user_type == 4) {
    alertmessage = "Are you sure you want to " + alert + " this Consumer?";
  }
  if (confirm(alertmessage)) {
    $.ajax({
      method: "POST",
      url: baseUrl + "include/process.php?action=change_user_status",
      data: { user_id: user_id, status: status, user_type: user_type },
      dataType: "JSON",
      beforeSend: function () {
        $(".stbtn").attr("disabled", true);
        $("#alert").hide();
      },
    })

      .fail(function (response) {
        alert("Try again later.");
      })

      .done(function (response) {
        if (response.status == 0) {
          $(".stbtn").attr("disabled", false);
        } else {
          $("#mytable").DataTable().destroy();
          tableLoad(
            response.fetchTableurl,
            response.user_type,
            response.portal,
            response.show_by
          );
        }
        $("#alert").html(response.message);
        $("#alert").show();
      })

      .always(function () {
        $(".stbtn").attr("disabled", false);
      });
  } else {
    return false;
  }
}
/*change user status end*/


/*load user detail model script start*/
function detailPopupUser(user_id) {
  $.ajax({
    method: "POST",
    url: baseUrl + "include/process.php?action=detail_popup_user",
    data: { user_id: user_id },
    dataType: "JSON",
    beforeSend: function () {
      $("#form-dialog-other").modal("show");
      $("#popupcontent").html('<div id="loader"></div>');
    },
  })

    .fail(function (response) {
      alert("Try again later.");
    })

    .done(function (response) {
      $.getScript(baseUrl + "assets/js/custom.js");

      $("#popupcontent").html(response.html);
    })
    .always(function () {
      $("#form-dialog-other").modal("show");
    });

  return false;
}
/*load user model script end*/
function showAlert() {
  alert ("Coming Soon!");
}
function showServiceAlert() {
  alert ("Please login as retailer and use this service!");
}

/*load blog list script start*/
function get_blogs(){
  var form=jQuery("#blogFetch").serialize();
  jQuery.ajax({
    type:'POST',
    url: baseUrl + "include/process.php?action=get_blogs",
    data:form,
    dataType:'JSON',
    beforeSend:function(){
      jQuery(".loader").fadeIn("slow");
      jQuery("#blog_list").html('');
      jQuery("#pagination").html('');
      jQuery("#searchBtn").html('Filter..');
    },
   
  })
  .fail(function (response) {
    alert("Try again later.");
  })
  .done(function (response) {
    jQuery(".loader").fadeOut("slow");
    jQuery("#blog_list").html(response.html);
    jQuery("#pagination").html(response.pagination);
    jQuery("#searchBtn").html('Filter <span class="mai-filter"></span>');
  })
  .always(function () {
    jQuery(".loader").fadeOut("slow");
    jQuery("#searchBtn").html('Filter <span class="mai-filter"></span>');
  });
  return false;
  }

/*reset filter form start*/
function resetFilterFrom() {
  $("#blogFetch")[0].reset();
  $.getScript(baseUrl + "assets/js/custom.js");
  window.history.pushState("object or string", "Title", baseUrl+'blog.php');
  $('#paged').val(1);
  get_blogs();
  return false;
}
/*reset filter form end*/

function change_url(url,page){
  window.history.pushState("object or string", "Title", url);
  $('#paged').val(page);
  get_blogs();
  return false;
  }
  
  function remove_page(){
    window.history.pushState("object or string", "Title", baseUrl+'blog.php');
    $('#paged').val(1);
  }


  /*load job form script start*/
function load_job_form(blog_id){
  
  jQuery.ajax({
    type:'POST',
    url: baseUrl + "include/process.php?action=load_job_form",
    data:{blog_id:blog_id},
    dataType:'JSON',
    beforeSend:function(){

      jQuery("#job_form_div").html('Please wait....');
      jQuery("#loadBtn").html('Send Job Notification <span class="mai-arrow-forward-circle"></span>');
      
    },
   
  })
  .fail(function (response) {
    alert("Try again later.");
  })
  .done(function (response) {
    $.getScript(baseUrl + "assets/js/custom.js");
    jQuery("#job_form_div").html(response.html);
    jQuery("#job_form_div").focus();
    jQuery("#loadBtn").html('Send Job Notification <span class="mai-notifications-circle"></span>');
  })
  .always(function () {
    jQuery("#loadBtn").html('Send Job Notification <span class="mai-notifications-circle"></span>');
  });
  return false;
  }

/*send notification script start*/
function send_notification(){
  var form=jQuery("#sendJobNotification").serialize();
  jQuery.ajax({
    type:'POST',
    url: baseUrl + "include/process.php?action=send_job_notification",
    data:form,
    dataType:'JSON',
    beforeSend:function(){
      jQuery("#alert_div").html('');
      jQuery("#sendBtn").prop("disabled", true);
      jQuery("#sendBtn").html('Sending..');
    },
   
  })
  .fail(function (response) {
    alert("Try again later.");
  })
  .done(function (response) {
      jQuery("#alert_div").html(response.message);
      jQuery("#sendBtn").prop("disabled", false);
      jQuery("#sendBtn").html('Send Notification');
      if(response.status==1){
        jQuery("#job_form_div").html('');
      }
  })
  .always(function () {
    
    jQuery("#sendBtn").prop("disabled", false);
    jQuery("#sendBtn").html('Send Notification');
  });
  return false;
  }
  