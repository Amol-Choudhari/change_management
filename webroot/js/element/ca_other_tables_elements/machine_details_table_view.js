//// js FILE for the machine details
	
	
	
	$(document).ready(function(){
		
    	var form_section_id = $('#form_section_id').val();

		add_function();
		edit_function();
        delete_function();
		save_function();
			
			
		// ADD FUNCTION 
		// Description : this is used to on the click of add button 
		// @AUTHOR : AKASH THAKRE 
		// DATE : 24-03-2022 (M)	
		
		function add_function() {
			
			$('#add_machine_details').click(function(e) {
				
				e.preventDefault();
				
                var machine_name = $('#machine_name').val();
                var machine_type = $('#machine_type').val();
                var machine_no = $('#machine_no').val();
                var machine_capacity = $('#machine_capacity').val();
                var form_data = $("#"+form_section_id).serializeArray();
				
                form_data.push({name: "machine_name",value: machine_name},
								{name: "machine_type",value: machine_type},
								{name: "machine_no",value: machine_no},
								{name: "machine_capacity",value: machine_capacity}
				);

                if(validate_machinery_details() == true){

					$.ajax({
						type: "POST",
						url: "../AjaxFunctions/add_machine_details",
						data: form_data,
						beforeSend: function (xhr) { // Add this line
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
						},
						success: function(response){

							$("#machinery_table").html(response);
							$("#machinery_table :input[type='text']").val('');
							
							add_function();
							edit_function();
							delete_function();
							save_function();
						}
					});
                }
       	 	});
		}

		
		
		// EDIT FUNCTION 
		// Description : this is used to on the click of EDIT button 
		// @AUTHOR : AKASH THAKRE 
		// DATE : 24-03-2022 (M)	

		function edit_function(){

			$('.edit_machine_id').click(function(e) {
				
				e.preventDefault();
				
				var machine_id = $(this).attr('id');
				var form_data = $("#"+form_section_id).serializeArray();
				
				form_data.push({name: "edit_machine_id",value: machine_id});
	
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/edit_machine_id",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(response){
						
						$("#machinery_table").html(response);
						
						add_function();
						edit_function();
						delete_function();
						save_function();
					}
				});
			});
		}
		
		
		

		// DELETE FUNCTION 
		// Description : this is used to on the click of DELETE button 
		// @AUTHOR : AKASH THAKRE 
		// DATE : 24-03-2022 (M)	
		
		function delete_function() {

			$('.delete_machine_id').click(function(e) {
				
				e.preventDefault();

				var machine_id = $(this).attr('id');
				var form_data = $("#"+form_section_id).serializeArray();
				
				form_data.push({name: "delete_machine_id",value: machine_id});
	
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/delete_machine_id",
					data:  form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(response){
						
						$("#machinery_table").html(response);
						
						add_function();
						edit_function();
						delete_function();
						save_function();
					}
				});
			});
		}
		
		
		
		// SAVE FUNCTION 
		// Description : this is used to on the click of SAVE button 
		// @AUTHOR : AKASH THAKRE 
		// DATE : 24-03-2022 (M)	

		function save_function() {

			
			$('#save_machine_details').click(function(e) {
				
				e.preventDefault();

                var edit_machine_id = '';
                var save_machine_id = $(this).attr('id');
                var machine_name = $('#machine_name').val();
                var machine_type = $('#machine_type').val();
                var machine_no = $('#machine_no').val();
                var machine_capacity = $('#machine_capacity').val();
                var form_data = $("#"+form_section_id).serializeArray();
				
                form_data.push({name: "save_machine_id",value: save_machine_id},
                                                {name: "machine_name",value: machine_name},
                                                {name: "machine_type",value: machine_type},
                                                {name: "machine_no",value: machine_no},
                                                {name: "machine_capacity",value: machine_capacity},
                                                {name: "edit_machine_id",value: edit_machine_id}
				);

                if(validate_machinery_details() == true){

					$.ajax({
						type: "POST",
						url: "../AjaxFunctions/edit_machine_id",
						data: form_data,
						beforeSend: function (xhr) { // Add this line
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
						},
						success: function(response){

							$("#machinery_table").html(response);
							$("#machinery_table :input[type='text']").val('');
							
							add_function();
							edit_function();
							delete_function();
							save_function();
						}
					});
                }
        	});
		}

    });

	
	
	//function to check empty fields of machinery details table on add/edit button
	function validate_machinery_details(){

		var machine_name = $('#machine_name').val();
		var machine_type  = $('#machine_type').val();
		var machine_no  = $('#machine_no').val();
		var machine_capacity  = $('#machine_capacity').val();
		var value_return = 'true';

		// Change Condition for validation and error message by pravin 12-07-2017
		if(check_whitespace_validation_textbox(machine_name).result == false){

			$("#error_machine_name").show().text("Please enter Machine name.");
			setTimeout(function(){ $("#error_machine_name").fadeOut();},5000);
			$('#machine_name').addClass("is-invalid");
			$("#machine_name").click(function(){$("#error_machine_name").hide().text; $("#machine_name").removeClass("is-invalid");});

			value_return = 'false';
		}

		if(machine_type==""){

			$("#error_machine_type").show().text("Please Select Machine type");
			setTimeout(function(){ $("#error_machine_type").fadeOut();},5000);
			$('#machine_type').addClass("is-invalid");
			$("#machine_type").click(function(){$("#error_machine_type").hide().text; $("#machine_type").removeClass("is-invalid");});

			value_return = 'false';
		}

		// Change Condition for validation and error message by pravin 12-07-2017
		if(check_whitespace_validation_textbox(machine_no).result == false){

			$("#error_machine_no").show().text("Please enter Machine No.");
			// $("#error_machine_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			setTimeout(function(){ $("#error_machine_no").fadeOut();},5000);
			$('#machine_no').addClass("is-invalid");
			$("#machine_no").click(function(){$("#error_machine_no").hide().text; $("#machine_no").removeClass("is-invalid");});

			value_return = 'false';
		}

		// Change Condition for validation and error message by pravin 12-07-2017
		if(check_number_with_decimal_two_validation(machine_capacity).result == false){

			$("#error_machine_capacity").show().text("Please enter Machine capacity");
			// $("#error_machine_capacity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			setTimeout(function(){ $("#error_machine_capacity").fadeOut();},5000);
			$('#machine_capacity').addClass("is-invalid");
			$("#machine_capacity").click(function(){$("#error_machine_capacity").hide().text; $("#machine_capacity").removeClass("is-invalid");});

			value_return = 'false';
		}


		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		
		} else {
			return true;
		}
	}


	// function for number with decimal two validation by pravin 12-07-2017
	function check_number_with_decimal_two_validation(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 2 decimal point allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^\d{1,25}(\.\d{1,2})?$/) == null){

			if(update_field_value > 0){

				return {result: false, error_message: error_message1};
			}

			return {result: false, error_message: error_message2};
		}

		return true;
	}


	// function for whitespace and blank value validation by pravin 12-07-2017
	function check_whitespace_validation_textbox(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
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
			
			return {result: false, error_message: error_message2};
		
		}else{
			
			return {result: false, error_message: error_message1};
		}

	}






