
$("#automatic_system_docs").change(function(){

	file_browse_onclick('automatic_system_docs');
	return false;
});

$("#separate_records_docs").change(function(){

	file_browse_onclick('separate_records_docs');
	return false;
});
$("#copy_of_orders_docs").change(function(){

	file_browse_onclick('copy_of_orders_docs');
	return false;
});

$("#copy_of_printing_docs").change(function(){

	file_browse_onclick('copy_of_printing_docs');
	return false;
});
$("#empty_container_docs").change(function(){

	file_browse_onclick('empty_container_docs');
	return false;
});

$("#issue_of_empty_container_docs").change(function(){

	file_browse_onclick('issue_of_empty_container_docs');
	return false;
});
$("#reg_sale_invoice_docs").change(function(){

	file_browse_onclick('reg_sale_invoice_docs');
	return false;
});

$("#graded_min_qty_docs").change(function(){

	file_browse_onclick('graded_min_qty_docs');
	return false;
});


var final_submit_status = $('#final_submit_status_id').val();


	//function to check empty fields of constituent oil mill details on add/edit button
	function validate_15_digit_report(){

		//validate file upload first time
		var automatic_system_docs = $('#automatic_system_docs').val();
		var separate_records_docs = $('#separate_records_docs').val();
		var copy_of_orders_docs = $('#copy_of_orders_docs').val();
		var copy_of_printing_docs = $('#copy_of_printing_docs').val();
		var empty_container_docs = $('#empty_container_docs').val();
		var issue_of_empty_container_docs = $('#issue_of_empty_container_docs').val();
		var reg_sale_invoice_docs = $('#reg_sale_invoice_docs').val();
		var graded_min_qty_docs = $('#graded_min_qty_docs').val();
		
		//validate file upload next time
		var automatic_system_docs_value = $('#automatic_system_docs_value').text();
		var separate_records_docs_value = $('#separate_records_docs_value').text();
		var copy_of_orders_docs_value = $('#copy_of_orders_docs_value').text();
		var copy_of_printing_docs_value = $('#copy_of_printing_docs_value').text();
		var empty_container_docs_value = $('#empty_container_docs_value').text();
		var issue_of_empty_container_docs_value = $('#issue_of_empty_container_docs_value').text();
		var reg_sale_invoice_docs_value = $('#reg_sale_invoice_docs_value').text();
		var graded_min_qty_docs_value = $('#graded_min_qty_docs_value').text();
		
		var recommendations = $('#recommendations').val();
	
		//for textarea
		if(check_whitespace_validation_textarea(recommendations).result == false){
			
			$("#error_recommendations").show().text(check_whitespace_validation_textarea(recommendations).error_message);
			$("#error_recommendations").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#recommendations").addClass("is-invalid");
			$("#recommendations").click(function(){$("#error_recommendations").hide().text; $("#recommendations").removeClass("is-invalid");});
			
			value_return = 'false';
		}

		var value_return = 'true';

	//to validate radio buttons
		if(check_radio_button_validation('is_automatic_system').result == false){

			$("#error_is_automatic_system").show().text(check_radio_button_validation('is_automatic_system').error_message);
			$("#error_is_automatic_system").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_automatic_system-yes").click(function(){$("#error_is_automatic_system").hide().text;});
			$("#is_automatic_system-no").click(function(){$("#error_is_automatic_system").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('is_separate_records').result == false){

			$("#error_is_separate_records").show().text(check_radio_button_validation('is_separate_records').error_message);
			$("#error_is_separate_records").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_separate_records-yes").click(function(){$("#error_is_separate_records").hide().text;});
			$("#is_separate_records-no").click(function(){$("#error_is_separate_records").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('is_copy_of_orders').result == false){

			$("#error_is_copy_of_orders").show().text(check_radio_button_validation('is_copy_of_orders').error_message);
			$("#error_is_copy_of_orders").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_copy_of_orders-yes").click(function(){$("#error_is_copy_of_orders").hide().text;});
			$("#is_copy_of_orders-no").click(function(){$("#error_is_copy_of_orders").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('is_copy_of_printing').result == false){

			$("#error_is_copy_of_printing").show().text(check_radio_button_validation('is_copy_of_printing').error_message);
			$("#error_is_copy_of_printing").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#is_copy_of_printing-yes").click(function(){$("#error_is_copy_of_printing").hide().text;});
			$("#is_copy_of_printing-no").click(function(){$("#error_is_copy_of_printing").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('reg_of_empty_container').result == false){

			$("#error_reg_of_empty_container").show().text(check_radio_button_validation('reg_of_empty_container').error_message);
			$("#error_reg_of_empty_container").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#reg_of_empty_container-yes").click(function(){$("#error_reg_of_empty_container").hide().text;});
			$("#reg_of_empty_container-no").click(function(){$("#error_reg_of_empty_container").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('issue_of_empty_container').result == false){

			$("#error_issue_of_empty_container").show().text(check_radio_button_validation('issue_of_empty_container').error_message);
			$("#error_issue_of_empty_container").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#issue_of_empty_container-yes").click(function(){$("#error_issue_of_empty_container").hide().text;});
			$("#issue_of_empty_container-no").click(function(){$("#error_issue_of_empty_container").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('reg_of_raw_materials').result == false){

			$("#error_reg_of_raw_materials").show().text(check_radio_button_validation('reg_of_raw_materials').error_message);
			$("#error_reg_of_raw_materials").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#reg_of_raw_materials-yes").click(function(){$("#error_reg_of_raw_materials").hide().text;});
			$("#reg_of_raw_materials-no").click(function(){$("#error_reg_of_raw_materials").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('reg_daily_production').result == false){

			$("#error_reg_daily_production").show().text(check_radio_button_validation('reg_daily_production').error_message);
			$("#error_reg_daily_production").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#reg_daily_production-yes").click(function(){$("#error_reg_daily_production").hide().text;});
			$("#reg_daily_production-no").click(function(){$("#error_reg_daily_production").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('reg_daily_account_qty').result == false){

			$("#error_reg_daily_account_qty").show().text(check_radio_button_validation('reg_daily_account_qty').error_message);
			$("#error_reg_daily_account_qty").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#reg_daily_account_qty-yes").click(function(){$("#error_reg_daily_account_qty").hide().text;});
			$("#reg_daily_account_qty-no").click(function(){$("#error_reg_daily_account_qty").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('reg_damaged_container').result == false){

			$("#error_reg_damaged_container").show().text(check_radio_button_validation('reg_damaged_container').error_message);
			$("#error_reg_damaged_container").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#reg_damaged_container-yes").click(function(){$("#error_reg_damaged_container").hide().text;});
			$("#reg_damaged_container-no").click(function(){$("#error_reg_damaged_container").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('reg_showing_daily_stock').result == false){

			$("#error_reg_showing_daily_stock").show().text(check_radio_button_validation('reg_showing_daily_stock').error_message);
			$("#error_reg_showing_daily_stock").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#reg_showing_daily_stock-yes").click(function(){$("#error_reg_showing_daily_stock").hide().text;});
			$("#reg_showing_daily_stock-no").click(function(){$("#error_reg_showing_daily_stock").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('reg_sale_invoice').result == false){

			$("#error_reg_sale_invoice").show().text(check_radio_button_validation('reg_sale_invoice').error_message);
			$("#error_reg_sale_invoice").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#reg_sale_invoice-yes").click(function(){$("#error_reg_sale_invoice").hide().text;});
			$("#reg_sale_invoice-no").click(function(){$("#error_reg_sale_invoice").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('graded_min_quantity').result == false){

			$("#error_graded_min_quantity").show().text(check_radio_button_validation('graded_min_quantity').error_message);
			$("#error_graded_min_quantity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#graded_min_quantity-yes").click(function(){$("#error_graded_min_quantity").hide().text;});
			$("#graded_min_quantity-no").click(function(){$("#error_graded_min_quantity").hide().text;});

			value_return = 'false';
		}
		
		if(check_radio_button_validation('grade_100_per_prod').result == false){

			$("#error_grade_100_per_prod").show().text(check_radio_button_validation('grade_100_per_prod').error_message);
			$("#error_grade_100_per_prod").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			$("#grade_100_per_prod-yes").click(function(){$("#error_grade_100_per_prod").hide().text;});
			$("#grade_100_per_prod-no").click(function(){$("#error_grade_100_per_prod").hide().text;});

			value_return = 'false';
		}
		
		
		
		
	//to validate file uploading	
		if(automatic_system_docs_value==""){
			if(check_file_upload_validation(automatic_system_docs).result == false){

				$("#error_automatic_system_docs").show().text(check_file_upload_validation(automatic_system_docs).error_message);
				$("#automatic_system_docs").addClass("is-invalid");
				$("#automatic_system_docs").click(function(){$("#error_automatic_system_docs").hide().text; $("#automatic_system_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		
		if(separate_records_docs_value==""){
			if(check_file_upload_validation(separate_records_docs).result == false){

				$("#error_separate_records_docs").show().text(check_file_upload_validation(separate_records_docs).error_message);
				$("#separate_records_docs").addClass("is-invalid");
				$("#separate_records_docs").click(function(){$("#error_separate_records_docs").hide().text; $("#separate_records_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		
		if(copy_of_orders_docs_value==""){
			if(check_file_upload_validation(copy_of_orders_docs).result == false){

				$("#error_copy_of_orders_docs").show().text(check_file_upload_validation(copy_of_orders_docs).error_message);
				$("#copy_of_orders_docs").addClass("is-invalid");
				$("#copy_of_orders_docs").click(function(){$("#error_copy_of_orders_docs").hide().text; $("#copy_of_orders_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}

		if(copy_of_printing_docs_value==""){
			if(check_file_upload_validation(copy_of_printing_docs).result == false){

				$("#error_copy_of_printing_docs").show().text(check_file_upload_validation(copy_of_printing_docs).error_message);
				$("#copy_of_printing_docs").addClass("is-invalid");
				$("#copy_of_printing_docs").click(function(){$("#error_copy_of_printing_docs").hide().text; $("#copy_of_printing_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		
		if(empty_container_docs_value==""){
			if(check_file_upload_validation(empty_container_docs).result == false){

				$("#error_empty_container_docs").show().text(check_file_upload_validation(empty_container_docs).error_message);
				$("#empty_container_docs").addClass("is-invalid");
				$("#empty_container_docs").click(function(){$("#error_empty_container_docs").hide().text; $("#empty_container_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		
		if(issue_of_empty_container_docs_value==""){
			if(check_file_upload_validation(issue_of_empty_container_docs).result == false){

				$("#error_issue_of_empty_container_docs").show().text(check_file_upload_validation(issue_of_empty_container_docs).error_message);
				$("#issue_of_empty_container_docs").addClass("is-invalid");
				$("#issue_of_empty_container_docs").click(function(){$("#error_issue_of_empty_container_docs").hide().text; $("#issue_of_empty_container_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		
		if(reg_sale_invoice_docs_value==""){
			if(check_file_upload_validation(reg_sale_invoice_docs).result == false){

				$("#error_reg_sale_invoice_docs").show().text(check_file_upload_validation(reg_sale_invoice_docs).error_message);
				$("#reg_sale_invoice_docs").addClass("is-invalid");
				$("#reg_sale_invoice_docs").click(function(){$("#error_reg_sale_invoice_docs").hide().text; $("#reg_sale_invoice_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		
		if(graded_min_qty_docs_value==""){
			if(check_file_upload_validation(graded_min_qty_docs).result == false){

				$("#error_graded_min_qty_docs").show().text(check_file_upload_validation(graded_min_qty_docs).error_message);
				$("#graded_min_qty_docs").addClass("is-invalid");
				$("#graded_min_qty_docs").click(function(){$("#error_graded_min_qty_docs").hide().text; $("#graded_min_qty_docs").removeClass("is-invalid");});

				value_return = 'false';
			}
		}
		

		if(value_return == 'false')
		{
			// alert("Please check some fields are missing or not proper.");
			var msg = "Please check some fields are missing or not proper.";
			$.alert(msg);
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