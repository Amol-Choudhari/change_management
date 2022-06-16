// LAB FIRM PROFILE JS

	$("#state").change(function(){
		get_district();
	});

	$("#business_type_docs").change(function(){
		file_browse_onclick('business_type_docs');
		return false;
	});

	$("#old_certification_pdf").change(function(){
		file_browse_onclick('old_certification_pdf');
		return false;
	});

	$("#old_application_docs").change(function(){
		file_browse_onclick('old_application_docs');
		return false;
	});

	$(document).ready(function () {
		$('#pickdate').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			endDate: new Date()   // add boostrap datepicker property for disable dates after current date (by pravin 05/05/2017)
		});
	});


	function get_district(){

		$("#district").find('option').remove();
		var state = $("#state").val();
		$.ajax({
			type: "POST",
			async:true,
			url:"../AjaxFunctions/show-district-dropdown",
			data: {state:state},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function (data) {
					$("#district").append(data);
			}
		});
	}

	var final_submit_status = $('#final_submit_status_id').val();

	$(document).ready(function () {
	  bsCustomFileInput.init();
	});
