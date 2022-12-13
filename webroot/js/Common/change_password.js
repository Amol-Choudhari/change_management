//// CHANGE PASSWORD VALIDATIONS JS FILE
	
	
	$(".submit_btn").click(function(e){

		if(change_password_validations()==false){
			e.preventDefault();
		}else{
			$("#change_password").submit();
		}

	});



	//Validation to check the existing password by AKASH on 31-12-2021
	$('#Oldpassword').focusout(function (e) { 
	  
		var Oldpassword = $("#Oldpassword").val();

		if (Oldpassword != '') {
			
			$.ajax({
				type : 'POST',
				url : '../AjaxFunctions/check_old_password',
				async : true,
				data : {Oldpassword:Oldpassword},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success : function(response){
		
					if($.trim(response)=='yes'){
		
						$.alert({
							title: "Alert!",
							content: 'The Old Password is Not Matched!!',
							typeAnimated: true,
							buttons: {
								Retry: {
									text: 'Retry',
									btnClass: 'btn-red',
									action: function(){
										$("#Oldpassword").val('');
									}
								},
							}
						});
					}
				}
			});
		}
	}); 


	//FOR CHECKING THE Password & CONFIRM Passwordd ARE SAME OR NOT ON 31-12-2021 BY AKASH
	$('#confpass').focusout(function(){

		var NewPassword = $("#Newpassword").val();
		var ConfirmedPassword = $('#confpass').val();
		if (NewPassword != '') {
			
			if (NewPassword != ConfirmedPassword) {
				$.alert('Confirm Password not matched!!');
				$('#confpass').val('');
			}
		}

	});


	//This function is used for change password input validations.
	function change_password_validations(){

		// Empty Field validation
		var oldpass=$("#Oldpassword").val();
		var newpass=$("#Newpassword").val();
		var confpass=$("#confpass").val();
		var user_id = $("#user_id").val();
		var value_return = 'true';

		if(oldpass==""){

			$("#error_oldpass").show().text("Please enter your old password.");
			$("#Oldpassword").addClass("is-invalid");
			$("#Oldpassword").click(function(){$("#error_oldpass").hide().text;$("#Oldpassword").removeClass("is-invalid");});
			value_return = 'false';
		}

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

			//old password encription
			var OldpasswordValue = document.getElementById('Oldpassword').value;
			var SaltValue = document.getElementById('hiddenSaltvalue').value;
			var OldpassEncryptpass = sha512(OldpasswordValue);
			var OldpassSaltedpass = SaltValue.concat(OldpassEncryptpass);
			var OldpassSaltedsha512pass = sha512(OldpassSaltedpass);
			document.getElementById('Oldpassword').value = OldpassSaltedsha512pass;

			//new password encription
			var NewpasswordValue = document.getElementById('Newpassword').value;
			
			if(NewpasswordValue.match(/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%^&*]{7,15}$/g)){
					//alert('Password matched to the pattern');
			}else{

				$.alert('Password length should be min. 8 char, min. 1 number, min. 1 Special char. and min. 1 Capital Letter');
				$("#Oldpassword").val('');
				$("#Newpassword").val('');
				$("#confpass").val('');
				return false;
			}

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
	
	// DISPLAY FORM RELATED ALERTS/MESSAGES IN NEW TEMPLATE
	// By Aniket Ganvir dated 10th DEC 2020
	function renderToast(theme, msgTxt) {

		$('#toast-msg-'+theme).html(msgTxt);
		$('#toast-msg-box-'+theme).fadeIn('slow');
		$('#toast-msg-box-'+theme).delay(3000).fadeOut('slow');

	}