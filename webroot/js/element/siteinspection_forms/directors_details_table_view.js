$(document).ready(function(){

	var form_id = '#'+$('#form_section_id').val();

    $('.edit_directors_details_id').click(function(e) {
		e.preventDefault();

		var directors_details_id = $(this).attr('id');

		var form_data = $(form_id).serializeArray();
		form_data.push({name: "edit_directors_details_id",value: directors_details_id});

        $.ajax({
            type: "POST",
            url: "../siteinspections/edit_directors_details_id",
            data: form_data,
            success: function(response){
                $("#directors_details_table").html(response);

            }
        });

    });


	 $('.delete_directors_details_id').click(function(e) {
		e.preventDefault();

		var directors_details_id = $(this).attr('id');

		var form_data = $(form_id).serializeArray();
		form_data.push({name: "delete_directors_details_id",value: directors_details_id});

        $.ajax({
            type: "POST",
            url: "../siteinspections/delete_directors_details_id",
            data: form_data,
            success: function(response){
                $("#directors_details_table").html(response);

            }
        });

    });


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
				url: "../siteinspections/edit_directors_details_id",
				data: form_data,
				success: function(response){

					$("#directors_details_table").html(response);
					$("#directors_details_table :input[type='text']").val('');

				}
			});
		}
    });


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
				url: "../siteinspections/add_directors_details",
				data: form_data,
				success: function(response){

					$("#directors_details_table").html(response);
					 $("#directors_details_table :input[type='text']").val('');


				}
			});
		}
    });


});
