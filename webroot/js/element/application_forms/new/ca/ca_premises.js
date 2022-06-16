$("#bevo_mills_address_docs").change(function(){

	file_browse_onclick('bevo_mills_address_docs');
	return false;
});

$("#separate_tanks_docs").change(function(){

	file_browse_onclick('separate_tanks_docs');
	return false;
});

$("#state").change(function(){

	get_district();
});

var final_submit_status = $('#final_submit_status_id').val();
var ca_bevo_applicant = $('#ca_bevo_applicant_id').val();


//function to check empty fields of tanks details table on add/edit button
function validate_tanks_details(){

    var tank_no = $('#tank_no').val();
    var tank_shape  = $('#tank_shape').val();
    var tank_size  = $('#tank_size').val();
    var tank_capacity  = $('#tank_capacity').val();
    var value_return = 'true';

    //if(tank_no==""){
    // Change Condition for validation and error message by pravin 12-07-2017
    if(check_whitespace_validation_textbox(tank_no).result == false){

        $("#error_tank_no").show().text("Please enter tank No.");
        // $("#error_tank_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
        setTimeout(function(){ $("#error_tank_no").fadeOut();},5000);
        $("#tank_no").addClass("is-invalid");
        $("#tank_no").click(function(){$("#error_tank_no").hide().text; $("#tank_no").removeClass("is-invalid");});

        value_return = 'false';
    }

    if(tank_shape==""){

        $("#error_tank_shape").show().text("Please Select tank shape");
        // $("#error_tank_shape").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
        setTimeout(function(){ $("#error_tank_shape").fadeOut();},5000);
        $("#tank_shape").addClass("is-invalid");
        $("#tank_shape").click(function(){$("#error_tank_shape").hide().text; $("#tank_shape").removeClass("is-invalid");});

        value_return = 'false';
    }

    //if(tank_size==""){

    // Change Condition for validation and error message by pravin 12-07-2017
    if(check_whitespace_validation_textbox(tank_size).result == false){

        $("#error_tank_size").show().text("Please enter tank size");
        // $("#error_tank_size").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
        setTimeout(function(){ $("#error_tank_size").fadeOut();},5000);
        $("#tank_size").addClass("is-invalid");
        $("#tank_size").click(function(){$("#error_tank_size").hide().text; $("#tank_size").removeClass("is-invalid");});

        value_return = 'false';
    }

    //if(tank_capacity==""){

    // Change Condition for validation and error message by pravin 12-07-2017
    if(check_number_with_decimal_two_validation(tank_capacity).result == false){

        $("#error_tank_capacity").show().text("Please enter tank capacity");
        // $("#error_tank_capacity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
        setTimeout(function(){ $("#error_tank_capacity").fadeOut();},5000);
        $("#tank_capacity").addClass("is-invalid");
        $("#tank_capacity").click(function(){$("#error_tank_capacity").hide().text; $("#tank_capacity").removeClass("is-invalid");});

        value_return = 'false';
    }

    if(value_return == 'false')
    {
        // alert("Please check some fields are missing or not proper.");
        var msg = "Please check some fields are missing or not proper.";
        renderToast('error', msg);
        return false;
    }
    else{
        return true;
    }

}



    // function for number with decimal two validation by pravin 12-07-2017
    function check_number_with_decimal_two_validation(field_value)
    {
        var field_length = field_value.length;
        var field_trim = $.trim(field_value);
        var update_field_value = field_trim.length;
        var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 2 decimal point allowed';
        var error_message2 = 'Please Remove blank space before and after the text';

        if(field_value.match(/^\d{1,25}(\.\d{1,2})?$/) == null)
        {
            if(update_field_value > 0)
            {
                return {result: false, error_message: error_message1};
            }
                return {result: false, error_message: error_message2};
        }

        return true;
    }

    // function for whitespace and blank value validation by pravin 12-07-2017
    function check_whitespace_validation_textbox(field_value){

        var field_length = field_value.length;
        var field_trim = $.trim(field_value);
        var update_field_value = field_trim.length;
        var error_message1 = 'This field is mandatory and maximum 50 characters allowed';
        var error_message2 = 'Please Remove blank space before and after the text';

        if(field_value != "")
        {
            if(update_field_value > 0)
            {
                if(field_length <= 50)
                {
                    return true;
                }
                    return {result: false, error_message: error_message1};
            }
                return {result: false, error_message: error_message2};
        }else{
                return {result: false, error_message: error_message1};
             }

    }


    $(document).ready(function(){

		//for undertaking
		//for already checked
		if($('#premises_belongs-yes').is(":checked")){

			$("#yes_premises_belongs").show();
			$("#no_premises_belongs").hide();

		}else if($('#premises_belongs-no').is(":checked")){

			$("#no_premises_belongs").show();
			$("#yes_premises_belongs").hide();
		}

		//for on clicked
		$('#premises_belongs-yes').click(function(){

			$("#yes_premises_belongs").show();
			$("#no_premises_belongs").hide();

		});

		$('#premises_belongs-no').click(function(){

			$("#no_premises_belongs").show();
			$("#yes_premises_belongs").hide();

		});

		//for registration certificate
		//for already checked
		if($('#reg_cert-yes').is(":checked")){

			$("#hide_reg_cert").show();

		}else if($('#reg_cert-no').is(":checked")){

			$("#hide_reg_cert").hide();

		}

		//for on clicked
		$('#reg_cert-yes').click(function(){

			$("#hide_reg_cert").show();

		});

		$('#reg_cert-no').click(function(){

			$("#hide_reg_cert").hide();

		});

		//for vat/cst

		//for already checked
		if($('#vat_cst-yes').is(":checked")){

			$("#hide_vat_cst").show();

		}else if($('#vat_cst-no').is(":checked")){

			$("#hide_vat_cst").hide();

		}

		$('#vat_cst-yes').click(function(){

			$("#hide_vat_cst").show();

		});

		$('#vat_cst-no').click(function(){

			$("#hide_vat_cst").hide();

		});
		//for seperate tanks used

		//for already checked
		if($('#separate_tanks_used-yes').is(":checked")){

			$("#hide_separate_tanks").show();

		}else if($('#separate_tanks_used-no').is(":checked")){

			$("#hide_separate_tanks").hide();

		}


		$('#separate_tanks_used-yes').click(function(){

			$("#hide_separate_tanks").show();

		});

		$('#separate_tanks_used-no').click(function(){

			$("#hide_separate_tanks").hide();

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


    	$(document).ready(function () {
    	  bsCustomFileInput.init();
    	});
