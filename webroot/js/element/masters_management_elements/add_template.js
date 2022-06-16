var masterId = $('#masterId').val();
var form_id = $('#form_id').val();

$("#add_sms_template_btn").click(function(e){

	if(sms_message_parameter_validation(masterId) == false){
		e.preventDefault();
	}else{
		form_id.submit();
	}

});

$(document).ready(function(){
    //for Roles list
    //for on clicked
    $("#dmi_roles").hide();
    $("#lmis_roles").hide();

    $('#template_for-dmi').click(function(){

        $("#dmi_roles").show();
        $("#lmis_roles").hide();

    });

    $('#template_for-lmis').click(function(){

        $("#lmis_roles").show();
        $("#dmi_roles").hide();

    });
});
