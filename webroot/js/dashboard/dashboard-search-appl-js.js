//search application ajax call
//added on 25-10-2021 by Amol

$("#applSearchInPopup").hide();
$("#search_appl_btn").click(function(){

    var applicant_id = $("#srch_appl_id").val();
	
	if(applicant_id==''){
		$.alert("Please Enter Application Id");
	}else{
		$.ajax({
				type: "POST",
				async:true,
				data:{applicant_id:applicant_id},
				url:"../AjaxFunctions/search_application",
				beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
				}, 
				success: function (response) {
					
					$("#applSearchInPopup").show();
					$("#srch_appl_content").html(response);
				}
		});
	}

});

$(".close").click(function(){
	
	$("#applSearchInPopup").hide();
    $("#srch_appl_content").html('');
				
});