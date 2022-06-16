
$(".loader").hide();
$("#ro_tabs_div li a").click(function() {
$("#ro_tabs_div li").removeClass('active');
$(this).parent().addClass('active');
});

//to get and append counts in Nodal office sub tabs
//the countarray is alreay declared in previous script as global
$("#with_applicant_tab_count").text(countarray['with_applicant'][maintabid]);
$("#scrutiny_tab_count").text(countarray['scrutiny'][maintabid]);
$("#reports_tab_count").text(countarray['reports'][maintabid]);


var ro_so_session_level = $("#ro_so_session_level").val();
var ro_so_level_3_for = $("#ro_so_level_3_for").val();

if(ro_so_session_level=='level_3' && ro_so_level_3_for=='RO'){
	$("#with_sub_offs_tab_count").text(countarray['with_sub_office'][maintabid]);
	$("#with_ho_offs_tab_count").text(countarray['with_ho_office'][maintabid]);
}else if(ro_so_session_level=='level_3' && ro_so_level_3_for=='SO'){
	$("#with_reg_offs_count").text(countarray['with_reg_office'][maintabid]);
}



//ajax to fetch listing for RO/SO with MO/SMO
$("#with_applicant_tab").click(function(){
	$("#level_3_common_applications_list").hide();
	$("#level_3_all_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/with_applicant_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_3_common_applications_list").show();
					$("#level_3_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for RO/SO with MO/SMO
$("#scrutiny_tab").click(function(){
	$("#level_3_common_applications_list").hide();
	$("#level_3_all_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/scrutiny_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_3_common_applications_list").show();
					$("#level_3_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for RO/SO with IO reports
$("#reports_tab").click(function(){
	$("#level_3_common_applications_list").hide();
	$("#level_3_all_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/reports_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_3_common_applications_list").show();
					$("#level_3_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for RO with SO officer
$("#with_sub_offs_tab").click(function(){
	$("#level_3_common_applications_list").hide();
	$("#level_3_all_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/with_sub_offs_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_3_common_applications_list").show();
					$("#level_3_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for SO to Ro officer
$("#with_reg_offs_tab").click(function(){
	$("#level_3_common_applications_list").hide();
	$("#level_3_all_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/with_reg_offs_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_3_common_applications_list").show();
					$("#level_3_common_applications_list").html(data);
			}
	});
});

//ajax to fetch listing for RO/SO with HO office
$("#with_ho_offs_tab").click(function(){
	$("#level_3_common_applications_list").hide();
	$("#level_3_all_applications_list").hide();
	$.ajax({
			type: "POST",
			async:true,
			url:"../dashboard/with_ho_offs_tab",
			beforeSend: function (xhr) { // Add this line
					$(".loader").show();$(".loadermsg").show();
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			}, 
			success: function (data) {
					$(".loader").hide();$(".loadermsg").hide();
					$("#level_3_common_applications_list").show();
					$("#level_3_common_applications_list").html(data);
			}
	});
});
