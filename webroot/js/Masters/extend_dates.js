

	$(document).ready(function () {

		$('#ren_ext_dt').datepicker({
			format: "dd/mm",
			autoclose: true,
			forceParse: false,
			orientation:'top'
		});



		$("#cert_type").change(function(){

			var cert_type = $("#cert_type").val();
			$.ajax({
				type: "POST",
				async:true,
				url:"../masters/fetch_ext_date",
				data:{cert_type:cert_type},
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				},
				success: function (response) {
					response = response.match(/~([^']+)~/)[1];
					$("#ren_ext_dt").val(response);
				}
			});
		});

	});
