	$('#approved_applications_report_table').DataTable();
		// Change on 3/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
		$('.search_field').val('');

		$('#application_type').multiselect({
			placeholder: 'Select Application Type',
            includeSelectAllOption: true,
			nonSelectedText :'Select Application Type',
			buttonWidth: '100%',
            maxHeight: 400,
		});

		$('#office').multiselect({
			placeholder: 'Select Office',
			includeSelectAllOption: true,
			buttonWidth: '100%',
            maxHeight: 200,
		});

		$(document).ready(function () {

			$('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
			$('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });
			$('#approved_applications_report_table').DataTable();

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
