var countarray = null; //declare as global here, and used below functions
var maintabid = null; //declare as global here, and used below functions
$(document).ready(function(){
	
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	$(".loader").hide();
	$(".loadermsg").hide();

	var current_level_script_id = $("#current_level_script_id").val();
	var show_list_for_script_id = $("#show_list_for_script_id").val();
	var fetchStatus = '';
	
	//created a custum function for common ajax and called
	//on 03-06-2022 by Amol
	getStatusWiseCount(fetchStatus);
	
	//this a custum function for common ajax
	//on 03-06-2022 by Amol
	function getStatusWiseCount(fetchStatus){
	
        $.ajax({
            type: "POST",
            async:false,
            url:"../dashboard/common_count_fetch",
			data:{fetchStatus:fetchStatus},							 
            beforeSend: function (xhr) {
				$(".loader").show();$(".loadermsg").show();
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            }, 
            success: function (data) {
                
                countarray = data.match(/~([^']+)~/)[1];
                countarray = JSON.parse(countarray);
               
            /*   $("#pending_count_no").text(countarray.pending);
               $("#reports_filed_count_no").text(countarray.reports_filed);
               $("#ref_back_count_no").text(countarray.ref_back);
               $("#replied_count_no").text(countarray.replied);
               $("#approved_count_no").text(countarray.approved);
               $("#rejected_count_no").text(countarray.rejected);
				$("#allocation_count_no").text(countarray.alloc_main);*/

				$("#pending_count_no").html('<span class="glyphicon glyphicon-search" ></span>');
               $("#reports_filed_count_no").html('<span class="glyphicon glyphicon-search" ></span>');
               $("#ref_back_count_no").html('<span class="glyphicon glyphicon-search" ></span>');
               $("#replied_count_no").html('<span class="glyphicon glyphicon-search" ></span>');
               $("#approved_count_no").html('<span class="glyphicon glyphicon-search" ></span>');
               $("#rejected_count_no").html('<span class="glyphicon glyphicon-search" ></span>');
				$("#allocation_count_no").html('<span class="glyphicon glyphicon-search" ></span>');
				
				if(fetchStatus=='pending'){$("#pending_count_no").text(countarray.pending);}
				if(fetchStatus=='reports_filed'){$("#reports_filed_count_no").text(countarray.reports_filed);}
				if(fetchStatus=='ref_back'){$("#ref_back_count_no").text(countarray.ref_back);}
				if(fetchStatus=='replied'){$("#replied_count_no").text(countarray.replied);}
				if(fetchStatus=='approved'){$("#approved_count_no").text(countarray.approved);}
				if(fetchStatus=='rejected'){$("#rejected_count_no").text(countarray.rejected);}
				if(fetchStatus=='allocation'){$("#allocation_count_no").text(countarray.alloc_main);}

				$(".loader").hide();$(".loadermsg").hide();
            }
		});

	}
//ajax to fetch pending list
$("#pending_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();	
	maintabid = 'pending';
	
	getStatusWiseCount('pending');//applied on 03-06-2022 by Amol														  
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/pending_applications",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
			$(".loader").hide();$(".loadermsg").hide();
			$("#common_applications_list").show();
			$("#list_heading_text").text("Given Below is Pending List:");
			
			$("#common_applications_list").html(data);
			
			//to hide scrutiny tab on pending, bcoz MO/SMO allocated application are in scrutiny pending, not pending for RO/SO
			$("#scrutiny_tab").hide();
			
			//to hide with reg office tab on pending, bcoz So forward application to RO, it will pending on RO side
			$("#with_reg_offs_tab").hide();
			
			//to hide with HO office tab on pending, bcoz RO forward application to HO, it will pending on HO side
			$("#with_ho_offs_tab").hide();

			MainTabsScriptscall(current_level_script_id,show_list_for_script_id);
		}
	});
	
});

//ajax to fetch reports filed lists
$("#reports_filed_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	maintabid = 'reports_filed';
	
	getStatusWiseCount('reports_filed');//applied on 03-06-2022 by Amol																
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/reports_filed",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is Filed Reports List:");
				
				$("#common_applications_list").html(data);

				MainTabsScriptscall(current_level_script_id,show_list_for_script_id);
		}
	});
});

