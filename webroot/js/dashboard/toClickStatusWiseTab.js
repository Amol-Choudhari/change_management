
$(document).ready(function(){
	
	var listForValue = $("#listForValue").val();
	

	//status tab
	if (listForValue=='pending') {
		$("#pending_count_box").click();
	}
	if (listForValue=='replied') {
		
		$("#replied_count_box").click();
	}
	if (listForValue=='ref_back') {
		
		$("#replied_count_box").click();
	}
	if (listForValue=='allocation') {
		
		$("#allocations_count_box").click();
	}
	
	
	
});