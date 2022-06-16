$("#edit_ir_comment_ul").change(function(){

	file_browse_onclick('edit_ir_comment_ul');
	return false;
});

$("#ir_comment_ul").change(function(){

	file_browse_onclick('ir_comment_ul');
	return false;
});

$("#edit_rb_comment_ul").change(function(){

	file_browse_onclick('edit_rb_comment_ul');
	return false;
});

$("#rb_comment_ul").change(function(){

	file_browse_onclick('rb_comment_ul');
	return false;
});


$("#save_edited_reply").click(function(e){

	if(comment_reply_box_validation()==false){
		e.preventDefault();
	}
});

$("#save_edited_referred_back").click(function(e){

	if(comment_reply_ro_to_applicant_box_validation()==false){
		e.preventDefault();
	}
});


	var show_final_report_btn = $('#show_final_report_btn').val(); 
	var accept_btn = $('#accept_btn_comm_win').val();
	var forward_to_btn = $('#forward_to_btn_comm_win').val();
	var final_granted_btn = $('#final_granted_btn_comm_win').val();

	if(forward_to_btn == '' || forward_to_btn == null){ forward_to_btn = null; }


	if(show_final_report_btn == 'no'){                                                
		$('#sent_to').hide();
	}

	if(accept_btn == 'yes'){
		$("#accepted").show();
	}

	if(forward_to_btn != null){ 
		$('#accepted_forward_btn').show();
	}

	if(final_granted_btn == 'yes'){
		$('#final_granted_btn').show();
	}
			