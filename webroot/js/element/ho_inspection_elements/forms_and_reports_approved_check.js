$("#approved_check_modal").show();
	$("#approve_btn").hide();
	
	$("#check_for_proceed").change(function() {
								
		if($(this).prop('checked') == true) {	
			$("#approve_btn").show();
								
		}else{
			$("#approve_btn").hide();								
		}
								
	});