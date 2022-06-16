$(document).ready(function(){

    bsCustomFileInput.init();

});

$(".submit_btn").click(function(e){

	if(add_user_validations()==false){
		e.preventDefault();
	}else{
		$("#user_profile").submit();
	}

});



var return_error_msg = $('#return_error_msg').val();

if(return_error_msg != ''){
    $.alert(return_error_msg);
}