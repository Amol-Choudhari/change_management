$("#profile_pic").change(function(){

	file_browse_onclick('profile_pic');
	return false;
});

$(".submit_btn").click(function(e){

	if(add_user_validations()==false){
		e.preventDefault();
	}else{
		$("#add_user_form").submit();
	}

});




$(document).ready(function(){

	//to show/hide lmis user role list
    //for already checked
    //below all scripts updated on 27-07-2018 by Amol

    bsCustomFileInput.init();//added on 06-05-2021 for profile pic

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


    //FOR CHECKING THE EMAIL & CONFIRM EMAIL ARE SAME OR NOT ON 22-12-2021 BY AKASH
    $('#confirm_email').focusout(function(){

        var confirm_email = $("#confirm_email").val();
        var email = $('#email').val();
        if (email != '') {
            if (confirm_email != '') {
                if (email != confirm_email) {
                    $.alert('Email not matched!!');
                    $('#confirm_email').val('');
                }
            }
        }
    });


    //FOR CHECKING THE MOBILE ALREADY EXITS AJAX in customers table AND VALIDATION IS ADDED BY AKASH ON 22-12-2021
    $('#phone').focusout(function(){

        var phone = $("#phone").val();

        if (phone != '') {
            $.ajax({
                type : 'POST',
                url : '../AjaxFunctions/check_mobile_number_exist_in_users_table',
                async : true,
                data : {phone:phone},
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success : function(response){

                    if($.trim(response)=='yes'){

                        $.alert({
                            title: "Alert!",
                            content: 'The entered mobile number is already used. Please verify and enter again!!',
                            typeAnimated: true,
                            buttons: {
                                Retry: {
                                    text: 'Retry',
                                    btnClass: 'btn-red',
                                    action: function(){
                                        $("#phone").val('');
                                    }
                                },
                            }
                        });
                    }
                }
            });
        }
    });


     //FOR CHECKING THE Email ALREADY EXITS AJAX in customers table AND VALIDATION IS ADDED BY AKASH ON 21-02-2022
     $('#email').focusout(function(){

        var email = $("#email").val();

        if (email != '') {
            $.ajax({
                type : 'POST',
                url : '../AjaxFunctions/check_email_exist_in_users_table',
                async : true,
                data : {email:email},
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success : function(response){

                    if($.trim(response)=='yes'){

                        $.alert({
                            title: "Alert!",
                            content: 'The entered email id is already used. Please verify and enter again!!',
                            typeAnimated: true,
                            buttons: {
                                Retry: {
                                    text: 'Retry',
                                    btnClass: 'btn-red',
                                    action: function(){
                                        $("#email").val('');
                                    }
                                },
                            }
                        });
                    }
                }
            });
        }
    });

    var return_error_msg = $('#return_error_msg').val();
                
    if(return_error_msg != ''){
        $.alert(return_error_msg);
    }