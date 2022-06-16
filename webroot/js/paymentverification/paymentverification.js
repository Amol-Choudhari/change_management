
	$( document ).ready(function() {

		$("#not_confirmed_reason").hide();
		$("#referred_back").hide();
		$("#payment_verificatin_action").css('display','none');

		var actionvalue = $("#actionvalue").val();
		var paymentstatus = $("#paymentstatus").val();


		$('#action').change(function(){


			if($('#action').val() == 1)
			{
				$("#payment_verificatin_action").css('display','block');
				$("#not_confirmed_reason").show();

			}else if($('#action').val() == 0){
				$("#payment_verificatin_action").css('display','block');
				$("#not_confirmed_reason").hide();

			}
		});


		if( actionvalue == 1) {

			$("#referred_back").show();

		}

		if(paymentstatus == 'not_confirmed' || paymentstatus == 'confirmed') {

			$("#form_outer_main :input").prop("disabled", true);
			$("#form_outer_main :input[type='select']").prop("disabled", true);
			$("#form_outer_main :input[type='submit']").css('display','none');

		}

	});


	$("#payment_verificatin_action").click(function(e){

		if(payment_fields_referred_back_validation()==false){
			e.preventDefault();
		}else{
			$("#reasone_comment").submit();
		}

	});


	function payment_fields_referred_back_validation()
	{

		var reasone_list_comment = $('#reasone_list_comment').val();
		var reasone_comment = $('#reasone_comment').val();
		var value_return = 'true';

		if($('#action').val() == 1){

			if(reasone_list_comment == ''){

			$("#error_reasone_list_comment").show().text("Please select reason");
			$("#error_reasone_list_comment").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#reasone_list_comment").click(function(){$("#error_reasone_list_comment").hide().text;});
			value_return = 'false';

			}

			if(reasone_comment == ''){

				$("#error_reasone_comment").show().text("Please enter comment for reason");
				$("#error_reasone_comment").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$("#reasone_comment").click(function(){$("#error_reasone_comment").hide().text;});
				value_return = 'false';
			}


			if(value_return == 'false')
			{
				$.alert("Please check some fields are missing or not proper.");
				return false;
			}
			else{
				exit();

			}

		}else{

			exit();
		}

	}


	//script added on 15-10-2019 by Amol to show hide modal
	// to show already used trsac id application details.
	$("#show_existed_appl").click(function(){

		$("#existed_appl_details").show();

	});

	$(".close").click(function(){

		$("#existed_appl_details").hide();
	});
