$("#tbl_registration_docs").change(function(){

    file_browse_onclick('tbl_registration_docs');
    return false;
});


$(document).ready(function(){
	
	var form_section_id = $('#form_section_id').val();

	add_function();
	edit_function();
	delete_function();
	save_function();	
	
	function add_function(){

			
		$('#add_tbl_details').click(function(e) { 
			e.preventDefault();
			
			
			var tbl_name = $('#tbl_name').val();
			var tbl_registered = $('input[name="data[tbl_registered]"]:checked').val();
			var tbl_registered_no = $('#tbl_registered_no').val();
			

			if(validate_tbl_details() == true){
				
				var tbl_registration_docs = $("#tbl_registration_docs")[0].files[0];
				var tbl_registration_docs_name = tbl_registration_docs.name;
				var tbl_registration_docs_size = tbl_registration_docs.size;
				var tbl_registration_docs_type = tbl_registration_docs.type;
				var tbl_registration_docs_tmp_name = $('#tbl_registration_docs').val();
				
				var form_data = $("#"+form_section_id).serializeArray();
				form_data.push(	{name: "tbl_name",value: tbl_name},
								{name: "tbl_registered",value: tbl_registered},
								{name: "tbl_registered_no",value: tbl_registered_no},
								{name: "tbl_registration_docs_tmp_name",value: tbl_registration_docs_tmp_name},
								{name: "tbl_registration_docs_name",value: tbl_registration_docs_name},
								{name: "tbl_registration_docs_size",value: tbl_registration_docs_size},
								{name: "tbl_registration_docs_type",value: tbl_registration_docs_type});
			
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/addTblDetails",
					data: form_data,  
									beforeSend: function (xhr) { // Add this line
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									}, 
					success: function(response){
						
						$("#tbls_table_view").html(response);
						$("#tbls_table_view :input[type='text']").val('');
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

		$('.edit_tbl_id').click(function(e) { 
			e.preventDefault();
			
			var tbl_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "edit_tbl_id",value: tbl_id});
			
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/editTblId",
				data:  form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#tbls_table_view").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
					
				}                             
			}); 
	
		});
	
	}


	function delete_function(){

		$('.delete_tbl_id').click(function(e) { 
			e.preventDefault();
			
			var tbl_id = $(this).attr('id');
			
			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "delete_tbl_id",value: tbl_id});
			
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/deleteTblId",
				data:  form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function(response){
					$("#tbls_table_view").html(response);
					add_function();
					edit_function();
					delete_function();
					save_function();
					
				}                             
			}); 
	
		});
	}


	function save_function(){

		$('#save_tbl_details').click(function(e) { 

			e.preventDefault();
			
			var edit_tbl_id = '';
			var save_tbl_id = $(this).attr('id');
			var tbl_name = $('#tbl_name').val();
			var tbl_registered = $('input[name="data[tbl_registered]"]:checked').val();
			var tbl_registered_no = $('#tbl_registered_no').val();
			
	
			if(validate_tbl_details() == true){
				
				var tbl_registration_docs = $("#tbl_registration_docs")[0].files[0];
				var tbl_registration_docs_name = tbl_registration_docs.name;
				var tbl_registration_docs_size = tbl_registration_docs.size;
				var tbl_registration_docs_type = tbl_registration_docs.type;
				var tbl_registration_docs_tmp_name = $('#tbl_registration_docs').val();
				
				
				var form_data = $("#"+form_section_id).serializeArray();
				form_data.push({name: "save_tbl_id",value: save_tbl_id},
								{name: "tbl_name",value: tbl_name},
								{name: "tbl_registered",value: tbl_registered},
								{name: "tbl_registered_no",value: tbl_registered_no},
								{name: "tbl_registration_docs_tmp_name",value: tbl_registration_docs_tmp_name},
								{name: "tbl_registration_docs_name",value: tbl_registration_docs_name},
								{name: "tbl_registration_docs_size",value: tbl_registration_docs_size},
								{name: "tbl_registration_docs_type",value: tbl_registration_docs_type},
								{name: "edit_tbl_id",value: edit_tbl_id}
								);
			
				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/editTblId",
					enctype: 'multipart/form-data',
					data:  form_data,  
									beforeSend: function (xhr) { // Add this line
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									}, 
					success: function(response){
						
						$("#tbls_table_view").html(response);
						$("#tbls_table_view :input[type='text']").val('');
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
