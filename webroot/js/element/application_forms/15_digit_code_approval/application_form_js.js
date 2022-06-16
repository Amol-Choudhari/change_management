
$("#all_commo_graded_doc").change(function(){

	file_browse_onclick('all_commo_graded_doc');
	return false;
});

$("#relevant_doc").change(function(){

	file_browse_onclick('relevant_doc');
	return false;
});


var final_submit_status = $('#final_submit_status_id').val();


	//function to check empty fields of constituent oil mill details on add/edit button
	function validate_15_digit_appl(){

		var all_commo_graded_doc = $('#all_commo_graded_doc').val();
		var relevant_doc = $('#relevant_doc').val();
		
		var all_commo_graded_doc_value = $('#all_commo_graded_doc_value').text();
		var relevant_doc_value = $('#relevant_doc_value').text();

		var value_return = 'true';

		if(check_radio_button_validation('auto_packing_lines').result == false){

			$("#error_auto_packing_lines").show().text(check_radio_button_validation('auto_packing_lines').error_message);
			$("#error_auto_packing_lines").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#auto_packing_lines-yes").click(function(){$("#error_auto_packing_lines").hide().text;});
			$("#auto_packing_lines-no").click(function(){$("#error_auto_packing_lines").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('separate_sections_unit').result == false){

			$("#error_separate_sections_unit").show().text(check_radio_button_validation('separate_sections_unit').error_message);
			$("#error_separate_sections_unit").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#separate_sections_unit-yes").click(function(){$("#error_separate_sections_unit").hide().text;});
			$("#separate_sections_unit-no").click(function(){$("#error_separate_sections_unit").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('is_all_commo_graded').result == false){

			$("#error_is_all_commo_graded").show().text(check_radio_button_validation('is_all_commo_graded').error_message);
			$("#error_is_all_commo_graded").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_all_commo_graded-yes").click(function(){$("#error_is_all_commo_graded").hide().text;});
			$("#is_all_commo_graded-no").click(function(){$("#error_is_all_commo_graded").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('is_commo_stored_in_room').result == false){

			$("#error_is_commo_stored_in_room").show().text(check_radio_button_validation('is_commo_stored_in_room').error_message);
			$("#error_is_commo_stored_in_room").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_commo_stored_in_room-yes").click(function(){$("#error_is_commo_stored_in_room").hide().text;});
			$("#is_commo_stored_in_room-no").click(function(){$("#error_is_commo_stored_in_room").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('is_reg_stored_in_room').result == false){

			$("#error_is_reg_stored_in_room").show().text(check_radio_button_validation('is_reg_stored_in_room').error_message);
			$("#error_is_reg_stored_in_room").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_reg_stored_in_room-yes").click(function(){$("#error_is_reg_stored_in_room").hide().text;});
			$("#is_reg_stored_in_room-no").click(function(){$("#error_is_reg_stored_in_room").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('is_reg_stored_in_room').result == false){

			$("#error_is_reg_stored_in_room").show().text(check_radio_button_validation('is_reg_stored_in_room').error_message);
			$("#error_is_reg_stored_in_room").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_reg_stored_in_room-yes").click(function(){$("#error_is_reg_stored_in_room").hide().text;});
			$("#is_reg_stored_in_room-no").click(function(){$("#error_is_reg_stored_in_room").hide().text;});

			value_return = 'false';
		}
		
		if(all_commo_graded_doc_value==""){
			if(check_file_upload_validation(all_commo_graded_doc).result == false){

				$("#error_all_commo_graded_doc").show().text(check_file_upload_validation(all_commo_graded_doc).error_message);
				$("#all_commo_graded_doc").addClass("is-invalid");
				$("#all_commo_graded_doc").click(function(){$("#error_all_commo_graded_doc").hide().text; $("#all_commo_graded_doc").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		
		if(relevant_doc_value==""){
			if(check_file_upload_validation(relevant_doc).result == false){

				$("#error_relevant_doc").show().text(check_file_upload_validation(relevant_doc).error_message);
				$("#relevant_doc").addClass("is-invalid");
				$("#relevant_doc").click(function(){$("#error_relevant_doc").hide().text; $("#relevant_doc").removeClass("is-invalid");});

				value_return = 'false';
			}
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
	
	
	function check_file_upload_validation(field_value){
		var error_message = 'Please upload the required file';

		if(field_value == "")
		{
			return {result: false, error_message: error_message};
		}

		return true;
	}
	
	
	function check_radio_button_validation(field_value){
		var error_message = 'Please select the option';

		if($('input[name="'+field_value+'"]:checked').val() != "yes" && $('input[name="'+field_value+'"]:checked').val() != "no")
		{

			return {result: false, error_message: error_message};

		}

		return true;
	}