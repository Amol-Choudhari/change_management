
    $("#uploaded_files").dataTable({"order": []});





	$('#upload_btn').click(function(e) {

		var file_uploads = $('#file_uploads').val();
		var value_return = 'true';

		if(file_uploads==""){

			$("#error_file_uploads").show().text("Please Select File");
			$("#file_uploads").addClass("is-invalid");
			$("#file_uploads").click(function(){$("#error_file_uploads").hide().text;$("#file_uploads").removeClass("is-invalid");});
			value_return = 'false';

		}
			
		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}


	});
	

	$("#file_uploads").change(function(){

		dashboard_file_upload('file_uploads');
		return false;
	});
	
	
		$('.delete_file').click(function (e) { 

			if (confirm('Are You Sure Delete This File record ')) {
				////
			} else {
				return false;
				exit;
			}
		
		});

    //created this function on 21/09/2018 by Amol, with increased file size limit to 10mb
    //using this function only for this file, in file uploads.
	function dashboard_file_upload(field_id){

		var selected_file = $('#'.concat(field_id)).val();
		var ext_type_array = ["jpg" , "pdf",];
		var get_file_size = $('#'.concat(field_id))[0].files[0].size;
		var get_file_ext = selected_file.split(".");
		var file_uploads = $('#file_uploads').val();
		var value_return = 'true';

	


		get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();

			if(get_file_size > 10997152)
			{

				$("#error_size_".concat(field_id)).show().text("Please select file below 10mb");
                $("#error_size_".concat(field_id)).css({"color": "#721c24","background-color": "#f8d7da","border-color": "#f5c6cb","position":"relative","padding": ".45rem 1.25rem","margin-bottom": "1rem","border": "1px solid transparent","border-radius": ".25rem"});
				$("#".concat(field_id)).click(function(){$("#error_size_".concat(field_id)).hide().text;});
				$('#'.concat(field_id)).val('')

				value_return = 'false';

			}


			if (ext_type_array.lastIndexOf(get_file_ext) == -1){


				$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
				$("#error_type_".concat(field_id)).css({"color": "#721c24","background-color": "#f8d7da","border-color": "#f5c6cb","position":"relative","padding": ".45rem 1.25rem","margin-bottom": "1rem","border": "1px solid transparent","border-radius": ".25rem"});
				$("#".concat(field_id)).click(function(){$("#error_type_".concat(field_id)).hide().text;});
				$('#'.concat(field_id)).val('');

				value_return = 'false';

			}

		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}

	}
