	var show_final_report_btn = $('#show_final_report_btn_report_buttons').val();
	var final_granted_btn = $('#final_granted_btn_report_buttons').val();
	var current_level = $('#current_level').val();
	var accept_btn = $('#accept_btn_report_buttons').val();
	var forward_to_btn = $('#forward_to_btn_report_buttons').val();
	var application_mode = $('#application_mode').val();
	var section_form_status = $('#section_form_status').val();//added on 03-11-2022 to show/hide Accepted btn

 	if(show_final_report_btn == 'yes'){
		
		$("#final_submit_btn").css('display','block'); 
	}

 	if(final_granted_btn == 'yes' && current_level == 'level_3'){

		$('#final_granted_btn').show(); 
		$('#referred_back').hide();
		$('#view_application').hide();
		$('#commentBoxIns').hide();
	}

	if(accept_btn == 'yes' && current_level == 'level_3'){ 

		$("#accepted").show();
	}

    if(forward_to_btn == '' || forward_to_btn == null){ forward_to_btn = null; }

	if(forward_to_btn != null && current_level == 'level_3'){

		$('#accepted_forward_btn').show();
	}

 	if(application_mode == 'view'){ 
	
		$( document ).ready(function() {			
			$("#form_inner_main :input").prop("disabled", true);
			$("#form_inner_main :input[type='radio']").prop("disabled", true);
			$("#form_inner_main :input[type='select']").prop("disabled", true);
			$("#form_inner_main :input[type='submit']").prop("disabled", true);
			$("#form_inner_main :input[type='reset']").prop("disabled", true);
			$("#form_inner_main :input[type='button']").prop("disabled", true);				
			$("#form_inner_main :input[type='submit']").hide();
			$(".glyphicon-edit").css('display','none');
			$(".glyphicon-remove-sign").css('display','none');
		});
 	}


	var validationFunction = $('#validationFunction').val();
    var splitValue = validationFunction.split('()'); 
    var section_form_id = $('#section_form_id').val();
    var validationFunctionString = splitValue[0];
    var validations = window[validationFunctionString];

    $('#save_btn').click(function (e) { 
               
		if(validations() == false){
			e.preventDefault();

		}else{

			section_form_id.submit();
		}
	});

	$('#referred_back').click(function (e){

		if(comment_reply_ro_to_applicant_box_validation() == false){
			e.preventDefault();
		}

	});
	
	//added on 03-11-2022 to show/hide Accepted btn
	if(section_form_status=='referred_back'){
		$("#accepted").hide();
	}