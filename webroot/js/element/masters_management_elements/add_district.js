
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


	//FOR CHECKING THE District name ALREADY EXITS AJAX AND VALIDATION IS ADDED BY AKASH ON 04-12-2021
	$('#district_name').focusout(function(){

		var district_name = $("#district_name").val();

		$.ajax({
			type : 'POST',
			url : '../AjaxFunctions/check_if_district_already_exist',
			async : true,
			data : {district_name:district_name},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success : function(response){

				if($.trim(response)=='yes'){

					$.alert({
						title: "District Name Error!",
					    content: 'The Distric Name <b>'+ district_name  +'</b> is already used. Please verify and enter again.',
					    type: 'red',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        tryAgain: {
					            text: 'Try again',
					            btnClass: 'btn-red',
					            action: function(){
					            	$("#district_name").val('');
					            }
					        },
						}
					});
				}
			}
		});
	});
