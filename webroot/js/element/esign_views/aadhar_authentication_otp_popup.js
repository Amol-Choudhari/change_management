	//below script is to show Enter OTP window popup
				
	// Get the <span> element that closes the modal
	var otp_span = document.getElementsByClassName("close")[0];
						
	var otp_modal = document.getElementById('otp_popup_box');

	// Get the button that opens the modal
	var register_btn = document.getElementById("register_btn");
	
	// When the user clicks on the button, open the modal
	register_btn.onclick = function() {
								
		//this condition added on 22-03-2018 to avoid mandatory Aadhar authenticaton
		var once_no = document.getElementById("once_card_no").value;
		if(once_no!=''){
			//check form validation on resister button first
			if(register_customer_validations() == false){										
				return false;										
			}
									
				$.ajax({
						type:'POST',
						async: true,
						cache: false,
						data:{once_no:once_no},
						url: "../esign/request_aadhar_otp",
						success: function(response){
							var token_session_id = response;
							$('#Token_key_id').val(token_session_id);
						}
					});
			
				document.getElementById('aadhar_otp').value = '';								
				otp_modal.style.display = "block";
		
				return false;
									
								
		}else{//this else added on 22-03-2018 to call validation function directly

			//check form validation on resister button first
			if(register_customer_validations() == false){										
				return false;										
			}
		}
	}


							
	$("#cancelotp").onclick = function() {
		otp_modal.style.display = "none";
		return false;
	}
							
							
	// When the user clicks on <span> (x), close the modal
	otp_span.onclick = function() {
		otp_modal.style.display = "none";
	}


	// When the user clicks anywhere outside of the modal, close it
	/*	window.onclick = function(event) {
		if (event.target == otp_modal) {
			otp_modal.style.display = "none";
		}
	} 
	*/
							
	$("#submitotp").focus(function(){
		
		var aadhar_otp = $("#aadhar_otp").val();
		
		if(aadhar_otp == ''){

			$("#error_aadhar_otp").show().text("Please enter OTP");
			$("#error_aadhar_otp").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"left"});
			setTimeout(function(){ $("#error_aadhar_otp").fadeOut();},5000);
			$("#aadhar_otp").click(function(){$("#error_aadhar_otp").hide().text;});
			
			return false;
		}

		//commented on 25-08-2018, now aadahr otp is taking directly from post data in authetication function	
		/*	$.ajax({
				type:'POST',
				async: true,
				cache: false,
				data: { aadhar_otp: aadhar_otp },
				url: "../esign/set_aadhar_otp_session",
				success: function(response){
					var token_session_id = response;
					$('#Token_key_id').val(token_session_id);
				}
				
			});
		*/
	});
							
							
							
							
							
	//to resend OTP request_aadhar_otp
	// When the user clicks on the button, open the modal
	$("#resend_otp").click(function() {
		
		$.ajax({
				type:'POST',
				async: true,
				cache: false,
				data:{once_no:once_no},
				url: "../esign/request_aadhar_otp",
				success: function(response){
					var token_session_id = response;
					$('#Token_key_id').val(token_session_id);
				}
			});
		
		document.getElementById('aadhar_otp').value = '';
		otp_modal.style.display = "block";
		return false;
	});
		