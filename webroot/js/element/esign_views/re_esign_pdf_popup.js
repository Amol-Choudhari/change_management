$("#esign_submit_btn").prop("disabled", true);
						$("#plz_wait").hide();
							
							// Get the modal
							var modal = document.getElementById('declarationModal');

							// Get the <span> element that closes the modal
							var span = document.getElementsByClassName("close")[0];
							

						//updated on 28-05-2021 for Form Based Esign method
						//now direct called xml creation function from esigncontroller hereby
						//removed the call to cw-dialog.js function, no need now
						//applied multiple inner ajax calls
								
							$("#declaration_check_box").change(function() {
								
								if($(this).prop('checked') == true) {
									
									$("#plz_wait").show();
									
									var pdf_funtion_link = $("#preview_link").attr("href");
									
									$("#preview_link").hide();//added on 24-12-2021 by Amol, disbale option to preview as re-esign session destroyed once xml created

									 $.ajax({
											type:'POST',
											async: true,
											cache: false,
											url: pdf_funtion_link,
											beforeSend: function (xhr) { // for csrf token
													xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
											},
											success: function(){
									
												$.ajax({
													type:'POST',
													async: true,
													cache: false,
													url: "../esign/create_re_esign_xml_ajax",
													beforeSend: function (xhr) { // for csrf token
															xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
													},
													success: function(xmlresult){
														
														xmlresult = JSON.parse(xmlresult);
														
														$("#eSignRequest").val('');
														$("#aspTxnID").val('');
														
														$("#eSignRequest").val(xmlresult.xml);
														$("#aspTxnID").val(xmlresult.txnid);
														
														$("#plz_wait").hide();
														$("#esign_submit_btn").prop("disabled", false);//enable esign button
														
													}
												});
											}
										});
									
								}
								
								if($(this).prop('checked') == false){
									
									$("#esign_submit_btn").prop("disabled", true);
								}
							}); 
							
							
							$("#esign_submit_btn").click(function(){
								
								if(confirm("You are now Redirecting to CDAC Server for Esign Authentication")){
									
									return true;
								}else{
									return false;
								}
							});
							
							$(".close").click(function() {
								$(".modal").hide();
								return false;
							});

					//till here on 28-05-2021 for Form based method, and renoved unwanted scripts	