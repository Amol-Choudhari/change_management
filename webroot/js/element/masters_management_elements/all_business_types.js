$(document).ready(function(){
	$("#printing_view").hide();
	$("#crush_ref_view").hide();
	$("#ca_btn").click(function(){
		$("#ca_view").show();
		$("#printing_view").hide();
		$("#crush_ref_view").hide();
	});

	$("#printing_btn").click(function(){
		$("#printing_view").show();
		$("#ca_view").hide();
		$("#crush_ref_view").hide();

	});

	$("#crush_ref_btn").click(function(){
		$("#crush_ref_view").show();
		$("#printing_view").hide();
		$("#ca_view").hide();
	});
});

$('.delete_buisness_type').click(function (e) { 

	if (confirm('Are you sure to Delete this Buisness Type record ?')) {
		////
	} else {
		return false;
		exit;
	}
	
});
