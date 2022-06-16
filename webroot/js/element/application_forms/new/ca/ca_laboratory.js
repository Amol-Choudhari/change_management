$("#chemist_detail_docs").change(function(){

	file_browse_onclick('chemist_detail_docs');
	return false;
});

$("#lab_equipped_docs").change(function(){

	file_browse_onclick('lab_equipped_docs');
	return false;
});

$("#consent_letter_docs").change(function(){

	file_browse_onclick('consent_letter_docs');
	return false;
});

$("#consent_letter_docs").change(function(){

	file_browse_onclick('consent_letter_docs');
	return false;
});

$(".getState").change(function(){

	get_district();
});

// added on 11-05-2017 by Amol(to hide consent letter if own laboratory)
$("#hide_consent_letter").hide();
$("#show_chemist_details").show();

$('#laboratory_type').change(function(){

    if($('#laboratory_type').val() == 1)
    {
        $("#hide_consent_letter").hide();
        $("#show_chemist_details").show();

    }
    else{
        $("#hide_consent_letter").show();
        $("#show_chemist_details").hide();
    }

});


if($('#laboratory_type').val() != ""){

    if($('#laboratory_type').val() != 1)
    {
        $("#hide_consent_letter").show();
        $("#show_chemist_details").hide();

    }else{

        $("#hide_consent_letter").hide();
        $("#show_chemist_details").show();

    }

}


$(document).ready(function(){

    if($('#consent_letter-yes').is(":checked")){
        $("#hide_consent_letter").show();

    }else if($('#consent_letter-no').is(":checked")){
        $("#hide_consent_letter").hide();
    }

    $('#consent_letter-yes').click(function(){
        $("#hide_consent_letter").show();
    });

    $('#consent_letter-no').click(function(){
        $("#hide_consent_letter").hide();
    });

});

function get_district(){

    $("#district").find('option').remove();
    var state = $("#state").val();
    $.ajax({
        type: "POST",
        async:true,
        url:"../AjaxFunctions/show-district-dropdown",
        data: {state:state},
        beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success: function (data) {
                $("#district").append(data);
        }
    });
}

var final_submit_status = $('#final_submit_status_id').val();
var ca_bevo_applicant = $('#ca_bevo_applicant_id').val();

	$(document).ready(function () {
	  bsCustomFileInput.init();
	});
