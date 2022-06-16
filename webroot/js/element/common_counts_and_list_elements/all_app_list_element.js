$(document).ready(function() {
    $('#all_pending_app').DataTable({
		"paging": false,
	   "ordering": false,
	   "searching": false,
	   "bInfo": false
	});
	$('#all_reports_filed').DataTable({
		"paging": false,
	   "ordering": false,
	   "searching": false,
	   "bInfo": false
	});
	$('#all_ref_back_app').DataTable({
		"paging": false,
	   "ordering": false,
	   "searching": false,
	   "bInfo": false
	});
	$('#all_replied_app').DataTable({
		"paging": false,
	   "ordering": false,
	   "searching": false,
	   "bInfo": false
	});
	$('#all_approved_app').DataTable({
		"paging": false,
	   "ordering": false,
	   "searching": false,
	   "bInfo": false
	});
	$('#all_rejected_app').DataTable({
		"paging": false,
	   "ordering": false,
	   "searching": false,
	   "bInfo": false
	});
	
	
	$(".allocate").click(function(){		
		$("#scrutiny_alloction_Modal").show();
	});
	$(".reallocate").click(function(){		
		$("#scrutiny_alloction_Modal").show();
	});
	
	$(".close").click(function(){		
		$("#scrutiny_alloction_Modal").hide();
	});
	
	$('.io_scheduled_date').datepicker({
		format: "dd/mm/yyyy",
		autoclose: true,
		startDate: new Date(),
		clearBtn: true
	
	});
	
	

});