
	$(document).ready(function(){

		$("#ro_list_div").hide();
		$("#so_list_div").hide();

		if($('#dist_office_type-ro').is(":checked")){
			$("#ro_list_div").show();

		}else if($('#dist_office_type-so').is(":checked")){
			$("#ro_list_div").show();
			$("#so_list_div").show();
		}

		$('#dist_office_type-ro').click(function(){
			$("#ro_list_div").show();
			$("#so_list_div").hide();

		});

		$('#dist_office_type-so').click(function(){
			$("#so_list_div").show();

		});


	});
