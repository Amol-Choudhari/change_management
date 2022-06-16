var duplicate_certification_no_msg =$("#duplicate_certification_no_msg").val();

if(duplicate_certification_no_msg !=''){
	$('#is_already_granted-yes').prop('checked', true);
	$("#old_granted_certificate").show();
	$("#total_charge_box").hide();//added on 28-11-2017 by Amol to hide if old application
}