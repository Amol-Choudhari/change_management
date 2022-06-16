
	$(document).ready(function(){

		var path = window.location.pathname;
		var paths = path.split("/");
		var controller = paths[2];
		var action = paths[3];


		//The JS code is converted into the function to call in the JS code itself
		edit_function ();
		delete_function ();
		save_function ();
		add_function ();
		
		
		function edit_function (){

			$('.edit_directors_details_id').click(function(e) {
				e.preventDefault();

				var form_id = $('#form_section_id').val();
				var directors_details_id = $(this).attr('id');
				var form_data = $(form_id).serializeArray();
				
				form_data.push({name: "edit_directors_details_id",value: directors_details_id});

				$.ajax({
					type: "POST",
				   url: "../AjaxFunctions/editDirectorsDetailsId",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(response){
						
						$("#directors_details_table").html(response);
						edit_function ();
						delete_function ();
						save_function ();
						add_function ();
					}
				});
			});
		}

		
		function delete_function (){

			$('.delete_directors_details_id').click(function(e) {
				e.preventDefault();

				var form_id = $('#form_section_id').val();
				var directors_details_id = $(this).attr('id');
				var form_data = $(form_id).serializeArray();
				form_data.push({name: "delete_directors_details_id",value: directors_details_id});

				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/deleteDirectorsDetailsId",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(response){
						$("#directors_details_table").html(response);
						edit_function ();
						delete_function ();
						save_function ();
						add_function ();
					}
				});
			});
		}

	

		function save_function (){


			$('#save_directors_details').click(function(e) {
				e.preventDefault();

				var form_id = $('#form_section_id').val();
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
						url: "../AjaxFunctions/editDirectorsDetails_id",
						data: form_data,
						beforeSend: function (xhr) { // Add this line
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
						},
						success: function(response){

							$("#directors_details_table").html(response);
							$("#directors_details_table :input[type='text']").val('');
							edit_function ();
							delete_function ();
							save_function ();
							add_function ();
						}
					});
				}
			});


		}
	

		function add_function (){


			$('#add_directors_details').click(function(e) {
				e.preventDefault();

				var form_id = $('#form_section_id').val();
				var d_name = $('#d_name').val();
				var d_address = $('#d_address').val();

				var form_data = $(form_id).serializeArray();
				form_data.push(	{name: "d_name",value: d_name},
								{name: "d_address",value: d_address});

				if(validate_directors_details() == true){
					$.ajax({
						type: "POST",
						url: "../AjaxFunctions/addDirectorsDetails",
						data: form_data,
						beforeSend: function (xhr) { // Add this line
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
						},
						success: function(response){

							$("#directors_details_table").html(response);
							$("#directors_details_table :input[type='text']").val('');
							edit_function ();
							delete_function ();
							save_function ();
							add_function ();
						}
					});
				}
			});


		}




	});
