//Call to new Captcha
$('#new_captcha').click(function (e) {
    e.preventDefault();
    get_new_captcha()
});

//call to login validations
$('.myfunctionclick').click(function (e) {
    if (feedbackFormValidation() == false) {
        e.preventDefault();
    } else {
      $('#feedback_form_id').submit();  
    }
});


//if selected other option, then show textbox to enter other feedback type , Change on 30-08-2018, By Pravin Bhakare//
$("#other").css('display','none');
$("#type").change(function() {
var feedbacktype = $("#type").val();
    if (feedbacktype == 1) {
        $("#other").css('display','block');
    } else {
        $("#other").css('display','none');
    }
});



function feedbackFormValidation() {

    var type         = $("#type").val();
    var email        = $("#email").val();
    var firstname    = $("#firstname").val();
    var lastname     = $("#lastname").val();
    var mobile       = $("#mobile").val();
    var address      = $("#address").val();
    var comment      = $("#comment").val();
    var other        = $("#other").val();
    var password     = $("#passwordValidation").val();
    var captchacode  = $("#captchacode").val();
    var value_return = 'true';

    if (type=="") {

        $("#error_type").show().text("Please select feedback type.");
        $("#type").addClass("is-invalid");
        $("#type").click(function() {$("#error_type").hide().text;$("#type").removeClass("is-invalid");});
        value_return='false';

    // Change on 30-08-2018, Pravin Bhakare
    } else if (type==1) {

        if (other=="") {

            $("#error_othertype").show().text("Please enter Other Feedback Type.");
            $("#other").addClass("is-invalid");
            $("#other").click(function() {$("#error_type").hide().text;$("#other").removeClass("is-invalid");});
            value_return='false';
        }
    }


    if(email==""){

        $("#error_email").show().text("Please Enter Email Address");
        $("#email").addClass("is-invalid");
        $("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
        value_return = 'false';

    }else{

        if(email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){}else{

            $("#error_email").show().text("Please Enter Valid Email Address");
            $("#email").addClass("is-invalid");
            $("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
            value_return = 'false';
        }

    }


    if (firstname=="") {

        $("#error_firstname").show().text("Please enter your firstname.");
        $("#firstname").addClass("is-invalid");
        $("#firstname").click(function() {$("#error_firstname").hide().text;$("#firstname").removeClass("is-invalid");});
        value_return='false';
    }

    if (lastname=="") {

        $("#error_lastname").show().text("Please enter your lastname.");
        $("#lastname").addClass("is-invalid");
        $("#lastname").click(function() {$("#error_lastname").hide().text;$("#lastname").removeClass("is-invalid");});
        value_return='false';
    }

    if (mobile=="") {

        $("#error_mobile").show().text("Please enter mobile number.");
        $("#mobile").addClass("is-invalid");
        $("#mobile").click(function() {$("#error_mobile").hide().text;$("#mobile").removeClass("is-invalid");});
        value_return='false';

    }else{

        if(mobile.match(/^(?=.*[0-9])[0-9]{10}$/g)){}else{//also allow if 6 X $ 4 nos found //added on 12-10-2017 by Amol

            $("#error_mobile").show().text("Should not be blank, Only numbers allowed, max & min length is 10");
            $("#mobile").addClass("is-invalid");
            $("#mobile").click(function(){$("#error_mobile").hide().text; $("#mobile").removeClass("is-invalid");});
            value_return = 'false';
        }

        //first valid no. for mob.no, applid on 16-02-2021 by Amol
        var validfirstno = ['7','8','9'];
        var f_m_no = mobile.charAt(0);
        if($.inArray(f_m_no,validfirstno) != -1){
            //valid
        }else{
            $("#error_mobile").show().text("Invalid mobile number");
            $("#mobile").addClass("is-invalid");
            $("#mobile").click(function(){$("#error_mobile").hide().text; $("#mobile").removeClass("is-invalid");});
            value_return='false';
        }
    }

    

    if (address=="") {

        $("#error_address").show().text("Please enter address.");
        $("#address").addClass("is-invalid");
        $("#address").click(function() {$("#error_address").hide().text;$("#address").removeClass("is-invalid");});
        value_return='false';
    }

    if (comment=="") {

        $("#error_comment").show().text("Please enter comment.");
        $("#comment").addClass("is-invalid");
        $("#comment").click(function() {$("#error_comment").hide().text;$("#comment").removeClass("is-invalid");});
        value_return='false';
    }

    if (captchacode=="") {

        $("#error_captchacode").show().text("Please enter captcha code.");
        $("#captchacode").addClass("is-invalid");
        $("#captchacode").click(function() {$("#error_captchacode").hide().text;$("#captchacode").removeClass("is-invalid");});
        value_return='false';
    }

    if (value_return=='false') {
        var msg = "Please check some fields are missing or not proper.";
        renderToast('error', msg);
        return false;
    } else {

        var PasswordValue = document.getElementById('passwordValidation').value;
        var SaltValue = document.getElementById('hiddenSaltvalue').value;
        var EncryptPass = sha512(PasswordValue);
        var SaltedPass = SaltValue.concat(EncryptPass);
        var Saltedsha512pass = sha512(SaltedPass);
        document.getElementById('passwordValidation').value = Saltedsha512pass;
        exit();
    }
 }


function get_new_captcha() {

    $.ajax({
        type: "POST",
        async:true,
        url:"refresh_captcha_code",
        beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success: function (data) {
                $("#captcha_img").html(data);
        }
    });
}


var return_error_msg = $('#return_error_msg').val();
            
if(return_error_msg != ''){
    $.alert(return_error_msg);
}