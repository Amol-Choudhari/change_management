//this function added on 28-072017 by Amol to check blank comment
function comment_reply_box_validation(){

    var check_save_reply = $("#check_save_reply").val();
    var value_return = 'true';

    if(check_whitespace_validation_textarea(check_save_reply).result == false){

        $("#error_save_reply").show().text(check_whitespace_validation_textarea(check_save_reply).error_message);
        $("#error_save_reply").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
        $("#check_save_reply").click(function(){$("#error_save_reply").hide().text;});
        //setTimeout(function(){ $("#error_packer_name").fadeOut();},5000);

        value_return = 'false';
    }
    console.log(value_return);
    if(value_return == 'false'){ return false; }else{ exit(); }

}


$("#ro_approve_btn").click(function(e){
	    
	if(comment_reply_box_validation()==false){
		e.preventDefault();
	}else{
        
		$("#view_applicant_forms").submit();
	}
	
});

$("#send_comment_btn").click(function(e){
	
	if(comment_reply_box_validation()==false){
		e.preventDefault();
	}else{
		$("#view_applicant_forms").submit();
	}
	
});


//added on 18-05-2021 by Amol, the approval comment can not go to MO
$('#ro_approve_btn').click(function(){
    if($('#comment_to-mo').is(':checked')){
        alert('Please check, The Approval comment must go to SO Officer.');
        return false;
    }
});


$(document).ready(function(){

    $('#comment_to-mo').click(function(){

        var mo_allocation = $("#mo_allocation").val();

        if(mo_allocation == ''){
            $("#error_mo_allocation").show().text('Sorry... Please allocate this application to Scrutiny Officer first');
            $("#error_mo_allocation").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
            $("#comment_to-so"). prop("checked", true);
            setTimeout(function(){ $("#error_mo_allocation").fadeOut();},10000);
        }
    });
});


var application_mode = $("#application_mode").val();
var amaapproved = $("#amaapproved").val();
var so_power_to_grant_appl = $("#so_power_to_grant_appl").val();

if(amaapproved == '' || amaapproved == null){ amaapproved  = null }


if(application_mode == 'view'){

    $('#comment_box_with_btn').css('display','none');
	$('#action-btns').css('display','none');
}


if(amaapproved != null){
    
    if(so_power_to_grant_appl == "no"){
        $('#commentBox').css('display','none');
    }    
	$('#comment_to').css('display','none');
	$('#send_comment_btn').css('display','none');
    //$('.remark-current').css('display','none'); //commented on 02-11-2022, as required while RO "Approve"
}