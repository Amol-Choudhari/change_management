$(document).ready(function() {

    var captcha_error_msg = $('#captcha_error_msg').val();
    var used_email_error_msg = $('#used_email_error_msg').val();
    var primary_registered = $('#primary_registered').val();

    if( captcha_error_msg == '' || captcha_error_msg == null ){ captcha_error_msg = null; }
    if( used_email_error_msg == '' || used_email_error_msg == null ){ used_email_error_msg = null; }
    if( primary_registered == '' || primary_registered == null ){ primary_registered = null; }


    if(captcha_error_msg != null) {
        $('#captchacode').addClass("is-invalid");
        $('#captchacode').click(function() { $(this).removeClass("is-invalid"); });
        renderToast('error',captcha_error_msg);

    }

    if(used_email_error_msg != null) {

        $('#confirm_email').addClass("is-invalid");
        $('#confirm_email').click(function() { $(this).removeClass("is-invalid"); });
        $('#email').addClass("is-invalid");
        $('#email').click(function() { $(this).removeClass("is-invalid"); });
        renderToast('error',used_email_error_msg);

    }

    if (primary_registered != null) {

        renderToast('success', 'Customer successfully added !');

    }

});
