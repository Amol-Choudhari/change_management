$("#primary_listing_table").hide();
	$("#firms_listing_table").hide();

	$("#primary_list").click(function(){

		$("#primary_listing_table").show();
		$("#firms_listing_table").hide();

	});

	$("#firms_list").click(function(){

		$("#primary_listing_table").hide();
		$("#firms_listing_table").show();

	});
	
$(".table").dataTable();