var form_section_id = $('#form_section_id').val();

$('#edit_referred_back').click(function(e) {
	e.preventDefault();

		var referred_back_max_id = $('#referred_back_max_id').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "referred_back_max_id",value: referred_back_max_id});

		$.ajax({
			type: "POST",
			url: "../AjaxFunctions/editReferredBack",
			data: form_data,
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){
				location.reload();
			}
		});
});

$('#delete_referred_back').click(function(e) {
	e.preventDefault();

		var referred_back_max_id = $('#referred_back_max_id').val();
		var model_name = $('#model_name').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "referred_back_max_id",value: referred_back_max_id},
					   {name: "model_name",value: model_name}
					  );
		$.ajax({
			type: "POST",
			url: "../AjaxFunctions/deleteReferredBack",
			data: form_data,
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){
				location.reload();
			}
		});
});


$('#edit_mo_comment').click(function(e) {
	e.preventDefault();

		var mo_comment_max_id = $('#mo_comment_max_id').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "mo_comment_max_id",value: mo_comment_max_id});

		$.ajax({
			type: "POST",
			url: "../AjaxFunctions/editMoComment",
			data: form_data,
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){
				location.reload();
			}
		});
});

$('#delete_mo_comment').click(function(e) {
	e.preventDefault();

		var mo_comment_max_id = $('#mo_comment_max_id').val();
		var model_name = $('#model_name').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "mo_comment_max_id",value: mo_comment_max_id},
					   {name: "model_name",value: model_name}
					  );
		$.ajax({
			type: "POST",
			url: "../AjaxFunctions/deleteMoComment",
			data: form_data,
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){
				location.reload();
			}
		});
});

$('#edit_ro_reply').click(function(e) {
	e.preventDefault();

		var ro_reply_max_id = $('#ro_reply_max_id').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "ro_reply_max_id",value: ro_reply_max_id});

		$.ajax({
			type: "POST",
			url: "../AjaxFunctions/editRoReply",
			data: form_data,
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){
				location.reload();
			}
		});
});


$('#delete_ro_reply').click(function(e) {
	e.preventDefault();

		var ro_reply_max_id = $('#ro_reply_max_id').val();
		var model_name = $('#model_name').val();

		var form_data = $("#"+form_section_id).serializeArray();
		form_data.push({name: "ro_reply_max_id",value: ro_reply_max_id},
					   {name: "model_name",value: model_name}
					  );
		$.ajax({
			type: "POST",
			url: "../AjaxFunctions/deleteRoReply",
			data: form_data,
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){
				location.reload();
			}
		});
});


$( document ).ready(function() {

    $(".form_outer_class :input").prop("disabled", true);
    $(".form_outer_class :input[type='radio']").prop("disabled", true);
    $(".form_outer_class :input[type='select']").prop("disabled", true);
    $(".form_outer_class :input[type='submit']").prop("disabled", true);
    $(".form_outer_class :input[type='submit']").css('display','none');
    $(".form_outer_class :input[type='file']").css('display','none');
    $(".form_outer_class .file_limits").css('display','none');
    $(".form_outer_class .glyphicon-edit").css('display','none');
    $(".form_outer_class .glyphicon-remove-sign").css('display','none');
    $(".form_outer_class .table_record_add_btn").css('display','none');
    $("input[type='file']").parent('.custom-file').css('display','none');
    $("#add_new_row").css('display','none');
    $("#add_packer_details").css('display','none');
    $("#add_new_row_r").css('display','none');
    $(".acols").css('display','none');
    $("#application_side_chemist_table").hide();
});


	$('#edit_comment').click(function(){

		$("#ro_referred_back_click").hide();
		$("#ro_referred_back").show();
		$("#ro_referred_back_box").show();
		$("#ro_reply_box").hide();

		var i = $('#i_id').val();

		if(i == -1) { var i = 0; }

		var comment = $('#mo_commentt'.concat(i)).text();
		//alert(comment);
		$('#reffered_back_comment').val(comment);


	});

	$('#forward_comment').click(function(){

		var i = $('#i_id').val();

		if(i == -1) { var i = 0; }

		var comment = $('#mo_commentt'.concat(i)).text();

		$('#reffered_back_comment').val(comment);

		$('#ro_referred_back').click();

	});


	// call comment box validation on applicant side by pravin
	$('#ro_referred_back').click(function(){

		comment_reply_ro_to_applicant_box_validation();return false
	});


	//by default set values
	$("#ro_reply_box").hide();
	$("#ro_reply").hide();
	$("#ro_referred_back").hide();
	$("#ro_referred_back_box").hide();
	$("#ro_referred_back_click").show();

	$("#ro_reply_click").click(function(){

		$("#ro_reply_box").show();
		$("#ro_reply_click").hide();
		$("#ro_reply").show();
		$("#ro_referred_back_box").hide();
		$("#ro_referred_back_click").show();
		$("#ro_referred_back").hide();

		$("#forward_comment").hide();
		$("#edit_comment").hide();

	});

	$("#ro_referred_back_click").click(function(){

		$("#ro_referred_back_box").show();
		$("#ro_referred_back_click").hide();
		$("#ro_referred_back").show();
		$("#ro_reply_box").hide();
		$("#ro_reply_click").show();
		$("#ro_reply").hide();

	});






    $( document ).ready(function() {

        var changelist = JSON.parse($("#changefields").val());

        if(changelist != ''){
            var buttonsArray = ['submit','reset','button'];
            var excludeId = ['esign_or_not_option-yes','esign_or_not_option-no','proceedbtn','okBtn_wo_esign','okBtn','cancelBtn'];
            var changeField = false;

            $("form :input").each(function(){

                var inputId = $(this).attr('id');
                var inputtype = $(this).attr('type');

                if($.inArray(inputId, changelist) == -1 && $.inArray(inputtype, buttonsArray) == -1 && $.inArray(inputId, excludeId) == -1){

                }else{
                    if($.inArray(inputtype, buttonsArray) == -1 && $.inArray(inputId, excludeId) == -1){
                        $("#"+inputId).css("border","1px solid red");
                        $("#"+inputId).parent(".custom-file").css("border","2px solid red");
                        $("#"+inputId).parent(".custom-file").prev('label').css("border","1px solid red");
                    }


                }
            });

        }
    });
