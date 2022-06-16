


$("#storage_details_docs").change(function(){

	file_browse_onclick('storage_details_docs');
	return false;
});

$("#condition_details_docs").change(function(){

	file_browse_onclick('condition_details_docs');
	return false;
});

$("#constituent_oil_mill_docs").change(function(){

	file_browse_onclick('constituent_oil_mill_docs');
	return false;
});


$("#room_details_docs").change(function(){

	file_browse_onclick('room_details_docs');
	return false;
});


$("#ventilation_details_docs").change(function(){

	file_browse_onclick('ventilation_details_docs');
	return false;
});

$("#locking_details_docs").change(function(){

	file_browse_onclick('locking_details_docs');
	return false;
});

//function to check empty fields of commodity storage tanks details on add/edit button
function validate_commodity_storage_tank(){

        var commodity_tank_no = $('#commodity_tank_no').val();
        var commodity_tank_shape  = $('#commodity_tank_shape').val();
        var commodity_tank_size  = $('#commodity_tank_size').val();
        var commodity_tank_capacity  = $('#commodity_tank_capacity').val();
        var value_return = 'true';


        //if(commodity_tank_no==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_whitespace_validation_textbox(commodity_tank_no).result == false){

            $("#error_commodity_tank_no").show().text("Please enter Tank No.");
            $("#error_commodity_tank_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_commodity_tank_no").fadeOut();},8000);
            $("#commodity_tank_no").click(function(){$("#error_commodity_tank_no").hide().text;});

            value_return = 'false';
        }

        if(commodity_tank_shape==""){

            $("#error_commodity_tank_shape").show().text("Please select tank shape");
            $("#error_commodity_tank_shape").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_commodity_tank_shape").fadeOut();},8000);
            $("#commodity_tank_shape").click(function(){$("#error_commodity_tank_shape").hide().text;});

            value_return = 'false';
        }

        //if(commodity_tank_size==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_whitespace_validation_textbox(commodity_tank_size).result == false){

            $("#error_commodity_tank_size").show().text("Please enter tank size");
            $("#error_commodity_tank_size").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_commodity_tank_size").fadeOut();},8000);
            $("#commodity_tank_size").click(function(){$("#error_commodity_tank_size").hide().text;});

            value_return = 'false';
        }

        //if(commodity_tank_capacity==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_number_with_decimal_two_validation(commodity_tank_capacity).result == false){

            $("#error_commodity_tank_capacity").show().text("Please enter tank capacity");
            $("#error_commodity_tank_capacity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_commodity_tank_capacity").fadeOut();},8000);
            $("#commodity_tank_capacity").click(function(){$("#error_commodity_tank_capacity").hide().text;});

            value_return = 'false';
        }


        if(value_return == 'false')
        {
            alert("Please check some fields are missing or not proper.");
            return false;
        }
        else{
            return true;
        }
}




//function to check empty fields of constituent oil tanks details on add/edit button
function validate_const_oil_tank(){

        var const_oils_tank_no = $('#const_oils_tank_no').val();
        var const_oils_tank_shape  = $('#const_oils_tank_shape').val();
        var const_oils_tank_size  = $('#const_oils_tank_size').val();
        var const_oils_tank_capacity  = $('#const_oils_tank_capacity').val();
        var value_return = 'true';


        //if(const_oils_tank_no==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_whitespace_validation_textbox(const_oils_tank_no).result == false){

            $("#error_const_oils_tank_no").show().text("Please enter Tank No.");
            $("#error_const_oils_tank_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_const_oils_tank_no").fadeOut();},8000);
            $("#const_oils_tank_no").click(function(){$("#error_const_oils_tank_no").hide().text;});

            value_return = 'false';
        }

        if(const_oils_tank_shape==""){

            $("#error_const_oils_tank_shape").show().text("Please select tank shape");
            $("#error_const_oils_tank_shape").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_const_oils_tank_shape").fadeOut();},8000);
            $("#const_oils_tank_shape").click(function(){$("#error_const_oils_tank_shape").hide().text;});

            value_return = 'false';
        }

        //if(const_oils_tank_size==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_whitespace_validation_textbox(const_oils_tank_size).result == false){

            $("#error_const_oils_tank_size").show().text("Please enter tank size");
            $("#error_const_oils_tank_size").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_const_oils_tank_size").fadeOut();},8000);
            $("#const_oils_tank_size").click(function(){$("#error_const_oils_tank_size").hide().text;});

            value_return = 'false';
        }

        //if(const_oils_tank_capacity==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_number_with_decimal_two_validation(const_oils_tank_capacity).result == false){

            $("#error_const_oils_tank_capacity").show().text("Please enter tank capacity");
            $("#error_const_oils_tank_capacity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_const_oils_tank_capacity").fadeOut();},8000);
            $("#const_oils_tank_capacity").click(function(){$("#error_const_oils_tank_capacity").hide().text;});

            value_return = 'false';
        }


        if(value_return == 'false')
        {
            alert("Please check some fields are missing or not proper.");
            return false;
        }
        else{
            return true;
        }
}





