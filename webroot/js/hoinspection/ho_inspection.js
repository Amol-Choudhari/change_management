var application_type_id = $("#application_type_id").val();

$("#approved_btn").click(function(e){
	    
	if(approval_comment_validation()==false){
		e.preventDefault();
	}else{
        
		//$("#view_applicant_forms").submit();
	}
	
});

$("#send_comment_btn").click(function(e){
	
	if(comment_reply_box_validation()==false){
		e.preventDefault();
	}else{
		//$("#view_applicant_forms").submit();
	}
	
});



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


	if(value_return == 'false')
		{
			return false;

		}else{

			exit();

		}

}


var check_user_role = JSON.parse($('#check_user_role').val());
var ca_bevo_applicant = $('#ca_bevo_applicant').val();


//added below new condition on 16-09-2019 for CA BEVO appln approved by Jtama only
if(check_user_role.ama == 'yes' || (check_user_role.jt_ama == 'yes' && ca_bevo_applicant == 'yes' && application_type_id==1))//added cond. on 22-11-2021 for appl. type = 1
{
    //below code added on 04-08-2017 by Amol
   $("#approved_btn").hide();
   $("#comment_box_with_btn").hide();

   $("#action").change(function(){

       if($("#action").val() == 0){

           $("#approved_btn").show();
           $("#comment_box_with_btn").hide();
       }else{
           $("#comment_box_with_btn").show();
           $("#approved_btn").hide();
       }
   });
}

var application_mode = $('#application_mode').val();

if(application_mode == 'view'){

    $('#comment_box_with_btn').css('display','none');
    $('#actionbox').css('display','none');
}


//this function to validate approval comment, on 05-05-2020 by Amol
function approval_comment_validation(){

	var approval_comment = $("#approval_comment").val();
	var value_return = 'true';

	if(check_whitespace_validation_textarea(approval_comment).result == false){

		$("#error_approval_comment").show().text(check_whitespace_validation_textarea(approval_comment).error_message);
		$("#error_approval_comment").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
		$("#approval_comment").click(function(){$("#error_approval_comment").hide().text;});

		value_return = 'false';
	}


	if(value_return == 'false'){
		return false;
	}else{
		exit();
	}

}



	var export_unit_status = $('#export_unit_status').val();
	var check_ama_approval = $('#check_ama_approval').val();
	var current_level = $('#current_level').val();
	var check_valid_ro = $('#check_valid_ro').val();

	if(check_ama_approval == '' || check_ama_approval == null){ check_ama_approval = null; }
	if(check_valid_ro == '' || check_valid_ro == null){ check_valid_ro = null; }
	
	//added export unit cond. on 28-09-2021 by Amol, if lab export than grant by Dyama
	if(export_unit_status != 'yes' && check_valid_ro !=null && current_level == 'level_3' && check_ama_approval != null){

		$('#comment_box_with_btn').css('display','none');
		$('#actionbox').css('display','none');

 	}
