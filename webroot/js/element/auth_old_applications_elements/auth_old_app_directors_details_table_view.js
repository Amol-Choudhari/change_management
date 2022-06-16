$(document).ready(function(){

	var path = window.location.pathname;
	var paths = path.split("/");
	var controller = paths[2];
	var action = paths[3];

	var form_id = '';


	if(action == 'printing_firm_profile')
	{
		form_id = '#printing_firm_form';

	}else if(action == 'laboratory_firm_profile')
	{
		form_id = '#add_laboratory_firm_form';

	}else if(action == 'firm_profile')
	{
		form_id = '#firm_form';
	}


	add_function();
	edit_function();
	delete_function();
	save_function();

	function add_function(){

		$('#add_directors_details').click(function(e) {
			e.preventDefault();
	
			var d_name = $('#d_name').val();
			var d_address = $('#d_address').val();
	
			var form_data = $(form_id).serializeArray();
			form_data.push(	{name: "d_name",value: d_name},
							{name: "d_address",value: d_address});
	
			if(validate_directors_details() == true){
				$.ajax({
					type: "POST",
					url: "auth_old_add_directors_details",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function(response){
	
						$("#directors_details_table").html(response);
						$("#directors_details_table :input[type='text']").val('');
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

		$('.edit_directors_details_id').click(function(e) {
			e.preventDefault();
	
			var directors_details_id = $(this).attr('id');
	
			var form_data = $(form_id).serializeArray();
			form_data.push({name: "edit_directors_details_id",value: directors_details_id});
	
			$.ajax({
				type: "POST",
			   url: "auth_old_edit_directors_details_id",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
	
					$("#directors_details_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
				}
			});
	
		});
	
	}


	function delete_function(){

		$('.delete_directors_details_id').click(function(e) {
			e.preventDefault();
	
			var directors_details_id = $(this).attr('id');
	
			var form_data = $(form_id).serializeArray();
			form_data.push({name: "delete_directors_details_id",value: directors_details_id});
	
			$.ajax({
				type: "POST",
				url: "auth_old_delete_directors_details_id",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#directors_details_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
				}
			});
	
		});
	
	}


	function save_function(){

		$('#save_directors_details').click(function(e) {
			e.preventDefault();
	
			var edit_directors_details_id = '';
			var save_directors_details_id = $(this).attr('id');
			var d_name = $('#d_name').val();
			var d_address = $('#d_address').val();
	
			var form_data = $(form_id).serializeArray();
			form_data.push({name: "save_directors_details_id",value: save_directors_details_id},
							{name: "d_name",value: d_name},
							{name: "d_address",value: d_address},
							{name: "edit_directors_details_id",value: edit_directors_details_id});
	
			if(validate_directors_details() == true){
				$.ajax({
					type: "POST",
					url: "auth_old_edit_directors_details_id",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function(response){
	
						$("#directors_details_table").html(response);
						$("#directors_details_table :input[type='text']").val('');
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
