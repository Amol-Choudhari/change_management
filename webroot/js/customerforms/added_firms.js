
$("#profile_pic").change(function(){

	file_browse_onclick('profile_pic');
	return false;
});

$(".updateButton").click(function(e){

	if(add_firm_validations() == false){
		e.preventDefault();
	}else{
		$("#added_firm_form").submit();
	}

});

$(".backButton").click(function(e){

	if(validations() == false){
		e.preventDefault();
	}else{
		$("#added_firm_form").submit();
	}

});

$("#error_aadhar_card_no").hide();
$("#msg_mobile_no").hide();

$('#once_card_no').click(function(){

    var once_card_no = $('#once_card_no').val();

    if(once_card_no.match(/^(?=.*[0-9])[0-9]{12}$/g)){

    }else{

        //alert("aadhar card number should be of 12 numbers only");
        $("#error_aadhar_card_no").show().text("Only numbers allowed, min & max length is 12");
        //$("#error_aadhar_card_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
        $("#once_card_no").focusout(function(){$("#error_aadhar_card_no").hide().text;});
        return false;
    }
});

$('#mobile_no').click(function(){

    $("#msg_mobile_no").show().text("OTP will be sent on this no. to reset password");
    //$("#msg_mobile_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
    $("#mobile_no").focusout(function(){$("#msg_mobile_no").hide().text;});
    return false;

});


// if certification type is printing press hide commodity box and show packaging types box

var value = $('#certificate_type_value').val();

if(value == 2)
{
    $('#commodity_box').hide();
    $('#packaging_types_box').show();

}else if(value == 1 || value == 3)
{
    $('#commodity_box').show();
    $('#packaging_types_box').hide();
}

//added on 18-05-2017 by Amol
if(value == 2)//changed condition from 2&3 to 2 only
{
    $('#export_unit').hide();

}else if(value == 1 || value == 3)//changed condition from 1 to 1&3
{
    $('#export_unit').show();
}


if($('#radioPrimary1').is(":checked")){

    $("#old_granted_certificate").show();

}else if($('#radioPrimary2').is(":checked")){

    $("#old_granted_certificate").hide();

}

$(document).ready(function () {
  bsCustomFileInput.init();
});
