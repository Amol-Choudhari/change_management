$(".loader").hide();
$("#ho_tabs_div li a").click(function() {
$("#ho_tabs_div li").removeClass('active');
$(this).parent().addClass('active');
});

//to get and append counts in HO sub tabs
//the countarray is alreay declared in previous script as global
$("#for_ho_scrutiny_count").text(countarray['for_ho_scrutiny'][maintabid]);
$("#for_dyama_count").text(countarray['for_dy_ama'][maintabid]);
$("#for_jtama_count").text(countarray['for_jt_ama'][maintabid]);
$("#for_ama_count").text(countarray['for_ama'][maintabid]);


function fetch_ho_list_ajax(list_for){
	
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/fetch_ho_level_lists",
		data:{list_for:list_for},
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#ho_level_common_applications_list").show();
				$("#ho_level_common_applications_list").html(data);
		}
	});
	
}
//ajax to fetch listing for scrutiny
$("#for_ho_scrutiny_tab").click(function(){
	$("#ho_level_common_applications_list").hide();
	var list_for = 'ho_scrutiny';
	
	fetch_ho_list_ajax(list_for);
});

//ajax to fetch listing for Dy AMA
$("#for_dyama_tab").click(function(){
	$("#ho_level_common_applications_list").hide();
	var list_for = 'dy_ama';
	
	fetch_ho_list_ajax(list_for);
});

//ajax to fetch listing for Jt AMA
$("#for_jtama_tab").click(function(){
	$("#ho_level_common_applications_list").hide();
	var list_for = 'jt_ama';
	
	fetch_ho_list_ajax(list_for);
});

//ajax to fetch listing for AMA
$("#for_ama_tab").click(function(){
	$("#ho_level_common_applications_list").hide();
	var list_for = 'ama';
	
	fetch_ho_list_ajax(list_for);
});
