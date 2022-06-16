//// PRIMARY PROFILE JS FILE FOR AUTH OLD PROCESSED APPLICATION	
	
	
	$('.onchangeGetDistrict').change(function(){ 
		get_district();
	});

	$("#upload_file").change(function(){
		file_browse_onclick('upload_file');
		return false;
	});


	$(".auth_call").click(function(e){
		
		if(auth_primary_reg_validations()==false){
			e.preventDefault();
		}else{
			$("#add_firm_form").submit();
		}
		
	});


	function get_district(){

		$("#district").find('option').remove();
		
		var state = $("#state").val();
		
		$.ajax({
			type: "POST",
			async:true,
			url:"../AjaxFunctions/show-district-dropdown",
			data: {state:state},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function (data) {
					$("#district").append(data);
			}
		});
	}

	$(document).ready(function(){


		$('#once_card_no').focusout(function(){

			var once_card_no = $('#once_card_no').val();

			if(once_card_no.match(/^(?=.*[0-9])[0-9]{12}$/g) || once_card_no.match(/^[X-X]{8}[0-9]{4}$/i)){//also allow if 8 X $ 4 nos found //added on 12-10-2017 by Amol

			}else{

				//alert("aadhar card number should be of 12 numbers only");
				$("#error_aadhar_card_no").show().text("Should not blank, Only numbers allowed, min & max length is 12");
				$("#error_aadhar_card_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text;});
				return false;
			}
		});
	});


	return_error_msg = $("#return_error_msg").val();  
	
	if(return_error_msg != ''){
		$.alert(return_error_msg);
		$('#add_firm_form').trigger("reset");
	}
	
	

