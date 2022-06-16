
$(document).ready(function(){

	$("#appl_id").change(function(){

		var appl_id = $("#appl_id").val();

		$.ajax({
			type: "POST",
			url: "../othermodules/on_select_set_customer_id_session",
			data: {appl_id:appl_id},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){

				var split_response = response.split("@");
				var file_path = split_response[0];
				var valid_upto = 'After re-esign, this Certificate will be valid upto '+split_response[1];

				var appl_id_split = appl_id.split("/");
				var cert_pdf_name = 'G-'+appl_id_split[0]+'-'+appl_id_split[1]+'-'+appl_id_split[2]+'-'+appl_id_split[3]+'(2).pdf';
				var cert_link = '<a target="_blank" href="'+file_path+'" >Click Here to Check Previously Granted Certificate</a>';
				$("#view_certificate_link").text("");
				$("#view_certificate_link").append(cert_link);
				$("#view_certificate_link").css({"color":"blue","font-size":"12px","font-weight":"500","text-align":"right","margin-top":"10px"});

				$("#view_valid_upto").text("");
				$("#view_valid_upto").append('<blink class="blink">'+valid_upto+'</blink>');
				$("#view_valid_upto").css({"color":"Red","font-size":"12px","font-weight":"300","text-align":"right","margin-top":"5px"});
			}
		});





	});


	// When the user clicks on the button, open the modal
		$("#proceed_btn").click(function(){

			var appl_id = $("#appl_id").val();
			var reason_to_re_esign = $("#reason_to_re_esign").val();
			var value_return = true;
			if(appl_id == ''){

				$("#error_appl_id").show().text("Select Application Id");
				$("#error_appl_id").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#appl_id").click(function(){$("#error_appl_id").hide().text;});
				value_return = false;

			}
			if(reason_to_re_esign == ''){

				$("#error_reason_to_re_esign").show().text("Enter Reason to Re-esign");
				$("#error_reason_to_re_esign").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#reason_to_re_esign").click(function(){$("#error_reason_to_re_esign").hide().text;});
				value_return = false;

			}
			if($('#re_esign_concent').prop("checked") == false){

				$("#error_re_esign_concent").show().text("Check this concent to proceed");
				$("#error_re_esign_concent").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#re_esign_concent").click(function(){$("#error_re_esign_concent").hide().text;});
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
					pdf_funtion_link = '../applicationformspdfs/grant_ca_certificate_pdf';

				}else if(customer_id_split[1]=='2'){
					pdf_funtion_link = '../applicationformspdfs/grant_printing_certificate_pdf';

				}else if(customer_id_split[1]=='3'){
					pdf_funtion_link = '../applicationformspdfs/grant_laboratory_certificate_pdf';
				}

				$("#preview_link").attr("href",pdf_funtion_link);

				return false;
			}


		});


	//called new ajax to create re-esign session
	$("#re_esign_concent").change(function() {

		if($(this).prop('checked') == true) {
		var reason_to_re_esign = $("#reason_to_re_esign").val();

			$.ajax({

				type:'POST',
				async:true,
				url: "../othermodules/create_re_esign_session",
				data: {reason_to_re_esign:reason_to_re_esign},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response){}

			});

		}

	});
});


//the function is commented on 28-05-2021 by Amol
//now using Form based method for esign, so script calls not required.
/*
function CDAC_re_esign_ajax_calls(){

	var once_no = '000000000000';
	var return_value = '';

			$.ajax({
				type:'POST',
				async: true,
				cache: false,
				data:{once_no:once_no},
				url: "../esign/request_esign_otp",
				success: function(response){
					var token_session_id = response;
					$('#Token_key_id').val(token_session_id);

					//below ajax to generate xml with signature
					$.ajax({
						type:'POST',
						async: true,
						cache: false,
						url: "../esign/create_re_esign_xml_ajax",
						success: function(response_xml){
							var xml_content = response_xml;

						//below ajax to request esig OTP, if success then redirect to CDAC server.
						//with CORS functionality
							$.ajax({
								type: 'POST',
								crossDomain: true,
								xhrFields: {
										withCredentials: true
								},

								//url:'https://esignservice1.cdac.in/esignservice2.1/2.1/signdoc',
								url:'https://esignservice.cdac.in/esign2.1/2.1/signdoc',//changed URL on 20-01-2021

								data: xml_content,
								contentType: 'application/xml',
								//dataType: "json",

								success : function(esignRes) {

									//redirecting to CDAC url for OTP authentication
									var espResp = esignRes.responseXml;
									var aspUrl = esignRes.responseUrl;
									var status = esignRes.status;
									if (status == 1) {

										alert("You are now redirecting to another domain.");
										window.location.replace(aspUrl);   ///OTP  Page Url
									} else if (status == 0) {

										alert("Sorry.. We found some error in the process.");
										if (aspUrl == 'NA') {
											//handle the Error Cases Here
										}

									}

								}
							});

						}

					});
				}

			});

			return_value = 'false';

	return return_value;
}
  */
