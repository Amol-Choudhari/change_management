var ca_bevo_applicant = $('#ca_bevo_applicant_id').val();
var applicant_type = $('#applicant_type_id').val();
var final_submit_status = $('#final_submit_status_id').val();

$("#detail_docs").change(function(){

	file_browse_onclick('detail_docs');
	return false;
});

$("#unit_related_docs").change(function(){

	file_browse_onclick('unit_related_docs');
	return false;
});

$("#bevo_machinery_details_docs").change(function(){

	file_browse_onclick('bevo_machinery_details_docs');
	return false;
});

$("#fat_spread_facility_docs").change(function(){

	file_browse_onclick('fat_spread_facility_docs');
	return false;
});

$("#stored_crushed_separately_docs").change(function(){

	file_browse_onclick('stored_crushed_separately_docs');
	return false;
});


$(document).ready(function(){

    if($('#have_details-yes').is(":checked")){

            $("#hide_machinery_details").show();

    }else if($('#have_details-no').is(":checked")){

            $("#hide_machinery_details").hide();

    }

    $('#have_details-yes').click(function(){

        $("#hide_machinery_details").show();

    });

    $('#have_details-no').click(function(){

        $("#hide_machinery_details").hide();

    });

    //for already checked
    $("#no_owned_unit").hide();
    if($('#manufacturing_unit-yes').is(":checked")){

        $("#no_owned_unit").hide();

    }else if($('#manufacturing_unit-no').is(":checked")){

        $("#no_owned_unit").show();

    }


    $('#manufacturing_unit-yes').click(function(){

        $("#no_owned_unit").hide();

    });

    $('#manufacturing_unit-no').click(function(){

        $("#no_owned_unit").show();

    });

    //for already checked
    if($('#storage_provision-yes').is(":checked")){

        $("#hide_storage_provision").show();

    }else if($('#storage_provision-no').is(":checked")){

        $("#hide_storage_provision").hide();
    }

    $('#storage_provision-yes').click(function(){

        $("#hide_storage_provision").show();

    });

    $('#storage_provision-no').click(function(){

        $("#hide_storage_provision").hide();

    });

    //for already checked
    if($('#stored_crushed_separately-yes').is(":checked")){

        $("#hide_separate_crushed").show();

    }else if($('#stored_crushed_separately-no').is(":checked")){

        $("#hide_separate_crushed").hide();

    }

    $('#stored_crushed_separately-yes').click(function(){

        $("#hide_separate_crushed").show();

    });

    $('#stored_crushed_separately-no').click(function(){

        $("#hide_separate_crushed").hide();

    });

});

$(document).ready(function () {
  bsCustomFileInput.init();
});
