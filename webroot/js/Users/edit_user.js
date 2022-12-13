$("#profile_pic").change(function(){

	file_browse_onclick('profile_pic');
	return false;
});

$(".update_btn").click(function(e){

	if(add_user_validations()==false){
		e.preventDefault();
	}else{
		$("#add_firm_form").submit();
	}

});


    var return_error_msg = $('#return_error_msg').val();

    if(return_error_msg != ''){
        $.alert(return_error_msg);
    }

    $(document).ready(function(){

        bsCustomFileInput.init();//added on 06-05-2021 for profile pic

        //aadhar card validation
    //commented on 15-06-2018 by Amol, no provision to store aadhar
    /*	$('#once_card_no').focusout(function(){

            var once_card_no = $('#once_card_no').val();

            if(once_card_no.match(/^(?=.*[0-9])[0-9]{12}$/g) || once_card_no.match(/^[X-X]{8}[0-9]{4}$/i)){}else{//also allow if 8 X $ 4 nos found

                //alert("aadhar card number should be of 12 numbers only");
                $("#error_aadhar_card_no").show().text("Should not blank, Only numbers allowed, min & max length is 12");
                $("#error_aadhar_card_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
                $("#once_card_no").click(function(){$("#error_aadhar_card_no").hide().text;});
                return false;
            }
        });
    */



        //to show/hide lmis user role list

        //for already checked

            //for already checked
        //below all scripts updated on 27-07-2018 by Amol

        $("#user_belongs_to_div").hide();

            if($('#division-dmi').is(":checked")){

                $("#ral_list_div :input").prop("disabled", true);
                $("#ro_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").hide();
                $("#ro_list_div").show();
                $("#ral_list_div").hide();
                $("#user_belongs_to_div").hide();
                $("#lmis_role").val('');


            }
            if($('#division-lmis').is(":checked")){

                $("#ro_list_div :input").prop("disabled", true);
                $("#ral_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").show();
                $("#ro_list_div").hide();
                $("#ral_list_div").show();
                $("#user_belongs_to_div").hide();

            }
            if($('#division-both').is(":checked")){

                $("#user_belongs_to_div").show();
                $("#lmis_roles_list").hide();
                $("#ro_list_div").hide();
                $("#ral_list_div").hide();

            }



        //for on clicked

            $('#division-dmi').click(function(){

                $("#ral_list_div :input").prop("disabled", true);
                $("#ro_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").hide();
                $("#ro_list_div").show();
                $("#ral_list_div").hide();
                $("#user_belongs_to_div").hide();
                $("#lmis_role").val('');


            });

            $('#division-lmis').click(function(){

                $("#ro_list_div :input").prop("disabled", true);
                $("#ral_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").show();
                $("#ro_list_div").hide();
                $("#ral_list_div").show();
                $("#user_belongs_to_div").hide();

            });

            $('#division-both').click(function(){

                $("#user_belongs_to_div").show();
                $("#lmis_roles_list").hide();
                $("#ro_list_div").hide();
                $("#ral_list_div").hide();

                //check again on BOTH clicked
                if($('#user_belongs_to-dmi').is(":checked")){

                    $("#ral_list_div :input").prop("disabled", true);
                    $("#ro_list_div :input").prop("disabled", false);
                    $("#lmis_roles_list").show();
                    $("#ro_list_div").show();
                    $("#ral_list_div").hide();
                    //$("#lmis_role").val('');

                }

                if($('#user_belongs_to-lmis').is(":checked")){

                    $("#ro_list_div :input").prop("disabled", true);
                    $("#ral_list_div :input").prop("disabled", false);
                    $("#lmis_roles_list").show();
                    $("#ro_list_div").hide();
                    $("#ral_list_div").show();

                }

            });



        //added on 27-07-2018 by Amol
            //for on clicked
            $('#user_belongs_to-dmi').click(function(){

                $("#ral_list_div :input").prop("disabled", true);
                $("#ro_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").show();
                $("#ro_list_div").show();
                $("#ral_list_div").hide();
                //$("#lmis_role").val('');

            });

            $('#user_belongs_to-lmis').click(function(){

                $("#ro_list_div :input").prop("disabled", true);
                $("#ral_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").show();
                $("#ro_list_div").hide();
                $("#ral_list_div").show();

            });


            //for already checked
            if($('#user_belongs_to-dmi').is(":checked")){

                $("#ral_list_div :input").prop("disabled", true);
                $("#ro_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").show();
                $("#ro_list_div").show();
                $("#ral_list_div").hide();
                //$("#lmis_role").val('');

            }

            if($('#user_belongs_to-lmis').is(":checked")){

                $("#ro_list_div :input").prop("disabled", true);
                $("#ral_list_div :input").prop("disabled", false);
                $("#lmis_roles_list").show();
                $("#ro_list_div").hide();
                $("#ral_list_div").show();

            }



    });
