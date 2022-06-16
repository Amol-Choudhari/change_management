
	var form_section_id = $('#form_section_id').val();

	$('#edit_io_reply').click(function(e) {
		e.preventDefault();

			var reply_max_id = $('#reply_max_id').val();

			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "reply_max_id",value: reply_max_id});

			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/editIoReply",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response){
					location.reload();
				}
			});
	});


	$('#delete_io_reply').click(function(e) {
		e.preventDefault();

			var reply_max_id = $('#reply_max_id').val();
			var model_name = $('#model_name').val();

			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "reply_max_id",value: reply_max_id},
						   {name: "model_name",value: model_name}
						  );
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/deleteIoReply",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response){
					location.reload();
				}
			});
	});


	$('#edit_referred_to_io_back').click(function(e) {
		e.preventDefault();

			var referred_back_max_id = $('#referred_back_max_id').val();

			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "referred_back_max_id",value: referred_back_max_id});

			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/editReferredToIoBack",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response){
					location.reload();
				}
			});
	});


	$('#delete_referred_to_io_back').click(function(e) {
		e.preventDefault();

			var referred_back_max_id = $('#referred_back_max_id').val();
			var model_name = $('#model_name').val();

			var form_data = $("#"+form_section_id).serializeArray();
			form_data.push({name: "referred_back_max_id",value: referred_back_max_id},
						   {name: "model_name",value: model_name}
						  );
			$.ajax({
				type: "POST",
				url: "../AjaxFunctions/deleteReferredToIoBack",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response){
					location.reload();
				}
			});
	});



	var current_level = $('#current_level').val();
	var section_form_details = $('#section_form_details').val();
	var final_submit_status = $('#final_submit_status_ir').val();
	
	if(final_submit_status == '' || final_submit_status == null){ final_submit_status = null; }

	if(current_level != 'level_2'){

		$("#form_inner_main :input").prop("disabled", true);
		$("#form_inner_main :input[type='radio']").prop("disabled", true);
		$("#form_inner_main :input[type='select']").prop("disabled", true);
		$("#form_inner_main :input[type='reset']").prop("disabled", true);
		$("#form_inner_main .glyphicon-edit").css('display','none');
		$("#form_inner_main .glyphicon-remove-sign").css('display','none');
		$("#form_inner_main :input[type='file']").css('display','none');
		$("#form_inner_main .file_limits").css('display','none');
		$("#form_inner_main .table_record_add_btn").css('display','none');
		$("#add_new_row_r").css('display','none');

    }




	if(current_level == 'level_2' && section_form_details != 'referred_back' && final_submit_status != null){

		$("#form_inner_main :input").prop("disabled", true);
		$("#form_inner_main :input[type='radio']").prop("disabled", true);
		$("#form_inner_main :input[type='select']").prop("disabled", true);
		$("#form_inner_main :input[type='reset']").prop("disabled", true);
		$("#form_inner_main .glyphicon-edit").css('display','none');
		$("#form_inner_main .glyphicon-remove-sign").css('display','none');
		$("#form_inner_main :input[type='file']").css('display','none');
		$("#form_inner_main .file_limits").css('display','none');
		$("#form_inner_main .table_record_add_btn").css('display','none');
		$("#add_new_row_r").css('display','none');

 }
	
