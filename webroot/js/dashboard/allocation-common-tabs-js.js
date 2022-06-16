$(".loader").hide();
$("#ro_tabs_div li a").click(function() {
$("#ro_tabs_div li").removeClass('active');
$(this).parent().addClass('active');
});

//to get and append counts in allocation sub tabs
//the countarray is alreay declared in previous script as global
$("#for_scrutiny_allocation_count").text(countarray['scrutiny_allocation_tab']);
$("#for_scrutiny_of_so_appl_count").text(countarray['scrutiny_allocation_by_level4ro_tab']);
$("#for_inspection_allocation_count").text(countarray['inspection_allocation_tab']);

//ajax to fetch listing for Allocation to scrutiny
$("#for_scrutiny_allocation_tab").click(function(){
	$("#allocation_common_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/allocation_for_scrutiny_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#allocation_common_applications_list").show();
					$("#allocation_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for allocation of Siteinspection
$("#for_inspection_allocation_tab").click(function(){
	$("#allocation_common_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/allocation_for_inspection_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#allocation_common_applications_list").show();
					$("#allocation_common_applications_list").html(data);
			}
	});
});



//ajax to fetch listing for scrutiny allocation by Ro for SO appli.
$("#for_scrutiny_of_so_appl_tab").click(function(){
	$("#allocation_common_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/allocation_for_scrutiny_by_level4_ro_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#allocation_common_applications_list").show();
					$("#allocation_common_applications_list").html(data);
			}
	});
});
