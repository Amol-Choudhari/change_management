var final_submit_status = $('#final_submit_status_id').val();

$("#other_machin_docs").change(function(){

	file_browse_onclick('other_machin_docs');
	return false;
});

$("#fabrication_docs").change(function(){

	file_browse_onclick('fabrication_docs');
	return false;
});



		$(document).ready(function(){

			$('#other_packing').hide();
			$('#type_of_packing').change(function(){

				if($('#type_of_packing option:selected').text() == 'Other'){

					$('#other_packing').show();
				}

			});

			if($('#have_machine_details-yes').is(":checked")){

				$("#hide_machine_details").show();

			}else if($('#have_machine_details-no').is(":checked")){

				$("#hide_machine_details").hide();
			}


			$('#have_machine_details-yes').click(function(){

				$("#hide_machine_details").show();
			});

			$('#have_machine_details-no').click(function(){

				$("#hide_machine_details").hide();
			});


			if($('#earlier_approved-yes').is(":checked")){

				$("#hide_expiry_date").show();

			}else if($('#earlier_approved-no').is(":checked")){

				$("#hide_expiry_date").hide();
			}


			$('#earlier_approved-yes').click(function(){

				$("#hide_expiry_date").show();

			});

			$('#earlier_approved-no').click(function(){

				$("#hide_expiry_date").hide();

			});

			if($('#proper_fabrication-yes').is(":checked")){

				$("#hide_name_address").hide();
				$("#fabrication_box").show();

			}else if($('#proper_fabrication-no').is(":checked")){

				$("#hide_name_address").show();
				$("#fabrication_box").show();

			}else if($('#proper_fabrication-n-a').is(":checked")){

				$("#hide_name_address").hide();
				$("#fabrication_box").hide();
			}

			$('#proper_fabrication-yes').click(function(){

				$("#hide_name_address").hide();
				$("#fabrication_box").show();

			});

			$('#proper_fabrication-no').click(function(){

				$("#hide_name_address").show();
				$("#fabrication_box").show();

			});

			$('#proper_fabrication-n-a').click(function(){

				$("#hide_name_address").hide();
				$("#fabrication_box").hide();

			});

		});



        $(document).ready(function () {

            $('#proposed_date').datepicker({
                format: "dd/mm/yyyy"+" 00:00:00",
                autoclose: true,
                startDate: new Date()
            });

        });


        // Check the earlier approved press date (by pravin 11/05/2017)

        $(document).ready(function () {

            $('#earlier_expiry_date').datepicker({
                format: "dd/mm/yyyy"+" 00:00:00",
                autoclose: true,
                endDate: new Date()
            });

        });




        $(document).ready(function () {
          bsCustomFileInput.init();
        });
