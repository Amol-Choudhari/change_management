
	var show_final_report_btn = $('#show_final_report_btn').val();
	var show_final_granted_btn = $('#show_final_granted_btn').val();
	var show_accept_btn = $('#show_accept_btn').val();
	var show_forward_to_ho_btn = $('#show_forward_to_ho_btn').val();
	var report_edit_mode = $('#report_edit_mode').val();
	var current_level = $('#current_level').val();
	var validation_function = $('#validation_function').val();
	var section_form_id = $('#section_form_id').val();

	//The value is splitted to get the validataion function string name. which is getting from the Datbase.
	//The spliting of value is used for the function name string so its can be use as function.
	//Added by the Akash P Thakre on 27-10-2021

	var splitValue = validation_function.split('()'); 
	var section_form_id = $('#section_form_id').val();

	//The Splitted Value is i.e "whatever validation function name stored in the database".
	//converting the string to a pointer by window[<method name>].
	//And the it can be use as variable with function ().

	var validationFunctionString = splitValue[0];
	var validations = window[validationFunctionString];


	$('#view_save_btn').click(function (e) { 
	           

	    if(validations() == false){
	        e.preventDefault();

	    }else{

	        section_form_id.submit();
	    }
	});
    

	if(show_final_report_btn == 'no'){
		
		$('#final_report_btn').hide();
	}
	
	if(show_final_granted_btn == 'No' || current_level == 'level_2'){

  		$('#final_granted_btn').hide();
	}

	if(show_accept_btn == 'No' || current_level == 'level_2'){
		
		$("#accepted").hide();
	}

	if(show_forward_to_ho_btn == 'No' || current_level == 'level_2'){
  	
	  $('#accepted_forward_btn').hide();	
	}

	if(report_edit_mode == 'No'){

		$( document ).ready(function() {
			
			$('#view_save_btn').hide();
			$('#final_report_btn').hide();
				
		});
	}





