$("#chemists_details_docs").change(function(){

	file_browse_onclick('chemists_details_docs');
	return false;
});


$("#edit_chemist_details").click(function(e){

	if(renewal_chemist_table_validation()==false){
		e.preventDefault();
    }
});

$("#authorized_packers_docs").change(function(){

	file_browse_onclick('authorized_packers_docs');
	return false;
});

$("#lots_graded_docs").change(function(){

	file_browse_onclick('lots_graded_docs');
	return false;
});

$("#quantity_graded_docs").change(function(){

	file_browse_onclick('quantity_graded_docs');
	return false;
});

$("#check_Sample_docs").change(function(){

	file_browse_onclick('check_Sample_docs');
	return false;
});


// for multiselect dropdown option
$('#chemist_list').multiselect({ placeholder: 'Select Option' });

// for showing the multiselect dropdown in application_side_chemist details table (by pravin 13/05/2017)
$('.application_side_commodity').multiselect({ placeholder: 'Select Option' });

if($('#is_warning_issued-yes').is(":checked")){

            $("#warning_details_box").show();

}else if($('#is_warning_issued-no').is(":checked")){

        $("#warning_details_box").hide();
}

//for Onclick option
$('#is_warning_issued-yes').click(function(){

    $("#warning_details_box").show();
});

$('#is_warning_issued-no').click(function(){

    $("#warning_details_box").hide();
});

$('.old_record_class').click(function(){

    var id = $(this).attr('id');
    $("#old_record_id").val(id);

});


// This script handal the functionality of old chemist table show status on checkbox checked/unchecked status (by pravin 13/05/2017)
$( document ).ready(function() {

    $("#application_side_chemist_table").hide();

    $("#chemist_details_choice-1").change(function() {

        if($(this).prop('checked') == true) {

            $("#application_side_chemist_table").show();
            $("#renewal_side_table").show();

        } else {

            $("#application_side_chemist_table").hide();
            $("#renewal_side_table").show();
        }
    });

    if($('#chemist_details_choice-1').is(":checked")){

        $("#application_side_chemist_table").show();
        $("#renewal_side_table").show();
    }
});

	var final_renewal_submit_status = $('#final_renewal_submit_status_id').vale();
