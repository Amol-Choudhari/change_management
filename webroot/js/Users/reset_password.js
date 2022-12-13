
//get new captcha
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

//Call to new Captcha
$('#new_captcha').click(function (e) {
    e.preventDefault();
    get_new_captcha()
});


$("#submit").click(function(e){

	if(reset_password_validations()==false){
		e.preventDefault();
	}else{
		$("#reset_password").submit();
	}

});


//below code added on 19-09-2022 to reset captcha on reset password window
function get_new_captch_resetPass(){
    $.ajax({
            type: "POST",
            async:true,
            url:"../refresh_captcha_code",
            beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success: function (data) {
                    $("#captcha_img").html(data);
            }
    });
}

$("#new_captcha_resetPass").click(function(e){

    get_new_captch_resetPass();

});
