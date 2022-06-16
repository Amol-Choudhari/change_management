$(document).ready(function() {
    $('#added_appl_list').DataTable({"order": []});
	
	//below functinality added on 08-04-2022 by Amol
	//to notify Admin if last Incharge of that office changed who granted the application
	$("#add_appl").click(function(e){
		e.preventDefault();
		
		var customer_id = $('#customer_id').val();
		$.ajax({			
			type: "POST",
			url: "../Masters/check_incharge_to_reesign",
			data:{customer_id:customer_id},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success:function(res){	

				var res = res.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response
				if(res!=1){
					if(confirm("The incharge who granted this application has been changed, So application will forwarded to current in-charge '"+res+"' for re-esign.")){						
						$("#add_reesign_form").submit();
						
					}else{
						alert("The application is not added for re-esign, as you have cancel the allocation");
						return false;
					}
				}else{
					$("#add_reesign_form").submit();
				}
			}			
		});
		
	});
});
