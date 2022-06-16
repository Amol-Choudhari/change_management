var add_master_btn_master_id = $('#add_master_btn_master_id').val();
var form_id = $('#form_id').val();
$("#add_master_btn").click(function(e){

	if(masters_validation(add_master_btn_master_id) == false){
		e.preventDefault();
	}else{
		form_id.submit();
	}

});
