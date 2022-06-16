
        // Change on 2/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
        $('.search_field').val('');

    // for multiselect dropdown option

     $('#application_type').multiselect({
        placeholder: 'Select Application Type',
        includeSelectAllOption: true,
        nonSelectedText :'Select Application Type',
        buttonWidth: '100%',
        maxHeight: 400,
        });


    /*$('#office').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '100%',
        maxHeight: 200,
    });*/

    $('#office_ro_input').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '100%',
        maxHeight: 200,
    });

    $('#office_mo_input').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '100%',
        maxHeight: 200,
    });
    $('#office_io_input').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '100%',
        maxHeight: 200,
    });

    //create the dynamic path for ajax url (Done by pravin 03/11/2017)
    var host = location.hostname;
    var paths = window.location.pathname;
    var split_paths = paths.split("/");
    var path = "/"+split_paths[1]+"/"+split_paths[2];


    $("#office_all").show();
    $("#office_ro").hide();
    $("#office_mo").hide();
    $("#office_io").hide();
    $("#office_ho_mo").hide();
    $("#office_dyama").hide();
    $("#office_jtama").hide();
    $("#office_ama").hide();

    $('#user_role').change(function(e){

        // Change on 09/11/2018, Unchecked the OR office checked options on the change of user role.
        $("#office_ro input").prop("checked",false);
        $("#office_ro button").prop("title",'None selected');
        $("#office_ro .multiselect-selected-text").text('None selected');
        $("#office_ro .dropdown-menu .active a ").css('background-color','#f5f5f5');
        $("#office_ro .dropdown-menu .active a ").css('color','#333');

        // Change on 09/11/2018, Unchecked the MO office checked options on the change of user role.
        $("#office_mo input").prop("checked",false);
        $("#office_mo button").prop("title",'None selected');
        $("#office_mo .multiselect-selected-text").text('None selected');
        $("#office_mo .dropdown-menu .active a ").css('background-color','#f5f5f5');
        $("#office_mo .dropdown-menu .active a ").css('color','#333');

        // Change on 09/11/2018, Unchecked the IO office checked options on the change of user role.
        $("#office_io input").prop("checked",false);
        $("#office_io button").prop("title",'None selected');
        $("#office_io .multiselect-selected-text").text('None selected');
        $("#office_io .dropdown-menu .active a ").css('background-color','#f5f5f5');
        $("#office_io .dropdown-menu .active a ").css('color','#333');

        e.preventDefault();

        var user_role = $('#user_role').val();

        if(user_role == 'RO/SO'){$("#office_ro").show();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
        else if(user_role == 'MO/SMO'){$("#office_ro").hide();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").show();$("#office_io").hide();$("#office_ho_mo").hide();}
        else if(user_role == 'IO'){$("#office_ro").hide();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").hide();$("#office_io").show();$("#office_ho_mo").hide();}
        else if(user_role == 'HO MO/SMO'){$("#office_ro").hide();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").show();}
        else if(user_role == 'DY.AMA'){$("#office_dyama").show();$("#office_all").hide();$("#office_ro").hide();$("#office_jtama").hide();$("#office_ama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
        else if(user_role == 'JT.AMA'){$("#office_jtama").show();$("#office_all").hide();$("#office_ro").hide();$("#office_dyama").hide();$("#office_ama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
        else if(user_role == 'AMA'){$("#office_ama").show();$("#office_all").hide();$("#office_ro").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
        else {$("#office_all").show();$("#office_ro").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}

        if(user_role == ''){var user_role = 'null_value';}
        var ro_offices = null;
        var mo_offices = null;
        var io_offices = null;
        var ho_mo_offices = null;

        var form_data = $("#pending_application_report").serializeArray();
        form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
                        {name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
                        {name: "user_role",value: user_role});

        $.ajax({
            type: "POST",
            url: path+"/pending_application_report_user_id",
            data: form_data,
            success: function(response){

                $("#user_id").html(response);
            }
        });


    });


    $('#office_ro_input').change(function(e){

        var user_role = null;
        var ro_offices = $('#office_ro_input').val();
        var mo_offices = null;
        var io_offices = null;
        var ho_mo_offices = null;
        var form_data = $("#pending_application_report").serializeArray();
        form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
                        {name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
                        {name: "user_role",value: user_role});

        $.ajax({
            type: "POST",
            url: path+"/pending_application_report_user_id",
            data: form_data,
            success: function(response){

                $("#user_id").html(response);
            }
        });
    });

    $('#office_mo_input').change(function(e){

        var user_role = null;
        var ro_offices =  null;
        var mo_offices = $('#office_mo_input').val();
        var io_offices = null;
        var ho_mo_offices = null;

        var form_data = $("#pending_application_report").serializeArray();
        form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
                        {name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
                        {name: "user_role",value: user_role});

        $.ajax({
            type: "POST",
            url: path+"/pending_application_report_user_id",
            data: form_data,
            success: function(response){

                $("#user_id").html(response);
            }
        });
    });

    $('#office_io_input').change(function(e){	

			// Change on 2/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
			$('.search_field').val('');

		// for multiselect dropdown option

		 $('#application_type').multiselect({
			placeholder: 'Select Application Type',
            includeSelectAllOption: true,
			nonSelectedText :'Select Application Type',
			buttonWidth: '100%',
            maxHeight: 400,
			});


		/*$('#office').multiselect({
			includeSelectAllOption: true,
			buttonWidth: '100%',
            maxHeight: 200,
		});*/

		$('#office_ro_input').multiselect({
			includeSelectAllOption: true,
			buttonWidth: '100%',
            maxHeight: 200,
		});

		$('#office_mo_input').multiselect({
			includeSelectAllOption: true,
			buttonWidth: '100%',
            maxHeight: 200,
		});
		$('#office_io_input').multiselect({
			includeSelectAllOption: true,
			buttonWidth: '100%',
            maxHeight: 200,
		});

		//create the dynamic path for ajax url (Done by pravin 03/11/2017)
		var host = location.hostname;
		var paths = window.location.pathname;
		var split_paths = paths.split("/");
		var path = "/"+split_paths[1]+"/"+split_paths[2];


		$("#office_all").show();
		$("#office_ro").hide();
		$("#office_mo").hide();
		$("#office_io").hide();
		$("#office_ho_mo").hide();
		$("#office_dyama").hide();
		$("#office_jtama").hide();
		$("#office_ama").hide();

		$('#user_role').change(function(e){

			// Change on 09/11/2018, Unchecked the OR office checked options on the change of user role.
			$("#office_ro input").prop("checked",false);
			$("#office_ro button").prop("title",'None selected');
			$("#office_ro .multiselect-selected-text").text('None selected');
			$("#office_ro .dropdown-menu .active a ").css('background-color','#f5f5f5');
			$("#office_ro .dropdown-menu .active a ").css('color','#333');

			// Change on 09/11/2018, Unchecked the MO office checked options on the change of user role.
			$("#office_mo input").prop("checked",false);
			$("#office_mo button").prop("title",'None selected');
			$("#office_mo .multiselect-selected-text").text('None selected');
			$("#office_mo .dropdown-menu .active a ").css('background-color','#f5f5f5');
			$("#office_mo .dropdown-menu .active a ").css('color','#333');

			// Change on 09/11/2018, Unchecked the IO office checked options on the change of user role.
			$("#office_io input").prop("checked",false);
			$("#office_io button").prop("title",'None selected');
			$("#office_io .multiselect-selected-text").text('None selected');
			$("#office_io .dropdown-menu .active a ").css('background-color','#f5f5f5');
			$("#office_io .dropdown-menu .active a ").css('color','#333');

			e.preventDefault();

			var user_role = $('#user_role').val();

			if(user_role == 'RO/SO'){$("#office_ro").show();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
			else if(user_role == 'MO/SMO'){$("#office_ro").hide();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").show();$("#office_io").hide();$("#office_ho_mo").hide();}
			else if(user_role == 'IO'){$("#office_ro").hide();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").hide();$("#office_io").show();$("#office_ho_mo").hide();}
			else if(user_role == 'HO MO/SMO'){$("#office_ro").hide();$("#office_all").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide(); $("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").show();}
			else if(user_role == 'DY.AMA'){$("#office_dyama").show();$("#office_all").hide();$("#office_ro").hide();$("#office_jtama").hide();$("#office_ama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
			else if(user_role == 'JT.AMA'){$("#office_jtama").show();$("#office_all").hide();$("#office_ro").hide();$("#office_dyama").hide();$("#office_ama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
			else if(user_role == 'AMA'){$("#office_ama").show();$("#office_all").hide();$("#office_ro").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}
			else {$("#office_all").show();$("#office_ro").hide();$("#office_dyama").hide();$("#office_jtama").hide();$("#office_ama").hide();$("#office_mo").hide();$("#office_io").hide();$("#office_ho_mo").hide();}

			if(user_role == ''){var user_role = 'null_value';}
			var ro_offices = null;
			var mo_offices = null;
			var io_offices = null;
			var ho_mo_offices = null;

			var form_data = $("#pending_application_report").serializeArray();
			form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
							{name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
							{name: "user_role",value: user_role});

			$.ajax({
				type: "POST",
				url: path+"/pending_application_report_user_id",
				data: form_data,
				success: function(response){

					$("#user_id").html(response);
				}
			});


		});


		$('#office_ro_input').change(function(e){

			var user_role = null;
			var ro_offices = $('#office_ro_input').val();
			var mo_offices = null;
			var io_offices = null;
			var ho_mo_offices = null;
			var form_data = $("#pending_application_report").serializeArray();
			form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
							{name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
							{name: "user_role",value: user_role});

			$.ajax({
				type: "POST",
				url: path+"/pending_application_report_user_id",
				data: form_data,
				success: function(response){

					$("#user_id").html(response);
				}
			});
		});

		$('#office_mo_input').change(function(e){

			var user_role = null;
			var ro_offices =  null;
			var mo_offices = $('#office_mo_input').val();
			var io_offices = null;
			var ho_mo_offices = null;

			var form_data = $("#pending_application_report").serializeArray();
			form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
							{name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
							{name: "user_role",value: user_role});

			$.ajax({
				type: "POST",
				url: path+"/pending_application_report_user_id",
				data: form_data,
				success: function(response){

					$("#user_id").html(response);
				}
			});
		});

		$('#office_io_input').change(function(e){

			var user_role = null;
			var ro_offices =  null;
			var mo_offices = null;
			var io_offices = $('#office_io_input').val();
			var ho_mo_offices = null;

			var form_data = $("#pending_application_report").serializeArray();
			form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
							{name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
							{name: "user_role",value: user_role});

			$.ajax({
				type: "POST",
				url: path+"/pending_application_report_user_id",
				data: form_data,
				success: function(response){

					$("#user_id").html(response);
				}
			});
		});

		$('#office_ho_mo_input').change(function(e){

			var user_role = null;
			var ro_offices =  null;
			var mo_offices = null;
			var io_offices = null;
			var ho_mo_offices = $('#office_ho_mo_input').val();

			var form_data = $("#pending_application_report").serializeArray();
			form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
							{name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
							{name: "user_role",value: user_role});

			$.ajax({
				type: "POST",
				url: path+"/pending_application_report_user_id",
				data: form_data,
				success: function(response){

					$("#user_id").html(response);
				}
			});
		});

		$(document).ready(function () {

			$('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
			$('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });
			$('#pending-new-applications-report-data-table').DataTable({/*"ordering": false*/});


			$('#search_btn').click(function(){

				var from = $("#fromdate").val().split("/");
				var fromdate = new Date(from[2], from[1] - 1, from[0]);

				var from = $("#todate").val().split("/");
				var todate = new Date(from[2], from[1] - 1, from[0]);

				if(todate < fromdate){

					alert('Invalid Date Range Selection');
					return false;
				}
			});

			$('html, body').animate({
        		scrollTop: $('#page-load').offset().top
    		}, 'slow');

		});




        var user_role = null;
        var ro_offices =  null;
        var mo_offices = null;
        var io_offices = $('#office_io_input').val();
        var ho_mo_offices = null;

        var form_data = $("#pending_application_report").serializeArray();
        form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
                        {name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
                        {name: "user_role",value: user_role});

        $.ajax({
            type: "POST",
            url: path+"/pending_application_report_user_id",
            data: form_data,
            success: function(response){

                $("#user_id").html(response);
            }
        });
    });

    $('#office_ho_mo_input').change(function(e){

        var user_role = null;
        var ro_offices =  null;
        var mo_offices = null;
        var io_offices = null;
        var ho_mo_offices = $('#office_ho_mo_input').val();

        var form_data = $("#pending_application_report").serializeArray();
        form_data.push(	{name: "ro_offices",value: ro_offices},{name: "mo_offices",value: mo_offices},
                        {name: "io_offices",value: io_offices},{name: "ho_mo_offices",value: ho_mo_offices},
                        {name: "user_role",value: user_role});

        $.ajax({
            type: "POST",
            url: path+"/pending_application_report_user_id",
            data: form_data,
            success: function(response){

                $("#user_id").html(response);
            }
        });
    });

    $(document).ready(function () {

        $('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
        $('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });
        $('#pending-new-applications-report-data-table').DataTable({/*"ordering": false*/});


        $('#search_btn').click(function(){

            var from = $("#fromdate").val().split("/");
            var fromdate = new Date(from[2], from[1] - 1, from[0]);

            var from = $("#todate").val().split("/");
            var todate = new Date(from[2], from[1] - 1, from[0]);

            if(todate < fromdate){

                alert('Invalid Date Range Selection');
                return false;
            }
        });

        $('html, body').animate({
            scrollTop: $('#page-load').offset().top
        }, 'slow');

    });
