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
			
			if(ro_scheduled_date != ''){
				$.ajax({
						type: "POST",
						async:true,
						url:"../dashboard/allocate_appl_for_inspection",
						data:({customer_id:customer_id,appl_type:appl_type,io_user_id:io_user_id,ro_scheduled_date:ro_scheduled_date}),
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
				
				alert('Please Schedule Date for Site Inspection. It can not be blank');
			}
		});