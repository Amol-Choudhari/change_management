
	$(this).click(function(e){
	
		var state_map_id = $(e.target).attr('id');
		var state_id = '';
		var state_name = '';
		
		if(typeof state_map_id === "undefined"){
			//do nothing
		}else{
			var result_arr = get_state_table_id(state_map_id);
			
			state_id = result_arr[0];
			state_name = result_arr[1];
			
			$.ajax({
					type: "POST",
					async:true,
					url:"getStateWiseDetails",
					data:{state_id:state_id},
					beforeSend: function (xhr) { // Add this line
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (result) {
						
						result = JSON.parse(result);
						$("#state_name").text(state_name);
						$("#st_wise_appl").text(result['appl']);
						$("#st_wise_grant").text(result['grant']);
						$("#st_wise_rev").text(result['revenue']);
					}
			});
		}

	});
	
	function get_state_table_id(state_map_id){
		var state_id = '';
		var state_name = '';
		
		if(state_map_id == 'jqvmap1_ANDAMAN & NICOBAR ISLANDS'){
			state_id = '1';
			state_name = 'ANDAMAN & NICOBAR';
		
		}else if(state_map_id == 'jqvmap1_ANDHRA PRADESH'){
			state_id = '2';
			state_name = 'ANDHRA PRADESH';
		
		}else if(state_map_id == 'jqvmap1_ARUNACHAL PRADESH'){
			state_id = '3';
			state_name = 'ARUNACHAL PRADESH';
		
		}else if(state_map_id == 'jqvmap1_ASSAM'){
			state_id = '4';
			state_name = 'ASSAM';
		
		}else if(state_map_id == 'jqvmap1_BIHAR'){
			state_id = '5';
			state_name = 'BIHAR';
		
		}else if(state_map_id == 'jqvmap1_CHANDIGARH'){
			state_id = '6';
			state_name = 'CHANDIGARH';
		
		}else if(state_map_id == 'jqvmap1_CHATTISGARH'){
			state_id = '7';
			state_name = 'CHATTISGARH';
		
		}else if(state_map_id == 'jqvmap1_DADRA & NAGAR HAVELI'){
			state_id = '8';
			state_name = 'DADRA & NAGAR HAVELI';
		
		}else if(state_map_id == 'jqvmap1_DAMAN & DIU'){
			state_id = '9';
			state_name = 'DAMAN & DIU';
		
		}else if(state_map_id == 'jqvmap1_DELHI'){
			state_id = '10';
			state_name = 'DELHI';
		
		}else if(state_map_id == 'jqvmap1_GOA'){
			state_id = '11';
			state_name = 'GOA';
		
		}else if(state_map_id == 'jqvmap1_GUJARAT'){
			state_id = '12';
			state_name = 'GUJARAT';
		
		}else if(state_map_id == 'jqvmap1_HARYANA'){
			state_id = '13';
			state_name = 'HARYANA';
		
		}else if(state_map_id == 'jqvmap1_HIMACHAL PRADESH'){
			state_id = '14';
			state_name = 'HIMACHAL PRADESH';
		
		}else if(state_map_id == 'jqvmap1_JAMMU & KASHMIR'){
			state_id = '15';
			state_name = 'JAMMU & KASHMIR';
		
		}else if(state_map_id == 'jqvmap1_JHARKHAND'){
			state_id = '16';
			state_name = 'JHARKHAND';
		
		}else if(state_map_id == 'jqvmap1_KARNATAKA'){
			state_id = '17';
			state_name = 'KARNATAKA';
		
		}else if(state_map_id == 'jqvmap1_KERALA'){
			state_id = '18';
			state_name = 'KERALA';
		
		}else if(state_map_id == 'jqvmap1_LAKSHADWEEP'){
			state_id = '19';
			state_name = 'LAKSHADWEEP';
		
		}else if(state_map_id == 'jqvmap1_MADHYA PRADESH'){
			state_id = '20';
			state_name = 'MADHYA PRADESH';
		
		}else if(state_map_id == 'jqvmap1_MAHARASHTRA'){
			state_id = '21';
			state_name = 'MAHARASHTRA';
		
		}else if(state_map_id == 'jqvmap1_MANIPUR'){
			state_id = '22';
			state_name = 'MANIPUR';
		
		}else if(state_map_id == 'jqvmap1_MIZORAM'){
			state_id = '23';
			state_name = 'MIZORAM';
		
		}else if(state_map_id == 'jqvmap1_ODISHA'){
			state_id = '24';
			state_name = 'ODISHA';
		
		}else if(state_map_id == 'jqvmap1_PONDICHERRY'){
			state_id = '25';
			state_name = 'PONDICHERRY';
		
		}else if(state_map_id == 'jqvmap1_PUNJAB'){
			state_id = '26';
			state_name = 'PUNJAB';
		
		}else if(state_map_id == 'jqvmap1_RAJASTHAN'){
			state_id = '27';
			state_name = 'RAJASTHAN';
		
		}else if(state_map_id == 'jqvmap1_SIKKIM'){
			state_id = '28';
			state_name = 'SIKKIM';
		
		}else if(state_map_id == 'jqvmap1_TAMIL NADU'){
			state_id = '29';
			state_name = 'TAMIL NADU';
		
		}else if(state_map_id == 'jqvmap1_TELANGANA'){
			state_id = '30';
			state_name = 'TELANGANA';
		
		}else if(state_map_id == 'jqvmap1_TRIPURA'){
			state_id = '31';
			state_name = 'TRIPURA';
		
		}else if(state_map_id == 'jqvmap1_UTTAR PRADESH'){
			state_id = '32';
			state_name = 'UTTAR PRADESH';
		
		}else if(state_map_id == 'jqvmap1_UTTARAKHAND'){
			state_id = '33';
			state_name = 'UTTARAKHAND';
		
		}else if(state_map_id == 'jqvmap1_WEST BENGAL'){
			state_id = '34';
			state_name = 'WEST BENGAL';
		
		}else if(state_map_id == 'jqvmap1_MEGHALAYA'){
			state_id = '35';
			state_name = 'MEGHALAYA';
		
		}else if(state_map_id == 'jqvmap1_NAGALAND'){
			state_id = '36';
			state_name = 'NAGALAND';
		
		}else{state_id = ''; state_name = '';}
		
		var result = [state_id,state_name];
		return result;
		
	}
