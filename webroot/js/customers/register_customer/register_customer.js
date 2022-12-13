
$("#state").change(function(){

	get_district();
});

$('#new_captcha').click(function (e) {
    e.preventDefault();
    get_new_captcha()
});

$('#register_btn').click(function (e) {
    if (register_customer_validations() == false) {
        e.preventDefault();
    } else {
      $('#login_user_form').submit();
    }
});





    $(document).ready(function(){

        $("#email").click(function(){$("#error_already_exist").hide().text;});
        $("#confirm_email").click(function(){$("#error_already_exist").hide().text;});
        $(".aadhar_check").hide();

        $('#once_card_no').focusout(function(){
        var once_card_no = $('#once_card_no').val();

            if(once_card_no != ''){//applied this condition on 22-03-2018 to avoid mandatory for aadhar

                if(once_card_no.match(/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9]{12,72}$/g)){

                    $(".aadhar_check").show();

                }else{

                    //alert("aadhar card number should be of 12 numbers only");
                    $("#error_aadhar_card_no").show().text("Please Enter Proper Aadhar/VID/Token Id.");
                    $("#error_aadhar_card_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
                    $("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text;});
                    return false;
                }
            }
        });

            //added on 12-08-2017 by Amol to avoid copy paste on confirm email field
            $('#confirm_email').bind("cut copy paste",function(e) {
            e.preventDefault();
            });

    });


    function get_district(){

        $("#district").find('option').remove();
        var state = $("#state").val();

        $.ajax({
            type: "POST",
            async:true,
            url:"../AjaxFunctions/show-district-dropdown",
            data: {state:state},
            beforeSend: function (xhr) { // Add this line
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },

            success: function (data) {
                $("#district").append(data);
            }
        });

    }


    function get_new_captcha(){

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

    $("#state").val('');//line added on 13-07-2018

    $(document).ready(function () {bsCustomFileInput.init();});

    //FOR CHECKING THE EMAIL ALREADY EXITS AJAX in customers table AND VALIDATION IS ADDED BY AKASH ON 22-12-2021
    $('#email').focusout(function(){

        var email = $("#email").val();

        if (email != '') {

             $.ajax({
                type : 'POST',
                url : '../AjaxFunctions/check_email_exist_in_customer_table',
                async : true,
                data : {email:email},
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success : function(response){

                    if($.trim(response)=='yes'){

                        $.alert({
                            title: "Alert!",
                            content: 'The Email is already used. Please verify and enter again.',
                            typeAnimated: true,
                            buttons: {
                                Retry: {
                                    text: 'Retry',
                                    btnClass: 'btn-red',
                                    action: function(){
                                        $("#email").val('');
                                    }
                                },
                            }
                        });
                    }
                }
            });
        }
    });


    //FOR CHECKING THE EMAIL & CONFIRM EMAIL ARE SAME OR NOT ON 22-12-2021 BY AKASH
    $('#confirm_email').focusout(function(){

        var confirm_email = $("#confirm_email").val();
        var email = $('#email').val();
        if (email != '') {
            if (confirm_email != '') {
                if (email != confirm_email) {
                    $.alert('Email not matched!!');
                    $('#confirm_email').val('');
                }
            }
        }
    });


    //FOR CHECKING THE MOBILE ALREADY EXITS AJAX in customers table AND VALIDATION IS ADDED BY AKASH ON 22-12-2021
    $('#mobile').focusout(function(){

        var mobile = $("#mobile").val();

        if (mobile != '') {
            $.ajax({
                type : 'POST',
                url : '../AjaxFunctions/check_mobile_number_exist_in_customers_table',
                async : true,
                data : {mobile:mobile},
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success : function(response){

                    if($.trim(response)=='yes'){

                        $.alert({
                            title: "Alert!",
                            content: 'The mobile is already used. Please verify and enter again.',
                            typeAnimated: true,
                            buttons: {
                                Retry: {
                                    text: 'Retry',
                                    btnClass: 'btn-red',
                                    action: function(){
                                        $("#mobile").val('');
                                    }
                                },
                            }
                        });
                    }
                }
            });
        }
    });


var return_error_msg = $('#return_error_msg').val();
        
    if(return_error_msg != ''){
        $.alert(return_error_msg);
    }