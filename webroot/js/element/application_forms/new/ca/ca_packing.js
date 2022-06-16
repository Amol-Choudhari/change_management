//// JS FOR CA PACKING

	$("#repacking_docs").change(function(){
		file_browse_onclick('repacking_docs');
		return false;
	});


	var final_submit_status = $('#final_submit_status_id').val();


	$(document).ready(function(){

		//for undertaking
		//for already checked
		if($('#undertaking-yes').is(":checked")){

			$("#hide_undertaking").show();

		}else if($('#undertaking-no').is(":checked")){

			$("#hide_undertaking").hide();

		}

		//for on clicked
		$('#undertaking-yes').click(function(){

			$("#hide_undertaking").show();

		});

		$('#undertaking-no').click(function(){

			$("#hide_undertaking").hide();

		});


		//for proposed to re-pack
		//for already checked
		if($('#proposed_to_repack-yes').is(":checked")){

			$("#hide_proposed_place").show();

		}else if($('#proposed_to_repack-no').is(":checked")){

			$("#hide_proposed_place").hide();

		}

		//for on clicked
		$('#proposed_to_repack-yes').click(function(){

			$("#hide_proposed_place").show();
		});

		$('#proposed_to_repack-no').click(function(){

			$("#hide_proposed_place").hide();

		});

		//for already checked
		if($('#have_grading_other_info-yes').is(":checked")){

					$("#hide_grading_other_info").show();

		}else if($('#have_grading_other_info-no').is(":checked")){

				$("#hide_grading_other_info").hide();

		}

		$('#have_grading_other_info-yes').click(function(){

			$("#hide_grading_other_info").show();

		});

		$('#have_grading_other_info-no').click(function(){

			$("#hide_grading_other_info").hide();

		});
		
		
		bsCustomFileInput.init();
	});

