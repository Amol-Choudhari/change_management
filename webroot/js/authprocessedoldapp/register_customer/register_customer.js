	////| Register Customer from the Auth Old Processed Application JS File for Validations |////
	
	$("#upload_file").change(function(){
		file_browse_onclick('upload_file');
		return false;
	});

	$(".getState").change(function(){
		get_district();
	});

	$("#register_btn").click(function(e){
		if(auth_primary_reg_validations() == false){
			e.preventDefault();
		}else{
			$("#reg_customer_form").submit();
		}
	});
	
	
	return_error_msg = $("#return_error_msg").val();  
	
	if(return_error_msg != ''){

		$.alert({
			title: 'Alert!',
			icon: 'fa fa-warning',
			columnClass: 'medium',
			content: return_error_msg
		});
	
		$('#add_firm_form').trigger("reset");
	}

	$(document).ready(function(){
		//added these 2 lines on 14-07-2018,
		$("#email").click(function(){$("#error_already_exist").hide().text;});
		$("#confirm_email").click(function(){$("#error_already_exist").hide().text;});

		//added on 12-08-2017 by Amol to avoid copy paste on confirm email field
		$('#confirm_email').bind("cut copy paste",function(e) {
			e.preventDefault();
		});
	});
	
	$("#state").val('');//line added on 14-07-2018


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

	function get_new_captcha(){
		
		$.ajax({
			type: "POST",
			async:true,
			url:"refresh_captcha_code",
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function (data) {
				$("#captcha_img").html(data);
			}
		});
	}
	


	//FOR CHECKING THE EMAIL ALREADY EXITS AJAX in customers table AND VALIDATION IS ADDED BY AKASH ON 22-12-2021
    $('#email').focusout(function(){

        var email = $("#email").val();

        if (email != '') {

            $.ajax({
                type : 'POST',
                url : '../AjaxFunctions/check_email_exist_in_customer_table',
                async : true,
                data : {email:email},
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success : function(response){

                    if($.trim(response)=='yes'){

                        $.alert({
                            title: "Alert!",
							columnClass: 'medium',
                            content: 'The Email is already used. Please verify and enter again.',
							type: 'red',
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


	//FOR CHECKING THE EMAIL & CONFIRM EMAIL ARE SAME OR NOT ON 08-03-2022 BY AKASH
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