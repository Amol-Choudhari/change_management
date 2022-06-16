$(document).ready(function(){
			//added on 12-08-2017 by Amol to avoid copy paste on confirm email field
			$('#confirm_email').bind("cut copy paste",function(e) {
				e.preventDefault();
			});

			chemist_registration_validations();
		});


		$(document).ready(function () {
			$('#dob').datepicker({
				format: "dd/mm/yyyy",
				autoclose: true,
				 startDate: '-50y',
				 endDate: '-20y'
			});
		});