//ajax to fetch ref_back lists
$("#ref_back_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	maintabid = 'ref_back';
	
	getStatusWiseCount('ref_back');//applied on 03-06-2022 by Amol														   
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/ref_back_applications",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is Referred back List:");
				
				$("#common_applications_list").html(data);

				MainTabsScriptscall(current_level_script_id,show_list_for_script_id);
		}
	});
});

//ajax to fetch replied lists
$("#replied_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	maintabid = 'replied';
	
	getStatusWiseCount('replied');//applied on 03-06-2022 by Amol														  
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/replied_applications",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is Replied List:");
				
				$("#common_applications_list").html(data);

				MainTabsScriptscall(current_level_script_id,show_list_for_script_id);
		}
	});
});

//ajax to fetch approved lists
$("#approved_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	maintabid = 'approved';
	
	getStatusWiseCount('approved');//applied on 03-06-2022 by Amol														   
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/approved_applications",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is Approved List:");
				
				$("#common_applications_list").html(data);

				MainTabsScriptscall(current_level_script_id,show_list_for_script_id);
		}
	});
});

//ajax to fetch rejected lists
$("#rejected_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	maintabid = 'rejected';
	
	getStatusWiseCount('rejected');//applied on 03-06-2022 by Amol														   
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/rejected_applications",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is Rejected List:");
				
				$("#common_applications_list").html(data);

				MainTabsScriptscall(current_level_script_id,show_list_for_script_id);
		}
	});
});


//ajax to fetch allocations lists
$("#allocations_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	
	getStatusWiseCount('allocation');//applied on 03-06-2022 by Amol															 
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/allocations_main_tab",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is List of Applications for Allocation/Reallocation:");
				
				$("#common_applications_list").html(data);

				allocation_common_tabs_js_call();
				
		}
	});
});

//ajax to fetch allocations lists
/*$("#jat_status_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/jtama_jat_status_main_tab",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is List of Laboratory (Export) applications for JAT");
				
				$("#common_applications_list").html(data);
		}
	});
});*/

/*
//ajax to fetch all list in one
$("#all_count_box").click(function(){
	$("#common_applications_list").hide();
	$("#all_applications_list").hide();
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/all_applications",
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#common_applications_list").show();
				$("#list_heading_text").text("Given Below is All Application List:");
				
				$("#common_applications_list").html(data);
		}
	});
});
*/



//to slove the CSP policy issue, as script not worked if loaded on the fly
//so created the functions for all the scripts called in dashboard counts and listing view
//now these functions will be call at the time of view loaded, to wake up the script call
//created on 21-10-2021 by Amol

