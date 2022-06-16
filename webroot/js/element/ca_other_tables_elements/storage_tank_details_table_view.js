
$(document).ready(function(){
	
	var form_section_id = $('#form_section_id').val();

	add_function();
	edit_function();
	delete_function();
	save_function();

	function add_function(){

		$('#add_storage_tank_details').click(function(e) { 
			e.preventDefault();
			
			
			var tank_no = $('#commodity_tank_no').val();
			var tank_shape = $('#commodity_tank_shape').val();
			var tank_size = $('#commodity_tank_size').val();
			var tank_capacity = $('#commodity_tank_capacity').val();
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push(	{name: "tank_no",value: tank_no},
							{name: "tank_shape",value: tank_shape},
							{name: "tank_size",value: tank_size},
							{name: "tank_capacity",value: tank_capacity});
			
			if(validate_commodity_storage_tank() == true){
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/addStorageTankDetails",
					data: form_data, 
									beforeSend: function (xhr) { // Add this line
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									}, 
					success: function(response){
						
						$("#commodity_storage_tank_table").html(response);
						 $("#commodity_storage_tank_table :input[type='text']").val('');
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

		$('.edit_storage_tank_id').click(function(e) { 
			e.preventDefault();
			
			var tank_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "edit_storage_tank_id",value: tank_id});
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/editStorageTankId",
				data: form_data, 
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#commodity_storage_tank_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
				}                             
			}); 
	
		});
		
	}


	function delete_function(){

		$('.delete_storage_tank_id').click(function(e) { 
			e.preventDefault();
			
			var tank_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "delete_storage_tank_id",value: tank_id});
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/deleteStorageTankId",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#commodity_storage_tank_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
				}                             
			}); 
	
		});

	}

	function save_function(){

		$('#save_storage_tank_details').click(function(e) { 
			e.preventDefault();
			
			var edit_storage_tank_id = '';
			var save_storage_tank_id = $(this).attr('id');
			var tank_no = $('#commodity_tank_no').val();
			var tank_shape = $('#commodity_tank_shape').val();
			var tank_size = $('#commodity_tank_size').val();
			var tank_capacity = $('#commodity_tank_capacity').val();
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "save_storage_tank_id",value: save_storage_tank_id},
							{name: "tank_no",value: tank_no},
							{name: "tank_shape",value: tank_shape},
							{name: "tank_size",value: tank_size},
							{name: "tank_capacity",value: tank_capacity},
							{name: "edit_storage_tank_id",value: edit_storage_tank_id});
			
			
			if(validate_commodity_storage_tank() == true){
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/editStorageTankId",
					data: form_data,   
									beforeSend: function (xhr) { // Add this line
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									}, 
					success: function(response){
						
						$("#commodity_storage_tank_table").html(response);
						$("#commodity_storage_tank_table :input[type='text']").val('');
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
