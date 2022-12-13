$("#machines_requisite_docs").change(function(){

	file_browse_onclick('machines_requisite_docs');
	return false;
});

$("#fabrication_facility_docs").change(function(){

	file_browse_onclick('fabrication_facility_docs');
	return false;
});

$("#ink_declaration_docs").change(function(){

	file_browse_onclick('ink_declaration_docs');
	return false;
});

$("#press_sponsored_docs").change(function(){

	file_browse_onclick('press_sponsored_docs');
	return false;
});



		$(document).ready(function(){

			if($('#is_assessed_for-yes').is(":checked")){

				$("#hide_is_assessed_for").show();

			}else if($('#is_assessed_for-no').is(":checked")){

				$("#hide_is_assessed_for").hide();

			}

			$('#is_assessed_for-yes').click(function(){

				$("#hide_is_assessed_for").show();

			});

			$('#is_assessed_for-no').click(function(){

				$("#hide_is_assessed_for").hide();

			});

			if($('#earlier_permitted-yes').is(":checked")){

				$("#hide_earlier_permitted").show();

			}else if($('#earlier_permitted-no').is(":checked")){

				$("#hide_earlier_permitted").hide();

			}

			$('#earlier_permitted-yes').click(function(){

				$("#hide_earlier_permitted").show();

			});

			$('#earlier_permitted-no').click(function(){

				$("#hide_earlier_permitted").hide();

			});

			if($('#fabrication_facility-yes').is(":checked")){

				$("#hide_fabrication_facility").hide();

			}else if($('#fabrication_facility-no').is(":checked")){

				$("#hide_fabrication_facility").show();

			// add new radio button value (by pravin 31/10/2017)
			}else if($('#fabrication_facility-n-a').is(":checked")){//changed "fabrication_facility-na" to "fabrication_facility-n-a" 11-08-2022

				$("#hide_fabrication_facility").hide();
			}

			$('#fabrication_facility-yes').click(function(){

				$("#hide_fabrication_facility").hide();

			});

			$('#fabrication_facility-no').click(function(){

				$("#hide_fabrication_facility").show();
			});

			// add new radio button value (by pravin 31/10/2017)
			$('#fabrication_facility-n-a').click(function(){//changed "fabrication_facility-na" to "fabrication_facility-n-a" 11-08-2022

				$("#hide_fabrication_facility").hide();
			});

			if($('#is_press_sponsored-yes').is(":checked")){

				$("#hide_press_sponsored").show();
				$("#hide_press_authorised").hide();

			}else if($('#is_press_sponsored-no').is(":checked") && $('#is_press_authorisedNo').is(":checked")){

				$("#hide_press_sponsored").hide();

			}

			$('#is_press_sponsored-yes').click(function(){

				$("#hide_press_sponsored").show();
				$("#hide_press_authorised").hide();  // add by pravin 23/05/2017

			});

			$('#is_press_sponsored-no').click(function(){

				$("#hide_press_authorised").show();
				//$("#hide_press_sponsored").hide();  // Commented by pravin 23/05/2017

			});

			// Commented by pravin 23/05/2017
			$('#is_press_authorised-no').click(function(){
				$("#hide_press_sponsored").hide();
			});

			// Commented by pravin 23/05/2017
			$('#is_press_authorised-yes').click(function(){
				$("#hide_press_sponsored").show();
			});
				/*code End by pravin 18/3/2017*/

		});



      	  //to send variables in printing_forms_validation js file called on save button (By pravin 08-07-2017)
      	  var final_status = $('#final_status_id').val();
