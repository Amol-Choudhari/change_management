/////////////////////////////////////////////////////////////////////////////// Laboratory Forms Validations ////////////////////////////////////////////////////////////
	


	// LABORATORY_FIRM_PROFILE_VALIDATION
	// DESCRIPTION : function for all fields for laboratory firm profile.
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  10-07-2017

	function laboratory_firm_profile_validation(){

		//split path to find controller and action
		var path = window.location.pathname;
		var paths = path.split("/");
		var controller = paths[2];

		var establishment_date = $("#pickdate").val();
		var business_type_docs_value = $("#business_type_docs").val();
		var old_certification_pdf = $("#old_certification_pdf").val();
		var old_application_docs = $("#old_application_docs").val();
		var business_type = $("#business_type").val();
		var check_save_reply = $("#check_save_reply").val();
		//var oldapplication = $("#oldapplication").val();
		var value_return = 'true';

		if(business_type != '1'){

			if($('#business_type_docs_value').text() == ""){

				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(business_type_docs_value).result == false){

					$("#error_business_type_docs").show().text(check_file_upload_validation(business_type_docs_value).error_message);
					$("#business_type_docs").addClass("is-invalid");
					$("#business_type_docs").click(function(){$("#error_business_type_docs").hide().text; $("#business_type_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}


		if(establishment_date == ""){

			$("#error_establishment_date").show().text("Please Select Vaild Establishment Date");
			$("#pickdate").addClass("is-invalid");
			$("#pickdate").click(function(){$("#error_establishment_date").hide().text; $("#pickdate").removeClass("is-invalid");});
			value_return = 'false';
		}


		// check Condition for validation  by pravin 07-07-2017
		if(final_submit_status != 'no_final_submit'){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_check_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_check_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}
		
		if(oldapplication == 'yes'){

			if($("#directors_details_table tr td:first").text() == '')
			{
				$("#error_directors_details").show().text("Sorry. There should be minimum 1 director details added.");
				$("#directors_details_table").addClass("is-invalid");
				$("#directors_details_table").click(function(){$("#error_directors_details").hide().text; $("#directors_details_table").removeClass("is-invalid");});
				value_return = 'false';
			}


			if($('#old_certification_pdf_value').text() == ""){

				if(check_file_upload_validation(old_certification_pdf).result == false){

					$("#error_old_certification_pdf").show().text(check_file_upload_validation(old_certification_pdf).error_message);
					$("#old_certification_pdf").addClass("is-invalid");
					$("#old_certification_pdf").click(function(){$("#error_old_certification_pdf").hide().text; $("#old_certification_pdf").removeClass("is-invalid");});
					value_return = 'false';
				}
			}


			if($('#old_application_docs_value').text() == ""){

				if(check_file_upload_validation(old_application_docs).result == false){

					$("#error_old_application_docs").show().text(check_file_upload_validation(old_application_docs).error_message);
					$("#old_application_docs").addClass("is-invalid");
					$("#old_application_docs").click(function(){$("#error_old_application_docs").hide().text; $("#old_application_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}


		if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}

	}

	
	
	// LABORATORY OTHER DETAILS VALIDATION
	// DESCRIPTION : function for all fields for laboratory other details section.
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE : ----
	
	function laboratory_other_details_validation(){

		var chemists_employed_docs = $("#chemists_employed_docs").val();

		// change varilable name by pravin 11-07-2017
		var chemist_table_record = $('#chemist_table tr td:first').text(); //this syntax is used for to take first td value from table (by pravin 06/05/2017)

		var owner_name = $("#owner_name").val();
		var premises_belongs_to = $('input[name="premises_belongs_to"]:checked').val();
		var premises_belongs_to_docs = $("#premises_belongs_to_docs").val();
		var total_area_covered = $("#total_area_covered").val();
		var total_area_covered_docs = $("#total_area_covered_docs").val();
		var is_accreditated = $('input[name="is_accreditated"]:checked').val();
		var accreditation_no = $("#accreditation_no").val();
		var accreditation_scope = $("#accreditation_scope").val();
		var accreditation_docs = $("#accreditation_docs").val();
		var is_laboretory_equipped = $('input[name="is_laboretory_equipped"]:checked').val();
		var is_laboretory_equipped_docs = $("#is_laboretory_equipped_docs").val();
		var other_information = $("#other_information").val();
		var nabl_accreditated_upto = $("#nabl_accreditated_upto").val();//new field added on 28-09-2021 by Amol
		var apeda_docs = $("#apeda_docs").val();//added on 31-08-2017 by Amol
		var lab_ceo_name = $("#lab_ceo_name").val();//added on 31-08-2017 by Amol
		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';

		// change varilable name by pravin 11-07-2017
		if(chemist_table_record == ""){

			$("#null_record_in_chemist_table").show().text("Please enter atleast one record in Details of chemist table");
			$("#chemist_name").addClass("is-invalid");
			$("#chemist_name").click(function(){$("#null_record_in_chemist_table").hide().text; $("#chemist_name").removeClass("is-invalid");});
			value_return = 'false';
		}

		if($('#chemists_employed_docs_value').text() == ""){
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(chemists_employed_docs).result == false){

				$("#error_chemists_employed_docs").show().text(check_file_upload_validation(chemists_employed_docs).error_message);
				$("#chemists_employed_docs").addClass("is-invalid");
				$("#chemists_employed_docs").click(function(){$("#error_chemists_employed_docs").hide().text; $("#chemists_employed_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('premises_belongs_to').result == false){

			$("#error_premises_belongs_to").show().text(check_radio_button_validation('premises_belongs_to').error_message);
			$("#premises_belongs_to").addClass("is-invalid");
			$("#premises_belongs_to").click(function(){$("#error_premises_belongs_to").hide().text; $("#premises_belongs_to").removeClass("is-invalid");});
			value_return = 'false';
		}


		if(premises_belongs_to == 'no'){
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textbox(owner_name).result == false){

				$("#error_owner_name").show().text(check_whitespace_validation_textbox(owner_name).error_message);
				$("#owner_name").addClass("is-invalid");
				$("#owner_name").click(function(){$("#error_owner_name").hide().text; $("#owner_name").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		if($('#premises_belongs_to_docs_value').text() == ""){
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(premises_belongs_to_docs).result == false){

				$("#error_premises_belongs_to_docs").show().text(check_file_upload_validation(premises_belongs_to_docs).error_message);
				$("#premises_belongs_to_docs").addClass("is-invalid");
				$("#premises_belongs_to_docs").click(function(){$("#error_premises_belongs_to_docs").hide().text; $("#premises_belongs_to_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_number_with_decimal_four_validation(total_area_covered).result == false){

			$("#error_total_area_covered").show().text(check_number_with_decimal_four_validation(total_area_covered).error_message);
			$("#total_area_covered").addClass("is-invalid");
			$("#total_area_covered").click(function(){$("#error_total_area_covered").hide().text; $("#total_area_covered").removeClass("is-invalid");});
			value_return = 'false';
		}


		if($('#total_area_covered_docs_value').text() == ""){
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(total_area_covered_docs).result == false){

				$("#error_total_area_covered_docs").show().text(check_file_upload_validation(total_area_covered_docs).error_message);
				$("#total_area_covered_docs").addClass("is-invalid");
				$("#total_area_covered_docs").click(function(){$("#error_total_area_covered_docs").hide().text; $("#total_area_covered_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('is_accreditated').result == false){

			$("#error_is_accreditated").show().text(check_radio_button_validation('is_accreditated').error_message);
			$("#is_accreditated").addClass("is-invalid");
			$("#is_accreditated").click(function(){$("#error_is_accreditated").hide().text; $("#is_accreditated").removeClass("is-invalid");});
			value_return = 'false';
		}


		if(is_accreditated == 'yes'){
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textbox(accreditation_no).result == false){

				$("#error_accreditation_no").show().text(check_whitespace_validation_textbox(accreditation_no).error_message);
				$("#accreditation_no").addClass("is-invalid");
				$("#accreditation_no").click(function(){$("#error_accreditation_no").hide().text; $("#accreditation_no").removeClass("is-invalid");});
				value_return = 'false';
			}

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textarea(accreditation_scope).result == false){

				$("#error_accreditation_scope").show().text(check_whitespace_validation_textarea(accreditation_scope).error_message);
				$("#accreditation_scope").addClass("is-invalid");
				$("#accreditation_scope").click(function(){$("#error_accreditation_scope").hide().text; $("#accreditation_scope").removeClass("is-invalid");});
				value_return = 'false';
			}

			if($('#accreditation_docs_value').text() == ""){
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(accreditation_docs).result == false){

					$("#error_accreditation_docs").show().text(check_file_upload_validation(accreditation_docs).error_message);
					$("#accreditation_docs").addClass("is-invalid");
					$("#accreditation_docs").click(function(){$("#error_accreditation_docs").hide().text; $("#accreditation_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

			//new field added on 28-09-2021 by Amol
			if(nabl_accreditated_upto == ""){
				
				$("#error_nabl_accreditated_upto").show().text(check_whitespace_validation_textarea(nabl_accreditated_upto).error_message);
				$("#nabl_accreditated_upto").addClass("is-invalid");
				$("#nabl_accreditated_upto").click(function(){$("#error_nabl_accreditated_upto").hide().text; $("#nabl_accreditated_upto").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('is_laboretory_equipped').result == false){

			$("#error_is_laboretory_equipped").show().text(check_radio_button_validation('is_laboretory_equipped').error_message);
			$("#is_laboretory_equipped").addClass("is-invalid");
			$("#is_laboretory_equipped").click(function(){$("#error_is_laboretory_equipped").hide().text; $("#is_laboretory_equipped").removeClass("is-invalid");});
			alert(check_radio_button_validation('is_laboretory_equipped'));
			value_return = 'false';
		}


		if(is_laboretory_equipped == "yes"){

			if($('#is_laboretory_equipped_docs_value').text() == ""){
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(is_laboretory_equipped_docs).result == false){

					$("#error_is_laboretory_equipped_docs").show().text(check_file_upload_validation(is_laboretory_equipped_docs).error_message);
					$("#is_laboretory_equipped_docs").addClass("is-invalid");
					$("#is_laboretory_equipped_docs").click(function(){$("#error_is_laboretory_equipped_docs").hide().text; $("#is_laboretory_equipped_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(other_information).result == false){

			$("#error_other_information").show().text(check_whitespace_validation_textarea(accreditation_scope).error_message);
			$("#other_information").addClass("is-invalid");
			$("#other_information").click(function(){$("#error_other_information").hide().text; $("#other_information").removeClass("is-invalid");});
			value_return = 'false';
		}


		//added on 31-08-2017 by Amol
		if(export_unit_status == 'yes')
		{
			if(is_accreditated == 'yes'){
				//added on 31-08-2017 by Amol
				if($('#apeda_docs_value').text() == ""){

					if(check_file_upload_validation(apeda_docs).result == false){

						$("#error_apeda_docs").show().text(check_file_upload_validation(apeda_docs).error_message);
						$("#apeda_docs").addClass("is-invalid");
						$("#apeda_docs").click(function(){$("#error_apeda_docs").hide().text; $("#apeda_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}

			//added on 31-08-2017 by Amol
			if(check_whitespace_validation_textbox(lab_ceo_name).result == false){

				$("#error_lab_ceo_name").show().text(check_whitespace_validation_textbox(lab_ceo_name).error_message);
				$("#lab_ceo_name").addClass("is-invalid");
				$("#lab_ceo_name").click(function(){$("#error_lab_ceo_name").hide().text; $("#lab_ceo_name").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		// check Condition for validation  by pravin 07-07-2017
		if(final_submit_status != 'no_final_submit'){
			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_check_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_check_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}

	}

	

	// LABORATORY_RENEWAL_VALIDATION
	// DESCRIPTION : ----
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  ----
	
	function laboratory_renewal_validation(){

		var chemist_detail_docs = $("#chemist_detail_docs").val();
		var authorized_packers_docs = $("#authorized_packers_docs").val();
		var lots_graded_docs = $("#lots_graded_docs").val();
		var quantity_graded_docs = $("#quantity_graded_docs").val();
		var check_Sample_docs = $("#check_Sample_docs").val();
		var warning_details = $("#warning_details").val();
		var check_save_reply = $("#check_save_reply").val();

		var value_return = 'true';

		if($('#renewal_chemist_table tr td:first').text() == ""){

			$("#error_renewal_chemist_table").show().text("Please enter atleast one record in Details of chemist table");
			$("#chemist_name").addClass("is-invalid");
			$("#chemist_name").click(function(){$("#error_renewal_chemist_table").hide().text; $("#chemist_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		if($('#chemist_detail_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(chemist_detail_docs).result == false){

				$("#error_chemist_detail_docs").show().text(check_file_upload_validation(chemist_detail_docs).error_message);
				$("#chemist_detail_docs").addClass("is-invalid");
				$("#chemist_detail_docs").click(function(){$("#error_chemist_detail_docs").hide().text; $("#chemist_detail_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if($('#authorized_packers_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(authorized_packers_docs).result == false){

				$("#error_size_authorized_packers_docs").show().text(check_file_upload_validation(authorized_packers_docs).error_message);
				$("#authorized_packers_docs").addClass("is-invalid");
				$("#authorized_packers_docs").click(function(){$("#error_size_authorized_packers_docs").hide().text; $("#authorized_packers_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if($('#lots_graded_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(authorized_packers_docs).result == false){

				$("#error_lots_graded_docs").show().text(check_file_upload_validation(authorized_packers_docs).error_message);
				$("#lots_graded_docs").addClass("is-invalid");
				$("#lots_graded_docs").click(function(){$("#error_lots_graded_docs").hide().text; $("#lots_graded_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if($('#quantity_graded_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(quantity_graded_docs).result == false){

				$("#error_quantity_graded_docs").show().text(check_file_upload_validation(quantity_graded_docs).error_message);
				$("#quantity_graded_docs").addClass("is-invalid");
				$("#quantity_graded_docs").click(function(){$("#error_quantity_graded_docs").hide().text; $("#quantity_graded_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if($('#check_Sample_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(check_Sample_docs).result == false){

				$("#error_check_Sample_docs").show().text(check_file_upload_validation(check_Sample_docs).error_message);
				$("#check_Sample_docs").addClass("is-invalid");
				$("#check_Sample_docs").click(function(){$("#error_check_Sample_docs").hide().text; $("#check_Sample_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('is_warning_issued').result == false){

			$("#error_is_warning_issued").show().text(check_radio_button_validation('is_warning_issued').error_message);
			$("#is_warning_issuedYes").click(function(){$("#error_is_warning_issued").hide().text;});
			$("#is_warning_issuedNo").click(function(){$("#error_is_warning_issued").hide().text;});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_value('is_warning_issued') == "yes"){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textarea(warning_details).result == false){

				$("#error_warning_details").show().text(check_whitespace_validation_textarea(warning_details).error_message);
				$("#warning_details").addClass("is-invalid");
				$("#warning_details").click(function(){$("#error_warning_details").hide().text; $("#warning_details").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		// check Condition for validation  by pravin 07-07-2017
		if(final_renewal_submit_status != 'no_final_submit'){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_check_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_check_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}

	}

	
	// CHEMIST TABLE VALIDATION
	// DESCRIPTION : ----
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  ----
	
	function chemist_table_validation(){

		var chemist_name = $("#chemist_name").val();
		var chemist_qualification = $("#chemist_qualification").val();
		var chemist_experience = $("#chemist_experience").val();
		var chemist_list = $("#chemist_list").val();
		var chemists_details_docs = $("#chemists_details_docs").val();

		var value_return = 'true';

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_alpha_character_validation(chemist_name).result == false){

			$("#error_chemist_name").show().text(check_alpha_character_validation(chemist_name).error_message);
			$("#chemist_name").addClass("is-invalid");
			$("#chemist_name").click(function(){$("#error_chemist_name").hide().text; $("#chemist_name").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_chemist_name").fadeOut();},5000);
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textbox(chemist_qualification).result == false){

			$("#error_qualification").show().text(check_whitespace_validation_textbox(chemist_qualification).error_message);
			$("#chemist_qualification").addClass("is-invalid");
			$("#chemist_qualification").click(function(){$("#error_qualification").hide().text; $("#chemist_qualification").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_qualification").fadeOut();},5000);
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_number_with_decimal_two_validation(chemist_experience).result == false){

			$("#error_experience").show().text(check_number_with_decimal_two_validation(chemist_experience).error_message);
			$("#chemist_experience").addClass("is-invalid");
			$("#chemist_experience").click(function(){$("#error_experience").hide().text; $("#chemist_experience").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_experience").fadeOut();},5000);
			value_return = 'false';
		}


		if(chemist_list == null){

			$("#error_commodity").show().text("Please Select Commodity");
			$("#chemist_list").addClass("is-invalid");
			$("#chemist_list").click(function(){$("#error_commodity").hide().text; $("#chemist_list").removeClass("is-invalid");});
			setTimeout(function(){ $("#error_commodity").fadeOut();},5000);
			value_return = 'false';
		}

		if($('#chemists_details_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(chemists_details_docs).result == false){

				$("#error_chemists_details_docs").show().text(check_file_upload_validation(chemists_details_docs).error_message);
				$("#chemists_details_docs").addClass("is-invalid");
				$("#chemists_details_docs").click(function(){$("#error_chemists_details_docs").hide().text; $("#chemist_list").removeClass("is-invalid");});
				setTimeout(function(){ $("#error_chemists_details_docs").fadeOut();},5000);
				value_return = 'false';
			}
		}


		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}


	}




	// RENEWAL CHEMIST TABLE VALIDATION
	// DESCRIPTION : ----
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  ----
	
	function renewal_chemist_table_validation(){

		var chemist_name = $("#chemist_name").val();
		var chemist_qualification = $("#chemist_qualification").val();
		var chemist_experience = $("#chemist_experience").val();
		var chemist_list = $("#chemist_list").val();
		var chemists_details_docs = $("#chemists_details_docs").val();

		var value_return = 'true';

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_alpha_character_validation(chemist_name).result == false){

			$("#error_chemist_name").show().text(check_alpha_character_validation(chemist_name).error_message);
			$("#error_chemist_name").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_name").click(function(){$("#error_chemist_name").hide().text;});
			setTimeout(function(){ $("#error_chemist_name").fadeOut();},5000);
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textbox(chemist_qualification).result == false){

			$("#error_qualification").show().text(check_whitespace_validation_textbox(chemist_qualification).error_message);
			$("#error_qualification").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_qualification").click(function(){$("#error_qualification").hide().text;});
			setTimeout(function(){ $("#error_qualification").fadeOut();},5000);
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_number_with_decimal_two_validation(chemist_experience).result == false){

			$("#error_experience").show().text(check_number_with_decimal_two_validation(chemist_experience).error_message);
			$("#error_experience").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_experience").click(function(){$("#error_experience").hide().text;});
			setTimeout(function(){ $("#error_experience").fadeOut();},5000);
			value_return = 'false';

		}


		if(chemist_list == null){

			$("#error_commodity").show().text("Please Select Commodity");
			$("#error_commodity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_list").click(function(){$("#error_commodity").hide().text;});
			setTimeout(function(){ $("#error_commodity").fadeOut();},5000);
			value_return = 'false';
		}


		if($('#chemists_details_docs_value').text() == ""){
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(chemists_details_docs).result == false){

				$("#error_chemists_details_docs").show().text(check_file_upload_validation(chemists_details_docs).error_message);
				$("#error_chemists_details_docs").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#chemists_details_docs").click(function(){$("#error_chemists_details_docs").hide().text;});
				setTimeout(function(){ $("#error_chemists_details_docs").fadeOut();},5000);
				value_return = 'false';
			}
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}


	}



	
	// APPLICATION SIDE_RENEWAL CHEMIST TABLE VALIDATION
	// DESCRIPTION : ----
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  ----
	
	function application_side_renewal_chemist_table_validation(){

		var chemist_name = $("#application_side_chemist_name").val();
		var chemist_qualification = $("#application_side_qualification").val();
		var chemist_experience = $("#application_side_experience").val();
		var chemist_list = $("#application_side_commodity").val();

		var value_return = 'true';

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_alpha_character_validation(chemist_name).result == false){

			$("#error_chemist_name").show().text(check_alpha_character_validation(chemist_name).error_message);
			$("#error_chemist_name").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_name").click(function(){$("#error_chemist_name").hide().text;});
			value_return = 'false';
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textbox(chemist_qualification).result == false){

			$("#error_qualification").show().text(check_whitespace_validation_textbox(chemist_qualification).error_message);
			$("#error_qualification").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_qualification").click(function(){$("#error_qualification").hide().text;});
			value_return = 'false';
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_number_with_decimal_two_validation(chemist_experience).result == false){

			$("#error_experience").show().text(check_number_with_decimal_two_validation(chemist_experience).error_message);
			$("#error_experience").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_experience").click(function(){$("#error_experience").hide().text;});
			value_return = 'false';
		}

		if(chemist_list == null){

			$("#error_commodity").show().text("Please Select Commodity");
			$("#error_commodity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#chemist_list").click(function(){$("#error_commodity").hide().text;});
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}


	}
	
	
	
	
	// LABORATORY SITEINSPECTION REPORT VALIDATION
	// DESCRIPTION : ----
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  ----
	
	function laboratory_siteinspection_report_validation(){

		var inspection_date = $("#inspection_date").val();
		var laboratory_site_plan_no = $("#laboratory_site_plan_no").val();
		var laboratory_site_plan_docs = $("#laboratory_site_plan_docs").val();
		var is_lab_fully_equipped_doc = $("#is_lab_fully_equipped_doc").val();
		var chemists_employed_docs = $("#chemists_employed_docs").val();
		var recommendations = $("#recommendations").val();
		var check_save_reply = $("#check_save_reply").val();

		var value_return = 'true';


		//added on 07-08-2017 by Amol
		if($("#directors_details_table tr td:first").text() == '')
		{
			$("#error_directors_details").show().text("Sorry. There should be minimum 1 director details added.");
			$("#error_directors_details").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#directors_details_table").click(function(){$("#error_directors_details").hide().text;});
			value_return = 'false';
		}
		
		if(inspection_date == ""){

			$("#error_inspection_date").show().text("Please Enter Inspection Date");
			$("#error_inspection_date").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#inspection_date").click(function(){$("#error_inspection_date").hide().text;});
			value_return = 'false';
		}

		// check Condition for validation  by pravin 27-07-2017
		if(final_status == 'referred_back'){

			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#error_save_reply").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#check_save_reply").click(function(){$("#error_save_reply").hide().text;});
				value_return = 'false';
			}
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textbox(laboratory_site_plan_no).result == false){

			$("#error_laboratory_site_plan_no").show().text(check_whitespace_validation_textbox(laboratory_site_plan_no).error_message);
			$("#error_laboratory_site_plan_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#laboratory_site_plan_no").click(function(){$("#error_laboratory_site_plan_no").hide().text;});
			value_return = 'false';
		}


		if($('#laboratory_site_plan_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(laboratory_site_plan_docs).result == false){

				$("#error_laboratory_site_plan_docs").show().text(check_file_upload_validation(laboratory_site_plan_docs).error_message);
				$("#error_laboratory_site_plan_docs").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#laboratory_site_plan_docs").click(function(){$("#error_laboratory_site_plan_docs").hide().text;});
				value_return = 'false';
			}
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('lab_surrounding_details').result == false){

			$("#error_lab_surrounding_details").show().text(check_radio_button_validation('lab_surrounding_details').error_message);
			$("#error_lab_surrounding_details").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#lab_surrounding_detailsYes").click(function(){$("#error_lab_surrounding_details").hide().text;});
			$("#lab_surrounding_detailsNo").click(function(){$("#error_lab_surrounding_details").hide().text;});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('lab_environment_details').result == false){

			$("#error_lab_environment_details").show().text(check_radio_button_validation('lab_environment_details').error_message);
			$("#error_lab_environment_details").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#lab_environment_detailsYes").click(function(){$("#error_lab_environment_details").hide().text;});
			$("#lab_environment_detailsNo").click(function(){$("#error_lab_environment_details").hide().text;});
			value_return = 'false';
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('is_lab_fully_equipped').result == false){

			$("#error_is_lab_fully_equipped").show().text(check_radio_button_validation('is_lab_fully_equipped').error_message);
			$("#error_is_lab_fully_equipped").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#is_lab_fully_equippedYes").click(function(){$("#error_is_lab_fully_equipped").hide().text;});
			$("#is_lab_fully_equippedNo").click(function(){$("#error_is_lab_fully_equipped").hide().text;});
			value_return = 'false';
		}

		// Change Condition for validation by pravin 11-07-2017
		if(check_radio_value('is_lab_fully_equipped') == "yes"){

			if($('#is_lab_fully_equipped_doc_value').text() == ""){

				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(is_lab_fully_equipped_doc).result == false){

					$("#error_is_lab_fully_equipped_doc").show().text(check_file_upload_validation(is_lab_fully_equipped_doc).error_message);
					$("#error_is_lab_fully_equipped_doc").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
					$("#is_lab_fully_equipped_doc").click(function(){$("#error_is_lab_fully_equipped_doc").hide().text;});
					value_return = 'false';
				}
			}
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('laboretory_safety_records').result == false){

			$("#error_laboretory_safety_records").show().text(check_radio_button_validation('laboretory_safety_records').error_message);
			$("#error_laboretory_safety_records").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#laboretory_safety_recordsYes").click(function(){$("#error_laboretory_safety_records").hide().text;});
			$("#laboretory_safety_recordsNo").click(function(){$("#error_laboretory_safety_records").hide().text;});
			value_return = 'false';
		}


		if($('#chemists_employed_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(chemists_employed_docs).result == false){

				$("#error_chemists_employed_docs").show().text(check_file_upload_validation(chemists_employed_docs).error_message);
				$("#error_chemists_employed_docs").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#chemists_employed_docs").click(function(){$("#error_chemists_employed_docs").hide().text;});
				value_return = 'false';

			}
		}

		/*
		if($('#chemists_employed_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(chemists_employed_docs).result == false){

				$("#error_chemists_employed_docs").show().text("Please upload the required file");
				$("#error_chemists_employed_docs").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#chemists_employed_docs").click(function(){$("#error_chemists_employed_docs").hide().text;});
				value_return = 'false';
			}
		}
		*/

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(recommendations).result == false){

			$("#error_recommendations").show().text(check_whitespace_validation_textarea(recommendations).error_message);
			$("#error_recommendations").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#recommendations").click(function(){$("#error_recommendations").hide().text;});
			value_return = 'false';
		}


		if(value_return == 'false'){
			alert("Please check some fields are missing or not proper.");
			return false;
		}else{
			exit();
		}

	}



	
	// COMMENT REPLY RO TO APPLICANT BOX VALIDATION
	// DESCRIPTION : validate save comment on applicant save comment box on RO dashboard.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE : 13/05/2017
	
	function comment_reply_ro_to_applicant_box_validation(){

		var reffered_back_comment = $("#reffered_back_comment").val();
		var value_return = 'true';

		if(check_whitespace_validation_textarea(reffered_back_comment).result == false){

			$("#error_referred_back").show().text(check_whitespace_validation_textarea(reffered_back_comment).error_message);
			$("#error_referred_back").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#reffered_back_comment").click(function(){$("#error_referred_back").hide().text;});
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}

	}



	// COMMENT REPLY BOX VALIDATION
	// DESCRIPTION : validate save comment on applicant save comment box on RO dashboard.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE : 13/05/2017
	
	function comment_reply_box_validation(){

		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';

		if(check_whitespace_validation_textarea(check_save_reply).result == false){

			$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
			$("#error_save_reply").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#check_save_reply").click(function(){$("#error_save_reply").hide().text;});
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}
	
	}



	// FILE BROWSE ONCLICK
	// DESCRIPTION :  File Uploading Validation Function AND This function is called on file upload browse button to validate selected file
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  09/05/2017
	
	function file_browse_onclick(field_id){

		var selected_file = $('#'.concat(field_id)).val();
		var ext_type_array = ["jpg" , "pdf"];
		var get_file_size = $('#'.concat(field_id))[0].files[0].size;
		var get_file_ext = selected_file.split(".");
		var value_return = 'true';

		get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();

		if(get_file_size > 2097152){

			$("#error_size_".concat(field_id)).show().text("Please select file below 2mb");
			$("#error_size_".concat(field_id)).css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#".concat(field_id)).click(function(){$("#error_size_".concat(field_id)).hide().text;});
			$('#'.concat(field_id)).val('')
			value_return = 'false';
		}

		if (ext_type_array.lastIndexOf(get_file_ext) == -1){

			$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
			$("#error_type_".concat(field_id)).css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#".concat(field_id)).click(function(){$("#error_type_".concat(field_id)).hide().text;});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}

	}



	// CHECK WHITESPACE VALIDATION TEXTAREA
	// DESCRIPTION :  function for whitespace and blank value validation for textarea.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_whitespace_validation_textarea(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 500 characters allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value != ""){

			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0){
				
				if(field_length <= 500){
					return true;
				}
				
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
			
		}else{
			return {result: false, error_message: error_message1};
		}
	
	}



	// CHECK WHITESPACE VALIDATION TEXTBOX
	// DESCRIPTION :  function for whitespace and blank value validation for textbox.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_whitespace_validation_textbox(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 50 characters allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value != ""){
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0){
				
				if(field_length <= 50){
					return true;
				}
				
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
			
		}else{
			return {result: false, error_message: error_message1};
		}

	}



	// CHECK ALPHA CHARACTER VALIDATION
	// DESCRIPTION :  function for Alpha character, whitespace character and blank value validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_alpha_character_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 50 character alphabets value allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^[A-z ]{1,50}$/) == null)
		{
			return {result: false, error_message: error_message1};
			
		}else{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return true;
			}else{
				return {result: false, error_message: error_message1};
			}
		}

	}



	// CHECK NUMBER VALIDATION
	// DESCRIPTION : function for number validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_number_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 numeric value allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^(?=.*[0-9])[0-9]{1,20}$/g) == null)
		{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
		}

		return true;
	}



	// CHECK EMAIL VALIDATION
	// DESCRIPTION : function for email validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_email_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'Please enter valid email address like(abc@gmail.com)';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/) == null)
		{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
		}

		return true;
	}



	// CHECK AADHAR VALIDATION
	// DESCRIPTION : function for aadhar validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017

	function check_aadhar_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and 12 digit numeric value required like(526548547512)';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^(?=.*[0-9])[0-9]{12}$/g) == null)
		{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
			return {result: false, error_message: error_message1};
		}

		return true;
	}



	// CHECK NUMBER WITH DECIMAL TWO VALIDATION
	// DESCRIPTION : function for number with decimal two validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_number_with_decimal_two_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 2 decimal point allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^\d{1,25}(\.\d{1,2})?$/) == null)
		{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
		}

		return true;
	}



	// CHECK NUMBER WITH DECIMAL FOUR VALIDATION
	// DESCRIPTION : function for number with decimal four validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_number_with_decimal_four_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 4 decimal point allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^\d{1,25}(\.\d{1,4})?$/) == null)
		{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
		}

		return true;
	}



	// CHECK MOBILE NUMBER VALIDATION
	// DESCRIPTION : function for mobile number validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_mobile_number_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and 10 digit numeric value required like(9638527412)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^(?=.*[0-9])[0-9]{10}$/g) == null)
		{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{	
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
		}	
		
		//first valid no. for mob.no, applid on 16-02-2021 by Amol
		var validfirstno = ['7','8','9'];
		//get first character of mobile no.
		var f_m_no = field_value.charAt(0);
		if($.inArray(f_m_no,validfirstno) != -1){
			//valid
		}else{
			return {result: false, error_message: error_message1};
		}
		
		return true;
	}


	// CHECK LANDLINE NUMBER VALIDATION
	// DESCRIPTION : function for landline number validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_landline_number_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and Min. 6 and Max. 12 digit numeric value allowed like(071222656880)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^(?=.*[0-9])[0-9]{6,12}$/g) == null)
		{	
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{	
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
		}				
		
		//validate landline no, pattern, not to contain string '00000', on 18-02-2021 by Amol
		if(field_value.indexOf('00000') > -1){
			return {result: false, error_message: error_message1};
		}
		
		return true;
	}




	// CHECK POSTAL CODE VALIDATION
	// DESCRIPTION : function for postal code number validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_postal_code_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and 6 digit numeric value allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^(?=.*[0-9])[0-9]{6}$/g) == null)
		{
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
			
			return {result: false, error_message: error_message1};
		}

		return true;
	}


	
	// CHECK FILE UPLOAD VALIDATION
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_file_upload_validation(field_value)
	{
		var error_message = 'Please upload the required file';

		if(field_value == "")
		{
			return {result: false, error_message: error_message};
		}

		return true;
	}


	
	// CHECK DROPDOWN VALIDATION
	// DESCRIPTION : function for dropdown validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_drop_down_validation(field_value)
	{
		var error_message = 'Please select the required valid option';

		if(field_value == "")
		{
			return {result: false, error_message: error_message};
		}

		return true;
	}



	// CHECK RADIO BUTTON VALIDATION
	// DESCRIPTION :  function for radio button validation.
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_radio_button_validation(field_value)
	{
		var error_message = 'Please select the option';
	
		if($('input[name="'+field_value+'"]:checked').val() != "yes" && $('input[name="'+field_value+'"]:checked').val() != "no")
		{
			return {result: false, error_message: error_message};
		}
		return true;
	}


	
	// CHECK RADIO VALUE
	// DESCRIPTION :  function for radio value validation
	// @AUTHOR : PRAVIN BHAKARE
	// DATE :  10-07-2017
	
	function check_radio_value(field_value)
	{
		if($('input[name="'+field_value+'"]:checked').val() == "yes"){
			return 'yes';
		}else if($('input[name="'+field_value+'"]:checked').val() == "no"){
			return 'no';
		}
	}



	// VALIDATE DIRECTORS DETAILS
	// DESCRIPTION :  function to validate directors details table field in siteinspections report.
	// @AUTHOR : AMOL CHAOUDHARI
	// DATE : 07-08-2017
	
	function validate_directors_details(){

		var d_name = $('#d_name').val();
		var d_address  = $('#d_address').val();
		var value_return = 'true';

		if(check_whitespace_validation_textbox(d_name).result == false){

			$("#error_directors_details_name").show().text("Enter Name with max. 50 characters");
			setTimeout(function(){ $("#error_directors_details_name").fadeOut();},8000);
			$("#d_name").addClass("is-invalid");
			$("#d_name").click(function(){$("#error_directors_details_name").hide().text; $("#d_name").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(check_whitespace_validation_textarea(d_address).result == false){

			$("#error_directors_details_address").show().text("Enter Address with max. 50 characters");
			setTimeout(function(){ $("#error_directors_details_address").fadeOut();},8000);
			$("#d_address").addClass("is-invalid");
			$("#d_address").click(function(){$("#error_directors_details_address").hide().text; $("#d_address").removeClass("is-invalid");});
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


	// RENDER TOAST
	// DESCRIPTION : DISPLAY FORM RELATED ALERTS/MESSAGES IN NEW TEMPLATE.
	// @AUTHOR : ANIKET GANVIR
	// DATE : 15-12-2020
	
	function renderToast(theme, msgTxt) {
		$('#toast-msg-'+theme).html(msgTxt);
		$('#toast-msg-box-'+theme).fadeIn('slow');
		$('#toast-msg-box-'+theme).delay(3000).fadeOut('slow');
	}
