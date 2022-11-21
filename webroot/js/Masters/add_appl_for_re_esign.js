$(document).ready(function() {


    $('#added_appl_list').DataTable({"order": []});
	
	//below functinality added on 08-04-2022 by Amol
	//to notify Admin if last Incharge of that office changed who granted the application
	$("#add_appl").click(function(e){

		e.preventDefault();
		
		var customer_id = $('#customer_id').val();

		if (customer_id != '') {
			
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
	
						$.confirm({
							title: 'Note:',
							content: "The incharge who granted this application has been changed, So application will forwarded to current in-charge <b>"+res+"</b> for re-esign.",
							columnClass: 'medium',
							type: 'dark',
							buttons: {
								proceed: function () {
									$("#add_reesign_form").submit();
								},
								cancel: function () {
									$.alert("The application is not added for re-esign, as you have cancel the allocation");
									
								}
							}
						});
	
					}else{
						$("#add_reesign_form").submit();
					}
				}			
			});
		} else {
			$.alert("Please Enter the Application ID !");
		}
		
		
	});
});
