$(document).ready(function () {
	$('#bharatkosh_payment_done-yes').click(function(){
		
		if (!$('#ren_intimation').is(':checked')) {
			
			alert('Please check the renewal intimation consent box');			
			$('#bharatkosh_payment_done-yes').prop('checked', false);
			
			$("#payment_details").hide();
			$("#submit_payment_detail").hide();	
		}else{
			$("#payment_details").show();
			$("#submit_payment_detail").show();
		}
		
		if($("#late_remark").val() == ''){
			
			alert('Please enter remark/reason for late application of renewal');			
			$('#bharatkosh_payment_done-yes').prop('checked', false);
			
			$("#payment_details").hide();
			$("#submit_payment_detail").hide();	
		}
	});

	$('#bharatkosh_payment_done-no').click(function(){
		$("#payment_details").hide();
		$("#submit_payment_detail").hide();	
	});

	$('#ren_intimation').change(function(){

		if (!$('#ren_intimation').is(':checked')) {
			
			$('#bharatkosh_payment_done-yes').prop('checked', false);			
			$("#payment_details").hide();
			$("#submit_payment_detail").hide();	
		}
		
	});

});