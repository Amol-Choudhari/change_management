$("#laboratory_site_plan_docs").change(function(){

	file_browse_onclick('laboratory_site_plan_docs');
	return false;
});

$("#is_lab_fully_equipped_doc").change(function(){

	file_browse_onclick('is_lab_fully_equipped_doc');
	return false;
});

$("#chemists_employed_docs").change(function(){

	file_browse_onclick('chemists_employed_docs');
	return false;
});




$(document).ready(function () {

    // For Date Pick
    $('#inspection_date').datepicker({
        format: "dd/mm/yyyy"+" 00:00:00",
        autoclose: true
    });

    //for is_lab_fully_equippedNo
    //for already checked

    if($('#is_lab_fully_equipped-yes').is(":checked")){

        $("#is_lab_equipped").show();

    }else if($('#is_lab_fully_equipped-no').is(":checked")){

        $("#is_lab_equipped").hide();
    }

    $('#is_lab_fully_equipped-yes').click(function(){

        $("#is_lab_equipped").show();

    });

    $('#is_lab_fully_equipped-no').click(function(){

        $("#is_lab_equipped").hide();
    });

});


//to send variables in printing_forms_validation js file called on save button (By pravin 27-07-2017)
var final_status = $('#final_status_id').val();
