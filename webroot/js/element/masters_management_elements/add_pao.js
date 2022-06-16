$('#add_pao_btn').click(function (e) { 
	if (set_pao_validation() == false) {
		e.preventDefault();
	}
	
});
		//create the dynamic path for ajax url (Done by pravin 03/11/2017)
		var host = location.hostname;
		var paths = window.location.pathname;
		var split_paths = paths.split("/");
		var path = "/"+split_paths[1]+"/"+split_paths[2];
		
			
			$('#district_list').multiselect({
				includeSelectAllOption: true,
				placeholder :'Select District',
				buttonWidth: '100%',
				maxHeight: 400,
			});
			
			
			$('#state_list').multiselect({
				includeSelectAllOption: true,
				placeholder :'Select State',
				buttonWidth: '100%',
				maxHeight: 400,
			});
			
				
						
			$('#state_list').change(function(e){					
			
				var state_id = $('#state_list').val();
				var form_data = $("#set_pao").serializeArray();
				form_data.push(	{name: "state_id",value: state_id});
				
				/* add new custom "new ms-options-update" class to avoid conflict of "ms-options" classes of two 
					multiselect dropdown options. */
				$('#update_district_div div.ms-options').addClass('ms-options-update');
				
				// Clear the place holder Text
				var selOpts = [];
				var placeholder = $('#district_list').next('.ms-options-wrap').find('> button:first-child');
				placeholder.text(selOpts.join( '' ));
				
				$.ajax({
					type: "POST",
					url: path+"/pao_district_dropdown",
					data: form_data,
					async: true,
					beforeSend: function (xhr) {
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(data){
						$(".ms-options-update").html(data);	
					},						
				}); 

				/* For extcuting two ajax function on one event action, click the second 
					function after first ajax function success */
				$('#district_option').click();
				
			});			
			
			
			$('#district_option').click(function(){
			
				
				var state_id = $('#state_list').val();
				var form_data = $("#set_pao").serializeArray();
				form_data.push(	{name: "state_id",value: state_id});
				
				$.ajax({
					type: "POST",
					url: path+"/pao_district_option",
					data: form_data,
					beforeSend: function (xhr) {
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
					success: function(response){
						
					$("#district_list").html(response);
					},						
				}); 				
								
			});
			
			
			function set_pao_validation(){
				
				var pao_email_id = $('#pao_email_id').val();
				var district_list = $('#district_list').val();
				var pao_alias_name = $('#pao_alias_name').val();
				
				
				value_return = 'true';
				
				if(pao_email_id == null){
					
					$("#error_pao_email_id").show().text('Select PAO/DDO email ID');
					$("#error_pao_email_id").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
					$("#pao_email_id").click(function(){$("#error_pao_email_id").hide().text;});
					
					value_return = 'false';
					
				}
				
				if(district_list == null){
					
					$("#error_district_list").show().text('Select district');
					$("#error_district_list").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
					$("#district_list").click(function(){$("#error_district_list").hide().text;});
					
					value_return = 'false';
					
				}
				
				if(pao_alias_name == ''){
					
					$("#error_pao_alias_name").show().text('Enter pao alias name');
					$("#error_pao_alias_name").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
					$("#pao_alias_name").click(function(){$("#error_pao_alias_name").hide().text;});
					
					value_return = 'false';
					
				}
				
				
				if(value_return == 'false')
				{
					
					return false;
				}
				else{
						exit();   
					}
				
			}



