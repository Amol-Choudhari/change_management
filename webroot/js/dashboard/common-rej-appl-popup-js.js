$(".close").click(function(){		
		$("#common_reject_Modal").hide();
	});

//for scrutiny allocation
$('#reject_appl_btn').click(function(){
	
	var appl_type = $("#rej_appl_type").val();
	var customer_id = $("#rej_customer_id").val();
	var remark = $("#rej_remark").val();
	
	if(remark==''){
		
		alert('Please enter remark/reason for this rejection');
		return false;
	}
	
	if(confirm('Are you sure to reject this application')){
	
		$.ajax({
				type: "POST",
				async:true,
				url:"../dashboard/reject_application",
				data:({customer_id:customer_id,appl_type:appl_type,remark:remark}),
				beforeSend: function (xhr) { // Add this line
						$(".loader").show();$(".loadermsg").show();
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function (data) {

						$(".loader").hide();$(".loadermsg").hide();
						$("#common_reject_Modal").hide();
						alert("The Application "+customer_id+" is successfully Rejected.");
						
						var from_sub_tab = data.match(/~([^']+)~/)[1];
						
						//to reload list after application rejected from popup
						if(from_sub_tab=='with_applicant'){								
							$('#with_applicant_tab').click();
							
						}else if(from_sub_tab=='scrutiny'){									
							$('#scrutiny_tab').click();
							
						}else if(from_sub_tab=='reports'){									
							$('#reports_tab').click();
							
						}else if(from_sub_tab=='with_sub_office'){									
							$('#with_sub_offs_tab').click();
							
						}else if(from_sub_tab=='with_reg_office'){									
							$('#with_reg_offs_tab').click();
							
						}else if(from_sub_tab=='with_ho_office'){									
							$('#with_ho_offs_tab').click();
							
						}
				}
		});
	
	}else{
		
		return false;
	}
});	