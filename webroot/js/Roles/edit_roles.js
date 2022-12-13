
	$(document).ready(function() {

		$('#mo_allocated_list_table').DataTable();
		$('#io_allocated_list_table').DataTable();
		$('#ho_mo_allocated_list_table').DataTable();




		//created this code to compare user office type with existing user from same office
		//on 04-01-2020 by Amol
		$("input[type='radio']").click(function() {

			var office_type_val = $("input[type='radio']:checked").val();
			var user_id = $('#user_list').val();

			$.ajax({

				type: "POST",
				url: "../roles/check_office_type",
				data: {user_id:user_id,office_type_val:office_type_val},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function(response) {

					var m = response.match(/"(.*?)"/); //to remove extra contents come with reponse
					if (m[1] != office_type_val && m[1] != null) {

						alert('Please select "'+m[1]+'" as office type for this user.');
						$("input[type='radio']").prop('checked',false);
					}
				}

			});

		});



		// If search empty fields //

		$('#search_btn').click(function() {
			var search_file=$("#search_file").val();
			if (start_date=="") {
				alert("Sorry...All search Fields are empty");
				return false;
			}
		});


		// Start to Check entry of dy_ama, jt_ama, ama into user_roles tabels for duplicate set roles for dy_ama, jt_ama, ama into user_roles
		// Done by pravin 30-08-2017
		var dyama_set_role_detail = $('#dyama_set_role_detail').val();
		
		$("#dy_ama").change(function() {
			
			if ($(this).prop('checked') == true) {
				
				if(dyama_set_role_detail == '' || dyama_set_role_detail == null){ dyama_set_role_detail = null; }
				//alert(dyama_set_role_detail);
				if (dyama_set_role_detail != null) {

					$.alert({
						title: "Alert!",
					    content: 'The role of Dy. AMA (QC) is already asigned to '+atob(dyama_set_role_detail)+'. Only one user ID can be allocated the role of Dy. AMA (QC)',
					    type: 'red',
						icon: 'fa fa-warning',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        Ok: {
					            text: 'Ok',
					            btnClass: 'btn-red',
					            action: function(){
									//this.dyama_set_role_detail.prop('checked', false);
					            	$('#dy_ama').prop('checked', false);
					            }
					        },
						}
					});
				}

			} else {

				var confirm_result = confirm('Are you sure to remove Dy. AMA (QC) role?');
				if (confirm_result == false) {
					$(this).prop('checked',true);
				}
			}

		});



		var jtama_set_role_detail = $('#jtama_set_role_detail').val();

		$("#jt_ama").change(function() {

			if ($(this).prop('checked') == true) {

				if(jtama_set_role_detail == '' || jtama_set_role_detail == null){ jtama_set_role_detail = null; }

				if (jtama_set_role_detail != null) {

					$.alert({
						title: "Alert!",
					    content: 'The role of Jt. AMA is already asigned to '+atob(jtama_set_role_detail)+'. Only one user ID can be allocated the role of Jt. AMA',
					    type: 'red',
						icon: 'fa fa-warning',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        Ok: {
					            text: 'Ok',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#jt_ama').prop('checked', false);
					            }
					        },
						}
					});
				}

			} else {

				var confirm_result = confirm('Are you sure to remove Jt. AMA role?');
				if (confirm_result == false) {
					$(this).prop('checked',true);
				}
			}
		});


		$("#ama").change(function() {

			if ($(this).prop('checked') == true) {

				if(ama_set_role_detail == '' || ama_set_role_detail == null) { ama_set_role_detail = null; }

				 if (ama_set_role_detail != null) {
					
					$.alert({
						title: "Alert!",
					    content: 'The role of AMA is already asigned to '+atob(ama_set_role_detail)+'. Only one user ID can be allocated the role of AMA',
					    type: 'red',
						icon: 'fa fa-warning',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        Ok: {
					            text: 'Ok',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#ama').prop('checked', false);
					            }
					        },
						}
					});
				}

			} else {

				var confirm_result = confirm('Are you sure to remove AMA role?');
				if (confirm_result == false) {
					$(this).prop('checked',true);
				}
			}
		});

		// End to Check entry of dy_ama, jt_ama, ama into user_roles tabels


		//apply validation before remove RO incharge role
		// Done By pravin 02-09-2017
		var ro_office_details = $('#ro_office_details').val();
		var user_id = $('#user_id').val();
		var ro_office = $('#ro_office').val();
		

		$("#ro_inspection").change(function() {

			if ($(this).prop('checked') == false) {

				if(ro_office_details == '' || ro_office_details == null) { ro_office_details = null; }
			
				//to check if the user is currently Incharge of any office, then alert and revert
				if (ro_office_details != null) {

					$.alert({
						title: "Alert!",
					    content: 'Currently this id '+atob(user_id)+' have RO In-charge of '+ro_office+'. The role of RO In-charge cannot be removed unless new RO In-charge is re-allocated.',
					    type: 'red',
						icon: 'fa fa-warning',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        Ok: {
					            text: 'Ok',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#ro_inspection').prop('checked', true);
					            }
					        },
						}
					});
					/*if (!alert('')) {

						$(this).prop('checked',true);
					}*/
					
				}
			
			//to check if the user is currently having SO incharge role, then alert to remove and revert
			}else if ($(this).prop('checked') == true) { 
			
				if ($("#so_inspection").prop('checked') == true) {
					
					$.alert({
						title: "Alert!",
					    content: 'Please remove SO In-charge role to assign RO In-charge role',
					    type: 'red',
						icon: 'fa fa-warning',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        Ok: {
					            text: 'Ok',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#ro_inspection').prop('checked', false);
					            }
					        },
						}
					});
				}
			
			}
		});


		//apply validation before remove SO incharge role
		// By Amol on 11-05-2021
		var so_office_details = $('#so_office_details').val();
		var so_office = $('#so_office').val();

		$("#so_inspection").change(function() {

			if ($(this).prop('checked') == false) {

				if(so_office_details == '' || so_office_details == null) { so_office_details = null; }

				//to check if the user is currently Incharge of any office, then alert and revert
				if (so_office_details != null) {

					$.alert({
						title: "Alert!",
					    content: 'Currently this id '+atob(user_id)+' have SO In-charge of '+so_office+'. The role of SO In-charge cannot be removed unless new SO In-charge is re-allocated.',
					    type: 'red',
						icon: 'fa fa-warning',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        Ok: {
					            text: 'Ok',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#so_inspection').prop('checked', true);
					            }
					        },
						}
					});
				}
			
			//to check if the user is currently having RO incharge role, then alert to remove and revert
			 }else if ($(this).prop('checked') == true) { 
			
				if ($("#ro_inspection").prop('checked') == true) {
					
					$.alert({
						title: "Alert!",
					    content: 'Please remove RO In-charge role to assign SO In-charge role',
					    type: 'red',
						icon: 'fa fa-warning',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        Ok: {
					            text: 'Ok',
					            btnClass: 'btn-red',
					            action: function(){
					            	$('#so_inspection').prop('checked', false);
					            }
					        },
						}
					});
				}
			
			}
		 });


		 var mo_allocated_running_application_list = $('#mo_allocated_running_application_list').val();
		 var mo_renewal_allocated_running_application_list = $('#mo_renewal_allocated_running_application_list').val();

		$("#mo_allocated_list").hide();

		$("#mo_smo_inspection").change(function() {

			if ($(this).prop('checked') == false) {

				if(mo_allocated_running_application_list == '' || mo_allocated_running_application_list == null) { mo_allocated_running_application_list = null; }

				if(mo_renewal_allocated_running_application_list == '' || mo_renewal_allocated_running_application_list == null) { mo_renewal_allocated_running_application_list = null; }

			 	if(mo_allocated_running_application_list != null || mo_renewal_allocated_running_application_list != null) {

					if (!alert('Currently this id '+user_id+' has some applications assigned. The role of Scrutiny Officer cannot be removed unless all the applications assigned are re-allocated. List of all  such applications is shown below')) {

						$(this).prop('checked',true);
						$("#mo_allocated_list").show();
					}
				}
			}
		});


		var io_allocated_running_application_list = $('#io_allocated_running_application_list').val();
		var io_renewal_allocated_running_application_list = $('#io_renewal_allocated_running_application_list').val();

		$("#io_allocated_list").hide();
		$("#io_inspection").change(function() {

			if ($(this).prop('checked') == false) {

				if(io_allocated_running_application_list == '' || io_allocated_running_application_list == null) { io_allocated_running_application_list = null; }

				if(io_renewal_allocated_running_application_list == '' || io_renewal_allocated_running_application_list == null) { io_renewal_allocated_running_application_list = null; }

				if(io_allocated_running_application_list != null || io_renewal_allocated_running_application_list != null) {

						if (!alert('Currently this id '+user_id+' has some applications assigned. The role of Inspection officer cannot be removed unless all the applications assigned are re-allocated. List of all  such applications is shown below')) {

							$(this).prop('checked',true);
							$("#io_allocated_list").show();
						}
					}
				}
		});


		var ho_mo_allocated_running_application_list = $('#ho_mo_allocated_running_application_list').val();

		$("#ho_mo_allocated_list").hide();
		$("#ho_mo_smo").change(function() {

			if ($(this).prop('checked') == false) {

				if(ho_mo_allocated_running_application_list == '' || ho_mo_allocated_running_application_list == null) { ho_mo_allocated_running_application_list = null; }

 					if (ho_mo_allocated_running_application_list != null) {

						if (!alert('Currently this id '+user_id+' has some applications assigned. The role of HO Scrutiny Officer cannot be removed unless all the applications assigned are re-allocated. List of all  such applications is shown below')) {

							$(this).prop('checked',true);
							$("#ho_mo_allocated_list").show();
						}
					}
				}

			});

	});



	//create the dynamic path for ajax url (Done by pravin 03/11/2017)
	var host = location.hostname;
	var paths = window.location.pathname;
	var split_paths = paths.split("/");
	var path = "/"+split_paths[1]+"/"+split_paths[2];



	$('#user_list').change(function(e) {

		$("#dmi_user_roles_list_box").hide();
		$("#lmis_user_roles_list_box").hide();
		$("#both_user_roles_list_box").hide();
		$("#update_roles_btn").css('display','none');

		var user_id = $('#user_list').val();

		var form_data = $("#edit_roles_form").serializeArray();
		form_data.push(	{name: "user_id",value: user_id});

		$.ajax({

			type: "POST",
			url: path+"/user_division_type",
			data: form_data,
			success: function(response) {

				$("#user_division").html(response);
			},

			error: function(data) {
				//alert(data);  // Change by pravin 05-09-2018
			}
		});

	});

		var user_division_type = $('#$user_division_type').val();

		 if (user_division_type == 'DMI') {

			$("#dmi_user_roles_list_box").show();
			$("#lmis_user_roles_list_box").hide();
			$("#both_user_roles_list_box").show();

		} else if (user_division_type == 'LMIS') {

			$("#dmi_user_roles_list_box").hide();
			$("#lmis_user_roles_list_box").show();
			$("#both_user_roles_list_box").show();

		} else {

			$("#dmi_user_roles_list_box").show();
			$("#lmis_user_roles_list_box").show();
			$("#both_user_roles_list_box").show();
		}

		//Check pao pending works before remove pao role from any user, Change on 14-12-2018 , By Pravin Bhakare
		var pao_pending_works = $('#pao_pending_works').val();

		$("#pao").change(function() {

			if ($(this).prop('checked') == false) {

				if (pao_pending_works == 'false') {

						if (!alert('Currently the user '+user_id+' has PAO/DDO charge for some districts. To remove role, please release districts from PAO/DDO master.')) {

							$(this).prop('checked',true);
						}

				}
			}

		});
