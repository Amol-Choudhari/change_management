$("#state").change(function(){

	get_district();
});

$("#vat_cst_docs").change(function(){

	file_browse_onclick('vat_cst_docs');
	return false;
});


$("#layout_plan_docs").change(function(){

	file_browse_onclick('layout_plan_docs');
	return false;
});


$("#first_rep_signature").change(function(){

	file_browse_onclick('first_rep_signature');
	return false;
});

$("#second_rep_signature").change(function(){

	file_browse_onclick('second_rep_signature');
	return false;
});

$(document).ready(function(){

    if($('#premises_belongs-yes').is(":checked")){

        $("#yes_premises_belongs").show();
        $("#no_premises_belongs").hide();

    }else if($('#premises_belongs-no').is(":checked")){

        $("#no_premises_belongs").show();
        $("#yes_premises_belongs").hide();

    }

    $('#premises_belongs-yes').click(function(){

        $("#yes_premises_belongs").show();
        $("#no_premises_belongs").hide();

    });

    $('#premises_belongs-no').click(function(){

        $("#no_premises_belongs").show();
        $("#yes_premises_belongs").hide();

    });


    if($('#vat_cst-yes').is(":checked")){

        $("#hide_vat_cst").show();

    }else if($('#vat_cst-no').is(":checked")){

        $("#hide_vat_cst").hide();

    }


    $('#vat_cst-yes').click(function(){

        $("#hide_vat_cst").show();

    });

    $('#vat_cst-no').click(function(){

        $("#hide_vat_cst").hide();

    });


    if($('#layout_plan_attached-yes').is(":checked")){

        $("#is_layout_plan_attached").show();

    }else if($('#layout_plan_attached-no').is(":checked")){

        $("#is_layout_plan_attached").hide();

    }


    $('#layout_plan_attached-yes').click(function(){

        $("#is_layout_plan_attached").show();

    });

    $('#layout_plan_attached-no').click(function(){

        $("#is_layout_plan_attached").hide();

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

    $(document).ready(function () {
      bsCustomFileInput.init();
    });
