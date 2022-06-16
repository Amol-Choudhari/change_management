

$(document).ready(function () {
	
		$("#user_work_list").dataTable();//to display list as it is in result array order

		var for_user_id = $("#users_list").val();
		$("#list_title").text('Under process applications in which "'+ atob(for_user_id) +'" is involved');

		var i=1;
		var limit = $('#increment_id').val();

		for(i=1;i < limit;i++){

		//to allocate selected application
			(function(p) {

				$('#user_work_list').on('click', '#allocate_btn'+p, function(){//applied btn id as selector to .on method and click event on table id, to apply onlick event to every record in datatables

					var allocate_to = $("#allocate_to"+p).val();

					if(allocate_to == ''){
						
						$.alert({
							title: 'Alert!',
							type: 'red',
							icon: 'fa fa-warning',
							columnClass: 'medium',
							content: 'Please select user id to whom the application may be transfered.',
						
						});
						return false;
					}

					$.confirm({
			
						icon: 'fas fa-info-circle',
						content: 'Are you sure for the action taken ?',
						columnClass: 'col-md-6 col-md-offset-3',
						buttons: {
			
							confirm: { 
			
								btnClass: 'btn-green',
								action: function () {
			
									var appl_type = $("#appl_type"+p).text();
									var appl_id = $("#appl_id"+p).text();
									var rels_from = $("#rels_from"+p).text();


									$.ajax({
										type: "POST",
										async:true,
										url:"../othermodules/transfer_work",
										data:({appl_id:appl_id,appl_type:appl_type,for_user_id:for_user_id,rels_from:rels_from,allocate_to:allocate_to}),
										beforeSend: function (xhr) {
											$("#work_transfer_loader").show();
											xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
										},
										success: function (data) {

											$("#work_transfer_loader").hide();

											data = data.match(/~([^']+)~/)[1]; //fetching value between ~~ string
											
											if(data == 'done'){

												$.alert({

													title: 'Alert!',
													columnClass: 'medium',
													content: 'The New Selected User id "' + allocate_to + '" has been Reallocated on Place of "' + for_user_id + '" for Application id "' + appl_id + '" on ' + rels_from,
													buttons: {
														Okay: { 
															btnClass: 'btn-blue',
															action: function () {
													
																$("#get_details").click();
															}
														},
													}
												});
						
											}else{
												$.alert('Sorry... Please try again');
												return false;
											}
										}
									});
								}
							},
							
							cancel:{
								
								btnClass: 'btn-red',
								action: function () {}
							},
						}
					});
				});
			})(i);
		}


		for(i=1;i < limit;i++){
			//to view status of selected application
			(function(p) {	

				$('#user_work_list').on('click', '#view_status_btn'+p, function(){//applied btn id as selector to .on method and click event on table id, to apply onlick event to every record in datatables

					var appl_type = $("#appl_type"+p).text();
					var appl_id = $("#appl_id"+p).text();

					$.ajax({
						type: "POST",
						async:true,
						url:"../othermodules/show_appl_status_popup",
						data:({appl_id:appl_id,appl_type:appl_type}),
						beforeSend: function (xhr) {
							$("#work_transfer_loader").show();
							xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
						},
						success: function (data) {
							$("#work_transfer_loader").hide();

							resArray = data.match(/~([^']+)~/)[1]; //fetching value between ~~ string
							resArray = JSON.parse(resArray);

							$("#show_appl_id").text(resArray['appl_id']);
							$("#show_firm_name").text(resArray['firm_name']);
							$("#show_applied_on").text(resArray['applied_on']);
							$("#show_currently_with").text(resArray['currently_with']);
							$("#show_last_status").text(resArray['last_status'] + ' ' + 'on' + ' ' + resArray['last_status_date']);
							$("#show_appl_status").show();
						}
					});
				});
			})(i);
		}

		$(".close").click(function(){

			$("#show_appl_status").hide();
		});




});
