    $("#machinery_processing_docs").change(function(){

    	file_browse_onclick('machinery_processing_docs');
    	return false;
    });

    $("#constituent_oil_suppliers_docs").change(function(){

    	file_browse_onclick('constituent_oil_suppliers_docs');
    	return false;
    });

    $("#bevo_machinery_details_docs").change(function(){

    	file_browse_onclick('bevo_machinery_details_docs');
    	return false;
    });



    //function to check empty fields of commodity storage tanks details on add/edit button
    function validate_const_oil_mills() {

        var oil_name = $('#oil_name').val();
        var mill_name_address  = $('#mill_name_address').val();
        var quantity_procured  = $('#quantity_procured').val();
        var value_return = 'true';


        //if (oil_name=="") {

        // Change Condition for validation and error message by pravin 12-07-2017
        if (check_whitespace_validation_textbox(oil_name).result == false) {

            $("#error_oil_name").show().text("Please enter oil name");
            $("#error_oil_name").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            $("#oil_name").click(function() {$("#error_oil_name").hide().text;});

            value_return = 'false';
        }

        //if (mill_name_address=="") {

        // Change Condition for validation and error message by pravin 12-07-2017
        if (check_whitespace_validation_textbox(mill_name_address).result == false) {

            $("#error_mill_name_address").show().text("Please enter mill name and address");
            $("#error_mill_name_address").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            $("#mill_name_address").click(function() {$("#error_mill_name_address").hide().text;});

            value_return = 'false';
        }

        //if (quantity_procured=="") {

        // Change Condition for validation and error message by pravin 12-07-2017
        if (check_number_with_decimal_two_validation(quantity_procured).result == false) {

            $("#error_quantity_procured").show().text("Please enter procured quantity, only number value allowd");
            $("#error_quantity_procured").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            $("#quantity_procured").click(function() {$("#error_quantity_procured").hide().text;});

            value_return = 'false';
        }


        if (value_return == 'false') {
            alert("Please check some fields are missing or not proper.");
            return false;
        } else {
            return true;
        }
    }



    $(document).ready(function() {

		//for laboratory equipped.for already checked
		if ($('#own_machinery-yes').is(":checked")) {

            $("#hide_own_machinery").hide();

		} else if ($('#own_machinery-no').is(":checked")) {

            $("#hide_own_machinery").show();
	    }


        $('#own_machinery-yes').click(function() {

            $("#hide_own_machinery").hide();

        });

        $('#own_machinery-no').click(function() {

            $("#hide_own_machinery").show();

        });


    	//for Extra Load.
    	//for already checked
    	if ($('#tbl_in_order-yes').is(":checked")) {

            $("#hide_tbl_in_order").show();

        }else if ($('#tbl_in_order-no').is(":checked")) {

    		$("#hide_tbl_in_order").hide();
    	}


    	//for on clicked
        $('#tbl_in_order-yes').click(function() {

            $("#hide_tbl_in_order").show();

        });

        $('#tbl_in_order-no').click(function() {

            $("#hide_tbl_in_order").hide();

        });

    });



    //to send variables in fields validation js function called on save button
    var ca_bevo_applicant =  $('#ca_bevo_applicant_id').val()
    var final_status = $('#final_status_id').val()
    var firm_sub_commodity =  $('#firm_sub_commodity_id').val()
