$("#laboratory_equipped_docs").change(function(){

	file_browse_onclick('laboratory_equipped_docs');
	return false;
});

$(document).ready(function() {

    if ($('#laboratory_equipped-yes').is(":checked")) {

        $("#hide_laboratory_equipped").show();
        $("#hide_not_equipped").hide();

    }else if ($('#laboratory_equipped-no').is(":checked")) {

        $("#hide_laboratory_equipped").hide();
        $("#hide_not_equipped").show();

    //added new option NA in radio options as per UAT suggestion
    //on 17-08-2022    
    }else if ($('#laboratory_equipped-n-a').is(":checked")) {
        $("#hide_laboratory_equipped").hide();
        $("#hide_not_equipped").hide();								   
    }

    $('#laboratory_equipped-yes').click(function() {

        $("#hide_laboratory_equipped").show();
        $("#hide_not_equipped").hide();

    });

    $('#laboratory_equipped-no').click(function() {

        $("#hide_laboratory_equipped").hide();
        $("#hide_not_equipped").show();

    });

	//added new option NA in radio options as per UAT suggestion
    //on 17-08-2022 
    $('#laboratory_equipped-n-a').click(function() {

        $("#hide_laboratory_equipped").hide();
        $("#hide_not_equipped").hide();

    });

    if ($('#extra_load_handled-yes').is(":checked")) {

        $("#hide_extra_load_handled").show();

    }else if ($('#extra_load_handled-no').is(":checked")) {

        $("#hide_extra_load_handled").hide();

    }

    $('#extra_load_handled-yes').click(function() {

        $("#hide_extra_load_handled").show();

    });

    $('#extra_load_handled-no').click(function() {

        $("#hide_extra_load_handled").hide();

    });

});

//to send variables in fields validation js function called on save button
var ca_bevo_applicant = $('#ca_bevo_applicant_id').val();
var final_status = $('#final_status_id').val();
