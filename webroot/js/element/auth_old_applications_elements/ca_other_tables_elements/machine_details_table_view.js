$(document).ready(function(){

	add_function();
	edit_function();
	delete_function();
	save_function();

	function add_function(){

		$('#add_machine_details').click(function(e) {
			e.preventDefault();
	
	
			var machine_name = $('#machine_name').val();
			var machine_type = $('#machine_type').val();
			var machine_no = $('#machine_no').val();
			var machine_capacity = $('#machine_capacity').val();
	
			var form_data = $("#machinery_form").serializeArray();
			form_data.push({name: "machine_name",value: machine_name},
							{name: "machine_type",value: machine_type},
							{name: "machine_no",value: machine_no},
							{name: "machine_capacity",value: machine_capacity});
	
			if(validate_machinery_details() == true){
				$.ajax({
					type: "POST",
					url: "../authprocessedoldapp/auth_old_application_add_machine_details",
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


	function edit_function(){

		$('.edit_machine_id').click(function(e) {
			e.preventDefault();
	
			var machine_id = $(this).attr('id');
	
			var form_data = $("#machinery_form").serializeArray();
			form_data.push({name: "edit_machine_id",value: machine_id});
	
			$.ajax({
				type: "POST",
				url: "../authprocessedoldapp/auth_old_application_edit_machine_id",
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


	function delete_function(){

		$('.delete_machine_id').click(function(e) {
			e.preventDefault();
	
			var machine_id = $(this).attr('id');
	
			var form_data = $("#machinery_form").serializeArray();
			form_data.push({name: "delete_machine_id",value: machine_id});
	
			$.ajax({
				type: "POST",
				url: "../authprocessedoldapp/auth_old_application_delete_machine_id",
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


	function save_function(){
	 
		$('#save_machine_details').click(function(e) {
			e.preventDefault();
	
			var edit_machine_id = '';
			var save_machine_id = $(this).attr('id');
			var machine_name = $('#machine_name').val();
			var machine_type = $('#machine_type').val();
			var machine_no = $('#machine_no').val();
			var machine_capacity = $('#machine_capacity').val();
	
			var form_data = $("#machinery_form").serializeArray();
			form_data.push({name: "save_machine_id",value: save_machine_id},
							{name: "machine_name",value: machine_name},
							{name: "machine_type",value: machine_type},
							{name: "machine_no",value: machine_no},
							{name: "machine_capacity",value: machine_capacity},
							{name: "edit_machine_id",value: edit_machine_id});
	
			if(validate_machinery_details() == true){
				$.ajax({
					type: "POST",
					url: "../authprocessedoldapp/auth_old_application_edit_machine_id",
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
