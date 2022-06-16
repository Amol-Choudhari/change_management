
$("#edit_sms_template_btn").click(function(e){

	if(sms_message_parameter_validation('.$masterId.') ==false){
		e.preventDefault();
	}else{
		$("#add_firm_form").submit();
	}

});
	$(document).ready(function(){

		//for Roles list

			$("#dmi_roles").hide();
			$("#lmis_roles").hide();

			//for already checked

			if($('#template_for-dmi').is(":checked")){

						$("#dmi_roles").show();
						$("#lmis_roles").hide();

			}else if($('#template_for-lmis').is(":checked")){

					$("#lmis_roles").show();
					$("#dmi_roles").hide();

			}



			//for on clicked

			$('#template_for-dmi').click(function(){

				$("#dmi_roles").show();
				$("#lmis_roles").hide();

			});

			$('#template_for-lmis').click(function(){

				$("#lmis_roles").show();
				$("#dmi_roles").hide();

			});


	});
