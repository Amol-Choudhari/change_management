
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


$(".submit_btn").click(function(e){

	if(reset_password_validations()==false){
		e.preventDefault();
	}else{
		$("#change_password").submit();
	}

});
