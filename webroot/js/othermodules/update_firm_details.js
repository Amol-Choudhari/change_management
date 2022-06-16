//call to login validations
$('#update_details').click(function (e) {

    if (edit_firm_validation() == false) {
        e.preventDefault();
    } else {
      $('#edit_firm_details').submit();
    }
});



	//This function is used for add_user, edit_user & user_profile form validations(admin users)
	function edit_firm_validation(){

			var last_email=$("#last_email").val();
      var last_mobile_no=$("#last_mobile_no").val();
      var email=$("#email").val();
      var mobile_no=$("#mobile_no").val();
			var reason=$("#reason").val();

      var value_return = 'true';

      //split path to find controller and action
      var path = window.location.pathname;
      var paths = path.split("/");
      var controller = paths[2];
      var action = paths[3];

      //first check if any of the value id change or not either mobile or email
			if(last_email==email && last_mobile_no==mobile_no){

				$.alert('To update the details you need to change either mobile no. or email id');
				return false;
			}


      if(email==""){

          $("#error_email").show().text("Please enter your email.");
					$("#email").addClass("is-invalid");
					$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
          value_return = 'false';

			}else{

          if(!email.match(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/)){

              $("#error_email").show().text("Entered email id is not valid.");
							$("#email").addClass("is-invalid");
							$("#email").click(function(){$("#error_email").hide().text;$("#email").removeClass("is-invalid");});
              value_return = 'false';
						}
			 }


        if(mobile_no==""){

            $("#error_mobile_no").show().text("Please Enter your Mobile No.");
						$("#mobile_no").addClass("is-invalid");
						$("#mobile_no").click(function(){$("#error_mobile_no").hide().text;$("#mobile_no").removeClass("is-invalid");});
            value_return = 'false';

        }else{

            if(!(mobile_no.match(/^(?=.*[0-9])[0-9]{10}$/g)))//also allow if 6 X $ 4 nos found //added on 12-10-2017 by Amol
            {
                $("#error_mobile_no").show().text("Mobile no. is not valid, only 10 digits no. allowed");
								$("#mobile_no").addClass("is-invalid");
								$("#mobile_no").click(function(){$("#error_mobile_no").hide().text;$("#mobile_no").removeClass("is-invalid");});
                value_return = 'false';

            }
            //first valid no. for mob.no, applid on 16-02-2021 by Amol
            var validfirstno = ['7','8','9'];
            //get first character of mobile no.
            var f_m_no = mobile_no.charAt(0);
            if($.inArray(f_m_no,validfirstno) != -1){
                //valid
            }else{
                $("#error_mobile_no").show().text("Invalid mobile number");
								$("#mobile_no").addClass("is-invalid");
								$("#mobile_no").click(function(){$("#error_mobile_no").hide().text;$("#mobile_no").removeClass("is-invalid");});
                value_return='false';
            }

        }

				if(reason==""){

            $("#error_reason").show().text("Please enter your Reason.");
						$("#reason").addClass("is-invalid");
						$("#reason").click(function(){$("#error_reason").hide().text;$("#reason").removeClass("is-invalid");});
            value_return = 'false';
        }

        if(value_return == 'false'){

					$.alert("Please check some fields are missing or not proper.");
					return false;

				}else{
					exit();

				}

    }

	$("#appl_id").css({'background-color':'#ccc'});
	$("#firm_name").css({'background-color':'#ccc'});

	$("#proceed_btn").prop('disabled',true);
	//called new ajax to create re-esign session
	$("#re_esign_concent").change(function() {

		if($('#re_esign_concent').prop('checked') == true) {
		var reason_to_re_esign = $("#reason_to_re_esign").val();
   
			$.ajax({
					type:'POST',
					url: "../othermodules/create_re_esign_session",
					data: {reason_to_re_esign:reason_to_re_esign},
					beforeSend: function (xhr) {
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(response){
						$("#proceed_btn").prop('disabled',false);
					}
				});
      }

  });



	// When the user clicks on the button, open the modal
		$("#proceed_btn").click(function(){

			var appl_id = $("#appl_id").val();
			var value_return = true;

			if($('#re_esign_concent').prop("checked") == false){

				$("#error_re_esign_concent").show().text("Check this concent to proceed");
        $("#re_esign_concent").addClass("is-invalid");
        $("#re_esign_concent").click(function(){$("#error_re_esign_concent").hide().text;$("#re_esign_concent").removeClass("is-invalid");});
				value_return = false;
			}
			if(value_return==false){

				return false;
			}else{

				//modal.style.display = "block";
				$("#declarationModal").show();

				var customer_id_split = appl_id.split("/");
				var pdf_funtion_link = null;

				if(customer_id_split[1]=='1'){
					pdf_funtion_link = '../Applicationformspdfs/grant_ca_certificate_pdf';

				}else if(customer_id_split[1]=='2'){
					pdf_funtion_link = '../Applicationformspdfs/grant_printing_certificate_pdf';

				}else if(customer_id_split[1]=='3'){
					pdf_funtion_link = '../Applicationformspdfs/grant_laboratory_certificate_pdf';
				}

				$("#preview_link").attr("href",pdf_funtion_link);

				return false;
			}


		});


		var email_updated = $('#email_updated').val();
		var mob_updated = $('#mob_updated').val();

		if(email_updated == 'yes'){
			$("#email").css('border','2px solid green');
		}

		if(mob_updated == 'yes'){
			$("#mobile_no").css('border','2px solid green');
		}
