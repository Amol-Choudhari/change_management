var final_submit_status = $('#final_submit_status_id').val();

$("#business_type_docs").change(function(){

	file_browse_onclick('business_type_docs');
	return false;
});

$("#affidavit_proforma_3_attached_docs").change(function(){

	file_browse_onclick('affidavit_proforma_3_attached_docs');
	return false;
});

$("#old_certification_pdf").change(function(){

	file_browse_onclick('old_certification_pdf');
	return false;
});


$("#old_application_docs").change(function(){

	file_browse_onclick('old_application_docs');
	return false;
});

if($('#affidavit_proforma_3_attached-yes').is(":checked")){

    $("#is_declaration_attached").show();

}else if($('#affidavit_proforma_3_attached-no').is(":checked")){

    $("#is_declaration_attached").hide();

}

    //for Onclick option

$('#affidavit_proforma_3_attached-yes').click(function(){

    $("#is_declaration_attached").show();

});

$('#affidavit_proforma_3_attached-no').click(function(){

    $("#is_declaration_attached").hide();
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

$(document).ready(function () {
    
  if(typeof bsCustomFileInput != 'undefined'){
    bsCustomFileInput.init();
  }
    
});