//function to check empty fields of constituent oil tanks details on add/edit button
function validate_bevo_oil_tank(){

        var bevo_tank_no = $('#bevo_tank_no').val();
        var bevo_tank_shape  = $('#bevo_tank_shape').val();
        var bevo_tank_size  = $('#bevo_tank_size').val();
        var bevo_tank_capacity  = $('#bevo_tank_capacity').val();
        var value_return = 'true';


        //if(bevo_tank_no==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_whitespace_validation_textbox(bevo_tank_no).result == false){

            $("#error_bevo_tank_no").show().text("Please enter Tank No.");
            $("#error_bevo_tank_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_bevo_tank_no").fadeOut();},8000);
            $("#bevo_tank_no").click(function(){$("#error_bevo_tank_no").hide().text;});

            value_return = 'false';
        }

        if(bevo_tank_shape==""){

            $("#error_bevo_tank_shape").show().text("Please select tank shape");
            $("#error_bevo_tank_shape").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_bevo_tank_shape").fadeOut();},8000);
            $("#bevo_tank_shape").click(function(){$("#error_bevo_tank_shape").hide().text;});

            value_return = 'false';
        }

        //if(bevo_tank_size==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_whitespace_validation_textbox(bevo_tank_size).result == false){

            $("#error_bevo_tank_size").show().text("Please enter tank size");
            $("#error_bevo_tank_size").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_bevo_tank_size").fadeOut();},8000);
            $("#bevo_tank_size").click(function(){$("#error_bevo_tank_size").hide().text;});

            value_return = 'false';
        }

        //if(bevo_tank_capacity==""){

        // Change Condition for validation and error message by pravin 12-07-2017
        if(check_number_with_decimal_two_validation(bevo_tank_capacity).result == false){

            $("#error_bevo_tank_capacity").show().text("Please enter tank capacity");
            $("#error_bevo_tank_capacity").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            //setTimeout(function(){ $("#error_bevo_tank_capacity").fadeOut();},8000);
            $("#bevo_tank_capacity").click(function(){$("#error_bevo_tank_capacity").hide().text;});

            value_return = 'false';
        }


        if(value_return == 'false')
        {
            alert("Please check some fields are missing or not proper.");
            return false;
        }
        else{
            return true;
        }
}



$(document).ready(function(){

    if($('#premises_inspected-yes').is(":checked")){	 $("#hide_premises_inspected").show();
    }else if($('#premises_inspected-no').is(":checked")){ $("#hide_premises_inspected").hide(); }

    $('#premises_inspected-yes').click(function(){ $("#hide_premises_inspected").show();	});
    $('#premises_inspected-no').click(function(){ $("#hide_premises_inspected").hide(); });

    if($('#locking_adequate-yes').is(":checked")){ $("#hide_locking_adequate").show();
    }else if($('#locking_adequate-no').is(":checked")){ $("#hide_locking_adequate").hide(); }

    $('#locking_adequate-yes').click(function(){	$("#hide_locking_adequate").show(); });
    $('#locking_adequate-no').click(function(){ $("#hide_locking_adequate").hide(); });

    if($('#lighted_ventilated-yes').is(":checked")){	 $("#hide_lighted_ventilated").show();
    }else if($('#lighted_ventilated-no').is(":checked")){ $("#hide_lighted_ventilated").hide(); }

    $('#lighted_ventilated-yes').click(function(){  $("#hide_lighted_ventilated").show();  });
    $('#lighted_ventilated-no').click(function(){   $("#hide_lighted_ventilated").hide();  });

    if($('#conditions_fulfilled-yes').is(":checked")){

        $("#hide_conditions_fulfilled").show();
        $("#hide_condition_details").hide();

    }else if($('#conditions_fulfilled-no').is(":checked")){

        $("#hide_conditions_fulfilled").hide();
        $("#hide_condition_details").show();
    }


    $('#conditions_fulfilled-yes').click(function(){
        $("#hide_conditions_fulfilled").show();
        $("#hide_condition_details").hide();
    });

    $('#conditions_fulfilled-no').click(function(){
        $("#hide_conditions_fulfilled").hide();
        $("#hide_condition_details").show();
    });

});


    //to send variables in fields validation js function called on save button
	var ca_bevo_applicant = $('#ca_bevo_applicant_id').val();
	var final_status = $('#final_status_id').val();
