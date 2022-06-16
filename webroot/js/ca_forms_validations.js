
	//Application forms validations function Starts here***********************************************

	//This function is used for Firm Profile Section
	function firm_profile_section(){
		

		var isOldApplication = $("#isOldApplication").val(); 

		//taking values from form fields
		var reg_licYes=$("#reg_lic-yes").val();
		var reg_licNo=$("#reg_licNo").val();
		var fssai_reg_no=$("#fssai_reg_no").val();
		var fssai_reg_docs=$("#fssai_reg_docs").val();
		var authorised_for_bevo=$("#authorised_for_bevo").val();
		var authorised_bevo_docs=$("#authorised_bevo_docs").val();
		var oil_manu_affidavit_docs=$("#oil_manu_affidavit_docs").val();
		var vopa_certificate_docs=$("#vopa_certificate_docs").val();
		var quantity_per_month=$("#quantity_per_month").val();
		var bank_references=$("#bank_references").val();
		var bank_references_docs=$("#bank_references_docs").val();
		var business_type=$("#business_type").val();
		var business_type_docs=$("#business_type_docs").val();
		var business_years=$("#business_years").val();
		var check_save_reply = $("#check_save_reply").val();
		var old_certification_pdf = $("#old_certification_pdf").val();
		var old_application_docs = $("#old_application_docs").val();
		
		var value_return = 'true';

		//condition to work validations for non BEVO fields
		if(ca_bevo_applicant == 'no'){		

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_button_validation('have_reg_no').result == false){
		
				$("#error_reg_lic").show().text(check_radio_button_validation('have_reg_no').error_message);
				$("#reg_licYes").addClass("is-invalid");
				$("#reg_licYes").click(function(){$("#error_reg_lic").hide().text; $("#reg_licYes").removeClass("is-invalid");});
				value_return = 'false';
			
			} else {
				
				// Change Condition for validation by pravin 11-07-2017 	
				if(check_radio_value('have_reg_no') == "yes"){	
									
					// Change Condition for validation and error message by pravin 11-07-2017		
					if(check_whitespace_validation_textbox(fssai_reg_no).result == false){	
						
						$("#error_fssai_reg_no").show().text(check_whitespace_validation_textbox(fssai_reg_no).error_message);
						$("#fssai_reg_no").addClass("is-invalid");
						$("#fssai_reg_no").click(function(){$("#error_fssai_reg_no").hide().text; $("#fssai_reg_no").removeClass("is-invalid");});
						value_return = 'false';
					}
					
					if($("#fssai_reg_docs_value").text() == ''){

						// Change Condition for validation and error message by pravin 11-07-2017
						if(check_file_upload_validation(fssai_reg_docs).result == false){	
							
							$("#error_fssai_reg_docs").show().text(check_file_upload_validation(fssai_reg_docs).error_message);
							$("#fssai_reg_docs").addClass("is-invalid");
							$("#fssai_reg_docs").click(function(){$("#error_fssai_reg_docs").hide().text; $("#fssai_reg_docs").removeClass("is-invalid");});
							value_return = 'false';
						}						
					}
				}				
			}
			
			if(business_years==""){
				
				$("#error_business_years").show().text("Please enter quantity per month");
				$("#business_years").addClass("is-invalid");
				$("#business_years").click(function(){$("#error_business_years").hide().text; $("#business_years").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		//condition to work validations for BEVO fields
		if(ca_bevo_applicant == 'yes'){
			
			if($("#const_oils_table tr td:first").text() == ''){
				
				$("#error_const_oil").show().text("Sorry. There should be minimum 1 constituent oil mill details added.");
				$("#const_oils_table").addClass("is-invalid");
				$("#const_oils_table").click(function(){$("#error_const_oil").hide().text; $("#const_oils_table").removeClass("is-invalid");});
				value_return = 'false';
			}
			
				
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_button_validation('authorised_for_bevo').result == false){
				
				$("#error_authorised_for_bevo").show().text(check_radio_button_validation('authorised_for_bevo').error_message);
				$("#authorised_for_bevoYes").addClass("is-invalid");
				$("#authorised_for_bevoYes").click(function(){$("#error_authorised_for_bevo").hide().text; $("#authorised_for_bevoYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				// Change Condition for validation by pravin 11-07-2017 	
				if(check_radio_value('authorised_for_bevo') == "yes"){	
					
					if($("#authorised_bevo_docs_value").text() == ''){
							
						// Change Condition for validation and error message by pravin 11-07-2017
						if(check_file_upload_validation(authorised_bevo_docs).result == false){	
							
							$("#error_authorised_bevo_docs").show().text(check_file_upload_validation(authorised_bevo_docs).error_message);
							$("#authorised_bevo_docs").addClass("is-invalid");
							$("#authorised_bevo_docs").click(function(){$("#error_authorised_bevo_docs").hide().text; $("#authorised_bevo_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
			}
			
			// Add new Upload Field for VOP Registration Details by Pravin 22/07/2017
			
			if($("#fssai_reg_docs_value").text() == '')
			{
				if(check_file_upload_validation(fssai_reg_docs).result == false){	
				
					$("#error_fssai_reg_docs").show().text(check_file_upload_validation(fssai_reg_docs).error_message);
					$("#fssai_reg_docs").addClass("is-invalid");
					$("#fssai_reg_docs").click(function(){$("#error_fssai_reg_docs").hide().text; $("#fssai_reg_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
			
			// Add new Upload Field for VOP Registration Details by Pravin 22/07/2017

			if($("#oil_manu_affidavit_docs_value").text() == '')
			{
				if(check_file_upload_validation(oil_manu_affidavit_docs).result == false){	
				
					$("#error_oil_manu_affidavit_docs").show().text(check_file_upload_validation(oil_manu_affidavit_docs).error_message);
					$("#oil_manu_affidavit_docs").addClass("is-invalid");
					$("#oil_manu_affidavit_docs").click(function(){$("#error_oil_manu_affidavit_docs").hide().text; $("#oil_manu_affidavit_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

			// Add new Upload Field for VOP Registration Details by Pravin 22/07/2017
			
			if($("#vopa_certificate_docs_value").text() == '')
			{
				if(check_file_upload_validation(vopa_certificate_docs).result == false){	

					$("#error_vopa_certificate_docs").show().text(check_file_upload_validation(vopa_certificate_docs).error_message);
					$("#vopa_certificate_docs").addClass("is-invalid");
					$("#vopa_certificate_docs").click(function(){$("#error_vopa_certificate_docs").hide().text; $("#vopa_certificate_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
			
				
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_number_with_decimal_two_validation(quantity_per_month).result == false){	
				
				$("#error_quantity_per_month").show().text(check_number_with_decimal_two_validation(quantity_per_month).error_message);
				$("#quantity_per_month").addClass("is-invalid");
				$("#quantity_per_month").click(function(){$("#error_quantity_per_month").hide().text; $("#quantity_per_month").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textbox(bank_references).result == false){
				
				$("#error_bank_references").show().text(check_whitespace_validation_textbox(bank_references).error_message);
				$("#bank_references").addClass("is-invalid");
				$("#bank_references").click(function(){$("#error_bank_references").hide().text; $("#bank_references").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			//added on 05-08-2017 by Amol
			if($("#bank_references_docs_value").text() == '')
			{
				if(check_file_upload_validation(bank_references_docs).result == false){	
								
					$("#error_bank_references_docs").show().text(check_file_upload_validation(bank_references_docs).error_message);
					$("#bank_references_docs").addClass("is-invalid");
					$("#bank_references_docs").click(function(){$("#error_bank_references_docs").hide().text; $("#bank_references_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}
		
		if(business_type==""){
			
			$("#error_business_type").show().text("Please select your business type");
			$("#business_type").addClass("is-invalid");
			$("#business_type").click(function(){$("#error_business_type").hide().text; $("#business_type").removeClass("is-invalid");});
			value_return = 'false';
		}
		
		
		if($("#business_type_docs_value").text() == ''){
			
			// Change Condition for validation and error message by pravin 11-07-2017	
			if(check_file_upload_validation(business_type_docs).result == false){
				
				$("#error_business_type_docs").show().text(check_file_upload_validation(business_type_docs).error_message);
				$("#business_type_docs").addClass("is-invalid");
				$("#business_type_docs").click(function(){$("#error_business_type_docs").hide().text; $("#business_type_docs").removeClass("is-invalid");});
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
		

		//For Old CA Application
		if(isOldApplication == 'yes'){

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
				alert();
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
		}else{
			exit();	
		}

	
	}


	//This function is used for Premises Profile Section
	function premises_profile_section(){
			
		//taking values from form fields
		var bevo_mills_address_docs=$("#bevo_mills_address_docs").val();
		var separate_tanks_used=$("#separate_tanks_used").val();
		var separate_tanks_docs=$("#separate_tanks_docs").val();
		var locking_for_storage_tanks=$("#locking_for_storage_tanks").val();
		var street_address=$("#street_address").val();
		var state=$("#state").val();
		var district=$("#district").val();
		var postal_code=$("#postal_code").val();
		var check_save_reply = $("#check_save_reply").val();
		
		var value_return = 'true';
		
		//condition to work validations for non BEVO fields
		if(ca_bevo_applicant == 'no')
		{	
			
		}
		
		//condition to work validations for BEVO fields
		if(ca_bevo_applicant == 'yes')
		{
			if($("#tank_table tr td:first").text() == '')
			{
				$("#error_tanks").show().text("Sorry. There should be minimum 1 storage tanks details added.");
				$("#tank_table").addClass("is-invalid");
				$("#tank_table").click(function(){$("#error_tanks").hide().text; $("#tank_table").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if($("#bevo_mills_address_docs_value").text() == '')
			{				
				// Change Condition for validation and error message by pravin 11-07-2017	
				if(check_file_upload_validation(bevo_mills_address_docs).result == false){	
					
					$("#error_bevo_mills_address_docs").show().text(check_file_upload_validation(bevo_mills_address_docs).error_message);
					$("#bevo_mills_address_docs").addClass("is-invalid");
					$("#bevo_mills_address_docs").click(function(){$("#error_bevo_mills_address_docs").hide().text; $("#bevo_mills_address_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
			
			
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_button_validation('separate_tanks_used').result == false){
		
				$("#error_separate_tanks_used").show().text(check_radio_button_validation('separate_tanks_used').error_message);
				$("#separate_tanks_usedYes").addClass("is-invalid");
				$("#separate_tanks_usedYes").click(function(){$("#error_separate_tanks_used").hide().text; $("#separate_tanks_usedYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
						
				// Change Condition for validation by pravin 11-07-2017
				if(check_radio_value('separate_tanks_used') == "yes"){		

					if($("#separate_tanks_docs_value").text() == ''){
							
						// Change Condition for validation and error message by pravin 11-07-2017	
						if(check_file_upload_validation(separate_tanks_docs).result == false){		
							
							$("#error_separate_tanks_docs").show().text(check_file_upload_validation(separate_tanks_docs).error_message);
							$("#separate_tanks_docs").addClass("is-invalid");
							$("#separate_tanks_docs").click(function(){$("#error_separate_tanks_docs").hide().text; $("#separate_tanks_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}				
			}
					
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_button_validation('locking_for_storage_tanks').result == false){
				
				$("#error_locking_for_storage_tanks").show().text(check_radio_button_validation('locking_for_storage_tanks').error_message);
				$("#locking_for_storage_tanksYes").addClass("is-invalid");
				$("#locking_for_storage_tanksYes").click(function(){$("#error_locking_for_storage_tanks").hide().text; $("#locking_for_storage_tanksYes").removeClass("is-invalid");});
				value_return = 'false';
			}
			
		}
		
		//common fileds for both	
		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(street_address).result == false){
			
			$("#error_street_address").show().text(check_whitespace_validation_textarea(street_address).error_message);
			$("#street_address").addClass("is-invalid");
			$("#street_address").click(function(){$("#error_street_address").hide().text; $("#street_address").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(state==""){
			
			$("#error_state").show().text("Please Select State.");
			$("#state").addClass("is-invalid");
			$("#state").click(function(){$("#error_state").hide().text; $("#state").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(district==""){
			
			$("#error_district").show().text("Please Select District.");
			$("#district").addClass("is-invalid");
			$("#district").click(function(){$("#error_district").hide().text; $("#district").removeClass("is-invalid");});
			value_return = 'false';
		}
		
		
		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_postal_code_validation(postal_code).result == false){	
			
			$("#error_postal_code").show().text(check_postal_code_validation(postal_code).error_message);
			$("#postal_code").addClass("is-invalid");
			$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
			value_return = 'false';
		
		}/*else{			
			if(!postal_code.match(/^(?=.*[0-9])[0-9]{6}$/g))   
			{ 
				$("#error_postal_code").show().text("Postal code is not valid, only 6 digits no. mandatory");
				$("#postal_code").addClass("is-invalid");
				$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
				value_return = 'false';
			}
		}*/
		
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
		}
		else{
			exit();
		}
		
	}


	//This function is used for Machinery Profile Section
	function machinery_profile_section(){

		//taking values from form fields
		var have_details=$("#have_details").val();
		var detail_docs=$("#detail_docs").val();
		var manufacturing_unit=$("#manufacturing_unit").val();
		var unit_name_address=$("#unit_name_address").val();
		var unit_related_docs=$("#unit_related_docs").val();
		var crushed_refined_seeds=$("#crushed_refined_seeds").val();
		var mill_business_period=$("#mill_business_period").val();
		var quantity_of_oilseeds=$("#quantity_of_oilseeds").val();
		var bevo_machinery_details_docs=$("#bevo_machinery_details_docs").val();
		var fat_spread_facility_docs=$("#fat_spread_facility_docs").val();
		var stored_crushed_separately=$("#stored_crushed_separately").val();
		var stored_crushed_separately_docs=$("#stored_crushed_separately_docs").val();
		var precautions_taken=$("#precautions_taken").val();
		var check_save_reply = $("#check_save_reply").val();
		
		var value_return = 'true';
			
		//condition to work validations for non BEVO fields
		if(ca_bevo_applicant == 'no'){

			// Change Condition for validation and error message by pravin 11-07-2017	
			if(check_radio_button_validation('have_details').result == false){	
				
				$("#error_have_details").show().text(check_radio_button_validation('have_details').error_message);
				$("#have_detailsYes").addClass("is-invalid");
				$("#have_detailsYes").click(function(){$("#error_have_details").hide().text; $("#have_detailsYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
					
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_radio_value('have_details') == "yes"){	
					
					if($("#machinery_table tr td:first").text() == ''){	

						$("#error_machinery").show().text("Sorry. There should be minimum 1 machine details added.");
						$("#machinery_table").addClass("is-invalid");
						$("#machinery_table").click(function(){$("#error_machinery").hide().text; $("#machinery_table").removeClass("is-invalid");});
						value_return = 'false';
					}
					
					if($("#detail_docs_value").text() == ''){
							
						// Change Condition for validation and error message by pravin 10-07-2017
						if(check_file_upload_validation(detail_docs).result == false){	
							
							$("#error_detail_docs").show().text(check_file_upload_validation(detail_docs).error_message);
							$("#detail_docs").addClass("is-invalid");
							$("#detail_docs").click(function(){$("#error_detail_docs").hide().text; $("#detail_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
			}
			
			
			// Change Condition for validation and error message by pravin 10-07-2017	
			if(check_radio_button_validation('owned_by_applicant').result == false){
			
				$("#error_manufacturing_unit").show().text(check_radio_button_validation('owned_by_applicant').error_message);
				$("#manufacturing_unitYes").addClass("is-invalid");
				$("#manufacturing_unitYes").click(function(){$("#error_manufacturing_unit").hide().text; $("#manufacturing_unitYes").removeClass("is-invalid");});
				value_return = 'false';

			}else{
				
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_radio_value('owned_by_applicant') == "no"){	
				
					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_whitespace_validation_textarea(unit_name_address).result == false){
				
						$("#error_unit_name_address").show().text(check_whitespace_validation_textarea(unit_name_address).error_message);
						$("#unit_name_address").addClass("is-invalid");
						$("#unit_name_address").click(function(){$("#error_unit_name_address").hide().text; $("#unit_name_address").removeClass("is-invalid");});
						value_return = 'false';
					}
					
					if($("#unit_related_docs_value").text() == ''){
							
						// Change Condition for validation and error message by pravin 11-07-2017
						if(check_file_upload_validation(unit_related_docs).result == false){	
							
							$("#error_unit_related_docs").show().text(check_file_upload_validation(unit_related_docs).error_message);
							$("#unit_related_docs").addClass("is-invalid");
							$("#unit_related_docs").click(function(){$("#error_unit_related_docs").hide().text; $("#unit_related_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}				
			}
		}


		//condition to work validations for BEVO fields
		if(ca_bevo_applicant == 'yes'){

			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textbox(crushed_refined_seeds).result == false){
						
				$("#error_crushed_refined_seeds").show().text(check_whitespace_validation_textbox(crushed_refined_seeds).error_message);
				$("#crushed_refined_seeds").addClass("is-invalid");
				$("#crushed_refined_seeds").click(function(){$("#error_crushed_refined_seeds").hide().text; $("#crushed_refined_seeds").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_number_with_decimal_two_validation(mill_business_period).result == false){
					
				$("#error_mill_business_period").show().text(check_number_with_decimal_two_validation(mill_business_period).error_message);
				$("#mill_business_period").addClass("is-invalid");
				$("#mill_business_period").click(function(){$("#error_mill_business_period").hide().text; $("#mill_business_period").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_number_with_decimal_two_validation(quantity_of_oilseeds).result == false){
				
				$("#error_quantity_of_oilseeds").show().text(check_number_with_decimal_two_validation(quantity_of_oilseeds).error_message);
				$("#quantity_of_oilseeds").addClass("is-invalid");
				$("#quantity_of_oilseeds").click(function(){$("#error_quantity_of_oilseeds").hide().text; $("#quantity_of_oilseeds").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if(applicant_type == 'bevo'){

				if($("#bevo_machinery_details_docs_value").text() == ''){
						
					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_file_upload_validation(bevo_machinery_details_docs).result == false){	
						
						$("#error_bevo_machinery_details_docs").show().text(check_file_upload_validation(bevo_machinery_details_docs).error_message);
						$("#bevo_machinery_details_docs").addClass("is-invalid");
						$("#bevo_machinery_details_docs").click(function(){$("#error_bevo_machinery_details_docs").hide().text; $("#bevo_machinery_details_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
			
			if(applicant_type == 'fat_spread'){

				if($("#fat_spread_facility_docs_value").text() == ''){

					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_file_upload_validation(fat_spread_facility_docs).result == false){
						
						$("#error_fat_spread_facility_docs").show().text(check_file_upload_validation(fat_spread_facility_docs).error_message);
						$("#fat_spread_facility_docs").addClass("is-invalid");
						$("#fat_spread_facility_docs").click(function(){$("#error_fat_spread_facility_docs").hide().text; $("#fat_spread_facility_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
			
				
			// Change Condition for validation and error message by pravin 11-07-2017	
			if(check_radio_button_validation('stored_crushed_separately').result == false){	
				
				$("#error_stored_crushed_separately").show().text(check_radio_button_validation('stored_crushed_separately').error_message);
				$("#stored_crushed_separatelyYes").addClass("is-invalid");
				$("#stored_crushed_separatelyYes").click(function(){$("#error_stored_crushed_separately").hide().text; $("#stored_crushed_separatelyYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{	

				if(check_radio_value('stored_crushed_separately') == "yes"){
			
					if($("#stored_crushed_separately_docs_value").text() == ''){
						
						// Change Condition for validation and error message by pravin 11-07-2017
						if(check_file_upload_validation(stored_crushed_separately_docs).result == false){	
							
							$("#error_stored_crushed_separately_docs").show().text(check_file_upload_validation(stored_crushed_separately_docs).error_message);
							$("#stored_crushed_separately_docs").addClass("is-invalid");
							$("#stored_crushed_separately_docs").click(function(){$("#error_stored_crushed_separately_docs").hide().text; $("#old_application_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
			}
			
				
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textarea(precautions_taken).result == false){
				
				$("#error_precautions_taken").show().text(check_whitespace_validation_textarea(precautions_taken).error_message);
				$("#precautions_taken").addClass("is-invalid");
				$("#precautions_taken").click(function(){$("#error_precautions_taken").hide().text; $("#precautions_taken").removeClass("is-invalid");});
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
		}
		else{
			exit();
			
		}

	}


	//This function is used for Packing Details Section
	function packing_details_section(){

		//taking values from form fields
		var proposed_to_repack=$("#proposed_to_repack").val();
		var proposed_place=$("#proposed_place").val();
		var repacking_docs=$("#repacking_docs").val();
		var have_grading_other_info=$("#have_grading_other_info").val();
		var grading_other_info=$("#grading_other_info").val();
		var check_save_reply = $("#check_save_reply").val();
		
		var value_return = 'true';
		
		// Change Condition for validation and error message by pravin 10-07-2017	
		if(check_radio_button_validation('proposed_to_repack').result == false){
			
			$("#error_proposed_to_repack").show().text(check_radio_button_validation('proposed_to_repack').error_message);
			$("#proposed_to_repackYes").addClass("is-invalid");
			$("#proposed_to_repackYes").click(function(){$("#error_proposed_to_repack").hide().text; $("#proposed_to_repackYes").removeClass("is-invalid");});
			value_return = 'false';
		
		}else{
			
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_value('proposed_to_repack') == "yes"){
			
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_whitespace_validation_textbox(proposed_place).result == false){
				
					$("#error_proposed_place").show().text(check_whitespace_validation_textbox(proposed_place).error_message);
					$("#proposed_place").addClass("is-invalid");
					$("#proposed_place").click(function(){$("#error_proposed_place").hide().text; $("#proposed_place").removeClass("is-invalid");});
					value_return = 'false';
				}
				
				if($("#repacking_docs_value").text() == ''){

					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_file_upload_validation(repacking_docs).result == false){	
						
						$("#error_repacking_docs").show().text(check_file_upload_validation(repacking_docs).error_message);
						$("#repacking_docs").addClass("is-invalid");
						$("#repacking_docs").click(function(){$("#error_repacking_docs").hide().text; $("#repacking_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
		}
			
		
		// Change Condition for validation and error message by pravin 11-07-2017	
		if(check_radio_button_validation('have_grading_other_info').result == false){
			
			$("#error_have_grading_other_info").show().text(check_radio_button_validation('have_grading_other_info').error_message);
			$("#have_grading_other_infoYes").addClass("is-invalid");
			$("#have_grading_other_infoYes").click(function(){$("#error_have_grading_other_info").hide().text; $("#have_grading_other_infoYes").removeClass("is-invalid");});
			value_return = 'false';
		
		}else{
		
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_value('have_grading_other_info') == "yes"){
			
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_whitespace_validation_textarea(grading_other_info).result == false){
					
					$("#error_grading_other_info").show().text(check_whitespace_validation_textarea(grading_other_info).error_message);
					$("#grading_other_info").addClass("is-invalid");
					$("#grading_other_info").click(function(){$("#error_grading_other_info").hide().text; $("#grading_other_info").removeClass("is-invalid");});
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
		
		if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}
		else{
			exit();
			
		}
		
	}


	//This function is used for laboratory Details Section
	function laboratory_details_section(){

		//taking values from form fields
		var laboratory_name=$("#laboratory_name").val();
		var laboratory_type=$("#laboratory_type").val();
		var consent_letter_docs=$("#consent_letter_docs").val();
		var street_address=$("#street_address").val();
		var state=$("#state").val();
		var district=$("#district").val();
		var postal_code=$("#postal_code").val();
		var lab_email_id=$("#lab_email_id").val();
		var lab_mobile_no=$("#lab_mobile_no").val();
		var lab_fax_no=$("#lab_fax_no").val();
		var is_lab_equipped=$("#is_lab_equipped").val();
		var lab_equipped_docs=$("#lab_equipped_docs").val();
		var chemist_detail_docs=$("#chemist_detail_docs").val();
		var check_save_reply = $("#check_save_reply").val();
		
		var value_return = 'true';

		//condition to work validations for non BEVO fields
		if(ca_bevo_applicant == 'no')
		{
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textbox(laboratory_name).result == false){	
				
				$("#error_laboratory_name").show().text(check_whitespace_validation_textbox(laboratory_name).error_message);
				$("#laboratory_name").addClass("is-invalid");
				$("#laboratory_name").click(function(){$("#error_laboratory_name").hide().text; $("#laboratory_name").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if(laboratory_type==""){
				
				$("#error_laboratory_type").show().text("Please select laboratory type");
				$("#laboratory_type").addClass("is-invalid");
				$("#laboratory_type").click(function(){$("#error_laboratory_type").hide().text; $("#laboratory_type").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if(laboratory_type != 1){

				if($("#consent_letter_docs_value").text() == ''){		
						
					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_file_upload_validation(consent_letter_docs).result == false){	
						
						$("#error_consent_letter_docs").show().text(check_file_upload_validation(consent_letter_docs).error_message);
						$("#consent_letter_docs").addClass("is-invalid");
						$("#consent_letter_docs").click(function(){$("#error_consent_letter_docs").hide().text; $("#consent_letter_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}

			}else{
				
				// Add new field validation by pravin 22-07-2017
				if($("#chemist_detail_docs_value").text() == ''){		
					
					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_file_upload_validation(chemist_detail_docs).result == false){	
						
						$("#error_chemist_detail_docs").show().text(check_file_upload_validation(chemist_detail_docs).error_message);
						$("#chemist_detail_docs").addClass("is-invalid");
						$("#chemist_detail_docs").click(function(){$("#error_chemist_detail_docs").hide().text; $("#chemist_detail_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
				
				// Add new field validation by pravin 22-07-2017
				if($("#lab_equipped_docs_value").text() == ''){		
						
					// Change Condition for validation and error message by pravin 11-07-2017
					if(check_file_upload_validation(lab_equipped_docs).result == false){	
						
						$("#error_lab_equipped_docs").show().text(check_file_upload_validation(lab_equipped_docs).error_message);
						$("#lab_equipped_docs").addClass("is-invalid");
						$("#lab_equipped_docs").click(function(){$("#error_lab_equipped_docs").hide().text; $("#lab_equipped_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
				
			}
			
		
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_whitespace_validation_textbox(street_address).result == false){
				
				$("#error_street_address").show().text(check_whitespace_validation_textbox(street_address).error_message);		
				$("#street_address").addClass("is-invalid");
				$("#street_address").click(function(){$("#error_street_address").hide().text; $("#street_address").removeClass("is-invalid");});
				value_return = 'false';
			}
		
			if(state==""){
				
				$("#error_state").show().text("Please Select State.");
				$("#state").addClass("is-invalid");
				$("#state").click(function(){$("#error_state").hide().text; $("#state").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(district==""){
				
				$("#error_district").show().text("Please Select District.");
				$("#district").addClass("is-invalid");
				$("#district").click(function(){$("#error_district").hide().text; $("#district").removeClass("is-invalid");});
				value_return = 'false';
			}
			
				
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_postal_code_validation(postal_code).result == false){	
				
				$("#error_postal_code").show().text(check_postal_code_validation(postal_code).error_message);
				$("#postal_code").addClass("is-invalid");
				$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
				value_return = 'false';
			
			}/*else{			
				if(!postal_code.match(/^(?=.*[0-9])[0-9]{6}$/g)){	
					$("#error_postal_code").show().text("Postal code is not valid, only 6 digits no. mandatory");
					$("#postal_code").addClass("is-invalid");
					$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
					value_return = 'false';
				}
			}*/
				
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_email_validation(lab_email_id).result == false){	
			
				$("#error_lab_email_id").show().text(check_email_validation(lab_email_id).error_message);
				$("#lab_email_id").addClass("is-invalid");
				$("#lab_email_id").click(function(){$("#error_lab_email_id").hide().text; $("#lab_email_id").removeClass("is-invalid");});
				value_return = 'false';
			
			}/*else{
				
				if(!lab_email_id.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/))   
				{ 
					$("#error_lab_email_id").show().text("Please Enter id is not valid.");
					$("#lab_email_id").addClass("is-invalid");
					$("#lab_email_id").click(function(){$("#error_lab_email_id").hide().text; $("#lab_email_id").removeClass("is-invalid");});
					value_return = 'false';
				}
			
			}*/
			
			//if(lab_mobile_no==""){
				
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_mobile_number_validation(lab_mobile_no).result == false){	
				
				$("#error_lab_mobile_no").show().text(check_mobile_number_validation(lab_mobile_no).error_message);
				$("#lab_mobile_no").addClass("is-invalid");
				$("#lab_mobile_no").click(function(){$("#error_lab_mobile_no").hide().text; $("#lab_mobile_no").removeClass("is-invalid");});	
				value_return = 'false';
			
			}/*else{
				
				if(!lab_mobile_no.match(/^(?=.*[0-9])[0-9]{10}$/g))   
				{	
					$("#error_lab_mobile_no").show().text("Mobile no. is not valid, only 10 digits no. allowed");
					$("#lab_mobile_no").addClass("is-invalid");
					$("#lab_mobile_no").click(function(){$("#error_lab_mobile_no").hide().text; $("#lab_mobile_no").removeClass("is-invalid");});
					value_return = 'false';
				}
			}*/
				
			
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_landline_number_validation(lab_fax_no).result == false){	
				
				$("#error_lab_fax_no").show().text(check_landline_number_validation(lab_fax_no).error_message);
				$("#lab_fax_no").addClass("is-invalid");
				$("#lab_fax_no").click(function(){$("#error_lab_fax_no").hide().text; $("#lab_fax_no").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			/*if(lab_fax_no !=""){
				if(!lab_fax_no.match(/^(?=.*[0-9])[0-9]{5,15}$/g))   
				{
					$("#error_lab_fax_no").show().text("Given Fax no. is not valid.");
					$("#lab_fax_no").addClass("is-invalid");
					$("#lab_fax_no").click(function(){$("#error_lab_fax_no").hide().text; $("#lab_fax_no").removeClass("is-invalid");});
					value_return = 'false';
				}
			}*/
		
		}
		
		//condition to work validations for BEVO fields
		if(ca_bevo_applicant == 'yes'){
			
			// Change Condition for validation and error message by pravin 11-07-2017	
			if(check_radio_button_validation('is_lab_equipped').result == false){
			
				$("#error_is_lab_equipped").show().text(check_radio_button_validation('is_lab_equipped').error_message);
				$("#is_lab_equippedYes").addClass("is-invalid");
				$("#is_lab_equippedYes").click(function(){$("#error_is_lab_equipped").hide().text; $("#is_lab_equippedYes").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if($("#lab_equipped_docs_value").text() == '')
			{		
				
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(lab_equipped_docs).result == false){	
					
					$("#error_lab_equipped_docs").show().text(check_file_upload_validation(lab_equipped_docs).error_message);
					$("#lab_equipped_docs").addClass("is-invalid");
					$("#lab_equipped_docs").click(function(){$("#error_lab_equipped_docs").hide().text; $("#lab_equipped_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
			
			// Add new field validation by pravin 22-07-2017
			if($("#chemist_detail_docs_value").text() == '')
			{		
					
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(chemist_detail_docs).result == false){	
					
					$("#error_chemist_detail_docs").show().text(check_file_upload_validation(chemist_detail_docs).error_message);
					$("#chemist_detail_docs").addClass("is-invalid");
					$("#chemist_detail_docs").click(function(){$("#error_chemist_detail_docs").hide().text; $("#chemist_detail_docs").removeClass("is-invalid");});
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

		if(value_return == 'false')
		{
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}
		else{
			exit();			
		}

	}


	//This function is used for laboratory Details Section
	function tbl_details_section(){

		//taking values from form fields
		var tbl_belongs_to_applicant=$("#tbl_belongs_to_applicant").val();
		var tbl_belongs_docs=$("#tbl_belongs_docs").val();
		var tbl_proposed_firm=$("#tbl_proposed_firm").val();
		var tbl_consent_letter_docs=$("#tbl_consent_letter_docs").val();
		var check_save_reply = $("#check_save_reply").val();
		
		var value_return = 'true';
		
		if($("#tbls_table tr td:first").text() == '')
		{	
			$("#error_tbls").show().text("Sorry. There should be minimum 1 TBL details added.");
			$("#error_tbls").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			setTimeout(function(){ $("#error_tbls").fadeOut();},8000);
			value_return = 'false';
		}	

			
		// Change Condition for validation and error message by pravin 10-07-2017	
		if(check_radio_button_validation('tbl_belongs_to_applicant').result == false){
				
			$("#error_tbl_belongs_to_applicant").show().text(check_radio_button_validation('tbl_belongs_to_applicant').error_message);
			$("#tbl_belongs_to_applicantYes").addClass("is-invalid");
			$("#tbl_belongs_to_applicantYes").click(function(){$("#error_tbl_belongs_to_applicant").hide().text; $("#tbl_belongs_to_applicantYes").removeClass("is-invalid");});
			value_return = 'false';
		
		}else{
			
			// Change Condition for validation and error message by pravin 11-07-2017
			if(check_radio_value('tbl_belongs_to_applicant') == "yes"){
			
				if($("#tbl_belongs_docs_value").text() == ''){	

					// Change Condition for validation and error message by pravin 10-07-2017
					if(check_file_upload_validation(tbl_belongs_docs).result == false){	
						
						$("#error_tbl_belongs_docs").show().text(check_file_upload_validation(tbl_belongs_docs).error_message);
						$("#tbl_belongs_docs").addClass("is-invalid");
						$("#tbl_belongs_docs").click(function(){$("#error_tbl_belongs_docs").hide().text; $("#tbl_belongs_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}					
			}
			
				
			if(check_radio_value('tbl_belongs_to_applicant') == "no"){
				
				// Change Condition for validation and error message by pravin 10-07-2017
				if(check_whitespace_validation_textbox(tbl_proposed_firm).result == false){
				
					$("#error_tbl_proposed_firm").show().text(check_whitespace_validation_textbox(tbl_proposed_firm).error_message);
					$("#tbl_proposed_firm").addClass("is-invalid");
					$("#tbl_proposed_firm").click(function(){$("#error_tbl_proposed_firm").hide().text; $("#tbl_proposed_firm").removeClass("is-invalid");});
					value_return = 'false';
				}
				

				if($("#tbl_consent_letter_docs_value").text() == ''){
						
					// Change Condition for validation and error message by pravin 10-07-2017
					if(check_file_upload_validation(tbl_consent_letter_docs).result == false){		
						
						$("#error_tbl_consent_letter_docs").show().text(check_file_upload_validation(tbl_consent_letter_docs).error_message);
						$("#tbl_consent_letter_docs").addClass("is-invalid");
						$("#tbl_consent_letter_docs").click(function(){$("#error_tbl_consent_letter_docs").hide().text; $("#tbl_consent_letter_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
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
		
		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();			
		}

	}

//Application forms validations function ends here***********************************************












//Siteinspection report forms validations function starts here***********************************************

	//created on 07-08-2017 by Amol
	//This function is used for Premises profile Section
	function premises_profile_report(){
		
		var path = window.location.pathname;
		var paths = path.split("/");
		var controller = paths[2];
		var action = paths[3];
		
		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';
		
		if(action == 'view_premises_profile')
		{
			if(check_whitespace_validation_textarea(check_save_reply).result == false){
				
				$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';		
			}
		}

		if($("#directors_details_table tr td:first").text() == '')
		{
			$("#error_directors_details").show().text("Sorry. There should be minimum 1 director details added.");
			$("#directors_details_table").addClass("is-invalid");
			$("#directors_details_table").click(function(){$("#error_directors_details").hide().text; $("#directors_details_table").removeClass("is-invalid");});
			value_return = 'false';
		}
		
		
		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();			
		}

	}



	//This function is used for Premises Details Section
	function premises_details_report(){

		//taking values from form fields
		var storage_site_plan_no=$("#storage_site_plan_no").val();
		var storage_details_docs=$("#storage_details_docs").val();
		var conditions_fulfilled=$("#conditions_fulfilled").val();
		var condition_details=$("#condition_details").val();
		var condition_details_docs=$("#condition_details_docs").val();
		var constituent_oil_mill_docs=$("#constituent_oil_mill_docs").val();
		var separate_pipe_lines=$("#separate_pipe_lines").val();
		var room_site_plan_no=$("#room_site_plan_no").val();
		var room_details_docs=$("#room_details_docs").val();
		var lighted_ventilated=$("#lighted_ventilated").val();
		var ventilation_details=$("#ventilation_details").val();
		var ventilation_details_docs=$("#ventilation_details_docs").val();
		var locking_adequate=$("#locking_adequate").val();
		var locking_details=$("#locking_details").val();
		var locking_details_docs=$("#locking_details_docs").val();
		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';

		
		// check Condition for validation  by Amol 28-07-2017
		if(final_status == 'referred_back'){
		
			if(check_whitespace_validation_textarea(check_save_reply).result == false){ 
		
				$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}
		
			
		//condition to work validations for non BEVO fields
		if(ca_bevo_applicant == 'no'){

			if($("#commodity_storage_tank_table tr td:first").text() == ''){
				
				$("#error_commodity_storage_tank").show().text("Sorry. There should be minimum 1 tank details added.");
				$("#machinery_table").addClass("is-invalid");
				$("#machinery_table").click(function(){$("#error_commodity_storage_tank").hide().text; $("#machinery_table").removeClass("is-invalid");});
				value_return = 'false';
			}
				
			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textbox(storage_site_plan_no).result == false){
				
				$("#error_storage_site_plan_no").show().text(check_whitespace_validation_textbox(storage_site_plan_no).error_message);
				$("#storage_site_plan_no").addClass("is-invalid");
				$("#storage_site_plan_no").click(function(){$("#error_storage_site_plan_no").hide().text; $("#storage_site_plan_no").removeClass("is-invalid");});
				
				value_return = 'false';
			}
				
			if($("#storage_details_docs_value").text() == ''){
		
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_file_upload_validation(storage_details_docs).result == false){	
							
					$("#error_storage_details_docs").show().text(check_file_upload_validation(storage_details_docs).error_message);
					$("#storage_details_docs").addClass("is-invalid");
					$("#storage_details_docs").click(function(){$("#error_storage_details_docs").hide().text; $("#storage_details_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
				
								
			// Change Condition for validation and error message by pravin 11-07-2017	
			if(check_radio_button_validation('conditions_fulfilled').result == false){
			
				$("#error_conditions_fulfilled").show().text(check_radio_button_validation('conditions_fulfilled').error_message);
				$("#tbl_belongs_to_applicantYes").addClass("is-invalid");
				$("#tbl_belongs_to_applicantYes").click(function(){$("#error_conditions_fulfilled").hide().text; $("#tbl_belongs_to_applicantYes").removeClass("is-invalid");});
				value_return = 'false';

			}else{
				
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_radio_value('conditions_fulfilled') == "yes"){
			
					if($("#condition_details_docs_value").text() == '')
					{
						// Change Condition for validation and error message by pravin 10-07-2017
						if(check_file_upload_validation(condition_details_docs).result == false){	
							
							$("#error_condition_details_docs").show().text(check_file_upload_validation(condition_details_docs).error_message);
							$("#condition_details_docs").addClass("is-invalid");
							$("#condition_details_docs").click(function(){$("#error_condition_details_docs").hide().text; $("#condition_details_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}					
				}


				if(check_radio_value('conditions_fulfilled') == "no"){

					// Change Condition for validation and error message by pravin 10-07-2017
					if(check_whitespace_validation_textarea(condition_details).result == false){	
							
						$("#error_condition_details").show().text(check_whitespace_validation_textarea(condition_details).error_message);
						$("#condition_details").addClass("is-invalid");
						$("#condition_details").click(function(){$("#error_condition_details").hide().text; $("#condition_details").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
		}

			
		//condition to work validations for BEVO fields
		if(ca_bevo_applicant == 'yes')
		{
			if($("#const_oil_storage_tank_table tr td:first").text() == '')
			{
				$("#error_const_oil_storage_tank").show().text("Sorry. There should be minimum 1 tank details added.");
				$("#const_oil_storage_tank_table").addClass("is-invalid");
				$("#const_oil_storage_tank_table").click(function(){$("#error_const_oil_storage_tank").hide().text; $("#const_oil_storage_tank_table").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			if($("#bevo_oil_storage_tank_table tr td:first").text() == '')
			{
				
				$("#error_bevo_oil_storage_tank").show().text("Sorry. There should be minimum 1 tank details added.");
				$("#bevo_oil_storage_tank_table").addClass("is-invalid");
				$("#bevo_oil_storage_tank_table").click(function(){$("#error_bevo_oil_storage_tank").hide().text; $("#bevo_oil_storage_tank_table").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			if($("#constituent_oil_mill_docs_value").text() == '')
			{
				// Change Condition for validation and error message by pravin 10-07-2017
				if(check_file_upload_validation(constituent_oil_mill_docs).result == false){	
					
					$("#error_constituent_oil_mill_docs").show().text(check_file_upload_validation(constituent_oil_mill_docs).error_message);
					$("#constituent_oil_mill_docs").addClass("is-invalid");
					$("#constituent_oil_mill_docs").click(function(){$("#error_constituent_oil_mill_docs").hide().text; $("#constituent_oil_mill_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
			
			// Change Condition for validation and error message by pravin 10-07-2017	
			if(check_radio_button_validation('separate_pipe_lines').result == false){	
				
				$("#error_separate_pipe_lines").show().text(check_radio_button_validation('separate_pipe_lines').error_message);
				$("#tbl_belongs_to_applicantYes").addClass("is-invalid");
				$("#tbl_belongs_to_applicantYes").click(function(){$("#error_separate_pipe_lines").hide().text; $("#tbl_belongs_to_applicantYes").removeClass("is-invalid");});
				value_return = 'false';
			}
			
		}
			
	
		// Change Condition for validation and error message by pravin 10-07-2017
		if(check_whitespace_validation_textbox(room_site_plan_no).result == false){
				
			$("#error_room_site_plan_no").show().text(check_whitespace_validation_textbox(room_site_plan_no).error_message);
			$("#room_site_plan_no").addClass("is-invalid");
			$("#room_site_plan_no").click(function(){$("#error_room_site_plan_no").hide().text; $("#room_site_plan_no").removeClass("is-invalid");});
			value_return = 'false';
		}
			
			
			if($("#room_details_docs_value").text() == '')
			{
				// Change Condition for validation and error message by pravin 10-07-2017
				if(check_file_upload_validation(room_details_docs).result == false){	
					
					$("#error_room_details_docs").show().text(check_file_upload_validation(room_details_docs).error_message);
					$("#room_details_docs").addClass("is-invalid");
					$("#room_details_docs").click(function(){$("#error_room_details_docs").hide().text; $("#room_details_docs").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
			
			
			// Change Condition for validation and error message by pravin 10-07-2017	
			if(check_radio_button_validation('lighted_ventilated').result == false){		
					
				$("#error_lighted_ventilated").show().text(check_radio_button_validation('lighted_ventilated').error_message);
				$("#lighted_ventilatedYes").addClass("is-invalid");
				$("#lighted_ventilatedYes").click(function(){$("#error_lighted_ventilated").hide().text; $("#lighted_ventilatedYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_radio_value('lighted_ventilated') == "yes"){
				
					if($("#ventilation_details_docs_value").text() == ''){
							
						// Change Condition for validation and error message by pravin 10-07-2017
						if(check_file_upload_validation(ventilation_details_docs).result == false){	
							
							$("#error_ventilation_details_docs").show().text(check_file_upload_validation(ventilation_details_docs).error_message);
							$("#ventilation_details_docs").addClass("is-invalid");
							$("#ventilation_details_docs").click(function(){$("#error_ventilation_details_docs").hide().text; $("#ventilation_details_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
			}
			

			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(ventilation_details).result == false){	
				
				$("#error_ventilation_details").show().text(check_whitespace_validation_textarea(ventilation_details).error_message);
				$("#ventilation_details").addClass("is-invalid");
				$("#ventilation_details").click(function(){$("#error_ventilation_details").hide().text; $("#ventilation_details").removeClass("is-invalid");});
				value_return = 'false';
			}
			

			// Change Condition for validation and error message by pravin 10-07-2017	
			if(check_radio_button_validation('locking_adequate').result == false){	
					
				$("#error_locking_adequate").show().text(check_radio_button_validation('locking_adequate').error_message);
				$("#locking_adequateYes").addClass("is-invalid");
				$("#locking_adequateYes").click(function(){$("#error_locking_adequate").hide().text; $("#locking_adequateYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				// Change Condition for validation and error message by pravin 11-07-2017
				if(check_radio_value('locking_adequate') == "yes"){
				
					if($("#locking_details_docs_value").text() == ''){
						
						// Change Condition for validation and error message by pravin 10-07-2017
						if(check_file_upload_validation(locking_details_docs).result == false){	
							
							$("#error_locking_details_docs").show().text(check_file_upload_validation(locking_details_docs).error_message);
							$("#locking_details_docs").addClass("is-invalid");
							$("#locking_details_docs").click(function(){$("#error_locking_details_docs").hide().text; $("#locking_details_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
			}

						
			// Change Condition for validation and error message by pravin 10-07-2017
			if(check_whitespace_validation_textarea(locking_details).result == false){
				
				$("#error_locking_details").show().text(check_whitespace_validation_textarea(locking_details).error_message);
				$("#locking_details").addClass("is-invalid");
				$("#locking_details").click(function(){$("#error_locking_details").hide().text; $("#locking_details").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			if(value_return == 'false'){

				var msg = "Please check some fields are missing or not proper.";
				renderToast('error', msg);
				return false;
			}else{
				exit();			
			}
	

	}



	//This function is used for laboratory Details Section
	function laboratory_details_report(){

		//taking values from form fields
		var laboratory_equipped=$("#laboratory_equipped").val();
		var lab_shortcomings=$("#lab_shortcomings").val();
		var lab_doc_ref_no=$("#lab_doc_ref_no").val();
		var laboratory_equipped_docs=$("#laboratory_equipped_docs").val();
		var check_save_reply = $("#check_save_reply").val();
		var value_return = 'true';

		// check Condition for validation  by Amol 28-07-2017
		if(final_status == 'referred_back'){
		
			if(check_whitespace_validation_textarea(check_save_reply).result == false){ 
		
				$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
				$("#check_save_reply").addClass("is-invalid");
				$("#check_save_reply").click(function(){$("#error_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
				value_return = 'false';
			}
		}
		
		
		//condition to work validations for non BEVO fields
		if(ca_bevo_applicant == 'no'){
				
			// Change Condition for validation and error message by pravin 12-07-2017	
			if(check_radio_button_validation('laboratory_equipped').result == false){	
				
				$("#error_laboratory_equipped").show().text(check_radio_button_validation('laboratory_equipped').error_message);
				$("#laboratory_equippedYes").addClass("is-invalid");
				$("#laboratory_equippedYes").click(function(){$("#error_laboratory_equipped").hide().text; $("#laboratory_equippedYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				// Change Condition for validation and error message by pravin 12-07-2017	
				if(check_radio_value('laboratory_equipped') == "yes"){
						
					// Change Condition for validation and error message by pravin 12-07-2017
					if(check_whitespace_validation_textbox(lab_doc_ref_no).result == false){
				
						$("#error_lab_doc_ref_no").show().text(check_whitespace_validation_textbox(lab_doc_ref_no).error_message);
						$("#lab_doc_ref_no").addClass("is-invalid");
						$("#lab_doc_ref_no").click(function(){$("#error_lab_doc_ref_no").hide().text; $("#lab_doc_ref_no").removeClass("is-invalid");});
						value_return = 'false';
					}
						
						
					if($("#laboratory_equipped_docs_value").text() == ''){
			
						// Change Condition for validation and error message by pravin 12-07-2017
						if(check_file_upload_validation(laboratory_equipped_docs).result == false){	
							
							$("#error_laboratory_equipped_docs").show().text(check_file_upload_validation(laboratory_equipped_docs).error_message);
							$("#laboratory_equipped_docs").addClass("is-invalid");
							$("#laboratory_equipped_docs").click(function(){$("#error_laboratory_equipped_docs").hide().text; $("#laboratory_equipped_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
				
			
				if(check_radio_value('laboratory_equipped') == "no"){
				
					// Change Condition for validation and error message by pravin 12-07-2017
					if(check_whitespace_validation_textarea(lab_shortcomings).result == false){
						
						$("#error_lab_shortcomings").show().text(check_whitespace_validation_textarea(lab_shortcomings).error_message);
						$("#lab_shortcomings").addClass("is-invalid");
						$("#lab_shortcomings").click(function(){$("#error_lab_shortcomings").hide().text; $("#lab_shortcomings").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
		}
		

		//condition to work validations for BEVO fields
		if(ca_bevo_applicant == 'yes')
		{
			// Change Condition for validation and error message by pravin 12-07-2017
			if(check_radio_button_validation('laboratory_equipped').result == false){
				
				$("#error_laboratory_equipped").show().text(check_radio_button_validation('laboratory_equipped').error_message);
				$("#laboratory_equippedYes").addClass("is-invalid");
				$("#laboratory_equippedYes").click(function(){$("#error_laboratory_equipped").hide().text; $("#laboratory_equippedYes").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				// Change Condition for validation and error message by pravin 12-07-2017	
				if(check_radio_value('laboratory_equipped') == "yes"){	
				
					if($("#laboratory_equipped_docs_value").text() == '')
					{
						// Change Condition for validation and error message by pravin 12-07-2017
						if(check_file_upload_validation(laboratory_equipped_docs).result == false){	
							
							$("#error_laboratory_equipped_docs").show().text(check_file_upload_validation(laboratory_equipped_docs).error_message);
							$("#laboratory_equipped_docs").addClass("is-invalid");
							$("#laboratory_equipped_docs").click(function(){$("#error_laboratory_equipped_docs").hide().text; $("#laboratory_equipped_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
					
				}else{
					
					// Change Condition for validation and error message by pravin 12-07-2017
					if(check_whitespace_validation_textarea(lab_shortcomings).result == false){
						
						$("#error_lab_shortcomings").show().text(check_whitespace_validation_textarea(lab_shortcomings).error_message);
						$("#lab_shortcomings").addClass("is-invalid");
						$("#lab_shortcomings").click(function(){$("#error_lab_shortcomings").hide().text; $("#lab_shortcomings").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
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









	//This function is used for other Details Section
	function other_details_report(){

		//taking values from form fields
			var commodity_quantity=$("#commodity_quantity").val();
			var own_machinery=$("#own_machinery").val();
			var processing_done_by=$("#processing_done_by").val();
			var machinery_processing_docs=$("#machinery_processing_docs").val();
			var constituent_oil_suppliers_docs=$("#constituent_oil_suppliers_docs").val();
			var bevo_machinery_details_docs=$("#bevo_machinery_details_docs").val();
			var fat_spread_facilitities=$("#fat_spread_facilitities").val();
			var bevo_quantity_per_month=$("#bevo_quantity_per_month").val();
			var graded_bevo_marketed_places=$("#graded_bevo_marketed_places").val();
			var other_points=$("#other_points").val();
			var recommendations=$("#recommendations").val();
			var check_save_reply = $("#check_save_reply").val();
			var value_return = 'true';
			
			
			// check Condition for validation  by Amol 28-07-2017
			if(final_status == 'referred_back'){
			
				if(check_whitespace_validation_textarea(check_save_reply).result == false){ 
				
					$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
					$("#check_save_reply").addClass("is-invalid");
					$("#check_save_reply").click(function(){$("#error_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
					value_return = 'false';	
				}
			}
			
			
			//condition to work validations for non BEVO fields
			if(ca_bevo_applicant == 'no')
			{

				// Change Condition for validation and error message by pravin 12-07-2017
				if(check_number_with_decimal_two_validation(commodity_quantity).result == false){	
								
					$("#error_commodity_quantity").show().text(check_number_with_decimal_two_validation(commodity_quantity).error_message);
					$("#commodity_quantity").addClass("is-invalid");
					$("#commodity_quantity").click(function(){$("#error_commodity_quantity").hide().text; $("#commodity_quantity").removeClass("is-invalid");});
					value_return = 'false';
				}
							
						
				// Change Condition for validation and error message by pravin 12-07-2017	
				if(check_radio_button_validation('own_machinery').result == false){	
					
					$("#error_own_machinery").show().text(check_radio_button_validation('own_machinery').error_message);
					$("#own_machineryYes").addClass("is-invalid");
					$("#own_machineryYes").click(function(){$("#error_own_machinery").hide().text; $("#own_machineryYes").removeClass("is-invalid");});
					value_return = 'false';

				}else{
					
					// Change Condition for validation and error message by pravin 12-07-2017
					if(check_radio_value('own_machinery') == "no"){	
											
						// Change Condition for validation and error message by pravin 12-07-2017
						if(check_whitespace_validation_textbox(processing_done_by).result == false){
							
							$("#error_processing_done_by").show().text(check_whitespace_validation_textbox(processing_done_by).error_message);
							$("#own_machineryYes").addClass("is-invalid");
							$("#own_machineryYes").click(function(){$("#error_processing_done_by").hide().text; $("#own_machineryYes").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
				
				
				if($("#machinery_processing_docs_value").text() == '')
				{
					// Change Condition for validation and error message by pravin 12-07-2017
					if(check_file_upload_validation(machinery_processing_docs).result == false){	
						
						$("#error_machinery_processing_docs").show().text(check_file_upload_validation(machinery_processing_docs).error_message);
						$("#machinery_processing_docs").addClass("is-invalid");
						$("#machinery_processing_docs").click(function(){$("#error_machinery_processing_docs").hide().text; $("#machinery_processing_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}
			
			//condition to work validations for BEVO fields
			if(ca_bevo_applicant == 'yes')
			{			
				if($("#const_oil_mills_table tr td:first").text() == '')
				{
					$("#error_const_oil_mills").show().text("Sorry. There should be minimum 1 oil mill details added.");
					$("#const_oil_mills_table").addClass("is-invalid");
					$("#const_oil_mills_table").click(function(){$("#error_const_oil_mills").hide().text; $("#const_oil_mills_table").removeClass("is-invalid");});
					value_return = 'false';
				}
				
				
				if($("#constituent_oil_suppliers_docs_value").text() == '')
				{
					// Change Condition for validation and error message by pravin 12-07-2017
					if(check_file_upload_validation(constituent_oil_suppliers_docs).result == false){	
						
						$("#error_constituent_oil_suppliers_docs").show().text(check_file_upload_validation(constituent_oil_suppliers_docs).error_message);
						$("#constituent_oil_suppliers_docs").addClass("is-invalid");
						$("#constituent_oil_suppliers_docs").click(function(){$("#error_constituent_oil_suppliers_docs").hide().text; $("#constituent_oil_suppliers_docs").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
				
				
				// Get sub_commodity values to validate "machinery Details" box fields and "Minimum Infrastructure/Facilities" fields (Done By pravin 10-01-2018)
				var sub_commodity_array = firm_sub_commodity;
				sub_commodity_array_obj = JSON.parse(sub_commodity_array);
				
				// Check sub_commodity value is "Blended edible vegitable oil" or not (Done by pravin 10-01-2018)
				if(jQuery.inArray("172", sub_commodity_array_obj) !== -1){
					
					if($("#bevo_machinery_details_docs_value").text() == '')
					{
						// Change Condition for validation and error message by pravin 12-07-2017
						if(check_file_upload_validation(bevo_machinery_details_docs).result == false){	
							
							$("#error_bevo_machinery_details_docs").show().text(check_file_upload_validation(bevo_machinery_details_docs).error_message);
							$("#bevo_machinery_details_docs").addClass("is-invalid");
							$("#bevo_machinery_details_docs").click(function(){$("#error_bevo_machinery_details_docs").hide().text; $("#bevo_machinery_details_docs").removeClass("is-invalid");});
							value_return = 'false';
						}
					}
				}
				
				
				// Check sub_commodity value is "fat spread" or not (Done by pravin 10-01-2018)
				if(jQuery.inArray("173", sub_commodity_array_obj) !== -1){
					
					// Change Condition for validation and error message by pravin 12-07-2017	
					if(check_radio_button_validation('fat_spread_facilitities').result == false){		
						
						$("#error_fat_spread_facilitities").show().text(check_radio_button_validation('fat_spread_facilitities').error_message);
						$("#fat_spread_facilititiesYes").addClass("is-invalid");
						$("#fat_spread_facilititiesYes").click(function(){$("#error_fat_spread_facilitities").hide().text; $("#fat_spread_facilititiesYes").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
									
				// Change Condition for validation and error message by pravin 12-07-2017	
				if(check_number_with_decimal_two_validation(bevo_quantity_per_month).result == false){	
						
					$("#error_bevo_quantity_per_month").show().text(check_number_with_decimal_two_validation(bevo_quantity_per_month).error_message);
					$("#bevo_quantity_per_month").addClass("is-invalid");
					$("#bevo_quantity_per_month").click(function(){$("#error_bevo_quantity_per_month").hide().text; $("#bevo_quantity_per_month").removeClass("is-invalid");});
					value_return = 'false';
				}
				
					
				// Change Condition for validation and error message by pravin 12-07-2017	
				if(check_whitespace_validation_textarea(graded_bevo_marketed_places).result == false){		
						
					$("#error_graded_bevo_marketed_places").show().text(check_whitespace_validation_textarea(graded_bevo_marketed_places).error_message);
					$("#graded_bevo_marketed_places").addClass("is-invalid");
					$("#graded_bevo_marketed_places").click(function(){$("#error_graded_bevo_marketed_places").hide().text; $("#graded_bevo_marketed_places").removeClass("is-invalid");});
					value_return = 'false';
				}
				
			}
			
			
			
			// Change Condition for validation and error message by pravin 12-07-2017	
			if(check_whitespace_validation_textarea(other_points).result == false){
					
				$("#error_other_points").show().text(check_whitespace_validation_textarea(other_points).error_message);
				$("#other_points").addClass("is-invalid");
				$("#other_points").click(function(){$("#error_other_points").hide().text; $("#other_points").removeClass("is-invalid");});
				value_return = 'false';
			}
			
	
			// Change Condition for validation and error message by pravin 12-07-2017	
			if(check_whitespace_validation_textarea(recommendations).result == false){
				
				$("#error_recommendations").show().text(check_whitespace_validation_textarea(recommendations).error_message);
				$("#recommendations").addClass("is-invalid");
				$("#recommendations").click(function(){$("#error_recommendations").hide().text; $("#recommendations").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			
			if(value_return == 'false'){

				var msg = "Please check some fields are missing or not proper.";
				renderToast('error', msg);
				return false;
			}else{
				exit();			
			}
			
			
	}






	function ca_export_report_validation(){
		
		var remark_on_report = $("#remark_on_report").val();
		var report_docs = $("#report_docs").val();
		var value_return = 'true';
		
		
		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(remark_on_report).result == false){	
		
			$("#error_remark_on_report").show().text(check_whitespace_validation_textarea(remark_on_report).error_message);
			$("#remark_on_report").addClass("is-invalid");
			$("#remark_on_report").click(function(){$("#error_remark_on_report").hide().text; $("#remark_on_report").removeClass("is-invalid");});
			value_return = 'false';	
		}
		
		
		if($('#report_docs_value').text() == ""){
				
			// Change Condition for validation and error message by pravin 11-07-2017			
			if(check_file_upload_validation(report_docs).result == false){
				
				$("#error_report_docs").show().text(check_file_upload_validation(report_docs).error_message);
				$("#report_docs").addClass("is-invalid");
				$("#report_docs").click(function(){$("#error_report_docs").hide().text; $("#report_docs").removeClass("is-invalid");});
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


	//Siteinspection report forms validations function ends here***********************************************


	//CA Renewal Form input fields validation starts here***********************************************

	function renewal_form_details(){

		//taking values from form fields
		var fullfill_minimum_quantity=$("#fullfill_minimum_quantity").val();
		var renewed_upto_date=$("#renewed_upto_date").val();
		var value_return = 'true';
		var check_save_reply = $("#check_save_reply").val();

		// Change Condition for validation and error message by pravin 12-07-2017	
		if(check_radio_button_validation('fullfill_minimum_quantity').result == false){	
				
			$("#error_fullfill_minimum_quantity").show().text(check_radio_button_validation('fullfill_minimum_quantity').error_message);
			$("#fat_spread_facilititiesYes").addClass("is-invalid");
			$("#fat_spread_facilititiesYes").click(function(){$("#error_fullfill_minimum_quantity").hide().text; $("#fat_spread_facilititiesYes").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		/*
		if($("#quantity_graded_table tr td").text() == '')
		{
			
			$("#error_quantity_graded").show().text("Sorry. All quantities should be entered.");
			$("#quantity_graded_table").addClass("is-invalid");
			$("#quantity_graded_table").click(function(){$("#error_quantity_graded").hide().text; $("#quantity_graded_table").removeClass("is-invalid");});
			value_return = 'false';
		}*/
			

		$(".renewal_min_qty_table").find('tr').each(function() {

			$(this).find('td').each(function() {
			
				if($(this).find('input').val() == '')
				{
					$("#error_quantity_graded").show().text("All quantities required.");
					$("#quantity_graded_table").addClass("is-invalid");
					$("#quantity_graded_table").click(function(){$("#error_quantity_graded").hide().text; $("#quantity_graded_table").removeClass("is-invalid");});
					value_return = 'false';
				}
			});
		});
			
		/*if(renewed_upto_date==""){
							
			$("#error_renewed_upto_date").show().text("Please select date for renewal upto");
			$("#renewed_upto_date").addClass("is-invalid");
			$("#renewed_upto_date").click(function(){$("#error_renewed_upto_date").hide().text; $("#renewed_upto_date").removeClass("is-invalid");});
			value_return = 'false';
		}*/
			
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
		}
		else{
			exit();			
		}	
			
	}		
			
	//CA Renewal Form input fields validation ends here***********************************************	
			
			
			
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
			$("#check_save_reply").click(function(){$("#error_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
			
			value_return = 'false';
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
			
			





	//File validation common function
	//This function is called on file upload browse button to validate selected file
	function file_browse_onclick(field_id){
	
		var selected_file = $('#'.concat(field_id)).val();
		var ext_type_array = ["jpg" , "pdf"];
		
		var get_file_size = $('#'.concat(field_id))[0].files[0].size;
		var get_file_ext = selected_file.split(".");
		
		var value_return = 'true';
		
		get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();
		
		if(get_file_size > 2097152){

			$("#error_size_".concat(field_id)).show().text("Please select file below 2mb");
			$("#error_size_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#error_size_".concat(field_id)).hide().text; $("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('')
			value_return = 'false';
		}
		
		
		if (ext_type_array.lastIndexOf(get_file_ext) == -1){
		
			$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#error_type_".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}
		
		if(value_return == 'false')
		{
			return false;
		}
		else{
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
			
			if(field_length > 0 ){
				
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

			if($('input[name="'+field_value+'"]:checked').val() == "yes"){
						return 'yes';			
			}else if($('input[name="'+field_value+'"]:checked').val() == "no"){
						return 'no';
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
				setTimeout(function(){ $("#error_directors_details_name").fadeOut();},8000);
				$("#d_name").addClass("is-invalid");
				$("#d_name").click(function(){$("#error_directors_details_name").hide().text; $("#d_name").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if(check_whitespace_validation_textarea(d_address).result == false){
				
				$("#error_directors_details_address").show().text(check_whitespace_validation_textarea(d_address).error_message);
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
			}
			else{
				return true;			
			}
		}
		
		
		//check CA renewal valid graded quantity input.
		//added on 14-12-2017
		function check_quantity(field_id){
			
			var get_quantity = $('#'.concat(field_id)).val();
			
			if($.isNumeric(get_quantity)){}else{
				
				document.getElementById(field_id).value = '';//clear input value
				/**
				 * COMMENTED ON 26TH DEC 2020 & SHOW ERROR MESSAGE FOR INPUT FIELD SEPARATELY
				 * by Aniket Ganvir
				 */
				// $("#error_quantity_graded").show().text("Enter Quantity in Numbers");
				// $("#error_quantity_graded").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				// setTimeout(function(){ $("#error_quantity_graded").fadeOut();},3000);
				//field_id.click(function(){$("#error_quantity_graded").hide().text;});

				// show invalid entry message separately on each invalid entry attempts
				// by Aniket Ganvir dated 26th DEC 2020
				$("#error_".concat(field_id)).show().text("Enter Quantity in Numbers");
				$('#'.concat(field_id)).addClass("is-invalid");
				setTimeout(function(){ $("#error_".concat(field_id)).fadeOut(); $('#'.concat(field_id)).removeClass("is-invalid");},3000);

				return false;
			}	 			
		}
	
	
	function renderToast(theme, msgTxt) {

		$('#toast-msg-'+theme).html(msgTxt);
		$('#toast-msg-box-'+theme).fadeIn('slow');
		$('#toast-msg-box-'+theme).delay(3000).fadeOut('slow');

	}