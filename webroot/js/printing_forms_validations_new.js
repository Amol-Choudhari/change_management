/////////////////////////////////////////////////////////////////////////////// Printing Forms Validations ////////////////////////////////////////////////////////////
	

	// PRINTING FIRM PROFILE
	// DESCRIPTION : function for all fields for Printing firm profile.
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  10-07-2017
	
	function printing_firm_profile(){

		var owner_name = $("#owner_name").val();
		var business_type_docs = $("#business_type_docs").val();
		var business_years = $("#business_years").val();
		var affidavit_proforma_3_attached_docs = $("#affidavit_proforma_3_attached_docs").val();
		var check_save_reply = $("#check_save_reply").val();
		var old_certification_pdf = $("#old_certification_pdf").val();
		var old_application_docs = $("#old_application_docs").val();

		var value_return = 'true';

		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_whitespace_validation_textbox(owner_name).result == false){

			$("#error_owner_name").show().text(check_whitespace_validation_textbox(owner_name).error_message);
			$("#owner_name").addClass("is-invalid");
			$("#owner_name").click(function(){$("#error_owner_name").hide().text; $("#owner_name").removeClass("is-invalid");});
			value_return = 'false';
		}

		if($('#business_type_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_file_upload_validation(business_type_docs).result == false){

				$("#error_business_type_docs").show().text(check_file_upload_validation(business_type_docs).error_message);
				$("#business_type_docs").addClass("is-invalid");
				$("#business_type_docs").click(function(){$("#error_business_type_docs").hide().text; $("#business_type_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_drop_down_validation(business_years).result == false){

			$("#error_business_years").show().text(check_drop_down_validation(business_years).error_message);
			$("#business_years").addClass("is-invalid");
			$("#business_years").click(function(){$("#error_business_years").hide().text; $("#business_years").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_radio_button_validation('affidavit_proforma_3_attached').result == false){

			$("#error_affidavit_proforma_3_attached").show().text(check_radio_button_validation('affidavit_proforma_3_attached').error_message);
			$("#error_affidavit_proforma_3_attached").addClass("is-invalid");
			$("#affidavit_proforma_3_attachedYes").click(function(){$("#error_affidavit_proforma_3_attached").hide().text;$("affidavit_proforma_3_attachedYes").removeClass("is-invalid")});
			$("#affidavit_proforma_3_attachedNo").click(function(){$("#error_affidavit_proforma_3_attached").hide().text;$("affidavit_proforma_3_attachedNo").removeClass("is-invalid")});
			value_return = 'false';
		}


		// Change Condition for validation by pravin 10-07-2017
		if(check_radio_value('affidavit_proforma_3_attached') == "yes"){

			if($('#affidavit_proforma_3_attached_docs_value').text() == ""){

				// Change Condition for validation and error message by pravin 10-07-2017
				if(check_file_upload_validation(affidavit_proforma_3_attached_docs).result == false){

					$("#error_affidavit_proforma_3_attached_docs").show().text(check_file_upload_validation(affidavit_proforma_3_attached_docs).error_message);
					$("#affidavit_proforma_3_attached_docs").addClass("is-invalid");
					$("#affidavit_proforma_3_attached_docs").click(function(){$("#error_affidavit_proforma_3_attached_docs").hide().text; $("#affidavit_proforma_3_attached_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
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

		if(oldapplication == 'yes'){

			if($("#directors_details_table tr td:first").text() == ''){

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

		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;

		} else {

			exit();
		}
	}


	// PRINTING PREMISES PROFILE
	// DESCRIPTION : function for all fields for Printing PREMISES profile.
	// @AUTHOR : PRAVIN BHAKARE , AMOL CHAUDHARI
	// DATE :  10-07-2017
	
	function printing_premises_profile(){
		


		var street_address = $("#street_address").val();
		var postal_code = $("#postal_code").val();
		//var vat_cst_no = $("#vat_cst_no").val();
		var gst_no = $("#gst_no").val();
		var vat_cst_docs = $("#vat_cst_docs").val();
		var layout_plan_docs = $("#layout_plan_docs").val();

		var first_rep_f_name = $("#first_rep_f_name").val();
		var first_rep_m_name = $("#first_rep_m_name").val();
		var first_rep_l_name = $("#first_rep_l_name").val();
		var first_rep_mobile = $("#first_rep_mobile").val();
		var first_rep_signature = $("#first_rep_signature").val();

		var second_rep_f_name = $("#second_rep_f_name").val();
		var second_rep_m_name = $("#second_rep_m_name").val();
		var second_rep_l_name = $("#second_rep_l_name").val();
		var second_rep_mobile = $("#second_rep_mobile").val();
		var second_rep_signature = $("#second_rep_signature").val();

		var check_save_reply = $("#check_save_reply").val();

		var value_return = 'true';

		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_whitespace_validation_textarea(street_address).result == false){

			$("#error_street_address").show().text(check_whitespace_validation_textarea(street_address).error_message);
			$("#street_address").addClass("is-invalid");
			$("#street_address").click(function(){$("#error_street_address").hide().text; $("#street_address").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_postal_code_validation(postal_code).result == false){

			$("#error_postal_code").show().text(check_postal_code_validation(postal_code).error_message);
			$("#postal_code").addClass("is-invalid");
			$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_radio_button_validation('have_vat_cst_no').result == false){

			$("#error_vat_cst").show().text(check_radio_button_validation('have_vat_cst_no').error_message);
			$("#error_vat_cst").addClass("is-invalid");
			$("#vat_cstYes").click(function(){$("#error_vat_cst").hide().text; $("#vat_cstYes").removeClass("is-invalid");});
			$("#vat_cstNo").click(function(){$("#error_vat_cst").hide().text; $("#vat_cstNo").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_value('have_vat_cst_no') == "yes"){

			//if(vat_cst_no.match(/^[^\?\=\.\*\@\$\%\~\`\#\^\&\(\)\-\[\]\{\}\|\\\;\'\:\"\<\>\,\+\/\!\_\s][a-zA-Z0-9\- ]+[^\?\=\.\*\@\$\%\~\`\#\^\&\(\)\-\[\]\{\}\|\\\;\'\:\"\<\>\,\+\/\!\_\s]$/) == null){

			// Change Condition for validation and error message by pravin 10-07-2017
			/*if(check_whitespace_validation_textbox(vat_cst_no).result == false){

				$("#error_vat_cst_no").show().text(check_whitespace_validation_textbox(vat_cst_no).error_message);
				$("#error_vat_cst_no").addClass("is-invalid");
				$("#vat_cst_no").click(function(){$("#error_vat_cst_no").hide().text;});

				value_return = 'false';
			}*/


			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textbox(gst_no).result == false){

				$("#error_gst_no").show().text(check_whitespace_validation_textbox(gst_no).error_message);
				$("#gst_no").addClass("is-invalid");
				$("#gst_no").click(function(){$("#error_gst_no").hide().text; $("#gst_no").removeClass("is-invalid");});
				value_return = 'false';
			}

			if($('#vat_cst_docs_value').text() == ""){

				// Change Condition for validation and error message by pravin 10-07-2017
				if(check_file_upload_validation(vat_cst_docs).result == false){

					$("#error_vat_cst_docs").show().text(check_file_upload_validation(vat_cst_docs).error_message);
					$("#vat_cst_docs").addClass("is-invalid");
					$("#vat_cst_docs").click(function(){$("#error_vat_cst_docs").hide().text; $("#vat_cst_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_radio_button_validation('layout_plan_attached').result == false){

			$("#error_layout_plan_attached").show().text(check_radio_button_validation('layout_plan_attached').error_message);
			$("#error_layout_plan_attached").addClass("is-invalid");
			$("#layout_plan_attachedYes").click(function(){$("#error_vat_cst_docs").hide().text; $("#layout_plan_attachedYes").removeClass("is-invalid");});
			$("#layout_plan_attachedNo").click(function(){$("#error_vat_cst_docs").hide().text; $("#layout_plan_attachedNo").removeClass("is-invalid");});
			value_return = 'false';
		}


		if(check_radio_value('layout_plan_attached') == "yes"){//applied on 10-10-2017 by Amol

			if($('#layout_plan_docs_value').text() == ""){
				// Change Condition for validation and error message by pravin 10-07-2017
				if(check_file_upload_validation(layout_plan_docs).result == false){

					$("#error_layout_plan_docs").show().text(check_file_upload_validation(layout_plan_docs).error_message);
					$("#layout_plan_docs").addClass("is-invalid");
					$("#layout_plan_docs").click(function(){$("#error_layout_plan_docs").hide().text; $("#layout_plan_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}

		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_alpha_character_validation(first_rep_f_name).result == false){

			$("#error_first_rep_f_name").show().text(check_alpha_character_validation(first_rep_f_name).error_message);
			$("#first_rep_f_name").addClass("is-invalid");
			$("#first_rep_f_name").click(function(){$("#error_first_rep_f_name").hide().text; $("#first_rep_f_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		// Remove validation as per suggestion by by pravin 04-08-2017

		if(first_rep_m_name != ""){

			if(check_alpha_character_validation(first_rep_m_name).result == false){

				$("#error_first_rep_m_name").show().text(check_alpha_character_validation(first_rep_m_name).error_message);
				$("#first_rep_m_name").addClass("is-invalid");
				$("#first_rep_m_name").click(function(){$("#error_first_rep_m_name").hide().text; $("#first_rep_m_name").removeClass("is-invalid");});

				value_return = 'false';
			}
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_alpha_character_validation(first_rep_l_name).result == false){

			$("#error_first_rep_l_name").show().text(check_alpha_character_validation(first_rep_l_name).error_message);
			$("#first_rep_l_name").addClass("is-invalid");
			$("#first_rep_l_name").click(function(){$("#error_first_rep_l_name").hide().text; $("#first_rep_l_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_mobile_number_validation(first_rep_mobile).result == false){

			$("#error_first_rep_mobile").show().text(check_mobile_number_validation(first_rep_mobile).error_message);
			$("#first_rep_mobile").addClass("is-invalid");
			$("#first_rep_mobile").click(function(){$("#error_first_rep_mobile").hide().text; $("#first_rep_mobile").removeClass("is-invalid");});
			value_return = 'false';
		}

		if($('#first_rep_signature_value').text() == ""){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_file_upload_validation(first_rep_signature).result == false){

				// Change error message by pravin 07/07/2017
				$("#error_first_rep_signature").show().text(check_file_upload_validation(first_rep_signature).error_message);
				$("#first_rep_signature").addClass("is-invalid");
				$("#first_rep_signature").click(function(){$("#error_first_rep_signature").hide().text; $("#first_rep_signature").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_alpha_character_validation(second_rep_f_name).result == false){

			$("#error_second_rep_f_name").show().text(check_alpha_character_validation(second_rep_f_name).error_message);
			$("#second_rep_f_name").addClass("is-invalid");
			$("#second_rep_f_name").click(function(){$("#error_second_rep_f_name").hide().text; $("#second_rep_f_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		// Remove validation as per suggestion by by pravin 04-08-2017

		if(second_rep_m_name != ""){

			if(check_alpha_character_validation(second_rep_m_name).result == false){

				$("#error_second_rep_m_name").show().text(check_alpha_character_validation(second_rep_m_name).error_message);
				$("#second_rep_m_name").addClass("is-invalid");
				$("#second_rep_m_name").click(function(){$("#error_second_rep_m_name").hide().text; $("#second_rep_m_name").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_alpha_character_validation(second_rep_l_name).result == false){

			$("#error_second_rep_l_name").show().text(check_alpha_character_validation(second_rep_l_name).error_message);
			$("#second_rep_l_name").addClass("is-invalid");
			$("#second_rep_l_name").click(function(){$("#error_second_rep_l_name").hide().text; $("#second_rep_l_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_mobile_number_validation(second_rep_mobile).result == false){

			$("#error_second_rep_mobile").show().text(check_mobile_number_validation(second_rep_mobile).error_message);
			$("#second_rep_mobile").addClass("is-invalid");
			$("#second_rep_mobile").click(function(){$("#error_second_rep_mobile").hide().text; $("#second_rep_mobile").removeClass("is-invalid");});
			value_return = 'false';

		}else{

			if(second_rep_mobile == first_rep_mobile )
			{
				$("#error_second_rep_mobile").show().text("Second Representative mobile no not same as First Representative mobile no");
				$("#second_rep_mobile").addClass("is-invalid");
				$("#second_rep_mobile").click(function(){$("#error_second_rep_mobile").hide().text; $("#second_rep_mobile").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if($('#second_rep_signature_value').text() == ""){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_file_upload_validation(second_rep_signature).result == false){

				// Change error message by pravin 07/07/2017
				$("#error_second_rep_signature").show().text(check_file_upload_validation(second_rep_signature).error_message);
				$("#second_rep_signature").addClass("is-invalid");
				$("#second_rep_signature").click(function(){$("#error_second_rep_signature").hide().text; $("#second_rep_signature").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		// Change Condition for validation  by pravin 07-07-2017
		if(final_submit_status != 'no_final_submit'){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_check_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_check_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}
	
	}


	//Printing Unit Details Validations Method
	function printing_unit_details() {

		var other_machin_docs = $("#other_machin_docs").val();
		var earlier_expiry_date = $("#earlier_expiry_date").val();
		var address_fabrication_unit = $("#address_fabrication_unit").val();
		var fabrication_docs = $("#fabrication_docs").val();
		var proposed_date = $("#proposed_date").val();
		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';

		if($('#machinery_table tr td:first').text() == ""){

			$("#error_machine_table").show().text("Please enter atleast one recod");
			$("#machine_name").addClass("is-invalid");
			$("#machine_name").click(function(){$("#error_machine_table").hide().text; $("#machine_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_radio_button_validation('earlier_approved').result == false){

			$("#error_is_earlier_approved").show().text(check_radio_button_validation('earlier_approved').error_message);
			$("#error_is_earlier_approved").addClass("is-invalid");
			$("#earlier_approvedYes").click(function(){$("#error_is_earlier_approved").hide().text; $("#earlier_approvedYes").removeClass("is-invalid");});
			$("#earlier_approvedNo").click(function(){$("#error_is_earlier_approved").hide().text; $("#earlier_approvedNo").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation by pravin 10-07-2017
		if(check_radio_value('earlier_approved') == "yes"){

			if( earlier_expiry_date == ""){

				$("#error_earlier_expiry_date").show().text("Please enter Earlier Expiry Date");
				$("#earlier_expiry_date").addClass("is-invalid");
				$("#earlier_expiry_date").click(function(){$("#error_earlier_expiry_date").hide().text; $("#earlier_expiry_date").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_radio_button_validation('in_house_machinery').result == false){

			$("#error_in_house_machinery").show().text(check_radio_button_validation('in_house_machinery').error_message);
			$("#error_in_house_machinery").addClass("is-invalid");
			$("#in_house_machineryYes").click(function(){$("#error_in_house_machinery").hide().text; $("#in_house_machineryYes").removeClass("is-invalid");});
			$("#in_house_machineryNo").click(function(){$("#error_in_house_machinery").hide().text; $("#in_house_machineryNo").removeClass("is-invalid");});
			value_return = 'false';
		}

		//if($('input[name="data[proper_fabrication]"]:checked').val() != 'n/a' ){ //commented and changed condition on 18-05-2022 by Amol
		if(check_radio_value('proper_fabrication') != "n/a"){
			
			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_radio_button_validation('proper_fabrication').result == false){

				$("#error_proper_fabrication").show().text(check_radio_button_validation('proper_fabrication').error_message);
				$("#error_proper_fabrication").addClass("is-invalid");
				$("#proper_fabricationYes").click(function(){$("#error_proper_fabrication").hide().text; $("#proper_fabricationYes").removeClass("is-invalid");});
				$("#proper_fabricationNo").click(function(){$("#error_proper_fabrication").hide().text; $("#proper_fabricationNo").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation by pravin 10-07-2017
		if(check_radio_value('proper_fabrication') == "no"){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(address_fabrication_unit).result == false){

				$("#error_address_fabrication_unit").show().text(check_whitespace_validation_textarea(address_fabrication_unit).error_message);
				$("#address_fabrication_unit").addClass("is-invalid");
				$("#address_fabrication_unit").click(function(){$("#error_address_fabrication_unit").hide().text; $("#address_fabrication_unit").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//if($('input[name="data[proper_fabrication]"]:checked').val() != 'n/a' ){ //commented and changed condition on 18-05-2022 by Amol
		if(check_radio_value('proper_fabrication') != "n/a"){

			if($('#fabrication_docs_value').text() == ""){

				// Change Condition for validation and error message by pravin 10-07-2017
				if(check_file_upload_validation(fabrication_docs).result == false){
					
					$("#error_fabrication_docs").show().text(check_file_upload_validation(fabrication_docs).error_message);
					$("#fabrication_docs").addClass("is-invalid");
					$("#fabrication_docs").click(function(){$("#error_fabrication_docs").hide().text; $("#fabrication_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}

		if( proposed_date == ""){

			$("#error_proposed_date").show().text("Please enter Proposed Start Date");
			$("#proposed_date").addClass("is-invalid");
			$("#proposed_date").click(function(){$("#error_proposed_date").hide().text; $("#proposed_date").removeClass("is-invalid");});
			value_return = 'false';
		}

		// Change Condition for validation  by pravin 07-07-2017
		if(final_submit_status != 'no_final_submit'){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_check_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_check_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}
	}


	//Printing Renewal Forms Validations Method
	function printing_renewal_forms_validation(){

		var validity_upto = $("#validity_upto").val();
		//var renew_upto = $("#renew_upto").val();
		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('is_particulars_furnished').result == false){

			$("#error_is_particulars_furnished").show().text(check_radio_button_validation('is_particulars_furnished').error_message);
			$("#error_is_particulars_furnished").addClass("is-invalid");
			$("#is_particulars_furnishedYes").click(function(){$("#error_is_particulars_furnished").hide().text; $("#is_particulars_furnishedYes").removeClass("is-invalid");});
			$("#is_particulars_furnishedNo").click(function(){$("#error_is_particulars_furnished").hide().text; $("#is_particulars_furnishedNo").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation by pravin 11-07-2017
		if(check_radio_value('is_particulars_furnished') == "yes"){

			if($('#packer_table_detail tr td:first').text() == ""){

				$("#error_packer_table_detail").show().text("Please enter atleast one recod");
				$("#packer_table_detail").addClass("is-invalid");
				$("#packer_table_detail").click(function(){$("#error_packer_table_detail").hide().text; $("#packer_table_detail").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if( validity_upto == ""){

			$("#error_validity_upto").show().text("Please enter Validity Date");
			$("#validity_upto").addClass("is-invalid");
			$("#validity_upto").click(function(){$("#error_validity_upto").hide().text; $("#validity_upto").removeClass("is-invalid");});
			value_return = 'false';
		}

		/*if( renew_upto == ""){

			$("#error_renew_upto").show().text("Please enter Renew Upto Date");
			$("#error_renew_upto").addClass("is-invalid");
			$("#renew_upto").click(function(){$("#error_renew_upto").hide().text;});

			value_return = 'false';

		}*/

		// Change Condition for validation  by pravin 07-07-2017
		if(final_renewal_submit_status != 'no_final_submit'){

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_check_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_check_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});

				value_return = 'false';
			}
		}


		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;

		}else{
			exit();
		}

	}


	//Printing Site Inspection Reports Validations Method
	function printing_siteinspection_report_validation(){
		
		var earlier_permitted = $("#earlier_permitted").val();
		var reason_of_withdrawal = $("#reason_of_withdrawal").val();
		var machines_requisite_details = $("#machines_requisite_details").val();
		var machines_requisite_docs = $("#machines_requisite_docs").val();
		var fabrication_facility_docs = $("#fabrication_facility_docs").val();
		var ink_declaration_docs = $("#ink_declaration_docs").val();
		var press_sponsored_docs = $("#press_sponsored_docs").val();
		var check_save_reply = $("#check_save_reply").val();
		// add new variable for validation by pravin 07-07-2017
		var any_other_point = $("#any_other_point").val();
		var recommendations = $("#recommendations").val();
		var value_return = 'true';


		//added on 07-08-2017 by Amol
		if($("#directors_details_table tr td:first").text() == ''){

			$("#error_directors_details").show().text("Sorry. There should be minimum 1 director details added.");
			$("#directors_details_table").addClass("is-invalid");
			$("#directors_details_table").click(function(){$("#error_directors_details").hide().text; $("#directors_details_table").removeClass("is-invalid");});
			value_return = 'false';
		}

		// check Condition for validation  by pravin 07-07-2017
		if(final_status == 'referred_back'){

			if(check_whitespace_validation_textarea(check_save_reply).result == false){

				$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Change Condition for validation by pravin 11-07-2017
		if(check_radio_value('earlier_permitted') == "yes"){

			// Correct variable name  by pravin 07-07-2017
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textarea(reason_of_withdrawal).result == false){

				$("#error_reason_of_withdrawal").show().text(check_whitespace_validation_textarea(reason_of_withdrawal).error_message);
				$("#reason_of_withdrawal").addClass("is-invalid");
				$("#reason_of_withdrawal").click(function(){$("#error_reason_of_withdrawal").hide().text; $("#reason_of_withdrawal").removeClass("is-invalid");});
				value_return = 'false';
			}

		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('machines_requisite').result == false){

			$("#error_machines_requisite").show().text(check_radio_button_validation('machines_requisite').error_message);
			$("#error_machines_requisite").addClass("is-invalid");
			$("#machines_requisiteYes").click(function(){$("#error_machines_requisite").hide().text; $("#machines_requisiteYes").removeClass("is-invalid");});
			$("#machines_requisiteNo").click(function(){$("#error_machines_requisite").hide().text; $("#machines_requisiteNo").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(machines_requisite_details).result == false){

			$("#error_machines_requisite_details").show().text(check_whitespace_validation_textarea(machines_requisite_details).error_message);
			$("#machines_requisite_details").addClass("is-invalid");
			$("#machines_requisite_details").click(function(){$("#error_machines_requisite_details").hide().text; $("#machines_requisite_details").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation by pravin 11-07-2017
		if($('#machines_requisite_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(machines_requisite_docs).result == false){

				$("#error_machines_requisite_docs").show().text(check_file_upload_validation(machines_requisite_docs).error_message);
				$("#machines_requisite_docs").addClass("is-invalid");
				$("#machines_requisite_docs").click(function(){$("#error_machines_requisite_docs").hide().text; $("#machines_requisite_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}



		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('in_house_storage_facility').result == false){

			$("#error_in_house_storage_facility").show().text(check_radio_button_validation('in_house_storage_facility').error_message);
			$("#error_in_house_storage_facility").addClass("is-invalid");
			$("#in_house_storage_facilityYes").click(function(){$("#error_in_house_storage_facility").hide().text; $("#in_house_storage_facilityYes").removeClass("is-invalid");});
			$("#in_house_storage_facilityNo").click(function(){$("#error_in_house_storage_facility").hide().text; $("#in_house_storage_facilityNo").removeClass("is-invalid");});
			value_return = 'false';

		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('account_maintained').result == false){

			$("#error_account_maintained").show().text(check_radio_button_validation('account_maintained').error_message);
			$("#error_account_maintained").addClass("is-invalid");
			$("#account_maintainedYes").click(function(){$("#error_account_maintained").hide().text; $("#account_maintainedYes").removeClass("is-invalid");});
			$("#account_maintainedNo").click(function(){$("#error_account_maintained").hide().text; $("#account_maintainedNo").removeClass("is-invalid");});
			value_return = 'false';

		}


		if($('input[name="fabrication_facility"]:checked').val() != 'n/a' ){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_button_validation('fabrication_facility').result == false){

				$("#error_fabrication_facility").show().text(check_radio_button_validation('fabrication_facility').error_message);
				$("#error_fabrication_facility").addClass("is-invalid");
				$("#fabrication_facilityYes").click(function(){$("#error_fabrication_facility").hide().text; $("#fabrication_facilityYes").removeClass("is-invalid");});
				$("#fabrication_facilityNo").click(function(){$("#error_fabrication_facility").hide().text; $("#fabrication_facilityNo").removeClass("is-invalid");});
				value_return = 'false';
			}

			// Change Condition for validation by pravin 11-07-2017
			if(check_radio_value('fabrication_facility') == "no"){

				if($('#fabrication_facility_docs_value').text() == ""){

					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_file_upload_validation(fabrication_facility_docs).result == false){

						$("#error_fabrication_facility_docs").show().text(check_file_upload_validation(fabrication_facility_docs).error_message);
						$("#fabrication_facility_docs").addClass("is-invalid");
						$("#fabrication_facility_docs").click(function(){$("#error_fabrication_facility_docs").hide().text; $("#fabrication_facility_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('declaration_given').result == false){

			$("#error_declaration_given").show().text(check_radio_button_validation('declaration_given').error_message);
			$("#error_declaration_given").addClass("is-invalid");
			$("#declaration_givenYes").click(function(){$("#error_declaration_given").hide().text; $("#declaration_givenYes").removeClass("is-invalid");});
			$("#declaration_givenNo").click(function(){$("#error_declaration_given").hide().text; $("#declaration_givenNo").removeClass("is-invalid");});
			value_return = 'false';
		}


		if($('#ink_declaration_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(ink_declaration_docs).result == false){

				$("#error_ink_declaration_docs").show().text(check_file_upload_validation(ink_declaration_docs).error_message);
				$("#ink_declaration_docs").addClass("is-invalid");
				$("#ink_declaration_docs").click(function(){$("#error_ink_declaration_docs").hide().text; $("#ink_declaration_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		// changes made by pravin in validation start  (by pravin 23/05/2017)
		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_button_validation('is_press_sponsored').result == false){

			$("#error_is_press_sponsored").show().text(check_radio_button_validation('is_press_sponsored').error_message);
			$("#error_is_press_sponsored").addClass("is-invalid");
			$("#is_press_sponsoredYes").click(function(){$("#error_is_press_sponsored").hide().text; $("#is_press_sponsoredYes").removeClass("is-invalid");});
			$("#is_press_sponsoredNo").click(function(){$("#error_is_press_sponsored").hide().text; $("#is_press_sponsoredNo").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation by pravin 11-07-2017
		if(check_radio_value('is_press_sponsored') == "no"){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_button_validation('is_press_authorised').result == false){

				$("#error_is_press_authorised").show().text(check_radio_button_validation('is_press_authorised').error_message);
				$("#error_is_press_authorised").addClass("is-invalid");
				$("#is_press_authorisedYes").click(function(){$("#error_is_press_authorised").hide().text; $("#is_press_authorisedYes").removeClass("is-invalid");});
				$("#is_press_authorisedNo").click(function(){$("#error_is_press_authorised").hide().text; $("#is_press_authorisedNo").removeClass("is-invalid");});
				value_return = 'false';

			}

		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_radio_value('is_press_sponsored') == "yes" || check_radio_value('is_press_authorised') == "yes"){

			if($('#press_sponsored_docs_value').text() == ""){

				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(press_sponsored_docs).result == false){

					$("#error_press_sponsored_docs").show().text(check_file_upload_validation(press_sponsored_docs).error_message);
					$("#press_sponsored_docs").addClass("is-invalid");
					$("#press_sponsored_docs").click(function(){$("#error_press_sponsored_docs").hide().text; $("#press_sponsored_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(any_other_point).result == false){

			$("#error_any_other_point").show().text(check_whitespace_validation_textarea(any_other_point).error_message);
			$("#any_other_point").addClass("is-invalid");
			$("#any_other_point").click(function(){$("#error_any_other_point").hide().text; $("#any_other_point").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(recommendations).result == false){

			$("#error_recommendations").show().text(check_whitespace_validation_textarea(recommendations).error_message);
			$("#recommendations").addClass("is-invalid");
			$("#recommendations").click(function(){$("#error_recommendations").hide().text; $("#recommendations").removeClass("is-invalid");});
			value_return = 'false';
		}


		// changes end   (by pravin 23/05/2017)

		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;

		}else{
			exit();
		}


	}



	// printing renewal siteinspection validation start by pravin 26-07-2017
	function printing_renewal_siteinspection_validation(){

		var firm_renewal_remark = $("#firm_renewal_remark").val();
		var firm_renewal_docs = $("#firm_renewal_docs").val();
		var value_return = 'true';

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(firm_renewal_remark).result == false){

			$("#error_firm_renewal_remark").show().text(check_whitespace_validation_textarea(firm_renewal_remark).error_message);
			$("#firm_renewal_remark").addClass("is-invalid");
			$("#firm_renewal_remark").click(function(){$("#error_firm_renewal_remark").hide().text; $("#firm_renewal_remark").removeClass("is-invalid");});
			value_return = 'false';

		}


		if($('#firm_renewal_docs_value').text() == ""){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_file_upload_validation(firm_renewal_docs).result == false){

				$("#error_firm_renewal_docs").show().text(check_file_upload_validation(firm_renewal_docs).error_message);
				$("#firm_renewal_docs").addClass("is-invalid");
				$("#firm_renewal_docs").click(function(){$("#error_firm_renewal_docs").hide().text; $("#firm_renewal_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;

		}else{
			exit();
		}

	}


	//Machine Details Validations Method
	function machine_details_validations(){

		var machine_name = $("#machine_name").val();
		var machine_no = $("#machine_no").val();
		var machine_capacity = $("#machine_capacity").val();

		var value_return = 'true';

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textbox(machine_name).result == false){

			$("#error_machine_name").show().text(check_whitespace_validation_textbox(machine_name).error_message);
			$("#machine_name").addClass("is-invalid");
			$("#machine_name").click(function(){$("#error_machine_name").hide().text; $("#machine_name").removeClass("is-invalid");});
			value_return = 'false';
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textbox(machine_no).result == false){

			$("#error_machine_no").show().text(check_whitespace_validation_textbox(machine_no).error_message);
			$("#machine_no").addClass("is-invalid");
			$("#machine_no").click(function(){$("#error_machine_no").hide().text; $("#machine_no").removeClass("is-invalid");});
			value_return = 'false';
		}

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_number_with_decimal_two_validation(machine_capacity).result == false){

			$("#error_machine_capacity").show().text(check_number_with_decimal_two_validation(machine_capacity).error_message);
			$("#machine_capacity").addClass("is-invalid");
			$("#machine_capacity").click(function(){$("#error_machine_capacity").hide().text; $("#machine_capacity").removeClass("is-invalid");});
			value_return = 'false';
		}


		if(value_return == 'false'){
			return false;
		}else{
			return true;
		}

	}



	// validate save comment on applicant save comment box on RO dashboard by pravin 13/05/2017
	function comment_reply_ro_to_applicant_box_validation(){

		var reffered_back_comment = $("#reffered_back_comment").val();
		var value_return = 'true';

		if(check_whitespace_validation_textarea(reffered_back_comment).result == false){

			$("#error_referred_back").show().text(check_whitespace_validation_textarea(reffered_back_comment).error_message);
			$("#reffered_back_comment").addClass("is-invalid");
			$("#reffered_back_comment").click(function(){$("#error_referred_back").hide().text; $("#reffered_back_comment").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}

	}


	// validate save comment on applicant save comment box on RO dashboard by pravin 13/05/2017
	function comment_reply_box_validation(){

		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';

		if(check_whitespace_validation_textarea(check_save_reply).result == false){

			$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
			$("#check_save_reply").addClass("is-invalid");
			$("#check_save_reply").click(function(){$("#error_referred_back").hide().text; $("#check_save_reply").removeClass("is-invalid");});
			value_return = 'false';
		}


		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}


	}


	function packer_table_validation(){

		var quantity_printed = $("#quantity_printed").val();
		var packer_name = $('#packer_name').val();
		var value_return = 'true';

		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textbox(packer_name).result == false){

			$("#error_packer_name").show().text(check_whitespace_validation_textbox(packer_name).error_message);
			$("#packer_name").addClass("is-invalid");
			$("#packer_name").click(function(){$("#error_packer_name").hide().text; $("#packer_name").removeClass("is-invalid");});
			value_return = 'false';
		}


		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_number_with_decimal_two_validation(quantity_printed).result == false){

			$("#error_quantity_printed").show().text(check_number_with_decimal_two_validation(quantity_printed).error_message);
			$("#quantity_printed").addClass("is-invalid");
			$("#quantity_printed").click(function(){$("#error_quantity_printed").hide().text; $("#quantity_printed").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(value_return == 'false'){
			// SHOW WARNING MESSAGE IN NEW ALERT COMPONENT
			// @By Aniket Ganvir dated 18th DEC 2020
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;

		}else{
			return true;
		}

	}


		// File Uploading Validation Function (By pravin 09/05/2017) //
		//File validation common function
		//This function is called on file upload browse button to validate selected file
		function file_browse_onclick(field_id){

			var selected_file = $('#'.concat(field_id)).val();
			var ext_type_array = ["jpg" , "pdf",];
			var get_file_size = $('#'.concat(field_id))[0].files[0].size;
			var get_file_ext = selected_file.split(".");
			var value_return = 'true';
			get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();

			if(get_file_size > 2097152){

				$("#error_size_".concat(field_id)).show().text("Please select file below 2mb");
				$("#error_size_".concat(field_id)).addClass("is-invalid");
				$("#".concat(field_id)).click(function(){$("#error_size_".concat(field_id)).hide().text; $("#").removeClass("is-invalid");});
				$('#'.concat(field_id)).val('')
				value_return = 'false';

			}


			if (ext_type_array.lastIndexOf(get_file_ext) == -1){

				$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
				$("#error_type_".concat(field_id)).addClass("is-invalid");
				$("#".concat(field_id)).click(function(){$("#error_type_".concat(field_id)).hide().text; $("#").removeClass("is-invalid");});
				$('#'.concat(field_id)).val('');

				value_return = 'false';
			}

			if(value_return == 'false'){
				return false;
			}else{
				exit();
			}

		}


		// function for whitespace and blank value validation by pravin 10-07-2017
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






		// function for whitespace and blank value validation by pravin 10-07-2017
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


		// function for Alpha character, whitespace character and blank value validation by pravin 10-07-2017
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

				//if(field_length == update_field_value){

				// change validation rule for whitespace after and before word by pravin 04-08-2017
				if(update_field_value > 0)
				{

					return true;

				}else{

					return {result: false, error_message: error_message1};
				}
			}

		}


		// function for number validation by pravin 10-07-2017
		function check_number_validation(field_value)
		{
			var field_length = field_value.length;
			var field_trim = $.trim(field_value);
			var update_field_value = field_trim.length;
			var error_message1 = 'This field is mandatory and maximum 20 numeric value allowed';
			var error_message2 = 'Please Remove blank space before and after the text';

			if(field_value.match(/^(?=.*[0-9])[0-9]{1,20}$/g) == null)
			{
				//if(field_length == update_field_value)
				//{

				// change validation rule for whitespace after and before word by pravin 04-08-2017
				if(update_field_value > 0)
				{
					return {result: false, error_message: error_message1};
				}
					return {result: false, error_message: error_message1};

			}

			return true;
		}


		// function for email validation by pravin 10-07-2017
		function check_email_validation(field_value)
		{
			var field_length = field_value.length;
			var field_trim = $.trim(field_value);
			var update_field_value = field_trim.length;
			var error_message1 = 'Please enter valid email address like(abc@gmail.com)';
			var error_message2 = 'Please Remove blank space before and after the text';

			if(field_value.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/) == null)
			{
				//if(field_length == update_field_value)
				//{

				// change validation rule for whitespace after and before word by pravin 04-08-2017
				if(update_field_value > 0)
				{
					return {result: false, error_message: error_message1};
				}
					return {result: false, error_message: error_message1};
			}

			return true;
		}


		// function for aadhar validation by pravin 10-07-2017
		function check_aadhar_validation(field_value)
		{
			var field_length = field_value.length;
			var field_trim = $.trim(field_value);
			var update_field_value = field_trim.length;
			var error_message1 = 'This field is mandatory and 12 digit numeric value required like(526548547512)';
			var error_message2 = 'Please Remove blank space before and after the text';

			if(field_value.match(/^(?=.*[0-9])[0-9]{12}$/g) == null)
			{
				//if(field_length == update_field_value)
				//{

				// change validation rule for whitespace after and before word by pravin 04-08-2017
				if(update_field_value > 0)
				{
					return {result: false, error_message: error_message1};
				}
					return {result: false, error_message: error_message1};
			}

			return true;
		}


		// function for number with decimal two validation by pravin 10-07-2017
		function check_number_with_decimal_two_validation(field_value)
		{
			var field_length = field_value.length;
			var field_trim = $.trim(field_value);
			var update_field_value = field_trim.length;
			var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 2 decimal point allowed';
			var error_message2 = 'Please Remove blank space before and after the text';

			if(field_value.match(/^\d{1,25}(\.\d{1,2})?$/) == null)
			{
				//if(field_length == update_field_value)
				//{

				// change validation rule for whitespace after and before word by pravin 04-08-2017
				if(update_field_value > 0)
				{
					return {result: false, error_message: error_message1};
				}
					return {result: false, error_message: error_message1};
			}

			return true;
		}


	// function for number with decimal four validation by pravin 10-07-2017
	function check_number_with_decimal_four_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 4 decimal point allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^\d{1,25}(\.\d{1,4})?$/) == null)
		{
			//if(field_length == update_field_value)
			//{

			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message1};
		}

		return true;
	}

	// function for mobile number validation by pravin 10-07-2017
	function check_mobile_number_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and 10 digit numeric value required like(9638527412)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^(?=.*[0-9])[0-9]{10}$/g) == null)
		{
			//if(field_length == update_field_value)
			//{
				
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


	// function for landline number validation by pravin 10-07-2017
	function check_landline_number_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and Min. 6 and Max. 12 digit numeric value allowed like(071222656880)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^(?=.*[0-9])[0-9]{6,12}$/g) == null)
		{
			//if(field_length == update_field_value)
			//{
				
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
	

	// function for postal code number validation by pravin 10-07-2017
	function check_postal_code_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and 6 digit numeric value allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^(?=.*[0-9])[0-9]{6}$/g) == null)
		{
			//if(field_length == update_field_value)
			//{

			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message1};
		}

		return true;
	}


	// function for blank file upload validation by pravin 10-07-2017
	function check_file_upload_validation(field_value)
	{
		var error_message = 'Please upload the required file';

		if(field_value == "")
		{
			return {result: false, error_message: error_message};
		}

		return true;
	}


	// function for drop_down validation by pravin 10-07-2017
	function check_drop_down_validation(field_value)
	{
		var error_message = 'Please select the required valid option';

		if(field_value == "")
		{
			return {result: false, error_message: error_message};
		}

		return true;
	}


	// function for radio button validation by pravin 10-07-2017
	function check_radio_button_validation(field_value)
	{
		var error_message = 'Please select the option';

		if($('input[name="'+field_value+'"]:checked').val() != "yes" && $('input[name="'+field_value+'"]:checked').val() != "no")
		{

			return {result: false, error_message: error_message};

		}

		return true;
	}


	// function for radio value validation by pravin 10-07-2017
	function check_radio_value(field_value)
	{

		 if($('input[name="'+field_value+'"]:checked').val() == "yes")
		 {
			return 'yes';

		}else if($('input[name="'+field_value+'"]:checked').val() == "no"){
			return 'no';

		//added below else condition on 18-05-2022 by Amol, for 'proper_fabrication' value
		}else if($('input[name="'+field_value+'"]:checked').val() == "n/a"){
			return 'n/a';

		}

	}




	//function to validate directors details table field in siteinspections report
	//added on 07-08-2017 by Amol

	function validate_directors_details(){

		var d_name = $('#d_name').val();
		var d_address  = $('#d_address').val();
		var value_return = 'true';

		if(check_whitespace_validation_textbox(d_name).result == false){

			$("#error_directors_details_name").show().text("Enter Name with max. 50 characters");
			$("#d_name").addClass("is-invalid");
			$("#d_name").click(function(){$("#error_directors_details_name").hide().text; $("#d_name").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(check_whitespace_validation_textarea(d_address).result == false){

			$("#error_directors_details_address").show().text("Enter Address with max. 180 characters");
			$("#d_address").addClass("is-invalid");
			$("#d_address").click(function(){$("#error_directors_details_address").hide().text; $("#d_address").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}
		else{
			return true;
		}

	}




	function renderToast(theme, msgTxt) {

		$('#toast-msg-'+theme).html(msgTxt);
		$('#toast-msg-box-'+theme).fadeIn('slow');
		$('#toast-msg-box-'+theme).delay(3000).fadeOut('slow');

	}