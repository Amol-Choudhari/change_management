// LAB FIRM OTHER JS

	var chemist_details_value = $('#chemist_details_value_id').val();
	var final_submit_status = $('#final_submit_status_id').val();
	var export_unit_status = $('#export_unit_status_id').val();
 
	//applied this script on 24-05-2022 by Amol
	//to disable Accreditation field once scrutinized, applicant can not change
	if(final_submit_status=='approved'){
		
		if($('#is_accreditated-yes').is(':checked')) {
			$("#is_accreditated-no").prop('disabled',true);
		}
		if($('#is_accreditated-no').is(':checked')) {
			$("#is_accreditated-yes").prop('disabled',true);
		}
		
	}

	$("#chemists_details_docs").change(function(){
		file_browse_onclick('chemists_details_docs');
		return false;
	});


	$("#edit_chemist_details").click(function(e){
		if(chemist_table_validation()==false){
			e.preventDefault();
		}
	});

	$("#add_chemist_details").click(function(e){

		if(chemist_table_validation()==false){
			e.preventDefault();
		}
	});
	

	$("#chemists_employed_docs").change(function(){
		file_browse_onclick('chemists_employed_docs');
		return false;
	});

	$("#premises_belongs_to_docs").change(function(){
		file_browse_onclick('premises_belongs_to_docs');
		return false;
	});

	$("#total_area_covered_docs").change(function(){
		file_browse_onclick('total_area_covered_docs');
		return false;
	});

	$("#accreditation_docs").change(function(){
		file_browse_onclick('accreditation_docs');
		return false;
	});

	$("#apeda_docs").change(function(){
		file_browse_onclick('apeda_docs');
		return false;
	});

	$("#is_laboretory_equipped_docs").change(function(){
		file_browse_onclick('is_laboretory_equipped_docs');
		return false;
	});


	$(document).ready(function () {
		$('#nabl_accreditated_upto').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			startDate: new Date()
		});
	});


	if($('#is_laboretory_equipped-yes').is(":checked")){

		$("#laboretory_equipped_attached").show();

	}else if($('#is_laboretory_equipped-no').is(":checked")){

		$("#laboretory_equipped_attached").hide();
	}


	$('#is_laboretory_equipped-yes').click(function(){

		$("#laboretory_equipped_attached").show();

	});

	$('#is_laboretory_equipped-no').click(function(){

		$("#laboretory_equipped_attached").hide();

	});

	if($('#is_accreditated-yes').is(":checked")){

		$("#is_accreditated_attached").show();

	}else if($('#is_accreditated-no').is(":checked")){

		$("#is_accreditated_attached").hide();
	}


	$('#is_accreditated-yes').click(function(){

		$("#is_accreditated_attached").show();
	});

	$('#is_accreditated-no').click(function(){

		$("#is_accreditated_attached").hide();
	});


	if($('#premises_belongs_to-yes').is(":checked")){

		$("#belongs_to").hide();

	}else if($('#premises_belongs_to-no').is(":checked")){

			$("#belongs_to").show();
	}

	$('#premises_belongs_to-yes').click(function(){

		$("#belongs_to").hide();

	});

	$('#premises_belongs_to-no').click(function(){

		$("#belongs_to").show();

	});




	$('#chemist_list').multiselect({
		maxWidth: 200,
		placeholder: 'Select Option'
	});


	$(document).ready(function () {
	  bsCustomFileInput.init();
	});
