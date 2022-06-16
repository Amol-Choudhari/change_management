
$("#category").change(function(){

	get_commodity();
});

$(".close").click(function(){
		$("#update_commodity_Modal").hide();
	});

$("#comm_open_popup_btn").click(function(e){

		e.preventDefault();
		$("#update_commodity_Modal").show();
	});

//to change/update commodity
$('#comm_update_btn').click(function(e){
	e.preventDefault();

	//select all selected commodities in the list
	$("#selected_commodity option[value!='']").prop('selected',true);

	//check non empty validation
	if($("#category").val()=='' || $("#selected_commodity").val()=='' || $("#updt_comm_remark").val()==''){

		alert('Please check all fields are properly selected and remark entered.');
		return false;
	}

	var selected_commodity = $("#selected_commodity").val();
	var remark = $("#updt_comm_remark").val();

	$.ajax({
			type: "POST",
			async:true,
			url:"../AjaxFunctions/update_commodity_call",
			data:({selected_commodity:selected_commodity,remark:remark}),
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function (data) {

				var response = data.match(/~([^']+)~/)[1];

				if(response=='done'){
					alert('Commodities updated successfully.');
					location.reload();
				}

			}
	});
});


function get_commodity(){

	$("#commodity").find('option').remove();
	var commodity = $("#category").val();

	$.ajax({
			type: "POST",
			async:true,
			url:"../AjaxFunctions/show-commodity-dropdown",
			data: {commodity:commodity},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function (data) {
				$("#commodity").append(data);

				//to hide already selected commodities from list, to avoid duplicate selection
				$('#selected_commodity option').each(function() {

					if($(this).val() != ''){
						$('#commodity option[value="'+$(this).val()+'"]').remove();
					}

				});
			}
	});



}


$('#commodity').change(function (e) {
			e.preventDefault();

	$("#selected_commodity").append($('#commodity option:selected'));

});


$('#selected_commodity').change(function () {

	if($(this).find('option:selected').val() != '')
	{
		var commodity_id = $(this).find('option:selected').val();
		var commodity_name = $(this).find('option:selected').text();

		$('#commodity').append("<option value='"+commodity_id+"'>"+commodity_name+"</option>");

		$("#commodity").append($("#commodity option:gt(0)").sort(function (a, b) {return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;}));
		$(this).find('option:selected').remove();

	}

	if($('#selected_commodity option').length == 1){
		$("#commodity_category option").prop('disabled', false);
	}

	 $("#selected_commodity option[value!='']").prop('selected',true);

});


//below script for printing press packing type update/change


//to hide already selected packing types from list, to avoid duplicate selection
	$('#selected_packing_types option').each(function() {

		if($(this).val() != ''){
			$('#packing_types option[value="'+$(this).val()+'"]').remove();
		}

	});

// To append selected Packaing types in mutiple selected box from list

	$('#packing_types').change(function () {

		var type_value = $('#packing_types option:selected').val();

		$("#selected_packing_types").append($('#packing_types option:selected'));

	});


	// To remove selected Packaing types in mutiple selected box from list
	//and append in the packing types list again
	$('#selected_packing_types').change(function () {

		if($(this).find('option:selected').val() != '')
		{
			var packing_type_id = $(this).find('option:selected').val();
			var packing_type = $(this).find('option:selected').text();

			$('#packing_types').append("<option value='"+packing_type_id+"'>"+packing_type+"</option>");

			$("#packing_types").append($("#packing_types option:gt(0)").sort(function (a, b) {return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;}));
			$(this).find('option:selected').remove();

		}

		$(this).find('option:selected').remove();

		 $('#selected_packing_types option').prop('selected',true);

	});


//to change/update packing types
$('#packtype_update_btn').click(function(e){

	e.preventDefault();

	//select all selected commodities in the list
	$("#selected_packing_types option[value!='']").prop('selected',true);

	//check non empty validation
	if($("#selected_packing_types").val()=='' || $("#updt_packtype_remark").val()==''){

		alert('Please check all fields are properly selected and remark entered.');
		return false;
	}

	var selected_packing_types = $("#selected_packing_types").val();
	var remark = $("#updt_packtype_remark").val();

	$.ajax({
			type: "POST",
			async:true,
			url:"../AjaxFunctions/update_packing_type_call",
			data:({selected_packing_types:selected_packing_types,remark:remark}),
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function (data) {

				var response = data.match(/~([^']+)~/)[1];
				if(response=='done'){
					alert('Packing Types updated successfully.');
					location.reload();
				}

			}
	});
});
