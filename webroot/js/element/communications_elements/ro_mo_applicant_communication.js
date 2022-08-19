    $("#edit_rb_comment_ul").change(function(){
        file_browse_onclick('edit_rb_comment_ul');
        return false;
    });

    $("#rb_comment_ul").change(function(){
        file_browse_onclick('rb_comment_ul');
        return false;
    });

    $("#edit_rr_comment_ul").change(function(){
        file_browse_onclick('edit_rr_comment_ul');
        return false;
    });

    $("#rr_comment_ul").change(function(){
        file_browse_onclick('rr_comment_ul');
        return false;
    });

    $("#edit_mo_comment_ul").change(function(){
        file_browse_onclick('edit_mo_comment_ul');
        return false;
    });

    $("#mo_comment_ul").change(function(){
        file_browse_onclick('mo_comment_ul');
        return false;
    });

    $('#save_edited_referred_back').click(function (e) { 
        if (comment_reply_ro_to_applicant_box_validation() == false) {
            e.preventDefault();
        }
    });

    $('#save_edited_ro_reply').click(function (e) { 
        if (comment_reply_box_validation() == false) {
            e.preventDefault();
        }
    });

    $('#ro_reply').click(function (e) { 
        if (comment_reply_box_validation() == false) {
            e.preventDefault();
        }
    });

    $('#save_edited_mo_comment').click(function (e) { 
        if (comment_reply_box_validation() == false) {
            e.preventDefault();
        }
    });

    $('#mo_referred_back').click(function (e) { 
        if (comment_reply_mo_to_ro_box_validation() == false) {
            e.preventDefault();
        }
    });

    $('#edit_rb_comment_ul').change(function() {
        $('#rb_comment_label').text('File Selected');
    });
    
    $('#edit_rr_comment_ul').change(function() {
        $('#rr_comment_label').text('File Selected');
    });

    $('#edit_mo_comment_ul').change(function() {
        $('#mo_comment_label').text('File Selected');
    });


	// This function is added for validation for MO - RO Communication Box - AKASH [19-08-2022]
	function comment_reply_mo_to_ro_box_validation(){
		
		var check_save_reply = $("#check_save_reply").val();
		
		var value_return = 'true';
		
		if(check_whitespace_validation_textarea(check_save_reply).result == false){
			
			$("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
			$("#check_save_reply").addClass("is-invalid");
			$("#check_save_reply").click(function(){$("#error_save_reply").hide().text; $("#check_save_reply").removeClass("is-invalid");});
			value_return = 'false';
		}
		
		if(value_return == 'false'){
				return false;
		}else{
				
			exit();
		}
			
	}




    //File validation common function
	//This function is called on file upload browse button to validate selected file
	function file_browse_onclick(field_id){

		var selected_file = $('#'.concat(field_id)).val();
		var ext_type_array = ["jpg" , "pdf"];
		var get_file_size = $('#'.concat(field_id))[0].files[0].size;
		var get_file_ext = selected_file.split(".");
		var validExt = get_file_ext.length-1;
		var value_return = 'true';

		get_file_ext = get_file_ext[get_file_ext.length-1].toLowerCase();

		if(get_file_size > 2097152){

			$("#error_size_".concat(field_id)).show().text("Please select file below 2mb");
			$("#error_size_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if(ext_type_array.lastIndexOf(get_file_ext) == -1){

			$("#error_type_".concat(field_id)).show().text("Please select file of jpg, pdf type only");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if (validExt != 1){

			$("#error_type_".concat(field_id)).show().text("Invalid file uploaded");
			$("#error_type_".concat(field_id)).addClass("is-invalid");
			$("#".concat(field_id)).click(function(){$("#".concat(field_id)).hide().text;$("#".concat(field_id)).removeClass("is-invalid");});
			$('#'.concat(field_id)).val('');
			value_return = 'false';
		}

		if(value_return == 'false'){
			return false;
		}else{
			exit();
		}

	}
