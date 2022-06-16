$(document).ready(function(){
	
	var form_section_id = $('#form_section_id').val();

	add_function();
	edit_function();
	delete_function();
	save_function();

	function add_function(){
	
		$('#add_const_oils_tank_details').click(function(e) { 
			e.preventDefault();
			
			
			var tank_no = $('#const_oils_tank_no').val();
			var tank_shape = $('#const_oils_tank_shape').val();
			var tank_size = $('#const_oils_tank_size').val();
			var tank_capacity = $('#const_oils_tank_capacity').val();
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push(	{name: "tank_no",value: tank_no},
							{name: "tank_shape",value: tank_shape},
							{name: "tank_size",value: tank_size},
							{name: "tank_capacity",value: tank_capacity});
			
			if(validate_const_oil_tank() == true){
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/addConstOilsTankDetails",
					data: form_data,      
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function(response){
						
						$("#const_oil_storage_tank_table").html(response);
						$("#const_oil_storage_tank_table :input[type='text']").val('');
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

		$('.edit_const_oils_tank_id').click(function(e) { 
			e.preventDefault();
			
			var tank_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "edit_const_oils_tank_id",value: tank_id});
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/editConstOilsTankId",
				data: form_data,           
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#const_oil_storage_tank_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
					
				}                             
			}); 
	
		});

	}


	function delete_function(){

		$('.delete_const_oils_tank_id').click(function(e) { 

			e.preventDefault();
			
			var tank_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "delete_const_oils_tank_id",value: tank_id});
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/deleteConstOilsTankId",
				data: form_data,     
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#const_oil_storage_tank_table").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
					
				}                             
			}); 
	
		});

	}


	function save_function(){
	
		$('#save_const_oils_tank_details').click(function(e) { 
			e.preventDefault();
			
			var edit_const_oils_tank_id = '';
			var save_const_oils_tank_id = $(this).attr('id');
			var tank_no = $('#const_oils_tank_no').val();
			var tank_shape = $('#const_oils_tank_shape').val();
			var tank_size = $('#const_oils_tank_size').val();
			var tank_capacity = $('#const_oils_tank_capacity').val();
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "save_const_oils_tank_id",value: save_const_oils_tank_id},
							{name: "tank_no",value: tank_no},
							{name: "tank_shape",value: tank_shape},
							{name: "tank_size",value: tank_size},
							{name: "tank_capacity",value: tank_capacity},
							{name: "edit_const_oils_tank_id",value: edit_const_oils_tank_id});
			
			if(validate_const_oil_tank() == true){
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/editConstOilsTankId",
					data: form_data,      
									beforeSend: function (xhr) { // Add this line
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									}, 
					success: function(response){
						
						$("#const_oil_storage_tank_table").html(response);
						$("#const_oil_storage_tank_table :input[type='text']").val('');
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