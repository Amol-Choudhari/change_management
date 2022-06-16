$(document).ready(function () {

    $('#validity_upto').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true
    });

    $('#renew_upto').datepicker({
        format: "dd/mm/yyyy",
        startDate: new Date()

    });

    if($('#is_particulars_furnished-yes').is(":checked")){

        $("#renew_last_validity_period").show();

    }else if($('#is_particulars_furnished-no').is(":checked")){

        $("#renew_last_validity_period").hide();
    }

    //for Onclick option
    $('#is_particulars_furnished-yes').click(function(){

        $("#renew_last_validity_period").show();
    });

    $('#is_particulars_furnished-no').click(function(){

        $("#renew_last_validity_period").hide();

    });

});


var final_renewal_submit_status = $('#final_renewal_submit_status_id').val();
