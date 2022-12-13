
	//This function is used for add_user, edit_user & user_profile form validations(admin users)
	function add_user_validations(){

		var f_name=$("#f_name").val();
		var l_name=$("#l_name").val();
		var email=$("#email").val();
		var phone=$("#phone").val();
		var landline_phone=$("#landline_phone").val();
		var profile_pic=$("#profile_pic").val();//added on 06-05-2021 for profile pic
		var value_return = 'true';

		//split path to find controller and action
		var path = window.location.pathname;
		var paths = path.split("/");
		var controller = paths[2];
		var action = paths[3];

		if(f_name==""){

			$("#error_f_name").show().text("Please Enter First Name");
			$("#f_name").addClass("is-invalid");
			$("#f_name").click(function(){$("#error_f_name").hide().text;$("#f_name").removeClass("is-invalid");});
			value_return = 'false';

		}

		if(l_name==""){

			$("#error_l_name").show().text("Please Enter Last Name");
			$("#l_name").addClass("is-invalid");
			$("#l_name").click(function(){$("#error_l_name").hide().text;$("#l_name").removeClass("is-invalid");});
			value_return = 'false';

		}


		if(email==""){

			$("#error_email").show().text("Please Enter Email Address");
			$("#email").addClass("is-invalid");
			$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
			value_return = 'false';

		}else{

			if(email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){}else{

				$("#error_email").show().text("Please Enter Valid Email Address");
				$("#email").addClass("is-invalid");
				$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
				value_return = 'false';
			}

		}

		/*if(once_card_no==""){

				$("#error_aadhar_card_no").show().text("Should not be blank, Only numbers allowed, max & min length is 12");
				$("#error_aadhar_card_no").css({"color":"red","font-size":"11px","font-weight":"500","text-align":"right"});
				$("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text;});

				value_return = 'false';

			}else{

				if(once_card_no.match(/^(?=.*[0-9])[0-9]{12}$/g) || once_card_no.match(/^[X-X]{8}[0-9]{4}$/i)){}else{//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol

					$("#error_aadhar_card_no").show().text("Should not be blank, Only numbers allowed, max & min length is 12");
					$("#error_aadhar_card_no").css({"color":"red","font-size":"11px","font-weight":"500","text-align":"right"});
					$("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text;});

					value_return = 'false';
				}

			}
			*/

		if(phone==""){

			$("#error_phone").show().text("Should not be blank, Only numbers allowed, max & min length is 10");
			$("#phone").addClass("is-invalid");
			$("#phone").click(function(){$("#error_phone").hide().text;	$("#phone").removeClass("is-invalid");});

			value_return = 'false';

		}else{

			if(action == 'add_user'){

				if(phone.match(/^(?=.*[0-9])[0-9]{10}$/g)){}else{//also allow if 6 X $ 4 nos found //added on 12-10-2017 by Amol

					$("#error_phone").show().text("Should not be blank, Only numbers allowed, max & min length is 10");
					$("#phone").addClass("is-invalid");
					$("#phone").click(function(){$("#error_phone").hide().text;	$("#phone").removeClass("is-invalid");});
					value_return = 'false';
				}

				//first valid no. for mob.no, applid on 16-02-2021 by Amol
				var validfirstno = ['7','8','9'];
				//get first character of mobile no.
				var f_m_no = phone.charAt(0);
				if($.inArray(f_m_no,validfirstno) != -1){
					//valid
				}else{
					$("#error_phone").show().text("Invalid mobile number");
					$("#phone").addClass("is-invalid");
					$("#phone").click(function(){$("#error_phone").hide().text;	$("#phone").removeClass("is-invalid");});
					value_return='false';
				}
			}else{

				if(phone.match(/^(?=.*[0-9])[0-9]{10}$/g) || phone.match(/^[X-X]{6}[0-9]{4}$/i)){}else{//also allow if 6 X $ 4 nos found //added on 12-10-2017 by Amol

					$("#error_phone").show().text("Should not be blank, Only numbers allowed, max & min length is 10");
					$("#phone").addClass("is-invalid");
					$("#phone").click(function(){$("#error_phone").hide().text;	$("#phone").removeClass("is-invalid");});
					value_return = 'false';
				}

			}

		}


		if(landline_phone==""){

			// Comment on 21/11/2017 by pravin to avoid blank landline no restriction

			/*$("#error_landline_phone").show().text("Should not be blank, Only numbers allowed, max length is 12");
			$("#error_landline_phone").css({"color":"red","font-size":"11px","font-weight":"500","text-align":"right"});
			$("#landline_phone").click(function(){$("#error_landline_phone").hide().text;});

			value_return = 'false';*/

		}else{

			if(landline_phone.match(/^(?=.*[0-9])[0-9]{6,12}$/g)){}else{

				$("#error_landline_phone").show().text("Should not be blank, Only numbers allowed, max length is 12");
				$("#landline_phone").addClass("is-invalid");
				$("#landline_phone").click(function(){$("#error_landline_phone").hide().text;	$("#landline_phone").removeClass("is-invalid");});
				value_return = 'false';
			}
			//validate landline no, pattern, not to contain string '00000', on 18-02-2021 by Amol
			if(landline_phone.indexOf('00000') > -1){

				$("#error_landline_phone").show().text("Given phone no. is not valid");
				$("#landline_phone").addClass("is-invalid");
				$("#landline_phone").click(function(){$("#error_landline_phone").hide().text;	$("#landline_phone").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//to validate lims role in LIMS selected
		if($('#division-lmis').is(":checked")){

			if($('#lmis_role').val()==''){

				$("#error_lmis_role").show().text("Mandatory to Select role if user belongs to LIMS");
				$("#lmis_role").addClass("is-invalid");
				$("#lmis_role").click(function(){$("#error_lmis_role").hide().text;	$("#lmis_role").removeClass("is-invalid");});
				value_return = 'false';
			}

		}
		//added on 10-08-2018 to validate lims role in BOTH with LIMS selected
		if($('#division-both').is(":checked") && $('#user_belongs_to-lmis').is(":checked")){

			if($('#lmis_role').val()==''){

				$("#error_lmis_role").show().text("Mandatory to Select role if user belongs to LIMS");
				$("#lmis_role").addClass("is-invalid");
				$("#lmis_role").click(function(){$("#error_lmis_role").hide().text;	$("#lmis_role").removeClass("is-invalid");});
				value_return = 'false';
			}

		}


		//this check is only first time add user
		if(action == 'add_user'){

			//commented on 11-04-2018 by Amol
			/*if($('#aadhar_auth_check').prop("checked") == false){

				$("#error_aadhar_auth_check").show().text("Please check to confirm Aadhar authentication");
				$("#aadhar_auth_check").addClass("is-invalid");
				$("#aadhar_auth_check").click(function(){$("#error_aadhar_auth_check").hide().text;	$("#aadhar_auth_check").removeClass("is-invalid");});
				value_return = 'false';
			}
			*/
		}

		//added on 06-05-2021 for profile pic
		//commented as it is not mandatory every time
		/*if(profile_pic==""){

			$("#error_profile_pic").show().text("Please select profile picture.");
			$("#profile_pic").addClass("is-invalid")
			$("#profile_pic").click(function(){$("#error_profile_pic").hide().text;	$("#profile_pic").removeClass("is-invalid");});
			value_return = 'false';
		}*/


		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}

	}


	
	//This function is used for applicant login validations.
	function login_customer_validations(){

		var customer_id=$("#customer_id").val();
		var password=$("#passwordValidation").val();
		var captchacode=$("#captchacode").val();
		var value_return = 'true';

		if(customer_id==""){

			$("#error_customer_id").show().text("Please enter your Applicant id.");
			$("#customer_id").addClass("is-invalid");
			$("#customer_id").click(function(){$("#error_customer_id").hide().text;	$("#customer_id").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(password==""){

			$("#error_password").show().text("Please enter your password.");
			$("#passwordValidation").addClass("is-invalid");
			$("#passwordValidation").click(function(){$("#error_password").hide().text;	$("#passwordValidation").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(captchacode==""){

			$("#error_captchacode").show().text("Please enter Captcha code");
			$("#captchacode").addClass("is-invalid");
			$("#captchacode").click(function(){$("#error_captchacode").hide().text;	$("#captchacode").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(value_return == 'false'){

			var msg = "Please Check Some Fields are Missing or not Proper.";
			renderToast('error', msg);
			return false;

		}else{

			var PasswordValue = document.getElementById('passwordValidation').value;
			var SaltValue = document.getElementById('hiddenSaltvalue').value;
			var EncryptPass = sha512(PasswordValue);
			var SaltedPass = SaltValue.concat(EncryptPass);
			var Saltedsha512pass = sha512(SaltedPass);
			document.getElementById('passwordValidation').value = Saltedsha512pass;
			exit();

		}

	}



	//This function is used for Chemist login validations. added on 07-09-2021 by AKASH
	function login_chemist_validations(){

		var chemist_id=$("#chemist_id").val();
		var password=$("#passwordValidation").val();
		var captchacode=$("#captchacode").val();
		var value_return = 'true';

		if(chemist_id==""){

			$("#error_chemist_id").show().text("Please enter your Chemist id.");
			$("#chemist_id").addClass("is-invalid");
			$("#chemist_id").click(function(){$("#error_chemist_id").hide().text;$("#chemist_id").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(password==""){

			$("#error_password").show().text("Please enter your password.");
			$("#passwordValidation").addClass("is-invalid");
			$("#passwordValidation").click(function(){$("#error_password").hide().text;$("#passwordValidation").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(captchacode==""){

			$("#error_captchacode").show().text("Please enter Captcha code");
			$("#captchacode").addClass("is-invalid");
			$("#captchacode").click(function(){$("#error_captchacode").hide().text;$("#captchacode").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(value_return == 'false'){

			var msg = "Please Check Some Fields are Missing or not Proper.";
			renderToast('error', msg);
			return false;

		}else{

			var PasswordValue = document.getElementById('passwordValidation').value;
			var SaltValue = document.getElementById('hiddenSaltvalue').value;
			var EncryptPass = sha512(PasswordValue);
			var SaltedPass = SaltValue.concat(EncryptPass);
			var Saltedsha512pass = sha512(SaltedPass);
			document.getElementById('passwordValidation').value = Saltedsha512pass;
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
		var validExt = get_file_ext.length-1;
		var value_return = 'true';

		get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();

		if(get_file_size > 2097152){

			$("#error_size_".concat(field_id)).show().text("Please select file below 2mb");
			$("#error_size_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if(ext_type_array.lastIndexOf(get_file_ext) == -1){

			$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if (validExt != 1){

			$("#error_type_".concat(field_id)).show().text("Invalid file uploaded");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}

	}


	//this function is used for add page, edit page validations
	function add_edit_pages_validation(){

		var title = $('#title').val();
		var publish_date = $('#publish_date').val();
		var archive_date = $('#archive_date').val();
		var meta_keyword = $('#meta_keyword').val();
		var meta_description = $('#meta_description').val();
		
		//var english_content =tinyMCE.get('english_content').getContent().replace(/<[^>]*>/gi, '').length;
		
		var value_return = 'true';

		if(title == ''){

			$("#error_title").show().text("Please enter page title");
			$("#title").addClass("is-invalid");
			$("#title").click(function(){$("#error_title").hide().text;$("#title").removeClass("is-invalid");});
			value_return = 'false';
	 	}

	 	
		//this code applied by Pravin on 02-08-2017 before date comparision
		var from = $("#publish_date").val().split("/");
		var publish_date_match = new Date(from[2], from[1] - 1, from[0]);

		var from = $("#archive_date").val().split("/");
		var archive_date_match = new Date(from[2], from[1] - 1, from[0]);

		if(publish_date_match > archive_date_match){

			$("#error_archive_date").show().text("Archive date can not be less than Publish date");
			$("#archive_date").addClass("is-invalid");
			$("#archive_date").click(function(){$("#error_archive_date").hide().text;$("#archive_date").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(publish_date == ''){

			$("#error_publish_date").show().text("Publish date can not be empty");
			$("#publish_date").addClass("is-invalid");
			$("#publish_date").click(function(){$("#error_publish_date").hide().text;$("#publish_date").removeClass("is-invalid");});
			value_return = 'false';

		}

		if(archive_date == ''){

			$("#error_archive_date").show().text("Archive date can not be empty");
			$("#archive_date").addClass("is-invalid");
			$("#archive_date").click(function(){$("#error_archive_date").hide().text;$("#archive_date").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(meta_keyword == ''){

			$("#error_meta_keyword").show().text("Please enter page meta keyword");
			$("#meta_keyword").addClass("is-invalid");
			$("#meta_keyword").click(function(){$("#error_meta_keyword").hide().text;$("#meta_keyword").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(meta_description == ''){

			$("#error_meta_description").show().text("Please enter page meta description");
			$("#meta_description").addClass("is-invalid");
			$("#meta_description").click(function(){$("#error_meta_description").hide().text;$("#meta_description").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(value_return == 'false'){

			var msg = "Please Check Some Fields are Missing or not Proper.";
			renderToast('error', msg);
			return false;
		
		}else{

			//	exit();
		}

	}



	//this function is used for add menu, edit menu validations
	function add_edit_menus_validation(){

		var title = $('#title').val();
		var link_type = $('#link_type').val();
		var external_link = $('#external_link').val();
		var link_id = $('#link_id').val();
		var position = $('#position').val();
		var order_id = $('#order_id').val();

		var value_return = 'true';


		if(title == ''){

			$("#error_title").show().text("Please enter Menu name");
			$("#title").addClass("is-invalid");
			$("#title").click(function(){$("#error_title").hide().text;$("#title").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(order_id == ''){

			$("#error_order").show().text("Please enter menu order no");
			$("#order_id").addClass("is-invalid");
			$("#order_id").click(function(){$("#error_order").hide().text;$("#order_id").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(!$('#link_type-page').is(":checked") && !$('#link_type-external').is(":checked")){

			$("#error_link_type").show().text("Please check Menu type");
			$("#link_type-page").addClass("is-invalid");
			$("#link_type-page").click(function(){$("#error_link_type").hide().text;$("#link_type-page").removeClass("is-invalid");});
			value_return = 'false';

		}else{

			if($('#link_type-external').is(":checked")){

				if(external_link == ''){

					$("#error_external_link").show().text("Please enter external link");
                    $("#external_link").addClass("is-invalid");
                    $("#external_link").click(function(){$("#error_external_link").hide().text;$("#external_link").removeClass("is-invalid");});
					value_return = 'false';

				}
			}

			if($('#link_type-page').is(":checked")){

				if(link_id == ''){

					$("#error_link_id").show().text("Please select page from list");
                    $("#link_id").addClass("is-invalid");
                    $("#link_id").click(function(){$("#error_link_id").hide().text;$("#link_id").removeClass("is-invalid");});
					value_return = 'false';

				}
			}

		}

		if(!$('#position-side').is(":checked") && !$('#position-bottom').is(":checked")){

			$("#error_position").show().text("Please check menu position");
            $("#position-side").addClass("is-invalid");
            $("#position-side").click(function(){$("#error_position").hide().text;$("#position-side").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(value_return == 'false'){
			var msg = "Please Check Some Fields are Missing or not Proper.";
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

		var value_return = 'true';


		if(newpass==""){

			$("#error_newpass").show().text("Please enter your new password.");
            $("#Newpassword").addClass("is-invalid");
            $("#Newpassword").click(function(){$("#error_newpass").hide().text;$("#Newpassword").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(confpass==""){

			$("#error_confpass").show().text("Please confirm your new password.");
            $("#confpass").addClass("is-invalid");
            $("#confpass").click(function(){$("#error_confpass").hide().text;$("#confpass").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(captchacode==""){

			$("#error_captchacode").show().text("Please enter your verification code.");
            $("#captchacode").addClass("is-invalid");
            $("#captchacode").click(function(){$("#error_captchacode").hide().text;$("#captchacode").removeClass("is-invalid");});
			value_return = 'false';
		}


		//added this condition on 10-02-2021 by Amol
		var user_id = $("#user_id").val();
		if(newpass==user_id){

			$.alert('Please Note: You can not use your User Id as your password');
			$("#Newpassword").val('');//clear field
			$("#confpass").val('');
			value_return = 'false';
		}


		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{

			//new password encription
			var NewpasswordValue = document.getElementById('Newpassword').value;

			if(NewpasswordValue.match(/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{7,15}$/g)){

			}else{

				$.alert('Password length should be min. 8 char, min. 1 number, min. 1 Special char. and min. 1 Capital Letter');
				return false;
			}


			var SaltValue = document.getElementById('hiddenSaltvalue').value;
			var NewpassEncryptpass = sha512(NewpasswordValue);
			var NewpassSaltedpass = SaltValue.concat(NewpassEncryptpass);
			document.getElementById('Newpassword').value = NewpassSaltedpass;

			//Confirm password encription
			var ConfpassValue = document.getElementById('confpass').value;
			var ConfpassEncrypt = sha512(ConfpassValue);
			var ConfpassSalted = SaltValue.concat(ConfpassEncrypt);
			document.getElementById('confpass').value = ConfpassSalted;
			document.getElementById('hiddenSaltvalue').value = '';
			exit();

		}


	}


	//this function is used for masters fields validations
	function masters_validation(masterId){
	
		//split path to find controller and action
		var path = window.location.pathname;
		var paths = path.split("/");
		var controller = paths[2];
		var action = paths[3];
		var value_return = 'true';

		//For State
		if(masterId == '1'){

			var state_name = $('#state_name').val();

			if(state_name == ''){

				$("#error_state_name").show().text("Please enter state name");
                $("#state_name").addClass("is-invalid");
                $("#state_name").click(function(){$("#error_state_name").hide().text;$("#state_name").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//For District
		if(masterId == '2'){

			var state_list = $('#state_list').val();
			var ro_offices_list = $('#ro_offices_list').val();
			var so_offices_list = $('#so_offices_list').val();
			var district_name = $('#district_name').val();

			if(state_list == ''){

				$("#error_state_list").show().text("Please select state from list");
                $("#state_list").addClass("is-invalid");
                $("#state_list").click(function(){$("#error_state_list").hide().text;$("#state_list").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(ro_offices_list == ''){

				$("#error_ro_offices_list").show().text("Please select RO office");
                $("#ro_offices_list").addClass("is-invalid");
                $("#ro_offices_list").click(function(){$("#error_ro_offices_list").hide().text;$("#ro_offices_list").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(district_name == ''){

				$("#error_district_name").show().text("Please enter district name");
                $("#district_name").addClass("is-invalid");
                $("#district_name").click(function(){$("#error_district_name").hide().text;$("#district_name").removeClass("is-invalid");});
				value_return = 'false';
			}

			if($('#dist_office_type-so').is(":checked")){

				if(so_offices_list == ''){

					$("#error_so_offices_list").show().text("Please select SO office");
                    $("#state_list").addClass("is-invalid");
                    $("#state_list").click(function(){$("#error_so_offices_list").hide().text;$("#state_list").removeClass("is-invalid");});
					value_return = 'false';
				}
			}
		}

		//For Buisness Type
		if(masterId == '3'){

			var business_type = $('#business_type').val();

			if(business_type == ''){

				$("#error_business_type").show().text("Please enter business type");
                $("#business_type").addClass("is-invalid");
                $("#business_type").click(function(){$("#error_business_type").hide().text;$("#business_type").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//For Packing Type
		if(masterId == '4'){

			var packing_type = $('#packing_type').val();

			if(packing_type == ''){

				$("#error_packing_type").show().text("Please enter packing type");
                $("#packing_type").addClass("is-invalid");
                $("#packing_type").click(function(){$("#error_packing_type").hide().text;$("#packing_type").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//For Laboratory
		if(masterId == '5'){

			var laboratory_type = $('#laboratory_type').val();

			if(laboratory_type == ''){

				$("#error_laboratory_type").show().text("Please enter laboratory type");
                $("#laboratory_type").addClass("is-invalid");
                $("#laboratory_type").click(function(){$("#error_laboratory_type").hide().text;$("#laboratory_type").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//For Machine
		if(masterId == '6'){

			var machine_type = $('#machine_types').val();

			if(machine_type == ''){

				$("#error_machine_type").show().text("Please enter Machine type");
                $("#machine_types").addClass("is-invalid");
                $("#machine_types").click(function(){$("#error_machine_type").hide().text;$("#machine_types").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//For Tank Shapes
		if(masterId == '7'){

			var tank_shape = $('#tank_shapes').val();

			if(tank_shape == ''){

				$("#error_tank_shape").show().text("Please enter Tank Shape");
                $("#tank_shapes").addClass("is-invalid");
                $("#tank_shapes").click(function(){$("#error_tank_shape").hide().text;$("#tank_shapes").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

        //For Charges
        if(masterId == '8'){

            var application_type = $('#application_type').val();
            var charge = $('#charge').val();
			var application_type_id = $('#application_type_id').val();				
			var firm_type = $('#firm_type').val();

            if(application_type == ''){

                $("#error_application_type").show().text("Please enter Application Type Details");
                $("#application_type").addClass("is-invalid");
                $("#application_type").click(function(){$("#error_application_type").hide().text;$("#application_type").removeClass("is-invalid");});
                value_return = 'false';
            }

            if(charge == ''){

                $("#error_charge").show().text("Please enter charges");
                $("#charge").addClass("is-invalid");
                $("#charge").click(function(){$("#error_charge").hide().text;$("#charge").removeClass("is-invalid");});
                value_return = 'false';
            }
 
			// This below code is added for the application_type_id & firm_type fields for validate by Akash on 09-04-2022

            if(application_type_id == ''){

                $("#error_application_type_id").show().text("Please enter application type id");
                $("#application_type_id").addClass("is-invalid");
                $("#application_type_id").click(function(){$("#error_application_type_id").hide().text;$("#application_type_id").removeClass("is-invalid");});
                value_return = 'false';
            }

            if(firm_type == ''){

                $("#error_firm_type").show().text("Please enter firm type");
                $("#firm_type").addClass("is-invalid");
                $("#firm_type").click(function(){$("#error_firm_type").hide().text;$("#firm_type").removeClass("is-invalid");});
                value_return = 'false';
            }
        }


		//For Buisness Years
		if(masterId == '9'){

			if($("#business_years").val() == ''){

				$("#error_business_year").show().text("Please Enter value here");
                $("#business_years").addClass("is-invalid");
                $("#business_years").click(function(){$("#error_business_year").hide().text;$("#business_years").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


		// Apply empty validation for "other feedback type", Done by pravin 30-08-2018
	/*	if(action=='add_feedback_type' || action=='edit_feedback_type'){

			var title = $('#title').val();

			if(title == ''){

				$("#error_title").show().text("Please enter feedback type");
                $("#title").addClass("is-invalid");
                $("#title").click(function(){$("#error_title").hide().text;$("#title").removeClass("is-invalid");});
				value_return = 'false';
			}
		}*/

		//For RO Offices
		if(masterId == '10'){

			var ro_office = $('#ro_office').val();
			var ro_office_address = $('#ro_office_address').val();
			var ro_email_id = atob($('#ro_email_id').val());//for email encoding
			var ro_office_phone = $('#ro_office_phone').val();
			var short_code = $('#short_code').val();
			var ral_email_id = atob($('#ral_email_id').val());//for email encoding
			var replica_code = $('#replica_code').val();

			if(ro_office == ''){

				$("#error_ro_office").show().text("Please enter office name");
                $("#ro_office").addClass("is-invalid");
                $("#ro_office").click(function(){$("#error_ro_office").hide().text;$("#ro_office").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(ro_office_address == ''){

				$("#error_ro_office_address").show().text("Please enter office address");
                $("#ro_office_address").addClass("is-invalid");
                $("#ro_office_address").click(function(){$("#error_ro_office_address").hide().text;$("#ro_office_address").removeClass("is-invalid");});
				value_return = 'false';
			}

			if($('#office_type-ro').is(":checked")){
				//this condition added on 26-07-2018 by Amol
				if(ro_email_id == ''){

					$("#error_ro_email_id").show().text("Please enter office email id");
                    $("#ro_email_id").addClass("is-invalid");
                    $("#ro_email_id").click(function(){$("#error_ro_email_id").hide().text;$("#ro_email_id").removeClass("is-invalid");});
					value_return = 'false';

				}else{

					if(!ro_email_id.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){

						$("#error_ro_email_id").show().text("Please enter valid email id");
                        $("#ro_email_id").addClass("is-invalid");
                        $("#ro_email_id").click(function(){$("#error_ro_email_id").hide().text;$("#ro_email_id").removeClass("is-invalid");});
						value_return = 'false';
					}
				}

			}else if($('#office_type-ral').is(":checked")){

				//this condition added on 26-07-2018 by Amol
				//below code added on 26-07-2018 to validate RAL email id
				if(ral_email_id == ''){

					$("#error_ral_email_id").show().text("Please enter office email id");
                    $("#ral_email_id").addClass("is-invalid");
                    $("#ral_email_id").click(function(){$("#error_ral_email_id").hide().text;$("#ral_email_id").removeClass("is-invalid");});
					value_return = 'false';

				}else{

					if(!ral_email_id.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){

						$("#error_ral_email_id").show().text("Please enter valid email id");
                        $("#ral_email_id").addClass("is-invalid");
                        $("#ral_email_id").click(function(){$("#error_ral_email_id").hide().text;$("#ral_email_id").removeClass("is-invalid");});
						value_return = 'false';
					}
				}
			}


			if(ro_office_phone == ''){

				$("#error_ro_office_phone").show().text("Please enter office phone no.");
                $("#ro_office_phone").addClass("is-invalid");
                $("#ro_office_phone").click(function(){$("#error_ro_office_phone").hide().text;$("#ro_office_phone").removeClass("is-invalid");});
				value_return = 'false';

			}else{

				if(!ro_office_phone.match(/^(?=.*[0-9])[0-9]{6,12}$/g)){

					$("#error_ro_office_phone").show().text("Phone no. is not valid, min. 6 & max. 12 nos. allowed");
                    $("#ro_office_phone").addClass("is-invalid");
                    $("#ro_office_phone").click(function(){$("#error_ro_office_phone").hide().text;$("#ro_office_phone").removeClass("is-invalid");});
					value_return = 'false';
				}

	   		 	//validate landline no, pattern, not to contain string '00000', on 18-02-2021 by Amol
				if(ro_office_phone.indexOf('00000') > -1){

					$("#error_ro_office_phone").show().text("Given phone no. is not valid");
                    $("#ro_office_phone").addClass("is-invalid");
                    $("#ro_office_phone").click(function(){$("#error_ro_office_phone").hide().text;$("#ro_office_phone").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

			if($('#office_type-ro').is(":checked") || $('#office_type-so').is(":checked")){
				//this condition added on 26-07-2018 by Amol
				if(short_code == ''){

					$("#error_short_code").show().text("Please enter district short code");
                    $("#short_code").addClass("is-invalid");
                    $("#short_code").click(function(){$("#error_short_code").hide().text;$("#short_code").removeClass("is-invalid");});
					value_return = 'false';
				}
			
				if (replica_code == '') {

					$("#error_replica_code").show().text("Please enter Office Code for 15-Digit Code . Enter only single alphabet like 'a' or 'A' ")
					$("#replica_code").addClass("is-invalid");
					$("#replica_code").click(function(){$("#error_replica_code").hide().text;$("#replica_code").removeClass("is-invalid");});
					value_return = 'false';
				}
			
			}
		
			

		}


		//For SMS/Email Templates
		if(masterId == '11'){

			var sms_message = $("#sms_message").val();
			var email_message = $("#email_message").val();
			var email_subject = $("#email_subject").val();
			var description = $("#description").val();


			if(sms_message=="" && email_message==""){

				$("#error_sms_message").show().text("Please enter Either SMS message or Email Message. Both Can't leaft blank.");
                $("#sms_message").addClass("is-invalid");
                $("#sms_message").click(function(){$("#error_sms_message").hide().text;$("#sms_message").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(email_message!=""){

				if(email_subject==""){

					$("#error_email_subject").show().text("Please enter Email Subject.");
                    $("#email_subject").addClass("is-invalid");
                    $("#email_subject").click(function(){$("#error_email_subject").hide().text;$("#email_subject").removeClass("is-invalid");});
					value_return = 'false';
				}
			}

			if(description==""){

				$("#error_description").show().text("Please Enter Short Description for the Message Template.");
                $("#description").addClass("is-invalid");
                $("#description").click(function(){$("#error_description").hide().text;$("#description").removeClass("is-invalid");});
				value_return = 'false';
			}

			if( $('#applicant').prop('checked') == false &&
                $('#mo_smo').prop('checked') == false &&
                $('#io').prop('checked') == false &&
			    $('#ro_so').prop('checked') == false &&
                $('#dy_ama').prop('checked') == false &&
                $('#jt_ama').prop('checked') == false &&
			    $('#ho_mo_smo').prop('checked') == false &&
                $('#ama').prop('checked') == false &&
                $('#accounts').prop('checked') == false &&
                $('#ro_incharge').prop('checked') == false &&
                $('#chemist_user').prop('checked') == false &&
                $('#inward_officer').prop('checked') == false &&
                $('#ral_cal_oic').prop('checked') == false &&
                $('#chemist').prop('checked') == false &&
                $('#chief_chemist').prop('checked') == false &&
                $('#lab_incharge').prop('checked') == false &&
                $('#dol').prop('checked') == false &&
                $('#inward_clerk').prop('checked') == false &&
                $('#outward_clerk').prop('checked') == false &&
                $('#ro_so_officer').prop('checked') == false &&
                $('#ro_so_oic').prop('checked') == false &&
                $('#accounts').prop('checked') == false &&
                $('#head_office').prop('checked') == false
            ) {

				$("#error_send_to").show().text("Sorry.. Atleast one option should be checked.");
				//$("#state_list").addClass("is-invalid");
				value_return = 'false';
			}

		}

        //For Feedback Type
        if (masterId == '15') {

            var title = $('#title').val();

            if (title == '') {

                $("#error_title").show().text("Please Enter Feedback type");
                $("#title").addClass("is-invalid");
                $("#title").click(function(){$("#error_title").hide().text;$("#title").removeClass("is-invalid");});
                value_return = 'false';
            }
        }

		//For Replica
		if(masterId == '16'){
		
			var commodity_category_code = $('#commodity_category').val();
			var commodity_code = $('#commodity').val();
			var replica_charges_details = $('#replica_charges').val();
			var minimum_quantity = $('#min_qty').val();
			var unit_details = $('#unit').val();
			var replica_code_details = $('#replica_code').val();
			
			if(commodity_category_code == ''){

				$("#error_commodity_category").show().text("Please Select Category");
                $("#commodity_category").addClass("is-invalid");
                $("#commodity_category").click(function(){$("#error_commodity_category").hide().text;$("#commodity_category").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(commodity_code == ''){

				$("#error_commodity").show().text("Please Select Commodity");
                $("#commodity").addClass("is-invalid");
                $("#commodity").click(function(){$("#error_commodity").hide().text;$("#commodity").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(replica_charges_details == ''){
					
				$("#error_replica_charges").show().text("Please Enter Charges");
                $("#replica_charges").addClass("is-invalid");
                $("#replica_charges").click(function(){$("#error_replica_charges").hide().text;$("#replica_charges").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(minimum_quantity == ''){

				$("#error_min_qty").show().text("Please enter Quantity");
                $("#min_qty").addClass("is-invalid");
                $("#min_qty").click(function(){$("#error_min_qty").hide().text;$("#min_qty").removeClass("is-invalid");});
				value_return = 'false';
			}

			if(unit_details == '0'){

				$("#error_unit").show().text("Please Select Unit");
                $("#unit").addClass("is-invalid");
                $("#unit").click(function(){$("#error_unit").hide().text;$("#unit").removeClass("is-invalid");});
				value_return = 'false';
			}
		
			if(replica_code_details == ''){
			
				$("#error_replica_code").show().text("Please Select Code 15-Digit for Commodity");
                $("#replica_code").addClass("is-invalid");
                $("#replica_code").click(function(){$("#error_replica_code").hide().text;$("#replica_code").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		//For Education
		if (masterId == '17') {

			var education_type = $('#education_type').val();

			if (education_type == '') {

				$("#error_education_type").show().text("Please Enter Education");
                $("#education_type").addClass("is-invalid");
                $("#education_type").click(function(){$("#error_education_type").hide().text;$("#education_type").removeClass("is-invalid");});
				value_return = 'false';
			}

		}

		//For Divsion
		if (masterId == '18') {

			var division_type = $('#division_type').val();

			if (division_type == '') {

				$("#error_division_type").show().text("Please Enter Division");
                $("#division_type").addClass("is-invalid");
                $("#division_type").click(function(){$("#error_division_type").hide().text;$("#division_type").removeClass("is-invalid");});
				value_return = 'false';
			}
		}


        if(value_return == 'false'){
            var msg = "Please check some fields are missing or not proper.";
            renderToast('error', msg);
            return false;
        }else{

			if (masterId == '11') {
				return true;
			} else {
				
				exit();
			}
		}
	}



	// Validate parameters that are used in sms message, if it is in pre-defined parameter list. (Done By Pravin 09-03-2018)
	function sms_message_parameter_validation(masterId=null){

		var form_validation = masters_validation(masterId);
		//alert(form_validation);
		if(form_validation == true){

			var sms_parameter_list = ['submission_date','firm_name','amount','commodities','applicant_name','applicant_mobile_no','company_id',
									  'certificate_valid_upto','premises_id','firm_email','firm_certification_type','ro_name','ro_mobile_no','ro_office','ro_email_id',
									  'mo_name','mo_mobile_no','mo_office','mo_email_id','io_name','io_mobile_no','io_office','io_email_id','dyama_name','dyama_mobile_no',
                                      'dyama_email_id','jtama_name','jtama_mobile_no','jtama_email_id','ama_name','ama_mobile_no','ama_email_id','io_scheduled_date','applicant_email',
									  'home_link','pao_name','pao_mobile_no','pao_email_id','ho_mo_name','ho_mo_mobile_no','ho_mo_email_id','chemist_user','inward_officer_name',
                                      'ral_cal_oic_name','chemist_name','chief_chemist_name','lab_incharge_name','dol_name','inward_clerk_name','outward_clerk_name',
                                      'ro_so_officer_name','ro_so_oic_name','account_ddo_name','head_office_name','ro_incharge_name','sample_code','sample_registration_date',
                                      'source_user_role','destination_user_role','source_officer','destination_officer','replica_commodities','chemist_id','source_office',
									  'destination_office','application_type','packer_name','dummy','dst_user','sample_code','sample_flow','src_user','dst_usr_role','dst_office',
									  'src_usr_role','src_office','ral','oic','customer_name','cal_ral']

			var sms_message = $('#sms_message').val();
			
			var total_occurrences = substr_count(sms_message,'%%');
			var parameter_not_inarray = '';

			while (total_occurrences > 0) {
				//var matches = sms_message.substr(sms_message.indexOf('%%')+1);
				var matches = sms_message.split("%%");

				if(matches[1]){

					var result = inArray(matches[1], sms_parameter_list);

					if(result == false){
						var parameter_not_inarray = parameter_not_inarray + '%%'+matches[1]+'%%' +', ';
					}
					var replace_value = '%%'+matches[1]+'%%';
					var sms_message = sms_message.replace(replace_value, matches[1]);
					var total_occurrences = substr_count(sms_message,'%%');

				}
			}

			if(parameter_not_inarray){

				$("#error_sms_message").show().text("The parameter "+parameter_not_inarray+" is/are not defined. You can only use the pre-defined parameters.");
                $("#sms_message").addClass("is-invalid");
                $("#sms_message").click(function(){$("#error_sms_message").hide().text;$("#sms_message").removeClass("is-invalid");});
				return false;

			}else{ exit(); }

		}else{
			return false;
		}

	}


	//SubString Count Method
	function substr_count(string,substring,start,length){

		var c = 0;
		if(start) {
			string = string.substr(start);
		}

		if(length) {
			string = string.substr(0,length);
		}

		for(var i=0;i<string.length;i++){

			if(substring == string.substr(i,substring.length))
			c++;
		}

		return c;
	}


	function inArray(str_value, str_array) {

		var length = str_array.length;

		for(var i = 0; i < length; i++) {

			if(str_array[i] == str_value) return true;
		}

		return false;
	}


	// DISPLAY FORM RELATED ALERTS/MESSAGES IN NEW TEMPLATE
	// By Aniket Ganvir dated 10th DEC 2020
	function renderToast(theme, msgTxt) {

		$('#toast-msg-'+theme).html(msgTxt);
		$('#toast-msg-box-'+theme).fadeIn('slow');
		$('#toast-msg-box-'+theme).delay(3000).fadeOut('slow');

	}


	$(document).ready(function() {

			$('.mod').fadeIn('slow');

			//$("#msgboxidcon").click(function(){
            $(document).on('click','#msgboxidcon',function (){

                console.log('hello');
					$('.mod').fadeOut('slow');
					var redirectto = $("#msgboxredi").val();

					setTimeout(function(){
						window.location = redirectto;
					}, 0000);

			});
	})
