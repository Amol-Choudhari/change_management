
	$(document).ready(function(){

			var printing_firm_form_status=$("#printing_firm_form_status").val();
			var printing_premises_form_status=$("#printing_premises_form_status").val();
			var printing_unit_form_status=$("#printing_unit_form_status").val();
			var payment_status=$("#payment_status").val();


			var printing_firm_reply_status=$("#printing_firm_reply_status").val();
			var printing_premises_profile_reply_status=$("#printing_premises_profile_reply_status").val();
			var printing_unit_reply_status=$("#printing_unit_reply_status").val();
			var payment_reply_status=$("#payment_reply_status").val();


			var printing_firm_current_level=$("#printing_firm_current_level").val();
			var printing_premises_profile_current_level=$("#printing_premises_profile_current_level").val();
			var printing_unit_current_level=$("#printing_unit_current_level").val();
			var payment_reply_status=$("#printing_payment_current_level").val();


			//for firm profile form
				if(printing_firm_form_status=="saved" && printing_firm_reply_status==""){

					$('#firm_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
					$('#firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}

				else if(printing_firm_form_status=="saved" && printing_firm_reply_status!=""){

					// comment the old condition for change progress bar color after customer reply (By pravin 02/06/2017)

					$('#firm_profile').removeClass('progress-bar-success');
					$('#firm_profile').removeClass('progress-bar-danger');
					$('#firm_profile').addClass('progress-bar-warning');
					$('#firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

					/*$('#firm_profile').addClass('progress-bar-success');
					$('#firm_profile').removeClass('progress-bar-danger');
					$('#firm_profile').removeClass('progress-bar-warning');
					$('#firm_profile_span').addClass('glyphicon-ok-sign').removeClass('glyphicon-remove-sign');*/


				}
				else if(printing_firm_form_status=="referred_back"){

					$('#firm_profile').removeClass('progress-bar-success');
					$('#firm_profile').removeClass('progress-bar-danger');
					$('#firm_profile').addClass('progress-bar-warning');
					$('#firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}
				else if(printing_firm_form_status=="approved"){

					$('#firm_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
					$('#firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

					/*$('#firm_profile').removeClass('progress-bar-success');
					$('#firm_profile').removeClass('progress-bar-danger');
					$('#firm_profile').removeClass('progress-bar-warning');
					$('#firm_profile').addClass('progress-bar-info');
					$('#firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
					*/
				}



			//for premises profile form
				if(printing_premises_form_status=="saved" && printing_premises_profile_reply_status==""){

					$('#premises_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
					$('#premises_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}
				else if(printing_premises_form_status=="saved" && printing_premises_profile_reply_status!=""){

					// comment the old condition for change progress bar color after customer reply (By pravin 02/06/2017)

					$('#premises_profile').removeClass('progress-bar-success');
					$('#premises_profile').removeClass('progress-bar-danger');
					$('#premises_profile').addClass('progress-bar-warning');
					$('#premises_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

					/*$('#premises_profile').addClass('progress-bar-success');
					$('#premises_profile').removeClass('progress-bar-danger');
					$('#premises_profile').removeClass('progress-bar-warning');
					$('#premises_profile_span').addClass('glyphicon-ok-sign').removeClass('glyphicon-remove-sign');*/

				}
				else if(printing_premises_form_status=="referred_back"){

					$('#premises_profile').removeClass('progress-bar-success');
					$('#premises_profile').removeClass('progress-bar-danger');
					$('#premises_profile').addClass('progress-bar-warning');
					$('#premises_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}
				else if(printing_premises_form_status=="approved"){

					$('#premises_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
					$('#premises_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

					/*$('#premises_profile').removeClass('progress-bar-success');
					$('#premises_profile').removeClass('progress-bar-danger');
					$('#premises_profile').removeClass('progress-bar-warning');
					$('#premises_profile').addClass('progress-bar-info');
					$('#premises_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
					*/
				}






			//for packing details form
				if(printing_unit_form_status=="saved" && printing_unit_reply_status==""){

					$('#printing_unit_details').removeClass('progress-bar-success').addClass('progress-bar-danger');
					$('#printing_unit_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}
				else if(printing_unit_form_status=="saved" && printing_unit_reply_status!=""){

					// comment the old condition for change progress bar color after customer reply (By pravin 02/06/2017)

					$('#printing_unit_details').removeClass('progress-bar-success');
					$('#printing_unit_details').removeClass('progress-bar-danger');
					$('#printing_unit_details').addClass('progress-bar-warning');
					$('#printing_unit_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

					/*$('#printing_unit_details').addClass('progress-bar-success');
					$('#printing_unit_details').removeClass('progress-bar-danger');
					$('#printing_unit_details').removeClass('progress-bar-warning');
					$('#printing_unit_span').addClass('glyphicon-ok-sign').removeClass('glyphicon-remove-sign');*/


				}
				else if(printing_unit_form_status=="referred_back"){

					$('#printing_unit_details').removeClass('progress-bar-success');
					$('#printing_unit_details').removeClass('progress-bar-danger');
					$('#printing_unit_details').addClass('progress-bar-warning');
					$('#printing_unit_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}
				else if(printing_unit_form_status=="approved"){

					$('#printing_unit_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
					$('#printing_unit_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

					/*$('#printing_unit_details').removeClass('progress-bar-success');
					$('#printing_unit_details').removeClass('progress-bar-danger');
					$('#printing_unit_details').removeClass('progress-bar-warning');
					$('#printing_unit_details').addClass('progress-bar-info');
					$('#printing_unit_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
					*/
				}








			//for payment details form
				if(payment_status=="saved" && payment_reply_status==""){

					$('#payment').removeClass('progress-bar-success').addClass('progress-bar-danger');
					$('#payment_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}
				else if(payment_status=="saved" && payment_reply_status!=""){

					$('#payment').removeClass('progress-bar-success');
					$('#payment').removeClass('progress-bar-danger');
					$('#payment').addClass('progress-bar-warning');
					$('#payment_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');


				}
				else if(payment_status=="referred_back"){

					$('#payment').removeClass('progress-bar-success');
					$('#payment').removeClass('progress-bar-danger');
					$('#payment').addClass('progress-bar-warning');
					$('#payment_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

				}
				else if(payment_status=="approved"){

					$('#payment').removeClass('progress-bar-danger').addClass('progress-bar-success');
					$('#payment_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

					/*$('#payment').removeClass('progress-bar-success');
					$('#payment').removeClass('progress-bar-danger');
					$('#payment').removeClass('progress-bar-warning');
					$('#payment').addClass('progress-bar-info');
					$('#payment_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
					*/
				}


		});
