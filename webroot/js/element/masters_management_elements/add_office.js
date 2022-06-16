


	//FOR VALIDATING THE LIMIT OF OFFICE CODE FOR THE 15-DIGIT AT 1 AND ALPHABET ONLY IS ADDED BY AKASH ON 02-12-2021
	$(document).ready(function() {
	    $('#replica_code').on('input propertychange', function() {
			charLimit(this, 1);
	    });

	   	$('#short_code').on('input propertychange', function() {
			charLimit(this, 3);
	    });
	
	});
	    
	let charLimit = (input, maxChar) => {

	    let len = $(input).val().length;
	    if (len > maxChar) {
	            $(input).val($(input).val().substring(0, maxChar));
	    }
	}

	

	//FOR CHECKING THE REPLCIA CODE FOR 15-DIGIT CODE ALREADY EXITS AJAX AND VALIDATION IS ADDED BY AKASH ON 02-12-2021
	$('#replica_code').focusout(function(){

		var replica_code = $("#replica_code").val();

		$.ajax({
			type : 'POST',
			url : '../AjaxFunctions/check_if_replica_code_is_exist',
			async : true,
			data : {replica_code:replica_code},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success : function(response){

				if($.trim(response)=='yes'){

					$.alert({
						title: "Office Code Error!",
					    content: 'The Offce Code <b>'+ replica_code  +'</b> is already used. Please verify and enter again.',
					    type: 'red',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        tryAgain: {
					            text: 'Try again',
					            btnClass: 'btn-red',
					            action: function(){
					            	$("#replica_code").val('');
					            }
					        },
						}
					});
				}
			}
		});
	});




	//FOR CHECKING THE SHORT CODE OF OFFICE IS ALREADY EXITS THE AJAX AND VALIDATION IS ADDED BY AKASH ON 02-12-2021
	$('#short_code').focusout(function(){

		var short_code = $("#short_code").val();

		$.ajax({
			type : 'POST',
			url : '../AjaxFunctions/check_if_office_short_code_is_exist',
			async : true,
			data : {short_code:short_code},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success : function(response){

				if($.trim(response)=='yes'){

					$.alert({
						title: "Office Short Code Error!",
					    content: 'The Short Code <b>'+ short_code  +'</b> is already used. Please verify and enter again.',
					    type: 'red',
				     	columnClass: 'medium',
					    typeAnimated: true,
					    buttons: {
					        tryAgain: {
					            text: 'Try again',
					            btnClass: 'btn-red',
					            action: function(){
					            	$("#short_code").val('');
					            }
					        },
						}
					});
				}
			}
		});
	});


	//for already checked		
	if($('#office_type-ro').is(":checked")){		

		$("#ro_email_list").show();
		$("#short_code_div").show();
		$("#ral_email_list").hide();
		$("#so_email_list").hide();		
		$("#ro_office_list").hide();	
		$("#replica_code_div").show();												  
	}
			
	
	//when clicked
	$('#office_type-ro').click(function(){		

		$("#ro_email_list").show();
		$("#short_code_div").show();
		$("#ral_email_list").hide();
		$("#so_email_list").hide();
		$("#ro_office_list").hide();	
		$("#replica_code_div").show();					  
									
	});
			
	$('#office_type-ral').click(function(){		

		$("#ro_email_list").hide();
		$("#short_code").val('');
		$("#short_code_div").hide();
		$("#ral_email_list").show();
		$("#so_email_list").hide();
		$("#ro_office_list").hide();		
		$("#replica_code_div").hide();		  
							
	});

	$('#office_type-so').click(function(){

		$("#ro_email_list").hide();
		$("#short_code_div").show();
		$("#ral_email_list").hide();
		$("#so_email_list").show();
		$("#ro_office_list").show();
		$("#replica_code_div").show();					  

									
	});