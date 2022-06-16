$("#commodity_category").change(function(){

	get_commodity();
});


function disableEnableBevoNonBevoOption(){

	$('#selected_commodity option').prop('selected', true);
	$("#selected_commodity option[value='']").prop('selected', false);


	if(certificationType == 1){

		if(formStatus == ''){

			$('#next_btn').attr('style', 'display: none !important');
			$('#mpayment').attr('style', 'display: none !important');
			$('#sectionpayment').attr('style', 'display: none !important');

		}else{

			$('#next_btn').removeAttr('style');
			$('#mpayment').removeAttr('style');
			$('#sectionpayment').removeAttr('style');
		}

		if($('#commodity_category').val() =='106'){

			//$("#commodity_category option[value!='106']").remove();
			$("#commodity_category option[value!='106']").prop('disabled', true);
			//$("#selected_commodity").append($('#commodity option:selected'));

			$("#selected_bevo_nonbevo_msg").show().text("Form E commodity selected, Form A commodities cannot be selected");
			$("#selected_bevo_nonbevo_msg").css({"color":"red","font-size":"12px","font-weight":"500","text-align":"right"});
			get_commodity();
		}
		else{
			//$("#commodity_category option[value='106']").remove();
			$("#commodity_category option[value='106']").prop('disabled', true);
			//$("#selected_commodity").append($('#commodity option:selected'));

			$("#selected_bevo_nonbevo_msg").show().text("Form A commodity selected, Form E commodities cannot be selected");
			$("#selected_bevo_nonbevo_msg").css({"color":"red","font-size":"12px","font-weight":"500","text-align":"right"});
		}
	}

	$('#selected_commodity').change(function () {

		if($(this).find('option:selected').val() != '')
		{
			var commodity_id = $(this).find('option:selected').val();
			var commodity_name = $(this).find('option:selected').text();

			$('#commodity').append("<option value='"+commodity_id+"'>"+commodity_name+"</option>");

			$("#commodity").append($("#commodity option:gt(0)").sort(function (a, b) {return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;}));
			$(this).find('option:selected').remove();
		}

		$("#selected_commodity option[value!='']").prop('selected',true);

	});

	$('#commodity').change(function (e) {
			e.preventDefault();
			var commodityPresent = 'no';
			$('#selected_commodity option').each(function(){

				if($(this).val() == $('#commodity option:selected').val()){
					commodityPresent = 'yes';
				}
			});
			if(commodityPresent == 'no'){
				$("#selected_commodity").append($('#commodity option:selected'));
			}else{
				$(this).find('option:selected').remove();
			}
	});
}

function get_commodity(){

	$("#commodity").find('option').remove();
	var commodity = $("#commodity_category").val();
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
			}
	});
}




var certificationType = "<?php echo $firm_details['certification_type'] ?>";
var formStatus = "<?php echo $section_form_details[0]['form_status'] ?>";

$(document).ready(function() {
    disableEnableBevoNonBevoOption();
});
