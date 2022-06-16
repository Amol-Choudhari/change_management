
	
		
//for already checked
if ($("#already_granted-yes").is(":checked")) {
   
   $("#old_details_section").show();
	$("#new_details_section").hide();
}

if ($("#already_granted-no").is(":checked")) {
   
   $("#old_details_section").hide();
	$("#new_details_section").show();
}

//for on check
$("#already_granted-yes").click(function(){
	
	$("#old_details_section").show();
	$("#new_details_section").hide();
	
});

$("#already_granted-no").click(function(){
	
	$("#old_details_section").hide();
	$("#new_details_section").show();
	
});

//for file uploading validation
$("#all_commo_graded_doc").change(function(){

	file_browse_onclick('all_commo_graded_doc');
	return false;
});

$("#relevant_doc").change(function(){

	file_browse_onclick('relevant_doc');
	return false;
});

$("#old_cert_doc").change(function(){

	file_browse_onclick('old_cert_doc');
	return false;
});


var final_submit_status = $('#final_submit_status_id').val();

//for validation
	function validate_e_code_appl(){
				
		var value_return = 'true';
		
		if ($("#already_granted-no").is(":checked")) {

			var all_commo_graded_doc = $('#all_commo_graded_doc').val();
			var relevant_doc = $('#relevant_doc').val();
			
			var all_commo_graded_doc_value = $('#all_commo_graded_doc_value').text();
			var relevant_doc_value = $('#relevant_doc_value').text();

			
			if(check_radio_button_validation('already_granted').result == false){

				$("#error_already_granted").show().text(check_radio_button_validation('already_granted').error_message);
				$("#error_already_granted").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
				$("#already_granted-yes").click(function(){$("#error_already_granted").hide().text;});
				$("#already_granted-no").click(function(){$("#error_already_granted").hide().text;});

				value_return = 'false';
			}


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
			
		}
		
		if ($("#already_granted-yes").is(":checked")) {
			
			var old_cert_no = $("#old_cert_no").val();
			var old_cert_doc = $("#old_cert_doc").val();
			var granted_e_code = $("#granted_e_code").val();
			var granted_on = $("#granted_on").val();
			var remark = $("#remark").val();
			var old_cert_doc_value = $("#old_cert_doc_value").text();
			
			if(check_whitespace_validation_textbox(old_cert_no).result == false){
						
				$("#error_old_cert_no").show().text(check_whitespace_validation_textbox(old_cert_no).error_message);
				$("#error_old_cert_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});						
				$("#old_cert_no").click(function(){$("#error_old_cert_no").hide().text;});
				
				value_return = 'false';
			}
			
			if(check_whitespace_validation_textbox(granted_e_code).result == false){
						
				$("#error_granted_e_code").show().text(check_whitespace_validation_textbox(granted_e_code).error_message);
				$("#error_granted_e_code").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});						
				$("#granted_e_code").click(function(){$("#error_granted_e_code").hide().text;});
				
				value_return = 'false';
			}
			
			if(check_whitespace_validation_textarea(remark).result == false){
						
				$("#error_remark").show().text(check_whitespace_validation_textarea(remark).error_message);
				$("#error_remark").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});						
				$("#remark").click(function(){$("#error_remark").hide().text;});
				
				value_return = 'false';
			}
			
			if(granted_on==''){
						
				$("#error_granted_on").show().text('Select the date');
				$("#error_granted_on").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});						
				$("#granted_on").click(function(){$("#error_granted_on").hide().text;});
				
				value_return = 'false';
			}
			
			if(old_cert_doc_value==""){
				if(check_file_upload_validation(old_cert_doc).result == false){

					$("#error_old_cert_doc").show().text(check_file_upload_validation(old_cert_doc).error_message);
					$("#old_cert_doc").addClass("is-invalid");
					$("#old_cert_doc").click(function(){$("#error_old_cert_doc").hide().text; $("#old_cert_doc").removeClass("is-invalid");});

					value_return = 'false';
				}
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
	
	function check_whitespace_validation_textbox(field_value){
		
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 50 characters allowed';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value != "")
		{
			//if(field_length == update_field_value)
			//{
			
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{	
				if(field_length <= 50)
				{
					return true;
				}
					return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message1};
		}else{
				return {result: false, error_message: error_message1};
			 }
		
	}
	
	function check_whitespace_validation_textarea(field_value){
		
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 500 characters allowed';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value != "")
		{
			
			//if(field_length == update_field_value)
			//{
				
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{	
				if(field_length <= 500)
				{
					return true;
				}
					return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message1};
		}else{
				return {result: false, error_message: error_message1};
			 }
		
	}
	
//date picker
$('#granted_on').datepicker({
	format: "dd/mm/yyyy",
	autoclose: true,
	endDate: new Date()
});