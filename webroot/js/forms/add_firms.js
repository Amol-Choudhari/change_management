
	$( document ).ready(function() {
		
		return_error_msg = $("#return_error_msg").val();	
		if(return_error_msg != ''){
			$.alert(return_error_msg);
			$('#add_firm_form').trigger("reset");
		}
	});


	$("#save").click(function(e){
		
		if(add_firm_validations()==false){
			e.preventDefault();
		}else{
			$("#add_firm_form").submit();
		}
	});
	
	
	$("#certification_type").change(function(){
		
		//show_charges();
	});
	
	
	$("#commodity_category").change(function(){
		get_commodity();
	});


	$("#profile_pic").change(function(){
		file_browse_onclick('profile_pic');
		return false;
	});
	

	$("#state").change(function(){
		get_district();
	});
	
	
	//function to district
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

	
	//function to get the commodity
	function get_commodity(){

		$("#commodity").find('option').remove();
		var commodity = $("#commodity_category").val();

		//applied check on 08-06-2021 by Amol, CA export can not apply for BEVO category -> THIS MESSAGE AND CONDITION IS CHANGED FOR THE EXPORT FLOW UPADTES (AKASH [30-08-2022])
		if($('#certification_type option:selected').val()=='1' && $('#radioSuccess1').is(':checked') && commodity != '14'){
			$.alert('As you have selected the Export option, you can not select category other than <b>Fruits & Vegetable</b>.');
			return false;
		}
		
		$.ajax({
			type: "POST",
			async:true,
			url:"../AjaxFunctions/show-commodity-dropdown",
			data: {commodity:commodity},
			beforeSend: function (xhr) { // Add this line
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
				$("#commodity").append(data);
			}
		});
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$("#certification_no").click(function(){$("#duplicate_certification_no_error").hide().text;});
	$("#error_aadhar_card_no").hide();
	$("#msg_mobile_no").hide();
	
	//below ajax code added on 09-08-2017 by Amol to fetch default charge on page load
	var form_data = '';
	form_data = $("#add_firm_form").serializeArray();
	
	$.ajax({
		type: "POST",
		url: "../AjaxFunctions/show_charge",
		data: form_data, 
		beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		},
		success: function(response){
			$(".show_charge").html(response);
		}
	});
	
	$("#total_charge").prop("readonly", true);//added on 09-08-2017 by Amol

	$('#once_card_no').click(function(){

		var once_card_no = $('#once_card_no').val();

		if(once_card_no.match(/^(?=.*[0-9])[0-9]{12}$/g)){
			
		}else{

			//alert("aadhar card number should be of 12 numbers only");
			$("#error_aadhar_card_no").show().text("Only numbers allowed, min & max length is 12");
			//$("#error_aadhar_card_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
			$("#once_card_no").focusout(function(){$("#error_aadhar_card_no").hide().text;});
			return false;
		}
	});

	$('#mobile_no').click(function(){
		
		$("#msg_mobile_no").show().text("OTP will be sent on this no. to reset password");
		//$("#msg_mobile_no").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
		$("#mobile_no").focusout(function(){$("#msg_mobile_no").hide().text;});
		return false;
		
	});


	// To get selected sub commodities and its charge with total	

	var total_charge = 0;
	
	$('#commodity').change(function (e) {

		e.preventDefault();
			
		//added this condition on 13-07-2018 by Amol
		if($('#commodity option:selected').val()!='')
		{
			//check if already selected in the list
			//added on 15-09-2021 by Amol
			var com_val = $('#commodity option:selected').val();
			var proceed_val = 'yes';
			$("#selected_commodity > option").each(function(index,value){
				
				if(com_val == $(this).val()){
					
					proceed_val = 'no';
					return false;
				}
			});

			if(proceed_val == 'no'){
				alert('The commodity is already selected');
				return false;
			}

			//var charge = $('#commodity option:selected').attr('id');
			
			//updated on 08-08-2017 By Amol to disable/enable BEVO/Non BEVO options on select sub commodity
			if($('#certification_type option:selected').val()=='1')
			{
			
				//id 79,80,81 added on 05-09-2022 for Fat Spread updates after UAT
				if($('#commodity option:selected').val()=='172'
					|| $('#commodity option:selected').val()=='173'
					|| $('#commodity option:selected').val()=='79'
					|| $('#commodity option:selected').val()=='80'
					|| $('#commodity option:selected').val()=='81') {
						
					//$("#commodity_category option[value!='106']").remove();
					$("#commodity_category option[value!='106']").prop('disabled', true);
					$("#commodity_category option[value='11']").prop('disabled', false);//added on 05-09-2022 for Fat Spread updates after UAT
					$("#selected_commodity").append($('#commodity option:selected'));
					$("#selected_bevo_nonbevo_msg").show().text("Please note You have selected Form E commodities");
					$("#selected_bevo_nonbevo_msg").css({"color":"blue","font-size":"12px","font-weight":"500","text-align":"right"});

				}else{

					//$("#commodity_category option[value='106']").remove();
					$("#commodity_category option[value='106']").prop('disabled', true);
					$("#commodity_category option[value='11']").prop('disabled', true);//added on 05-09-2022 for Fat Spread updates after UAT
					$("#selected_commodity").append($('#commodity option:selected'));
					$("#selected_bevo_nonbevo_msg").show().text("Please note You have selected Form A commodities");
					$("#selected_bevo_nonbevo_msg").css({"color":"blue","font-size":"12px","font-weight":"500","text-align":"right"});
				}
			}else{
				$("#selected_commodity").append($('#commodity option:selected'));
			}
			
			
			//below ajax code added on 09-08-2017 by Amol to call ajax function to calculate main category wise total charge
			//on addition of subcommodity to the list
			if($('#certification_type option:selected').val()=='1')
			{
				var selected_sub_commodities = $("#selected_commodity").val();
					
				var form_data = '';
				form_data = $("#add_firm_form").serializeArray();
				form_data.push(	{name: "selected_sub_commodities",value: selected_sub_commodities});

				$.ajax({
					type: "POST",
					url: "../AjaxFunctions/calculate_category_wise_charge",
					data: form_data,
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(response){
						$(".show_charge").html(response);
					}
				}); 
			}
			
			/* total_charge = (Number(total_charge)+Number(charge));
			$('#total_charge').val(total_charge); */
		}
		
	});	


	// To remove selected sub commodities and its charge with total from list
	var total_charge = $('#total_charge').val();
	
	$('#selected_commodity').change(function () {
		
		//var charge = $('#selected_commodity option:selected').attr('id');
		
		if($(this).find('option:selected').val() != '')
		{
			var commodity_id = $(this).find('option:selected').val(); 
			var commodity_name = $(this).find('option:selected').text(); 
		
			$('#commodity').append("<option value='"+commodity_id+"'>"+commodity_name+"</option>");
			$("#commodity").append($("#commodity option:gt(0)").sort(function (a, b) {return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;}));
			$(this).find('option:selected').remove();
		}

		if($('#selected_commodity option').length == 1){
			$("#commodity_category option").prop('disabled', false);
		}
		
		//$("#selected_commodity").Multiselect('select_all');
		$("#selected_commodity option[value!='']").prop('selected',true);
		 
		//below ajax code added on 09-08-2017 by Amol to call ajax function to calculate main category wise total charge 
		//on removal of selected subcommodity from list 
		if($('#certification_type option:selected').val()=='1')
		{ 
			var selected_sub_commodities = $("#selected_commodity").val();
				
			var form_data = '';
			form_data = $("#add_firm_form").serializeArray();
			form_data.push(	{name: "selected_sub_commodities",value: selected_sub_commodities});

			$.ajax({
				type: "POST",
				// url: "../customerforms/calculate_category_wise_charge",
				url: "../AjaxFunctions/calculate_category_wise_charge",
				data: form_data,
				beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response){
					$(".show_charge").html(response);
				}
			});
		}
	 
		//	var total_charge = (Number(total_charge)-Number(charge));
		//	$('#total_charge').val(total_charge);
	});


	// To append selected Packaing types in mutiple selected box from list
	$("#other_packaging_details_box").hide();
	
	$('#packaging_materials').change(function () {
		
		var type_value = $('#packaging_materials option:selected').val();
		
		$("#selected_packaging_materials").append($('#packaging_materials option:selected'));
		
		if(type_value == 18){
			$("#other_packaging_details_box").show();
			//$("#selected_packaging_materials_box").hide();
		}else{
			//$("#other_packaging_details_box").hide();
			$("#selected_packaging_materials_box").show();
		}
		
	});


	// To remove selected Packaing types in mutiple selected box from list
	$('#selected_packaging_materials').change(function () {
		
		if($(this).find('option:selected').val() == 18)
		{
			$("#other_packaging_details_box").hide();
		}

		$(this).find('option:selected').remove();

		$('#selected_packaging_materials option').prop('selected',true);
	 
	});
	
	

	// if certification type is printing press hide commodity box and show packaging box
	$('#packaging_type_box').hide();
	$('#certification_type').change(function () {
				
		var value = $('#certification_type option:selected').attr('value');

		if(value == 2){
			$('#commodity_box').hide();
			$('#packaging_type_box').show();
		}else if(value == 1 || value == 3){
			$('#commodity_box').show();
			$('#packaging_type_box').hide();
		}
	});


	// If If 'Grant of permission to Printing Press' selected (added by pravin)
	$('#certification_type').change(function () {
		
		var value = $('#certification_type option:selected').attr('value');
						//below AND portion added on 09-10-2017 by Amol temp. to hide lab export
		if(value == 2)	//changed condition value from 2 & 3 to 2 only on 31-08-2017 by Amol
		{
			$('#radioSuccess2').prop('checked', true);
			$('#export_unit').hide();
			$('#sponsored_press_by_ca').show(); // add by pravin bhakare 18-10-2021 
												//below AND portion commented on 09-10-2017 by Amol temp. to hide lab export
		}else if(value == 1 || value == 3)		//changed condition value from 1 to 1&3 on 31-08-2017 by Amol
		{
			$('#export_unit').show();
			$('#sponsored_press_by_ca').hide(); // add by pravin bhakare 18-10-2021 
			
		}
	
	});	//commented on 27-04-2017 by Amol to hide Export unit option
		
		
	$('#certification_type').change(function () {
			
		$('#radioPrimary2').is(":checked");
		$("#radioPrimary2").prop("checked", true);
		$("#old_granted_certificate").hide();
		$("#last_renewal_details").hide();
		
		//below 3 lines of added on 09-12-2019 by Amol
		$("#selected_commodity").empty();
		$("#selected_commodity").append('<option value="">--Selected--</option>');
		$("#selected_bevo_nonbevo_msg").text('');
	});
		

	// Start To check Added firm is new or old granted firm 
	// Done By pravin 26-09-2017 for already checked
	if($('#radioPrimary1').is(":checked")){
		$("#old_granted_certificate").show();
	}else if($('#radioPrimary2').is(":checked")){
		$("#old_granted_certificate").hide();
	}


	//for on clicked
	$('#radioPrimary1').click(function(){

		$("#old_granted_certificate").show();
		$("#total_charge_box").hide();//added on 28-11-2017 by Amol to hide if old application
	});
	
	$('#radioPrimary2').click(function(){

		$("#old_granted_certificate").hide();
		$("#last_renewal_details").hide();
		$("#total_charge_box").show();//added on 28-11-2017 by Amol to show if new application
	});


	// For sponsored press option , Done by pravin bhakare 18-10-2021
	$('#is_sponsored_pressYes').click(function(){	
		$(".sponsored_cas").show();
	});
	
	$('#is_sponsored_pressNo').click(function(){
		$(".sponsored_cas").hide();
	});




	//this function is used to get old grant date and calculate valid renewal dates. by Pravin on 02-10-2017
	$(document).ready(function () {
		
		$("#old_granted_certificate").hide(); // added on 10th DEC 2020
		$("#last_renewal_details").hide();
		
		$('#grant_date').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			endDate: new Date(),
			clearBtn: true
		}).on('changeDate', function(e) {
			
			var certification_type = $('#certification_type').val();
			var current_date = new Date();
			var grant_date = $("#grant_date").val().split("/");
			var get_grant_month = grant_date[1];
			var get_grant_year = grant_date[2];
			
			if(certification_type == 1){
				
				if(get_grant_month <= 3){
					var valid_upto_year = parseInt(get_grant_year)+ parseInt(4);
				}else{
					var valid_upto_year = parseInt(get_grant_year)+ parseInt(5);
				}

				var valid_upto_date = '31/09/'+ valid_upto_year;//temp date extended to 30-09 for covid 19 on 07-09-2021 by Amol
				var static_value = '01/04/';
				
			}else if(certification_type == 2){
				
				var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);
				var valid_upto_date = '31/12/'+ valid_upto_year;
				var static_value = '01/01/';
				
			}else if(certification_type == 3){
				
				if(get_grant_month <= 6){
					var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);
				}else{
					var valid_upto_year =  parseInt(get_grant_year)+ parseInt(2);
				}

				var valid_upto_date = '30/06/'+ valid_upto_year;
				var static_value = '01/07/';
			}
			
			var convert_valid_upto_date = valid_upto_date.split("/");
			var final_valid_upto_date = new Date(convert_valid_upto_date[2], convert_valid_upto_date[1] - 1, convert_valid_upto_date[0]);
			
			if( current_date > final_valid_upto_date ){
				
				// Show pop-up message box before enter previous renewals details history.
				// Done by Pravin Bhakare on 31-01-2019
				// Suggest By Navin Sir
				// Why : show message box because user enter wrong renewals date history	
				var modal = document.getElementById('declarationModal');
				modal.style.display = "block";
				
				//added below logic on 30-03-2019 by Amol, 
				//to show predicted last renewal date to applicant in message
				var predicted_last_renewal_year = null;
					
				var current_date_year = new Date().getFullYear();//current year
				get_grant_year = convert_valid_upto_date[2];
				
				while (get_grant_year <= current_date_year){

					var one_step_old_grant_year = valid_upto_year; //get one step last renewal grant date before increment
					
					if(certification_type == 1){
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(5);
					}else if(certification_type == 2){
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(1);	
					}else if(certification_type == 3){
						var valid_upto_year =  parseInt(get_grant_year)+ parseInt(2);
					}
					
					if(valid_upto_year > current_date_year){

						predicted_last_renewal_year = get_grant_year;

						var concatinate_date = static_value + predicted_last_renewal_year;
						var convert_date = concatinate_date.split("/");//concatinate and split to convert
						var converted_predicted_date = new Date(convert_date[2], convert_date[1] - 1, convert_date[0]);
						
						var show_last_predicted_date = null;
						
						if( converted_predicted_date > current_date ){ //to show last renewal date before current date
							
							if(certification_type == 1){
								var one_step_old_grant_year =  parseInt(one_step_old_grant_year)- parseInt(5);
			
							}else if(certification_type == 2){
								var one_step_old_grant_year =  parseInt(one_step_old_grant_year)- parseInt(1);	
								
							}else if(certification_type == 3){
								var one_step_old_grant_year =  parseInt(one_step_old_grant_year)- parseInt(2);
							}

							show_last_predicted_date = static_value + one_step_old_grant_year;//one step decremented date
							
						}else{
							show_last_predicted_date = static_value + predicted_last_renewal_year; //Regular incremented date
						}
						
						//set conditional variable with a value which not enter in loop again
						get_grant_year = parseInt(current_date_year)+ parseInt(1);;//to break the loop
					}
					
					//increment year by 1
					get_grant_year =  valid_upto_year;
				}
				
				$("#entered_grant_date").text($("#grant_date").val());//added on 30-03-2019 by Amol
				$("#predicted_last_renewal_date").text(show_last_predicted_date);//added on 30-03-2019 by Amol
				
				$("#last_renewal_details").show();
				$("#static_renewal_dates1").val(static_value);
				
			}else{

				$("#last_renewal_details").hide();
			}
		});	
		
	});
			
			

	//Below jquery logic is created to get multiple renewal dates from applicant, if old applicant
	//on 02-10-2017 by pravin
	var max_fields_limit = 10; //set limit for maximum input fields
	var x = 1; //initialize counter for text box
	$('.add_more_button').click(function(e){
		
		//click event on add more fields button having class add_more_button
		e.preventDefault();
		var certification_type = $('#certification_type').val();

		if(certification_type == 1){
			var static_value = '01/04/';
		}else if(certification_type == 2){
			var static_value = '01/01/';
		}else if(certification_type == 3){
			var static_value = '01/07/';
		}
		
		if(x < max_fields_limit){ //check conditions
			x++; //counter increment
			
			$('.input_fields_container').append('<div class="d-flex"><label for="field3" class="col-sm-3 col-form-label">Year Of Renewal <span class="cRed">*</span></label><span class="col-sm-9 marginL20"><input type="text" class="form-control year-of-renewal marginRM19" id="static_renewal_dates'+x+'" value="'+static_value+'" readonly="true"><input type="text" class="renewal_dates_input form-control marginL20" name="renewal_dates[]" readonly="true" id="last_renewal_dates'+x+'"/><a href="#" class="remove_field btn btn-sm bg-danger ml-2"><i class="fa fa-trash"></i> Remove</a><span id="error_renewal_dates'+x+'" class="d-block text-red"></span></span></div>'); //add input field

			$('.renewal_dates_input').datepicker({
				format: " yyyy",
				viewMode: "years", 
				minViewMode: "years",
				autoclose: true	
			}).on('changeDate', function(e) {
				
				var last_renewal_dates = $("#last_renewal_dates"+x).val();
				var result2 = valid_last_renewal_date(last_renewal_dates);   // This function define in primary_forms_validation js file
				var application_expired_status = result2.application_expired_status;
				if(application_expired_status == 'yes')
				{
					$("#error_renewal_dates"+x).show().text("Please enter next renewal date with 'addmore' button. If not renewed, So the application was expired. Please register with new application");
					$("#error_renewal_dates"+x).css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
					$(".add_more_button").click(function(){$("#error_renewal_dates"+x).hide().text;});
				}
			});
		}
	});  
	
	
	$('.input_fields_container').on("click",".remove_field", function(e){ //user click on remove text links
		e.preventDefault(); $(this).closest('div').remove(); x--;
	})


	$(document).ready(function () {

		$('.renewal_dates_input').datepicker({
			format: " yyyy",
			viewMode: "years", 
			minViewMode: "years",
			autoclose: true		
		}).on('changeDate', function(e) {
			
			var last_renewal_dates = $("#last_renewal_dates1").val();
			var result2 = valid_last_renewal_date(last_renewal_dates);   // This function define in primary_forms_validation js file
			var application_expired_status = result2.application_expired_status;
			if(application_expired_status == 'yes')
			{
				$("#error_renewal_dates1").show().text("Please enter next renewal date with 'addmore' button. If not renewed, So the application was expired. Please register with new application");
				$("#error_renewal_dates1").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
				$(".add_more_button").click(function(){$("#error_renewal_dates1").hide().text;});
			}
		});
	});
		
		
	$('#is_already_granted-yes').click(function(){
		$("#old_granted_certificate").show();
	});


	// End To check Added firm is new or old granted firm

	//$("#state").val('');//line added on 14-07-2018	


	$('#export_unit').change(function(){
		
		if ($('#radioSuccess1').is(":checked")) {

			$.confirm({
				title: 'Note:',
				content: 'You Have Selected the Export Unit. If You Want to  Proceed click on the <b>Proceed</b> or click on the <b>Cancel</b>.',
				columnClass: 'medium',
				type: 'dark',
				theme: 'modern',
				buttons: {
					proceed: function () {
						
					},
					cancel: function () {
						$("#radioSuccess2").prop("checked",true);
					}
				}
			});
		}
	});
