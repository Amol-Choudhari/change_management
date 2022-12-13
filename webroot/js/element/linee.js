var advancePaymentStatus = $('#advPaymentStatus_id').val();
var cr = $('#chemist_registration_id').val();
var ca = $('#chemist_approval_id').val();
var scic = $('#set_chemist_in_charge_id').val();
var appal = $('#attach_pp_lab_id').val();

alert(cr);

if (aps != '') {

    if (advancePaymentStatus == 'pending') {
        $("#aps").addClass("active");
    }

    if(advancePaymentStatus == 'confirmed'){
        $("#apa").addClass("active");
    }
   
}

if (cr != '') {
    $("#cr").addClass("active");
}

if (ca != '') {
    $("#ca").addClass("active");
}
if (scic != '') {
    $("#scic").addClass("active");
}
if (appal != '') {
    $("#appal").addClass("active");
}
