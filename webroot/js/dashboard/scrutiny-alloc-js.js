$(".close").click(function(){		
		$("#scrutiny_alloction_Modal").hide();
	});
	
	
	
//for scrutiny allocation
		$('#scrutiny_allocate_btn').click(function(){
			
			var appl_type = $("#alloc_appl_type").val();
			var customer_id = $("#alloc_customer_id").val();
			var mo_user_id = $("#mo_users_list").val();
			
			$.ajax({
					type: "POST",
					async:true,
					url:"../dashboard/allocate_appl_for_scrutiny",
					data:({customer_id:customer_id,appl_type:appl_type,mo_user_id:mo_user_id}),
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
		});	