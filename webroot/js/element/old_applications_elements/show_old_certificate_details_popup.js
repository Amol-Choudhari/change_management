
	var customer_id = $("#customer_flash_id").val();
	
	$(document).ready(function () {

		$('#grant_date').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			endDate: new Date(),
			clearBtn: true
		});

		$('#last_ren_year').datepicker({
			format: "yyyy",
			viewMode: "years",
			minViewMode: "years",
			autoclose: true
		});


		//functionality to be done when update button clicked
		$("#update_old_date").click(function(){

			var grant_date = $("#grant_date").val();
			var last_ren_year = $("#last_ren_year").val();
			var reason_to_update = $("#reason_to_update").val();
			var value_return = 'true';

			if(grant_date==""){

				$("#error_grant_date").show().text("Grant date can not be blank");
				$("#grant_date").addClass("is-invalid");
				$("#grant_date").click(function(){$("#error_grant_date").hide().text;$("#grant_date").removeClass("is-invalid");});
				value_return = 'false';
			}


			if(last_ren_year==""){

				$("#error_last_ren_year").show().text("Last Renewal year can not be blank");
				$("#last_ren_year").addClass("is-invalid");
				$("#last_ren_year").click(function(){$("#error_last_ren_year").hide().text;$("#last_ren_year").removeClass("is-invalid");});
				value_return = 'false';
			}
			
			
			if(reason_to_update==""){

				$("#error_reason_to_update").show().text("If your are updating/approving the dates, please write a genuine remark.");
				$("#reason_to_update").addClass("is-invalid");
				$("#reason_to_update").click(function(){$("#error_reason_to_update").hide().text;$("#reason_to_update").removeClass("is-invalid");});
				value_return = 'false';
			}


			if(value_return == 'true'){
				
				var last_ren_day_month = $("#last_ren_day_month").val();
				
				if(last_ren_day_month){
					var last_grant_date = last_ren_day_month + last_ren_year;
				}else{
					var last_grant_date = grant_date;
				}

				var valid_upto_date = show_valid_upto_date_func(last_grant_date);//called common function to get valid upto date


				$.confirm({
			
					icon: 'fas fa-info-circle',
					content: "According to the changed dates, now Certificate is valid upto " + valid_upto_date + ". If this is proper, then press CONFIRM to update",
					columnClass: 'col-md-6 col-md-offset-3',
					buttons: {
						confirm: { 
							btnClass: 'btn-green',
							action: function () {

								if(last_ren_day_month){
									var last_ren_date = last_ren_day_month + last_ren_year;
								}else{
									var last_ren_date = null;
								}
			
								//ajax call to update table records with dates on controller side.
								$.ajax({
									type: "POST",
									url: "../AjaxFunctions/updateOldCertDates",
									data: {grant_date:grant_date, last_ren_date:last_ren_date, reason_to_update:reason_to_update, valid_upto_date:valid_upto_date},
									beforeSend: function (xhr) { // Add this line
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
									},
									success: function(response){
										response = response.match(/~([^']+)~/)[1];
										if($.trim(response)=='done'){
											$.alert({
												content:"New dates are updated successfully.",
												onClose: function(){
													location.reload();
												}
											});
											
										}else{
											alert('Sorry.. Dates are not updated, please try again');
											return false;
										}
									}
								});
								
								//return false;
							}
						},
						cancel:{
							btnClass: 'btn-red',
							action: function () {}
						},
					}
				});
			}

			if(value_return == 'false'){
				return false;
			}else{
				exit();
			}

		});
		
		
		
		//to show valid upto date on grant date changed.
		$("#grant_date").change(function(){

			var grant_date = $("#grant_date").val();
			var valid_upto_date = show_valid_upto_date_func(grant_date);

			$("#show_valid_upto_date").show().text("Now certificate is valid upto " + valid_upto_date);
			$("#show_valid_upto_date").css({"color":"blue","font-size":"13px","font-weight":"500","text-align":"right","margin-bottom":"10px"});
		
		});



		//to show valid upto date on last renewal grant date changed.
		$("#last_ren_year").change(function(){

			var last_ren_day_month = $("#last_ren_day_month").val();
			var last_ren_year = $("#last_ren_year").val();
			var grant_date = last_ren_day_month + last_ren_year;
			var valid_upto_date = show_valid_upto_date_func(grant_date);

			$("#show_valid_upto_date").show().text("Now certificate is valid upto " + valid_upto_date);
			$("#show_valid_upto_date").css({"color":"blue","font-size":"13px","font-weight":"500","text-align":"right","margin-bottom":"10px"});

		});



		//common function to get valid upto date with last grant date as parameter
		function show_valid_upto_date_func(grant_date){

			var split_customer_id = customer_id.split("/");
			var certification_type = split_customer_id[1];
			var split_grant_date = grant_date.split("/");
			var get_grant_month = split_grant_date[1];
			var get_grant_year = split_grant_date[2];

			
			//condition applied on 23-09-2022 for new order validity date as per order on "01-04-2021"
			//now PP and Labs will also have validity of 5 years. grant after 31-03-2021
			if (get_grant_month <= 3 && get_grant_year <= 2021) {

				if(certification_type == 1){

					if(get_grant_month <= 3){
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(4);
					}else{
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(5);
					}
					
					var valid_upto_date = '31/03/'+ valid_upto_year;

				}else if(certification_type == 2){

					var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);
					var valid_upto_date = '31/12/'+ valid_upto_year;

				}else if(certification_type == 3){

					if(get_grant_month <= 6){
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);
					}else{
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(2);
					}
					var valid_upto_date = '30/06/'+ valid_upto_year;
				}
				
			}else{
				
				if(certification_type == 1){

					if(get_grant_month <= 3){
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(4);
					}else{
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(5);
					}
					
					var valid_upto_date = '31/03/'+ valid_upto_year;

				}else if(certification_type == 2){

					var valid_upto_year =  parseInt(get_grant_year)+ parseInt(4);
					var valid_upto_date = '31/12/'+ valid_upto_year;

				}else if(certification_type == 3){

					if(get_grant_month <= 6){
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(4);
					}else{
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(5);
					}
					var valid_upto_date = '30/06/'+ valid_upto_year;
				}
			}

			return valid_upto_date;
		}


	});