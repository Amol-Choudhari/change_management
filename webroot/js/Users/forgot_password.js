
$("#new_captcha").click(function(){

	get_new_captcha();
	return false;
});


$(".submit_btn").click(function(e){

	if(forgot_password_validations()==false){
		e.preventDefault();
	}else{
		$("#forgot_password_form").submit();
	}

});


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
