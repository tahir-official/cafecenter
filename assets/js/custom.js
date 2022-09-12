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
