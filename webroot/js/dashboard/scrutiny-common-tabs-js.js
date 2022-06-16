
$(".loader").hide();
$("#ro_tabs_div li a").click(function() {
$("#ro_tabs_div li").removeClass('active');
$(this).parent().addClass('active');
});

//to get and append counts in scutiny office sub tabs
//the countarray is alreay declared in previous script as global
$("#with_nodal_office_count").text(countarray['scrutiny_with_nodal_office'][maintabid]);
$("#with_reg_office_count").text(countarray['scrutiny_with_reg_office'][maintabid]);
$("#with_ho_office_count").text(countarray['scrutiny_with_ho_office'][maintabid]);


//ajax to fetch listing for scrutiny with nodal officer
$("#with_nodal_office_tab").click(function(){
	$("#level_1_common_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/scrutiny_with_nodal_office_Tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_1_common_applications_list").show();
					$("#level_1_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for scrutiny with Reg. Office
$("#with_reg_office_tab").click(function(){
	$("#level_1_common_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/scrutiny_with_reg_office_Tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_1_common_applications_list").show();
					$("#level_1_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for scrutiny with HO QC
$("#with_ho_office_tab").click(function(){
	$("#level_1_common_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/scrutiny_with_ho_office_Tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_1_common_applications_list").show();
					$("#level_1_common_applications_list").html(data);
			}
	});
});
