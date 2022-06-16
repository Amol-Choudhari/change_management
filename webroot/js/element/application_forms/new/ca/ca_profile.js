//// ca profile javascript validations	
	
	$("#state").change(function(){
		get_district();
	});


	$("#fssai_reg_docs").change(function(){
		file_browse_onclick('fssai_reg_docs');
		return false;
	});


	$("#authorised_bevo_docs").change(function(){
		file_browse_onclick('authorised_bevo_docs');
		return false;
	});


	$("#oil_manu_affidavit_docs").change(function(){
		file_browse_onclick('oil_manu_affidavit_docs');
		return false;
	});


	$("#vopa_certificate_docs").change(function(){
		file_browse_onclick('vopa_certificate_docs');
		return false;
	});


	$("#bank_references_docs").change(function(){
		file_browse_onclick('bank_references_docs');
		return false;
	});


	$("#business_type_docs").change(function(){
		file_browse_onclick('business_type_docs');
		return false;
	});


	$("#old_certification_pdf").change(function(){
		file_browse_onclick('old_certification_pdf');
		return false;
	});


	$("#old_application_docs").change(function(){
		file_browse_onclick('old_application_docs');
		return false;
	});


	var final_submit_status = $('#final_submit_status_id').val();
	var ca_bevo_applicant = $('#ca_bevo_applicant_id').val();

	//function to check empty fields of constituent oil mill details on add/edit button
	function validate_const_oil_details(){

		var oil_name = $('#oil_name').val();
		var mill_name_address  = $('#mill_name_address').val();
		var quantity_procured  = $('#quantity_procured').val();
		var value_return = 'true';

		// Change Condition for validation and error message by pravin 12-07-2017
		if(check_whitespace_validation_textbox(oil_name).result == false){

			$("#error_oil_name_add").show().text("Please enter oil name");
			setTimeout(function(){ $("#error_oil_name_add").fadeOut();},5000);
			$("#oil_name").addClass("is-invalid");
			$("#oil_name").click(function(){$("#error_oil_name_add").hide().text; $("#oil_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 12-07-2017
		if(check_whitespace_validation_textbox(mill_name_address).result == false){

			$("#error_mill_name_address_add").show().text("Please enter mill name & addres");
			setTimeout(function(){ $("#error_mill_name_address_add").fadeOut();},5000);
			$("#mill_name_address").addClass("is-invalid");
			$("#mill_name_address").click(function(){$("#error_mill_name_address_add").hide().text; $("#mill_name_address").removeClass("is-invalid");});
			value_return = 'false';
		}

	
		// Change Condition for validation and error message by pravin 12-07-2017
		if(check_number_with_decimal_two_validation(quantity_procured).result == false){

			$("#error_quantity_procured_add").show().text("Please enter procured quantity, only number value allowd");
			setTimeout(function(){ $("#error_quantity_procured_add").fadeOut();},5000);
			$("#quantity_procured").addClass("is-invalid");
			$("#quantity_procured").click(function(){$("#error_quantity_procured_add").hide().text; $("#quantity_procured").removeClass("is-invalid");});
			value_return = 'false';
		}


		if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
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
			if(update_field_value > 0){
				
				if(field_length <= 50){
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

		/*Comment the Registration/License radio button "on clicked" and "on checked" functionality because this field mandatory now
		 Done By Pravin 02-02-2018*/
		//for Registration/License No.
		$("#reg_lic-yes").hide();

		//for Authorised BEVO
		//for already checked
		if($('#authorised_for_bevo-yes').is(":checked")){

			$("#hide_bevo_authorised").show();

		}else if($('#authorised_for_bevo-no').is(":checked")){

			$("#hide_bevo_authorised").hide();

		}


		//for on clicked
		$('#authorised_for_bevo-yes').click(function(){

			$("#hide_bevo_authorised").show();

		});

		$('#authorised_for_bevo-no').click(function(){

			$("#hide_bevo_authorised").hide();

		});

	});
	
	
	//Get the District
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
