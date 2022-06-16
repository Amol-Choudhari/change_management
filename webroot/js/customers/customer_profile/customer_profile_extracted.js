$(".getState").change(function(){

	get_district();
});

$("#upload_file").change(function(){

	file_browse_onclick('upload_file');
	return false;
});

$("#profile_pic").change(function(){

	file_browse_onclick('profile_pic');
	return false;
});


$(".updateButtonProfile").click(function(e){

	if(register_customer_validations()==false){
		e.preventDefault();
	}else{
		$("#add_firm_form").submit();
	}

});

var return_error_msg = $('#return_error_msg').val();
            
if(return_error_msg != ''){
    $.alert(return_error_msg);
}
            


$(document).ready(function(){
    $('#once_card_no').focusout(function(){
        var once_card_no = $('#once_card_no').val();

        if(once_card_no.match(/^(?=.*[0-9])[0-9]{12}$/g) || once_card_no.match(/^[X-X]{8}[0-9]{4}$/i)){//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol

        }else{
            //alert("aadhar card number should be of 12 numbers only");
            $("#error_aadhar_card_no").show().text("Should not blank, Only numbers allowed, min & max length is 12");
            $("#error_aadhar_card_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            $("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text;});
            return false;
        }
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

$(document).ready(function () {bsCustomFileInput.init();});


$(document).ready(function () {
    $.validator.setDefaults({
        submitHandler: function () {
        alert( "Form successful submitted!" );
        }
    });

    $('#quickForm').validate({
        rules: {
        email: {
            required: true,
            email: true,
        },
        password: {
            required: true,
            minlength: 5
        },
        terms: {
            required: true
        },
    },
    messages: {
        email: {
            required: "Please enter a email address",
            email: "Please enter a vaild email address"
        },
        password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 5 characters long"
        },
        terms: "Please accept our terms"
    },

    errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
