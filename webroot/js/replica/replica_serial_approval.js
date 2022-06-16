$(document).ready(function(){

	$("#esign_submit_btn").prop("disabled", true);
		$("#plz_wait").hide();

			// Get the modal
			var modal = document.getElementById('declarationModal');

			// Get the button that opens the modal
			var btn = document.getElementById('che_approve');

			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close")[0];



			// When the user clicks on the button, open the modal
			btn.onclick = function(e) {
				e.preventDefault();
				modal.style.display = "block";
				return false;
			}

			$("#declaration_check_box").change(function() {

				if($(this).prop('checked') == true) {

					$("#plz_wait").show();


				//now direct called xml creation function from esigncontroller hereby
				//removed the call to cw-dialog.js function, no need now
				//applied multiple inner ajax calls
					 $.ajax({
							type:'POST',
							async: true,
							cache: false,
							url: "../replica/replica_allotment_pdf_view",
							beforeSend: function (xhr) { // Add this line
									xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
							},
							success: function(){

								$.ajax({
									type:'POST',
									async: true,
									cache: false,
									url: "../esign/create_esign_xml_ajax",
									beforeSend: function (xhr) { // Add this line
											xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									},
									success: function(xmlresult){

										xmlresult = JSON.parse(xmlresult);console.log(xmlresult);

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

	//Form based method, and renoved unwanted scripts



});

//var tableFormData = $('#tableFormData').val();
