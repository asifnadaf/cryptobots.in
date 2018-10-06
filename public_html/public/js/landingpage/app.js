$(document).ready(function() {

    $(function() {
      $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
          if (target.length) {
            $('html,body').animate({
              scrollTop: target.offset().top
            }, 1000);
            return false;
          }
        }
      });
    });

    $( ".send-head" ).click(function() {
      $( ".form-div" ).slideToggle();
    });

    $('.success-message').hide();

    $("#sendMessageForm").submit(function() {

        var url = "contact_form/send_process.php"; // the script where you handle the form input.
        var formdata = $("#sendMessageForm").serialize();
        $.ajax({
               type: "POST",
               url: url,
               data: formdata, // serializes the form's elements.
               success: function(data)
               {
                  var jsonData = $.parseJSON(data);
                  if (jsonData.code == 200) {
                    $('.form-div').hide();
                    $('.success-message').html('Message sent successfully, we will contact you shortly').fadeIn();
                    $('.success-message').delay(3000).fadeOut();
                    $("#sendMessageForm")[0].reset();
                    $('.nameError').html("");
                    $('.emailError').html("");
                    $('.mobileError').html("");
                    $('.messageError').html("");
                  } else if (jsonData.code == 500) {
                    $('.form-div').hide();
                    $('.success-message').html('DNS error please try after some time').fadeIn();
                    $('.success-message').delay(3000).fadeOut();
                    $("#sendMessageForm")[0].reset();
                    $('.nameError').html("");
                    $('.emailError').html("");
                    $('.mobileError').html("");
                    $('.messageError').html("");
                  } else {
                      if(jsonData.errors.name){
                        $('.nameError').html(jsonData.errors.name);
                      } else {
                        $('.nameError').html("");
                      }
                      if(jsonData.errors.email){
                        $('.emailError').html(jsonData.errors.email);
                      } else {
                        $('.emailError').html("");
                      }
                      if(jsonData.errors.mobile){
                        $('.mobileError').html(jsonData.errors.mobile);
                      } else {
                        $('.mobileError').html("");
                      }
                      if(jsonData.errors.message){
                        $('.messageError').html(jsonData.errors.message);
                      } else {
                        $('.messageError').html("");
                      }
                  }

                  console.log(data);
               }
             });

        return false; // avoid to execute the actual submit of the form.
    });
});