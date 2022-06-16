	//This function is used for both add firm and added firm validations.
	function add_firm_validations(){

		// Empty fields validations start
		//split path to find controller and action
		var path = window.location.pathname;
		var paths = path.split("/");
		var controller = paths[2];
		var action = paths[3];
		
		//taking values from form fields
		var certification_type=$("#certification_type").val();
		//var export_unit=$("#export_unit").val();
		var firm_name=$("#firm_name").val();
		var once_card_no=$("#once_card_no").val();
		var email=$("#email").val();
		var mobile_no=$("#mobile_no").val();
		var fax_no=$("#fax_no").val();
		var commodity_category=$("#commodity_category").val();
		var commodity=$("#commodity").val();
		var selected_commodity=$("#selected_commodity").val();
		var total_charge=$("#total_charge").val();
		var packaging_materials=$("#packaging_materials").val();
		var selected_packaging_materials=$("#selected_packaging_materials").val();
		var other_packaging_details=$("#other_packaging_details").val();
		var street_address=$("#street_address").val();
		var state=$("#state").val();
		var district=$("#district").val();
		var postal_code=$("#postal_code").val();
		
		var value_return = 'true';
		
		//condition to work validations for both add firm and added firm
		if(action == 'add_firm' || action == 'add-firm')
		{		
			
			//validation to check give application is old or new. If application old then check it is expired or not
			// Done By pravin 04/10/2017
			if($('input[name="data[is_already_granted]"]:checked').val() == "yes")
			{
				var last_renewal_dates = $("#last_renewal_dates"+x).val();
				var certificate_no = $("#certification_no").val();
				var grant_date = $("#grant_date").val();
				var result = check_renewal_date_due(grant_date);
				var renewal_date_due = result.renewal_date_due;
				
				if(certificate_no == ''){
					$("#error_certificate_no").show().text("Please enter Certificate No.");
					$("#certification_no").addClass("is-invalid");
					$("#certification_no").click(function(){$("#error_certificate_no").hide().text;$("#certification_no").removeClass("is-invalid");});
					value_return = 'false';
				}
				
				if(grant_date == ''){
					$("#error_grant_date").show().text("Please Select Certificate Grant Date.");
					$("#grant_date").addClass("is-invalid");
					$("#grant_date").click(function(){$("#error_grant_date").hide().text;$("#grant_date").removeClass("is-invalid");});
					value_return = 'false';
				}
				
				if(renewal_date_due == 'yes')
				{

					if(last_renewal_dates == '')
					{
						$("#error_renewal_dates"+x).show().text("Please enter renewal date. If not renewed, So the application was expired. Please register with new application");
						$(".add_more_button").addClass("is-invalid");
						$(".add_more_button").click(function(){$("#error_renewal_dates"+x).hide().text;$(".add_more_button").removeClass("is-invalid");});
						value_return = 'false';
						
					}else{
						
						var result2 = valid_last_renewal_date(last_renewal_dates);
						var application_expired_status = result2.application_expired_status;
						if(application_expired_status == 'yes')
						{
							$("#error_renewal_dates"+x).show().text("Please enter next renewal date with 'addmore' button. If not renewed, So the application was expired. Please register with new application");
							$(".add_more_button").addClass("is-invalid");
							$(".add_more_button").click(function(){$("#error_renewal_dates"+x).hide().text;$(".add_more_button").removeClass("is-invalid");});
							value_return = 'false';
						}
						
					}	
				}
			}
			
			
			if(certification_type==""){
			
				$("#error_certification_type").show().text("Please select Certification type.");
				$("#certification_type").addClass("is-invalid");
				$("#certification_type").click(function(){$("#error_certification_type").hide().text;$("#certification_type").removeClass("is-invalid");});
				value_return = 'false';
			}

			/*	if(export_unit==""){
				
				$("#error_export_unit").show().text("Please Check Export unit yes or no.");
				$("#certification_type").addClass("is-invalid");
				//setTimeout(function(){ $("#error_export_unit").fadeOut();},8000);
				$("#export_unit").click(function(){$("#error_export_unit").hide().text;});
				value_return = 'false';
				}
			*/

			if(check_whitespace_validation_textbox(firm_name).result == false){	

				$("#error_firm_name").show().text(check_whitespace_validation_textbox(firm_name).error_message);
				$("#firm_name").addClass("is-invalid");
				$("#firm_name").click(function(){$("#error_firm_name").hide().text;$("#firm_name").removeClass("is-invalid");});
				value_return = 'false';
			}
				
		}
		
		
		//These four fields required in added firm so not in condition
		/*	if(once_card_no==""){
				
				$("#error_aadhar_card_no").show().text("Please enter your Aadhar card no.");
				$("#once_card_no").addClass("is-invalid");
				$("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text;$("#once_card_no").removeClass("is-invalid");});
				value_return = 'false';
			}
			else{
				
				if(once_card_no.match(/^(?=.*[0-9])[0-9]{12}$/g) || once_card_no.match(/^[X-X]{8}[0-9]{4}$/i)){//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol  
						
				}else{				
					
					$("#error_aadhar_card_no").show().text("Only numbers allowed, min & max length is 12");
					//$("#error_aadhar_card_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
					$("#once_card_no").focusout(function(){$("#error_aadhar_card_no").hide().text;});
					value_return = 'false';
				}
				
			}
			*/

			if(email==""){

				$("#error_email").show().text("Please enter your email.");
				$("#email").addClass("is-invalid");
				$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				if(!email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/))   
				{ 				
					$("#error_email").show().text("Entered email id is not valid.");
					$("#email").addClass("is-invalid");
					$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
					value_return = 'false';
					
				}
			
			}	

			if(mobile_no==""){

				$("#error_mobile_no").show().text("Please Enter your Mobile No.");
				$("#mobile_no").addClass("is-invalid");
				$("#mobile_no").click(function(){$("#error_mobile_no").hide().text;$("#mobile_no").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{

				if(action == 'add_firm' || action == 'add-firm')//condition added on 17-12-2019 by Amol
				{				
					if(!(mobile_no.match(/^(?=.*[0-9])[0-9]{10}$/g)))//also allow if 6 X $ 4 nos found //added on 12-10-2017 by Amol   
					{ 
						$("#error_mobile_no").show().text("Mobile no. is not valid, only 10 digits no. allowed");
						$("#mobile_no").addClass("is-invalid");
						$("#mobile_no").click(function(){$("#error_mobile_no").hide().text;$("#mobile_no").removeClass("is-invalid");});
						value_return = 'false';
						
					}

					//first valid no. for mob.no, applid on 16-02-2021 by Amol
					var validfirstno = ['7','8','9'];
					//get first character of mobile no.
					var f_m_no = mobile_no.charAt(0);
					if($.inArray(f_m_no,validfirstno) != -1){
						//valid
					}else{

						$("#error_mobile_no").show().text("Invalid mobile number");
						$("#mobile_no").addClass("is-invalid");
						$("#mobile_no").click(function(){$("#error_mobile_no").hide().text;$("#mobile_no").removeClass("is-invalid");});
						value_return='false';
					}
				
				}else{
					
					if(!(mobile_no.match(/^(?=.*[0-9])[0-9]{10}$/g) || mobile_no.match(/^[X-X]{6}[0-9]{4}$/i)))//also allow if 6 X $ 4 nos found //added on 12-10-2017 by Amol   
					{ 
						$("#error_mobile_no").show().text("Mobile no. is not valid, only 10 digits no. allowed");
						$("#mobile_no").addClass("is-invalid");
						$("#mobile_no").click(function(){$("#error_mobile_no").hide().text;$("#mobile_no").removeClass("is-invalid");});
						value_return = 'false';
						
					}
					
				}
			}

			if(fax_no !=""){

				if(!fax_no.match(/^(?=.*[0-9])[0-9]{5,15}$/g))   
				{
					
					$("#error_fax_no").show().text("Given phone no. is not valid");
					$("#fax_no").addClass("is-invalid");
					$("#fax_no").click(function(){$("#error_fax_no").hide().text;$("#fax_no").removeClass("is-invalid");});
					value_return = 'false';
				}

				//validate landline no, pattern, not to contain string '00000', on 18-02-2021 by Amol
				if(fax_no.indexOf('00000') > -1){

					$("#error_fax_no").show().text("Given phone no. is not valid");
					$("#fax_no").addClass("is-invalid");
					$("#fax_no").click(function(){$("#error_fax_no").hide().text;$("#fax_no").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
			
			//condition to work validations for both add firm and added firm
			if(action == 'add_firm' || action == 'add-firm')
			{

				if($("#certification_type").val() != 2 )
				{
				
					if(commodity_category==""){

						$("#error_commodity_category").show().text("Please Select Commodity Category");
						$("#commodity_category").addClass("is-invalid");
						$("#commodity_category").click(function(){$("#error_commodity_category").hide().text;$("#commodity_category").removeClass("is-invalid");});
						value_return = 'false';
					}
					
					/*	if(commodity==""){
							
							$("#error_commodity").show().text("Please Select Commodity from list");
							$("#error_commodity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
							//setTimeout(function(){ $("#error_commodity").fadeOut();},8000);
							$("#commodity").click(function(){$("#error_commodity").hide().text;});
							value_return = 'false';
						}
					*/

					if(!($('select#selected_commodity option').length > 1)){//changed from 0 to 1 on 09-08-2017 by Amol

						$("#error_selected_commodity").show().text("There are no selected commodities. Please select first.");
						$("#selected_commodity").addClass("is-invalid");
						$("#selected_commodity").click(function(){$("#error_selected_commodity").hide().text;$("#selected_commodity").removeClass("is-invalid");});
						value_return = 'false';
					}
					
				} else if($("#certification_type").val() == 2) {

					if($('input[name="data[is_sponsored_press]"]:checked').val() == "yes"){
						
						if($('#sponsored_ca').val() == ''){

							$("#error_sponsored_ca").show().text("Please select the sponsored ca");
							$("#sponsored_ca").addClass("is-invalid");
							$("#sponsored_ca").click(function(){$("#error_sponsored_ca").hide().text;$("#sponsored_ca").removeClass("is-invalid");});
							value_return = 'false';
						}

					}
					
					if($('select#selected_packaging_materials option').length > 0){
					
						if(($("#selected_packaging_materials option:selected" ).text() == 'Other')){

							if(other_packaging_details==""){
								
								$("#error_other_packaging").show().text("Please Enter other packaging materials names");
								$("#other_packaging_details").addClass("is-invalid");
								$("#other_packaging_details").click(function(){$("#error_other_packaging").hide().text;$("#other_packaging_details").removeClass("is-invalid");});
								value_return = 'false';
							}
						}			
					
					}else{			
						
						$("#error_packaging_materials").show().text("Please Select Packaging Material from list");
						$("#selected_packaging_materials").addClass("is-invalid");
						$("#selected_packaging_materials").click(function(){$("#error_packaging_materials").hide().text;$("#selected_packaging_materials").removeClass("is-invalid");});
						value_return = 'false';	
					}
					
				}
			}


			if(check_whitespace_validation_textarea(street_address).result == false){	

				$("#error_street_address").show().text(check_whitespace_validation_textarea(street_address).error_message);
				$("#street_address").addClass("is-invalid");
				$("#street_address").click(function(){$("#error_street_address").hide().text;$("#street_address").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if(state==""){

				$("#error_state").show().text("Please Select State.");
				$("#state").addClass("is-invalid");
				$("#state").click(function(){$("#error_state").hide().text;$("#state").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(district==""){

				$("#error_district").show().text("Please Select District.");
				$("#district").addClass("is-invalid");
				$("#district").click(function(){$("#error_district").hide().text;$("#district").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			if(postal_code==""){

				$("#error_postal_code").show().text("Please Enter Postal code");
				$("#postal_code").addClass("is-invalid");
				$("#postal_code").click(function(){$("#error_postal_code").hide().text;$("#postal_code").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				if(!postal_code.match(/^(?=.*[0-9])[0-9]{6}$/g))   
				{ 
					$("#error_postal_code").show().text("Postal code is not valid, only 6 digits no. mandatory");
					$("#postal_code").addClass("is-invalid");
					$("#postal_code").click(function(){$("#error_postal_code").hide().text;$("#postal_code").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
				
			/*
			if($('#aadhar_auth_check').prop("checked") == false){
				
				$("#error_aadhar_auth_check").show().text("Please check to confirm Aadhar authentication");
				$("#error_aadhar_auth_check").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				//setTimeout(function(){ $("#error_aadhar_auth_check").fadeOut();},8000);
				$("#aadhar_auth_check").click(function(){$("#error_aadhar_auth_check").hide().text;});
				value_return = 'false';	
			}
			*/
	

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


	//This function is used for New Customer registration form validations.
	function register_customer_validations(){

		//split path to find controller and action
		var path = window.location.pathname;
		var paths = path.split("/");
		var controller = paths[2];
		var action = paths[3];

		var f_name=$("#f_name").val();
		var m_name=$("#m_name").val();
		var l_name=$("#l_name").val();
		var street_address=$("#street_address").val();
		var state=$("#state").val();
		var district=$("#district").val();
		var postal_code=$("#postal_code").val();
		var email=$("#email").val();
		var mobile=$("#mobile").val();
		var landline=$("#landline").val();
		var once_card_no=$("#once_card_no").val();
		var document=$("#document").val();
		var photo_id_no=$("#photo_id_no").val();
		var upload_file=$("#upload_file").val();

		var value_return = 'true';
		
		if(check_alpha_character_validation(f_name).result == false){	
				
			$("#error_f_name").show().text(check_alpha_character_validation(f_name).error_message);
			$("#f_name").addClass("is-invalid");
			$("#f_name").click(function(){$("#error_f_name").hide().text; $("#f_name").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(m_name != ""){
			
			if(check_alpha_character_validation(m_name).result == false){	
					
				$("#error_m_name").show().text(check_alpha_character_validation(m_name).error_message);	
				$("#m_name").addClass("is-invalid");
				$("#m_name").click(function(){$("#error_m_name").hide().text; $("#m_name").removeClass("is-invalid");});
				value_return = 'false';
			}
		}	
			
	
		if(check_alpha_character_validation(l_name).result == false){	
				
			$("#error_l_name").show().text(check_alpha_character_validation(l_name).error_message);	
			$("#l_name").addClass("is-invalid");
			$("#l_name").click(function(){$("#error_m_name").hide().text; $("#l_name").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(check_whitespace_validation_textarea(street_address).result == false){
			
			$("#error_street_address").show().text(check_whitespace_validation_textarea(street_address).error_message);	
			$("#street_address").addClass("is-invalid");
			$("#street_address").click(function(){$("#error_street_address").hide().text; $("#street_address").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(state==""){
			
			$("#error_state").show().text("Please select state.");
			$("#state").addClass("is-invalid");
			$("#state").click(function(){$("#error_state").hide().text; $("#state").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(district==""){
			
			$("#error_district").show().text("Please select district.");
			$("#district").addClass("is-invalid");
			$("#district").click(function(){$("#error_district").hide().text; $("#district").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(postal_code==""){
			
			$("#error_postal_code").show().text("Please enter postal code.");
			$("#postal_code").addClass("is-invalid");
			$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
			value_return = 'false';
		
		}else{

			if(!postal_code.match(/^(?=.*[0-9])[0-9]{6}$/g)){ 
				
				$("#error_postal_code").show().text("Postal code is not valid, only 6 digits no. mandatory");
				$("#postal_code").addClass("is-invalid");
				$("#postal_code").click(function(){$("#error_postal_code").hide().text; $("#postal_code").removeClass("is-invalid");});
				value_return = 'false';
				
			}
		}	
									
			
		if(email==""){
					
			$("#error_email").show().text("Please enter your email.");
			$("#email").addClass("is-invalid");
			$("#email").click(function(){$("#error_email").hide().text; $("#email").removeClass("is-invalid");});
			value_return = 'false';
		
		}else{
			
			if(!email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/))   
			{ 
				
				$("#error_email").show().text("Entered email id is not valid.");
				$("#email").addClass("is-invalid");
				$("#email").click(function(){$("#error_email").hide().text; $("#email").removeClass("is-invalid");});
				value_return = 'false';
				
			}
		
		}
			
		if(mobile==""){
			
			$("#error_mobile_no").show().text("Please Enter your Mobile No.");
			$("#mobile").addClass("is-invalid");
			$("#mobile").click(function(){$("#error_mobile_no").hide().text; $("#mobile").removeClass("is-invalid");});
			value_return = 'false';
	
		}else{
			
			if(action == 'register_customer')//condition added on 17-12-2019 by Amol 
			{		
				if(!(mobile.match(/^(?=.*[0-9])[0-9]{10}$/g)))//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol     
				{ 
					
					$("#error_mobile_no").show().text("Mobile no. is not valid, only 10 digits no. allowed");
					$("#mobile").addClass("is-invalid");
					$("#mobile").click(function(){$("#error_mobile_no").hide().text; $("#mobile").removeClass("is-invalid");});
					value_return = 'false';
					
				}

				//first valid no. for mob.no, applid on 16-02-2021 by Amol
				var validfirstno = ['7','8','9'];
				//get first character of mobile no.
				var f_m_no = mobile.charAt(0);
				if($.inArray(f_m_no,validfirstno) != -1){
					//valid
				}else{
					$("#error_mobile_no").show().text("Invalid mobile number");
					$("#mobile").addClass("is-invalid");
					$("#mobile").click(function(){$("#error_mobile_no").hide().text; $("#mobile").removeClass("is-invalid");});
					value_return='false';
				}

			}else{
				
				if(!(mobile.match(/^(?=.*[0-9])[0-9]{10}$/g) || mobile.match(/^[X-X]{6}[0-9]{4}$/i)))//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol     
				{ 
					
					$("#error_mobile_no").show().text("Mobile no. is not valid, only 10 digits no. allowed");
					$("#mobile").addClass("is-invalid");
					$("#mobile").click(function(){$("#error_mobile_no").hide().text; $("#mobile").removeClass("is-invalid");});
					value_return = 'false';
					
				}
				
			}
		}	
		
		if(landline==""){
			
			/*	$("#error_landline").show().text("Please Enter your landline No.");
			$("#error_landline").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			//setTimeout(function(){ $("#error_landline").fadeOut();},8000);
			$("#landline").click(function(){$("#error_landline").hide().text;});
			value_return = 'false';
			*/
		}else{
			
			if(!landline.match(/^(?=.*[0-9])[0-9]{6,12}$/g))   
			{ 
				
				$("#error_landline").show().text("landline no. is not valid, only no. allowed");
				$("#landline").addClass("is-invalid");
				$("#landline").click(function(){$("#error_landline").hide().text; $("#landline").removeClass("is-invalid");});
				value_return = 'false';
				
			}

			//validate landline no, pattern, not to contain string '00000', on 18-02-2021 by Amol
			if(landline.indexOf('00000') > -1){

				$("#error_landline").show().text("Given phone no. is not valid");
				$("#landline").addClass("is-invalid");
				$("#landline").click(function(){$("#error_landline").hide().text; $("#landline").removeClass("is-invalid");});
				value_return = 'false';
			}
			
		}	
		
		if(document==""){
				
			$("#error_document").show().text("Please select document type.");
			$("#document").addClass("is-invalid");
			$("#document").click(function(){$("#error_document").hide().text; $("#document").removeClass("is-invalid");});
			value_return = 'false';
		}
		
		if(photo_id_no==""){
				
			$("#error_photo_id_no").show().text("Please enter photo id no.");
			$("#photo_id_no").addClass("is-invalid");
			$("#photo_id_no").click(function(){$("#error_photo_id_no").hide().text; $("#photo_id_no").removeClass("is-invalid");});
			value_return = 'false';
		}
		
		//condition to work validations only for first time registration
		if(action == 'register_customer')
		{		
		
			var captchacode=$("#captchacode").val();
			var confirm_email=$("#confirm_email").val();
			
			if(email != ""){

				if(email == confirm_email)				
				{			
					//alert('blank name');
					//value_return = 'true'; // by pravin 01/05/2017
				}
				else{				
					//alert('Confirm email Not matched with email entered. please confirm properly');
					//changed on 13-07-2018 by pravin
					$("#error_confirm_email").show().text("The Confirmation Email must match your Email Address.");
					$("#confirm_email").addClass("is-invalid");
					$("#confirm_email").click(function(){$("#error_confirm_email").hide().text; $("#confirm_email").removeClass("is-invalid");});
					value_return = 'false';
				}
				
			}
			
			if(confirm_email==""){
				
				$("#error_confirm_email").show().text("Please confirm email.");
				$("#confirm_email").addClass("is-invalid");
				$("#confirm_email").click(function(){$("#error_confirm_email").hide().text; $("#confirm_email").removeClass("is-invalid");});
				value_return = 'false';
			
			}else{
				
				if(!confirm_email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/))   
				{ 
					
					$("#error_confirm_email").show().text("Entered email id is not valid.");
					$("#confirm_email").addClass("is-invalid");
					$("#confirm_email").click(function(){$("#error_confirm_email").hide().text; $("#confirm_email").removeClass("is-invalid");});
					value_return = 'false';
				}
			
			}
			
			/*	commented on 05-04-2018 by Amol
				if(upload_file==""){
					
					$("#error_upload_file").show().text("Please select file to upload.");
					$("#upload_file").addClass("is-invalid");
					$("#upload_file").click(function(){$("#error_upload_file").hide().text; $("#upload_file").removeClass("is-invalid");});
					value_return = 'false';
				}
			*/

			if(once_card_no==""){

				/*	commented on 22-03-2018 to avoid mandatory for aadhar
					$("#error_aadhar_card_no").show().text("Please enter your Aadhar card no.");
					$("#once_card_no").addClass("is-invalid");
					$("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text; $("#once_card_no").removeClass("is-invalid");});
					value_return = 'false';
				*/
			
			}else{

				//updated limit to 72 and alphanumeric for aadhar/VID/Token acceptance, on 18-06-2018
				if(once_card_no.match(/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9]{12,72}$/g) || once_card_no.match(/^[X-X]{8}[0-9]{4}$/i)){//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol 
						
				}else{
					
					$("#error_aadhar_card_no").show().text("Please Enter Proper Aadhar/VID/Token Id.");
					$("#once_card_no").addClass("is-invalid");
					$("#once_card_no").focusout(function(){$("#error_aadhar_card_no").hide().text;$("#once_card_no").removeClass("is-invalid");});
					value_return = 'false';
				}
				
			}
		
			if(once_card_no != ''){
				//applied condition on 22-03-2018 to avoid mandatory for aadhar
				if($('#aadhar_auth_check').prop("checked") == false){
						
					$("#error_aadhar_auth_check").show().text("Please check to confirm Aadhar authentication");
					$("#once_card_no").addClass("is-invalid");
					$("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text; $("#once_card_no").removeClass("is-invalid");});
					value_return = 'false';	
				}
			}
			
			if(captchacode==""){
				
				$("#error_captchacode").show().text("Please enter your captcha code.");
				$("#captchacode").addClass("is-invalid");
				$("#captchacode").click(function(){$("#error_captchacode").hide().text; $("#captchacode").removeClass("is-invalid");});
				value_return = 'false';
			}
		}
		
		//this condition is added on 16-02-2021 by Amol to encode sensitive info like document no. etc before sending post
		if(value_return == 'true'){
			
			$("#photo_id_no").val(btoa(photo_id_no));//encoding to base64
		}
		
		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		
		}else{

			if(action == 'customer_profile' || action == 'customer-profile') // added "customer-profile" in condition (@by Aniket Ganvir dated 8th DEC 2020)
			{
				exit();
			}
		}
	}


	//This function is used for applicant login validations.
	function login_customer_validations(){

		var customer_id=$("#customer_id").val();
		var password=$("#passwordValidation").val();
		var captchacode=$("#captchacode").val();
		var chemist_id = $("#chemist_id").val();
		
		var value_return = 'true';
		
		if(customer_id==""){
				
			$("#error_customer_id").show().text("Please enter your Applicant id.");
			$("#customer_id").addClass("is-invalid");
			$("#customer_id").click(function(){$("#error_customer_id").hide().text; $("#customer_id").removeClass("is-invalid");});
			value_return = 'false';
		}
		
		if(chemist_id==""){
				
			$("#error_chemist_id").show().text("Please enter your Chemist id.");
			$("#chemist_id").addClass("is-invalid");
			$("#chemist_id").click(function(){$("#error_chemist_id").hide().text; $("#chemist_id").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(password==""){
				
			$("#error_password").show().text("Please enter your password.");
			$("#passwordValidation").addClass("is-invalid");
			$("#passwordValidation").click(function(){$("#error_password").hide().text; $("#passwordValidation").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(captchacode==""){
				
			$("#error_captchacode").show().text("Please enter Captcha code");
			$("#captchacode").addClass("is-invalid");
			$("#captchacode").click(function(){$("#error_captchacode").hide().text; $("#captchacode").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		
		}else{
			
			var PasswordValue = document.getElementById('passwordValidation').value;
			var SaltValue = document.getElementById('hiddenSaltvalue').value;
			var EncryptPass = sha512(PasswordValue);
			var SaltedPass = SaltValue.concat(EncryptPass);
			var Saltedsha512pass = sha512(SaltedPass);
			document.getElementById('passwordValidation').value = Saltedsha512pass;
			document.getElementById('hiddenSaltvalue').value = '';
			exit();
		}
	
	
	}


	//Login User Validation
	function login_users_validations(){
									
		var email=$("#email").val();
		var password=$("#passwordValidation").val();
		var captchacode=$("#captchacode").val();
		var value_return = 'true';

		if(email==""){

			$("#error_email").show().text("Please enter your Email id.");
			$("#email").addClass("is-invalid");
			$("#email").click(function(){$("#error_email").hide().text; $("#email").removeClass("is-invalid");});
			value_return = 'false';
		
		}else{

			if(!email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){ 
				
				$("#error_email").show().text("Entered email id is not valid.");
				$("#email").addClass("is-invalid");
				$("#email").click(function(){$("#error_email").hide().text; $("#email").removeClass("is-invalid");});
				value_return = 'false';
			}
		
		}
				
		if(password==""){

			$("#error_password").show().text("Please enter your password.");
			$("#passwordValidation").addClass("is-invalid");
			$("#passwordValidation").click(function(){$("#error_password").hide().text; $("#passwordValidation").removeClass("is-invalid");});
			value_return = 'false';
		}
				
		if(captchacode==""){

			$("#error_captchacode").show().text("Please enter captcha code");
			$("#captchacode").addClass("is-invalid");
			$("#captchacode").click(function(){$("#error_captchacode").hide().text; $("#captchacode").removeClass("is-invalid");});
			value_return = 'false';
		}
				
		if(value_return == 'false'){               

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		
		} else {

			var PasswordValue = document.getElementById('passwordValidation').value;
			var SaltValue = document.getElementById('hiddenSaltvalue').value;
			var EncryptPass = sha512(PasswordValue);
			var SaltedPass = SaltValue.concat(EncryptPass);
			var Saltedsha512pass = sha512(SaltedPass);
			
			document.getElementById('passwordValidation').value = Saltedsha512pass;
			document.getElementById('hiddenSaltvalue').value = '';
	
			exit();
		}
			
									
	}
		
		
	//This function is used for Forgot password input validations.
	function forgot_password_validations(){
			
		// Empty Field validation
		var customer_id = $("#customer_id").val();
		var email = $("#email").val();
		var captchacode = $("#captchacode").val();
		var mobileno = $('#mobileno').val();
		var value_return = 'true';

		if (mobileno == "") {

			$("#error_mobileno").show().text("Please enter Mobile Number!");
			$("#mobileno").addClass("is-invalid");
			$("#mobileno").click(function(){$("#error_mobileno").hide().text; $("#mobileno").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(customer_id == ""){
				
			$("#error_customer_id").show().text("Please enter Applicant Id!");
			$("#customer_id").addClass("is-invalid");
			$("#customer_id").click(function(){$("#error_customer_id").hide().text; $("#customer_id").removeClass("is-invalid");});
			value_return = 'false';
		}
				
		if(email==""){
			
			$("#error_email").show().text("Please enter your email.");
			$("#email").addClass("is-invalid");
			$("#email").click(function(){$("#error_email").hide().text; $("#email").removeClass("is-invalid");});
			value_return = 'false';
		
		}else{
				
			if(!email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){ 
				
				$("#error_email").show().text("Entered email id is not valid.");
				$("#email").addClass("is-invalid");
				$("#email").click(function(){$("#error_email").hide().text; $("#email").removeClass("is-invalid");});
				value_return = 'false';
			}
		}
				
		if(captchacode==""){
			
			$("#error_captchacode").show().text("Please enter Captcha code.");
			$("#captchacode").addClass("is-invalid");
			$("#captchacode").click(function(){$("#error_captchacode").hide().text; $("#captchacode").removeClass("is-invalid");});
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


	//This function is used for Reset password input validations.
	function reset_password_validations(){
		
		var newpass=$("#Newpassword").val();
		var confpass=$("#confpass").val();
		var captchacode=$("#captchacode").val();
		var user_id = $("#user_id").val();

		var value_return = 'true';

		if(newpass==""){
			
			$("#error_newpass").show().text("Please enter your new password.");
			$("#Newpassword").addClass("is-invalid");
			$("#Newpassword").click(function(){$("#error_newpass").hide().text; $("#Newpassword").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(confpass==""){
			
			$("#error_confpass").show().text("Please confirm your new password.");
			$("#confpass").addClass("is-invalid");
			$("#confpass").click(function(){$("#error_confpass").hide().text; $("#confpass").removeClass("is-invalid");});
			value_return = 'false';
		}
			
		if(captchacode==""){
			
			$("#error_captchacode").show().text("Please enter your verification code.");
			$("#captchacode").addClass("is-invalid");
			$("#captchacode").click(function(){$("#error_captchacode").hide().text; $("#captchacode").removeClass("is-invalid");});
			value_return = 'false';
		}

		
		if(newpass==user_id){
			
			$.alert('Please Note: You can not use your User Id as your password');
			$("#Newpassword").val('');
			$("#confpass").val('');
			value_return = 'false';
		}	
				
				
		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		
		}else{
				
			//New Password Encryption

				var NewpasswordValue = document.getElementById('Newpassword').value;
				
				if(NewpasswordValue.match(/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{7,15}$/g)){ 
					
				}else{

					var msg = "Password length should be min. 8 char, min. 1 number, min. 1 Special char. and min. 1 Capital Letter.";
					renderToast('error', msg);
					return false;	
				}
				
				
				var SaltValue = document.getElementById('hiddenSaltvalue').value;
				var NewpassEncryptpass = sha512(NewpasswordValue);
				var NewpassSaltedpass = SaltValue.concat(NewpassEncryptpass);
				document.getElementById('Newpassword').value = NewpassSaltedpass;
				

			//Confirm Password Encryption

				var ConfpassValue = document.getElementById('confpass').value;
				var ConfpassEncrypt = sha512(ConfpassValue);
				var ConfpassSalted = SaltValue.concat(ConfpassEncrypt);
				document.getElementById('confpass').value = ConfpassSalted;
				document.getElementById('hiddenSaltvalue').value = '';
			
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
			$("#".concat(field_id)).click(function(){$("#error_size_".concat(field_id)).hide().text; $("#").removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}
	
		
		if (ext_type_array.lastIndexOf(get_file_ext) == -1){
			
			$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#error_type_".concat(field_id)).hide().text;$("#").removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
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


	// function for whitespace and blank value validation by Pravin 10-07-2017
	function check_whitespace_validation_textbox(field_value){
		
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);	
		// change validation rule for whitespace after and before word by pravin 04-08-2017
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 50 characters allowed';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value != ""){
	
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
	
	
	// function for whitespace and blank value validation by pravin 10-07-2017
	function check_whitespace_validation_textarea(field_value){
		
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 500 characters allowed';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value != ""){

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
		
			if(update_field_value > 0){	
				return true;
			}else{
				return {result: false, error_message: error_message1};
			}
		}
		
	}
	
	
	// function for number validation by pravin 10-07-2017
	function check_number_validation(field_value){

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
	
	
	// function for email validation by pravin 10-07-2017
	function check_email_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'Please enter valid email address like(abc@gmail.com)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/) == null){
		
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
	function check_aadhar_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and 12 digit numeric value required like(526548547512)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^(?=.*[0-9])[0-9]{12}$/g) == null){

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
	function check_number_with_decimal_two_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 2 decimal point allowed';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^\d{1,25}(\.\d{1,2})?$/) == null){

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
	function check_number_with_decimal_four_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 4 decimal point allowed';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^\d{1,25}(\.\d{1,4})?$/) == null){
	
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
	function check_mobile_number_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and 10 digit numeric value required like(9638527412)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^(?=.*[0-9])[0-9]{10}$/g) == null){
		
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{	
				return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message1};
		}				
		
		return true;
	}
	
	
	// function for landline number validation by pravin 10-07-2017
	function check_landline_number_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and Min. 6 and Max. 12 digit numeric value allowed like(071222656880)';
		var error_message2 = 'Please Remove blank space before and after the text';
		
		if(field_value.match(/^(?=.*[0-9])[0-9]{6,12}$/g) == null){
			
			// change validation rule for whitespace after and before word by pravin 04-08-2017
			if(update_field_value > 0)
			{	
				return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message1};
		}				
		
		return true;
	}
	
	
	// function for postal code number validation by pravin 10-07-2017
	function check_postal_code_validation(field_value){

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
	
	
	// function for blank file upload validation by pravin 10-07-2017
	function check_file_upload_validation(field_value){

		var error_message = 'Please upload the required file';
		
		if(field_value == "")
		{
			return {result: false, error_message:error_message };
		}				
		
		return true;
	}
	
	
	// function for drop_down validation by pravin 10-07-2017
	function check_drop_down_validation(field_value){

		var error_message = 'Please select the required valid option';
		
		if(field_value == "")
		{
			return {result: false, error_message:error_message};
		}				
		
		return true;
	}
	
	
	// function for radio button validation by pravin 10-07-2017
	function check_radio_button_validation(field_value){

		var error_message = 'Please select the option';
		
		if($('input[name="data['+field_value+']"]:checked').val() != "yes" && $('input[name="data['+field_value+']"]:checked').val() != "no"){
			
			return {result: false, error_message:error_message};
		}
		
		return true;
	}
	
	
	// function for radio value validation by pravin 10-07-2017
	function check_radio_value(field_value){
		
		 if($('input[name="data['+field_value+']"]:checked').val() == "yes"){
					
			return 'yes';
		
		}else if($('input[name="data['+field_value+']"]:checked').val() == "no"){

			return 'no';
							
		}
		
	}
	
	
	
	// This function used to find "renewal date due" vaild or not for old application
	// Done By pravin 03/10/2017
	
	function check_renewal_date_due(grant_date){
		
		var certification_type = $('#certification_type').val();
		var current_date = new Date();
		var grant_date = grant_date.split("/");
		var get_grant_month = grant_date[1];
		var get_grant_year = grant_date[2];
		
		if(certification_type == 1){
			
			if(get_grant_month <= 3)
			{
				var valid_upto_year =  parseInt(get_grant_year)+ parseInt(4);
			}else{
				var valid_upto_year =  parseInt(get_grant_year)+ parseInt(5);
			}
			//for one month grace peroid by Amol on 25-01-2019
			//changed date logic, extended one month on validation, for CA early it was 31/3/ now it is 30/04/ on 25-01-2019 by Amol
			var valid_upto_date = '30/09/'+ valid_upto_year;//temp date extended to 30-09 for covid 19 on 15-09-2021 by Amol
			
		}else if(certification_type == 2){
			
			var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);
			//for one month grace peroid by Amol on 25-01-2019
			//changed date logic, extended one month on validation, for Printing early it was 31/12/ now it is 31/01/ on 25-01-2019 by Amol
			//also added +1 in year for printing because of last month of year, so grace month, year changed.on 25-01-2019 by Amol
			var valid_upto_year = parseInt(valid_upto_year)+ parseInt(1);
			var valid_upto_date = '31/01/'+ valid_upto_year;
			
		}else if(certification_type == 3){
			
			if(get_grant_month <= 6)
			{
				var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);
			}else{
				var valid_upto_year =  parseInt(get_grant_year)+ parseInt(2);
			}
			//for one month grace peroid by Amol on 25-01-2019
			//changed date logic, extended one month on validation, for Lab early it was 30/06/ now it is 31/07/ on 25-01-2019 by Amol
			var valid_upto_date = '31/07/'+ valid_upto_year;
			
		}
		
		var convert_valid_upto_date = valid_upto_date.split("/");
		var final_valid_upto_date = new Date(convert_valid_upto_date[2], convert_valid_upto_date[1] - 1, convert_valid_upto_date[0]);
		if( current_date > final_valid_upto_date ){
			
			return {renewal_date_due: "yes", application_expired_status: "yes"};
			
		}else{
			
			return {renewal_date_due: "no", application_expired_status: "no"};
		}
	}
	
	
	
	// This function used to validated renewal valid date with current date for old application
	// Done By pravin 04/10/2017
	function valid_last_renewal_date(last_renewal_date){
		
		var certification_type = $('#certification_type').val();
		var current_date = new Date();
		var get_grant_year = last_renewal_date;
		
		if(certification_type == 1){
			
			var valid_upto_year =  parseInt(get_grant_year)+ parseInt(5);
			//for one month grace peroid by Amol on 25-01-2019
			//changed date logic, extended one month on validation, for CA early it was 31/3/ now it is 30/04/ on 25-01-2019 by Amol
			var valid_upto_date = '30/09/'+ valid_upto_year;//temp date extended to 30-09 for covid 19 on 15-09-2021 by Amol
			
		}else if(certification_type == 2){
				
			var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);
			//for one month grace peroid by Amol on 25-01-2019
			//changed date logic, extended one month on validation, for Printing early it was 31/12/ now it is 31/01/ on 25-01-2019 by Amol
			//also added +1 in year for printing because of last month of year, so grace month, year changed.on 25-01-2019 by Amol
			var valid_upto_year = parseInt(valid_upto_year)+ parseInt(1);
			var valid_upto_date = '31/01/'+ valid_upto_year;
			
		}else if(certification_type == 3){
			
			
			var valid_upto_year =  parseInt(get_grant_year)+ parseInt(2);
			//for one month grace peroid by Amol on 25-01-2019
			//changed date logic, extended one month on validation, for Lab early it was 30/06/ now it is 31/07/ on 25-01-2019 by Amol				
			var valid_upto_date = '31/07/'+ valid_upto_year;
		}
		
		var convert_valid_upto_date = valid_upto_date.split("/");
		var final_valid_upto_date = new Date(convert_valid_upto_date[2], convert_valid_upto_date[1] - 1, convert_valid_upto_date[0]);
		if( current_date > final_valid_upto_date ){
			return {application_expired_status: "yes"};
		}else{
			return {application_expired_status: "no"};
		}
	}

// DISPLAY FORM RELATED ALERTS/MESSAGES IN NEW TEMPLATE
// By Aniket Ganvir dated 10th DEC 2020
function renderToast(theme, msgTxt) {

	$('#toast-msg-'+theme).html(msgTxt);
	$('#toast-msg-box-'+theme).fadeIn('slow');
	$('#toast-msg-box-'+theme).delay(3000).fadeOut('slow');

}