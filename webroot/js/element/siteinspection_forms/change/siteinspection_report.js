$("#report_docs").change(function(){

	file_browse_onclick('report_docs');
	return false;
});


function change_report_validation(){
		
		var remark_on_report = $("#remark_on_report").val();
		var report_docs = $("#report_docs").val();
		var value_return = 'true';
		
		
		// Change Condition for validation and error message by pravin 11-07-2017
		if(check_whitespace_validation_textarea(remark_on_report).result == false){	
		
			$("#error_remark_on_report").show().text(check_whitespace_validation_textarea(remark_on_report).error_message);
			$("#remark_on_report").addClass("is-invalid");
			$("#remark_on_report").click(function(){$("#error_remark_on_report").hide().text; $("#remark_on_report").removeClass("is-invalid");});
			value_return = 'false';	
		}
		
		
		if($('#report_docs_value').text() == ""){
				
			// Change Condition for validation and error message by pravin 11-07-2017			
			if(check_file_upload_validation(report_docs).result == false){
				
				$("#error_report_docs").show().text(check_file_upload_validation(report_docs).error_message);
				$("#report_docs").addClass("is-invalid");
				$("#report_docs").click(function(){$("#error_report_docs").hide().text; $("#report_docs").removeClass("is-invalid");});
				value_return = 'false';
			}
		}
		
		
		if(value_return == 'false'){

			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();			
		}
	}