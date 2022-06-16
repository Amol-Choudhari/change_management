$(document).ready(function(){

	add_function();
	edit_function();
	delete_function();
	save_function();

	function add_function(){

		$('#add_const_oils_details').click(function(e) {
			e.preventDefault();
	
	
			var oil_name = $('#oil_name').val();
			var mill_name_address = $('#mill_name_address').val();
			var quantity_procured = $('#quantity_procured').val();
	
			var form_data = $("#firm_form").serializeArray();
				form_data.push(	{name: "oil_name",value: oil_name},
								{name: "mill_name_address",value: mill_name_address},
								{name: "quantity_procured",value: quantity_procured});
	
			if(validate_const_oil_details() == true){
				$.ajax({
					type: "POST",
					url: "../authprocessedoldapp/add_const_oils_details",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function(response){
	
						$("#const_oils_table").html(response);
						$("#const_oils_table :input[type='text']").val('');
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

		$('.edit_const_oils_id').click(function(e) {
			e.preventDefault();
	
			var const_oils_id = $(this).attr('id');
	
			var form_data = $("#firm_form").serializeArray();
				form_data.push(	{name: "edit_const_oils_id",value: const_oils_id});
	
			$.ajax({
				type: "POST",
				url: "../authprocessedoldapp/edit_const_oils_id",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#const_oils_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
				}
			});
	
		});
	
	}

	function delete_function(){

		$('.delete_const_oils_id').click(function(e) {
			e.preventDefault();
	
			var const_oils_id = $(this).attr('id');
	
			var form_data = $("#firm_form").serializeArray();
				form_data.push(	{name: "delete_const_oils_id",value: const_oils_id});
	
			$.ajax({
				type: "POST",
				url: "../authprocessedoldapp/delete_const_oils_id",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#const_oils_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
				}
			});
	
		});
	}


	function save_function(){

		$('#save_const_oils_details').click(function(e) {
			e.preventDefault();
	
			var edit_const_oils_id = '';
			var save_const_oils_id = $(this).attr('id');
			var oil_name = $('#oil_name').val();
			var mill_name_address = $('#mill_name_address').val();
			var quantity_procured = $('#quantity_procured').val();
	
			var form_data = $("#firm_form").serializeArray();
				form_data.push(	{name: "save_const_oils_id",value: save_const_oils_id},
								{name: "oil_name",value: oil_name},
								{name: "mill_name_address",value: mill_name_address},
								{name: "quantity_procured",value: quantity_procured},
								{name: "edit_const_oils_id",value: edit_const_oils_id});
	
			if(validate_const_oil_details() == true){
				$.ajax({
					type: "POST",
					url: "../authprocessedoldapp/edit_const_oils_id",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function(response){
	
						$("#const_oils_table").html(response);
						$("#const_oils_table :input[type='text']").val('');
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