//for common listing view script
	function appl_common_list_js_call(){

		$('#common_app_list_table').DataTable({"ordering": false});

		$("#allocation_popup_box").hide();

		$('.io_scheduled_date').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			startDate: new Date(),
			clearBtn: true

		});

		var current_level_script_id = $("#current_level_script_id").val();
			

			//added pao in this condition on 07-09-2022 for rejection option script
		if(current_level_script_id=='level_2' || current_level_script_id=='level_3' || current_level_script_id=='level_4' || current_level_script_id=='pao'){
			
			var i=1;
			var limit = $("#i-value").val();

			for(i=1;i < limit;i++){	

				(function(p) {
					
					//for scrutiny allocation
				//	$('#allocate-scrutiny'+p).click(function(){
					$('#common_app_list_table').on('click', '#allocate-scrutiny'+p, function(){
						
						var appl_type = $("#appl_type"+p).val();
						var customer_id = $("#customer_id"+p).val();
						var comm_with = $("#comm_with"+p).text();
						
						$.ajax({
								type: "POST",
								async:true,
								url:"../dashboard/open_scrutiny_allocation_popup",
								data:{customer_id:customer_id,appl_type:appl_type,comm_with:comm_with},
								beforeSend: function (xhr) { // Add this line
										$(".loader").show();$(".loadermsg").show();
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
								}, 
								success: function (data) {
										$(".loader").hide();$(".loadermsg").hide();
										$("#allocation_popup_box").show();
										$("#allocation_popup_box").html(data);
										$("#scrutiny_alloction_Modal").show();
										
										scrutiny_alloc_js_call();
								}
						});
					});
					
					
					
					//for Inspection allocation
				//	$('#allocate-inspection'+p).click(function(){
					$('#common_app_list_table').on('click', '#allocate-inspection'+p, function(){
						
						var appl_type = $("#appl_type"+p).val();
						var customer_id = $("#customer_id"+p).val();
						var comm_with = $("#comm_with"+p).text();
						
						$.ajax({
								type: "POST",
								async:true,
								url:"../dashboard/open_inspection_allocation_popup",
								data:{customer_id:customer_id,appl_type:appl_type,comm_with:comm_with},
								beforeSend: function (xhr) { // Add this line
										$(".loader").show();$(".loadermsg").show();
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
								}, 
								success: function (data) {
										$(".loader").hide();$(".loadermsg").hide();
										$("#allocation_popup_box").show();
										$("#allocation_popup_box").html(data);
										$("#inspection_alloction_Modal").show();
										
										inspection_alloc_js_call();
								}
						});
					});
					
					
					
					//for IO change inspection date
					
					//added on 12-05-2021 by Amol
					if($("#io_sched_date_comment"+p).val()==''){
						$("#io_sched_date_comment"+p).hide();
					}
					$("#io_scheduled_date"+p).click(function(){
						$("#io_sched_date_comment"+p).show();
					});
					
					
				//	$("#change_date"+p).click(function(){
					$('#common_app_list_table').on('click', '#change_date'+p, function(){
						
						var appl_type = $("#appl_type"+p).val();
						var customer_id = $("#customer_id"+p).val();
						var io_scheduled_date = $("#io_scheduled_date"+p).val();
						var io_sched_date_comment = $("#io_sched_date_comment"+p).val();//added on 12-05-2021 by Amol
						
						if(io_scheduled_date==''){
							
							alert('Date can not be blank');
							return false;
						}
						if(io_sched_date_comment==''){
							alert('Please write remark before changing date');
							return false;
						}
						//for change date
						$.ajax({
								type: "POST",
								async:true,
								url:"../dashboard/change_inspection_date",
								data:{customer_id:customer_id,appl_type:appl_type,io_scheduled_date:io_scheduled_date,io_sched_date_comment:io_sched_date_comment},//updated on 12-05-2021 by Amol
								beforeSend: function (xhr) { // Add this line
										$(".loader").show();$(".loadermsg").show();
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
								}, 
								success: function (response) {
										$(".loader").hide();$(".loadermsg").hide();
										
										alert('The Site Inspection Date for Application id '+customer_id+' is Re-scheduled Successfully.');
										
								}
						});	
					});
					
					
					
					//for Rejection of Application
				//	$('#reject_appln'+p).click(function(){
					$('#common_app_list_table').on('click', '#reject_appln'+p, function(){
						
						var appl_type = $("#appl_type"+p).val();
						var customer_id = $("#customer_id"+p).val();
						
						$.ajax({
								type: "POST",
								async:true,
								url:"../dashboard/open_reject_appl_popup",
								data:{customer_id:customer_id,appl_type:appl_type},
								beforeSend: function (xhr) { // Add this line
										$(".loader").show();$(".loadermsg").show();
										xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
								}, 
								success: function (data) {
										$(".loader").hide();$(".loadermsg").hide();
										$("#allocation_popup_box").show();
										$("#allocation_popup_box").html(data);
										$("#common_reject_Modal").show();
										
										common_rej_appl_popup_js_call();
								}
						});
					});
					
				})(i);

			}

			
		}
	}

