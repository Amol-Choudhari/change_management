var form_section_id = $('#form_section_id').val();

$('#edit_reply').click(function(e) {
	e.preventDefault();

		var reply_max_id = $('#reply_max_id').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "reply_max_id",value: reply_max_id});

		$.ajax({
			type: "POST",
			url: "edit_reply",
			data: form_data,
			success: function(response){
				location.reload();
			}
		});
});

$('#delete_reply').click(function(e) {
	e.preventDefault();

		var reply_max_id = $('#reply_max_id').val();
		var model_name = $('#model_name').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "reply_max_id",value: reply_max_id},
					   {name: "model_name",value: model_name}
					  );
		$.ajax({
			type: "POST",
			url: "delete_reply",
			data: form_data,
			success: function(response){
				location.reload();
			}
		});
});