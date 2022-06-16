
$('#common_app_list_table').DataTable({"ordering": false});

$("#allocation_popup_box").hide();

$('.io_scheduled_date').datepicker({
	format: "dd/mm/yyyy",
	autoclose: true,
	startDate: new Date(),
	clearBtn: true

});
	

var session_current_level = $("#session-current-level").val();

if(session_current_level=='level_2' || session_current_level=='level_3' || session_current_level=='level_4'){
	
	var i=1;
	var limit = $("#i-value").val();

	for(i=1;i < limit;i++){	

		(function(p) {
			
				//for scrutiny allocation
				$('#allocate-scrutiny'+p).click(function(){
					
					var appl_type = $("#appl_type"+p).val();
					var customer_id = $("#customer_id"+p).val();
					var comm_with = $("#comm_with"+p).text();
					
					$.ajax({
							type: "POST",
							async:true,
							url:"../dashboard/open_scrutiny_allocation_popup",
							data:({customer_id:customer_id,appl_type:appl_type,comm_with:comm_with}),
							beforeSend: function (xhr) { // Add this line
									$(".loader").show();$(".loadermsg").show();
									xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
							}, 
							success: function (data) {
									$(".loader").hide();$(".loadermsg").hide();
									$("#allocation_popup_box").show();
									$("#allocation_popup_box").html(data);
									$("#scrutiny_alloction_Modal").show();
									
							}
					});
				});
				
				
				
				//for Inspection allocation
				$('#allocate-inspection'+p).click(function(){
					
					var appl_type = $("#appl_type"+p).val();
					var customer_id = $("#customer_id"+p).val();
					var comm_with = $("#comm_with"+p).text();
					
					$.ajax({
							type: "POST",
							async:true,
							url:"../dashboard/open_inspection_allocation_popup",
							data:({customer_id:customer_id,appl_type:appl_type,comm_with:comm_with}),
							beforeSend: function (xhr) { // Add this line
									$(".loader").show();$(".loadermsg").show();
									xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
							}, 
							success: function (data) {
									$(".loader").hide();$(".loadermsg").hide();
									$("#allocation_popup_box").show();
									$("#allocation_popup_box").html(data);
									$("#inspection_alloction_Modal").show();
									
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
				
				
				$("#change_date"+p).click(function(){
					
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
							data:({customer_id:customer_id,appl_type:appl_type,io_scheduled_date:io_scheduled_date,io_sched_date_comment:io_sched_date_comment}),//updated on 12-05-2021 by Amol
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
				$('#reject_appln'+p).click(function(){
					
					var appl_type = $("#appl_type"+p).val();
					var customer_id = $("#customer_id"+p).val();
					
					$.ajax({
							type: "POST",
							async:true,
							url:"../dashboard/open_reject_appl_popup",
							data:({customer_id:customer_id,appl_type:appl_type}),
							beforeSend: function (xhr) { // Add this line
									$(".loader").show();$(".loadermsg").show();
									xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
							}, 
							success: function (data) {
									$(".loader").hide();$(".loadermsg").hide();
									$("#allocation_popup_box").show();
									$("#allocation_popup_box").html(data);
									$("#common_reject_Modal").show();
									
							}
					});
				});
				
		})(i);

	}

	
}