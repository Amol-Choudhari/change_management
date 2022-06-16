var masterId = $('#master_id_for_office').val();
var form_id = $('#form_id')


$('#ro_reallocate_btn').click(function (e) { 
	
	if (masters_validation(masterId) == false) {
		e.preventDefault();
	} else {
		form_id.submit();
	}	
});

$('#edit_ro_office').click(function (e) { 
	
	if (masters_validation(masterId) == false) {
		e.preventDefault();
	} else {
		form_id.submit();
	}	
});


$("#ro_reallocate_btn").click(function(){

	if(confirm('Please confirm you are sure to Reallocate Office In-charge? \nOnce Reallocated, All applications of this office will be avaiiable to newly appointed office In-charge.')){

	}else{return false;}
});

$("#save").click(function(e){

	if(add_firm_validations()==false){
		e.preventDefault();
	}else{
		$("#add_firm_form").submit();
	}

});


//for already checked
	if($('#current_type').val()=='RO'){
		$("#ro_office_list").hide();
	}

	if($('#current_type').val()=='SO'){
		$("#ro_office_list").show();
	}


	//when clicked
	$('#office_type-ro').click(function(){
		$("#ro_office_list").hide();
	});

	$('#office_type-so').click(function(){
		$("#ro_office_list").show();
	});
