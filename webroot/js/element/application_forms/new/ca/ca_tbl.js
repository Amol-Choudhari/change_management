$("#tbl_registration_docs").change(function(){

	file_browse_onclick('tbl_registration_docs');
	return false;
});


$("#tbl_registration_file").change(function(){

	file_browse_onclick('tbl_registration_file');
	return false;
});

$("#tbl_belongs_docs").change(function(){

	file_browse_onclick('tbl_belongs_docs');
	return false;
});

$("#tbl_consent_letter_docs").change(function(){

	file_browse_onclick('tbl_consent_letter_docs');
	return false;
});


$("#edit_tbl_details").click(function(e){
//updated on 02-12-2022 by amol for change
	if(validate_tbl_details()==false){//condition added on 06-07-2022 by Amol
		e.preventDefault();
	}
});

$("#add_tbl_details").click(function(e){
//updated on 02-12-2022 by amol for change
	if(validate_tbl_details()==false){//condition added on 06-07-2022 by Amol
		e.preventDefault();
	}
});




	//function to check empty fields of machinery details table on add/edit button
	function validate_tbl_details(){

		var tbl_name = $('#tbl_name').val();
		var tbl_registered  = $('#tbl_registered').val();
		var tbl_registered_no  = $('#tbl_registered_no').val();
		var tbl_registration_file  = $('#tbl_registration_file').val();
		var value_return = 'true';

		//if(tbl_name==""){

		// Change Condition for validation and error message by pravin 12-07-2017
		if(check_whitespace_validation_textbox(tbl_name).result == false){

			$("#error_tbl_name").show().text("Please enter TBL name.");
			// $("#error_tbl_name").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			setTimeout(function(){ $("#error_tbl_name").fadeOut();},5000);
			$("#tbl_name").addClass("is-invalid");
			$("#tbl_name").click(function(){$("#error_tbl_name").hide().text; $("#tbl_name").removeClass("is-invalid");});

			value_return = 'false';
		}

		if(!$('#tbl_registered-yes').is(":checked") && !$('#tbl_registered-no').is(":checked")){

			$("#error_tbl_registered").show().text("Please check if the TBL is registered or not");
			// $("#error_tbl_registered").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			setTimeout(function(){ $("#error_tbl_registered").fadeOut();},5000);
			$("#tbl_registered-yes").click(function(){$("#error_tbl_registered").hide().text;});

			value_return = 'false';
		}
		else{
			//updated on 08-08-2017 by Amol
			if($('#tbl_registeredYes').is(":checked")){

				// Change Condition for validation and error message by pravin 12-07-2017
				if(check_whitespace_validation_textbox(tbl_registered_no).result == false){

					$("#error_tbl_registered_no").show().text("Please enter Registration No.");
					// $("#error_tbl_registered_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
					setTimeout(function(){ $("#error_tbl_registered_no").fadeOut();},5000);
					$("#tbl_registered_no").click(function(){$("#error_tbl_registered_no").hide().text;});

					value_return = 'false';
				}

			}

		}

		//if(tbl_registered_no==""){

		if(tbl_registration_file==""){

			$("#error_tbl_registration_docs").show().text("Please upload tbl registration document");
			// $("#error_tbl_registration_docs").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			setTimeout(function(){ $("#error_tbl_registration_docs").fadeOut();},5000);
			$("#tbl_registration_file").addClass("is-invalid");
			$("#tbl_registration_file").click(function(){$("#error_tbl_registration_docs").hide().text; $("#tbl_registration_file").removeClass("is-invalid");});

			value_return = 'false';
		}

		if(value_return == 'false')
		{
			// alert("Please check some fields are missing or not proper.");
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}
		else{
			exit;
		}
	}


	// function for number with decimal two validation by pravin 12-07-2017
	function check_number_with_decimal_two_validation(field_value)
	{
		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 20 digit numeric value with 2 decimal point allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value.match(/^\d{1,25}(\.\d{1,2})?$/) == null)
		{
			if(update_field_value > 0)
			{
				return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message2};
		}

		return true;
	}

	// function for whitespace and blank value validation by pravin 12-07-2017
	function check_whitespace_validation_textbox(field_value){

		var field_length = field_value.length;
		var field_trim = $.trim(field_value);
		var update_field_value = field_trim.length;
		var error_message1 = 'This field is mandatory and maximum 50 characters allowed';
		var error_message2 = 'Please Remove blank space before and after the text';

		if(field_value != "")
		{
			if(update_field_value > 0)
			{
				if(field_length <= 50)
				{
					return true;
				}
					return {result: false, error_message: error_message1};
			}
				return {result: false, error_message: error_message2};
		}else{
				return {result: false, error_message: error_message1};
			 }

	}




    $(document).ready(function(){

    //added on 08-08-2017 by Amol
    //for tbl registration no

    //for already checked

        if($('#tbl_registered-yes').is(":checked")){

                $("#tbl_registered_no").show();

        }else if($('#tbl_registered-no').is(":checked")){

                $("#tbl_registered_no").hide();

        }


    //for on clicked

        $('#tbl_registered-yes').click(function(){

            $("#tbl_registered_no").show();

        });

        $('#tbl_registered-no').click(function(){

            $("#tbl_registered_no").val(null);
            $("#tbl_registered_no").hide();

        });


    //for tbl_belongs

        //for already checked

            if($('#tbl_belongs_to_applicant-yes').is(":checked")){

                        $("#hide_tbl_belongs").show();
                        $("#hide_tbl_consent_letter_docs").hide();

            }else if($('#tbl_belongs_to_applicant-no').is(":checked")){


                    $("#hide_tbl_belongs").hide();
                    $("#hide_tbl_consent_letter_docs").show();

            }



        //for on clicked


            $('#tbl_belongs_to_applicant-yes').click(function(){

                        //alert('Yes Selected');
                        $("#hide_tbl_belongs").show();
                        $("#hide_tbl_consent_letter_docs").hide();

            });

            $('#tbl_belongs_to_applicant-no').click(function(){

                        //alert('No Selected');
                        $("#hide_tbl_belongs").hide();
                        $("#hide_tbl_consent_letter_docs").show();

            });

    });


    var final_submit_status = $('#final_submit_status').val();


    	$(document).ready(function () {
    	  bsCustomFileInput.init();
    	});