//for RO/SO common sub tabs scripts
	function ro_so_common_tabs_js_call(){
				
		$(".loader").hide();
		$("#ro_tabs_div li a").click(function() {
		$("#ro_tabs_div li").removeClass('active');
		$(this).parent().addClass('active');
		});

		//to get and append counts in Nodal office sub tabs
		//the countarray is alreay declared in previous script as global
		$("#with_applicant_tab_count").text(countarray['with_applicant'][maintabid]);
		$("#scrutiny_tab_count").text(countarray['scrutiny'][maintabid]);
		$("#reports_tab_count").text(countarray['reports'][maintabid]);


		var ro_so_session_level = $("#ro_so_session_level").val();
		var ro_so_level_3_for = $("#ro_so_level_3_for").val();

		if(ro_so_session_level=='level_3' && ro_so_level_3_for=='RO'){
			$("#with_sub_offs_tab_count").text(countarray['with_sub_office'][maintabid]);
			$("#with_ho_offs_tab_count").text(countarray['with_ho_office'][maintabid]);
		}else if(ro_so_session_level=='level_3' && ro_so_level_3_for=='SO'){
			$("#with_reg_offs_count").text(countarray['with_reg_office'][maintabid]);
		}



		//ajax to fetch listing for RO/SO with MO/SMO
		$("#with_applicant_tab").click(function(){
			$("#level_3_common_applications_list").hide();
			$("#level_3_all_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/with_applicant_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_3_common_applications_list").show();
							$("#level_3_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		//ajax to fetch listing for RO/SO with MO/SMO
		$("#scrutiny_tab").click(function(){
			$("#level_3_common_applications_list").hide();
			$("#level_3_all_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/scrutiny_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_3_common_applications_list").show();
							$("#level_3_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		//ajax to fetch listing for RO/SO with IO reports
		$("#reports_tab").click(function(){
			$("#level_3_common_applications_list").hide();
			$("#level_3_all_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/reports_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_3_common_applications_list").show();
							$("#level_3_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		//ajax to fetch listing for RO with SO officer
		$("#with_sub_offs_tab").click(function(){
			$("#level_3_common_applications_list").hide();
			$("#level_3_all_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/with_sub_offs_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_3_common_applications_list").show();
							$("#level_3_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		//ajax to fetch listing for SO to Ro officer
		$("#with_reg_offs_tab").click(function(){
			$("#level_3_common_applications_list").hide();
			$("#level_3_all_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/with_reg_offs_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_3_common_applications_list").show();
							$("#level_3_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		//ajax to fetch listing for RO/SO with HO office
		$("#with_ho_offs_tab").click(function(){
			$("#level_3_common_applications_list").hide();
			$("#level_3_all_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/with_ho_offs_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_3_common_applications_list").show();
							$("#level_3_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

	}


//for RO/SO reject appl popup scripts
	function common_rej_appl_popup_js_call(){

		$(".close").click(function(){		
			$("#common_reject_Modal").hide();
		});
	
		//for scrutiny allocation
		$('#reject_appl_btn').click(function(){
			
			var appl_type = $("#rej_appl_type").val();
			var customer_id = $("#rej_customer_id").val();
			var remark = $("#rej_remark").val();
			
			if(remark==''){
				
				alert('Please enter remark/reason for this rejection');
				return false;
			}
			
			if(confirm('Are you sure to reject this application')){
			
				$.ajax({
						type: "POST",
						async:true,
						url:"../dashboard/reject_application",
						data:{customer_id:customer_id,appl_type:appl_type,remark:remark},
						beforeSend: function (xhr) { // Add this line
								$(".loader").show();$(".loadermsg").show();
								xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
						}, 
						success: function (data) {
		
								$(".loader").hide();$(".loadermsg").hide();
								$("#common_reject_Modal").hide();
								alert("The Application "+customer_id+" is successfully Rejected.");
								
								var from_sub_tab = data.match(/~([^']+)~/)[1];
								
								//to reload list after application rejected from popup
								if(from_sub_tab=='with_applicant'){								
									$('#with_applicant_tab').click();
									
								}else if(from_sub_tab=='scrutiny'){									
									$('#scrutiny_tab').click();
									
								}else if(from_sub_tab=='reports'){									
									$('#reports_tab').click();
									
								}else if(from_sub_tab=='with_sub_office'){									
									$('#with_sub_offs_tab').click();
									
								}else if(from_sub_tab=='with_reg_office'){									
									$('#with_reg_offs_tab').click();
									
								}else if(from_sub_tab=='with_ho_office'){									
									$('#with_ho_offs_tab').click();
									
								}
						}
				});
			
			}else{
				
				return false;
			}
		});	
	}

//for scrutiny common sub tabs view scripts
	function scrutiny_common_tabs_js_call(){
		
		$(".loader").hide();
		$("#ro_tabs_div li a").click(function() {
		$("#ro_tabs_div li").removeClass('active');
		$(this).parent().addClass('active');
		});

		//to get and append counts in scutiny office sub tabs
		//the countarray is alreay declared in previous script as global
		$("#with_nodal_office_count").text(countarray['scrutiny_with_nodal_office'][maintabid]);
		$("#with_reg_office_count").text(countarray['scrutiny_with_reg_office'][maintabid]);
		$("#with_ho_office_count").text(countarray['scrutiny_with_ho_office'][maintabid]);


		//ajax to fetch listing for scrutiny with nodal officer
		$("#with_nodal_office_tab").click(function(){
			$("#level_1_common_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/scrutiny_with_nodal_office_Tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_1_common_applications_list").show();
							$("#level_1_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		//ajax to fetch listing for scrutiny with Reg. Office
		$("#with_reg_office_tab").click(function(){
			$("#level_1_common_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/scrutiny_with_reg_office_Tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_1_common_applications_list").show();
							$("#level_1_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		//ajax to fetch listing for scrutiny with HO QC
		$("#with_ho_office_tab").click(function(){
			$("#level_1_common_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/scrutiny_with_ho_office_Tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#level_1_common_applications_list").show();
							$("#level_1_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

	}

//for HO common sub tabs view scripts
	function ho_common_tabs_js_call(){

		$(".loader").hide();
		$("#ho_tabs_div li a").click(function() {
		$("#ho_tabs_div li").removeClass('active');
		$(this).parent().addClass('active');
		});

		//to get and append counts in HO sub tabs
		//the countarray is alreay declared in previous script as global
		$("#for_ho_scrutiny_count").text(countarray['for_ho_scrutiny'][maintabid]);
		$("#for_dyama_count").text(countarray['for_dy_ama'][maintabid]);
		$("#for_jtama_count").text(countarray['for_jt_ama'][maintabid]);
		$("#for_ama_count").text(countarray['for_ama'][maintabid]);


		//ajax to fetch listing for scrutiny
		$("#for_ho_scrutiny_tab").click(function(){
			$("#ho_level_common_applications_list").hide();
			var list_for = 'ho_scrutiny';
			
			fetch_ho_list_ajax(list_for);
		});

		//ajax to fetch listing for Dy AMA
		$("#for_dyama_tab").click(function(){
			$("#ho_level_common_applications_list").hide();
			var list_for = 'dy_ama';
			
			fetch_ho_list_ajax(list_for);
		});

		//ajax to fetch listing for Jt AMA
		$("#for_jtama_tab").click(function(){
			$("#ho_level_common_applications_list").hide();
			var list_for = 'jt_ama';
			
			fetch_ho_list_ajax(list_for);
		});

		//ajax to fetch listing for AMA
		$("#for_ama_tab").click(function(){
			$("#ho_level_common_applications_list").hide();
			var list_for = 'ama';
			
			fetch_ho_list_ajax(list_for);
		});

	}
//this function used in ho common sub tabs scripts call
	function fetch_ho_list_ajax(list_for){
			
		$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/fetch_ho_level_lists",
			data:{list_for:list_for},
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#ho_level_common_applications_list").show();
					$("#ho_level_common_applications_list").html(data);

					appl_common_list_js_call();
			}
		});
		
	}

//for allocation common sub tabs view scripts
	function allocation_common_tabs_js_call(){

		$(".loader").hide();
		$("#ro_tabs_div li a").click(function() {
		$("#ro_tabs_div li").removeClass('active');
		$(this).parent().addClass('active');
		});

		//to get and append counts in allocation sub tabs
		//the countarray is alreay declared in previous script as global
		$("#for_scrutiny_allocation_count").text(countarray['scrutiny_allocation_tab']);
		$("#for_scrutiny_of_so_appl_count").text(countarray['scrutiny_allocation_by_level4ro_tab']);
		$("#for_inspection_allocation_count").text(countarray['inspection_allocation_tab']);

		//ajax to fetch listing for Allocation to scrutiny
		$("#for_scrutiny_allocation_tab").click(function(){
			$("#allocation_common_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/allocation_for_scrutiny_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#allocation_common_applications_list").show();
							$("#allocation_common_applications_list").html(data);

							appl_common_list_js_call();

					}
			});
		});

		//ajax to fetch listing for allocation of Siteinspection
		$("#for_inspection_allocation_tab").click(function(){
			$("#allocation_common_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/allocation_for_inspection_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#allocation_common_applications_list").show();
							$("#allocation_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});



		//ajax to fetch listing for scrutiny allocation by Ro for SO appli.
		$("#for_scrutiny_of_so_appl_tab").click(function(){
			$("#allocation_common_applications_list").hide();
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/allocation_for_scrutiny_by_level4_ro_tab",
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {
							$(".loader").hide();$(".loadermsg").hide();
							$("#allocation_common_applications_list").show();
							$("#allocation_common_applications_list").html(data);

							appl_common_list_js_call();
					}
			});
		});

		subTabsClicksEvents();

	}

//for scrutiny allocation popup view scripts
	function scrutiny_alloc_js_call(){

		$(".close").click(function(){		
			$("#scrutiny_alloction_Modal").hide();
		});
	
		//for scrutiny allocation
		$('#scrutiny_allocate_btn').click(function(){
			
			var appl_type = $("#alloc_appl_type").val();
			var customer_id = $("#alloc_customer_id").val();
			var mo_user_id = $("#mo_users_list").val();
			
			if (appl_type != '' && customer_id !='' && mo_user_id !=null) {


				//this check added on 03-02-2023
				//to check if the application is already in between comm.
				//if yes, then alert user on reallocation of application to get confirmation.
				$.ajax({
					
					type: "POST",
					async:true,
					url:"../dashboard/check_appl_inprocess_before_reallocation_scrutiny",
					data:{customer_id:customer_id,appl_type:appl_type,mo_user_id:mo_user_id},
					beforeSend: function (xhr) { // Add this line
							$(".loader").show();$(".loadermsg").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					}, 
					success: function (data) {

						$(".loader").hide();$(".loadermsg").hide();
						$("#scrutiny_alloction_Modal").hide();
						
						//for reallocation
						if (data=='yes') {
							if(confirm("The Application "+customer_id+" is already allocated and inprocess for scrutiny. Even if you want to Reallocate click 'Ok' else click 'Cancel'.")){
								
								common_ajax_code_scrutiny_alloc(customer_id, appl_type, mo_user_id);
							}
						
						//for first time allocation
						}else{
							
							common_ajax_code_scrutiny_alloc(customer_id, appl_type, mo_user_id);
							
						}							
							
					}
				});
				
				
		   } else {
				$.alert('Please Select All Details. It can not be blank');
				return false;
			}
		});	
	}
	
	
	//added method on 03-02-2023 for common ajax code scrutiny allocation
	function common_ajax_code_scrutiny_alloc(var customer_id, var appl_type, var mo_user_id){
		
		$.ajax({
									
			type: "POST",
			async:true,
			url:"../dashboard/allocate_appl_for_scrutiny",
			data:{customer_id:customer_id,appl_type:appl_type,mo_user_id:mo_user_id},
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {

				$(".loader").hide();$(".loadermsg").hide();
				$("#scrutiny_alloction_Modal").hide();
				alert("The Application "+customer_id+" is successfully allocated for scrutiny to Scrutiny Officer.");
				
				var allocation_by = data.match(/~([^']+)~/)[1];
				
				//to reload list after allocation
				if(allocation_by=='nodal' || allocation_by=='dy_ama'){
					$('#for_scrutiny_allocation_tab').click();
				}else if(allocation_by=='level_4_ro'){
					$('#for_scrutiny_of_so_appl_tab').click();
				}
			}
		});
	}


//for scrutiny allocation popup view scripts
	function inspection_alloc_js_call(){

		$(".close").click(function(){		
			$("#inspection_alloction_Modal").hide();
		});
		
		
		$('.ro_scheduled_date').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			startDate: new Date(),
			clearBtn: true
		
		});
			
		//for Inspection allocation
		$('#inspection_allocate_btn').click(function(){
			
			var appl_type = $("#alloc_appl_type").val();
			var customer_id = $("#alloc_customer_id").val();
			var io_user_id = $("#io_users_list").val();
			var ro_scheduled_date = $("#ro_scheduled_date").val();
			
			if (appl_type != '' && io_user_id != null && customer_id != '' && customer_id != '')
			{
			
				$.ajax({
						type: "POST",
						async:true,
						url:"../dashboard/allocate_appl_for_inspection",
						data:{customer_id:customer_id,appl_type:appl_type,io_user_id:io_user_id,ro_scheduled_date:ro_scheduled_date},
						beforeSend: function (xhr) { // Add this line
								$(".loader").show();$(".loadermsg").show();
								xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
						}, 
						success: function (data) {

								$(".loader").hide();$(".loadermsg").hide();
								$("#inspection_alloction_Modal").hide();
								alert("The Application "+customer_id+" is successfully allocated for Site Inspection to IO user.");
								//to reload list after allocation
								$('#for_inspection_allocation_tab').click();

						}
				});
				
			}else{
				
				$.alert('Please Select All Details. It can not be blank');
				return false;
			}
		});
	}


//to call the specific script function on specific call conditionally
	function MainTabsScriptscall(current_level_script_id,show_list_for_script_id){

		if(current_level_script_id == 'level_3' && show_list_for_script_id != 'rejected'){
			ro_so_common_tabs_js_call();
		
		}else if(current_level_script_id == 'level_4'){
			ho_common_tabs_js_call();
		
		}else if(current_level_script_id == 'level_1'){
			scrutiny_common_tabs_js_call();
		
		}else{
			appl_common_list_js_call();
		}
		subTabsClicksEvents();				
	}

	//function to get value from session and click the sub tab, when come from pending status window
	function subTabsClicksEvents(){
		
		var listSubValue = $("#listSubValue").val();
		
		//RO/SO sub tab
		if (listSubValue=='with_applicant') {
			$("#with_applicant_tab").click();
		}
		if (listSubValue=='with_scrutiny') {
			$("#scrutiny_tab").click();
			$("#scrutiny_tab").addClass('active');
			$("#with_applicant_tab").removeClass('active');
		}
		if (listSubValue=='with_report') {
			$("#reports_tab").click();
			$("#reports_tab").addClass('active');
			$("#with_applicant_tab").removeClass('active');
		}
		if (listSubValue=='with_reg_off') {
			$("#with_reg_offs_tab").click();
			$("#with_reg_offs_tab").addClass('active');
			$("#with_applicant_tab").removeClass('active');
		}
		if (listSubValue=='with_sub_off') {
			$("#with_sub_offs_tab").click();
			$("#with_sub_offs_tab").addClass('active');
			$("#with_applicant_tab").removeClass('active');
		}
		if (listSubValue=='with_ho_off') {
			$("#with_ho_offs_tab").click();
			$("#with_ho_offs_tab").addClass('active');
			$("#with_applicant_tab").removeClass('active');
		}
		
		//scrutiny sub tabs
		if (listSubValue=='scr_with_nodal') {
			$("#with_nodal_office_tab").click();
		}
		if (listSubValue=='scr_with_reg') {
			$("#with_reg_office_tab").click();
			$("#with_reg_office_tab").addClass('active');
			$("#with_nodal_office_tab").removeClass('active');
		}
		if (listSubValue=='scr_with_ho') {
			$("#with_ho_office_tab").click();
			$("#with_ho_office_tab").addClass('active');
			$("#with_nodal_office_tab").removeClass('active');
		}
		
		//HO sub tabs
		if (listSubValue=='scr_with_ho') {
			$("#for_ho_scrutiny_tab").click();
		}
		if (listSubValue=='for_dyama') {
			$("#for_dyama_tab").click();
			$("#for_dyama_tab").addClass('active');
			$("#for_ho_scrutiny_tab").removeClass('active');
		}
		if (listSubValue=='for_jtama') {
			$("#for_jtama_tab").click();
			$("#for_jtama_tab").addClass('active');
			$("#for_ho_scrutiny_tab").removeClass('active');
		}
		if (listSubValue=='for_ama') {
			$("#for_ama_tab").click();
			$("#for_ama_tab").addClass('active');
			$("#for_ho_scrutiny_tab").removeClass('active');
		}
		
		//allocation
		if (listSubValue=='for_scr') {
			$("#for_scrutiny_allocation_tab").click();
		}
		if (listSubValue=='for_ins') {
			$("#for_inspection_allocation_tab").click();
			$("#for_inspection_allocation_tab").addClass('active');
			$("#for_scrutiny_allocation_tab").removeClass('active');
		}
	}
});
