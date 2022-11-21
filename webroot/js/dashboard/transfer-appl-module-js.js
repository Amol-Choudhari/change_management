
	$("#transfer_btn").click(function(e){

		if(transfer_appl_validation() == false){
			
			e.preventDefault(); 
		}else{
			$('#transfer_appl_form').submit();
		}
	});

			

	$('#appl_type').change(function(){
		
		$('#from_office').val('');//reset 'from office' dropdown
	});
	

	//to fetch application id list for specific appl type and office.
	$('#from_office').change(function(){
		
		var from_office = $('#from_office').val();
		var appl_type = $('#appl_type').val();
		
		if(appl_type != ''){
		
			$.ajax({
				
				type: "POST",
				url:"../dashboard/get_office_wise_appl",
				data: {from_office:from_office,appl_type:appl_type},
				beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function (data) {
					
					$("#appl_id").html('');
					var resArray = data.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response
					resArray = JSON.parse(resArray);//response is JSOn encoded to parse JSON

					$("#appl_id").append("<option value=''>--Select--</option>");//for first option with value blank
					//taking each customer id from array and creating options tag with value and text.
					$.each(resArray, function(value, value) {
						$("#appl_id").append($("<option></option>")
						.attr("value", this.customer_id).text(this.customer_id));
					});

				}
			});
		
		}else{
			$('#error_appl_type').text('Please Select Application type').css({'color':'red','font-size':'12px'});
			$('#appl_type').change(function(){$('#error_appl_type').hide();});
			$('#from_office').val('');
			
			return_value = false;
		}
		
	});


		
	//to get selected application status and show on window
	$('#appl_id').change(function(){
		
		var appl_id = $('#appl_id').val();
		var appl_type = $('#appl_type').val();
		$.ajax({
			
			type: "POST",
			url:"../dashboard/get_appl_status_details",
			data: {appl_id:appl_id,appl_type:appl_type},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
				
				var response = data.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response				
				$("#appl_status").html('');//clear list
				//creating HTML for output
				var showHtml = '<h5 class="colNavy">Application Status : <label class="cRed">'+response+'</label></h5>';
				//concatinate HTML if condition matched.
				if(!(response == 'Renewal Granted' || response == 'Granted')){							
					showHtml = showHtml.concat('<div class="checkbox"><label><input id="consent_check" type="checkbox" value="" class="cRed">Transfer this application anyway</label><div id="error_consent_check"></div></div>');
				}
				$("#appl_status").html(showHtml);
			}
		});
	});



	//form field not empty validations function
	function transfer_appl_validation(){

		var appl_type = $('#appl_type').val();
		var from_office = $('#from_office').val();
		var appl_id = $('#appl_id').val();
		var to_office = $('#to_office').val();
		var remark = $('#remark').val();
		var value_return = 'true';
		
		if(appl_type==''){

			$("#error_appl_type").show().text("Please Select Application type");
			$("#appl_type").addClass("is-invalid");
			$("#appl_type").click(function(){$("#error_appl_type").hide().text;$("#appl_type").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(from_office==''){

			$("#error_from_office").show().text("Please Select Office");
			$("#from_office").addClass("is-invalid");
			$("#from_office").click(function(){$("#error_from_office").hide().text;$("#from_office").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(appl_id==''){	

			$("#error_appl_id").show().text("Please Select Application Id");
			$("#appl_id").addClass("is-invalid");
			$("#appl_id").click(function(){$("#error_appl_id").hide().text;$("#appl_id").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(to_office==''){		
			
			$("#error_to_office").show().text("Please Select Office");
			$("#to_office").addClass("is-invalid");
			$("#to_office").click(function(){$("#error_to_office").hide().text;$("#to_office").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(remark==''){	

			$("#error_remark").show().text("Please write Remark/Reason");
			$("#remark").addClass("is-invalid");
			$("#remark").click(function(){$("#error_remark").hide().text;$("#remark").removeClass("is-invalid");});
			value_return = 'false';
		}

		if(!($('#appl_status h5 label').text() == 'Renewal Granted' || $('#appl_status h5 label').text() == 'Granted')){

			if($('#consent_check').prop("checked") == false){

				$("#error_consent_check").show().text("Please check this consent, as application is in progress");
				$("#consent_check").addClass("is-invalid");
				$("#consent_check").click(function(){$("#error_consent_check").hide().text;$("#consent_check").removeClass("is-invalid");});
				value_return = 'false';
			}
		}

		if(value_return == 'false'){
			var msg = "Please check some fields are missing or not proper.";
			renderToast('error', msg);
			return false;
		}else{
			exit();
		}
	}
